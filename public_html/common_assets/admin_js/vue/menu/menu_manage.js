var MenuManage = function(){
    
    var menus = [];
    let base_url = null;
    let update_flag = false;
    var initAttachEvent = () =>{
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            form.addEventListener('submit', handleFormSubmit);
        });
        document.getElementById('form_update').addEventListener('click', function(){
            update_flag = true;
        })

        document.getElementById('form_delete').addEventListener('click', function(e){
            e.preventDefault();
            deleteMenu( document.getElementById('node_id').value);
        })
    }

    var handleFormSubmit = (event) => {
        if (!event.target.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            event.stopPropagation();
            if(update_flag === false){
                saveMenu(event);
            }else{
                updateMenu(event);
            }
        }
        event.target.classList.add('was-validated');
    }

    var initCommonValue = () => {
        base_url = document.getElementById('ajax_base_url').value;
        Promise.all([
            promise_request(base_url + '/menu_manage/get_all_menus'),
        ]).then(function (results) {
            menus = [...results[0].menus];
            convertDataFormat(results[0].menus);
        }).catch(function () {
            // console.log('Error');
        });
    }

    var convertDataFormat = (menus) => {
        var childNodes = makeMenuStructure(menus, 0);
        const data = [
            {
              text: 'All',
              state: {
                opened: true
              },
              id : 0,
              order : 0,
              children: childNodes.length === 0 ? [] : childNodes
            }
          ]
        initTreeView(data);
    }

    function makeMenuStructure(flatArray, parentId) {
        const nodes = flatArray.filter(node => node.parent_id == parentId).sort((a, b) => a.order - b.order);
        return nodes.map(node => ({
            text: node.title,
            id: node.id,
            page_url : node.page_url,
            icon_name : node.icon_name,
            children: makeMenuStructure(flatArray, node.id),
            state: {
                opened : document.getElementById('node_id'). value == node.id ? true : false
            }
        }));
    }

    var saveMenu = (event) => {
        let order = getRanking(document.getElementById('node_id').value) + 1;
        const formData = new FormData(event.target);
        formData.append('order', order);
        send_xhr_post(
            base_url + '/menu_manage/insert_menu', formData, function(xhr){
                menus.push(JSON.parse(xhr.response).menu);
                convertDataFormat(menus);
                showToastr('success', 'Success');
            }
        )
    }

    var getRanking = (parent_id) => {
        let nodes = menus.filter(menu => menu.parent_id == parent_id);
        return nodes.length;
    }

    var updateMenu = (event) => {
        const formData = new FormData(event.target);
        send_xhr_post(
            base_url + '/menu_manage/update_menu', formData, function(xhr){
                const updatedMenu = JSON.parse(xhr.response).menu;
                const index = menus.findIndex(menu => menu.id === updatedMenu.id);
                if (index !== -1) {
                    menus[index] = updatedMenu;
                    update_flag = false;
                    convertDataFormat(menus);
                    showToastr('success', 'Success');
                }
            }
        )
    }

    var deleteMenu = (id) => {
        const formData = new FormData();
        formData.append('id', id);
        send_xhr_post(
            base_url + '/menu_manage/delete_menu', formData, function(xhr){
                const id = JSON.parse(xhr.response).id;
                const index = menus.findIndex(menu => menu.id == id);
                if (index !== -1) {
                    menus.splice(index, 1)
                    convertDataFormat(menus);
                    $('#node_id').val(0);
                    $('#title').val('');
                    $('#page_url').val('');
                    $('#icon_name').val('');
                    showToastr('success', 'Success');
                    }
            }
        )
    }

    var orderUpdate = (node_id, parent_id, order) => {
        const formData = new FormData();
        formData.append('node_id', node_id);
        formData.append('parent_id', parent_id);
        formData.append('order', order);
        send_xhr_post(
            base_url + '/menu_manage/update_menu', formData, function(xhr){
                const updatedMenu = JSON.parse(xhr.response).menu;
                const index = menus.findIndex(menu => menu.id === updatedMenu.id);
                if (index !== -1) {
                    menus[index] = updatedMenu;
                    update_flag = false;
                    convertDataFormat(menus);
                    showToastr('success', 'Success');
                }
            }
        )
    }

    var initTreeView = (data) => {
        if ($('#jstree').jstree(true)) {
            $('#jstree').jstree("destroy"); // Destroy the existing instance
        }
        var theme = $('html').hasClass('light-style') ? 'default' : 'default-dark';
        var ajaxTree = $('#jstree');
        if (ajaxTree.length) {
            ajaxTree.jstree({
              core: {
                themes: {
                  name: theme
                },
                check_callback: true,
                data: data
              },
              plugins: ['types', 'dnd'],
              types: {
                default: {
                  icon: 'ti ti-folder'
                },
                html: {
                  icon: 'ti ti-brand-html5 text-danger'
                },
                css: {
                  icon: 'ti ti-brand-css3 text-info'
                },
                img: {
                  icon: 'ti ti-photo text-success'
                },
                js: {
                  icon: 'ti ti-brand-javascript text-warning'
                }
              }
            });
            ajaxTree.on('select_node.jstree', function(e, data){
                $('#node_id').val(data.node.original.id);
                $('#title').val(data.node.original.text);
                $('#page_url').val(data.node.original.page_url);
                $('#icon_name').val(data.node.original.icon_name);
                $('#selected_id').val(data.selected[0]);
            });
            ajaxTree.on('move_node.jstree', function(e, data) {
                let brothers = menus.filter( menu => ( menu.id == data.parent))
                orderUpdate(data.node.original.id, data.parent, brothers.length + 1);
            });
            ajaxTree.on('ready.jstree', function() {
                ajaxTree.jstree('select_node', document.getElementById('selected_id').value);
            });
        }
    }

    return {
        init : function() {
            initCommonValue();
            initAttachEvent();
        }
    }
    
}();

$(document).ready(function(){
    MenuManage.init()
});