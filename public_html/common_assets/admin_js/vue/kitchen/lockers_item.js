let app;
let data = {};
let materials = {};
let categories;
let item = null;
let decor_data = null;

let locker_variant = {
    name: '',
    code: '',
    price: 0,
    min_x: 0,
    max_x: 0,
    min_y: 0,
    max_y: 0,
    min_z: 0,
    max_z: 0,
    params: {
        children: [

        ],
        accessories: [

        ],
    }
}

let param_template = {
    key: '',
    name: '',
    type: 'c',
    values: [],
    value: 0,
}

document.addEventListener('Glob_ready', async function () {

    let requests = [];
    requests.push(promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name))
    requests.push(promise_request(glob.base_url + '/catalog/items_get/material_types'))
    requests.push(promise_request(glob.base_url + '/materials/get_all_data_ajax'))

    requests.push(promise_request(glob.base_url + '/catalog/categories_get/facades'));
    requests.push(promise_request(glob.base_url + '/catalog/items_get/facades'))

    requests.push(promise_request(glob.base_url + '/catalog/categories_get/handles'));
    requests.push(promise_request(glob.base_url + '/catalog/items_get/handles'))

    requests.push(promise_request(glob.base_url + '/catalog/categories_get/model3d'));
    requests.push(promise_request(glob.base_url + '/catalog/items_get/model3d'))

    requests.push(promise_request(glob.base_url + '/catalog/items_get/handles'))


    let item_id_index = 0;

    if(glob.item_id != 0){
        item_id_index = requests.length;
        requests.push(promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id))
    }

    let result = await Promise.all(requests)

    console.log(result)

    cat_data = result[0];
    cat_hash = get_hash(cat_data);
    cat_ordered = flatten(create_tree(cat_data));

    let mats = result[1]

    for (let i = 0; i < mats.length; i++){
        let it = mats[i]
        it.data = JSON.parse(it.data);

        if(!it.data.variants[it.data.default]){
            it.data.default = Object.keys(it.data.variants)[0]

        }

        materials[it.key] = it.data;
    }




    decor_data = result[2];

    let category_0 = {
        id:0,
        parent: 0,
        code:'',
        name: 'Категория в базе не найдена',
        items: [],
        categories: []
    }
    let material_0 = {
        id:0,
        category: 0,
        code:'',
        name: 'Материал не найден',
        params:{
            color: '#ff0000',
            transparent: true,
            opacity:0.3
        },
        add_params:{}
    }

    for (let i = decor_data.items.length; i--;){
        if(decor_data.items[i].params) continue;
        decor_data.items[i].params = {};
        decor_data.items[i].params.color = decor_data.items[i].color;
        decor_data.items[i].params.map = decor_data.items[i].map;
        decor_data.items[i].params.metalness = decor_data.items[i].metalness;
        decor_data.items[i].params.roughness = decor_data.items[i].roughness;
        decor_data.items[i].params.transparent = decor_data.items[i].transparent;

        decor_data.items[i].add_params = {};
        decor_data.items[i].add_params.real_height = decor_data.items[i].real_height;
        decor_data.items[i].add_params.real_width = decor_data.items[i].real_width;
        decor_data.items[i].add_params.stretch_height = decor_data.items[i].stretch_height;
        decor_data.items[i].add_params.stretch_width = decor_data.items[i].stretch_width;
        decor_data.items[i].add_params.wrapping = decor_data.items[i].wrapping;
    }

    decor_categories_hash = get_hash(decor_data.categories);
    decor_categories_ordered = flatten(create_tree(decor_data.categories));


    facades_data = {
        categories: result[3],
        items: result[4]
    }

    facades_hash = get_hash(facades_data.items);
    facades_cat_hash = get_hash(facades_data.categories);

    for(let i = 0; i < facades_data.items.length; i++){
        facades_data.items[i].data = JSON.parse(facades_data.items[i].data)
        facades_data.items[i].materials = JSON.parse(facades_data.items[i].materials)
    }

    model_data = {
        categories: result[7],
        items: result[8]
    }

    for(let i = 0; i < model_data.items.length; i++){
        model_data.items[i].params = JSON.parse(model_data.items[i].data)
    }

    handles_data = {
        categories: [],
        items: result[9]
    }

    for(let i = 0; i < handles_data.items.length; i++){
        handles_data.items[i].material = JSON.parse(handles_data.items[i].material)
        handles_data.items[i].sizes = JSON.parse(handles_data.items[i].variants)
    }

    if(glob.item_id != 0){
        item = result[item_id_index]
    }

    console.log(materials)

    init_vue();
})


