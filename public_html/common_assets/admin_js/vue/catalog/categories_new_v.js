((window) => {
    let lang = null;
    let controller_name = null;
    let base_url = null;

    let categories_data = null;
    let set_id = '';
    let is_common = 0;

    let method_name = {
        get: 'categories_get',
        add: 'categories_add',
        update: 'categories_update',
        active: 'categories_set_active',
        delete: 'category_delete_ajax',
        order: 'categories_order_update',
    }

    document.addEventListener("Glob_ready", async () => {
        prepare();
        await getData();
        run();
    });

    function prepare() {
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
    }

    async function getData() {
        let results;

        try {
            results = await promise_request(base_url + '/catalog/' + method_name.get + '/' + controller_name + set_id);
        } catch (error) {
            console.log('Error');
            return;
        }

        categories_data = results;

        for (let i = categories_data.length - 1; i >= 0; i--) {
            categories_data[i].title = categories_data[i].name;
        }

        console.log('Done');
    }

    function run() {
        const { createApp, shallowRef, ref, onMounted, watch } = Vue;

        const app = createApp({
            setup() {
                const data_ready = ref(false);
                const is_common_opt = ref(is_common);
                const base_url = ref(document.getElementById('ajax_base_url').value);
                const controller = ref(controller_name);
                const pp_category_options = shallowRef([]);
                let pp_category_options_changed = false;

                const modals = ref({
                    edit_category: {
                        name: '',
                        parent: '0',
                        id: 0,
                        description: '',
                        item: null,
                        image: '',
                        show: false,
                        isNew: false,
                    }
                });

                const nodes = shallowRef([]);
                const tree = ref(null);
                const maxHeight = 450;

                watch(() => modals.value.edit_category.show, editCategoryShowChange);

                onMounted(() => {
                    nodes.value = create_tree(categories_data);
                    pp_category_options_changed = true;
                    data_ready.value = true;
                });

                function editCategoryShowChange(show) {
                    if (!show) {
                        return;
                    }

                    const api = tree.value.api;

                    if (pp_category_options_changed) {
                        pp_category_options_changed = false;
                        pp_category_options.value = api.getFlatTree();
                    }

                    const id = modals.value.edit_category.id;

                    const options = pp_category_options.value;

                    const disabledIds = [];

                    if (!modals.value.edit_category.isNew) {
                        api.walkNodeTree(api.findIndexById(id), (x) => {
                            disabledIds.push(`${x.id}`);
                        });
                    }

                    for (const option of options) {
                        if (option.preventChildren) {
                            continue;
                        }

                        if (disabledIds.includes(`${option.id}`)) {
                            option.disabled = true;
                            continue;
                        }

                        if (option.disabled) {
                            option.disabled = false;
                        }
                    }

                    pp_category_options.value = [...options];
                }

                function addHandler(e) {
                    const item = getNodeFromClickEvent(e);

                    modals.value.edit_category.name = lang['no_name'];
                    modals.value.edit_category.parent = item ? `${item.id}` : "0";
                    modals.value.edit_category.description = '';
                    modals.value.edit_category.id = item ? `${item.id}` : null;
                    modals.value.edit_category.item = item;
                    modals.value.edit_category.isNew = true;
                    modals.value.edit_category.show = true;
                }

                function addItem() {
                    const api = tree.value.api;

                    const parent = modals.value.edit_category.parent || "0";
                    let parentIndex = -1;
                    let order = 1;
                    let addFunc;
                    const inside = Boolean(parent && parent !== "0");

                    if (inside) {
                        parentIndex = api.findIndexById(parent);

                        if (parentIndex === null || parentIndex < 0) {
                            const parentNode = api.findNodeById(parent);

                            if (!parentNode) {
                                throw new Error("Can't add node!");
                            }

                            if (parentNode.preventChildren) {
                                return;
                            }

                            if (!parentNode.isLeaf && parentNode.expanded) {
                                const parentOfParent = api.findParentByNodeId(parent);

                                if (!parentOfParent || !parentOfParent.container) {
                                    throw new Error("Can't add node!");
                                }

                                const parentLastChildIndex = api.findLastChildIndex(parentOfParent.container, parentOfParent.index);
                                const insertIndex = parentLastChildIndex < 0 ? parentOfParent.index + 1 : parentLastChildIndex + 1;

                                order = parentLastChildIndex < 0 ? 1 : parentOfParent.container[parentLastChildIndex].order + 1;

                                const level = parentOfParent.container[parentOfParent.index].level + 1;

                                addFunc = (data) => {
                                    parentOfParent.container.splice(insertIndex, 0, { ...data, isLeaf: true, expanded: true, level });
                                };
                            }
                            else {
                                parentNode.isLeaf = false;
                                parentNode.expanded = false;

                                if (!parentNode.children) {
                                    parentNode.children = [];
                                }

                                order = parentNode.children.length + 1;

                                const level = parentNode.level + 1;

                                addFunc = (data) => {
                                    parentNode.children.push({ ...data, isLeaf: true, expanded: true, level });
                                };
                            }
                        }
                        else {
                            const parentNode = api.getNode(parentIndex);

                            if (parentNode.preventChildren) {
                                return;
                            }

                            if (!parentNode.isLeaf) {
                                const children = api.getDirectChildren(parentIndex);
                                order = children.length + 1;
                            }
                        }
                    }
                    else {
                        order = api.getLastRelativeIndex() + 2;
                    }

                    if (!addFunc) {
                        addFunc = (data) => { api.add(inside ? parentIndex : api.getLastIndex() + 1, data, inside) };
                    }

                    const data = {
                        name: modals.value.edit_category.name,
                        parent: parent,
                        active: 1,
                        order: order,
                    };

                    const form_data = new FormData();

                    Object.keys(data).forEach(function (key) {
                        form_data.append(key, data[key]);
                    });

                    send_xhr_post(
                        base_url.value + '/catalog/' + method_name.add + '/' + controller_name + set_id,
                        form_data,
                        function (xhr) {
                            data["id"] = xhr.response;
                            data["title"] = data["name"];

                            addFunc(data);

                            pp_category_options_changed = true;
                        }
                    );

                    modals.value.edit_category.show = false;
                }

                function editHandler(e) {
                    const item = getNodeFromClickEvent(e);

                    if (!item) {
                        return;
                    }

                    item.image = item.image || '';
                    item.description = item.description || '';

                    modals.value.edit_category.name = item.name;
                    modals.value.edit_category.initialParent = `${item.parent}`;
                    modals.value.edit_category.parent = `${item.parent}`;
                    modals.value.edit_category.description = item.description;
                    modals.value.edit_category.id = item.id;
                    modals.value.edit_category.item = item;
                    modals.value.edit_category.image = item.image;
                    modals.value.edit_category.isNew = false;
                    modals.value.edit_category.show = true;
                }

                function updateItem() {
                    const form_data = new FormData();

                    let order = 1;

                    const data = {
                        id: modals.value.edit_category.id,
                        parent: modals.value.edit_category.parent,
                        name: modals.value.edit_category.name,
                        description: modals.value.edit_category.description,
                        image: modals.value.edit_category.image,
                    };

                    const initialParent = modals.value.edit_category.initialParent || "0";
                    const parent = data.parent || "0";

                    const api = tree.value.api;
                    const index = api.findIndexById(data.id);

                    api.updateNode(index, {
                        image: data.image,
                        name: data.name,
                        title: data.name,
                        description: data.description,
                        parent: parent,
                    });

                    if (initialParent !== parent) {
                        const oldIndex = index;
                        const newParentIndex = api.findIndexById(parent);

                        if (newParentIndex === null || newParentIndex < 0) {
                            const parentNode = api.findNodeById(parent);

                            if (!parentNode) {
                                throw new Error("Can't move node!");
                            }

                            let node = api.getNode(index);

                            if (!node.isLeaf && node.expanded) {
                                api.collapse(index);
                                node = api.getNode(index);
                            }

                            const levelOffset = parentNode.level + 1 - node.level;

                            const children = [];

                            api.walkNodeTree(index, (x) => {
                                if (`${x.id}` === `${node.id}`) {
                                    return;
                                }

                                children.push(x);
                            });

                            for (const child of children) {
                                child.level += levelOffset;
                            }

                            api.remove(index);

                            node.level += levelOffset;

                            if (!parentNode.isLeaf && parentNode.expanded) {
                                const parentOfParent = api.findParentByNodeId(parent);

                                if (!parentOfParent || !parentOfParent.container) {
                                    throw new Error("Can't move node!");
                                }

                                const parentLastChildIndex = api.findLastChildIndex(parentOfParent.container, parentOfParent.index);
                                const insertIndex = parentLastChildIndex < 0 ? parentOfParent.index + 1 : parentLastChildIndex + 1;

                                let order = parentLastChildIndex < 0 ? 1 : parentOfParent.container[parentLastChildIndex].order + 1;
                                node.order = order || 1;
                                order = node.order;

                                parentOfParent.container.splice(insertIndex, 0, node);
                            }
                            else {
                                parentNode.isLeaf = false;
                                parentNode.expanded = false;

                                if (!parentNode.children) {
                                    parentNode.children = [];
                                }

                                node.order = parentNode.children.length + 1;
                                order = node.order;

                                parentNode.children.push(node);
                            }
                        }
                        else {
                            const moveResult = api.move(oldIndex, newParentIndex, true);

                            if (moveResult) {
                                order = moveResult.newRIndex + 1;
                                api.updateNode(moveResult.newIndex, { order });
                            }
                        }

                        api.updateTree();
                    }

                    data["order"] = order;

                    Object.keys(data).forEach(function (key) {
                        form_data.append(key, data[key]);
                    });

                    send_xhr_post(
                        base_url.value + '/catalog/' + method_name.update + '/' + controller_name + set_id,
                        form_data,
                        function (xhr) {
                        }
                    );

                    pp_category_options_changed = true;
                    modals.value.edit_category.show = false;
                }

                function changeActiveHandler(e) {
                    const item = getNodeFromClickEvent(e);

                    if (!item) {
                        return;
                    }

                    const index = tree.value.api.findIndexById(item.id);
                    const active = item.active == 0 ? 1 : 0;

                    const children = tree.value.api.getDirectChildren(index);

                    item.active = active;

                    for (const child of children) {
                        child.active = active;
                    }

                    send_xhr_get(base_url.value + '/catalog/' + method_name.active + '/' + controller_name + '/' + item.id + '/' + active);
                }

                function show_swal_n(e) {
                    const item = getNodeFromClickEvent(e);
                    if (!item || !item.isLeaf) {
                        return;
                    }

                    Swal.fire({
                        title: lang['are_u_sure'],
                        text: lang['delete_confirm_message'],
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: lang['yes'],
                        customClass: {
                          confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                          cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                        },
                        buttonsStyling: false
                      }).then(function (result) {
                        if (result.value) {
                          const form_data = new FormData();
                            form_data.append('id', item.id);
                            send_xhr_post(
                                base_url.value + '/catalog/' + method_name.delete + '/' + controller_name,
                                form_data,
                                function (xhr) {
                                    tree.value.api.remove(tree.value.api.findIndexById(item.id));
                                    pp_category_options_changed = true;
                                }
                            )
                        }
                      });

                }

                function show_swal_clear(e) {
                    const item = getNodeFromClickEvent(e);

                    if (!item) {
                        return;
                    }
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
                    }).then(() => {
                        send_xhr_get(
                            base_url.value + '/materials/remove_items_by_cat_common/' + item.id + '/drop_true',
                            function (xhr) {
                                Swal.fire({
                                    title: 'Очищена!',
                                    text: 'Категория очищена',
                                    customClass: {
                                      confirmButton: 'btn btn-primary waves-effect waves-light'
                                    },
                                    buttonsStyling: false,
                                    icon : 'success'
                                  });
                            })
                    });
                }

                function updatedEventHandler(event) {
                    if (!tree.value) {
                        return;
                    }

                    if (event.type === "move") {
                        event.node.parent = event.newParent ? event.newParent.id : "0";

                        const data = tree.value.api.getFlatTree(true);
                        pp_category_options_changed = true;

                        const form_data = new FormData();
                        form_data.append('data', JSON.stringify(data));

                        send_xhr_post(
                            base_url.value + '/catalog/' + method_name.order + '/' + controller_name + set_id,
                            form_data,
                            function (xhr) {
                                console.log(xhr);
                            }
                        );

                        return;
                    }
                }

                function removeAll() {
                    //show modal and when remove all

                    if (false) {
                        tree.value.api.removeAll();
                        pp_category_options_changed = true;
                    }
                }

                function expandAll() {
                    tree.value.api.expandAll();
                }

                function collapseAll() {
                    tree.value.api.collapseAll();
                }

                function getIdFromClickEvent(e) {
                    if (!e || !e.target) {
                        return;
                    }

                    return tree.value.api.getDataIdAttribute(e.target);
                }

                function getNodeFromClickEvent(e) {
                    const id = getIdFromClickEvent(e);

                    if (!id) {
                        return null;
                    }

                    return tree.value.api.getNode(tree.value.api.findIndexById(id));
                }

                function langFunc(str) {
                    return lang[str]
                }

                function check_controller() {
                    return true;
                    // if(this.controller === 'modules' || this.controller === 'module_sets') return false;
                    // return true
                }

                function sel_file(file) {
                    this.modals.edit_category.image = file.true_path.substr(1);
                    $('#filemanager').modal('toggle')
                }

                return {
                    data_ready,
                    is_common: is_common_opt,
                    controller,
                    modals,

                    sel_file,
                    addItem,
                    updateItem,
                    show_swal_n,
                    show_swal_clear,
                    check_controller,
                    lang: langFunc,

                    nodes,
                    tree,
                    maxHeight,
                    pp_category_options,
                    updatedEventHandler,
                    addHandler,
                    editHandler,
                    changeActiveHandler,
                    removeAll,
                    expandAll,
                    collapseAll,
                }
            }
        });

        app.component('SlickList', window.VueSlicksort.SlickList);
        app.component('SlickItem', window.VueSlicksort.SlickItem);
        app.component('DragHandle', window.VueSlicksort.DragHandle);
        app.component("pp_category", window.PpCategory);
        app.component("PpTree", window.PpTree);

        app.mount('#sub_form');
    }

})(window);
