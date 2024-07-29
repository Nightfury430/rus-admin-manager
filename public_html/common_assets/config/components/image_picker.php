<script>
    let pp_image_tmplt = `
<div>
    <div>
        <div class="pp_image_icon_block">
            <div :style="{ width: p_width + 'px', height: p_height + 'px' }" :height="p_height" @click="show_fm" v-show="url == ''" class="pp_img_placeholder">
                {{t_width}}x{{t_height}}px
                <i @click="show_fm" class="fa fa-folder-open open_file" aria-hidden="true"></i>
            </div>
            <div v-show="url != ''">
                <img @click="show_fm" :style="max_width"  :src="correct_url(url)" alt="">
                <i @click="show_fm" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                <i v-if="url != ''" @click="remove_image" class="fa fa-trash delete_file" aria-hidden="true"></i>
            </div>
        </div>

        <div class="pp_modal">
            <div ref="fm" class="modal inmodal image_filemanager" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                            <h5 class="modal-title">Выбрать файл</h5>
                        </div>
                        <div class="modal-body">

                            <?php if (isset($common) && $common == 1) : ?>
                                <filemanager :type="'common'" :mode="'images'" ref="m_fileman" @select_file="m_sel_file($event)"></filemanager>
                            <?php else: ?>
                                <filemanager :mode="'images'" ref="m_fileman" @select_file="m_sel_file($event)"></filemanager>
                            <?php endif; ?>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
`

    Vue.component('pp_image', {
        template: pp_image_tmplt,
        props: {
            src: {
                type: String,
                default: ''
            },
            p_width: {
                type: Number,
                default: 78
            },
            p_height: {
                type: Number,
                default: 78
            },
            t_width: {
                type: Number,
                default: 256
            },
            t_height: {
                type: Number,
                default: 256
            }
        },

        data: function () {
            return {
                url: '',
                show_modal: 0
            };
        },

        created: function () {
            this.url = this.src;
        },

        mounted: function () {

        },

        computed: {
            max_width: function () {
                return {
                    'max-width': this.p_width + 'px'
                }
            }
        },

        methods: {
            show_fm: function () {
                this.show_modal = 1;
                $(this.$refs.fm).modal('show');
            },

            emit_update() {
                this.$emit('e_update', copy_object(this.url));
            },

            remove_image: function () {
                this.url = '';
                this.emit_update();
            },

            m_sel_file: async function (file) {

                let tp = file.true_path;
                if (tp.indexOf('common_assets') < 0) {
                    tp = tp.substr(1)
                }

                this.url = tp;

                this.emit_update();

                $(this.$refs.fm).modal('hide');
                this.show_modal = 0;
            },
            correct_url: function (path) {
                if (path == '' || path == null) return '/common_assets/images/placeholders/256x256.png'

                let date_time = new Date().getTime();

                if (path.indexOf('common_assets') > -1) {
                    return path + '?' + date_time
                } else {

                    if(path.indexOf('http') > -1){
                        return path + '?' + date_time;
                    }

                    return glob.acc_url + path + '?' + date_time;
                }
            },
        }
    })
</script>

<style>

    .pp_image_icon_block{
        display: inline-block;
        position: relative;
    }

    .pp_image_icon_block .open_file {
        cursor: pointer;
        position: absolute;
        right: -35px;
        top: 0;
        font-size: 25px;
        color: #1c84c6;
    }

    .pp_image_icon_block .delete_file {
        top: -1px;
        cursor: pointer;
        position: absolute;
        right: -60px;
        font-size: 25px;
        color: #ed5565;
    }

    .pp_img_placeholder{
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #e9e9e9;
    }
</style>