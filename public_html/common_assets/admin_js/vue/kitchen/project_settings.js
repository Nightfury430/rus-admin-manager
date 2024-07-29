let data = null;

document.addEventListener('Glob_ready', async function () {

    data = await promise_request(glob.base_url + '/project_settings/get_data_ajax');


    console.log(data);


    Object.keys(data.settings).forEach(function (key) {
        try{
            data.settings[key] = JSON.parse(data.settings[key])

            if(typeof data.settings[key] != 'object') data.settings[key] +='';

        } catch (e) {

        }
    })

    if(!data.settings.settings){
        let settings = copy_object(data.settings);
        delete settings.settings;
        data.settings = settings;
    } else {
        data.settings = copy_object(data.settings.settings);
    }

    if(!data.settings.selected_cornice_model) data.settings.selected_cornice_model = 0;

    data.cornice_items.unshift({
        id: 0,
        name: glob.lang['default']
    })



    if(data.settings.selected_material_wallpanel) delete data.settings.selected_material_wallpanel

    if(!data.settings.available_shelves_thickness) data.settings.available_shelves_thickness = ['16'];
    if(!data.settings.default_shelves_thickness) data.settings.default_shelves_thickness = '16';

    data.facades_categories = get_hash(data.facades_categories)
    data.facades_items_hash = get_hash(data.facades_items)

    Object.keys(data.facades_items).forEach(function (key) {
        try{
            data.facades_items[key].materials = JSON.parse(data.facades_items[key].materials)
        } catch (e) {

        }
    })

    data.handles_hash = get_hash(data.handles)

    for (let i = 0; i < data.handles.length; i++){
        try{
            data.handles[i].variants = JSON.parse(data.handles[i].variants)
        } catch (e) {

        }
    }

    for (let i = 0; i < data.glass_items.length; i++){
        try{
            data.glass_items[i].params = JSON.parse(data.glass_items[i].params)
        } catch (e) {

        }
    }


    data.materials_categories_top = [];
    for (let i = 0; i < data.materials_categories.length; i++){
        if(!data.materials_categories[i].parent || data.materials_categories[i].parent == 0){
            data.materials_categories_top.push(data.materials_categories[i])
        }
    }

    data.materials_tree = get_tree(data.materials_items, data.materials_categories)


    data.glass_categories_top = [];
    for (let i = 0; i < data.glass_categories.length; i++){
        if(!data.glass_categories[i].parent || data.glass_categories[i].parent == 0){
            data.glass_categories_top.push(data.glass_categories[i])
        }
    }

    data.glass_tree = get_tree(data.glass_items, data.glass_categories)

    for (let i = data.settings.available_materials_cokol.length; i--;){
        if(!data.settings.available_materials_cokol[i]) data.settings.available_materials_cokol.splice(i,1);
        if(!data.materials_tree[data.settings.available_materials_cokol[i]]){
            data.settings.available_materials_cokol.splice(i,1)
        }
    }

    for (let i = data.settings.available_materials_glass.length; i--;){
        if(!data.settings.available_materials_glass[i]) data.settings.available_materials_glass.splice(i,1);
        if(!data.glass_tree[data.settings.available_materials_glass[i]]){
            data.settings.available_materials_glass.splice(i,1)
        }
    }

    if(!data.settings.available_materials_back_wall)
        data.settings.available_materials_back_wall = {

        };


    if(!data.settings.available_materials_glass_shelves){
        data.settings.available_materials_glass_shelves = {

        };
    }



    init_vue();
})
function init_vue() {
    app = new Vue({
        el: '#bp_app',
        components:{
            'v-select': VueSelect.VueSelect
        },
        data: {
            project_settings:{},
            current_facade_materials: [],
            current_handle_variants: []
        },
        computed:{
            computed_facade_materials: function () {
               if(!this.current_facade_materials.length) return '';
                let result = this.get_materials_from_cats(this.current_facade_materials);
                // this.project_settings.selected_material_facade = result[0].id;
                return result
            },
            get_materials_corpus: function(){
                return this.get_materials_from_cats(this.project_settings.available_materials_corpus);
            },
            get_materials_cokol: function(){
                return this.get_materials_from_cats(this.project_settings.available_materials_cokol);
            },
            get_materials_tabletop: function(){
                return this.get_materials_from_cats(this.project_settings.available_materials_tabletop);
            },
            get_materials_wallpanel: function(){
                return this.get_materials_from_cats(this.project_settings.available_materials_wallpanel);
            },
            get_materials_walls: function(){
                return this.get_materials_from_cats(this.project_settings.available_materials_walls);
            },
            get_materials_floor: function(){
                return this.get_materials_from_cats(this.project_settings.available_materials_floor);
            },
            get_materials_glass: function () {
                return this.get_glass_from_cats(this.project_settings.available_materials_glass);
            }
        },
        created: function(){

            if(!data.handles_hash[data.settings.handle_selected_model]){
                data.settings.handle_selected_model = data.handles_hash[Object.keys(data.handles_hash)[0]].id
                data.settings.handle_preferable_size = 0;
            }

            // if(data.handles_hash[data.settings.handle_selected_model]){
            //     Vue.set(this,'current_handle_variants', data.handles_hash[data.settings.handle_selected_model].variants)
            // } else {
            //     Vue.set(this.project_settings,'handle_selected_model', data.handles_hash[Object.keys(data.handles_hash)[0]].id)
            // }

            console.log(copy_object(data.settings))

            Vue.set(this,'current_handle_variants', data.handles_hash[data.settings.handle_selected_model].variants)

            Vue.set(this,'project_settings',data.settings)

            if(!data.facades_items_hash[data.settings.selected_facade_model]){
                data.settings.selected_facade_model = Object.keys(data.facades_items_hash)[0];
            }

            if(!data.facades_items_hash[data.settings.selected_facade_model]){
                Vue.set(this,'current_facade_materials', data.facades_items_hash[Object.keys(data.facades_items_hash)[0]].materials)
            } else {
                if(data.facades_items_hash[data.settings.selected_facade_model]){
                    Vue.set(this,'current_facade_materials', data.facades_items_hash[data.settings.selected_facade_model].materials)
                }
            }




            this.$options.facades_items = data.facades_items;
            this.$options.facades_categories = data.facades_categories;

            this.$options.materials_tree = data.materials_tree;
            this.$options.materials_categories_top = data.materials_categories_top;
            this.$options.cornice_items = data.cornice_items;

            this.$options.glass_tree = data.glass_tree;
            this.$options.glass_categories_top = data.glass_categories_top;

            this.$options.available_dsp_thickness = ['16','18']




            this.$options.handles = data.handles;
        },
        mounted(){
            console.log(copy_object(data.settings))
        },
        methods: {
            get_materials_from_cats(arr, key){
                let result = [];

                for (let i = 0; i < arr.length; i++){
                    if(data.materials_tree[ arr[i] ]){

                        let cat = data.materials_tree[ arr[i] ];

                        for (let it = 0; it < cat.items.length; it++){
                            result.push(cat.items[it])
                        }

                        for (let c = 0; c < cat.categories.length; c++){
                            for (let it = 0; it < cat.categories[c].items.length; it++){
                                result.push(cat.categories[c].items[it])
                            }
                        }
                    }
                }

                return result;
            },

            get_glass_from_cats(arr, key){

                if(!arr) return [];
                let result = [];



                for (let i = 0; i < arr.length; i++){
                    if(data.glass_tree[ arr[i] ]){

                        let cat = data.glass_tree[ arr[i] ];

                        for (let it = 0; it < cat.items.length; it++){
                            result.push(cat.items[it])
                        }

                        for (let c = 0; c < cat.categories.length; c++){
                            for (let it = 0; it < cat.categories[c].items.length; it++){
                                result.push(cat.categories[c].items[it])
                            }
                        }
                    }
                }


                return result;
            },

            select_facade: function (value) {
                this.project_settings.selected_material_facade = null;
                Vue.set(this,'current_facade_materials', data.facades_items_hash[value].materials)
            },

            select_handle: function (value) {
                Vue.set(this,'current_handle_variants', data.handles_hash[value].variants)
                Vue.set(this.project_settings,'handle_preferable_size', 0)
            },
            correct_url: function(path){
                if(!path) return '';
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return glob.acc_url + path;
                }
            },

            add_back_wall_type(){

                let keys = Object.keys(this.project_settings.available_materials_back_wall);
                let last_key = keys.pop();
                let last_index = 'type_1';



                if(last_key){
                    last_index = 'type_' + (parseInt(last_key.split('_')[1]) + 1)
                }




                Vue.set(this.project_settings.available_materials_back_wall, last_index, {
                    name: '',
                    mode: 'custom',
                    materials: [],
                    default: null,
                    offset: {
                        width_type: 'custom',
                        height_type: 'custom',
                        width: 0,
                        height: 0
                    },
                    thickness: 0
                })


            },
            remove_back_wall_type(key){
                let scope = this;

                show_warning_yes_no(function () {
                    Vue.delete(scope.project_settings.available_materials_back_wall, key)
                })
            },

            add_glass_shelve_type(){

                let keys = Object.keys(this.project_settings.available_materials_glass_shelves);
                let last_key = keys.pop();
                let last_index = 'type_1';


                if(last_key){
                    last_index = 'type_' + (parseInt(last_key.split('_')[1]) + 1)
                }


                Vue.set(this.project_settings.available_materials_glass_shelves, last_index, {
                    name: '',
                    mode: 'custom',
                    materials: [],
                    default: null,
                    thickness: 0
                })


            },
            remove_glass_shelve_type(key){
                let scope = this;

                show_warning_yes_no(function () {
                    Vue.delete(scope.project_settings.available_materials_glass_shelves, key)
                })
            },

            send_data: async function () {
                let data = copy_object(this.project_settings)
                console.log(data)


                if(data.cokol_as_corpus == 1){
                    data.selected_material_cokol = data.selected_material_corpus
                }

                let form_data = new FormData();

                let errs = 0;

                Object.keys(data.available_materials_back_wall).forEach(function (key) {
                    if(data.available_materials_back_wall[key].mode == 'corpus' || data.available_materials_back_wall[key].mode == 'custom_as_corpus'){
                        data.available_materials_back_wall[key].materials = data.available_materials_corpus;
                    }
                    if(data.available_materials_back_wall[key].mode == 'custom'){
                        if(!data.available_materials_back_wall[key].materials.length){


                            toastr.error('Выберите материалы задней стенки в типе ' + data.available_materials_back_wall[key].name)
                            errs = 1;
                        }
                        if(!data.available_materials_back_wall[key].default){


                            toastr.error('Не выбран материал по умолчанию задней стенки в типе ' + data.available_materials_back_wall[key].name)
                            errs = 1;
                        }
                    }

                })

                if(errs) return


                // Object.keys(data).forEach(function (key) {
                //     try{
                //         if(typeof data[key] == 'object') data[key] = JSON.stringify(data[key]);
                //     } catch (e) {
                //
                //     }
                //
                //
                //
                //     form_data.append(key, data[key])
                //
                // })
                form_data.append('settings', JSON.stringify(this.project_settings))




                let res = await promise_request_post(glob.base_url + '/project_settings/save_data_new', form_data)

                // console.log(res)

                if(res == 'ok') toastr.success(glob.lang['success']);
            }
        }
    });
}


