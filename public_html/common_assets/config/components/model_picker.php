
<script>
    let pp_model_template = `
<div>
    <div class="input-group input-group-sm">
        <input :title=l_model :value="l_model" @input="input_update($event)" type="text" class="form-control">
        <span class="input-group-append">
            <button @click='show_fm()' type="button" class="btn btn-sm btn-outline-info">
                <span class="fa fa-folder-open"></span><span v-if="!short_btn">{{$options.lang['choose_file']}}</span>
            </button>
            <a :title="$options.lang['download_model']" class="btn btn-sm btn-outline-info" v-show="this.l_model" download :href="correct_download_url(this.l_model)"><span class="fa fa-download"></span></a>
        </span>

        <div class="pp_modal">
            <div ref="fm" class="modal inmodal material_filemanager" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">{{$options.lang['ok']}}</span></button>
                            <h5 class="modal-title">{{$options.lang['choose_file']}}</h5>
                        </div>
                        <div class="modal-body">

                            <?php if (isset($common) && $common == 1) : ?>
                                <filemanager :type="'common'" :mode="'models'" ref="m_fileman" @select_file="m_sel_file($event)"></filemanager>
                            <?php else: ?>
                                <filemanager :mode="'models'" ref="m_fileman" @select_file="m_sel_file($event)"></filemanager>
                            <?php endif; ?>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal">{{$options.lang['ok']}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    `


    Vue.component('pp_model', {
        template: pp_model_template,
        props: {
            model: {
                type: String,
                default: ''
            },
            short_btn: {
                type: Boolean,
                default: false
            }
        },
        data: function () {
            return {
                l_model: ''
            };
        },
        computed: {},
        beforeCreate: function () {
            this.$options.lang = glob.lang;
        },
        created: function () {
            this.l_model = this.model;
            console.log(this.model)
        },
        mounted: function () {
        },
        methods: {
            correct_model_url: function (path) {
                if (path.indexOf('common_assets') > -1) {
                    return path.split('/').pop()
                } else {
                    return path
                }
            },
            correct_download_url(path) {
                if (path.indexOf('common_assets') > -1) {
                    if(path[0]!='/') {path = '/' + path}
                    return path
                } else {
                    return glob.acc_url + path
                }
            },
            show_fm: function () {
                $(this.$refs.fm).modal('show');
            },
            input_update(event) {
                this.l_model = event.target.value;
                this.$emit('input', event.target.value)
            },
            emit_update: function () {
                this.$emit('input', this.l_model);
            },
            m_sel_file: async function (file) {
                let tp = file.true_path;
                if (tp.indexOf('common_assets') < 0) tp = tp.substr(1)
                this.l_model = tp
                this.emit_update();
                $(this.$refs.fm).modal('hide');
            }
        }
    })

</script>