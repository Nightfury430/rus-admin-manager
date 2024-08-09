let app = null;
let lang = null;
let controller_name = null;
let base_url = null;
let acc_url = null;

let lang_data = null;
let categories_data = null;
let set_id = '';
let is_common = 0;
let get_method_name = 'categories_get';

let method_name = {
    get: 'categories_get',
    add: 'categories_add',
    update: 'categories_update',
    active: 'categories_set_active',
    delete: 'category_delete_ajax',
    order: 'categories_order_update'
}

let contr_names = {
    'materials': 'Декоры',
    'handles': 'Ручки',
    'tech': 'Техника',
    'comms': 'Коммуникации',
    'interior': 'Интерьер',
    'modules': 'Модули',
    'facades': 'Фасады',
    'glass': 'Витрины',
}

document.addEventListener('dragend', function () {
    if (app) {
        app.hide_dz = false;
        app.md = false;
    }
})

document.addEventListener('DOMContentLoaded', function () {

    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;
    lang = JSON.parse(document.getElementById('lang_json').value);
    controller_name = document.getElementById('footer_controller_name').value;
    // controller_name = 'coupe_accessories';


    if (document.getElementById('set_id')) {
        set_id = '/' + document.getElementById('set_id').value
    }

    if (document.getElementById('is_common')) {
        is_common = document.getElementById('is_common').value
    }

    if (is_common == 1) {
        Object.keys(method_name).forEach(function (k) {
            method_name[k] = method_name[k] + '_common';
        })
    }


    Promise.all([
        promise_request(base_url + '/catalog/' + method_name.get + '/' + controller_name + set_id),
    ]).then(function (results) {
        categories_data = results[0];

        for (let i = categories_data.length; i--;) {
            categories_data[i].title = categories_data[i].name;
            categories_data[i].data = copy_object(categories_data[i]);
        }

        console.log('Done');
        init_vue();
    }).catch(function () {
        console.log('Error');
    });


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
        aData.title = aData.name
        if (aData.parent && aData.parent > 0) {
            if (hashTable[aData.parent]) {
                hashTable[aData.parent].children.push(hashTable[aData.id])
            }

        } else dataTree.push(hashTable[aData.id])
    })
    return dataTree
}

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components: {
            draggable: vuedraggable,
            'v-select': VueSelect.VueSelect,
            SlVueTree: SlVueTree
        },
        data: {
            data_ready: false,
            errors: [],
            settings: {},
            is_common: is_common,
            show_id: false,
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
            selectedNodesTitle: '',
            base_url: document.getElementById('ajax_base_url').value,
            controller: controller_name,
            modals: {
                add_category: {
                    name: '',
                    parent: '0',
                    show: false,
                    root: false,
                    item: null,
                    index: 0
                },
                edit_category: {
                    name: '',
                    parent: '0',
                    id: 0,
                    description: '',
                    item: null,
                    image: '',
                    show: false,
                }
            },
            md: false,
            hide_dz: false
        },
        created: function () {

            console.log(create_tree(categories_data))


            Vue.set(this, 'items', create_tree(categories_data))

            // Vue.set(this, 'items', createDataTree(categories_data))
        },
        mounted: function () {
            this.modals.add_category.name = lang['no_name'];
            this.data_ready = true;
        },
        methods: {

            correct_url: function (path) {
                if (path == '') return '/common_assets/images/placeholders/125x125.png'
                return glob.acc_url + path + '?' + new Date().getTime();
            },

            sel_file: function (file) {
                this.modals.edit_category.image = file.true_path.substr(1);
                $('#filemanager').modal('toggle')
            },

            check_md: function (e, item) {
                if (item.children.length) {
                    this.md = false
                    this.hide_dz = true;
                } else {
                    this.md = true
                    this.hide_dz = false;
                }

            },
            check_mu: function (item) {
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
                    base_url + '/catalog/' + method_name.add + '/' + controller_name + set_id,
                    form_data,
                    function (xhr) {
                        data.id = xhr.response;
                        data.children = [];
                        if (scope.modals.add_category.root) {
                            scope.items.push(data)
                        } else {
                            scope.items[index].children.push(data)
                        }

                    })
                this.modals.add_category.show = false;
            },
            add_item_n: function () {
                let scope = this;
                let node = this.modals.add_category.item
                let parent = 0;
                let order = this.$refs.sl_tree.nodes.length + 1;
                if (node) {
                    parent = node.data.id;
                    order = node.children.length + 1
                }
                console.log(parent)
                console.log(node)
                console.log(order)
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
                    base_url + '/catalog/' + method_name.add + '/' + controller_name + set_id,
                    form_data,
                    function (xhr) {
                        let pos = {
                            node: scope.$refs.sl_tree.getLastNode(),
                            placement: 'after'
                        }
                        if(node){
                            pos.node = node
                            pos.placement = 'inside';
                            if (node.children.length) {
                                pos.node = node.children[node.children.length - 1]
                                pos.placement = 'after';
                            }
                        }


                        scope.$refs.sl_tree.insert(
                            pos,
                            {
                                title: scope.modals.add_category.name,
                                children: [],
                                data: {
                                    id: xhr.response,
                                    name: scope.modals.add_category.name,
                                    parent: parent,
                                    active: 1,
                                    order: order
                                }
                            }
                        )
                    })


                // let scope = this;
                //
                // let index = this.modals.add_category.index;
                //
                // let parent = this.modals.add_category.root ? 0 : this.items[index].id
                // let order = this.modals.add_category.root ? this.items.length + 1 : this.items[index].children.length + 1
                // let data = {
                //     name: this.modals.add_category.name,
                //     parent: parent,
                //     active: 1,
                //     order: order
                // }
                //
                // let form_data = new FormData();
                // Object.keys(data).forEach(function (key) {
                //     form_data.append(key, data[key])
                // })
                //
                // send_xhr_post(
                //     base_url + '/catalog/'+ method_name.add +'/' + controller_name + set_id,
                //     form_data,
                //     function (xhr) {
                //         data.id = xhr.response;
                //         data.children = [];
                //         if(scope.modals.add_category.root){
                //             scope.items.push(data)
                //         } else {
                //             scope.items[index].children.push(data)
                //         }
                //
                //     })
                this.modals.add_category.show = false;
            },
            update_item: function () {
                let scope = this;
                let form_data = new FormData();
                let data = {
                    id: this.modals.edit_category.id,
                    name: this.modals.edit_category.name,
                    description: this.modals.edit_category.description,
                    image: this.modals.edit_category.image
                }
                Object.keys(data).forEach(function (key) {
                    form_data.append(key, data[key])
                })

                send_xhr_post(
                    base_url + '/catalog/' + method_name.update + '/' + controller_name + set_id,
                    form_data,
                    function (xhr) {
                        scope.modals.edit_category.item.name = data.name;
                        scope.modals.edit_category.item.title = data.name;
                        scope.modals.edit_category.item.description = data.description;
                    })
                this.modals.edit_category.show = false;
            },


            show_edit_modal_n: function (item) {
                if (item.data.image == null) item.data.image = '';

                console.log(item.data.parent)

                this.modals.edit_category.name = item.data.name;
                if (item.data.description == null) item.data.description = '';
                // this.modals.edit_category.parent = item.data.parent + '';
                this.modals.edit_category.description = item.data.description;
                this.modals.edit_category.id = item.data.id;
                this.modals.edit_category.item = item;
                this.modals.edit_category.image = item.data.image;
                this.modals.edit_category.show = true;
            },
            update_item_n: function () {
                let scope = this;
                let form_data = new FormData();
                let data = {
                    id: this.modals.edit_category.id,
                    name: this.modals.edit_category.name,
                    description: this.modals.edit_category.description,
                    image: this.modals.edit_category.image
                }
                Object.keys(data).forEach(function (key) {
                    form_data.append(key, data[key])
                })

                send_xhr_post(
                    base_url + '/catalog/' + method_name.update + '/' + controller_name + set_id,
                    form_data,
                    function (xhr) {
                        scope.modals.edit_category.item.data.image = data.image;
                        scope.modals.edit_category.item.data.name = data.name;
                        scope.modals.edit_category.item.title = data.name;
                        scope.modals.edit_category.item.data.description = data.description;

                        console.log(copy_object(scope.modals.edit_category.item))

                        let node = scope.$refs.sl_tree.getNode(scope.modals.edit_category.item.path)
                        console.log(data)
                        console.log(node)
                        scope.modals.edit_category.item.title = data.name
                        node.title = data.name;
                        node.data.title = data.name;
                        node.data.name = data.name;
                        node.data.description = data.description;
                        node.data.image = data.image;
                        console.log(node)

                    })
                this.modals.edit_category.show = false;
            },


            change_active: function (item, parent) {
                if (item.active == 0) {
                    item.active = 1
                } else {
                    item.active = 0;
                }
                for (let i = item.children.length; i--;) {
                    item.children[i].active = item.active;
                }
                send_xhr_get(base_url + '/catalog/' + method_name.active + '/' + controller_name + '/' + item.id + '/' + item.active)
            },
            change_active_n: function (item) {
                console.log(item)

                let it = this.$refs.sl_tree.getNode(item.path);

                if (it.data.active == 0) {
                    // Vue.set(item.data, 'active', 1)
                    it.data.active = 1
                } else {
                    it.data.active = 0;
                    // Vue.set(item.data, 'active', 0)
                }


                for (let i = it.children.length; i--;) {
                    it.children[i].data.active = it.data.active;
                }
                send_xhr_get(base_url + '/catalog/' + method_name.active + '/' + controller_name + '/' + it.data.id + '/' + it.data.active)
            },


            show_swal: function (item, index, child, child_index) {
                let scope = this;

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
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                      },
                }).then(() => {
                    let form_data = new FormData();
                    form_data.append('id', item.id)

                    send_xhr_post(
                        base_url + '/catalog/' + method_name.delete + '/' + controller_name,
                        form_data,
                        function (xhr) {
                            if (child) {
                                scope.items[index].children.splice(child_index, 1)
                            } else {
                                scope.items.splice(index, 1)
                            }
                        })
                });
            },
            show_swal_n: function (item, index, child, child_index) {
                let scope = this;

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
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                      },
                }).then(() => {
                    let form_data = new FormData();
                    form_data.append('id', item.data.id)

                    send_xhr_post(
                        base_url + '/catalog/' + method_name.delete + '/' + controller_name,
                        form_data,
                        function (xhr) {
                            scope.$refs.sl_tree.remove([item.path]);
                        })
                });
            },

            show_swal_clear: function (item, index) {
                let scope = this;

                Swal.fire({
                    title: lang['are_u_sure'],
                    text: lang['delete_confirm_message'],
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: lang['no'],
                    confirmButtonText: lang['yes'],
                    closeOnConfirm: false,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                      },
                }).then(() =>{
                    send_xhr_get(
                        base_url + '/materials/remove_items_by_cat_common/' + item.id + '/drop_true',
                        function (xhr) {
                            Swal.fire("Очищена!", "Категория очищена", "success");
                        })

                });
            },

            check_controller: function () {
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

            get_eye_class_n: function (node) {

                if (node.data.active == 1) {
                    return ['fa-eye', 'btn-primary']
                } else {
                    return ['fa-solid fa-eye-low-vision', 'btn-default']
                }

                // if(node.path.length > 1){
                //
                //     let parent = this.$refs.sl_tree.getNode(node.path.pop())
                //     console.log(parent)
                // }


                //

                // if (parent) {
                //     if (parent.active == 1) {
                //         if (item.active == 1) {
                //             return ['fa-eye', 'btn-primary']
                //         } else {
                //             return ['fa-solid fa-eye-low-vision', 'btn-default']
                //         }
                //     } else {
                //         return ['fa-solid fa-eye-low-vision', 'btn-default', 'pointer-none', 'opacity-05']
                //     }
                // } else {
                //     if (item.active == 1) {
                //         return ['fa-eye', 'btn-primary']
                //     } else {
                //         return ['fa-solid fa-eye-low-vision', 'btn-default']
                //     }
                // }

            },

            show_add_modal: function (root, index) {
                this.modals.add_category.name = lang['no_name'];
                this.modals.add_category.index = index;
                this.modals.add_category.root = root;
                this.modals.add_category.show = true;
            },

            show_add_modal_n: function (item) {
                this.modals.add_category.name = lang['no_name'];
                this.modals.add_category.item = item;
                this.modals.add_category.show = true;
            },

            show_edit_modal: function (item) {
                if (item.image == null) item.image = '';

                this.modals.edit_category.name = item.name;
                if (item.description == null) item.description = '';
                this.modals.edit_category.description = item.description;
                this.modals.edit_category.id = item.id;
                this.modals.edit_category.item = item;
                this.modals.edit_category.image = item.image;
                this.modals.edit_category.show = true;
            },


            start_drag: function () {
                this.drag = true
            },
            end_drag: function () {
                this.drag = false
                let scope = this;
                let form_data = new FormData();
                form_data.append('data', JSON.stringify(this.get_data()))
                this.md = false;
                send_xhr_post(
                    base_url + '/catalog/' + method_name.order + '/' + controller_name + set_id,
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

            nodeSelected: function () {
            },
            node_selected: function () {
            },
            nodeDropped(nodes, position, event) {
                console.log(nodes)
                console.log(position)
                console.log(event)


                if(position.placement == 'inside'){
                    nodes[0].data.parent = position.node.data.id;
                    // this.update_item_n2(nodes[0])
                }

                if(position.placement == 'before' || position.placement == 'after'){
                    if(position.node.path.length > 1){
                        let parent = this.$refs.sl_tree.getNode(position.node.path.slice(0,-1))
                        nodes[0].data.parent = parent.data.id;
                    } else {
                        nodes[0].data.parent = 0;
                    }
                }



                // let nd = this.$refs.sl_tree.getNode(nodes[0].path);
                // nd.data.parent = nodes[0].data.parent;
                // nd.parent = nodes[0].data.parent;
                //
                // console.log(1111111111111)
                // console.log(nodes[0].data.parent)
                //
                let nods = copy_object(this.$refs.sl_tree.nodes)
                set_order(nods);
                let ordered = flatten(nods)
                let data = [];
                for (let i = 0; i < ordered.length; i++){
                    data.push({
                        id: ordered[i].data.id,
                        parent: ordered[i].data.parent,
                        order: ordered[i].data.order
                    })
                }
                console.log(data)

                let form_data = new FormData();
                form_data.append('data', JSON.stringify(data))
                send_xhr_post(
                    base_url + '/catalog/' + method_name.order + '/' + controller_name + set_id,
                    form_data,
                    function (xhr) {
                        console.log(xhr)
                    })





            },

            update_item_n2: function (node) {
                let scope = this;
                let form_data = new FormData();
                let data = node.data;
                Object.keys(data).forEach(function (key) {
                    form_data.append(key, data[key])
                })

                send_xhr_post(
                    base_url + '/catalog/' + method_name.update + '/' + controller_name + set_id,
                    form_data,
                    function (xhr) {
                        console.log(xhr)
                    })
                this.modals.edit_category.show = false;
            },

            context_menu: function () {
            },

            lang: function (str) {
                return lang[str]
            }
        }
    });

}


function set_order(data) {
    for (let i = 0; i < data.length; i++){
        order_children(data[i], data[i].data.id)
    }
}


function order_children(input, parent) {
    for (let i = 0; i < input.children.length; i++){
        input.children[i].data.order = i + 1;
        input.children[i].data.parent = parent;
        if(input.children[i].children){
            order_children(input.children[i], input.children[i].data.id)
        }
    }
}