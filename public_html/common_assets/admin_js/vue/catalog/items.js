let app = null;
let lang = null;
let controller_name = null;
let base_url = null;
let acc_url = null;

let categories_data = null;
let categories_hash = null;
let categories_ordered = null;

let items_data = null;
let items_hash = null;
let items_count = 0;

let is_modules = false;
let is_catalogue = false;
let set_id = null;

let is_common = 0;
let is_catalog = 0;
let items_method_name = 'items_get_pagination_multi';
let categories_method_name = 'categories_get';

let session_data = {
    category: "0",
    per_page: "20",
    start: 0,
    search: ""
};

let ls_keys = {};

let local_storage_available = 1;

let per_page = ["10", "20", "50", "100"]

let single_page_names = {
    coupe_accessories: 1,
    modules_sets: 1,
}

let add_urls = {}

let edit_urls = {}

let contr_names = {
    'material_types': 'Материалы',
    'materials': 'Декоры',
    'handles': 'Ручки',
    'tech': 'Техника',
    'comms': 'Коммуникации',
    'interior': 'Интерьер',
    'modules': 'Модули',
    'facades': 'Фасады',
    'glass': 'Витрины',
    'catalogue': 'Каталог',
    'model3d': '3D Модели',
    'params_blocks': 'Наборы параметров',
    'cornice': 'Карнизы',
    'kitchen_models': 'Модели кухни'
}
let no_icons = [
    'accessories',
    'material_types',
    'params_blocks',
]
let has_color = [
    'materials',
    'glass'
]

let pp_keyboard = {
    ctrl: false,
    shift: false,
    r: false,
    c: false,
    x: false,
    y: false,
    z: false
}

