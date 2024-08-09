let app = null;
let lang_data = null;
let categories_data = null;
let set_id = '';

document.addEventListener('dragend', function () {
    if(app){
        app.hide_dz = false;
        app.md = false;
    }
})

document.addEventListener('DOMContentLoaded', function () {

    let base_url = document.getElementById('ajax_base_url').value;
    let lang_str = document.getElementById('current_language').value

    if(document.getElementById('set_id')){
        set_id = '/' + document.getElementById('set_id').value
    }


    // let lang_url = "/common_assets/config/lng.php?get_lang=1";
    // if (lang_str != 'default') {
    let  lang_url = base_url + '/languages/get_converted_lang_ajax'
    // }


    Promise.all([
        promise_request(lang_url),
        promise_request(base_url + '/' + document.getElementsByClassName('cat_controller')[0].value + '/categories_get' + set_id)
    ]).then(function (results) {
        if(results[0].custom){
            lang_data = Object.assign(results[0].custom, results[0].original)
        } else {
            lang_data = results[0].original
        }



        // lang_data = results[0];
        categories_data = results[1];
        console.log('Done');
        init_vue();
    }).catch(function () {
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
            test: [
                {
                    name: "task 1",
                    children: [
                        {
                            name: 'child1',
                            categories: []
                        },
                        {
                            name: 'child2',
                            categories: []
                        },
                        {
                            name: 'child3',
                            categories: []
                        }
                    ]

                },
                {
                    name: "task 3",
                    children: []

                },
                {
                    name: "task 5",
                    children: []

                }
            ],
            items: [],
            categories: [],
            base_url: document.getElementById('ajax_base_url').value,
            controller: document.getElementsByClassName('cat_controller')[0].value,
            modals: {
                add_category: {
                    name: '',
                    show: false,
                    root: false,
                    index: 0
                },
                edit_category: {
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
            Vue.set(this, 'items', createDataTree(categories_data))



        },
        mounted: function () {
            this.modals.add_category.name = this.$options['lang'].no_name;
        },
        methods: {

            check_md: function(e, item){
                if(item.children.length){
                    this.md = false
                    this.hide_dz = true;
                } else {
                    this.md = true
                    this.hide_dz = false;
                }

            },
            check_mu: function(item){
                this.md = false
                this.hide_dz = false;
            },
            add_item: function () {
                let scope = this;

                let index = this.modals.add_category.index;

                let parent = this.modals.add_category.root ? 0 : this.items[index].id
                let order = this.modals.add_category.root ? this.items.length + 1 : this.items[index].children.length + 1
                let data = {
                    name: this.modals.add_category.name,
                    parent: parent,
                    active: 1,
                    order: order
                }

                let form_data = new FormData();
                Object.keys(data).forEach(function (key) {
                    form_data.append(key, data[key])
                })

                send_xhr_post(
                    this.base_url + '/' + scope.controller + '/category_add_ajax' + set_id,
                    form_data,
                    function (xhr) {
                        data.id = xhr.response;
                        data.children = [];
                        if(scope.modals.add_category.root){
                            scope.items.push(data)
                        } else {
                            scope.items[index].children.push(data)
                        }

                    })
                this.modals.add_category.show = false;
            },
            update_item: function () {
                let scope = this;
                let form_data = new FormData();
                let data = {
                    id: this.modals.edit_category.id,
                    name: this.modals.edit_category.name,
                    description: this.modals.edit_category.description
                }
                Object.keys(data).forEach(function (key) {
                    form_data.append(key, data[key])
                })

                send_xhr_post(
                    this.base_url + '/'+ this.controller +'/category_edit_ajax' + set_id,
                    form_data,
                    function (xhr) {
                        scope.modals.edit_category.item.name = data.name;
                        scope.modals.edit_category.item.description = data.description;
                    })
                this.modals.edit_category.show = false;
            },
            change_active: function (item, parent) {
                if (item.active == 0) {
                    item.active = 1
                } else {
                    item.active = 0;
                }
                for (let i = item.children.length; i--;){
                    item.children[i].active = item.active;
                }
                send_xhr_get(this.base_url + '/' + this.controller + '/categories_set_active_ajax/' + item.id + '/' + item.active)
            },
            show_swal: function (item, index, child, child_index) {
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
                        scope.base_url + '/'+ scope.controller +'/category_delete_ajax',
                        form_data,
                        function (xhr) {
                            if(child){
                                scope.items[index].children.splice(child_index, 1)
                            } else {
                                scope.items.splice(index, 1)
                            }
                        })
                });
            },

            check_controller: function(){
                return true;
                // if(this.controller === 'modules' || this.controller === 'module_sets') return false;
                // return true
            },

            get_group: function () {
                // if(this.controller !== 'modules' && this.controller !== 'module_sets'){
                return {
                    name: 'children',
                    put: true,
                    pull: true,
                }
                // }
            },
            get_delete_class: function (item) {
                if (item.children.length) {
                    return ['btn-default', 'pointer-none', 'opacity-05']
                } else {
                    return ['btn-danger']
                }
            },
            get_eye_class: function (item, parent) {
                if (parent) {
                    if (parent.active == 1) {
                        if (item.active == 1) {
                            return ['fa-eye', 'btn-primary']
                        } else {
                            return ['fa-solid fa-eye-low-vision', 'btn-default']
                        }
                    } else {
                        return ['fa-solid fa-eye-low-vision', 'btn-default', 'pointer-none', 'opacity-05']
                    }
                } else {
                    if (item.active == 1) {
                        return ['fa-eye', 'btn-primary']
                    } else {
                        return ['fa-solid fa-eye-low-vision', 'btn-default']
                    }
                }

            },

            show_add_modal: function (root, index) {
                this.modals.add_category.name = this.$options['lang'].no_name;
                this.modals.add_category.index = index;
                this.modals.add_category.root = root;
                this.modals.add_category.show = true;
            },
            show_edit_modal: function (item) {
                this.modals.edit_category.name = item.name;
                this.modals.edit_category.description = item.description;
                this.modals.edit_category.id = item.id;
                this.modals.edit_category.item = item;
                this.modals.edit_category.show = true;
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

            lang: function(str){
                return this.$options.lang[str]
            }
        }
    });

}