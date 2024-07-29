document.addEventListener('DOMContentLoaded', function () {


    app = new Vue({
        el: '#sub_form',
        data: {
        },
        beforeMount(){

        },
        mounted(){
        },
        methods: {
            generate_modules_names(e){
                e.preventDefault();



                let scope = this;
                axios({
                    method: 'post',
                    url: e.target.dataset.url,
                    data: {autogen: true},
                    headers: {'Content-Type': 'multipart/form-data'}
                }).then(function (msg) {

                    scope.show_success();


                }).catch(function (err) {
                    console.log(err)
                });
            },
            show_success: function () {

                toastr.success(document.getElementById('success_message').innerHTML)


            },
            check_form: function (e) {
                e.preventDefault();

                let scope = this;


                this.errors = [];

                if(this.settings.shop_mode === 1){
                    if (this.settings.default_kitchen_model === '') {
                        this.errors.push('Не указана модель кухни по умолчанию');
                    }
                }




                if (!this.errors.length) {
                    let formData = new FormData();
                    formData.append('reflection_image', this.$refs.reflection_image.files[0]);
                    formData.append('settings', JSON.stringify(this.settings));

                    axios({
                        method: 'post',
                        url: scope.$el.action,
                        data: formData,
                        headers: {'Content-Type': 'multipart/form-data'}
                    }).then(function (msg) {

                        scope.show_success();


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
    })


});