document.addEventListener('DOMContentLoaded', async function () {
    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;
    set_id = document.getElementById('cat_set_id').value;
    is_common = document.getElementById('is_common').value;
    is_catalog = document.getElementById('is_catalog').value;
    lang = JSON.parse(document.getElementById('lang_json').value);
    controller_name = document.getElementById('footer_controller_name').value;

    if(is_common == 1){
        items_method_name = 'items_get_pagination_common_multi';
        categories_method_name = 'categories_get_common';
    }

    if(controller_name == 'modules' || controller_name == 'module_sets') is_modules = true;
    if(controller_name == 'catalogue' || controller_name == 'bardesks' || controller_name == 'cokol') is_catalogue = true;

    let account_name = ''

    let acc_arr = acc_url.split('/')
    account_name  = acc_arr[acc_arr.length-1];
    if(account_name == '') account_name = acc_arr[acc_arr.length-2]

    ls_keys = {
        category: 'bplanner_admin_' + account_name + '_' + controller_name + '_category',
        per_page: 'bplanner_admin_' + account_name + '_' + controller_name + '_per_page',
        start: 'bplanner_admin_' + account_name + '_' + controller_name + '_start',
        search: 'bplanner_admin_' + account_name + '_' + controller_name + '_search'
    }

    try{
        Object.keys(ls_keys).forEach(function (k) {
            if(localStorage.getItem(ls_keys[k])) session_data[k] = localStorage.getItem(ls_keys[k]);
        })

    }catch (e) {
        local_storage_available = 0;
        console.log(e)
    }
    // controller_name = 'coupe_accessories';

    let categories_url = base_url + '/catalog/'+ categories_method_name +'/' + controller_name

    if(controller_name == 'module_sets' || controller_name == 'modules_sets_modules'){
        categories_url = base_url + '/catalog/'+ categories_method_name +'/' + controller_name + '/' + set_id
    }

    categories_data = await promise_request(categories_url);
    categories_data.unshift({
        name: lang['all'],
        id: '0',
        parent: '0'
    })

    categories_hash = get_hash(categories_data);
    categories_ordered = flatten(create_tree(categories_data));
    if(!categories_hash[session_data.category]) session_data.category = "0"
    console.log(categories_hash[session_data.category])

    // session_data.search = session_data.search.replaceAll('\'', '\"')
    //
    // let search = session_data.search.replaceAll('(', '^pp_lb^')
    // search = search.replaceAll(')', '^pp_rb^')
    // search = search.replaceAll(',', '^pp_com^')
    // search = search.replaceAll('!', '^pp_exc^')
    // search = search.replaceAll('@', '^pp_dog^')
    // search = search.replaceAll('$', '^pp_dol^')
    // search = search.replaceAll('%', '^pp_per^')
    // search = search.replaceAll('&', '^pp_amp^')
    // search = search.replaceAll('*', '^pp_mul^')
    // search = search.replaceAll('+', '^pp_plu^')
    // search = search.replaceAll('[', '^pp_slb^')
    // search = search.replaceAll(']', '^pp_srb^')
    // search = search.replaceAll('=', '^pp_eq^')
    // search = search.replaceAll('\'', '^pp_qu^')

    let search = session_data.search
    let items_url = glob.base_url + '/catalog/' + items_method_name
    let fdata = new FormData();

    fdata.append('name', controller_name)
    fdata.append('category', session_data.category)
    fdata.append('per_page', session_data.per_page)
    fdata.append('start', session_data.start)
    fdata.append('search', search)
    fdata.append('categories', JSON.stringify([]))
    fdata.append('categories_multi', is_catalogue)
    if(controller_name == 'module_sets' || controller_name == 'modules_sets_modules'){
        fdata.append('set_id', set_id)
    }

    // let result = await promise_request_post(url, fdata)

    // let items_url = base_url + '/catalog/'+ items_method_name +'/' + controller_name + '/' + session_data.category + '/' + session_data.per_page + '/' + session_data.start + '/' + search

    // if(controller_name == 'module_sets' || controller_name == 'modules_sets_modules'){
    //     items_url = base_url + '/catalog/'+ items_method_name +'/' + controller_name + '/' + session_data.category + '/' + session_data.per_page + '/' + session_data.start + '/' + set_id
    // }

    // let items = await promise_request(items_url);
    let items = await promise_request_post(items_url,fdata);

    items_data = items['items'];
    items_count = items['count'];

    add_urls = {
        'comms': base_url + '/' + controller_name + '/items_add',
        'facades': base_url + '/' + controller_name + '/item',
        'glass': base_url + '/' + controller_name + '/items_add',
        'handles': base_url + '/' + controller_name + '/item',
        'interior': base_url + '/' + controller_name + '/items_add',
        'materials': base_url + '/' + controller_name + '/item',
        'module_sets': base_url + '/' + controller_name + '/items_add/' + set_id,
        'modules': base_url + '/' + controller_name + '/items_add',
        'tech': base_url + '/' + controller_name + '/items_add',

        'material_types': base_url + '/' + controller_name + '/item',
        'catalogue': base_url + '/' + controller_name + '/item',
        'model3d': base_url + '/' + controller_name + '/item',

        'params_blocks': base_url + '/' + controller_name + '/item',
        'kitchen_models': base_url + '/' + controller_name + '/add_new',
    }

    edit_urls = {
        'comms': base_url + '/' + controller_name + '/items_add',
        'facades': base_url + '/' + controller_name + '/item',
        'glass': base_url + '/' + controller_name + '/items_edit',
        'handles': base_url + '/' + controller_name + '/item',
        'interior': base_url + '/' + controller_name + '/items_add',
        'materials': base_url + '/' + controller_name + '/item',
        'module_sets': base_url + '/' + controller_name + '/items_add/' + set_id,
        'modules': base_url + '/' + controller_name + '/items_add',
        'tech': base_url + '/' + controller_name + '/items_add',

        'material_types': base_url + '/' + controller_name + '/item',
        'catalogue': base_url + '/' + controller_name + '/item',
        'model3d': base_url + '/' + controller_name + '/item',

        'params_blocks': base_url + '/' + controller_name + '/item',
        'kitchen_models': base_url + '/' + controller_name + '/add_new',
    }



    cat_btn = {
        facades: 1,
        handles: 1,
        glass: 1,
        comms: 1,
        tech: 1,
        interior: 1,
        materials: 1,
        cornice: 1,
        washes: 1
    }

    headings = {
        'comms': 'comms_items_list',
        'facades': 'facades_items_heading',
        'glass': 'glass_items_index_heading',
        'handles': 'handles_items_heading',
        'interior': 'interior_items_list',
        'materials': 'materials_items_list',
        'module_sets': 'modules_list',
        'modules': 'modules_list',
        'tech': 'tech_items_list',
        'builtin': 'builtin_items_list',

        'material_types': 'Материалы',
        'model3d': '3D модели',
        'params_blocks': 'params_blocks',
        'cornice': 'cornice',
    }
    init_vue();
})

let last_selected = null;

document.addEventListener("keydown", function (e) {
    if(e.shiftKey){
        pp_keyboard.shift = true;
    }
})

