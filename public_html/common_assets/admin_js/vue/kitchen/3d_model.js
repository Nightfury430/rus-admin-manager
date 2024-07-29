let m_categories_data = null;
let m_categories_hash = null;
let m_categories_ordered = null;
let params_blocks = null;
document.addEventListener('Glob_ready', async function () {


    sudat = '';

    preview_scene_model = null;

    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;


    categories_data = await promise_request(base_url + '/materials/get_all_data_ajax');
    categories_hash = get_hash(categories_data.categories);
    categories_ordered = flatten(create_tree(categories_data.categories));
    let cat_url =  glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name

    // m_categories_data = await promise_request(document.getElementById('ajax_base_url').value + '/' + document.getElementById('controller_name').value + '/get_categories_ajax');
    m_categories_data = await promise_request(cat_url);
    console.log(m_categories_data);
    m_categories_hash = get_hash(m_categories_data);
    m_categories_ordered = flatten(create_tree(m_categories_data));

    params_blocks = await promise_request(document.getElementById('ajax_base_url').value + '/catalog/items_get/params_blocks');

    for (let i = 0; i < params_blocks.length; i++){
        params_blocks[i].data = JSON.parse(params_blocks[i].data);
    }


    let category_0 = {
        id: 0,
        parent: 0,
        code: '',
        name: 'Категория в базе не найдена',
        items: [],
        categories: []
    }
    let material_0 = {
        id: 0,
        category: 0,
        code: '',
        name: 'Материал не найден',
        params: {
            color: '#ff0000',
            transparent: true,
            opacity: 0.3
        },
        add_params: {}
    }

    mat_lib = new lib_f(material_0, category_0);

    convert_lib_2(mat_lib, categories_data)

    init_vue();

});

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components: {
            'v-select': VueSelect.VueSelect,
            draggable: vuedraggable
        },
        data: {
            active: 1,
            drag: false,
            item: {
                id: 0,
                name: '',
                link: '',
                order: 100000,
                icon: '',
                category: 0,
                code: '',
                active: 1,
                show_in_report: 0,
                cabinet_group: 'top',
                drag_mode: 'common',
                wall_panel: false,
                draggable: true,
                sizes_available: 1,
                variants: [
                    {
                        id: new Date().getTime(),
                        name: '',
                        code: '',
                        price: '',
                        width: '',
                        height: '',
                        depth: '',
                        model: '',
                        file: '',
                        fixed: 1
                    }
                ],
                self_additional_materials: {},
                custom_data: {},
                params_blocks: [],
                material: {
                    type: 'Standart',
                    params: {
                        color: '#ffffff',
                        roughness: 0.8,
                        metalness: 0,
                        transparent: false,
                        opacity: 1,
                        map: '',
                        normalMap: '',
                    },
                    add_params: {
                        stretch_width: 1,
                        stretch_height: 1,
                        real_width: 0,
                        real_height: 0,
                        wrapping: 'mirror'
                    }
                }

            },
            active_material_tab: '0',

            custom_data_modal: {
                show: 0,
                name: '',
                key: '',
                value: ''
            },

            categories: {
                0: {
                    name: 'Нет',
                    parent: '0'
                }
            },
            all_categories: {
                0: {
                    name: 'Нет',
                    parent: '0'
                }
            },
            add_modal: false,
            errors: [],
            show_success_message: false,
            file_target: null
        },
        computed: {
            dragOptions() {
                return {
                    animation: 500,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },
            computed_parent_name: function () {
                return this.all_categories[this.all_categories[this.item.category].parent].name.replace(/<[^>]+>/g, '')
                // return 'Category parent name'
            },
            computed_mat_params: function () {
                let params = JSON.parse(JSON.stringify(this.item.material));
                if (params.params.map != ''&& params.params.map != null) params.params.map = this.correct_download_url(params.params.map)
                if (params.params.normalMap != '' && params.params.normalMap != null) params.params.normalMap = this.correct_download_url(params.params.normalMap)
                if (params.params.alphaMap != '' && params.params.alphaMap != null) params.params.alphaMap = this.correct_download_url(params.params.alphaMap)
                if (params.params.roughnessMap != '' && params.params.roughnessMap != null ) params.params.roughnessMap = this.correct_download_url(params.params.roughnessMap)

                return params
            },
            computed_mats: function () {
                let scope = this;
                let result = [];

                if (scope.active_material_tab != null) {
                    if (scope.item.self_additional_materials[scope.active_material_tab]) {
                        let mats = scope.item.self_additional_materials[scope.active_material_tab].materials;

                        for (let i = 0; i < mats.length; i++) {
                            let cats = mat_lib.get_category(mats[i]);
                            for (let c = 0; c < cats.categories.length; c++) {
                                result = result.concat(JSON.parse(JSON.stringify(cats.categories[c].items)))
                            }
                            result = result.concat(JSON.parse(JSON.stringify(cats.items)))
                        }

                    } else {
                        // result = categories_data.items;
                    }
                }


                return result
            }


        },
        mounted: function () {
            let scope = this;

            $("#color").spectrum({
                color: scope.item.material.params.color,
                preferredFormat: "hex",
                // cancelText: '<?php echo $lang_arr['cancel']?>',
                // chooseText: '<?php echo $lang_arr['pick']?>',
                showInput: true,
                move: function (color) {
                    scope.item.material.params.color = color.toHexString();
                    if (preview_scene_model) preview_scene_model.set_material_from_params(scope.computed_mat_params)
                },
                change: function (color) {
                    scope.item.material.params.color = color.toHexString();
                    if (preview_scene_model) preview_scene_model.set_material_from_params(scope.computed_mat_params)
                }
            });



        },
        created: function () {
            this.$options.cat_ordered = m_categories_ordered;
            this.$options.params_blocks = params_blocks;
            this.$options.params_blocks_hash = get_hash(params_blocks)
        },
        beforeMount: async function () {
            let scope = this;

            if (document.getElementById('item_id')) {


                let acc_url = document.getElementById('acc_base_url').value;

                let url = glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id

                let data = await promise_request(url);




                let item_data;

                if (data.model_data != null && data.model_data != '') {
                    item_data = JSON.parse(data.model_data);
                    item_data.active = data.active;
                    if (!item_data.self_additional_materials) item_data.self_additional_materials = {};
                    if (!item_data.show_in_report) item_data.show_in_report = 0;

                    Object.keys(item_data.self_additional_materials).forEach(function (k) {
                        let mats = item_data.self_additional_materials[k].materials;
                        if(!item_data.self_additional_materials[k].type){
                            item_data.self_additional_materials[k].type = '0';
                            item_data.self_additional_materials[k].params = {};
                        }

                        if(item_data.self_additional_materials[k].type == 1){
                            if(!Object.keys(item_data.self_additional_materials[k].params).length){
                                item_data.self_additional_materials[k].params = {
                                    type: 'Standart',
                                    params: {
                                        color: '#ffffff',
                                        roughness: 0.8,
                                        metalness: 0,
                                        transparent: false,
                                        opacity: 1,
                                        map: '',
                                        normalMap: ''
                                    },
                                    add_params: {
                                        stretch_width: 1,
                                        stretch_height: 1,
                                        real_width: 0,
                                        real_height: 0,
                                        wrapping: 'mirror'
                                    }
                                }
                            }
                        }

                        for (let i = mats.length; i--;) {
                            if (!categories_hash[mats[i]]) mats.splice(i, 1)
                        }
                    })


                } else {

                    let variants_data = JSON.parse(data.variants);
                    let material_data = JSON.parse(data.material);


                    if (!material_data.params.map) material_data.params.map = '';
                    if (!material_data.params.normalMap) material_data.params.normalMap = '';
                    if (!material_data.params.transparent) material_data.params.transparent = false;
                    if (!material_data.params.opacity) material_data.params.opacity = 1;

                    item_data = {
                        id: data.id,
                        name: data.name,
                        icon: data.icon,
                        category: data.category,
                        code: data.code,
                        active: data.active,
                        order: data.order,
                        cabinet_group: data.group,
                        drag_mode: data.drag_mode,
                        wall_panel: parseInt(data.wall_panel),
                        draggable: true,
                        sizes_available: parseInt(data.sizes_available),
                        variants: [],
                        self_additional_materials: {},
                        show_in_report: 0,
                        material: {
                            type: 'Standart',
                            params: material_data.params,
                            add_params: material_data.add_params
                        }
                    }

                    for (let i = 0; i < variants_data.length; i++) {
                        let variant = {
                            id: new Date().getTime(),
                            name: variants_data[i].alias !== undefined ? variants_data[i].alias : '',
                            width: variants_data[i].width,
                            height: variants_data[i].height,
                            depth: variants_data[i].depth,
                            model: variants_data[i].model !== undefined ? variants_data[i].model : ''
                        };

                        if (i == 0) {
                            variant.code = data.code;
                            variant.model = data.model;
                        }


                        item_data.variants.push(variant)
                    }
                }


                if (!item_data.material) {
                    item_data.material = {
                        type: 'Standart',
                        params: {
                            color: '#ffffff',
                            roughness: 0.8,
                            metalness: 0,
                            transparent: false,
                            opacity: 1,
                            map: '',
                            normalMap: ''
                        },
                        add_params: {
                            stretch_width: 1,
                            stretch_height: 1,
                            real_width: 0,
                            real_height: 0,
                            wrapping: 'mirror'
                        }
                    }
                }
                if (!item_data.material.params.map) item_data.material.params.map = '';
                if (!item_data.material.params.normalMap) item_data.material.params.normalMap = '';

                if (!item_data.order) item_data.order = 100000;

                item_data.draggable = true;

                if (!item_data.category) item_data.category = 0;

                if(!item_data.custom_data) item_data.custom_data = {};
                if(!item_data.params_blocks) item_data.params_blocks = [];

                console.log(item_data)

                Vue.set(scope, 'item', JSON.parse(JSON.stringify(item_data)))

                for (let i = 0; i < item_data.variants.length; i++) {
                    if (!item_data.variants[i].model) item_data.variants[i].model = '';

                    if (item_data.variants[i].model.indexOf('/common_assets') < 0) {
                        item_data.variants[i].model = acc_url + item_data.variants[i].model
                    }
                }

                if (!item_data.model) item_data.model = item_data.variants[0].model;


                $("#color").spectrum("set", item_data.material.params.color)


                let int = setInterval(function () {
                    if (typeof room !== 'undefined') {

                        // materials_lib.items = mat_lib.items
                        // materials_lib.categories = mat_lib.categories
                        // materials_lib.tree = mat_lib.tree



                        preview_scene_model = sc.add_object('model', JSON.parse(JSON.stringify(item_data)), false);


                        preview_scene_model.position.set(
                            preview_scene_model.params.width / units / 2 + 10,
                            0,
                            preview_scene_model.params.depth / units / 2 + 10,
                        )

                        preview_scene_model.addEventListener('model_loaded', function(e){
                            scope.material_change();
                        })



                        clearInterval(int)
                    }
                }, 100)

                this.change_params_blocks();
                this.$root.$emit('set_item_params', copy_object(item_data.material))
            }
        },
        methods: {

            sel_file: function (file) {

                let tp = file.true_path;
                if(tp.indexOf('common_assets') < 0) {
                    tp =  tp.substr(1)
                }

                if (this.file_target == 'icon') {
                    this.item[this.file_target] = tp;
                } else if (this.file_target == 'map') {
                    this.item.material.params.map = tp;
                    this.material_change();
                } else if (this.file_target == 'normalMap') {
                    this.item.material.params.normalMap = tp;
                    this.material_change();
                } else {


                    this.item.variants[this.file_target].model = tp;
                    if (this.file_target == 0) {
                        let scope = this;


                        if (preview_scene_model) {
                            preview_scene_model.delete();
                        }


                        this.item.variants[0].width = '';
                        this.item.variants[0].height = '';
                        this.item.variants[0].depth = '';

                        let params = scope.get_data();
                        params.draggable = true;
                        params.model = this.correct_download_url(this.item.variants[this.file_target].model);
                        for (let i = 0; i < params.variants.length; i++) {
                            params.variants[i].model = '';
                        }

                        preview_scene_model = sc.add_object('model', params, false);

                        let int = setInterval(function () {
                            if (preview_scene_model.model_loaded == 'loaded') {
                                scope.item.variants[0].width = preview_scene_model.params.width;
                                scope.item.variants[0].height = preview_scene_model.params.height;
                                scope.item.variants[0].depth = preview_scene_model.params.depth;
                                preview_scene_model.position.set(
                                    preview_scene_model.params.width / units / 2 + 10,
                                    0,
                                    preview_scene_model.params.depth / units / 2 + 10,
                                )

                                clearInterval(int)
                            }
                        }, 100)


                    }
                }

                $('#filemanager').modal('toggle')

                this.file_target = null;

            },

            correct_url: function (path) {

                if (path == '') return '/common_assets/images/placeholders/256x256.png'

                let date_time = new Date().getTime();

                if (path.indexOf('common_assets') > -1) {
                    return path + '?' + date_time
                } else {
                    return glob.acc_url + path + '?' + date_time;
                }
            },
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
            get_map: function (item) {
                if (item.map) return 'url(' + this.correct_url(item.map) + ')'
                return item.color;
            },

            on_drag({relatedContext, draggedContext}) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            },

            update_color_self(name, color){
                this.item.self_additional_materials[name].params.params.color = color;
            },
            update_material_self(name, data){
                Vue.set(this.item.self_additional_materials[name], 'params', copy_object(data))

            },

            update_color(data){
                this.item.material.params.color = data;
                this.material_change();
            },
            update_material(data) {
                Vue.set(this.item, 'material', copy_object(data))
                this.material_change();
            },

            material_change() {
                let params = this.computed_mat_params

                params.add_params.model_width = this.item.variants[0].width;
                params.add_params.model_height = this.item.variants[0].height;

                if (preview_scene_model) preview_scene_model.set_material_from_params(params)
            },
            size_change() {
                if (preview_scene_model) {
                    preview_scene_model.set_size_by_number(
                        this.item.variants[0].width,
                        this.item.variants[0].height,
                        this.item.variants[0].depth
                    );
                }
            },
            params_change() {

                if (preview_scene_model) {
                    dControls.s_helper.hide()
                    preview_scene_model.delete();
                } else {
                    return;
                }

                let params = this.get_data();
                params.draggable = true;

                if (!params.model) params.model = params.variants[0].model;

                params.model = this.correct_download_url(params.model)

                for (let i = 0; i < params.variants.length; i++) {
                    params.variants[i].model = '';
                }

                preview_scene_model = sc.add_object('model', params, false);
                preview_scene_model.position.set(
                    preview_scene_model.params.width / units / 2 + 10,
                    0,
                    preview_scene_model.params.depth / units / 2 + 10,
                )

            },

            resize_viewport() {
                document.getElementById('main_app').style.display = 'block'
                document.getElementById('preview_block').append(document.getElementById('main_app'))
                setTimeout(function () {
                    resize_viewport();
                }, 10)
            },

            get_data: function () {
                return JSON.parse(JSON.stringify(this.item));
            },
            set_data: function (key, data) {
                Vue.set(this.item, key, data)
            },

            add_variant: function () {

                let obj = {
                    id: new Date().getTime(),
                    name: '',
                    code: '',
                    price: '',
                    width: '',
                    height: '',
                    depth: '',
                    model: '',
                    file: '',
                    fixed: 1,
                    params_blocks: {}
                }
                let hash = this.$options.params_blocks_hash;
                if(this.item.params_blocks.length){
                    for (let i = 0; i < this.item.params_blocks.length; i++){
                        let block_id = this.item.params_blocks[i];
                        if(hash[block_id]){
                            console.log(hash[block_id])
                            obj.params_blocks[block_id] = copy_object(hash[block_id])
                        }

                    }
                }

                console.log(obj)

                this.item.variants.push(
                    obj
                )
            },
            remove_variant(index) {
                this.item.variants.splice(index, 1);
            },

            change_params_blocks: function(){
                let blocks = this.item.params_blocks;
                let hash = this.$options.params_blocks_hash;

                let id_to_remove = {};

                for (let i = 0; i < this.item.params_blocks.length; i++){
                    let block_id = this.item.params_blocks[i];
                    if(!hash[block_id]) id_to_remove[block_id] = 1;

                    for (let v = 0; v < this.item.variants.length; v++){
                        let variant = this.item.variants[v];
                        if(!variant.params_blocks) variant.params_blocks = {};

                        if(id_to_remove[block_id] == 1){
                            if(variant.params_blocks[block_id]) delete variant.params_blocks[block_id];
                        } else {
                            if(variant.params_blocks[block_id]){
                                if(hash[block_id].type == 'select'){
                                    let new_opts = copy_object(hash[block_id].params.options);
                                    let old_opts = variant.params_blocks[block_id].params.options;

                                    for (let no = 0; no < new_opts.length; no++){
                                        for (let oo = 0; oo < old_opts.length; oo++){
                                            if(new_opts[no].value == old_opts[oo].value){
                                                new_opts[no].price = old_opts[oo].price;
                                                new_opts[no].name = old_opts[oo].name;
                                            }
                                        }
                                    }
                                    Vue.set(this.item.variants)
                                }
                            } else {
                                variant.params_blocks[block_id] = copy_object(hash[block_id])
                            }
                        }
                    }
                }

                for (let i = 0; i < this.item.variants.length; i++){
                    let v = this.item.variants[i];
                    if(!v.params_blocks) v.params_blocks = {};
                    Object.keys(v.params_blocks).forEach((k)=>{
                        if(!blocks.includes(k)) delete v.params_blocks[k]
                    })
                }

            },


            add_custom_data: function () {

                this.item.custom_data[this.custom_data_modal.key] = {
                    name: this.custom_data_modal.name,
                    key: this.custom_data_modal.key,
                    value: this.custom_data_modal.value
                }

                this.custom_data_modal.name = '';
                this.custom_data_modal.key = '';
                this.custom_data_modal.value = '';


            },
            remove_custom_data: function (key) {
                Vue.delete(this.item.custom_data, key)
            },

            add_material: function () {
                let scope = this;
                let i = Object.keys(scope.item.self_additional_materials).length
                while (this.item.self_additional_materials[i]) {
                    i++;
                }

                let obj = {
                    key: '',
                    name: 'Без названия',
                    required: false,
                    fixed: 1,
                    type: 0,
                    params: {},
                    materials: [],
                    selected: null
                };

                Vue.set(this.item.self_additional_materials, i, obj);
                this.active_material_tab = i;

            },

            show_swal: function (k) {
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {
                    scope.remove_material(k)
                });
            },

            remove_material: function (k) {
                let scope = this;
                Vue.delete(this.item.self_additional_materials, k);
                this.active_material_tab = closest(Object.keys(scope.item.self_additional_materials), k)
            },

            download_assets: async function () {

                let zip = new JSZip();

                let params = this.get_data();


                let map_path = '';
                let files = [];
                if (params.material.params.map) {
                    if (params.material.params.map.indexOf('common_assets') > -1) {
                        map_path = params.material.params.map;
                    } else {
                        map_path = acc_url + params.material.params.map;
                    }
                }

                if (map_path != '') {
                    let obj = {};
                    obj.file = await get_binary(map_path);
                    let arr = map_path.split('/');
                    obj.name = arr[arr.length - 1];
                    files.push(obj);
                }


                if (params.material.params.normalMap) {
                    if (params.material.params.normalMap.indexOf('common_assets') > -1) {
                        map_path = params.material.params.normalMap;
                    } else {
                        map_path = acc_url + params.material.params.normalMap;
                    }
                }

                if (map_path != '') {
                    let obj = {};
                    obj.file = await get_binary(map_path);
                    let arr = map_path.split('/');
                    obj.name = arr[arr.length - 1];
                    files.push(obj);
                }


                if (params.variants) {
                    for (let i = 0; i < params.variants.length; i++) {
                        if (!params.variants[i].model) continue;
                        let model = params.variants[i].model;
                        let model_path = '';
                        if (model.indexOf('common_assets') > -1) {
                            model_path = model;
                        } else {
                            model_path = acc_url + model;
                        }

                        let obj = {};
                        obj.file = await get_binary(model_path);
                        let arr = model_path.split('/');
                        obj.name = arr[arr.length - 1];
                        files.push(obj);
                    }
                }


                for (let i = 0; i < files.length; i++) {
                    zip.file(files[i].name, files[i].file, {binary: true});
                }
                zip.generateAsync({type: "blob"}).then(function (content) {
                    save_file(content, params.name + '.zip');
                })

            },

            submit: function (e) {
                e.preventDefault();
                let scope = this;

                let data = JSON.parse(JSON.stringify(scope.get_data()));


                let formData = new FormData();
                formData.append('data', JSON.stringify(data));

                let url =  document.getElementById('form_submit_url').value

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        // console.log(msg);
                        window.location = document.getElementById('form_success_url').value
                    }
                });

                return false;

            },
            mi: function(){

                global_temp.single_data.c_pos = camera.position.clone();
                global_temp.single_data.target = controls.target.clone();

                controls.target.set(
                    0,
                    0,
                    0
                );
                camera.position.set(
                    140,
                    65,
                    225
                );




                camera.aspect = 400 / 400;
                camera.updateProjectionMatrix();
                renderer.setSize(400, 400);

                dControls.s_helper.visible = false
                hide_sizes();
                // preview_scene_model.hide_sizes();
                room.hide();
                controls.update();

                fitCameraToSelection(camera, controls, [preview_scene_model], 1.2);

                renderer.render(scene, camera)

                let canvas = document.createElement('canvas');
                canvas.width = 400;
                canvas.height = 400;
                let ctx = canvas.getContext('2d');
                ctx.drawImage(renderer.domElement,0,0,renderer.domElement.width,renderer.domElement.height,0,0,400,400);
                let data = canvas.toDataURL('image/jpeg', 1);
                var link = document.createElement("a");
                link.download = "image.jpeg";
                console.log(data)


                link.download = location.href.split('/').pop() + '_' + 'icon_common.jpg'

                canvas.toBlob(function (blob) {
                    link.href = URL.createObjectURL(blob);
                    link.click();
                }, 'image/jpeg');

                this.resize_viewport();
                room.show();
                show_sizes();
                // preview_scene_model.show_sizes();
                camera.position.copy(global_temp.single_data.c_pos)
                controls.target.copy(global_temp.single_data.target)
                controls.update();
            }
        }
    });
}

