let app = null;
let lang_data = null;
let categories_data = null;
let categories_hash = null;
let categories_order = null;
let materials_categories = null;
let materials_hash = null;
let clear_item = {
    id:0,
    name: '',
    code: '',
    category: '0',
    icon: '',
    params:{
        model:'',
        height: 30,
        depth: 20
    },
    materials:[],
    prices:{},
    active: 1,
    order: 0
}
let set_id = '';
let acc_url = null;


document.addEventListener('DOMContentLoaded', function () {

    let base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;

    let lang_str = document.getElementById('current_language').value
    let  lang_url = base_url + '/languages/get_converted_lang_ajax'


    Promise.all([
        promise_request(lang_url),
        promise_request(base_url + '/' + document.getElementsByClassName('cat_controller')[0].value + '/items_get' + set_id),
        promise_request(base_url + '/' + document.getElementsByClassName('cat_controller')[0].value + '/categories_get' + set_id),
        promise_request(base_url + '/' + 'materials/categories_get' + set_id),
    ]).then(function (results) {
        if(results[0].custom){
            lang_data = Object.assign(results[0].custom, results[0].original)
        } else {
            lang_data = results[0].original
        }


        results[2].unshift({
            name: 'Без категории',
            id: '0',
            parent: '0'
        })

        items_data = results[1];

        for (let i = items_data.length; i--;){
            items_data[i].params = JSON.parse(items_data[i].params)
            items_data[i].materials = JSON.parse(items_data[i].materials)
            items_data[i].prices = JSON.parse(items_data[i].prices)
        }

        categories_data = results[2];
        materials_categories = results[3];

        categories_hash = get_hash(categories_data)
        categories_hash[0] = {
            id:0,
            name: 'Без категории'
        }







        materials_hash = get_hash(materials_categories);

        console.log('Done');
        init_vue();
    }).catch(function (e) {
        console.log(e)
        console.log('Error');
    });


    // init_vue();


    function power_of_2(n) {
        if (typeof n !== 'number')
            return 'Not a number';

        return n && (n & (n - 1)) === 0;
    }
});

