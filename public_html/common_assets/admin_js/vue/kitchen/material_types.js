let app;
let data = {};
let materials;
let categories;
let categories_hash;
let item = null;
document.addEventListener('Glob_ready', async function () {
    let requests = [];
    requests.push(promise_request(glob.base_url + '/catalog/categories_get/materials'))
    requests.push(promise_request(glob.base_url + '/catalog/items_get/materials'))

    if(glob.item_id != 0){
        requests.push(promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id))
    }

    let result = await Promise.all(requests)



    categories = result[0]
    materials = result[1]
    if(glob.item_id != 0){
        item = result[2]
        console.log(item)
    }

    categories_hash = get_hash(categories)

    data.materials_categories_top = [];
    for (let i = 0; i < categories.length; i++){
        if(!categories[i].parent || categories[i].parent == 0){
            data.materials_categories_top.push(categories[i])
        }
    }

    data.materials_tree = get_tree(materials, categories)

    console.log(data)
    init_vue();
})

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components:{
            'v-select': VueSelect.VueSelect
        },
        data: {
            active: 1,
            drag: false,
            item:{
                id: 0,
                name: '',
                type: 'm2',
                default: 'v_',
                variants: [
                    {
                        name: '',
                        key: 'v_',
                        size: {
                            x: 0,
                            y: 0,
                            z: 0,
                        },
                        id: '',
                        categories: [],
                        prices: {}
                    }
                ]
            },
            errors: [],
            show_success_message: false,
            delay: 500,
            debounce: null
        },
        computed:{

        },
        mounted: function(){

        },
        created: function(){

            if(item != null){
                item.data = JSON.parse(item.data);
                let variants = [];
                Object.keys(item.data.variants).forEach(function (k) {
                    if(!item.data.variants[k].prices){
                        item.data.variants[k].prices = {};

                        for (let i = item.data.variants[k].categories.length; i--;){
                            if(!categories_hash[item.data.variants[k].categories[i]]){
                                item.data.variants[k].categories.splice(i, 1);
                                continue
                            }
                            let cat = item.data.variants[k].categories[i];
                            if(categories_hash[cat]){
                                let cat_obj = categories_hash[cat];

                                item.data.variants[k].prices[cat] = {
                                    name: cat_obj.name,
                                    price: 0,
                                    code: ''
                                };

                                if(cat_obj.categories && cat_obj.categories.length){
                                    for (let j = 0; j < cat_obj.categories.length; j++){
                                        let subcat_obj = cat_obj.categories[j];
                                        item.data.variants[k].prices[subcat_obj.id] = {
                                            name: subcat_obj.name,
                                            price: 0,
                                            code: ''
                                        };
                                    }
                                }
                            }
                        }
                    } else {
                        for (let i = item.data.variants[k].categories.length; i--;){
                            if(!categories_hash[item.data.variants[k].categories[i]]){
                                item.data.variants[k].categories.splice(i, 1);
                            }
                        }
                    }

                    variants.push(item.data.variants[k])
                })



                item.data.variants = variants;


                Vue.set(this, 'item', item.data)
            }

            this.$options.materials_categories_top = data.materials_categories_top
            this.$options.materials_tree = data.materials_tree
        },
        methods: {
            change_name: function(){
                let tr = translit(this.item.name)
                let arr = tr.split(' ');
                tr = arr.join('_')
                if(this.item.id == 0){
                    this.item.key = tr.toLowerCase()
                }

                // if(this.item.key != 'ldsp'){
                //     this.item.key = tr.toLowerCase()
                // }

            },
            set_variant_key: function(variant){
                let str = 'v_'
                switch (this.item.type) {
                    case 'm2':
                        str += variant.size.z
                        break;
                    case 'pm':
                        str += variant.size.y + '_' + variant.size.z
                        break;
                }
                // str += '_' + translit(variant.name).toLowerCase()
                variant.key = str;
            },
            add_variant: function(){


                let last_var = JSON.parse(JSON.stringify(this.item.variants[this.item.variants.length-1]))


                this.item.variants.push( {
                    name: '',
                    key: 'v_',
                    size: {
                        x: 0,
                        y: 0,
                        z: 0,
                    },
                    prices: last_var.prices,
                    id: last_var.id,
                    categories: last_var.categories
                })
            },
            remove_variant: function(index){
                let scope = this;
                show_warning_yes_no(
                    function () {
                        scope.item.variants.splice(index, 1)
                    }
                )

            },

            change_variant_categories: function(index){
                let variant = this.item.variants[index];
                let active_cats = {};

                for (let i = variant.categories.length; i--;){
                    let cat = variant.categories[i];
                    let cat_obj = categories_hash[cat];
                    active_cats[variant.categories[i]] = true;
                    if (cat_obj.categories && cat_obj.categories.length) {
                        for (let j = 0; j < cat_obj.categories.length; j++) {
                            active_cats[cat_obj.categories[j].id] = true;
                        }
                    }
                }

                let prices = variant.prices;
                Object.keys(prices).forEach((k)=>{
                    if(!active_cats[k]){
                        delete prices[k];
                    }
                })

                Object.keys(active_cats).forEach((k)=>{
                    if(!prices[k]){
                        prices[k] = {
                            name: categories_hash[k].name,
                            price: 0,
                            code: ''
                        }
                    }
                })

            },
            copy_price: function(variant, cat_id){
                let prices = variant.prices;
                let cat_obj = categories_hash[cat_id];
                if(cat_obj.categories && cat_obj.categories.length){
                    for (let i = 0; i < cat_obj.categories.length; i++) {
                        let subcat_obj = cat_obj.categories[i];
                        variant.prices[subcat_obj.id].price = parseFloat(variant.prices[cat_id].price);
                    }
                }
            },
            format_variant_prices: function(variant) {
                let prices = variant.prices;
                let categories = variant.categories;
                let result = [];
                for (let i = 0; i < categories.length; i++){
                    let cat = categories[i];
                    let cat_obj = categories_hash[cat];
                    result.push(cat_obj);
                }

                return result;

            },

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
            correct_url: function(path){
                if(!path) return '';
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return glob.acc_url + path;
                }
            },

            get_data: function(){
                return JSON.parse(JSON.stringify(this.item));
            },
            submit: async function (e) {
                e.preventDefault();
                let scope = this;

                let errors = [];
                let data = this.get_data();
                let variants = {};
                for (let i = 0; i < data.variants.length; i++){
                   let v = data.variants[i];
                   switch (data.type) {
                       case 'm2':
                           if(v.size.z < 1){
                               errors.push('Толщина варианта ' + v.name + ' не может быть меньше 1\n')
                           }
                           break;
                       case 'm':
                           if(v.size.z < 1){
                               errors.push('Толщина варианта ' + v.name + ' не может быть меньше 1')
                           }
                           if(v.size.y < 1){
                               errors.push('Высота варианта ' + v.name + ' не может быть меньше 1')
                           }
                           break;
                   }
                   variants[v.key] = v;
                }

                data.variants = variants;

                if(errors.length) {
                    alert(errors.join('\n'))
                    return
                }

                console.log(data);

                let form_data = new FormData();
                form_data.append('data', JSON.stringify(data));

                let url = glob.base_url + '/material_types/item_add';
                if(item != null) url += '/' + glob.item_id;

                let res = await promise_request_post(url, form_data)
                if(res == 'ok'){
                    window.location = document.getElementById('form_success_url').value
                }

                return false;
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