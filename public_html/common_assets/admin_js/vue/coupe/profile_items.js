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

let per_page = ["10", "20", "50", "100"]

document.addEventListener('DOMContentLoaded', function () {


    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;
    lang = JSON.parse(document.getElementById('lang_json').value);
    // controller_name = document.getElementById('footer_controller_name').value;
    controller_name = 'coupe_accessories';

    Promise.all([
        promise_request(base_url + '/catalog/categories_get/' + controller_name),
        promise_request(base_url + '/catalog/items_get_pagination/' + controller_name),
    ]).then(function (results) {
        categories_data = results[0]
        items_data = results[1]['items'];
        items_count = results[1]['count'];

        categories_data.unshift({
            name: lang['all'],
            id: '0',
            parent: '0'
        })

        categories_hash = get_hash(categories_data);

        categories_ordered = flatten(create_tree(categories_data));

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
            errors: [],
            items:[],
            items_count: 0,
            page: 1,
            filter:{
                category: "0",
                per_page: "20"
            }
        },
        created: function () {
            this.items_count = items_count;

            if(controller_name == 'modules'){
                for (let i = 0; i < items_data.length; i++){
                    items_data[i].params = JSON.parse(items_data[i].params).params
                }
            }



            Vue.set(this,'items', items_data)
        },
        computed:{
            pages_count: function () {
                return Math.ceil(this.items_count / this.filter.per_page);
            }
        },
        mounted: function () {

        },
        methods: {
            get_map: function(item){
                if(item.map) return 'url(' + this.correct_url(item.map) + ')'
                return item.color;
            },
            correct_url: function(path){
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return acc_url + path;
                }
            },
            filter_category: function(val){
                console.log(val)
                let scope = this;
                promise_request(base_url + '/catalog/items_get_pagination/' + controller_name + '/' + val + '/' + scope.filter.per_page)
                    .then(function (result) {
                        scope.items_count = result['count'];

                        if(controller_name == 'modules'){
                            for (let i = 0; i < result['items'].length; i++){
                                result['items'][i].params = JSON.parse(result['items'][i].params).params
                            }
                        }

                        Vue.set(scope,'items', result['items'])
                        scope.$emit('reset_page', 1);
                    }).catch(function (err) {
                })

            },
            filter_per_page: function(val){
                console.log(val)
                let scope = this;
                promise_request(base_url + '/catalog/items_get_pagination/' + controller_name + '/' + scope.filter.category + '/' + val)
                    .then(function (result) {
                        scope.items_count = result['count'];

                        if(controller_name == 'modules'){
                            for (let i = 0; i < result['items'].length; i++){
                                result['items'][i].params = JSON.parse(result['items'][i].params).params
                            }
                        }

                        Vue.set(scope,'items', result['items'])
                        scope.$emit('reset_page', 1);
                    }).catch(function (err) {
                })
            },
            change_page: function(val){
                this.page = val;
                let scope = this;
                promise_request(base_url + '/catalog/items_get_pagination/' + controller_name + '/' + scope.filter.category + '/' + scope.filter.per_page + '/' + ((val -1 )*scope.filter.per_page))
                    .then(function (result) {
                        scope.items_count = result['count'];

                        if(controller_name == 'modules'){
                            for (let i = 0; i < result['items'].length; i++){
                                result['items'][i].params = JSON.parse(result['items'][i].params).params
                            }
                        }

                        Vue.set(scope,'items', result['items'])
                    }).catch(function (err) {
                })

                this.$emit('change_page_sync', val);

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


            get_eye_class: function (item) {
                return item.active ? ['fa-eye', 'btn-primary'] : ['fa-solid fa-eye-low-vision', 'btn-default']
            }

        }
    });

}





