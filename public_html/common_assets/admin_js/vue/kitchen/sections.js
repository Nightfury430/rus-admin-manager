document.addEventListener('DOMContentLoaded', function () {

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
            kitchen_models:[],
            show_success_message:false,
            test: [
                {
                    name: "task 1",
                    children:[
                        {
                            name: 'child1',
                            categories:[]
                        },
                        {
                            name: 'child2',
                            categories:[]
                        },
                        {
                            name: 'child3',
                            categories:[]
                        }
                    ]

                },
                {
                    name: "task 3",
                    children:[]

                },
                {
                    name: "task 5",
                    children:[]

                }
            ],
            items:[],
            categories:[]
        },
        beforeMount(){

            let scope = this;

            axios({
                method: 'get',
                url: document.getElementById('ajax_base_url').value + '/sections/get_data'
            }).then(function (msg) {
                console.log(msg.data)

                if(msg.data.sections != null){
                    scope.items = JSON.parse(msg.data.sections);
                }

                for (let i = 0; i < msg.data.categories.length; i++){
                    if(msg.data.categories[i].parent == 0) scope.categories.push(msg.data.categories[i])
                }


            }).catch(function () {
                alert('Unknown error')
            });



        },
        mounted(){
        },
        computed: {
            dragOptions() {
                return {
                    animation: 500,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            }
        },
        methods: {
            add_parent:function () {
                this.items.push({
                    name: 'Без названия ' + (this.items.length+1),
                    children:[]
                })
            },
            add_child:function (index) {
                this.items[index].children.push({
                    name: 'Без названия ' +  (this.items[index].children.length+1),
                    categories:[]
                })
            },
            show_swal: function (index, type, child_index) {
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {
                    if(type == 'parent'){
                        console.log(scope.items)
                       scope.items.splice(index, 1)
                    }
                    if(type == 'child'){
                        scope.items[index].children.splice(child_index, 1)
                    }
                });
            },
            check_form: function (e) {
                e.preventDefault();

                let scope = this;


                this.errors = [];



                if (!this.errors.length) {
                    let formData = new FormData();

                    formData.append('data', JSON.stringify(this.items));

                    axios({
                        method: 'post',
                        url: scope.$el.action,
                        data: formData,
                        headers: {'Content-Type': 'multipart/form-data'}
                    }).then(function (msg) {

                        console.log(msg)
                        console.log(msg.data)
                        toastr.success(document.getElementById('success_message').innerHTML)

                        // scope.show_success();


                    }).catch(function (err) {
                        console.log(err)
                    });




                } else {

                    for (let i = 0; i < this.errors.length; i++){
                        toastr.error(this.errors[i])
                    }



                }

                return false;

            }
        }
    });

    function power_of_2(n) {
        if (typeof n !== 'number')
            return 'Not a number';

        return n && (n & (n - 1)) === 0;
    }
});