document.addEventListener("keyup", function (e) {
    if(pp_keyboard.shift){
        pp_keyboard.shift = false
    }
})

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components: {
            'v-select': VueSelect.VueSelect
        },
        data: {
            errors: [],
            items:[],
            selected_items: [],
            last_selected: null,
            mass_action: 'edit_category',
            items_count: 0,
            page: 1,
            has_icons: true,
            has_color: false,
            filter:{
                category: session_data.category,
                per_page: session_data.per_page,
                start: session_data.start,
                search: session_data.search
            },
            catalog_categories: {
                hash: {},
                ordered: {},
                ready: 0,
                value: "0",
                item_id: 0,
                item: {}
            },
            catalog_data: false,
            catalog_items:[],
            catalog_items_count:0,
            catalog_page: 1,
            catalog_filter: {
                category: '0',
                per_page: '20',
                start: '0',
                search: ''
            },
            controller_name: controller_name

        },
        created: function () {

            if(no_icons.includes(controller_name)) this.has_icons = false;
            if(has_color.includes(controller_name)) this.has_color = true;


            this.$options.catalog_items = [];

            this.items_count = items_count;

            if(is_modules){
                for (let i = 0; i < items_data.length; i++){
                    items_data[i].params = JSON.parse(items_data[i].params).params
                }
            }

            if(is_catalogue){
                for (let i = 0; i < items_data.length; i++){
                    items_data[i].category = JSON.parse(items_data[i].category)
                    items_data[i].data = JSON.parse(items_data[i].data)
                }
            }

            if(controller_name == 'facades'){
                for (let i = 0; i < items_data.length; i++) {
                    items_data[i].materials = JSON.parse(items_data[i].materials)
                }
            }

            if(controller_name == 'materials'){
                for (let i = 0; i < items_data.length; i++) {
                    if(items_data[i].data){
                        items_data[i].data = JSON.parse(items_data[i].data)
                        items_data[i].color = items_data[i].data.params.color
                        items_data[i].map = items_data[i].data.params.map
                        items_data[i].icon = items_data[i].data.params.icon
                    }
                }
            }

            if(controller_name == 'glass'){
                for (let i = 0; i < items_data.length; i++){
                    items_data[i].params = JSON.parse(items_data[i].params).params

                    if(items_data[i].params.icon && items_data[i].params.icon != ''){
                        items_data[i].icon = items_data[i].params.icon
                        console.log(items_data[i].icon)
                    } else {
                        if(items_data[i].params.map) items_data[i].map = items_data[i].params.map
                        if(items_data[i].params.color) items_data[i].color = items_data[i].params.color
                    }
                }
            }
            console.log(items_data)
            Vue.set(this,'items', items_data)
        },
        computed:{
            pages_count: function () {
                return Math.ceil(this.items_count / this.filter.per_page);
            },
            catalog_button: function () {
                return is_catalog == 0 && cat_btn[controller_name] != undefined;
            },
            heading: function () {

                if(headings[controller_name] !== undefined){
                    return this.lang(headings[controller_name]);
                }

                if(this.lang(controller_name + '_items') !== undefined){
                    return this.lang(controller_name + '_items');
                }

                return this.lang('coupe_profiles');
            },
            computed: {
                // images: function() {
                //     return this.items.map(function(item) {
                //         return item.params.map;
                //     });
                // },
            }
        },
        mounted: function () {
            this.$options.is_common = is_common;
            let scope = this;
            this.$emit('change_page_sync', parseInt(this.filter.start) / parseInt(this.filter.per_page) + 1);
            $('#bm_mass_depth').click(function (e) {
                e.preventDefault();
                let data = {};
                let scope = $(this);
                if($('#bm_depth').val() > 0 && $('#bm_depth_to').val() > 0){
                    data.depth = $('#bm_depth').val();
                    data.depth_to = $('#bm_depth_to').val();
                    $("#mass_change_modal").hide();
                    $('.modal-backdrop')[0].remove();
                }
                send_mass(scope.attr('data-action'), data)

            });

            $('#bm_mass_fo').click(function (e) {
                e.preventDefault();
                let data = {};
                let scope = $(this);
                if($('#bm_fo').val() > 0) {
                    data.front_offset = $('#bm_fo').val();
                    $("#mass_change_modal").hide();
                    $('.modal-backdrop')[0].remove();
                }
                send_mass(scope.attr('data-action'), data)
            });

            $('#bm_mass_bo').click(function (e) {
                e.preventDefault();
                let data = {};
                let scope = $(this);
                if($('#bm_bo').val() > 0) {
                    data.back_offset = $('#bm_bo').val();
                    $("#mass_change_modal").hide(); 
                    $('.modal-backdrop')[0].remove();
                };
                send_mass(scope.attr('data-action'), data)
            });

            $('#bm_mass_height').click(function (e) {
                e.preventDefault();
                let data = {};
                let scope = $(this);
                if($('#bm_height').val() > 0 && $('#bm_height_to').val() > 0){
                    data.height = $('#bm_height').val();
                    data.height_to = $('#bm_height_to').val();
                    $("#mass_change_modal").hide();
                    $('.modal-backdrop')[0].remove();
                }
                send_mass(scope.attr('data-action'), data)

            });

            $('#tm_mass_depth').click(function (e) {
                e.preventDefault();
                let data = {};
                let scope = $(this);
                if($('#tm_depth').val() > 0 && $('#tm_depth_to').val() > 0){
                    data.depth = $('#tm_depth').val();
                    data.depth_to = $('#tm_depth_to').val();
                    $("#mass_change_modal_top").hide();
                    $('.modal-backdrop')[0].remove();
                }
                send_mass(scope.attr('data-action'), data)

            });

            $('#tm_mass_height').click(function (e) {
                e.preventDefault();
                let data = {};
                let scope = $(this);
                if($('#tm_height').val() > 0 && $('#tm_height_to').val() > 0){
                    data.height = $('#tm_height').val();
                    data.height_to = $('#tm_height_to').val();
                    $("#mass_change_modal_top").hide();
                    $('.modal-backdrop')[0].remove();
                }
                send_mass(scope.attr('data-action'), data)
            });

            function send_mass(url, data) {
                if(Object.keys(data).length > 0){
                    data.data = 1;
                    Swal.fire({
                        title: scope.lang('are_u_sure'),
                        text: $('#change_confirm_message').html(),
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: scope.lang('no'),
                        confirmButtonText: scope.lang('yes'),
                        closeOnConfirm: true,
                        customClass: {
                            confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                            cancelButton: 'btn btn-label-danger waves-effect waves-light'
                          }
                    }).then(() =>{ 
                        $.ajax({
                            url: url,
                            type: 'post',
                            data:  data,
                        }).done(function (msg) {
                            console.log(msg)
                            toastr.success(scope.lang('success'))
                            $('#mass_change_modal').modal('hide')
                            setTimeout(function (msg) {
                                location.reload();
                            },200)
                        })
                    });


                } else {
                    toastr.error(scope.lang('error'))
                }
            }

        },
        methods: {
            select_item: function(e, ind, id){
               if(pp_keyboard.shift){
                   if(last_selected !== null){
                       if(ind > last_selected){
                           for (let i = last_selected; i <= ind; i++) {
                               this.selected_items.push(this.items[i].id)
                           }
                       } else {
                           for (let i = ind; i <= last_selected; i++) {
                               this.selected_items.push(this.items[i].id)
                           }
                       }

                       this.selected_items = _.uniq(this.selected_items)

                   }
               }

                last_selected = ind;

            },
            do_mass_action: function(){
                switch (this.mass_action) {
                    case 'edit_category':
                        this.mass_category()
                        break;
                    case 'copy_to_cat':
                        this.mass_copy_to_category()
                        break;
                    case 'edit':
                        this.mass_change();
                        break;
                    case 'delete':
                        this.mass_delete();
                        break;
                }
            },
            mass_change: function(){
                $('#modal_mass_change').modal('show')
            },
            mass_change_do: async function(){
                console.log(this.selected_items)
                console.log(this.$refs.mass_materials.get_data())

                let new_vals = this.$refs.mass_materials.get_data()
                let sel_hash = get_hash(this.selected_items);
                let items = [];
                for (let i = this.items.length; i--;) {
                    if(this.selected_items.includes(this.items[i].id)){
                        items.push(copy_object(this.items[i]))
                    }
                    // if (sel_hash[this.items.id]) {
                    //     items.push(copy_object(this.items[i]))
                    // }
                }

                for (let i = items.length; i--;){
                    if(items[i].data){

                        Object.keys(new_vals).forEach((key)=>{
                            Object.keys(new_vals[key]).forEach((k)=>{
                                items[i].data[key][k] = new_vals[key][k]
                            })
                        })

                        items[i].data = JSON.stringify(items[i].data);
                    } else {
                        Object.keys(new_vals).forEach((key)=>{
                            Object.keys(new_vals[key]).forEach((k)=>{
                                if(items[i][k] !== undefined) items[i][k] = new_vals[key][k];
                            })
                        })
                    }
                }

                let fdata = new FormData();
                fdata.append('data', JSON.stringify(items))
                let url = glob.base_url + '/catalog/mass_category_change/' + controller_name
                let result = await promise_request_post(url, fdata);

                $('#modal_mass_change').modal('hide');
                this.make_request();
                toastr.success(lang['success'])

            },
            mass_category: function(){
                $('#modal_mass_category').modal('show')
            },
            mass_category_do: async function(){
                console.log(this.selected_items)
                console.log(this.$refs.mass_category.l_value)

                let new_cat = this.$refs.mass_category.l_value
                let sel_hash = get_hash(this.selected_items);
                let items = [];
                for (let i = this.items.length; i--;) {
                    if(this.selected_items.includes(this.items[i].id)){
                        items.push(copy_object(this.items[i]))
                    }
                }

                for (let i = items.length; i--;){
                    items[i].category = new_cat

                    if(items[i].data && items[i].data.category){
                        items[i].data.category = new_cat
                        items[i].data = JSON.stringify(items[i].data);
                    }
                }

                let fdata = new FormData();
                fdata.append('data', JSON.stringify(items))

                let url = glob.base_url + '/catalog/mass_category_change/' + controller_name
                let result = await promise_request_post(url, fdata);

                $('#modal_mass_category').modal('hide');
                this.make_request();
                toastr.success(lang['success'])
            },

            mass_copy_to_category: function(){
                $('#modal_mass_copy_to_category').modal('show')
            },
            mass_copy_to_category_do: async function(){
                console.log(this.selected_items)
                console.log(this.$refs.mass_copy_to_category.l_value)

                let new_cat = this.$refs.mass_copy_to_category.l_value
                let sel_hash = get_hash(this.selected_items);
                let items = [];
                for (let i = 0; i < this.items.length; i++) {
                    if(this.selected_items.includes(this.items[i].id)){
                        items.push(copy_object(this.items[i]))
                    }
                }

                for (let i = 0; i < items.length; i++){
                    items[i].category = new_cat
                    delete items[i].id;
                    if(items[i].data && items[i].data.category){
                        items[i].data.category = new_cat
                        items[i].data = JSON.stringify(items[i].data);
                    }
                }



                let fdata = new FormData();
                fdata.append('data', JSON.stringify(items))

                let url = glob.base_url + '/catalog/mass_copy_to_category/' + controller_name
                let result = await promise_request_post(url, fdata);

                $('#modal_mass_copy_to_category').modal('hide');
                this.make_request();
                toastr.success(lang['success'])
            },

            mass_delete: async function(){

                let fdata = new FormData();
                fdata.append('data', JSON.stringify(this.selected_items))

                let scope = this;

                Swal.fire({
                    title: scope.lang('are_u_sure'),
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: scope.lang('no'),
                    confirmButtonText: scope.lang('yes'),
                    closeOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-danger waves-effect waves-light'
                      }
                }).then(async () => {
                    let url = glob.base_url + '/catalog/mass_items_remove/' + controller_name
                        let result = await promise_request_post(url, fdata);
                        scope.make_request();
                        toastr.success(lang['success'])
                });

            },
            toggle_all: function(e){
                if(e.target.checked){
                    let res = [];
                    for (let i = 0; i < this.items.length; i++) {
                        res.push(this.items[i].id)
                    }
                    Vue.set(this, 'selected_items', res)
                } else {
                    Vue.set(this, 'selected_items', [])
                }

            },
            lang: function(key){
                return lang[key]
            },
            get_map: function(item){
                console.log(item)
                if(item.mat_icon) return 'url(' + this.correct_url(item.mat_icon) + ')'
                if(item.map) return 'url(' + this.correct_url(item.map) + ')'
                return item.color;
            },
            correct_url: function(path){
                let date_time = new Date().getTime();

                if(path.indexOf('common_assets') > -1){
                    return path +  '?' + date_time
                } else {
                    return acc_url + path +  '?' + date_time;
                }
            },
            change_active: function (item) {
                if (item.active == 0) {
                    item.active = 1
                } else {
                    item.active = 0;
                }

                send_xhr_get(base_url + '/catalog/item_set_active/' + controller_name + '/' + item.id + '/' + item.active)
            },
            filter_category: function(val){
                console.log(val)
                let scope = this;


                if(local_storage_available) localStorage.setItem(ls_keys.category, val);
                scope.filter.category = val;

                if(local_storage_available) localStorage.setItem(ls_keys.start, 0);
                scope.filter.start = 0;

                scope.make_request(true)

            },
            filter_per_page: function(val){
                console.log(val)
                let scope = this;
                scope.filter.per_page = val;
                if(local_storage_available) localStorage.setItem(ls_keys.per_page, val);

                if(local_storage_available) localStorage.setItem(ls_keys.start, 0);
                scope.filter.start = 0;

                scope.make_request(true)
            },
            change_page: function(val){
                this.page = val;
                let scope = this;

                console.log((val -1 )*scope.filter.per_page)

                scope.filter.start = (val -1 )*scope.filter.per_page
                if(local_storage_available) localStorage.setItem(ls_keys.start, scope.filter.start);

                scope.make_request(false)



                this.$emit('change_page_sync', val);

            },
            do_search: function(){
                let scope = this;

                if(local_storage_available) localStorage.setItem(ls_keys.search, scope.filter.search);
                if(local_storage_available) localStorage.setItem(ls_keys.start, 0);
                scope.filter.start = 0;

                scope.make_request(true)

            },
            make_request: async function(reset){
                let scope = this;
                let search = scope.filter.search

                this.$refs.select_all_checkbox.checked = false;
                Vue.set(this,'selected_items', [])
                last_selected = null;
                // scope.filter.search = scope.filter.search.replaceAll('\'', '\"')
                //
                // let search = scope.filter.search.replaceAll('(', '^pp_lb^')
                // search = search.replaceAll(')', '^pp_rb^')
                // search = search.replaceAll(',', '^pp_com^')
                // search = search.replaceAll('!', '^pp_exc^')
                // search = search.replaceAll('@', '^pp_dog^')
                // search = search.replaceAll('$', '^pp_dol^')
                // search = search.replaceAll('%', '^pp_per^')
                // search = search.replaceAll('&', '^pp_amp^')
                // search = search.replaceAll('*', '^pp_mul^')
                // search = search.replaceAll('+', '^pp_plu^')
                // search = search.replaceAll('[', '^pp_slb^')
                // search = search.replaceAll(']', '^pp_srb^')
                // search = search.replaceAll('=', '^pp_eq^')


                let url = glob.base_url + '/catalog/' + items_method_name
                let fdata = new FormData();

                fdata.append('name', controller_name)
                fdata.append('category', scope.filter.category)
                fdata.append('per_page',  scope.filter.per_page)
                fdata.append('start', scope.filter.start)
                fdata.append('search', search)
                fdata.append('categories', JSON.stringify([]))
                fdata.append('categories_multi', is_catalogue)
                if(controller_name == 'module_sets' || controller_name == 'modules_sets_modules'){
                    fdata.append('set_id', set_id)
                }

                let result = await promise_request_post(url, fdata);
                scope.items_count = result['count'];
                if(is_modules){
                    for (let i = 0; i < result['items'].length; i++){
                        result['items'][i].params = JSON.parse(result['items'][i].params).params
                    }
                }
                if(is_catalogue){
                    for (let i = 0; i < result['items'].length; i++){
                        result['items'][i].category = JSON.parse(result['items'][i].category)
                        result['items'][i].data = JSON.parse(result['items'][i].data)
                    }
                }
                if(controller_name == 'facades'){
                    for (let i = 0; i < result['items'].length; i++) {
                        result['items'][i].materials = JSON.parse(result['items'][i].materials)
                    }
                }

                if(controller_name == 'materials'){
                    for (let i = 0; i < result['items'].length; i++) {
                        if(result['items'][i].data){
                            result['items'][i].data = JSON.parse(result['items'][i].data);
                            if (result['items'][i].data.params.icon && result['items'][i].data.params.icon != '') {
                                result['items'][i].icon = result['items'][i].data.params.icon
                            } else {
                                if (result['items'][i].data.params.color) result['items'][i].color = result['items'][i].data.params.color
                                if (result['items'][i].data.params.map) result['items'][i].map = result['items'][i].data.params.map
                            }
                        }

                    }
                }

                if(controller_name == 'glass'){
                    for (let i = 0; i < result['items'].length; i++){
                        result['items'][i].params = JSON.parse(result['items'][i].params).params

                        if(result['items'][i].params.icon && result['items'][i].params.icon != ''){
                            result['items'][i].icon = result['items'][i].params.icon

                        } else {
                            if(result['items'][i].params.color) result['items'][i].color = result['items'][i].params.color
                            if(result['items'][i].params.map) result['items'][i].map = result['items'][i].params.map
                        }


                    }
                }


                Vue.set(scope,'items', result['items'])
                if(reset) scope.$emit('reset_page', 1);


                // let url = base_url + '/catalog/'+ items_method_name +'/' + controller_name + '/' + scope.filter.category + '/' + scope.filter.per_page + '/' + scope.filter.start + '/' + search;
                //
                // if(controller_name == 'module_sets' || controller_name == 'modules_sets_modules'){
                //     url = base_url + '/catalog/'+ items_method_name +'/' + controller_name + '/' + scope.filter.category + '/' + scope.filter.per_page + '/' + scope.filter.start + '/' + set_id;
                // }

                // promise_request(url)
                //     .then(function (result) {
                //         scope.items_count = result['count'];
                //
                //         if(is_modules){
                //             for (let i = 0; i < result['items'].length; i++){
                //                 result['items'][i].params = JSON.parse(result['items'][i].params).params
                //             }
                //         }
                //
                //         if(is_catalogue){
                //             for (let i = 0; i < result['items'].length; i++){
                //                 result['items'][i].category = JSON.parse(result['items'][i].category)
                //                 result['items'][i].data = JSON.parse(result['items'][i].data)
                //             }
                //         }
                //
                //         if(controller_name == 'facades'){
                //             for (let i = 0; i < result['items'].length; i++) {
                //                 result['items'][i].materials = JSON.parse(result['items'][i].materials)
                //             }
                //         }
                //
                //         if(controller_name == 'glass'){
                //             for (let i = 0; i < result['items'].length; i++){
                //                 result['items'][i].params = JSON.parse(result['items'][i].params).params
                //
                //                 if(result['items'][i].params.icon && result['items'][i].params.icon != ''){
                //                     result['items'][i].icon = result['items'][i].params.icon
                //
                //                 } else {
                //                     if(result['items'][i].params.color) result['items'][i].color = result['items'][i].params.color
                //                     if(result['items'][i].params.map) result['items'][i].map = result['items'][i].params.map
                //                 }
                //
                //
                //             }
                //         }
                //
                //
                //         Vue.set(scope,'items', result['items'])
                //         if(reset) scope.$emit('reset_page', 1);
                //
                //     }).catch(function (err) {}
                //     )


            },
            add_item: function(){
                if(single_page_names[controller_name]){
                    this.$emit('add_item', 1);
                } else {

                    if(add_urls[controller_name]){

                        let path = add_urls[controller_name]
                        if(is_common == 1) path += '_common'

                        location.href = path
                    } else {

                        if(is_common == 1){

                            if(set_id != 0){
                                location.href = base_url + '/catalog/item_common/' + controller_name + '/0/' + set_id
                            } else {
                                location.href = base_url + '/catalog/item_common/' + controller_name + '/'
                            }


                            return;
                        }

                        location.href = base_url + '/catalog/item/' + controller_name + '/'
                    }


                }

            },
            edit_item: function(item){

                if(edit_urls[controller_name]){
                    let path = edit_urls[controller_name]
                    if(is_common == 1) path += '_common'

                    // location.href = path + '/' + item.id
                    return path + '/' + item.id
                }

                if(single_page_names[controller_name]){
                    if(!categories_hash[item.category]) item.category = "0"
                    this.$emit('edit_item', item)
                } else {

                    if(is_common == 1){

                        if(set_id != 0){
                            return base_url + '/catalog/item_common/' + controller_name + '/' + item.id + '/' + set_id
                        } else {
                            return base_url + '/catalog/item_common/' + controller_name + '/' + item.id
                        }
                    }

                    return base_url + '/catalog/item/' + controller_name + '/' + item.id
                }

            },
            show_swal: function (item) {
                let scope = this;

                // if(controller_name == 'material_types'){
                //     if(item.key == 'ldsp'){
                //         alert('Невозможно удалить ЛДСП')
                //         return
                //     }
                // }

                Swal.fire({
                    title: lang['are_u_sure'],
                    text: lang['delete_confirm_message'],
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: lang['no'],
                    confirmButtonText: lang['yes'],
                    closeOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-danger waves-effect waves-light'
                      }
                }).then(() => {
                    let form_data = new FormData();
                    form_data.append('id', item.id)

                    let url = base_url + '/catalog/remove_item/' + controller_name + '/'

                    if(is_common == 1){
                        url = base_url + '/catalog/remove_item_common/' + controller_name + '/'
                    }

                    send_xhr_post(
                        url,
                        form_data,
                        function (xhr) {
                            scope.change_page(scope.page)
                        })
                });
            },

            show_catalog_modal: async function(item){
                console.log(item)


                Vue.set(this.catalog_categories, 'item', copy_object(item))

                let categories_url = base_url + '/catalog/'+ 'categories_get' +'/' + controller_name

                this.catalog_categories.item_id = item.id;

                this.catalog_categories.ready = 0;

                let categories_data = await promise_request(categories_url);
                categories_data.unshift({
                    name: lang['no'],
                    id: '0',
                    parent: '0'
                })

                let categories_hash = get_hash(categories_data);
                let categories_ordered = flatten(create_tree(categories_data));

                Vue.set(this.catalog_categories, 'hash', categories_hash)
                Vue.set(this.catalog_categories, 'ordered', categories_ordered)

                this.catalog_categories.ready = 1;

                $('#catalog_modal').modal('show')
            },
            add_item_from_catalog: async function(){
                if(controller_name == 'tech' || controller_name == 'comms' || controller_name == 'interior' || controller_name == 'handles' || controller_name == 'facades'){
                    let data = await promise_request(base_url + '/' + controller_name + '/add_item_from_catalog/' + this.catalog_categories.item_id + '/' + this.catalog_categories.value);
                } else {
                    let item = copy_object(this.catalog_categories.item)
                    let fdata = new FormData();

                    Object.keys(item).forEach((k)=>{
                        fdata.append(k, item[k])
                    })

                    fdata.append('new_cat', JSON.stringify(this.catalog_categories.value))
                    let res = await promise_request_post(base_url + '/catalog/item_add_from_catalog/' + controller_name, fdata)
                }


                $('#catalog_modal').modal('hide')
                toastr.success(lang['add_model_success'])
            },
            copy_item: async function(item){
                console.log(item)
                console.log(controller_name)
                let data = await promise_request(base_url + '/catalog/item_copy/' + controller_name + '/' + item.id);
                toastr.success('Копирование успешно')
                this.make_request();
            },
            add_from_catalog: async function(){
                if(controller_name === 'materials'){
                    $('#catalog_modal_materials').modal('show')
                    if(!this.catalog_data){
                        let data = await promise_request(base_url + '/catalog/get_catalog_items/' + controller_name);
                        let cats = create_tree(data.categories);
                        Vue.set(this,'catalog_items', cats)
                        this.catalog_data = true;


                    }
                    return
                }
                location.href = base_url + '/catalog/items_catalog/' + controller_name
            },
            show_swal_catalog: function (item) {
                let scope = this;

                Swal.fire({
                    title: lang['are_u_sure'],
                    text: lang['add_from_catalog_confirm_message'],
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: lang['no'],
                    confirmButtonText: lang['yes'],
                    closeOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-danger waves-effect waves-light'
                      }
                }).then(() => {
                    let form_data = new FormData();
                    form_data.append('id', item.id)

                    send_xhr_post(
                        base_url + '/catalog/add_catalog_items/' + controller_name + '/',
                        form_data,
                        function (xhr) {
                            console.log(xhr.responseText)
                            toastr.success(scope.lang('add_model_success'))
                            // scope.make_request();
                            setTimeout(function () {
                                location.reload();
                            },500)
                        })
                });
            },
            add_multiple: function(){
                location.href = base_url + '/' + controller_name + '/items_add_multiple'
            },
            get_eye_class: function (item) {
                return item.active == 1 ? ['fa-eye', 'btn-primary'] :['fa-eye-slash', 'btn-default']
            },
            save_fs_data: async function(){


                let result = {}

                let data = await promise_request(base_url + '/catalog/items_get_common/facades_systems')

                for (let i = 0; i < data.length; i++){
                    let fs_data = JSON.parse(data[i].data)


                    fs_data.facades.name = fs_data.name;

                    fs_data.materials = [];
                    fs_data.facades.additional_materials = {};

                    let start_id = 1000000000

                    for (let j = 0; j < fs_data.facades.materials_n.length; j++){

                        let it = fs_data.facades.materials_n[j];


                        fs_data.materials.push({
                            id: start_id + j,
                            add_params: it.add_params,
                            params: it.params,
                            name: it.name,
                            category: 0,
                            type: 'Standart',
                            code: ''
                        })

                        fs_data.facades.additional_materials[it.key] = {
                            fixed: 1,
                            key: it.key,
                            materials: [],
                            name: it.name,
                            required: false,
                            selected: start_id + j
                        }



                    }

                    fs_data.id = data[i].id;

                    if(fs_data.modules_set_id && fs_data.modules_set_id != 0){


                        let modules = await promise_request(base_url + '/modules_sets_modules/export_modules/' + fs_data.modules_set_id)
                        console.log(modules)

                        fs_data.modules = modules;

                        result[data[i].id] = fs_data;

                    }

                }

                let f_data = new FormData();
                f_data.append('data', JSON.stringify(result));

                let res = await promise_request_post(base_url + '/catalog/export_facades_systems', f_data)
                toastr.success('Успешно сохранено')

                console.log(res)
            }

        }
    });

}