function get_tree(items, categories) {
    let data = {
        items: items,
        categories: categories
    };

    let cat_h = Object.create(null)

    data.categories.forEach(function (obj) {
        obj.categories = [];
        obj.items = [];
        cat_h[obj.id] = obj;
    })

    for (let i = 0; i < data.items.length; i++){
        if (data.items[i].category in cat_h) {
            if (!cat_h[data.items[i].category].items) cat_h[data.items[i].category].items = [];
            cat_h[data.items[i].category].items.push(data.items[i])
        }
    }

    // let dataTree = []
    for (let i = 0; i < data.categories.length; i++){
        if( data.categories[i].parent && data.categories[i].parent != 0 ){
            if(cat_h[data.categories[i].parent])
                cat_h[data.categories[i].parent].categories.push(cat_h[data.categories[i].id])
        }
        // else{
            // dataTree.push(cat_h[data.categories[i].id])
        // }
    }


    return cat_h;
}



function check_material_in_category(id, arr) {
    let cat_id = -1;

    for (let i = 0; i < data.materials_items.length; i++){
        if(data.materials_items[i].id == id){
            cat_id = data.materials_items[i].category;
            break;
        }
    }

    if(cat_id > -1){
        if(data.materials_tree[cat_id]){
            let par = data.materials_tree[cat_id].id;

            if(data.materials_tree[cat_id].parent && data.materials_tree[cat_id].parent !=0){
                par = data.materials_tree[data.materials_tree[cat_id].parent].id;
            }

            if(arr.includes(par)) return 1;
        }
    }

    return 0;
}

