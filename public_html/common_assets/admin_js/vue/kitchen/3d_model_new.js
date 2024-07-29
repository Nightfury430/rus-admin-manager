let app = null;
let ajax_base_url = null;
let controller_name = null;
let item_id = null;
let acc_url = null;
let lang_data = null;

let categories_data = null;
let categories_tree = null;
let categories_ordered = null;
let categories_hash = null;

let input_timeout_time = 750;
let input_timeout = null;

let model_data = null;
let preview_scene_model = null;

let bim_types_list = [
    "common",
    "dryer",
    "oven",
    "microwave"
]

document.addEventListener('DOMContentLoaded', async function(){


    item_id = document.getElementById('item_id').value;
    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;
    lang_data = JSON.parse(document.getElementById('lang_json').value);
    controller_name = document.getElementById('footer_controller_name').value;


    let requests = [];
    requests.push(promise_request(base_url + '/catalog/categories_get/' + controller_name))
    if(item_id != 0) requests.push(promise_request(base_url + '/catalog/get_item/' + controller_name + '/' + item_id))

    Promise.all(requests).then(function (results) {
        categories_data = results[0];

        categories_data.unshift({
            id:"0",
            name: lang_data['no'],
            parent: "0"
        })
        categories_tree = create_tree(categories_data);
        categories_ordered = flatten(categories_data);
        categories_hash = get_hash(categories_data)


        if(item_id != 0){
            temp_data = results[1]
            model_data = JSON.parse(temp_data['model_data']);
        }

        init_vue();


    }).catch(function (e) {
        console.log('Error');
        console.log(e)
    });

});

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components:{
            'v-select': VueSelect.VueSelect,
            draggable: vuedraggable
        },
        data: {
            active: 1,
            drag: false,
            item:{
                id: 0,
                name: '',
                order: 100000,
                icon: '',
                category: "0",
                code: '',
                active: 1,
                show_in_report: 1,
                cabinet_group: 'top',
                drag_mode: 'common',
                wall_panel: false,
                draggable: false,
                sizes_available: 1,
                exact: 0,
                type: 'common',
                variants:[
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
                        module_width: 0,
                        fixed: 0,

                    }
                ],
                self_additional_materials:{

                },
                material:{
                    type: 'Standart',
                    params:{
                        color: '#ffffff',
                        roughness: 0.8,
                        metalness: 0,
                        map: ''
                    },
                    add_params:{
                        stretch_width: 1,
                        stretch_height: 1,
                        real_width: 0,
                        real_height: 0,
                        wrapping: 'mirror'
                    }
                }

            },
            active_material_tab: '0',
            icon_file: '',
            map_file:'',
            selected_variant: 0,
            single_model: 1,

            model_tab: 0,

            errors: [],
            show_success_message: false
        },
        computed:{
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
                return JSON.parse(JSON.stringify(this.item.material))
            },
            computed_mats: function () {
                let scope = this;
                let result = [];

                if(scope.active_material_tab != null){
                    if(scope.item.self_additional_materials[scope.active_material_tab].fixed != 1){
                        let mats = scope.item.self_additional_materials[scope.active_material_tab].materials;


                        for (let i = 0; i < mats.length; i++){

                            let cats = mat_lib.get_category(mats[i]);

                            console.log(cats)

                            for (let c = 0; c < cats.categories.length; c++){
                                result = result.concat(JSON.parse(JSON.stringify(cats.categories[c].items)))
                            }

                            result = result.concat(JSON.parse(JSON.stringify(cats.items)))
                        }

                    }
                }

                console.log(result)

                return result
            }

        },
        created: function(){
            this.$options.categories_ordered = categories_ordered;
            this.$options.categories_hash = categories_hash;

            this.$options.controller_name = controller_name;
            this.$options.bim_types = bim_types_list
            console.log(model_data)

            if(item_id != 0) {

                if(controller_name == 'builtin'){
                    model_data.draggable = 1;
                }

                Vue.set(this, 'item', model_data)

                let count = 0;

                for (let i = model_data.variants.length; i--;){
                    if(model_data.variants[i].model != '') count++;
                }

                if(count > 1){
                    this.single_model = 0;
                }

            }

        },
        mounted(){
            let scope = this;
            $("#color").spectrum({
                color: scope.item.material.params.color,
                preferredFormat: "hex",
                // cancelText: '<?php echo $lang_arr['cancel']?>',
                // chooseText: '<?php echo $lang_arr['pick']?>',
                showInput: true,
                move: function(color) {
                    scope.item.material.params.color = color.toHexString();
                    if(preview_scene_model) preview_scene_model.set_material_from_params(scope.computed_mat_params)
                },
                change: function(color) {
                    scope.item.material.params.color = color.toHexString();
                    if(preview_scene_model) preview_scene_model.set_material_from_params(scope.computed_mat_params)
                }
            });


            if (document.getElementById('item_id')) {
                let inter = setInterval(function () {
                    if(scene !== undefined){
                        clearInterval(inter);






                        let params = scope.get_data();



                        for (let i = params.variants.length; i--;){
                            if(params.variants[i].model != ''){
                                if(params.variants[i].model.indexOf('common_assets') < 0){
                                    params.variants[i].model = acc_url + params.variants[i].model;
                                }
                            }
                        }


                        preview_scene_model = sc.add_object('model', params, false);
                        preview_scene_model.position.set(100, 0, 100)
                    }

                }, 100)
            }

        },
        methods: {
            lang: function(key){
                return lang_data[key];
            },
            get_map: function(item){
                console.log(item)
                if(item.map) return 'url(' + this.correct_url(item.map) + ')'
                return item.color;
            },
            correct_url: function(path){
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return acc_url + path;
                }
            },


            on_drag({ relatedContext, draggedContext }) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            },
            material_change(){
                if(preview_scene_model) preview_scene_model.set_material_from_params(this.computed_mat_params)
            },
            size_change(index){
                let scope = this;
                console.log(12312)
                debounce(function () {
                    if(preview_scene_model){
                        preview_scene_model.set_size_by_number(
                            scope.item.variants[index].width,
                            scope.item.variants[index].height,
                            scope.item.variants[index].depth
                        );
                    }
                })


            },
            params_change(){
                if(preview_scene_model){
                    preview_scene_model.delete();
                } else {
                    return;
                }

                let params = this.get_data();
                params.draggable = true;

                if(!params.model) params.model = params.variants[0].model;
                let acc_url = document.getElementById('acc_base_url').value;
                if(params.model.indexOf('/common_assets') < 0) {
                    params.model = acc_url + params.model
                }
                for (let i = 0; i < params.variants.length; i++){
                    params.variants[i].model = '';
                }

                preview_scene_model = sc.add_object('model', params, false);
                preview_scene_model.position.set(
                    preview_scene_model.params.width / units / 2 + 10,
                    0,
                    preview_scene_model.params.depth / units / 2 + 10,
                )

            },
            process_file(event, index) {
                if(event.target.files.length){

                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.item.variants[index].file = event.target.files[0];
                } else {
                    this.item.variants[index].file = '';
                }

            },
            process_icon_file: function (event) {
                if(event.target.files.length){

                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.icon_file = event.target.files[0];

                } else {
                    this.icon_file = '';
                }
            },
            update_model(){
                console.log(this.get_data())

            },
            process_model(event, index){
                let scope = this;

                if(preview_scene_model){
                    preview_scene_model.delete();
                    scope.item.variants[index].width = '';
                    scope.item.variants[index].height = '';
                    scope.item.variants[index].depth = '';
                }


                if(event.target.files.length){

                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.item.variants[index].file = event.target.files[0];

                    let reader = new FileReader;
                    reader.readAsDataURL(event.target.files[0]);

                    reader.onload = function(e) {
                        let params = scope.get_data();

                        params.draggable = true;
                        params.model = e.target.result;

                        for (let i = 0; i < params.variants.length; i++){
                            params.variants[i].model = '';
                        }

                        preview_scene_model = sc.add_object('model', params, false);


                        let int = setInterval(function () {

                            if(preview_scene_model.model_loaded == 'loaded'){
                                scope.item.variants[index].width = preview_scene_model.params.width;
                                scope.item.variants[index].height = preview_scene_model.params.height;
                                scope.item.variants[index].depth = preview_scene_model.params.depth;



                                preview_scene_model.position.set(
                                    preview_scene_model.params.width / units / 2 + 10,
                                    0,
                                    preview_scene_model.params.depth / units / 2 + 10,
                                )

                                clearInterval(int)
                            }


                        },100)


                    };

                } else {
                    this.item.variants[0].file = '';
                }
            },
            process_map_file(event){
                let scope = this;

                if(event.target.files.length){

                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.map_file = event.target.files[0];

                    let reader = new FileReader;
                    reader.onload = function() { // file is loaded
                        let img = new Image;
                        img.onload = function () {

                            scope.item.material.add_params.real_width = img.width;
                            scope.item.material.add_params.real_height = img.height;
                            scope.item.material.params.map = img.src;

                            // let params = JSON.parse(JSON.stringify(scope.computed_mat_params));
                            // params.params.map = img.src;
                            if(preview_scene_model) preview_scene_model.set_material_from_params(scope.computed_mat_params)
                        };
                        img.src = reader.result; // is the data URL because called with readAsDataURL
                    };

                    reader.readAsDataURL(event.target.files[0]);
                } else {
                    this.map_file = '';
                    if(preview_scene_model) preview_scene_model.set_material_from_params(scope.computed_mat_params)
                }
            },
            remove_map_file(){
                this.map_file = '';
                this.item.material.params.map = '';
                this.$refs.map_file.value = '';
                if(preview_scene_model) preview_scene_model.set_material_from_params(this.computed_mat_params);
            },
            clear_file(event, index){
                event.target.parentElement.getElementsByTagName('INPUT')[0].value = '';
                // delete this.item.variants[index].file;

                Vue.delete(this.item.variants[index], 'file')

                this.select_variant(index)
            },
            get_icon_src:function (file) {
                if(this.item.icon != '' && this.icon_file == ''){
                    if(this.item.icon.indexOf('/common_assets') < 0) return acc_url + this.item.icon
                    return this.item.icon;
                }
                if(this.icon_file != '') return URL.createObjectURL(file);
                if(this.item.icon == '' &&  this.icon_file == '') return 'https://via.placeholder.com/78x125';
            },
            get_map_src:function (file) {
                if(this.item.material.params.map != '' && this.map_file == '') return this.item.material.params.map;
                if(this.map_file != '') return URL.createObjectURL(file);
                if(this.item.material.params.map == '' &&  this.map_file == '') return 'https://via.placeholder.com/256x256';
            },
            resize_viewport(){
                setTimeout(function () {
                    resize_viewport();
                },10)
            },

            get_data: function () {
                return JSON.parse(JSON.stringify(this.item));
            },

            set_data: function(key, data){
                Vue.set(this.item, key, data)
            },

            add_variant:function () {
                this.item.variants.push(
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
                        module_width: 0,
                        fixed: 0,

                    }
                )
            },
            select_variant(index){

                if(this.selected_variant == index) return;

                if(!this.item.variants[index]) return;

                this.selected_variant = index;


                if(preview_scene_model){

                    let variant = this.get_data().variants[index];

                    if(variant.model != ''){
                        if(variant.model.indexOf('common_assets') < 0){
                            variant.model = acc_url + variant.model;
                        }
                    }

                    if(this.item.variants[index].file){
                        let reader = new FileReader;
                        reader.readAsDataURL(this.item.variants[index].file);
                        reader.onload = function(e) {
                            variant.model = e.target.result;

                            preview_scene_model.params.variants[index] = variant;
                            preview_scene_model.set_size_by_index(index)

                        };
                        return;
                    }

                    preview_scene_model.params.variants[index] = variant;
                    preview_scene_model.set_size_by_index(index)
                }
            },
            remove_variant(index){
                this.item.variants.splice(index,1);
                this.select_variant(index-1);
            },


            preload_models: async function(){
                let scope = this;
                let models = {};

                for (let i = 0; i < scope.item.variants.length; i++) {
                    if (scope.item.variants[i].model) {
                        if (scope.item.variants[i].model.indexOf('common_assets') < 0) {
                            models[scope.item.variants[i].id] = acc_url + scope.item.variants[i].model
                        } else {
                            models[scope.item.variants[i].id] = scope.item.variants[i].model
                        }
                    }
                }



                for (const key of Object.keys(models)) {
                    let model = await get_model_meshes(models[key])



                }



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
                    materials: [],
                    selected: null
                };

                Vue.set( this.item.self_additional_materials, i, obj);
                this.active_material_tab = i;

            },
            remove_material: function (k) {
                let scope = this;
                Vue.delete( this.item.self_additional_materials, k);
                this.active_material_tab = closest(Object.keys(scope.item.self_additional_materials), k)
            },

            download_assets: async function(){

                let zip = new JSZip();

                let params = this.get_data();

                let map_path = '';
                let files = [];
                if(params.material.params.map){
                    if(params.material.params.map.indexOf('common_assets') > -1){
                        map_path = params.material.params.map;
                    } else {
                        map_path = acc_url + params.material.params.map;
                    }
                }

                if(map_path != ''){
                    let obj = {};
                    obj.file = await get_binary(map_path);
                    let arr = map_path.split('/');
                    obj.name = arr[arr.length-1];
                    files.push(obj);
                }

                if(params.variants){
                    for (let i = 0; i < params.variants.length; i++){
                        if(!params.variants[i].model) continue;
                        let model = params.variants[i].model;
                        let model_path = '';
                        if(model.indexOf('common_assets') > -1){
                            model_path = model;
                        } else {
                            model_path = acc_url + model;
                        }
                        console.log(model_path)

                        let obj = {};
                        obj.file = await get_binary(model_path);
                        let arr = model_path.split('/');
                        obj.name = arr[arr.length-1];
                        files.push(obj);

                    }
                }


                console.log(files)

                for (let i = 0; i < files.length; i++){
                    zip.file(files[i].name, files[i].file, {binary:true});
                }
                zip.generateAsync({type:"blob"}).then(function (content) {
                    save_file(content, params.name + '.zip');
                })

            },

            submit: function (e) {
                e.preventDefault();
                let scope = this;
                let acc_url = document.getElementById('acc_base_url').value;
                let data = JSON.parse(JSON.stringify(scope.get_data()));
                if(controller_name == 'builtin'){
                    data.draggable = 0;
                }
                let files = {};



                for (let i = 0; i < scope.item.variants.length; i++){
                    data.variants[i].model = data.variants[i].model.replace(acc_url,'');
                    if(scope.item.variants[i].file != '') files[data.variants[i].id] = scope.item.variants[i].file;
                    delete data.variants[i].file;
                    delete data.variants[i].fixed;
                }
                data.icon = data.icon.replace(acc_url,'');
                data.material.params.map = data.material.params.map.replace(acc_url,'')


                let formData = new FormData();



                if(scope.icon_file != '') formData.append('icon_file', scope.icon_file);
                if(scope.map_file != '') formData.append('map_file', scope.map_file);

                Object.keys(files).forEach(function (k) {
                    formData.append(k, files[k])
                });

                formData.append('data', JSON.stringify(data));
                // return ;


                $.ajax({
                    url : $('#sub_form').attr('action'),
                    type : 'POST',
                    data : formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success : function(msg) {
                        console.log(msg);
                        // window.location = document.getElementById('form_success_url').value
                    }
                });

                return false;

            }

        }
    });
}
function debounce(callback) {
    clearTimeout(input_timeout);
    input_timeout = setTimeout(callback, input_timeout_time);
}

function get_model_meshes(url) {

    return new Promise(function (resolve, reject) {
        fbx_loader.load(
            url,
            function (obj) {
                resolve(obj)
            }
        )
    })

}