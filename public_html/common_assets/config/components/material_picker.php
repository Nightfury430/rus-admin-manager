



<script src="/common_assets/libs/vue/colorpicker/colorpicker.min.js"></script>

<script>
    let pp_mat_tmplt = `
<div>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Цвет</label>
                    <div class="col-6">
                        <color-picker  v-if="item.params.color" @change="update_color" v-model="item.params.color"></color-picker>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['roughness'] ?></label>
                    <div class="col-6"><input @input="update_material" v-model="item.params.roughness" type="number" min="0" max="1" step="0.01" class="form-control form-control-sm"></div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['metalness'] ?></label>
                    <div class="col-6"><input @input="update_material" v-model="item.params.metalness" type="number" min="0" max="1" step="0.01" class="form-control form-control-sm"></div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['transparent'] ?></label>
                    <div class="col-6">
                        <label class="switch">
                            <input @change="update_material" :true-value="1" :false-value="0" v-model="item.params.transparent" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div v-show="item.params.transparent == 1" class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['opacity'] ?></label>
                    <div class="col-6"><input @input="update_material" v-model="item.params.opacity" type="number" min="0" max="1" step="0.01" class="form-control form-control-sm"></div>
                </div>
                <div v-show="item.params.transparent == 1" class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['alphamap'] ?></label>
                    <div class="col-sm-6">
                        <div class="icon_block">
                            <img @click="show_fm('alphaMap')" style="max-width: 78px" :src="correct_url(item.params.alphaMap)" alt="">
                            <i @click="show_fm('alphaMap')" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                            <i v-if="check_texture('alphaMap')" @click="remove_texture('alphaMap')" class="fa fa-trash delete_file" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['texture_file'] ?></label>
                    <div class="col-sm-6">
                        <div class="icon_block">
                            <img @click="show_fm('map')" style="max-width: 78px" :src="correct_url(item.params.map)" alt="">
                            <i @click="show_fm('map')" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                            <i v-if="check_texture('map')" @click="remove_texture('map')" class="fa fa-trash delete_file" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>

                <div v-show="has_map" class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['wrapping_type'] ?></label>
                    <div class="col-sm-6">
                        <select @change="update_material" v-model="item.add_params.wrapping" class="form-control">
                            <option value="mirror"><?php echo $lang_arr['wrapping_type_mirror'] ?></option>
                            <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat'] ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['normalMap'] ?></label>
                    <div class="col-sm-6">
                        <div class="icon_block">
                            <img @click="show_fm('normalMap')" style="max-width: 78px" :src="correct_url(item.params.normalMap)" alt="">
                            <i @click="show_fm('normalMap')" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                            <i v-if="check_texture('normalMap')" @click="remove_texture('normalMap')" class="fa fa-trash delete_file" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>

                <div v-show="has_normal_map" class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['wrapping_type'] ?></label>
                    <div class="col-sm-6">
                        <select @change="update_material" v-model="item.add_params.normal_wrapping" class="form-control">
                            <option value="mirror"><?php echo $lang_arr['wrapping_type_mirror'] ?></option>
                            <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat'] ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['roughnessMap'] ?></label>
                    <div class="col-sm-6">
                        <div class="icon_block">
                            <img @click="show_fm('roughnessMap')" style="max-width: 78px" :src="correct_url(item.params.roughnessMap)" alt="">
                            <i @click="show_fm('roughnessMap')" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                            <i v-if="check_texture('roughnessMap')" @click="remove_texture('roughnessMap')" class="fa fa-trash delete_file" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <div v-show="has_roughness_map" class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['wrapping_type'] ?></label>
                    <div class="col-sm-6">
                        <select @change="update_material" v-model="item.add_params.roughness_wrapping" class="form-control">
                            <option value="mirror"><?php echo $lang_arr['wrapping_type_mirror'] ?></option>
                            <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat'] ?></option>
                        </select>
                    </div>
                </div>

                <div v-show="has_map" class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['material_rotation'] ?></label>
                    <div class="col-sm-6">
                        <select @change="update_material" v-model="item.add_params.rotation" class="form-control">
                            <option value="normal">По умолчанию</option>
                            <option value="rotated">Повернуто</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['stretch_width'] ?></label>
                    <div class="col-6">
                        <label class="switch">
                            <input @change="update_material" :true-value="1" :false-value="0" v-model="item.add_params.stretch_width" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['stretch_height'] ?></label>
                    <div class="col-6">
                        <label class="switch">
                            <input @change="update_material" :true-value="1" :false-value="0" v-model="item.add_params.stretch_height" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['texture_real_width'] ?></label>
                    <div class="col-6"><input @input="update_material" v-model.number="item.add_params.real_width" type="number" min="1"  step="1" class="form-control form-control-sm"></div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['texture_real_heght'] ?></label>
                    <div class="col-6"><input @input="update_material" v-model.number="item.add_params.real_height" type="number" min="1"  step="1" class="form-control form-control-sm"></div>
                </div>

            </div>
        </div>

     <div class="pp_modal">
        <div ref="fm" class="modal inmodal material_filemanager" v-show="show_modal == 1"  tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                        <h5 class="modal-title">Выбрать файл</h5>
                    </div>
                    <div class="modal-body">

                     <?php if (isset($common) && $common == 1) :?>
                           <filemanager :type="'common'" :mode="'images'" ref="m_fileman" @select_file="m_sel_file($event)"></filemanager>
                    <?php else:?>
                         <filemanager :mode="'images'" ref="m_fileman" @select_file="m_sel_file($event)"></filemanager>
                    <?php endif;?>


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

    let dm_params = {
        type: 'Standard',
        params: {
            icon: null,
            alphaMap: null,
            // aoMap: null,
            // aoMapIntensity: 1,
            // bumpMap: null,
            // bumpScale: 1,
            color: '#ffffff',
            // displacementMap: null,
            // displacementScale: 1,
            // displacementBias: 0,
            // emissive: '#000000',
            // emissiveIntensity: 1,
            // emissiveMap: null,
            // envMap: null,
            // envMapIntensity: 1,
            // flatShading: false,
            // fog: true,
            // lightMap: null, //second set of UVs,
            // lightMapIntensity: 1,
            map: null,
            metalness: 0,
            // metalnessMap: null,
            normalMap: null,
            // normalScale: {x:1, y:1}, //Vec2
            roughness: 1,
            roughnessMap: null,
            transparent: 0,
            opacity: 1,
        },
        add_params: {
            real_width: 256,
            real_height: 256,
            rotation: 'normal',
            stretch_width: 0,
            stretch_height: 0,
            normal_scale: false,
            wrapping: 'mirror',
            normal_wrapping: 'mirror',
            roughness_wrapping: 'mirror',
        },
    }
    let dm_item = {
        id: 0,
        name: '',
        code: '',
        order: 100000,
        category: "0",
        active: 1,
        params: dm_params.params,
        add_params: dm_params.add_params,
        type: 'Standard'
    }
    Vue.component('pp_material', {
        template: pp_mat_tmplt,
        props: {
            params_obj: {
                type: Object,
                default: {}
            },
            comp_id: {
                type: String,
                default: ''
            }
        },
        computed: {
            has_texture: function () {
                return this.item.params.map || this.item.params.normalMap || this.item.params.alphaMap || this.item.params.roughnessMap
            },
            has_map: function () {
                return !!this.item.params.map
            },
            has_normal_map: function () {
                return!!this.item.params.normalMap
            },
            has_roughness_map: function () {
                return!!this.item.params.roughnessMap
            }
        },
        data: function () {
            return {
                item: {},
                show_modal: 1,
                file_target: {}
            };
        },
        beforeCreate: function(){
            Vue.use(ColorPanel)
            Vue.use(ColorPicker)
        },

        created: function () {
            Vue.set(this, 'item', _.merge(copy_object(dm_params), this.params_obj))
            let scope = this;
            this.$root.$on('set_item_params' + scope.comp_id, function (data) {
                console.log(scope.comp_id)

                Vue.set(scope, 'item', _.merge(copy_object(dm_params), data))
            })

        },
        mounted: function(){
            let els = document.getElementsByClassName('one-colorpicker');
            for (let i = 0; i < els.length; i++){
                els[i].style.border = '1px solid #e5e6e7'
            }
        },
        methods: {

            get_params: function(){
                return JSON.parse(JSON.stringify(this.item));
            },
            show_fm: function (file_target) {
                this.file_target = file_target;
                $(this.$refs.fm).modal('show');
            },
            update_material:function(){
                this.$emit('e_update', copy_object(this.item));
            },

            update_color: function(){
                this.$emit('e_update_color', copy_object(this.item.params.color));
            },

            remove_texture: function(key){
                this.item.params[key] = null;
                this.update_material();
            },
            check_texture: function(key){
                return this.item.params[key] != '' && this.item.params[key] != null;
            },
            m_sel_file: async function (file) {

                let tp = file.true_path;
                if(tp.indexOf('common_assets') < 0) {
                    tp =  tp.substr(1)
                }


                this.item.params[this.file_target] = tp;

                if(this.file_target == 'map'){
                    let size = await get_image_size_by_src(this.correct_url(this.item.params[this.file_target]));
                    this.item.add_params.real_width = size.width;
                    this.item.add_params.real_height = size.height;
                    // this.$emit('e_map_change', size);
                }


                this.update_material();
                $(this.$refs.fm).modal('hide');
            },
            correct_url: function (path) {
                if (path == '' || path == null) return '/common_assets/images/placeholders/256x256.png'

                let date_time = new Date().getTime();

                if (path.indexOf('common_assets') > -1) {
                    return path + '?' + date_time
                } else {
                    return glob.acc_url + path + '?' + date_time;
                }
            },
        }
    })
</script>