const createDataTree = dataset => {
    let hashTable = Object.create(null)
    dataset.forEach(aData => hashTable[aData.id] = {...aData, children: []})
    let dataTree = []
    dataset.forEach(aData => {
        if (aData.parent && aData.parent > 0) hashTable[aData.parent].children.push(hashTable[aData.id])
        else dataTree.push(hashTable[aData.id])
    })
    return dataTree
}

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components: {
            draggable: vuedraggable,
            'v-select': VueSelect.VueSelect
        },
        data: {
            errors: [],
            settings: {},
            drag: false,
            kitchen_models: [],
            show_success_message: false,
            items: [
                {
                    id: 1,
                    name: 'Плинтус 1',
                    code: 'asd',
                    category: 1,
                    icon: 'https://via.placeholder.com/200x200',
                    params:{
                        model:'',
                        height: 30,
                        depth: 20
                    },
                    materials: [23],
                    active: 1,
                    order: 1
                },
                {
                    id: 2,
                    name: 'Плинтус 2',
                    code: 'A-10',
                    category: 4,
                    icon: 'https://via.placeholder.com/200x200',
                    params:{},
                    materials: [26],
                    active: 1,
                    order: 100
                }
            ],
            categories: [],


            current_item:{},
            icon_file:'',
            model_file: '',

            base_url: document.getElementById('ajax_base_url').value,
            controller: document.getElementsByClassName('cat_controller')[0].value,
            modals: {
                add: {
                    name: '',
                    show: false,
                    root: false,
                    index: 0
                },
                edit: {
                    name: '',
                    id: 0,
                    description:'',
                    item: null,
                    show: false
                }
            },
            md: false,
            hide_dz: false
        },
        created: function () {
            this.$options.lang = lang_data;
            this.$options.categories_hash = categories_hash;
            this.$options.materials_hash = materials_hash;


            let cat_tree = createDataTree(categories_data);

            this.$options.cat_ordered = flatten(cat_tree);

            let mat_tree = createDataTree(materials_categories);
            this.$options.materials_ordered = flatten(mat_tree);




            Vue.set(this, 'categories', cat_tree)
            Vue.set(this, 'items', items_data)
            Vue.set(this, 'current_item', clear_item)

        },
        computed:{
            computed_prices_categories: function () {
                let result = [];
                let scope = this;

                let parent_ids = JSON.parse(JSON.stringify(scope.current_item.materials));


            }
        },
        mounted: function () {
            this.modals.add.name = this.$options['lang'].no_name;
        },
        methods: {

            add_item: function () {
                let scope = this;

                let data = JSON.parse(JSON.stringify(scope.current_item))


                let form_data = new FormData();
                form_data.append('id', data.id);
                form_data.append('name', data.name);
                form_data.append('code', data.code);
                form_data.append('category', data.category);
                form_data.append('active', data.active);
                form_data.append('order', data.order);
                form_data.append('materials', JSON.stringify(data.materials));
                form_data.append('params', JSON.stringify(data.params));
                form_data.append('prices', JSON.stringify(data.prices));
                if(scope.icon_file != '') form_data.append('icon', scope.icon_file);
                if(scope.model_file != '') form_data.append('model', scope.model_file);

                send_xhr_post(
                    this.base_url + '/' + scope.controller + '/item',
                    form_data,
                    function (xhr) {
                        // console.log(xhr.response);
                        // console.log(JSON.parse(xhr.response));
                        let result = JSON.parse(xhr.response);
                        result.params = JSON.parse(result.params)
                        result.materials = JSON.parse(result.materials)
                        result.prices = JSON.parse(result.prices)
                        if(data.id == 0){
                            scope.items.push(result)
                        }
                    })
                this.modals.add.show = false;
                Vue.set(this, 'current_item', clear_item);
            },
            show_swal: function (item, index) {
                let scope = this;

                swal({
                    title: this.$options.lang['are_u_sure'],
                    text: this.$options.lang['delete_confirm_message'],
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: this.$options.lang['no'],
                    confirmButtonText: this.$options.lang['yes'],
                    closeOnConfirm: true
                }, function () {

                    let form_data = new FormData();
                    form_data.append('id', item.id)

                    send_xhr_post(
                        scope.base_url + '/'+ scope.controller +'/items_remove',
                        form_data,
                        function (xhr) {
                                scope.items.splice(index, 1)
                        })
                });
            },

            check_controller: function(){
                if(this.controller === 'modules' || this.controller === 'module_sets') return false;
                return true
            },

            get_group: function () {
                if(this.controller !== 'modules' && this.controller !== 'module_sets'){
                    return {
                        name: 'children',
                        put: true,
                        pull: true,
                    }
                }
            },
            get_delete_class: function (item) {
                if (item.children.length) {
                    return ['btn-default', 'pointer-none', 'opacity-05']
                } else {
                    return ['btn-danger']
                }
            },
            get_eye_class: function (item) {
                return item.active ? ['fa-eye', 'btn-primary'] : ['fa-eye-slash', 'btn-default']
            },

            show_add_modal: function (item) {
                if(!item){
                    Vue.set(this, 'current_item', clear_item )
                } else {
                    Vue.set(this, 'current_item', item )




                }
                this.modals.add.show = true;
            },


            start_drag: function(){
                this.drag = true
            },
            end_drag: function () {
                this.drag = false
                let scope = this;
                let form_data = new FormData();
                form_data.append('data', JSON.stringify(this.get_data()))
                this.md = false;
                send_xhr_post(
                    this.base_url + '/' + this.controller + '/categories_order_update' + set_id,
                    form_data,
                    function (xhr) {
                        console.log(xhr)
                    })
            },
            get_data: function () {
                let data = JSON.parse(JSON.stringify(this.items))
                let result = [];
                for (let i = 0; i < data.length; i++) {
                    data[i].order = i + 1;
                    result.push({
                        id: data[i].id,
                        order: data[i].order,
                        parent: 0
                    })
                    if (data[i].children.length) {
                        for (let c = 0; c < data[i].children.length; c++) {
                            data[i].children[c].order = c + 1;
                            result.push({
                                id: data[i].children[c].id,
                                order: data[i].children[c].order,
                                parent: data[i].id
                            })
                        }
                    }
                }
                console.log(result)

                return result;
            },

            materials_categories_change: function(e){
              console.log(e)
            },

            lang: function(str){
                return this.$options.lang[str]
            },

            get_icon_src:function (file) {
                if(this.current_item.icon != '' && this.icon_file == '') return this.correct_url(this.current_item.icon);
                if(this.icon_file != '') return URL.createObjectURL(file);
                if(this.current_item.icon == '' &&  this.icon_file == '') return 'https://via.placeholder.com/200x200';
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
            process_model(event){

                if(event.target.files.length){
                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.model_file = event.target.files[0];
                } else {
                    this.model_file = '';
                }
            },



        }
    });
}