function create_tree(dataset) {
    let hashTable = Object.create(null)
    dataset.forEach(function (aData) {
        hashTable[aData.id] = aData;
        aData.children = [];
    })
    let dataTree = []
    dataset.forEach(function (aData) {
        if (aData.parent && aData.parent > 0) hashTable[aData.parent].children.push(hashTable[aData.id])
        else dataTree.push(hashTable[aData.id])
    })
    return dataTree
}

function get_hash(data) {
    return data.reduce(function(map, obj) {
        map[obj.id] = obj;
        return map;
    }, {});
}

function check_filename(file) {
    let split_arr = file.name.split('.');
    if(split_arr.length > 2) return false;
    return !(split_arr[0].match(/[^\u0000-\u007f]/));
}

function promise_request (url) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.send();
    return new Promise(function (resolve, reject) {
        xhr.onreadystatechange = function (e) {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let data = JSON.parse(xhr.responseText);
                resolve(data);
            } else if (xhr.readyState == 4) {
                reject();
            }
        };
    });
}

function send_xhr_get(url, ready) {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', url);
    xhr.send();
    xhr.addEventListener("readystatechange", function () {
        if (xhr.readyState === 4) {
            if(typeof ready == "function") ready(xhr);
        }
    });
}

function send_xhr_post(url, data, ready) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    xhr.send(data);
    xhr.addEventListener("readystatechange", function () {
        if (xhr.readyState === 4) {
            ready(xhr);
        }
    });
}