function init_vue() {
    app = new Vue({
        el: '#app',
        data: {
            item:{
                id: 0,
                icon: '',
                name: '',
                order: 0,
                active: 1,
                category: [],

                models: [
                    copy_object(locker_variant)
                ],
                params: [
                    copy_object(param_template)
                ],
            },

            params: {
                spec: {
                    name: '',
                    types: [],
                    template: '',
                    materials: [
                        // {
                        //     "key": "ldsp_kub_fresh_nyu",
                        //     "fixed": 0,
                        //     "label": "ЛДСП куб Фреш Нью",
                        //     "variant": "v_16",
                        //     "id": "8050"
                        // }
                    ],
                    variant: 0,
                    variants: [
                        {
                            name: '',
                            code: '',
                            price: '',
                            size: {
                                x: 600,
                                y: 360,
                                z: 530
                            },
                            variables: {

                            }
                        }
                    ],
                },
                children: [

                ],
                computed: [],
                variables: {

                },
                materials: {

                }
            },

            edges: {
                is_on: false,
                transparent: false
            },
            param: copy_object(param_template),
            param_add_vis: 0,
            current_model: 0,

            tab: 'models',
            debounce: null,
            delay: 500
        },
        computed: {

        },
        mounted: function(){
            let scope = this;
            let inter = setInterval(function () {

                if(scene !== undefined){

                    // custom_settings.forced_mat_opacity = true;

                    clearInterval(inter);
                    set_obj.materials = materials;

                    // materials_lib = new lib(material_0, category_0);
                    convert_lib(materials_lib, decor_data)
                    materials_catalog = decor_data;
                    materials_catalog.categories.push({
                        id: 0,
                        name: 'Без категории'
                    })
                    materials_catalog.items.push({
                        id: 0,
                        category: 0,
                        name: 'Материал не найден',
                        code: '',
                        params: {
                            color: '#ffffff'
                        },
                        add_params: {}
                    })

                    models3d_lib = new lib(material_0, category_0)
                    convert_lib(models3d_lib, model_data)

                    handles_lib = new lib(handles_0, category_0)
                    convert_lib(handles_lib, handles_data)

                    // facades_lib = new lib(material_0, category_0);
                    // convert_lib(facades_lib, facades_data)

                    let params;

                    if(item != null){

                        // scope.params.spec.materials.push({key:'ldsp'})



                        params = copy_object(scope.params)
                        params.children = copy_object(scope.item.models[0].params.children)
                        // params.materials = copy_object(scope.item.models[0].params.materials)

                        Object.keys(scope.item.models[0].params.materials).forEach((k)=>{
                            params.spec.materials.push(scope.item.models[0].params.materials[k])
                        })

                        if(scope.item.models[0].params.variables){
                            params.variables = copy_object(scope.item.models[0].params.variables)
                        }





                    } else {
                        scope.params.spec.materials.push({key:'ldsp'})
                        params = copy_object(scope.params)

                        // params.children = copy_object(scope.item.models[scope.current_model].params.children)


                    }






                    cabinet = sc.add_object('cabinet_cn', params, false);
                    cabinet.position.set(100, 0, 100)


                    // cabinet.add_child_by_path('0', copy_object(scope.item.models[scope.current_model].params.children[0]), 0)

                    cabinet.box.update();
                    single_preview(true, cabinet)

                    scope.$selected_object = cabinet;
                    scope.$emit('set_selected', cabinet)

                    // scope.get_rest_facades();
                    scope.resize_viewport();

                }

            }, 100)

        },
        beforeCreate: function(){

        },
        created: function () {
            if (glob.item_id != 0) {
                console.log(item)
                item.data = JSON.parse(item.data)
                Vue.set(this, 'item', item.data)
            }
            this.$root.$on('configurator_update', (params) => {
                this.item.models[this.current_model].params.children = params.children
                this.item.models[this.current_model].params.materials = params.materials
                this.item.models[this.current_model].params.variables = params.variables
            })

            this.$root.$on('change_default_material', (params) => {
                let key = params.mat_key + '::' + params.group;
                this.item.models[this.current_model].params.materials[key][params.key] = params.val
            })

            this.$root.$on('change_variable', (variable) => {

                if(!this.item.models[this.current_model].params.variables) {
                    Vue.set(this.item.models[this.current_model].params, 'variables', {})
                }

                this.item.models[this.current_model].params.variables[variable.key] = copy_object(variable)
            })

            this.$root.$on('remove_variable', (key) => {
                Vue.delete(this.item.models[this.current_model].params.variables, key)
            })



        },
        beforeMount: async function () {

        },
        methods: {
            change_preview_size: function(axis, e){
                clearTimeout(this.debounce)
                this.debounce = setTimeout(()=> {
                    this.params.spec.variants[0].size[axis] = e.target.value
                    this.set_params();
                }, this.delay)
            },
            change_tab: function (key) {
                this.tab = key;
            },
            show_param_add(item){
                if(item){
                    Vue.set(this,'param', copy_object(item))
                    this.param_add_vis = 1;
                } else {
                    this.param_add_vis = 1;
                }
            },
            var_change_name: function(){
                this.param.key = translit(this.param.name).toLowerCase().replaceAll(' ', '_');
            },
            var_remove_option: function(index){
                this.param.values.splice(index, 1)
            },
            var_add_option: function(){
                this.param.values.push({
                    name: ''
                })
            },
            hide_var_add: function(){
                this.param_add_vis = 0;
                Vue.set(this,'param', copy_object(param_template))
            },
            add_variable: function(){
                if(!this.item.params) Vue.set(this.item, 'params', [])
                this.item.params.push(copy_object(this.param))

                this.hide_var_add();
            },
            add_param: function (){
                if(!this.item.params) Vue.set(this.item, 'params', [])
                this.item.params.push(copy_object(param_template))
            },
            remove_param: function (i){
                this.item.params.splice(i, 1)
            },
            param_up: function (i){
                swap_arr_elements(this.item.params, i, i-1);
            },
            param_down: function (i){
                swap_arr_elements(this.item.params, i, i+1);
            },
            add_model: function () {
                this.item.models.push(copy_object(locker_variant))
            },
            remove_variant: function (ind) {
                this.item.models.splice(ind, 1)
                this.refresh_params()
            },
            variant_up: function (ind) {
                swap_arr_elements(this.item.models, ind, ind-1);
            },
            variant_down: function (ind) {
                swap_arr_elements(this.item.models, ind, ind+1);
            },

            change_variant_accessories: function(ind, e){
                console.log(e)
            },

            configure_variant: function(ind){
                this.current_model = ind;
                this.set_params();
                this.tab = 'config';
            },

            change_current_model: function(e){
                this.current_model = e.target.value;
                this.set_params();
            },

            set_params: function(){
                if(cabinet) sc.remove_object(cabinet);

                let params = copy_object(this.params)
                params.children = copy_object(this.item.models[this.current_model].params.children)
                if(this.item.models[this.current_model].params.materials){
                    params.materials = copy_object(this.item.models[this.current_model].params.materials)
                }

                if(this.item.models[this.current_model].params.variables){
                    params.variables = copy_object(this.item.models[this.current_model].params.variables)
                }


                cabinet = sc.add_object('cabinet_cn', params, false);
                cabinet.position.set(100, 0, 100)

                // cabinet.add_child_by_path('0', copy_object(this.item.models[this.current_model].params.children[0]), 0)

                cabinet.box.update();
                single_preview(true, cabinet)

                this.$selected_object = cabinet;
                this.$emit('set_selected', cabinet)

                // scope.get_rest_facades();
                this.resize_viewport();
            },

            refresh_params: function () {},
            get_data(){
                return copy_object(this.item)
            },
            resize_viewport: function(){
                document.getElementById('main_app').style.display = 'block'
                document.getElementById('preview_block').append(document.getElementById('main_app'))
                setTimeout(function () {
                    resize_viewport();
                },10)
            },
            edges_mode(){},
            edges_transparent(){},
            reset_camera(){},
            submit: function (e) {
                e.preventDefault();



                let url = this.$refs.submit_url.value

                let fdata = new FormData();
                let data = app.get_data();
                // delete data.params;
                delete data.category;
                fdata.append('id', data.id)
                fdata.append('name', data.name)
                fdata.append('icon', data.icon)
                fdata.append('data', JSON.stringify(data))
                fdata.append('active', data.active)
                fdata.append('order', data.order)

                let res = promise_request_post(url, fdata)
                console.log(res)
                location.href = this.$refs.success_url.value
                return false;
            }
        }
    });
}

function swap_arr_elements(arr, i1, i2) {
    arr[i1] = arr.splice(i2, 1, arr[i1])[0];
}