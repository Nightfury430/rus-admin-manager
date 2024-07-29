//accessories
//categories
//materials_categories


let app = null;
let lang = null;
let controller_name = null;
let base_url = null;
let acc_url = null;

let item_id = null;
let item = null;
let categories_data = null;
let categories_hash = null;
let categories_ordered = null;

let accessories_items = null;
let accessories_items_hash = null;
let accessories_categories_data = null;
let accessories_categories_hash = null;
let accessories_categories_ordered = null;

let materials_items = null;
let materials_items_hash = null;
let materials_items_tree = null;
let materials_categories_data = null;
let materials_categories_hash = null;
let materials_categories_ordered = null;

let materials_lib = {
    tree: '',
    items: '',
    categories: '',
    get_item: function (id) {
        return copy_object(this.items[id]);
    },
    get_category: function (id) {
        return copy_object(this.categories[id]);
    },
}


document.addEventListener('DOMContentLoaded', function () {


    item_id = document.getElementById('item_id').value;
    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;
    lang = JSON.parse(document.getElementById('lang_json').value);
    controller_name = document.getElementById('footer_controller_name').value;
    // controller_name = 'coupe_accessories';

    let requests = [];
    requests.push(promise_request(base_url + '/catalog/categories_get/' + controller_name))
    requests.push(promise_request(base_url + '/catalog/categories_get/coupe_accessories'))
    requests.push(promise_request(base_url + '/catalog/items_get/coupe_accessories'))
    requests.push(promise_request(base_url + '/catalog/categories_get/coupe_materials'))
    requests.push(promise_request(base_url + '/catalog/items_get/coupe_materials'))
    if(item_id != 0) requests.push(promise_request(base_url + '/catalog/get_item/' + controller_name + '/' + item_id))


    Promise.all(requests).then(function (results) {


        categories_data = results[0]
        accessories_categories_data = results[1];
        accessories_items = results[2];
        materials_categories_data = results[3];
        materials_items = results[4];
        if(item_id != 0){
            item = results[5]
        }



        let catalog = {
            categories: copy_object(results[3]),
            items: copy_object(results[4])
        }

        convert_lib(materials_lib, catalog)

        categories_data.unshift({
            name: lang['no'],
            id: '0',
            parent: '0'
        })
        categories_hash = get_hash(categories_data);
        categories_ordered = flatten(create_tree(categories_data));

        accessories_categories_data.unshift({
            name: lang['all'],
            id: '0',
            parent: '0'
        })
        accessories_categories_hash = get_hash(accessories_categories_data);
        accessories_categories_ordered = flatten(create_tree(accessories_categories_data));

        accessories_items_hash = get_hash(accessories_items);



        //
        materials_categories_hash = get_hash(materials_categories_data);
        materials_categories_ordered = flatten(create_tree(materials_categories_data));

        materials_items_hash = get_hash(materials_items);
        init_vue();
    }).catch(function (e) {
        console.log(e)
        console.log('Error');
    });


})

