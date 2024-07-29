let app;
let data = {};
let materials = {};
let categories;
let item = null;
let decor_data = null;


document.addEventListener('Glob_ready', async function () {

    let requests = [];
    requests.push(promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name))
    requests.push(promise_request(glob.base_url + '/catalog/items_get/material_types'))
    requests.push(promise_request(glob.base_url + '/materials/get_all_data_ajax'))

    let item_id_index = 0;

    if (glob.item_id != 0) {
        item_id_index = requests.length;
        requests.push(promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id))
    }

    let result = await Promise.all(requests)

    console.log(result)

    cat_data = result[0];
    cat_hash = get_hash(cat_data);
    cat_ordered = flatten(create_tree(cat_data));

    let mats = result[1]

    for (let i = 0; i < mats.length; i++) {
        let it = mats[i]
        it.data = JSON.parse(it.data);

        if (!it.data.variants[it.data.default]) {
            it.data.default = Object.keys(it.data.variants)[0]
        }

        materials[it.key] = it.data;
    }

    decor_data = result[2];

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

    for (let i = decor_data.items.length; i--;) {
        if (decor_data.items[i].params) continue;
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

    // decor_items_hash = get_hash(decor_data.items);

    if (glob.item_id != 0) {
        item = result[item_id_index]
        console.log(item)
    }

    console.log(materials)

    init_vue();

})