function copy_object(obj) {
    return JSON.parse(JSON.stringify(obj))
}

function save_file( blob, filename ) {
    var link = document.createElement( 'a' );
    link.style.display = 'none';
    document.body.appendChild( link );
    link.href = URL.createObjectURL( blob );
    link.download = filename;
    link.click();
    link.remove();
}

async function get_tree_async(url, no_item) {
    let result = {};
    let data = await promise_request(url)
    if(no_item){
        data.unshift(no_item)
    }
    result.tree = create_tree(copy_object(data));
    result.ordered = flatten(copy_object(result.tree))
    result.hash = get_hash(copy_object(data))

    return result;
}

function show_warning_yes_no(callback, close, cancel) {

    if(!callback){
        console.log('no_callback');
        return;
    }

    if(!close) close = true;
    if(!cancel) cancel = true;

    Swal.fire({
        title: glob.lang['are_u_sure'],
        text: glob.lang['delete_confirm_message'],
        type: "warning",
        showCancelButton: cancel,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: glob.lang['no'],
        confirmButtonText: glob.lang['yes'],
        closeOnConfirm: close,
        customClass: {
            confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
            cancelButton: 'btn btn-label-danger waves-effect waves-light'
          }
    }).then(() => {
        if(typeof callback === 'function') callback();
    });
}

function clean_string(string) {
    return string.toString().replace(/\s{2,}/g, ' ').replace(/\t/g, ' ').trim();
}