var MenuManage = function(){
    
    var menus = [];
    let base_url = null;

    var initAttachEvent = () =>{
        
    }

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

    var convertDataFormat = (menus) => {
        var childNodes = makeMenuStructure(menus, 0);
        const data = [
            {
              text: 'All',
              state: {
                opened: true
              },
              id : 0,
              children: childNodes.length === 0 ? [] : childNodes
            }
          ]
        initTreeView(data);
    }

    function makeMenuStructure(flatArray, parentId) {
        const nodes = flatArray.filter(node => node.parent_id == parentId);
        return nodes.map(node => ({
            text: node.title,
            id: node.id,
            page_url : node.page_url,
            icon_name : node.icon_name,
            children: makeMenuStructure(flatArray, node.id) // Recursively get children
        }));
    }

    var saveMenu = (event) => {
        const formData = new FormData(event.target);
        send_xhr_post(
            base_url + '/menu_manage/insert_menu', formData, function(xhr){
                menus.push(JSON.parse(xhr.response).menu);
                convertDataFormat(menus);
            }
        )
    }

    var initTreeView = (data) => {
        var theme = $('html').hasClass('light-style') ? 'default' : 'default-dark';
        var ajaxTree = $('#jstree');
        if (ajaxTree.length) {
            ajaxTree.jstree({
              core: {
                themes: {
                  name: theme
                },
                data: data
              },
              plugins: ['types', 'state', 'dnd'],
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
                $('#parent_id').val(data.node.original.id);
                $('#title').val(data.node.original.text);
                $('#page_url').val(data.node.original.page_url);
                $('#icon_name').val(data.node.original.icon_name);
            })
          }
    }

    return {
        init : function() {
            initCommonValue();
            initAttachEvent();
        },
        saveMenu : saveMenu
    }
    
}();

$(document).ready(function(){
    MenuManage.init()
});

(function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const bsValidationForms = document.querySelectorAll('.needs-validation');
    // Loop over them and prevent submission
    Array.prototype.slice.call(bsValidationForms).forEach(function (form) {
      form.addEventListener(
        'submit',
        function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          } else {
            event.preventDefault();
            event.stopPropagation();
            MenuManage.saveMenu(event);
          }
          form.classList.add('was-validated');
        },
        false
      );
    });
})();