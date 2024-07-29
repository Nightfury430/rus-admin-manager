document.addEventListener('Glob_ready', async function(){

    Vue.component('v-select', VueSelect.VueSelect)

    // let requests = [];
    // requests.push(promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name))
    // requests.push(promise_request(glob.base_url + '/materials/get_all_data_ajax'))
    // if(glob.item_id != 0){
    //     requests.push(promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id))
    // }
    //
    // let result = await Promise.all(requests);
    //
    // let categories_data = convert_categories(result[0]);
    // function convert_categories(data) {
    //     let categories = {};
    //     let tmp = [];
    //     let all = {};
    //     for (let i = 0; i < data.length; i++ ){
    //         let cat = data[i];
    //         cat.name = cat.name.replace(/<[^>]+>/g, '')
    //         all[cat.id] = cat;
    //         if(cat.parent == 0){
    //             cat.children = {};
    //             categories[cat.id] = cat;
    //         } else {
    //             tmp.push(cat);
    //         }
    //     }
    //
    //     for (let i = 0; i < tmp.length; i++ ){
    //         let cat = tmp[i];
    //         if(!cat) continue
    //         if(!categories[cat.parent]) continue
    //         categories[cat.parent].children[cat.id] = cat;
    //
    //     }
    //
    //     categories[0] = {
    //         name: 'Нет',
    //         id: 0,
    //         parent: '0'
    //     }
    //
    //     all[0] = categories[0];
    //
    //     return {
    //         all: all,
    //         categories: categories
    //     }
    //
    // }
    // return

    let cornice_data = await promise_request(glob.base_url + '/catalog/' + 'items_get' + '/' + 'cornice')
    cornice_data.unshift({
        id: '0',
        name: glob.lang['no']
    })
    console.log(cornice_data)

    let facade_data = await promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id)

    app = new Vue({
        el: '#sub_form',
        data: {
            active: 1,
            facade_obj:{
                id:0,
                active: 1,
                order: 100000,
                name: '',
                code: '',
                thickness: 18,
                thickness_side: 18,
                compatibility_types:{},
                category: '0',
                materials: [],
                prices:{},
                prices_edge: {},
                prices_fixed: [],
                prices_decor: [],
                icon: '',
                handle: 1,
                handle_offset: 30,
                types: {},
                types_radius:{},
                types_double: {},
                types_triple: {},
                double_offset: 0,
                accessories: [],
                triple_decor_model: {
                    model: '',
                    model_file: '',
                    current_model: '',
                    height: 0,
                    thickness: 0,
                    offset: 0,
                },
                additional_materials:{},
                rec_cornice: '0'
            },
            icon_file: '',
            materials:{},
            mat_cats: [],
            mat_cats_all: {},
            mats:[],
            filter_cat: null,
            mat_search: '',
            selected_add_mat:null,
            categories: {
                0: {
                    name: 'Нет',
                    parent: '0'
                }
            },
            all_categories:{
                0: {
                    name: 'Нет',
                    parent: '0'
                }
            },
            prices_obj:{},
            prices_edge_obj:{},
            active_tab: '0',
            active_tab_double: '0',
            active_tab_triple: '0',
            active_material_tab: '0',
            add_modal: false,
            new_type_name: '',
            yesno_type: '',
            material_select_modal:false,
            errors: [],
            settings: {},
            kitchen_models: [],
            show_success_message: false,

            file_target: null,
            file_target_type: null,
            file_target_item: null,
            file_target_block: null,

            options_ready: false
        },
        mats:{},
        cats_hash:{},
        computed:{

            types_length: function(){
                return Object.keys(this.facade_obj.types).length;
            },

            computed_cats: function () {
                let scope = this;
                let result = [];

                if(scope.selected_add_mat != null){
                    if(scope.facade_obj.additional_materials[scope.selected_add_mat].fixed != 1){
                        let cats = scope.facade_obj.additional_materials[scope.selected_add_mat].materials;

                        for (let i = 0; i < cats.length; i++){

                            result.push(scope.$options.cats_hash[cats[i]]);

                            for (let c = 0; c < scope.$options.cats_hash[cats[i]].children.length; c++){
                                result.push(scope.$options.cats_hash[cats[i]].children[c])
                            }


                        }
                    } else {
                        for (let i = 0; i < scope.mat_cats.length; i++){

                            result.push(scope.$options.cats_hash[scope.mat_cats[i].id]);

                            for (let c = 0; c < scope.$options.cats_hash[scope.mat_cats[i].id].children.length; c++){
                                result.push(scope.$options.cats_hash[scope.mat_cats[i].id].children[c])
                            }


                        }
                    }
                } else {
                    result = scope.mat_cats;
                }

                return result;
            },
            computed_cats_with_children: function () {
                let scope = this;
                let result = [];
                if(scope.selected_add_mat != null){
                    if(scope.facade_obj.additional_materials[scope.selected_add_mat].fixed != 1){
                        let cats = scope.facade_obj.additional_materials[scope.selected_add_mat].materials;

                        for (let i = 0; i < cats.length; i++){

                            result.push(cats[i]);

                            for (let c = 0; c < scope.$options.cats_hash[cats[i]].children.length; c++){
                                result.push(scope.$options.cats_hash[cats[i]].children[c].id)
                            }


                        }

                    }
                }

                return result;
            },
            computed_mats: function () {
                let scope = this;
                let result = [];

                result = this.mats;

                if(scope.selected_add_mat != null){
                    if(scope.facade_obj.additional_materials[scope.selected_add_mat].fixed != 1){
                        result = result.filter(function (item) {

                            let m = scope.computed_cats_with_children;

                            let flag = 0;
                            for (let i = 0; i < m.length; i++){
                                if(item.category == m[i]) flag = 1;
                            }



                            return flag == 1;


                        });
                    }
                }




                if(scope.mat_search != '') result = result.filter(function (item) {
                    return item.name.indexOf(scope.mat_search) > -1 ;
                });

                if (this.filter_cat != null) result = result.filter(function (item) {

                    let flag = 0;
                    if(item.category == scope.filter_cat) flag = 1;
                    if(flag == 0){
                        if(scope.$options.cats_hash[scope.filter_cat].children){

                            for (let i = 0; i < scope.$options.cats_hash[scope.filter_cat].children.length; i++){


                                if(item.category == scope.$options.cats_hash[scope.filter_cat].children[i].id){
                                    flag = 1;
                                    break;
                                }
                            }
                        }
                    }


                    return flag == 1;
                });



                return result;
            },
            computed_icon: function () {
                return URL.createObjectURL(this.icon_file);
            },

            computed_parent_name: function () {
                return this.all_categories[this.all_categories[this.facade_obj.category].parent].name.replace(/<[^>]+>/g, '')
            },

            computed_count: function () {
                return Object.keys(this.facade_obj.types).length;
            },

            computed_count_double: function () {
                return Object.keys(this.facade_obj.types_double).length;
            },

            computed_count_triple: function () {
                return Object.keys(this.facade_obj.types_triple).length;
            },

            computed_types: function () {
                let scope = this;
                return Object.keys(scope.facade_obj.types)
            },

            computed_prices_categories: function () {
                let result = [];
                let scope = this;

                let parent_ids = JSON.parse(JSON.stringify(scope.facade_obj.materials));

                for (let i = 0; i < parent_ids.length; i++){

                    result.push({
                        id: parent_ids[i]
                    });


                    Object.keys(scope.mat_cats_all).forEach(function (k) {
                        if(scope.mat_cats_all[k].parent == parent_ids[i]){
                            result.push({
                                id: scope.mat_cats_all[k].id
                            })
                        }
                    })




                }


                return result;
            }



        },
        mounted(){
            this.$options.acc_url = glob.acc_url;
        },
        created(){
            this.$options.cornice_items = cornice_data;
            this.$options.lang = glob.lang;
        },
        beforeMount(){
            let scope = this;

            if(document.getElementById('facade_id')){

                let acc_url = document.getElementById('acc_base_url').value;

                axios({
                    method: 'get',
                    // url: document.getElementById('ajax_base_url').value + '/facades/item_get_ajax/' + document.getElementById('facade_id').value
                    url: glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id
                }).then(function (msg) {
                    let facade_data;
                    if(!msg.data.data){
                        let cdata = msg.data;

                        facade_data = {
                            name: cdata.name,
                            category: cdata.category,
                            active: cdata.active,
                            materials: JSON.parse(cdata.materials),
                            icon: cdata.icon,
                            types: {},
                            compatibility_types: {},
                            additional_materials:{}
                        };

                        if(cdata.full != null){
                            facade_data.types[0] = {
                                name: 'Глухой',
                                icon: '',
                                items:[],
                            };
                            facade_data.compatibility_types.full = 0;
                            let full_data = JSON.parse(cdata.full);

                            for (let i = 0; i < full_data.length; i++){
                                facade_data.types[0].items.push({
                                    min_width: full_data[i].min_width,
                                    min_height: full_data[i].min_height,
                                    model: full_data[i].model,
                                    group: 'all'
                                })
                            }
                        }

                        if(cdata.window != null){
                            facade_data.types[1] = {
                                name: 'Витрина',
                                icon: '',
                                items:[],
                            };
                            facade_data.compatibility_types.window = 1;
                            let full_data = JSON.parse(cdata.window);

                            for (let i = 0; i < full_data.length; i++){
                                facade_data.types[1].items.push({
                                    min_width: full_data[i].min_width,
                                    min_height: full_data[i].min_height,
                                    model: full_data[i].model,
                                    group: 'all'
                                })
                            }
                        }

                        if(cdata.frame != null){
                            facade_data.types[2] = {
                                name: 'Решетка',
                                icon: '',
                                items:[],
                            };
                            facade_data.compatibility_types.frame = 2;
                            let full_data = JSON.parse(cdata.frame);

                            for (let i = 0; i < full_data.length; i++){
                                facade_data.types[2].items.push({
                                    min_width: full_data[i].min_width,
                                    min_height: full_data[i].min_height,
                                    model: full_data[i].model,
                                    group: 'all'
                                })
                            }
                        }

                        if(cdata.radius_full != null){
                            facade_data.types[3] = {
                                name: 'Радиус глухой',
                                icon: '',
                                items:[],
                                radius: 1,
                            };
                            facade_data.compatibility_types.radius = 3;
                            let full_data = JSON.parse(cdata.radius_full);

                            for (let i = 0; i < full_data.length; i++){
                                facade_data.types[3].items.push({
                                    min_width: full_data[i].min_width,
                                    min_height: full_data[i].min_height,
                                    model: full_data[i].model,
                                    group: 'all'
                                })
                            }
                        }

                        if(cdata.radius_window != null){
                            facade_data.types[4] = {
                                name: 'Радиус витрина',
                                icon: '',
                                items:[],
                                radius: 1
                            };
                            facade_data.compatibility_types.radius_window = 4;
                            let full_data = JSON.parse(cdata.radius_window);

                            for (let i = 0; i < full_data.length; i++){
                                facade_data.types[4].items.push({
                                    min_width: full_data[i].min_width,
                                    min_height: full_data[i].min_height,
                                    model: full_data[i].model,
                                    group: 'all'
                                })
                            }
                        }

                    } else {
                        facade_data = JSON.parse(msg.data.data);

                        if(facade_data.types.constructor !== Object){
                            facade_data.types = Object.assign({}, facade_data.types);
                        }
                        if(facade_data.types_double && facade_data.types_double.constructor !== Object){
                            facade_data.types_double = Object.assign({}, facade_data.types_double);
                        }
                        if(facade_data.types_double && facade_data.types_triple.constructor !== Object){
                            facade_data.types_triple = Object.assign({}, facade_data.types_triple);
                        }



                        let add_mat = {};

                        let m = 0;

                        Object.keys(facade_data.additional_materials).forEach(function (key) {
                            add_mat[m] = facade_data.additional_materials[key]
                            m++;
                        });

                        facade_data.additional_materials = add_mat;
                    }


                    Object.keys(facade_data.types).forEach(function (key) {

                        if(facade_data.types[key].thickness == undefined) facade_data.types[key].thickness = 0;
                        if(facade_data.types[key].nmap == undefined) facade_data.types[key].nmap = '';
                        if(facade_data.types[key].side == undefined){
                            facade_data.types[key].side = 0;
                        }

                        Object.keys(facade_data.types[key].items).forEach(function (k) {

                            if(facade_data.types[key].items[k].max_width == undefined) facade_data.types[key].items[k].max_width = 0;
                            if(facade_data.types[key].items[k].max_height == undefined) facade_data.types[key].items[k].max_height = 0;

                            if(!facade_data.types[key].items[k].open) facade_data.types[key].items[k].open = 'all';

                            if(!facade_data.types[key].items[k].group) facade_data.types[key].items[k].group = 'all'

                        })
                    })


                    if(facade_data.icon){
                        if(facade_data.icon.indexOf('/common_assets') < 0) facade_data.icon = acc_url + facade_data.icon
                    } else {
                        facade_data.icon = '';
                    }

                    if(!facade_data.nmap) facade_data.nmap = '';


                    axios({
                        method: 'get',
                        // url: document.getElementById('ajax_base_url').value + '/facades/get_categories_ajax'
                        url: glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name
                    }).then(function (msg) {
                        let categories = {};
                        let tmp = [];
                        let all = {};
                        for (let i = 0; i < msg.data.length; i++ ){
                            let cat = msg.data[i];
                            cat.name = cat.name.replace(/<[^>]+>/g, '')
                            all[cat.id] = cat;


                            if(cat.parent == 0){
                                cat.children = {};
                                categories[cat.id] = cat;
                            } else {
                                tmp.push(cat);
                            }
                        }

                        for (let i = 0; i < tmp.length; i++ ){
                            let cat = tmp[i];

                            if(!cat) continue
                            if(!categories[cat.parent]) continue
                            categories[cat.parent].children[cat.id] = cat;

                        }

                        categories[0] = {
                            name: 'Нет',
                            id: 0,
                            parent: '0'
                        }

                        all[0] = categories[0];

                        scope.all_categories = all;

                        scope.categories = categories;



                        axios({
                            method: 'get',
                            url: document.getElementById('ajax_base_url').value + '/materials/get_all_data_ajax'
                        }).then(function (msg) {

                            let categories = [];
                            let mats = [];

                            let tmp = [];
                            let mats_opt = {};
                            let cats_opt = {};

                            for (let i = 0; i < msg.data.categories.length; i++ ){

                                let cat = msg.data.categories[i];

                                scope.mat_cats_all[cat.id] = JSON.parse(JSON.stringify(cat));

                                cats_opt[cat.id] = cat;
                                if(cat.parent == 0){
                                    cat.children = [];
                                    categories.push(cat);
                                } else {
                                    tmp.push(cat);
                                }
                            }

                            for (let i = 0; i < msg.data.items.length; i++ ){
                                mats.push(msg.data.items[i]);
                                mats_opt[msg.data.items[i].id] = msg.data.items[i];
                            }

                            mats_opt['length'] = msg.data.items.length;





                            for (let i = 0; i < tmp.length; i++ ){
                                let cat = tmp[i];


                                for (let c = 0; c < categories.length; c++){

                                    if(cat.parent == categories[c].id){
                                        categories[c].children.push(cat)
                                    }
                                }
                            }

                            if(!facade_data.materials) facade_data.materials = [];
                            for(let i = facade_data.materials.length -1; i >= 0 ; i--){
                                if( !scope.mat_cats_all[facade_data.materials[i]] ) facade_data.materials.splice(i,1)
                            }




                            scope.mat_cats = categories;
                            scope.mats = mats;
                            scope.$options.mats = mats_opt;
                            scope.$options.cats_hash = cats_opt;

                            if(!facade_data.compatibility_types) facade_data.compatibility_types = {};
                            if(!facade_data.types_double) facade_data.types_double = {};
                            if(!facade_data.types_triple) facade_data.types_triple = {};
                            if(!facade_data.double_offset) facade_data.double_offset = 0;
                            if(!facade_data.order) facade_data.order = 100000;
                            if(!facade_data.triple_decor_model) facade_data.triple_decor_model =  {
                                model: '',
                                model_file: '',
                                current_model: '',
                                thickness: 0,
                                height: 0,
                                offset: 0,
                            };


                            Object.keys(facade_data.types).forEach(function (key) {
                                if(!facade_data.types[key].icon_file) facade_data.types[key].icon_file = '';

                                if(facade_data.types[key].icon.indexOf('/common_assets') < 0  && facade_data.types[key].icon != ''){
                                    // facade_data.types[key].icon = acc_url + facade_data.types[key].icon;
                                }

                                for (let i = 0; i < facade_data.types[key].items.length; i++){
                                    if(facade_data.types[key].items[i].model == null) facade_data.types[key].items[i].model = '';

                                    if(facade_data.types[key].items[i].model.indexOf('/common_assets') < 0  && facade_data.types[key].items[i].model != ''){
                                        // facade_data.types[key].items[i].model = acc_url + facade_data.types[key].items[i].model;
                                    }

                                    // facade_data.types[key].items[i].current_model = facade_data.types[key].items[i].model;

                                    if(!facade_data.types[key].items[i].model_file) facade_data.types[key].items[i].model_file = '';
                                }

                            });

                            Object.keys(facade_data.types_double).forEach(function (key) {
                                if(!facade_data.types_double[key].icon_file) facade_data.types_double[key].icon_file = '';

                                if(facade_data.types_double[key].icon.indexOf('/common_assets') < 0  && facade_data.types_double[key].icon != ''){
                                    // facade_data.types_double[key].icon = acc_url + facade_data.types_double[key].icon;
                                }

                                for (let i = 0; i < facade_data.types_double[key].items.length; i++){
                                    if(facade_data.types_double[key].items[i].model == null) facade_data.types_double[key].items[i].model = '';

                                    if(facade_data.types_double[key].items[i].model.indexOf('/common_assets') < 0  && facade_data.types_double[key].items[i].model != ''){
                                        // facade_data.types_double[key].items[i].model = acc_url + facade_data.types_double[key].items[i].model;
                                    }

                                    // facade_data.types_double[key].items[i].current_model = facade_data.types_double[key].items[i].model;

                                    if(!facade_data.types_double[key].items[i].model_file) facade_data.types_double[key].items[i].model_file = '';
                                }

                            })

                            Object.keys(facade_data.types_triple).forEach(function (key) {
                                if(!facade_data.types_triple[key].icon_file) facade_data.types_triple[key].icon_file = '';

                                if(facade_data.types_triple[key].icon.indexOf('/common_assets') < 0  && facade_data.types_triple[key].icon != ''){
                                    // facade_data.types_triple[key].icon = acc_url + facade_data.types_triple[key].icon;
                                }

                                for (let i = 0; i < facade_data.types_triple[key].items.length; i++){

                                    if(facade_data.types_triple[key].items[i].model == null) facade_data.types_triple[key].items[i].model = '';

                                    if(facade_data.types_triple[key].items[i].model.indexOf('/common_assets') < 0  && facade_data.types_triple[key].items[i].model != ''){
                                        // facade_data.types_triple[key].items[i].model = acc_url + facade_data.types_triple[key].items[i].model;
                                    }

                                    // facade_data.types_triple[key].items[i].current_model = facade_data.types_triple[key].items[i].model;

                                    if(!facade_data.types_triple[key].items[i].model_file) facade_data.types_triple[key].items[i].model_file = '';
                                }

                            })

                            if(!facade_data.triple_decor_model.model) facade_data.triple_decor_model.model = '';

                            if(facade_data.triple_decor_model.model.indexOf('/common_assets') < 0  && facade_data.triple_decor_model.model != ''){
                                // facade_data.triple_decor_model.model = acc_url + facade_data.triple_decor_model.model;
                            }

                            if(!scope.all_categories[facade_data.category])facade_data.category = 0;

                            facade_data.triple_decor_model.current_model = facade_data.triple_decor_model.model;

                            if(!facade_data.rec_cornice) facade_data.rec_cornice = '0';
                            if(!facade_data.thickness_side) facade_data.thickness_side = facade_data.thickness;
                            if(!facade_data.prices_fixed) facade_data.prices_fixed = [];
                            if(!facade_data.prices_decor) facade_data.prices_decor = [];
                            if(!facade_data.accessories) facade_data.accessories = [];


                            facade_data.category += '';

                            Vue.set(scope.facade_obj, 'name', facade_data.name);
                            Vue.set(scope.facade_obj, 'code', facade_data.code);
                            Vue.set(scope.facade_obj, 'order', facade_data.order);
                            Vue.set(scope.facade_obj, 'thickness', facade_data.thickness);
                            Vue.set(scope.facade_obj, 'thickness_side', facade_data.thickness_side);
                            Vue.set(scope.facade_obj, 'category', facade_data.category);
                            Vue.set(scope.facade_obj, 'materials', facade_data.materials);
                            Vue.set(scope.facade_obj, 'icon', facade_data.icon);
                            Vue.set(scope.facade_obj, 'nmap', facade_data.nmap);
                            Vue.set(scope.facade_obj, 'types', facade_data.types);
                            Vue.set(scope.facade_obj, 'types_double', facade_data.types_double);
                            Vue.set(scope.facade_obj, 'double_offset', facade_data.double_offset);
                            Vue.set(scope.facade_obj, 'types_triple', facade_data.types_triple);
                            Vue.set(scope.facade_obj, 'triple_decor_model', facade_data.triple_decor_model);
                            Vue.set(scope.facade_obj, 'additional_materials', facade_data.additional_materials);
                            Vue.set(scope.facade_obj, 'compatibility_types', facade_data.compatibility_types);
                            Vue.set(scope.facade_obj, 'rec_cornice', facade_data.rec_cornice);
                            Vue.set(scope.facade_obj, 'prices_fixed', facade_data.prices_fixed);
                            Vue.set(scope.facade_obj, 'prices_decor', facade_data.prices_decor);
                            Vue.set(scope.facade_obj, 'accessories', facade_data.accessories);





                            if(facade_data.handle == undefined){
                                facade_data.handle = 1
                            }

                            if(facade_data.handle_offset == undefined){
                                facade_data.handle_offset = 30
                            }

                            Vue.set(scope.facade_obj, 'handle', facade_data.handle);
                            Vue.set(scope.facade_obj, 'handle_offset', facade_data.handle_offset);

                            console.log(facade_data)
                            console.log(facade_data.prices)

                            if(Array.isArray(facade_data.prices)){
                                let i = 0;
                                let prices_obj = {}

                                Object.keys(facade_data.types).forEach((k)=>{
                                    prices_obj[k] = facade_data.prices[i];
                                    i++;
                                })

                                facade_data.prices = prices_obj;


                            }

                            if(Array.isArray(facade_data.prices_edge)){
                                let i = 0;
                                let prices_obj = {}

                                Object.keys(facade_data.types).forEach((k)=>{
                                    prices_obj[k] = facade_data.prices_edge[i];
                                    i++;
                                })

                                facade_data.prices_edge = prices_obj;


                            }



                            if(!facade_data.prices){

                                let obj = {};

                                let price_obj = {}

                                for (let i = 0; i < facade_data.materials.length; i++){

                                    for (let c = 0; c < categories.length; c++){

                                        if(categories[c].id == facade_data.materials[i]){

                                            price_obj[facade_data.materials[i]] = {
                                                // name: categories[c].name,
                                                price: 0,
                                                parent: 0
                                            };

                                            for (let j = 0; j < categories[c].children.length; j++){

                                                price_obj[categories[c].children[j].id] = {
                                                    parent: categories[c].children[j].parent,
                                                    price: 0
                                                }

                                            }
                                        }
                                    }

                                }

                                scope.prices_obj = price_obj;


                                Object.keys(facade_data.types).forEach(function (key) {
                                    obj[key] = JSON.parse(JSON.stringify(scope.prices_obj));
                                });




                                Vue.set(scope.facade_obj, 'prices', obj)


                            } else {
                                let price_obj = {};
                                for (let c = 0; c < categories.length; c++){

                                        for (let i = 0; i < facade_data.materials.length; i++){

                                        if(categories[c].id == facade_data.materials[i]){

                                            price_obj[facade_data.materials[i]] = {
                                                // name: categories[c].name,
                                                price: 0,
                                                parent: 0
                                            };

                                            for (let j = 0; j < categories[c].children.length; j++){



                                                price_obj[categories[c].children[j].id] = {
                                                    parent: categories[c].children[j].parent,
                                                    price: 0
                                                }

                                            }
                                        }
                                    }

                                }


                                Object.keys(price_obj).forEach(function (po_key) {

                                    Object.keys(facade_data.prices).forEach(function (types_key) {


                                        // console.log(po_key);
                                        // console.log(facade_data.prices[types_key][po_key]);

                                       if(!facade_data.prices[types_key][po_key]){

                                           console.log(po_key)

                                            facade_data.prices[types_key][po_key] = {
                                                parent: price_obj[po_key].parent,
                                                price: 0
                                            }
                                       }

                                    })

                                });



                                // console.log(JSON.parse(JSON.stringify(price_obj)))
                                // console.log(JSON.parse(JSON.stringify(facade_data)))
                                // Object.keys(Object.keys(facade_data.prices)[0]).forEach(function (key) {
                                //     let f = 0;
                                //     for (let c = 0; c < categories.length; c++){
                                //         if(categories[c].id == key) f = 1;
                                //     }
                                //
                                //     console.log(key)
                                //     console.log(f)
                                //
                                //     // if(f == 0) {
                                //     //     delete facade_data.prices[key];
                                //     //     delete price_obj[key];
                                //     // }
                                // });
                                scope.prices_obj = price_obj;

                                Vue.set(scope.facade_obj, 'prices', facade_data.prices)


                                if(!scope.all_categories[scope.facade_obj.category]) scope.facade_obj.category = '0';

                            }

                            if(!facade_data.prices_edge){
                                let obj = {};

                                let price_obj = {}

                                for (let i = 0; i < facade_data.materials.length; i++){

                                    for (let c = 0; c < categories.length; c++){

                                        if(categories[c].id == facade_data.materials[i]){

                                            price_obj[facade_data.materials[i]] = {
                                                // name: categories[c].name,
                                                price: 0,
                                                parent: 0
                                            };

                                            for (let j = 0; j < categories[c].children.length; j++){

                                                price_obj[categories[c].children[j].id] = {
                                                    parent: categories[c].children[j].parent,
                                                    price: 0
                                                }

                                            }
                                        }
                                    }

                                }

                                scope.prices_edge_obj = price_obj;


                                Object.keys(facade_data.types).forEach(function (key) {
                                    obj[key] = JSON.parse(JSON.stringify(scope.prices_edge_obj));
                                });





                                Vue.set(scope.facade_obj, 'prices_edge', obj)
                            } else {
                                let price_obj = {};
                                for (let c = 0; c < categories.length; c++){

                                    for (let i = 0; i < facade_data.materials.length; i++){

                                        if(categories[c].id == facade_data.materials[i]){

                                            price_obj[facade_data.materials[i]] = {
                                                // name: categories[c].name,
                                                price: 0,
                                                parent: 0
                                            };

                                            for (let j = 0; j < categories[c].children.length; j++){



                                                price_obj[categories[c].children[j].id] = {
                                                    parent: categories[c].children[j].parent,
                                                    price: 0
                                                }

                                            }
                                        }
                                    }

                                }


                                Object.keys(price_obj).forEach(function (po_key) {

                                    Object.keys(facade_data.prices_edge).forEach(function (types_key) {


                                        // console.log(po_key);
                                        // console.log(facade_data.prices[types_key][po_key]);

                                        if(!facade_data.prices_edge[types_key][po_key]){

                                            console.log(po_key)

                                            facade_data.prices_edge[types_key][po_key] = {
                                                parent: price_obj[po_key].parent,
                                                price: 0
                                            }
                                        }

                                    })

                                });

                                scope.prices_edge_obj = price_obj;

                                Vue.set(scope.facade_obj, 'prices_edge', facade_data.prices_edge)


                                if(!scope.all_categories[scope.facade_obj.category]) scope.facade_obj.category = '0';
                            }



                            scope.options_ready = true;
                        }).catch(function (e) {
                            console.log(e)
                        });




                    }).catch(function (e) {
                        console.log(e)
                    });





                }).catch(function (e) {
                    console.log(e)
                });


                return;
            } else {
                scope.options_ready = true;
            }

            axios({
                method: 'get',
                // url: document.getElementById('ajax_base_url').value + '/facades/get_categories_ajax'
                url: glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name
            }).then(function (msg) {
                let categories = {};
                let tmp = [];
                let all = {};
                for (let i = 0; i < msg.data.length; i++ ){
                    let cat = msg.data[i];
                    cat.name = cat.name.replace(/<[^>]+>/g, '')
                    all[cat.id] = cat;
                    if(cat.parent == 0){
                        cat.children = {};
                        categories[cat.id] = cat;
                    } else {
                        tmp.push(cat);
                    }
                }

                for (let i = 0; i < tmp.length; i++ ){
                    let cat = tmp[i];
                    if(!cat) continue
                    if(!categories[cat.parent]) continue
                    categories[cat.parent].children[cat.id] = cat;

                }

                categories[0] = {
                    name: 'Нет',
                    id: 0,
                    parent: '0'
                }

                all[0] = categories[0];

                scope.all_categories = all;

                scope.categories = categories;


            }).catch(function (e) {
                console.log(e)
            });


            axios({
                method: 'get',
                url: document.getElementById('ajax_base_url').value + '/materials/get_all_data_ajax'
            }).then(function (msg) {

                let categories = [];
                let mats = [];

                let tmp = [];
                let mats_opt = {};
                let cats_opt = {};


                for (let i = 0; i < msg.data.categories.length; i++ ){
                    let cat = msg.data.categories[i];

                    scope.mat_cats_all[cat.id] = JSON.parse(JSON.stringify(cat));


                    cats_opt[cat.id] = cat;
                    if(cat.parent == 0){
                        cat.children = [];
                        categories.push(cat);
                    } else {
                        tmp.push(cat);
                    }
                }

                for (let i = 0; i < msg.data.items.length; i++ ){
                    mats.push(msg.data.items[i]);
                    mats_opt[msg.data.items[i].id] = msg.data.items[i];
                }

                mats_opt['length'] = msg.data.items.length;

                for (let i = 0; i < tmp.length; i++ ){
                    let cat = tmp[i];

                    for (let c = 0; c < categories.length; c++){
                        if(cat.parent == categories[c].id){
                            categories[c].children.push(cat)
                        }
                    }



                }

                scope.mat_cats = categories;
                scope.mats = mats;
                scope.$options.mats = mats_opt;
                scope.$options.cats_hash = cats_opt;

                if(!scope.all_categories[scope.facade_obj.category]) scope.facade_obj.category = '0';




            }).catch(function (e) {
                console.log(e)
            });



        },
        methods: {

            add_fixed_item: function(){
                this.facade_obj.prices_fixed.push({
                    width: 0,
                    height: 0,
                    type: '0',
                    mat: '0',
                    mat_code: '',
                    price: 0
                })
            },
            remove_fixed_item: function(index){
                show_warning_yes_no(()=> {
                    this.facade_obj.prices_fixed.splice(index, 1)
                })
            },

            add_decor_item: function(){

                let types = {}
                Object.keys(this.facade_obj.types).forEach((k)=>{
                    types[k] = 0;
                })

                this.facade_obj.prices_decor.push({
                    mat_code: '',
                    mat_id: 0,
                    price: types,
                    acc_id: 0
                })
            },
            remove_decor_item: function(index){
                show_warning_yes_no(()=> {
                    this.facade_obj.prices_decor.splice(index, 1)
                })
            },

            sel_file: function(file){

                if(!glob.is_common){
                    file.true_path = file.true_path.substr(1);
                }

                if(this.file_target_item != null){
                    this.facade_obj[this.file_target_block][this.file_target_type].items[this.file_target_item][this.file_target] = file.true_path;
                } else if(this.file_target_type != null){
                    this.facade_obj[this.file_target_block][this.file_target_type][this.file_target] = file.true_path;
                } else {
                    this.facade_obj[this.file_target] = file.true_path;
                }

                $('#filemanager').modal('toggle')

                this.file_target = null;
                this.file_target_type = null;
                this.file_target_item = null;

            },
            correct_url: function(path){
                if(path == '') return '/common_assets/images/placeholders/78x125.png'

                let date_time = new Date().getTime();

                if(path.indexOf('common_assets') > -1){
                    return path +  '?' + date_time
                } else {
                    return glob.acc_url + path +  '?' + date_time;
                }
            },
            correct_url_type: function(type, key){
                if(this.facade_obj[type][key].icon == '') return '/common_assets/images/placeholders/78x125.png'

                let date_time = new Date().getTime();

                if(this.facade_obj[type][key].icon.indexOf('common_assets') > -1){
                    return this.facade_obj[type][key].icon +  '?' + date_time
                } else {
                    return glob.acc_url + this.facade_obj[type][key].icon +  '?' + date_time;
                }
            },
            correct_model_url: function(path){
                if(path.indexOf('common_assets') > -1){
                    return path.split('/').pop()
                } else {
                  return path
                }
            },

            correct_download_url(path){
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return glob.acc_url  + path
                }
            },

            check_item_tmp: function(item){
                if(!item.materials.length) item.selected = null
            },

            change_children_price(name, catid){
                let scope = this;
                let keys = [];
                Object.keys(this.mat_cats_all).forEach(function (key) {
                    if(scope.mat_cats_all[key].parent == catid) {
                        keys.push(key);
                    }
                })

                for (let i = 0; i < keys.length; i++){
                    scope.facade_obj.prices[name][keys[i]].price = scope.facade_obj.prices[name][catid].price
                }

            },
            change_children_price_edge(name, catid){
                let scope = this;
                let keys = [];
                Object.keys(this.mat_cats_all).forEach(function (key) {
                    if(scope.mat_cats_all[key].parent == catid) {
                        keys.push(key);
                    }
                })

                for (let i = 0; i < keys.length; i++){
                    scope.facade_obj.prices_edge[name][keys[i]].price = scope.facade_obj.prices_edge[name][catid].price
                }

            },
            computed_compat_type:function (key) {

                let scope = this;

                let result = '';

                if(scope.facade_obj.compatibility_types){
                    Object.keys(scope.facade_obj.compatibility_types).forEach(function (k) {
                        if(scope.facade_obj.compatibility_types[k] == key) result = k;
                    });
                }

                return result;
            },
            set_compat_type: function (val, key) {
                this.facade_obj.compatibility_types[val.target.value] = parseInt(key);
            },


            get_data: function () {
                return JSON.parse(JSON.stringify(this.facade_obj));
            },

            add_type: function () {
                let i = this.computed_count;

                while (this.facade_obj.types[i]) {
                    i++;
                }

                let obj = {
                    name:'Без названия',
                    icon: '',
                    icon_file:'',
                    items: []
                };
                Vue.set( this.facade_obj.types, i, obj);
                Vue.set( this.facade_obj.prices, i, copy_object(this.prices_obj));
                Vue.set( this.facade_obj.prices_edge, i, copy_object(this.prices_obj));

                for (let j = this.facade_obj.prices_decor.length; j--;){
                    Vue.set( this.facade_obj.prices_decor[j].price, i, 0);
                }

                this.active_tab = i;
            },

            remove_type: function (k) {
                let scope = this;

                Vue.delete( this.facade_obj.types, k);
                Vue.delete( this.facade_obj.prices, k);
                Vue.delete( this.facade_obj.prices_edge, k);

                for (let i = this.facade_obj.prices_decor.length; i--;){
                    Vue.delete( this.facade_obj.prices_decor[i].price, k);
                }

                this.active_tab = closest(Object.keys(scope.facade_obj.types), k)
            },

            add_type_double: function () {
                let i = this.computed_count_double;

                while (this.facade_obj.types_double[i]) {
                    i++;
                }

                let obj = {
                    name:'Без названия',
                    icon: '',
                    icon_file:'',
                    items: []
                };
                Vue.set( this.facade_obj.types_double, i, obj);

                this.active_tab_double = i;
            },
            add_type_triple: function () {
                let i = this.computed_count_triple;

                while (this.facade_obj.types_triple[i]) {
                    i++;
                }

                let obj = {
                    name:'Без названия',
                    icon: '',
                    icon_file:'',
                    items: []
                };
                Vue.set( this.facade_obj.types_triple, i, obj);

                this.active_tab_triple = i;
            },

            remove_type_double: function (k) {
                let scope = this;
                Vue.delete( this.facade_obj.types_double, k);
                this.active_tab_double = closest(Object.keys(scope.facade_obj.types_double), k)
            },
            remove_type_triple: function (k) {
                let scope = this;
                Vue.delete( this.facade_obj.types_triple, k);
                this.active_tab_triple = closest(Object.keys(scope.facade_obj.types_triple), k)
            },




            materials_categories_change: function (e) {
                let scope = this;

                let categories = JSON.parse(JSON.stringify(scope.facade_obj.materials))
                let prices_obj_data = JSON.parse(JSON.stringify(scope.prices_obj));
                // let prices_obj_edge_data = JSON.parse(JSON.stringify(scope.prices_edge_obj));

                let parent_ids = [];

                Object.keys(scope.prices_obj).forEach(function (key) {
                    if(scope.prices_obj[key].parent == 0 ) parent_ids.push(key)
                });

                // Object.keys(scope.prices_edge_obj).forEach(function (key) {
                //     if(scope.prices_edge_obj[key].parent == 0 ) parent_ids.push(key)
                // });


                let diff = _.xor(categories, parent_ids)[0];

                if(categories.includes(diff)){
                    //add
                    Object.keys(scope.facade_obj.prices).forEach(function (k) {
                        Vue.set(scope.facade_obj.prices[k], diff, {
                            price: 0,
                            parent: 0
                        })

                        Vue.set(scope.facade_obj.prices_edge[k], diff, {
                            price: 0,
                            parent: 0
                        })

                    })

                    Vue.set(scope.prices_obj, diff, {
                        price: 0,
                        parent: 0
                    });


                    Vue.set(scope.prices_edge_obj, diff, {
                        price: 0,
                        parent: 0
                    });


                    Object.keys(scope.mat_cats_all).forEach(function (key) {
                        if(scope.mat_cats_all[key].parent == diff){

                            Object.keys(scope.facade_obj.prices).forEach(function (k) {
                                Vue.set(scope.facade_obj.prices[k], key, {
                                    price: 0,
                                    parent: diff
                                })
                            })

                            Object.keys(scope.facade_obj.prices_edge).forEach(function (k) {
                                Vue.set(scope.facade_obj.prices_edge[k], key, {
                                    price: 0,
                                    parent: diff
                                })
                            })

                            Vue.set(scope.prices_obj, key, {
                                price: 0,
                                parent: diff
                            } )
                            Vue.set(scope.prices_edge_obj, key, {
                                price: 0,
                                parent: diff
                            } )






                        }
                    })

                } else {
                    //remove

                    Object.keys(scope.facade_obj.prices).forEach(function (k) {
                        Vue.delete(scope.facade_obj.prices[k], diff)
                        Vue.delete(scope.facade_obj.prices_edge[k], diff)
                    })

                    Vue.delete(scope.prices_obj, diff)
                    Vue.delete(scope.prices_edge_obj, diff)

                    Object.keys(scope.mat_cats_all).forEach(function (key) {
                        if(scope.mat_cats_all[key].parent == diff){

                            Object.keys(scope.facade_obj.prices).forEach(function (k) {
                                Vue.delete(scope.facade_obj.prices[k], key)
                                Vue.delete(scope.facade_obj.prices_edge[k], key)
                            })
                            Vue.delete(scope.prices_obj, key)
                            Vue.delete(scope.prices_edge_obj, key)

                        }
                    })
                }

            },

            add_material: function () {
                let scope = this;
                let i = Object.keys(scope.facade_obj.additional_materials).length
                while (this.facade_obj.additional_materials[i]) {
                    i++;
                }

                let obj = {
                    key: '',
                    name: 'Без названия',
                    required: false,
                    fixed: false,
                    materials: [],
                    selected: null
                };

                Vue.set( this.facade_obj.additional_materials, i, obj);
                this.active_material_tab = i;

            },
            remove_material: function (k) {
                let scope = this;
                Vue.delete( this.facade_obj.additional_materials, k);
                this.active_material_tab = closest(Object.keys(scope.facade_obj.additional_materials), k)
            },
            show_swal: function (item, type) {
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
                    if(scope.yesno_type == 'delete_type'){
                        scope.remove_type(item);
                        scope.yesno_type = '';
                    }
                    if(scope.yesno_type == 'delete_type_double'){
                        scope.remove_type_double(item);
                        scope.yesno_type = '';
                    }
                    if(scope.yesno_type == 'delete_type_triple'){
                        scope.remove_type_triple(item);
                        scope.yesno_type = '';
                    }
                    if(scope.yesno_type == 'delete_size'){
                        scope.facade_obj.types[item].items.splice(type, 1)
                        scope.yesno_type = '';
                    }
                    if(scope.yesno_type == 'delete_size_double'){
                        scope.facade_obj.types_double[item].items.splice(type, 1)
                        scope.yesno_type = '';
                    }

                    if(scope.yesno_type == 'delete_size_triple'){
                        scope.facade_obj.types_triple[item].items.splice(type, 1)
                        scope.yesno_type = '';
                    }


                    if(scope.yesno_type == 'delete_material'){
                        scope.remove_material(item);
                        scope.yesno_type = '';
                    }

                });
            },

            show_category_modal: function () {
                this.add_modal = true;
                $('body').addClass('modal-open');
                $('body').append('<div class="modal-backdrop fade show"></div>');
            },
            hide_category_modal: function () {
                this.add_modal = false;
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            },

            show_modal: function () {
                $('body').addClass('modal-open');
                $('body').append('<div class="modal-backdrop fade show"></div>');
            },
            hide_modal: function () {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            },
            set_material: function (id) {
                this.facade_obj.additional_materials[this.selected_add_mat].selected = id;
                this.selected_add_mat = null
            },
            set_category: function (id) {
                this.facade_obj.category = id;
                this.hide_category_modal();
            },

            add_size: function (item,name) {
                console.log(item);
                item.items.push({
                    min_width: 0,
                    max_width: 0,
                    min_height: 0,
                    max_height: 0,
                    model: '',
                    model_file: '',
                    current_model: '',
                    group: 'all',
                    open: 'all'
                });

            },
            add_size_double: function (item,name) {

                console.log(item)

                item.items.push({
                    min_width: 0,
                    max_width: 0,
                    min_height: 0,
                    max_height: 0,
                    model: '',
                    model_file: '',
                    current_model: '',
                    group: 'all',
                    open: 'all'
                });

            },

            preview_model: function (name, index) {

                let scope = this;

                var modal = $('.three_modal_wrapper');
                modal.fadeIn();

                let files_length = 0;

                Object.keys(scope.facade_obj.types).forEach(function (key) {
                    for (let i = 0; i < scope.facade_obj.types[key].items.length; i++){
                        if(scope.facade_obj.types[key].items[i].model_file != '') files_length ++;
                    }
                });

                if(files_length > 0){

                    let loaded_length = 0;

                    Object.keys(scope.facade_obj.types).forEach(function (key) {
                        for (let i = 0; i < scope.facade_obj.types[key].items.length; i++){
                            if(scope.facade_obj.types[key].items[i].model_file != ''){

                                var reader = new FileReader();
                                reader.onload = function(event) {
                                    scope.facade_obj.types[key].items[i].model =  event.target.result;
                                    loaded_length ++
                                };

                                reader.readAsDataURL( scope.facade_obj.types[key].items[i].model_file );

                            } else {
                                scope.facade_obj.types[key].items[i].model = scope.facade_obj.types[key].items[i].current_model;
                            }
                        }
                    });


                    let interval = setInterval(function () {

                        console.log(loaded_length + 'of' + files_length)
                        if (files_length == loaded_length) {

                            clearInterval(interval);

                            init_three_test('three_viewport', ''+ scope.facade_obj.types[name].items[index].model, scope.facade_obj.types[name].items[index].min_width, scope.facade_obj.types[name].items[index].min_height, name );

                        }
                    }, 100);

                } else {
                    init_three_test('three_viewport', ''+ this.facade_obj.types[name].items[index].model, this.facade_obj.types[name].items[index].min_width, this.facade_obj.types[name].items[index].min_height, name );
                }


                $('.close_three_modal').click(function () {
                    modal.fadeOut();
                    $('#three_viewport').html('');
                    renderer.renderLists.dispose();
                })
            },
            preview_model_new: function (item, index, name, type) {

                let scope = this;

                var modal = $('.three_modal_wrapper');
                modal.fadeIn();

                let files_length = 0;

                console.log(item)

                    for (let i = 0; i < item.items.length; i++){
                        if(item.items[i].model_file != '') files_length ++;
                    }


                if(files_length > 0){

                    let loaded_length = 0;


                        for (let i = 0; i < item.items.length; i++){
                            if(item.items[i].model_file != ''){

                                var reader = new FileReader();
                                reader.onload = function(event) {
                                    item.items[i].model =  event.target.result;
                                    loaded_length ++
                                };

                                reader.readAsDataURL( item.items[i].model_file );

                            } else {
                                item.items[i].model = item.items[i].current_model;
                            }
                        }



                    let interval = setInterval(function () {

                        console.log(loaded_length + 'of' + files_length)
                        if (files_length == loaded_length) {

                            clearInterval(interval);

                            init_three_test('three_viewport', ''+ item.items[index].model, item.items[index].min_width, item.items[index].min_height, name , type);

                        }
                    }, 100);

                } else {

                    console.log(12321313)

                    init_three_test('three_viewport', ''+ item.items[index].model, item.items[index].min_width, item.items[index].min_height, name , type);
                }


                $('.close_three_modal').click(function () {
                    modal.fadeOut();
                    $('#three_viewport').html('');
                    renderer.renderLists.dispose();
                })


            },

            get_mats: function () {
                let scope = this;
                let result = [];
                let acc_url = document.getElementById('acc_base_url').value;

                result = {};
                result[0] = {
                    id: 0,
                    add_params: {
                        real_width: "1024",
                        real_height: "1024",
                        stretch_width: "1",
                        stretch_height: "1",
                        wrapping: "mirror"
                    },

                    params: {color: "#8ca1a9", roughness: 1, metalness: 0},
                    type: "Standart"
                }

                Object.keys(scope.facade_obj.additional_materials).forEach(function (k) {

                    result['add_' + k] = {
                        id: 'add_' + k,
                        add_params: {
                            real_width: "1024",
                            real_height: "1024",
                            stretch_width: "1",
                            stretch_height: "1",
                            wrapping: "mirror"
                        },
                        params: {color: "#ffffff", roughness: 1, metalness: 0},
                        type: "Standart"
                    }

                })
                return result;
            },
            submit: function (e) {
                e.preventDefault();
                let scope = this;
                let data = scope.get_data();


                let add_mat_obj = {};
                Object.keys(data.additional_materials).forEach(function (key) {
                    add_mat_obj[data.additional_materials[key].key] = data.additional_materials[key]
                });

                data.additional_materials = add_mat_obj;

                let acc_url = document.getElementById('acc_base_url').value;

                data.icon = data.icon.replace(acc_url,'');
                data.triple_decor_model.model = data.triple_decor_model.model.replace(acc_url,'');


                Object.keys(scope.facade_obj.types).forEach(function (key) {
                    scope.facade_obj.types[key].items.sort(function (a, b) {
                        return (a.min_height - b.min_height) || (a.min_width - b.min_width)
                    });
                });

                Object.keys(scope.facade_obj.types_double).forEach(function (key) {
                    scope.facade_obj.types_double[key].items.sort(function (a, b) {
                        return (a.min_height - b.min_height) || (a.min_width - b.min_width)
                    });
                });

                Object.keys(scope.facade_obj.types_triple).forEach(function (key) {
                    scope.facade_obj.types_triple[key].items.sort(function (a, b) {
                        return (a.min_height - b.min_height) || (a.min_width - b.min_width)
                    });
                });





                Object.keys(data.types).forEach(function (key) {
                    data.types[key].icon = data.types[key].icon.replace(acc_url,'');

                    data.types[key].items.sort(function (a, b) {
                        return (a.min_height - b.min_height) || (a.min_width - b.min_width)
                    });

                    for (let i = 0; i < data.types[key].items.length; i++){
                        data.types[key].items[i].model = data.types[key].items[i].model.replace(acc_url,'');
                    }
                });




                Object.keys(data.types_double).forEach(function (key) {
                    data.types_double[key].icon = data.types_double[key].icon.replace(acc_url,'');

                    data.types_double[key].items.sort(function (a, b) {
                        return (a.min_height - b.min_height) || (a.min_width - b.min_width)
                    });

                    for (let i = 0; i < data.types_double[key].items.length; i++){
                        data.types_double[key].items[i].model = data.types_double[key].items[i].model.replace(acc_url,'');
                    }
                });

                Object.keys(data.types_triple).forEach(function (key) {
                    data.types_triple[key].icon = data.types_triple[key].icon.replace(acc_url,'');

                    data.types_triple[key].items.sort(function (a, b) {
                        return (a.min_height - b.min_height) || (a.min_width - b.min_width)
                    });

                    for (let i = 0; i < data.types_triple[key].items.length; i++){
                        data.types_triple[key].items[i].model = data.types_triple[key].items[i].model.replace(acc_url,'');
                    }
                });

                let files_arr = {};



                Object.keys(scope.facade_obj.types).forEach(function (k) {
                    data.types[k].icon = data.types[k].icon.replace(acc_url,'');
                    if( scope.facade_obj.types[k].icon_file && scope.facade_obj.types[k].icon_file != ''){
                        files_arr['icon_types_' + k] = scope.facade_obj.types[k].icon_file;
                        delete data.types[k].icon_file;
                    }

                    console.log(copy_object(scope.facade_obj.types[k].items))

                    for (let i = 0; i < scope.facade_obj.types[k].items.length; i++){
                        data.types[k].items[i].model = data.types[k].items[i].model.replace(acc_url,'');

                        if( scope.facade_obj.types[k].items[i].model_file &&
                            scope.facade_obj.types[k].items[i].model_file != ''){
                            files_arr['model_types_' + k + '_' + i] = scope.facade_obj.types[k].items[i].model_file;
                        }
                        delete data.types[k].items[i].model_file;
                        delete data.types[k].items[i].current_model;
                    }

                });

                Object.keys(scope.facade_obj.types_double).forEach(function (k) {
                    data.types_double[k].icon = data.types_double[k].icon.replace(acc_url,'');
                    if( scope.facade_obj.types_double[k].icon_file && scope.facade_obj.types_double[k].icon_file != ''){
                        files_arr['icon_typesdouble_' + k] = scope.facade_obj.types_double[k].icon_file;
                        delete data.types_double[k].icon_file;
                    }

                    for (let i = 0; i < scope.facade_obj.types_double[k].items.length; i++){
                        data.types_double[k].items[i].model = data.types_double[k].items[i].model.replace(acc_url,'');

                        if( scope.facade_obj.types_double[k].items[i].model_file &&
                            scope.facade_obj.types_double[k].items[i].model_file != ''){
                            files_arr['model_typesdouble_' + k + '_' + i] = scope.facade_obj.types_double[k].items[i].model_file;
                        }
                        delete data.types_double[k].items[i].model_file;
                        delete data.types_double[k].items[i].current_model;

                    }

                });

                Object.keys(scope.facade_obj.types_triple).forEach(function (k) {
                    data.types_triple[k].icon = data.types_triple[k].icon.replace(acc_url,'');
                    if( scope.facade_obj.types_triple[k].icon_file && scope.facade_obj.types_triple[k].icon_file != ''){
                        files_arr['icon_typestriple_' + k] = scope.facade_obj.types_triple[k].icon_file;
                        delete data.types_triple[k].icon_file;
                    }

                    for (let i = 0; i < scope.facade_obj.types_triple[k].items.length; i++){
                        data.types_triple[k].items[i].model = data.types_triple[k].items[i].model.replace(acc_url,'');

                        if( scope.facade_obj.types_triple[k].items[i].model_file &&
                            scope.facade_obj.types_triple[k].items[i].model_file != ''){
                            files_arr['model_typestriple_' + k + '_' + i] = scope.facade_obj.types_triple[k].items[i].model_file;
                        }
                        delete data.types_triple[k].items[i].model_file;
                        delete data.types_triple[k].items[i].current_model;
                    }

                });

                if(data.triple_decor_model.current_model) delete data.triple_decor_model.current_model;



                let formData = new FormData();


                Object.keys(files_arr).forEach(function (k) {
                    formData.append(k, files_arr[k]);
                });




                if(scope.icon_file != ''){
                    formData.append('icon', scope.icon_file)


                }

                if(scope.facade_obj.triple_decor_model.model_file != ''){
                    formData.append('triple_decor_model', scope.facade_obj.triple_decor_model.model_file)
                }



                data.v = 2;

                data.has_side = 0;
                Object.keys(scope.facade_obj.types).forEach((k)=> {
                    if(scope.facade_obj.types[k].side == 1) data.has_side = 1;
                });



                formData.append('data', JSON.stringify(data));


                $.ajax({
                    url : $('#sub_form').attr('action'),
                    type : 'POST',
                    data : formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success : function(msg) {
                        // console.log(msg);
                        window.location = document.getElementById('form_success_url').value
                    }
                });

                return false;

            }

        }
    });





    function closest(arr, goal) {
        if(!arr.length) return 0;

        return arr.reduce(function(prev, curr) {
            return (Math.abs(curr - goal) < Math.abs(prev - goal) ? curr : prev);
        });
    }

    function init_three_test(element_id, model, mw, mh, style, doubtrip) {

        if(!doubtrip) doubtrip = 0;

        var fac;
        var fac2;
        var fac3;


        let cur_width = 396;
        let cur_height = 716;
        let cur_depth = 16;

        if(parseInt(mw) !== 0){
            cur_width = parseInt(mw);
        }

        if(parseInt(mh) !== 0){
            cur_height = parseInt(mh);
        }

        var controls_div = $('<div id="three_viewport_controls"></div>');
        $('#three_viewport').append(controls_div);

        var heights = [136, 176, 356, 536, 716, 916, 946];
        var widths = [96, 196, 296, 346, 396, 446, 496, 596, 796];


        var width_input = $('<input type="number" value="'+cur_width+'">');
        var height_input = $('<input type="number" value="'+cur_height+'">');


        controls_div.append('ШИР: </span>');

        for (let i = 0; i < widths.length; i++){
            let btn = $('<button >'+ widths[i] +'</button >');
            btn.click(function () {
                cur_width = widths[i];
                width_input.val(cur_width);
                set_size(cur_width,cur_height,cur_depth)
            });
            controls_div.append(btn);
        }

        controls_div.append('<br>');

        controls_div.append('<span>Высота: </span>');

        for (let i = 0; i < heights.length; i++){
            let btn = $('<button >'+ heights[i] +'</button >');
            btn.click(function () {
                cur_height = heights[i];
                height_input.val(cur_height);
                set_size(cur_width,cur_height,cur_depth)
            });
            controls_div.append(btn);
        }

        controls_div.append('<br>');
        controls_div.append('<span>СР: </span>');


        controls_div.append(width_input);
        controls_div.append(height_input);


        var button = $('<button style="width: 200px;">ИЗМ</button>');
        controls_div.append(button);
        button.click(function () {
            cur_width = parseInt($(width_input).val());
            cur_height = parseInt(height_input.val());

            set_size(cur_width,cur_height,cur_depth)
        });

        var mode_div = $('<div id="three_viewport_mode"></div>')
        $('#three_viewport').append(mode_div);

        var button_icon_c = $('<button >Иконка (Общ)</button>');
        var button_view = $('<button >Вид</button>');
        // var button_icon = $('<button style="width: 200px;">ИКОНКА</button>');
        mode_div.append(button_view)
        mode_div.append(button_icon_c)
        Object.keys(app.facade_obj.types).forEach(function (k) {
            let btn = $('<button >Иконка ('+ app.facade_obj.types[k].name +')</button>');
            mode_div.append(btn)

            btn.click(function () {
                icon_mode(false, k);
            });

        })

        materials_lib.items = app.get_mats();
        // let mmats = copy_object(app.$options.mats);
        //
        // Object.keys(mmats).forEach( (k)=>{
        //     if(mmats[k].data){
        //         mmats[k] = JSON.parse(mmats[k].data)
        //     }
        // })
        //
        // materials_lib.items = mmats
        // //
        // let amats = app.get_mats();
        // Object.keys(amats).forEach((k)=>{
        //     materials_lib.items[k] = amats[k]
        // })

        let clrgen = '#8ca1a9'

        try {
            if (localStorage['pplace_a_facade_color_gen']) {
                clrgen = localStorage['pplace_a_facade_color_gen'];
                materials_lib.items[0].params.color = clrgen
            }

        } catch (e) {console.log(e)

        }
        let color = $('<div class="clrpckr"><label>Основной цвет</label><input type="text" class="form-control" id="color" value="'+ clrgen +'"></div>')
        mode_div.append(color)

        $("#color").spectrum({
            color: clrgen,
            preferredFormat: "hex",
            cancelText: glob.lang['cancel'],
            chooseText: glob.lang['pick'],
            showInput: true,
            move: function(color) {
                materials_lib.items[0].params.color = color.toHexString()
                localStorage['pplace_a_facade_color_gen'] = color.toHexString()
                fac.change_material({
                    group: 'all'
                })

                if(fac2){
                    fac2.change_material({
                        group: 'all'
                    })
                }
                if(fac3){
                    fac3.change_material({
                        group: 'all'
                    })
                }

            },
            change: function(color) {
                materials_lib.items[0].params.color = color.toHexString()
                fac.change_material({
                    group: 'all'
                })
                localStorage['pplace_a_facade_color_gen'] = color.toHexString()
            }
        });

        Object.keys(app.facade_obj.additional_materials).forEach(function (k) {

            let clr_key = '#ffffff';

            try {
                if (localStorage['pplace_a_facade_color_' + app.facade_obj.additional_materials[k].key]) {
                    clr_key = localStorage['pplace_a_facade_color_' + app.facade_obj.additional_materials[k].key];
                    materials_lib.items['add_' + k].params.color = clr_key
                }

            } catch (e) {console.log(e)

            }

            let color = $('<div class="clrpckr"><label>'+ app.facade_obj.additional_materials[k].name +'</label><input type="text" class="form-control color_'+ app.facade_obj.additional_materials[k].key +'" value="'+ clr_key +'"></div>')
            mode_div.append(color)

            setTimeout(function () {
                $('.color_'+ app.facade_obj.additional_materials[k].key).spectrum({
                    color: clr_key,
                    preferredFormat: "hex",
                    cancelText: glob.lang['cancel'],
                    chooseText: glob.lang['pick'],
                    showInput: true,
                    move: function(color) {
                        materials_lib.items['add_' + k].params.color = color.toHexString()
                        localStorage['pplace_a_facade_color_' + app.facade_obj.additional_materials[k].key] = color.toHexString()
                        fac.change_material({
                            group: 'all',
                            type: app.facade_obj.additional_materials[k].key,
                            id: 'add_' + k
                        })

                        if(fac2){
                            fac2.change_material({
                                group: 'all',
                                type: app.facade_obj.additional_materials[k].key,
                                id: 'add_' + k
                            })
                        }
                        if(fac3){
                            fac3.change_material({
                                group: 'all',
                                type: app.facade_obj.additional_materials[k].key,
                                id: 'add_' + k
                            })
                        }

                    },
                    change: function(color) {
                        materials_lib.items['add_' + k].params.color = color.toHexString()
                        localStorage['pplace_a_facade_color_' + app.facade_obj.additional_materials[k].key] = color.toHexString()
                        fac.change_material({
                            group: 'all',
                            type: app.facade_obj.additional_materials[k].key,
                            id: 'add_' + k
                        })

                        if(fac2){
                            fac2.change_material({
                                group: 'all',
                                type: app.facade_obj.additional_materials[k].key,
                                id: 'add_' + k
                            })
                        }
                        if(fac3){
                            fac3.change_material({
                                group: 'all',
                                type: app.facade_obj.additional_materials[k].key,
                                id: 'add_' + k
                            })
                        }

                    }
                });
            }, 100)

        })





        button_view.click(function () {
            view_mode();
        });

        button_icon_c.click(function () {
            icon_mode(true);
        });






        var viewport = document.getElementById(element_id);

        scene = new THREE.Scene();
        // scene.fog = new THREE.Fog(0xE9E5CE, 500, 10000);
        // scene.fog = new THREE.Fog(0xFFFFFF, 500, 10000);
        camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

        renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true,
            preserveDrawingBuffer: true,
        });
        // renderer.setClearColor(scene.fog.color);
        renderer.setSize( viewport.clientWidth, viewport.clientHeight );
        viewport.appendChild( renderer.domElement );
        renderer.prese
        fbx_manager = new THREE.LoadingManager();
        loader = new THREE.FBXLoader(fbx_manager);

        var axesHelper = new THREE.AxesHelper( 50 );
        scene.add( axesHelper );


        var view_amb_light = new THREE.AmbientLight( 0xffffff, 0.63);
        scene.add( view_amb_light );


        var view_directionalLight = new THREE.DirectionalLight( 0xffffff, 0.2 ,100  );
        view_directionalLight.castShadow = false;
        view_directionalLight.position.set( 0, 450, 300 );

        view_directionalLight.target = new THREE.Group();
        view_directionalLight.target.position.set(0,0,0);
        view_directionalLight.target.name = 'Directional Light Target';

        scene.add(view_directionalLight);
        scene.add(view_directionalLight.target);

        var view_light1 = new THREE.PointLight( 0xffffff, 0.5, 2000);
        view_light1.position.set(0, 300,0);
        view_light1.decay = 5;

        var view_light2 = new THREE.PointLight( 0xffffff, 0.5, 2000);
        view_light2.position.set(300, 0,0);
        view_light2.decay = 5;

        var view_light3 = new THREE.PointLight( 0xffffff, 0.5, 2000);
        view_light3.position.set(-300, 0,0);
        view_light3.decay = 5;

        var view_light4 = new THREE.PointLight( 0xffffff, 0.5, 2000);
        view_light4.position.set(0, 0,300);
        view_light4.decay = 5;

        var view_light5 = new THREE.PointLight( 0xffffff, 0.5, 2000);
        view_light5.position.set(0, 0,-300);
        view_light5.decay = 5;

        scene.add(view_light1);
        scene.add(view_light2);
        scene.add(view_light3);
        scene.add(view_light4);
        scene.add(view_light5);


        var icon_directionalLight = new THREE.DirectionalLight( 0xffffff, 0.2 ,100  );
        icon_directionalLight.castShadow = true;
        icon_directionalLight.position.set( 0, 450 / 2, 320 /2  );
        icon_directionalLight.shadow.mapSize.width = 4096;
        icon_directionalLight.shadow.mapSize.height = 4096;
        icon_directionalLight.shadow.camera.near = 0.5;
        icon_directionalLight.shadow.camera.far = 1500;
        icon_directionalLight.shadow.camera.left = -1000;
        icon_directionalLight.shadow.camera.right = 1000;
        icon_directionalLight.shadow.camera.top = 1000;
        icon_directionalLight.shadow.camera.bottom = -1000;
        icon_directionalLight.target = new THREE.Group();

        var icon_directionalLight2 = new THREE.DirectionalLight( 0xffffff, 0.2 ,100  );
        icon_directionalLight2.castShadow = true;
        icon_directionalLight2.position.set( 0, -450 / 2, 320 /2  );
        icon_directionalLight2.shadow.mapSize.width = 4096;
        icon_directionalLight2.shadow.mapSize.height = 4096;
        icon_directionalLight2.shadow.camera.near = 0.5;
        icon_directionalLight2.shadow.camera.far = 1500;
        icon_directionalLight2.shadow.camera.left = -1000;
        icon_directionalLight2.shadow.camera.right = 1000;
        icon_directionalLight2.shadow.camera.top = 1000;
        icon_directionalLight2.shadow.camera.bottom = -1000;
        icon_directionalLight2.target = new THREE.Group();

        var icon_directionalLight3 = new THREE.DirectionalLight( 0xffffff, 0.8 ,100  );
        icon_directionalLight3.castShadow = true;
        icon_directionalLight3.position.set( 0, 450 / 2, -320 /2  );
        icon_directionalLight3.shadow.mapSize.width = 4096;
        icon_directionalLight3.shadow.mapSize.height = 4096;
        icon_directionalLight3.shadow.camera.near = 0.5;
        icon_directionalLight3.shadow.camera.far = 1500;
        icon_directionalLight3.shadow.camera.left = -1000;
        icon_directionalLight3.shadow.camera.right = 1000;
        icon_directionalLight3.shadow.camera.top = 1000;
        icon_directionalLight3.shadow.camera.bottom = -1000;
        icon_directionalLight3.target = new THREE.Group();


        var icon_directionalLight4 = new THREE.DirectionalLight( 0xffffff, 0.8 ,100  );
        icon_directionalLight4.castShadow = true;
        icon_directionalLight4.position.set( 0, -450 / 2, -320 /2  );
        icon_directionalLight4.shadow.mapSize.width = 4096;
        icon_directionalLight4.shadow.mapSize.height = 4096;
        icon_directionalLight4.shadow.camera.near = 0.5;
        icon_directionalLight4.shadow.camera.far = 1500;
        icon_directionalLight4.shadow.camera.left = -1000;
        icon_directionalLight4.shadow.camera.right = 1000;
        icon_directionalLight4.shadow.camera.top = 1000;
        icon_directionalLight4.shadow.camera.bottom = -1000;
        icon_directionalLight4.target = new THREE.Group();


        icon_directionalLight.intensity = 0.8
        icon_directionalLight2.intensity = 0.8
        icon_directionalLight3.intensity = 0.8

        icon_directionalLight.target.position.set(0, 0, 0);
        icon_directionalLight2.target.position.set(0, 0, 0);
        icon_directionalLight3.target.position.set(0, 0, 0);
        icon_directionalLight4.target.position.set(0, 0, 0);

        icon_directionalLight.position.set( 0, 1500, 1500  );
        icon_directionalLight2.position.set( 0, -1500, 1500  );
        icon_directionalLight3.position.set( 1500, 1500, -1500  );
        icon_directionalLight4.position.set( -1500, -1500, -1500  );




        scene.add(icon_directionalLight );
        scene.add(icon_directionalLight2 );
        scene.add(icon_directionalLight3 );
        scene.add(icon_directionalLight4 );
        scene.add(icon_directionalLight.target);
        scene.add(icon_directionalLight2.target);
        scene.add(icon_directionalLight3.target);
        scene.add(icon_directionalLight4.target);







        camera.position.z = 150;












        fac_model = '';



        // set_size();

        let fac_data = app.get_data();

        let add_m = {};

        Object.keys(fac_data.additional_materials).forEach((k)=>{
            add_m[fac_data.additional_materials[k].key] = fac_data.additional_materials[k];
        })

        fac_data.additional_materials = add_m;

        Object.keys(fac_data.types).forEach(function (key) {
            for (let i = 0; i < fac_data.types[key].items.length; i++){
                let path = fac_data.types[key].items[i].model

                if(path.indexOf('common_assets') > -1){

                } else {
                    fac_data.types[key].items[i].model =  glob.acc_url + path;
                }

            }

            if(fac_data.types_double[key] && fac_data.types_double[key].items) {
                for (let i = 0; i < fac_data.types_double[key].items.length; i++) {
                    let path = fac_data.types_double[key].items[i].model

                    if (path.indexOf('common_assets') > -1) {

                    } else {
                        fac_data.types_double[key].items[i].model = glob.acc_url + path;
                    }
                }
            }

            if(fac_data.types_triple[key] && fac_data.types_triple[key].items){
                for (let i = 0; i < fac_data.types_triple[key].items.length; i++){
                    let path = fac_data.types_triple[key].items[i].model

                    if(path.indexOf('common_assets') > -1){

                    } else {
                        fac_data.types_triple[key].items[i].model =  glob.acc_url + path;
                    }

                }
            }




        })


        facades_sets = [
            fac_data
        ]




        env_urls = [
            "/common_assets/tests/prod/test12.jpg",
            "/common_assets/tests/prod/test12.jpg",
            "/common_assets/tests/prod/test12.jpg",
            "/common_assets/tests/prod/test12.jpg",
            "/common_assets/tests/prod/test12.jpg",
            "/common_assets/tests/prod/test12.jpg"
        ];





        textureCube2 = new THREE.CubeTextureLoader().load( env_urls );
        textureCube2.format = THREE.RGBFormat;
        textureCube2.mapping = THREE.CubeReflectionMapping;

        let tmp2 = app.get_data();


        let add_mat_obj = {};
        Object.keys(tmp2.additional_materials).forEach(function (key) {

            add_mat_obj[tmp2.additional_materials[key].key] = tmp2.additional_materials[key]

        });

        tmp2.additional_materials = add_mat_obj;


        project_settings = {
            models:{
                top: 0,
                bottom: 0
            },
            facades_data: {
                top: tmp2
            },
            selected_materials:{
                pat: 1
            }
        };

        // materials_catalog = {
        //     items: app.get_mats()
        // };

        facades_lib.items[0] = fac_data;



        let rad = 0;

        if(app.facade_obj.types[style].radius){
            rad = 1;
        }

        fac = new Facade_new({
            width: cur_width,
            height: cur_height,
            thickness: fac_data.thickness,
            is_double: doubtrip == 1,
            is_triple: doubtrip == 2,
            style: style,
            radius: rad,
            demo_mode: true
        });

        let int2 = setInterval(function () {
            if(fac.model_loaded){
                clearInterval(int2)

                Object.keys(app.facade_obj.additional_materials).forEach(function (k) {
                    fac.change_material({
                        group: 'all',
                        type: app.facade_obj.additional_materials[k].key,
                        id: 'add_' + k
                    })
                })

            }
        },100)



        scene.add(fac);




        function set_size() {
            fac.params.width = cur_width
            fac.params.height = cur_height
            fac.load_model();
        }



        controls = new THREE.OrbitControls(camera, renderer.domElement);

        view_mode();




        function view_mode() {
            scene.fog = new THREE.Fog(0xFFFFFF, 500, 10000);
            renderer.setClearColor(scene.fog.color);
            view_amb_light.visible = false
            view_directionalLight.visible = false
            view_light1.visible = false
            view_light2.visible = false
            view_light3.visible = false
            view_light4.visible = false
            view_light5.visible = false
            axesHelper.visible = true

            icon_directionalLight.visible = true
            icon_directionalLight2.visible = true
            icon_directionalLight.visible = true
            icon_directionalLight2.visible = true

            controls.target.set(0,0,0)
            camera.position.set(0,0,150)
            controls.update()

            fac.visible = true;
            if(fac2) scene.remove(fac2)
            if(fac3) scene.remove(fac3)
        }

        function icon_mode(common, type) {
            scene.fog = new THREE.Fog(0xFFFFFF, 500, 10000);
            renderer.setClearColor(scene.fog.color);
            view_amb_light.visible = false
            view_directionalLight.visible = false
            view_light1.visible = false
            view_light2.visible = false
            view_light3.visible = false
            view_light4.visible = false
            view_light5.visible = false
            axesHelper.visible = false

            icon_directionalLight.visible = true
            icon_directionalLight2.visible = true
            icon_directionalLight.visible = true
            icon_directionalLight2.visible = true

            controls.target.set(0,0,0)
            camera.position.set(0,0,150)
            controls.update()

            fac.visible = false;

            if(fac2) scene.remove(fac2)
            if(fac3) scene.remove(fac3)

            if(!type) type = style;

            let radi = 0;

            if(app.facade_obj.types[type].radius){
                radi = 1;
            }

            if(common){
                fac2 = new Facade_new({
                    width: 446,
                    height: 570,
                    thickness: fac_data.thickness,
                    is_double: doubtrip == 1,
                    is_triple: doubtrip == 2,
                    style: type,
                    group: 'bottom',
                    demo_mode: true
                });
                scene.add(fac2);

                let int2 = setInterval(function () {
                    if(fac2.model_loaded){
                        clearInterval(int2)

                        Object.keys(app.facade_obj.additional_materials).forEach(function (k) {
                            fac2.change_material({
                                group: 'all',
                                type: app.facade_obj.additional_materials[k].key,
                                id: 'add_' + k
                            })
                        })

                    }

                    fac2.position.y = -146 / 10 / 2


                },100)


                fac3 = new Facade_new({
                    width: 446,
                    height: 146,
                    thickness: fac_data.thickness,
                    is_double: doubtrip == 1,
                    is_triple: doubtrip == 2,
                    group: 'bottom',
                    style: type,
                    demo_mode: true
                });
                scene.add(fac3);

                let int3 = setInterval(function () {
                    if(fac3.model_loaded){
                        clearInterval(int3)

                        Object.keys(app.facade_obj.additional_materials).forEach(function (k) {
                            fac3.change_material({
                                group: 'all',
                                type: app.facade_obj.additional_materials[k].key,
                                id: 'add_' + k
                            })
                        })

                    }
                    fac3.position.y = 570 / 10 / 2 + 0.4
                },100)


                setTimeout(function () {

                },150)
            } else {



                fac2 = new Facade_new({
                    width: 446,
                    height: 716,
                    thickness: fac_data.thickness,
                    is_double: doubtrip == 1,
                    is_triple: doubtrip == 2,
                    style: type,
                    radius: radi,
                    demo_mode: true
                });

                scene.add(fac2);


                let int2 = setInterval(function () {
                    if(fac2.model_loaded){
                        clearInterval(int2)

                        Object.keys(app.facade_obj.additional_materials).forEach(function (k) {
                            fac2.change_material({
                                group: 'all',
                                type: app.facade_obj.additional_materials[k].key,
                                id: 'add_' + k
                            })
                        })

                    }

                    if(radi){
                        fac2.rotation.y = Math.PI / 8
                        fac2.position.x += 4
                    }


                },100)


                setTimeout(function () {

                },150)


            }

            if(radi){
                camera.zoom = 0.95
            } else {
                camera.zoom = 1
            }
            renderer.setSize(500,500)
            renderer.setClearColor( 0x000000, 0 )
            camera.aspect = 500 / 500;
            camera.updateProjectionMatrix();

            let int = setInterval(function () {
                console.log(fac2)
                if(fac2.model_loaded){
                    clearInterval(int)
                    setTimeout(function () {

                        let canvas = document.createElement('canvas');
                        let iw = 144;
                        let ih = 230
                        canvas.width = iw;
                        canvas.height = ih;
                        let ctx = canvas.getContext('2d');

                        let left0 = renderer.domElement.width / 2 - iw/2;
                        let top0 = renderer.domElement.height / 2 - ih/2;


                        ctx.drawImage(renderer.domElement,left0,top0,iw,ih,0,0,iw,ih);
                        let data = canvas.toDataURL('image/jpeg', 1);
                        var link = document.createElement("a");
                        link.download = "image.png";
                        if(common){
                            link.download = location.href.split('/').pop() + '_' + 'icon_common.png'
                        } else {
                            link.download = location.href.split('/').pop() + '_' + 'icon_type_'+ type +'.png'
                        }


                        canvas.toBlob(function (blob) {
                            link.href = URL.createObjectURL(blob);
                            link.click();
                        }, 'image/png');

                        renderer.setSize(viewport.clientWidth, viewport.clientHeight)
                        camera.aspect = viewport.clientWidth / viewport.clientHeight;
                        camera.updateProjectionMatrix();

                    },200)

                }

            },100)

        }

        var animate = function () {
            requestAnimationFrame( animate );

            // cube.rotation.x += 0.01;
            // cube.rotation.y += 0.01;

            renderer.render( scene, camera );
        };
        animate();


    }

    function check_filename(file) {
        let split_arr = file.name.split('.');
        if(split_arr.length > 2) return false;
        return !(split_arr[0].match(/[^\u0000-\u007f]/));

    }
});