async function get_binary(path) {

    return new Promise(function (resolve, reject) {
        JSZipUtils.getBinaryContent(path, function (err, data) {
            if (err) {
                reject(err); // or handle the error
            }
            resolve(data);
        });
    });

}


function closest(arr, goal) {
    if (!arr.length) return 0;

    return arr.reduce(function (prev, curr) {
        return (Math.abs(curr - goal) < Math.abs(prev - goal) ? curr : prev);
    });
}


function convert_lib_2(lib, input, height) {

    let data = copy_object(input);
    console.log(data)
    let cat_h = Object.create(null)


    data.categories.forEach(function (obj) {
        obj.categories = [];
        obj.items = [];
        cat_h[obj.id] = obj;
    })

    // data.categories.forEach( aData => cat_h[aData.id] = { ...aData, categories : [] } )
    lib.categories = cat_h;
    lib.items = get_hash(data.items);


    for (let i = 0; i < data.items.length; i++) {
        if (height) data.items[i].height = data.items[i].params.variants[0].height;
        if (data.items[i].category in cat_h) {
            if (!cat_h[data.items[i].category].items) cat_h[data.items[i].category].items = [];
            cat_h[data.items[i].category].items.push(data.items[i])
        }
    }

    let dataTree = []
    for (let i = 0; i < data.categories.length; i++) {

        if (data.categories[i].parent && data.categories[i].parent != 0){
            if(cat_h[data.categories[i].parent])
            cat_h[data.categories[i].parent].categories.push(cat_h[data.categories[i].id])
        }
        else dataTree.push(cat_h[data.categories[i].id])
    }

    lib.tree = dataTree
}