function init_vue() {


    app = new Vue({
        el: '#sub_form',
        components: {
            'v-select': VueSelect.VueSelect
        },
        data: {
            item:{
                id: 0,
                icon: '',
                name: '',
                code: '',
                category: "0",
                description: '',
                params:{
                    models:{
                        handle: '',
                        divider: '',
                        top: '',
                        bottom:'',
                        vertical:'',
                        slider_top:'',
                        slider_bottom:''
                    },
                    accessories:[
                        {
                            min_width:0,
                            items:{}
                        }
                    ],
                    materials:[],
                    prices:{}
                },
                active: 1,
                order: 0
            },
            icon_file: '',
            model_file: {
                handle: '',
                divider: '',
                top: '',
                bottom:'',
                vertical:'',
                slider_top:'',
                slider_bottom:''
            },
            active_tab: 0,
            acc_modal: false,
            acc_cat: 0
        },
        created: function () {
            let scope = this;

            this.$options.categories_ordered = categories_ordered;
            this.$options.categories_hash = categories_hash;
            this.$options.materials_categories_ordered = materials_categories_ordered;
            this.$options.accessories_items_hash = accessories_items_hash;
            this.$options.accessories_categories_hash = accessories_categories_hash;
            this.$options.accessories_categories_ordered = accessories_categories_ordered;
            this.$options.materials_categories_hash = materials_categories_hash;
            this.$options.materials_items_hash = materials_items_hash;

            if(item_id !=0){
                if(item.params) item.params = JSON.parse(item.params)

                for (let i = item.params.materials.length; i--;){
                    if(!materials_categories_hash[item.params.materials[i]]) item.params.materials.splice(i,1)
                }

                if(item.params.prices.length !== undefined) item.params.prices = {};

                let prices_keys = Object.keys(item.params.prices)

                console.log(item.params.prices[prices_keys[0]] && item.params.prices[prices_keys[0]].handle != undefined)

                if(item.params.prices[prices_keys[0]] && item.params.prices[prices_keys[0]].handle != undefined) {
                    item.params.prices = {}
                    for (let i = 0; i < prices_keys.length; i++){
                        if(!item.params.prices[prices_keys[i]]){
                            item.params.prices[prices_keys[i]] = get_hash_for_price(materials_lib.categories[prices_keys[i]].items)
                        }
                    }
                }

                if(item.params.accessories[0].items.length !== undefined) item.params.accessories[0].items = {};

                Object.keys(item.params.prices).forEach(function (k) {
                    if(!item.params.materials.includes(k)) delete item.params.prices[k]
                })

                Object.keys(item.params.accessories).forEach(function (k) {
                    Object.keys(item.params.accessories[k].items).forEach(function (key) {
                        if(!accessories_items_hash[key]) delete item.params.accessories[k].items[key]
                    })
                })


                Vue.set(this,'item',  JSON.parse(JSON.stringify(item)));





                // if(!item.params.materials.length)
                // console.log(item)
                // console.log(item.params.materials)
                // Vue.set(this.item,'params', JSON.parse(JSON.stringify(item.params)))
                // Vue.set(this.item.params,'models', JSON.parse(JSON.stringify(item.params.models)))
                // Vue.set(this.item.params,'materials', JSON.parse(JSON.stringify(item.params.materials)))
            }

        },
        computed:{
            computed_materials_categories_ordered: function () {
                let result = [];
                for (let i = 0; i < materials_categories_ordered.length; i++){
                    if(materials_categories_ordered[i].parent == 0) result.push(materials_categories_ordered[i])
                }
                return result;
            },
            computed_accessories: function () {

                if(this.acc_cat == 0) return accessories_items

                let cats = [];
                cats.push(this.acc_cat)

                if(accessories_categories_hash[this.acc_cat].children.length){
                    for (let i = 0; i < accessories_categories_hash[this.acc_cat].children; i++){
                        cats.push(accessories_categories_hash[this.acc_cat].children[i].id)
                    }
                }

                let result = [];

                for (let i = 0; i < accessories_items.length; i++){
                    if(cats.includes(accessories_items[i].category)) result.push(accessories_items[i])
                }

                return result;
            }
        },
        mounted: function () {

        },
        methods: {
            lang: function(key){
                return lang[key]
            },
            get_data: function(){
                return JSON.parse(JSON.stringify(this.item))
            },
            change_column_price: function(e, cat_key, name){
                let scope = this;
                Object.keys(scope.item.params.prices[cat_key]).forEach(function (k) {
                    scope.item.params.prices[cat_key][k][name] = e.target.value
                })
            },
            materials_categories_change: function(e){
                let scope = this;



                for (let i = 0; i < this.item.params.materials.length; i++){
                    let ind = this.item.params.materials[i];

                    if(!this.item.params.prices[ind]){
                        Vue.set(this.item.params.prices, ind, get_hash_for_price(materials_lib.categories[ind].items))
                    }

                    if(materials_categories_hash[ind].children.length){
                        for (let c = 0; c < materials_categories_hash[ind].children.length; c++){
                            let cind = materials_categories_hash[ind].children[c].id;
                            if(!this.item.params.prices[cind]){
                                Vue.set(this.item.params.prices, cind, get_hash_for_price(materials_lib.categories[cind].items))
                            }
                        }
                    }

                }

                Object.keys(this.item.params.prices).forEach(function (key) {
                    if(!scope.item.params.materials.includes(key)){
                        Vue.delete(scope.item.params.prices, key)
                    }
                })

            },

            add_accessories_size: function(){
                this.item.params.accessories.push({
                    min_width: 0,
                    items: {}
                })

                this.active_tab = this.item.params.accessories.length - 1;
            },
            remove_accessories_size: function(index){
                this.item.params.accessories.splice(index, 1)
                if(index == 0) this.active_tab = 0
                if(index > 0) this.active_tab = index - 1;
            },
            remove_accessory: function(index){
                let scope = this;
                scope.item.params.accessories[scope.active_tab].items[index]--;
                console.log(scope.item.params.accessories[scope.active_tab].items[index]);
                if(scope.item.params.accessories[scope.active_tab].items[index] < 1){
                    Vue.delete(scope.item.params.accessories[scope.active_tab].items, index)
                }
            },
            add_accessory: function(id){

                if( this.item.params.accessories[this.active_tab].items[id]){
                    let val = this.item.params.accessories[this.active_tab].items[id];

                    Vue.set(this.item.params.accessories[this.active_tab].items, id, val+1)
                } else {
                    Vue.set(this.item.params.accessories[this.active_tab].items, id, 1)
                }

            },
            show_swal: function (type, index) {
                let scope = this;

                swal({
                    title: lang['are_u_sure'],
                    text: lang['delete_confirm_message'],
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: lang['no'],
                    confirmButtonText: lang['yes'],
                    closeOnConfirm: true
                }, function () {
                    if(type == 'size') scope.remove_accessories_size(index)
                    if(type == 'accessory'){
                        scope.item.params.accessories[scope.active_tab].items[index]--;
                        console.log(scope.item.params.accessories[scope.active_tab].items[index]);
                        if(scope.item.params.accessories[scope.active_tab].items[index] < 1){
                            Vue.delete(scope.item.params.accessories[scope.active_tab].items, index)
                        }
                    }

                });
            },
            get_icon_src:function (file) {
                if(this.item.icon != '' && this.icon_file == '') return this.correct_url(this.item.icon);
                if(this.icon_file != '') return URL.createObjectURL(file);
                if(this.item.icon == '' &&  this.icon_file == '') return 'https://via.placeholder.com/200x200';
            },
            correct_url: function(path){
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return acc_url + path;
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
            process_model(event, name){

                swal({
                    title: 'В разработке',
                    text: 'В настоящий момент загрузка своих моделей в разработке',
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: lang['no'],
                    confirmButtonText: lang['ok'],
                    closeOnConfirm: true
                }, function () {


                });
                return;

                if(event.target.files.length){
                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.model_file[name] = event.target.files[0];
                } else {
                    this.model_file[name] = '';
                }
            },
            submit: function () {
                let scope = this;
                let data = JSON.parse(JSON.stringify(this.item));
                console.log(data)
                if(!data.params.materials.length){
                    (alert('Не выбрано ни одной категории декоров'));
                    return;
                }

                let key_files = [
                ];

                let formData = new FormData();

                Object.keys(this.model_file).forEach(function (key) {
                    key_files.push('params_models_' + key)
                    if(scope.model_file[key] && scope.model_file[key] != ''){
                        formData.append('params_models_' + key, scope.model_file[key])
                    }
                })

                key_files.push('icon');

                if(scope.icon_file && scope.icon_file != ''){
                    formData.append('icon', scope.icon_file)
                }

                formData.append('key_files', JSON.stringify(key_files));

                if(data.id == 0) delete data.id;
                formData.append('data', JSON.stringify(data));


                send_xhr_post(
                    base_url + '/catalog/add_item_ajax/' + controller_name + '/',
                    formData,
                    function (xhr) {
                        console.log(xhr)
                        // location.href = base_url + '/catalog/items/' + controller_name
                    })


            }

        }
    });

}

function convert_lib(lib, input, height) {
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

    for (let i = 0; i < data.items.length; i++){

        data.items[i].category = JSON.parse(data.items[i].category)
        if(height) data.items[i].height = data.items[i].params.variants[0].height;
        if(Array.isArray(data.items[i].category)){
            for (let c = 0; c < data.items[i].category.length; c++){
                if (data.items[i].category[c] in cat_h) {


                    if (!cat_h[data.items[i].category[c]].items) cat_h[data.items[i].category[c]].items = [];
                    cat_h[data.items[i].category[c]].items.push(data.items[i])
                }
            }
        } else {
            if (data.items[i].category in cat_h) {
                if (!cat_h[data.items[i].category].items) cat_h[data.items[i].category].items = [];
                cat_h[data.items[i].category].items.push(data.items[i])
            }
        }




    }

    let dataTree = []
    for (let i = 0; i < data.categories.length; i++){
        if( data.categories[i].parent && data.categories[i].parent !=0){
            cat_h[data.categories[i].parent].categories.push(cat_h[data.categories[i].id])
        }
        else dataTree.push(cat_h[data.categories[i].id])
    }

    lib.tree = dataTree


}



function get_hash_for_price(data) {
    return data.reduce(function(map, obj) {
        map[obj.id] = {
            handle: 0,
            vertical: 0,
            divider: 0,
            top: 0,
            bottom: 0,
            slider_top:0,
            slider_bottom:0
        };
        return map;
    }, {});
}


