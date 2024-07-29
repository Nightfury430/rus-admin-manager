document.addEventListener('Glob_ready', async function () {

    // categories_data = await promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name);
    //
    // categories_data.unshift({
    //     id: '0',
    //     name: glob.lang['no'],
    //     parent: '0'
    // })
    //
    // categories_hash = get_hash(categories_data);
    // categories_ordered = flatten(create_tree(categories_data));
    //
    // if(glob.item_id != 0){
    //     item = await promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id);
    //     item = JSON.parse(item.data);
    //     if(!item.mat_categories) item.mat_categories = [];
    //
    // }


    init_vue();
})

function init_vue() {



    app = new Vue({
        el: '#app',
        data: {
            item:{
                id: 0,
                name: '',
                category: '0',
                bottom_as_top_facade_models: 0,
                bottom_as_top_facade_materials: 0,
                facades_selected_material_top: 0,
                facades_selected_material_bottom: 0,
                facades_models_top: 0,
                facades_models_bottom: 0,
                allow_facades_materials_select: 0,

                glass_materials: [],
                selected_glass_material: 0,
                allow_glass_materials_select: 0,

                bottom_as_top_corpus_materials: 0,
                corpus_materials_top: [],
                selected_corpus_material_top: 0,
                selected_corpus_material_bottom: 0,
                allow_corpus_materials_select: 0,

                cokol_active: 1,
                cokol_height: 120,
                cokol_materials: [],
                cokol_as_corpus: 1,
                selected_cokol_material: 0,
                allow_cokol_materials_select: 1,

                tabletop_thickness: 40,
                tabletop_materials: [],
                selected_tabletop_material: 0,
                allow_tabletop_materials_select: 1,

                wallpanel_height: 550,
                wallpanel_materials: [],
                selected_wallpanel_material: 0,
                allow_wallpanel_materials_select: 1,

                no_handle: 0,
                handle_orientation: 'vertical',
                handle_lockers_position: 'top',
                available_handles: [],
                handle_selected_model: 0,
                handle_preferable_size: 0,
                allow_handles_select: 0,

                cornice_available: 0,
                cornice_active: 0,

                available_modules: 0,
                fixed_materials: '',
                facades_categories: []
            },
            handle_variants: [],
            module_sets: [],
            multiple_facades_mode: 0,
            options_ready: false,
            f_mats: {
                facades_models_top: [],
                facades_models_bottom: []
            }
        },
        components: {
            'v-select': VueSelect.VueSelect,
        },
        computed: {},
        mounted: function(){

        },
        beforeCreate: function(){
            this.$options.lang = glob.lang;
        },
        created: async function () {
            // let categories_data = await promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name);
            // categories_data.unshift({
            //     id: '0',
            //     name: glob.lang['no'],
            //     parent: '0'
            // })
            //
            // this.$options.cat_hash = get_hash(categories_data);
            // this.$options.cat_ordered = flatten(create_tree(categories_data));

            let modules_sets = await promise_request(glob.base_url + '/catalog/items_get/module_sets/1');
            modules_sets.unshift({
                id: 0,
                name: this.$options.lang['choose_modules_set']
            })

            Vue.set(this, 'module_sets', modules_sets)

            let settings = await promise_request(glob.base_url + '/constructor/get_constructor_settings');

            if(settings.settings){
                settings = JSON.parse(settings.settings)
            }

            this.multiple_facades_mode = settings.multiple_facades_mode


            if(glob.item_id != 0){
                let item = await promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id);

                Object.keys(item).forEach((k)=>{
                    try{
                        item[k] = JSON.parse(item[k]);
                    } catch (e) {

                    }
                })

                if(item.settings){
                    item = item.settings;
                }

                console.log(item)

                if(!item.category) item.category = "0"
                if(!item.facades_categories) item.facades_categories = [];

                for (let i = item.facades_categories.length; i--;){
                    if(!item.facades_categories[i]) item.facades_categories.splice(i,1)
                }

                Vue.set(this, 'item', item)
                await this.set_facade_materials(this.item.facades_models_top, 'facades_models_top')
                await this.set_facade_materials(this.item.facades_models_bottom, 'facades_models_bottom')

            }

            this.options_ready = true;



            let int = setInterval(()=>{

                if(this.$refs.handle_sel && this.$refs.handle_sel.item_data && this.$refs.handle_sel.item_data.variants){
                    this.set_handle_variants();
                    clearInterval(int)
                }

            }, 1000)




        },
        beforeMount: async function () {

        },
        methods: {
            lang: function(key){
                return glob.lang[key]
            },
            get_data(){
                return copy_object(this.item)
            },

            set_facade_materials: async function(id, key){
                let facade_data = await promise_request(glob.base_url + '/catalog/get_item/facades/' + id);
                let materials = JSON.parse(facade_data['materials']);
                Vue.set(this.f_mats, key, materials)
            },

            change_facades: async function(id, key){

                if(this.item.bottom_as_top_facade_models == 1){
                    this.item.facades_models_top = id;
                    this.item.facades_models_bottom = id;

                    await this.set_facade_materials(this.item.facades_models_top, 'facades_models_top')
                    await this.set_facade_materials(this.item.facades_models_bottom, 'facades_models_bottom')
                } else {
                    this.item[key] = id;
                    await this.set_facade_materials(this.item[key], key)
                }

            },
            change_corpus_eq: function(){
                if(this.item.bottom_as_top_corpus_materials == 1){
                    this.item.selected_corpus_material_bottom =  this.item.selected_corpus_material_top;
                    this.copy_cokol_from_corpus();
                }
            },
            change_corpus: function(id, key){
                if(this.item.bottom_as_top_corpus_materials == 1){
                    this.item.selected_corpus_material_top = id;
                    this.item.selected_corpus_material_bottom = id;
                } else {
                    this.item[key] = id;
                }
                this.copy_cokol_from_corpus();
            },
            change_corpus_categories: function (e) {
                this.item.corpus_materials_top = e
                this.copy_cokol_from_corpus();
            },

            change_cokol_as_corpus(){
                this.copy_cokol_from_corpus();
            },

            copy_cokol_from_corpus(){
                if(this.item.cokol_as_corpus == 1){
                    this.item.cokol_materials = copy_object(this.item.corpus_materials_top)
                    this.item.selected_cokol_material = copy_object(this.item.selected_corpus_material_bottom)
                }
            },

            change_handle(e){
                this.item.handle_selected_model = e
                this.handle_preferable_size = 0;
                this.set_handle_variants();
            },

            set_handle_variants(){
                let variants = JSON.parse(this.$refs.handle_sel.item_data.variants)
                Vue.set(this, 'handle_variants', variants)
            },


            submit: async function (e) {


                let fdata = new FormData();
                let data = app.get_data();

                let dta = {};
                dta.id = data.id;
                dta.category = data.category;
                dta.name = data.name;
                dta.order = data.order;
                dta.active = data.active;
                dta.settings = JSON.stringify(data);



                fdata.append('data', JSON.stringify(dta))



                let res = await promise_request_post(glob.base_url + '/catalog/add_item_v2/' + glob.controller_name + '/1', fdata);

                // location.href = glob.base_url + '/kitchen_models/index/'

                //
                // let fdata = new FormData();
                // let data = app.get_data();
                // fdata.append('id', data.id)
                // fdata.append('name', data.name)
                // fdata.append('icon', data.icon)
                // fdata.append('data', JSON.stringify(data))
                // fdata.append('active', data.active)
                // fdata.append('order', data.order)
                //
                // let res = promise_request_post(url, fdata)
                // console.log(res)
                location.href = this.$refs.success_url.value
                // return false;
            }
        }
    });
}


