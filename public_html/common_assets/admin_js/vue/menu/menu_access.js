var MenuAccess = function(){

    var menus = [];
    let base_url = null;
    var ajaxTree = null;
    var selectedUserId = '';
    var initAttachEvent = () => {
        $('#user_menu_access_control_save').click(() => {
            if(selectedUserId !== ''){
                var checkedNodes = ajaxTree.jstree('get_checked', true);
                const selectedMenus = checkedNodes.map((node) => {
                    return { user_id : selectedUserId, menu_id : node.original.id * 1 }
                })
                send_xhr_post(
                    base_url + '/menu_access/save_menu_access', JSON.stringify({
                        user_id : selectedUserId,
                        new_select_menus : selectedMenus
                    }), function(xhr){
                        showToastr('success', 'Success');
                    }
                )
            } else{
                showToastr('warning', 'пожалуйста, выберите пользователя')
            }
            
        })
    };

    var initCommonValue = () => {
        base_url = document.getElementById('ajax_base_url').value;
        Promise.all([
            promise_request(base_url + '/menu_manage/get_all_menus'),
        ]).then(function (results) {
            menus = [...results[0].menus];
            convertDataFormat(results[0].menus);
        }).catch(function () {
            console.log('Error');
        });
    }

    var convertDataFormat = (menus, selectedMenus = []) => {
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
          
        initTreeView(data, selectedMenus);
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
                opened : true,
            }
        }));
    }

    var initTreeView = (data, selectedMenus) => {
        if ($('#jstree').jstree(true)) {
            $('#jstree').jstree("destroy"); // Destroy the existing instance
        }
        var theme = $('html').hasClass('light-style') ? 'default' : 'default-dark';
        ajaxTree = $('#jstree');
        if (ajaxTree.length) {
            ajaxTree.jstree({
              core: {
                themes: {
                  name: theme
                },
                check_callback: true,
                data: data
              },
              plugins: ['types', 'checkbox'],
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
            ajaxTree.on('loaded.jstree', function(){
                selectedMenus.forEach(function(menus) {
                    $('#jstree').jstree('check_node', menus.menu_id * 1);
                });
            })
        }
    }

    var selectUser = (userId) => {
        selectedUserId = userId;
        send_xhr_post(
            base_url + '/menu_access/get_menu_access', JSON.stringify({
                user_id : userId,
            }), function(xhr){
                convertDataFormat(menus, JSON.parse(xhr.response))
            }
        )
    }
    
    return {
        init : function(){
            initAttachEvent();
            initCommonValue();
        },
        selectUser : function(userId){
            selectUser(userId);
        }
    }
}();

$(document).ready(function(){
    MenuAccess.init();
})