function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components: {
            'v-select': VueSelect.VueSelect
        },
        props: {
            delay: {
                type: Number,
                default: typeof input_delay === 'undefined' ? 500 : input_delay
            }
        },
        data: {
            active: 1,
            drag: false,
            item: {
                id: 0,
                name: '',
                icon: '',
                category: '',
                active: 1,
                params: {
                    price_type: 'p',
                    version: 2,
                    group: '',
                    variants: [],
                    // materials: {
                    //     gen: {
                    //         name: 'Основной материал',
                    //         key: "gen",
                    //         mode: 'm', //categories, variants, material, id
                    //         selected: 'ldsp',
                    //         params: {},
                    //         fixed: 0,
                    //         categories: [],
                    //         variants: [],
                    //     }
                    // },
                    materials: [
                        {
                            name: 'Основной материал',
                            key: "gen",
                            mode: 'm', //categories, variants, material, id
                            selected: 'ldsp',
                            group: 0,
                            params: {},
                            fixed: 0,
                            categories: [],
                            variants: [],
                        }
                    ],
                    models: [
                        {
                            name: '',
                            code: '',
                            price: 0,
                            min_x: 0,
                            min_y: 0,
                            min_z: 0,
                            model: ''
                        }
                    ]
                }
            },
            materials: [
                {
                    name: 'Основной материал',
                    key: "gen",
                    mode: 'v', //categories, variants, material, id
                    selected: null,
                    params:{
                        "type": "Standard",
                        "params": {
                            "icon": null,
                            "alphaMap": null,
                            "color": "#ffffff",
                            "map": null,
                            "metalness": 0,
                            "normalMap": null,
                            "roughness": 1,
                            "roughnessMap": null,
                            "transparent": 0,
                            "opacity": 1
                        },
                        "add_params": {
                            "real_width": 256,
                            "real_height": 256,
                            "rotation": "normal",
                            "stretch_width": 0,
                            "stretch_height": 0,
                            "normal_scale": false,
                            "wrapping": "mirror",
                            "normal_wrapping": "mirror",
                            "roughness_wrapping": "mirror"
                        }
                    },
                    fixed: 0,
                    categories: [],
                    variants: [],
                    tex_axis: {
                        x: 'x',
                        y: 'y'
                    }
                }
            ],
            tab: 'models',
            materials_tab: 0,
            preview_size: {
                x: 600,
                y: 720,
                z: 600
            },
            file_target: 0,
            debounce: null,
            errors: [],
            show_success_message: false,
        },
        computed: {},
        mounted: function () {
            let scope = this;
            let inter = setInterval(function () {

                if (scene !== undefined) {
                    clearInterval(inter);
                    set_obj.materials = materials;
                    materials_lib = new lib(material_0, category_0);
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



                    let mp = scope.get_data().params

                    console.log(mp)

                    models3d_lib.items[0] = {
                        params: mp
                    }

                    let params = {
                        spec: {
                            name: '',
                            types: [],
                            template: '',
                            materials: [],
                            variant: 0,
                            variants: [
                                {
                                    name: '',
                                    code: '',
                                    price: '',
                                    size: copy_object(scope.preview_size),
                                    variables: {}
                                }
                            ]
                        },
                        children: [
                            {
                                type: 'model',
                                // spec: mp
                                spec: {
                                    id: 0
                                }
                            }
                        ],
                        computed: [],
                        variables: {},
                        materials: {}
                    }



                    Object.keys(materials).forEach(function (k) {
                        params.spec.materials.push(
                            {
                                key: k
                            }
                        )

                    })


                    // let params;
                    //
                    // if(item != null){
                    //     params = JSON.parse(item.data);
                    //     scope.item.active = item.active;
                    //     scope.item.icon = item.icon;
                    //     scope.item.name = params.spec.name;
                    //     console.log(params.spec.name)
                    //     scope.item.category = JSON.parse(item.category);
                    // } else {
                    //     scope.item.params.spec.materials.push({key:'ldsp'})
                    // params = scope.get_data().params
                    // }
                    //
                    //
                    //
                    cabinet = sc.add_object('cabinet_cn', params, false);
                    cabinet.params.spec.facades.materials.gen = 169
                    cabinet.position.set(100, 0, 100)
                    //
                    scope.$selected_object = cabinet;
                    // scope.$emit('set_selected', cabinet)

                    scope.resize_viewport();

                }

            }, 100)

            for (let i = 0; i < this.materials.length; i++) {
                if(this.materials[i].mode == 'v'){
                    this.$emit('set_item_params_pp_mat' + i, this.materials[i].params)
                }

            }

        },
        beforeCreate: function(){
            this.$options.lang = glob.lang;
        },
        created: function () {
            if(item){

                item.params = JSON.parse(item.data)
                if(!item.params.price_type) item.params.price_type = 'p'
                let mats = [];
                mats[0] = {};

                if(Array.isArray(item.params.materials)){
                    mats = item.params.materials;

                    for (let i = 0; i < item.params.materials.length; i++){
                        if(!item.params.materials[i].tex_axis) item.params.materials[i].tex_axis = {
                            x: 'x',
                            y: 'y'
                        }
                    }

                } else {
                    mats = [];
                    Object.keys(item.params.materials).forEach((k)=> {
                        if(!item.params.materials[k].params) item.params.materials[k].params = {};
                        mats.push(item.params.materials[k]);
                        if(!item.params.materials[k].tex_axis) item.params.materials[k].tex_axis = {
                            x: 'x',
                            y: 'y'
                        }
                    })

                    item.params.materials = mats;
                }

                // Object.keys(item.params.materials).forEach((k)=> {
                //     if(k == 'gen'){
                //         mats[0] = item.params.materials[k];
                //     } else {
                //         mats.push(item.params.materials[k]);
                //     }
                //     if(!item.params.materials[k].params) item.params.materials[k].params = {};
                // })

                if(item.params.models[0]){
                    ce();
                    ce();
                    ce();
                    const ax_arr = ['x','y','z']
                    console.log(item.params.models[0])
                    for (let i = ax_arr.length; i--;){
                        let val = parseInt(item.params.models[0]['min_'+ax_arr[i]])
                        console.log(val)
                        if( val != 0 ){
                            this.preview_size[ax_arr[i]] = val;
                        }
                    }
                }







                // this.preview_size.x =

                Vue.set(this, 'materials', mats)
                Vue.set(this, 'item', item)
            }
            this.$options.cat_ordered = cat_ordered;
            this.$options.materials = materials;
        },
        methods: {
            get_mat_tab_class: function(ind){
                if(ind == this.materials_tab) return "rounded shadow active"
                return ""
            },
            get_materials_keys: function(){
                let model = cabinet.userData.object.userData.children[0];
                let meshes = model.get_meshes();

                let keys_arr = [];

                for (let i = 0; i < meshes.length; i++){
                    let custom_material_set = meshes[i].name.match(/m\((.*?)\)/);
                    if(custom_material_set != null){
                        let mat_key;
                        mat_key = custom_material_set[1];
                        keys_arr.push(mat_key);
                        this.add_material(mat_key);
                    }
                }


                // for (let i = 0; i < keys_arr.length; i++){
                //     if(!this.item.params.materials[keys_arr[i]]){
                //         Vue.delete(this.item.params.materials, keys_arr[i])
                //     }
                //
                // }

                this.refresh_params();
            },

            change_tab: function (key) {
                this.tab = key;
            },
            add_model: function () {
                this.item.params.models.push({
                    name: '',
                    code: '',
                    price: 0,
                    min_x: 0,
                    min_y: 0,
                    min_z: 0,
                    model: ''
                })
            },

            remove_variant: function (ind) {
                this.item.params.models.splice(ind, 1)
                this.refresh_params()
            },
            variant_up: function (ind) {
                swap_arr_elements(this.item.params.models, ind, ind-1);
            },
            variant_down: function (ind) {
                swap_arr_elements(this.item.params.models, ind, ind+1);
            },

            add_material: function(ind){

                this.$nextTick(()=> {

                    let params = this.$refs['pp_mat'][this.$refs['pp_mat'].length - 1].get_params();

                    console.log(params)

                    let obj = {
                        name: glob.lang['material'],
                        key: 'Введите ключ',
                        mode: 'v', //categories, variants, material, id
                        selected: null,
                        fixed: 0,
                        params: params,
                        categories: [],
                        variants: [],
                        tex_axis:{
                            x: 'x',
                            y: 'y'
                        }
                    }

                    this.materials.push(obj);

                    this.refresh_materials();
                })

            },
            remove_material: function(ind){
                this.materials_tab = ind-1;
                this.materials.splice(ind, 1)
                this.refresh_materials();
            },
            change_material_key: function(mat, key){
                clearTimeout(this.debounce)
                this.debounce = setTimeout(()=> {
                    this.refresh_materials();
                }, this.delay)
            },

            change_material_group: function(mat, key){
                clearTimeout(this.debounce)
                this.debounce = setTimeout(()=> {
                    this.refresh_materials();
                }, this.delay)
            },

            change_selected_material: function(e, index){

                this.materials[index].selected = e;
                this.refresh_materials();
            },

            refresh_params: function (rebuild = true) {

                Vue.set(this.item.params, 'materials', this.convert_materials())

                let params = this.get_data().params;
                for (let i = 0; i < params.models.length; i++) {
                    params.models[i].model = this.correct_url(params.models[i].model)
                }

                models3d_lib.items[0] = {
                    params: params
                }

                cabinet.userData.params.children[0].spec = params;
                if (rebuild) {
                    cabinet.build(true)
                }
            },

            change_material_mode: function(mat, ind){




                this.$nextTick(()=> {


                    Vue.set(this.materials[ind], 'categories', [])
                    Vue.set(this.materials[ind], 'selected', '')


                    if(this.$refs.vselect && this.$refs.vselect[0]){
                        this.$refs.vselect[0].clearSelection()
                    }


                    if(mat.mode == 'v'){
                        Vue.set(this.materials[ind], 'params', this.$refs['pp_mat'][ind].get_params())
                    } else {
                        Vue.set(this.materials[ind], 'params', {})
                    }

                    if(mat.mode == 'm'){
                        mat.selected = 'ldsp'
                    }


                    this.refresh_materials();
                    // this.refresh_params();
                })
            },

            convert_materials: function(){
                return copy_object(this.materials)
                let materials_obj = {}

                for (let i = 0; i < this.materials.length; i++) {
                    materials_obj[this.materials[i].key] = copy_object(this.materials[i])
                }

                return materials_obj;
            },

            computed_mats: function (ind) {
                let scope = this;
                let result = [];
                let categories = this.materials[ind].categories;

                if(typeof categories === 'string'){
                    let cats = materials_lib.get_category(categories);
                    for (let c = 0; c < cats.categories.length; c++) {
                        result = result.concat(JSON.parse(JSON.stringify(cats.categories[c].items)))
                    }
                    result = result.concat(JSON.parse(JSON.stringify(cats.items)))


                } else {
                    for (let i = 0; i < categories.length; i++) {
                        let cats = materials_lib.get_category(categories[i]);
                        for (let c = 0; c < cats.categories.length; c++) {
                            result = result.concat(JSON.parse(JSON.stringify(cats.categories[c].items)))
                        }
                        result = result.concat(JSON.parse(JSON.stringify(cats.items)))
                    }
                }



                return result
            },

            refresh_materials: function() {
                Vue.set(this.item.params, 'materials', this.convert_materials())
                let params = this.get_data().params.materials;
                cabinet.userData.params.children[0].spec.materials = params
                cabinet.userData.paths['0,0'].userData.params.spec.materials = params;
                cabinet.userData.paths['0,0'].apply_materials();
            },
            update_material:function(params, ind){
                Vue.set(this.materials[ind], 'params', params)
                this.refresh_materials();
            },

            update_color: function(params, ind){
                this.materials[ind].params.params.color = params
                this.refresh_materials();
            },

            get_data: function () {
                return JSON.parse(JSON.stringify(this.item));
            },

            get_item: function () {

            },
            resize_viewport: function () {
                document.getElementById('main_app').style.display = 'block'
                document.getElementById('preview_block').append(document.getElementById('main_app'))
                setTimeout(function () {
                    resize_viewport();
                }, 10)
            },
            sel_file: function (file) {
                if (!glob.is_common) {
                    file.true_path = file.true_path.substr(1);
                }
                if (this.file_target == 'icon') {
                    this.item.icon = file.true_path
                } else {
                    this.item.params.models[this.file_target].model = file.true_path
                    this.refresh_params();
                }

                $('#filemanager').modal('toggle')
            },
            correct_url: function (path) {
                if (!path) return '';
                if (path.indexOf('common_assets') > -1) {
                    return path
                } else {
                    return glob.acc_url + path;
                }
            },

            get_map: function (item) {
                if (item.map) return 'url(' + this.correct_url(item.map) + ')'
                return item.color;
            },

            change_preview_size: function () {
                clearTimeout(this.debounce)
                let scope = this;
                this.debounce = setTimeout(function () {
                    scope.refresh_params(false);
                    cabinet.set_size(scope.preview_size)
                }, this.delay)
            },
            set_preview_size: function (ind) {
                cabinet.set_size({
                    x: this.item.params.models[ind].min_x !== 0 ? this.item.params.models[ind].min_x : this.preview_size.x,
                    y: this.item.params.models[ind].min_y !== 0 ? this.item.params.models[ind].min_y : this.preview_size.y,
                    z: this.item.params.models[ind].min_z !== 0 ? this.item.params.models[ind].min_z : this.preview_size.z,
                })

                this.refresh_params()
            },
            submit: async function (e) {
                e.preventDefault();

                let scope = this;

                let params = this.get_data();

                let errors = [];
                if (errors.length) {
                    alert(errors.join('\n'))
                    return
                }


                let form_data = new FormData();
                form_data.append('data', JSON.stringify(params));

                let url = glob.base_url + '/model3d/item_add';
                if (item != null) url += '/' + glob.item_id;

                let res = await promise_request_post(url, form_data)
                if (res == 'ok') {
                    window.location = document.getElementById('form_success_url').value
                }

                return false;
            },

        }
    });
}

function swap_arr_elements(arr, i1, i2) {
    arr[i1] = arr.splice(i2, 1, arr[i1])[0];
}

// myArray.sort(function(a,b) {
//     if(a.min_x !== b.min_x) return (a.min_x < b.min_x) ? -1 : 1;
//     if(a.min_y !== b.min_y) return (a.min_y < b.min_y) ? -1 : 1;
//     return (a.min_z < b.min_z) ? -1 : 1;
// });