let lib_f = function (default_item, default_category) {

    let item = copy_object(default_item)
    let category = copy_object(default_category)
    if (!category.items) category.items = []
    if (!category.items.length) category.items.push(item)
    if (!category.categories) category.categories = []

    let result = {
        tree: '',
        items: {
            0: item
        },
        categories: {
            0: category
        },
        get_item: function (id) {
            if (this.items[id]) {
                let copy = copy_object(this.items[id]);
                if (!copy.add_params) copy.add_params = {};
                return copy;
            } else {
                return item
            }
        },
        get_category: function (id) {
            if (this.categories[id]) {
                return copy_object(this.categories[id]);
            } else {
                return category
            }
        }
    }

    return result;
}

function fitCameraToSelection(camera, controls, selection, fitOffset = 1.2) {
    const size = new THREE.Vector3();
    const center = new THREE.Vector3();
    const box = new THREE.Box3();
    // box.makeEmpty();
    // for(const object of selection) {
    //     box.expandByObject(object.userData.object);
    // }
    selection[0].box.update()
    // box.getSize(size);
    // box.getCenter(center );
    // const helper = new THREE.Box3Helper( selection[0].box, 0xffff00 );
    // scene.add( helper );

    selection[0].box.getSize(size);
    selection[0].box.getCenter(center);

    console.log(size)
    console.log(center)

    const maxSize = Math.max(size.x, size.y, size.z);
    const fitHeightDistance = maxSize / (2 * Math.atan(Math.PI * camera.fov / 360));
    const fitWidthDistance = fitHeightDistance / camera.aspect;
    const distance = fitOffset * Math.max(fitHeightDistance, fitWidthDistance);

    const direction = controls.target.clone()
        .sub(camera.position)
        .normalize()
        .multiplyScalar(distance);

    controls.maxDistance = distance * 10;
    controls.target.copy(center);

    camera.near = distance / 100;
    camera.far = distance * 100;
    camera.updateProjectionMatrix();

    camera.position.copy(controls.target).sub(direction);

    controls.update();
}
