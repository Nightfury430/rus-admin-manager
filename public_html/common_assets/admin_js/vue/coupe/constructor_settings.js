document.addEventListener('DOMContentLoaded', function () {


    app = new Vue({
        el: '#sub_form',
        data: {
            errors: [],
            settings: {},
            show_success_message:false
        },
        beforeMount(){

            let scope = this;

            axios({
                method: 'get',
                url: document.getElementById('ajax_base_url').value + '/constructor/get_coupe_settings_ajax'
            }).then(function (msg) {
                scope.settings = msg.data.settings;
            }).catch(function () {
                alert('Unknown error')
            });



        },
        mounted(){
        },
        methods: {
            reflection_image_preview(e) {
                let scope = this;
                let file =  this.$refs.reflection_image.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = evt => {
                    let img = new Image();
                    img.onload = () => {
                        if(power_of_2(img.width) === false || power_of_2(img.height) === false){
                            toastr.warning(document.getElementById('must_be_power_of_two_message').innerHTML);
                            this.$refs.reflection_image.value = ''
                        } else {
                            scope.settings.custom_reflection_image = evt.target.result;
                        }

                    };
                    img.src = evt.target.result;
                };

                reader.onerror = evt => {
                    console.error(evt);
                }

            },
            delete_logo: function(){
                // this.logo_url = null;
                // app.$refs.logo.value = '';
                // this.do_delete_logo = 1;
            },
            show_success: function () {

                toastr.success(document.getElementById('success_message').innerHTML)

                // let scope = this;
                // this.show_success_message = true;
                // setTimeout(function () {
                //     scope.show_success_message = false;
                // },1000)
            },
            check_form: function (e) {
                e.preventDefault();

                let scope = this;


                this.errors = [];




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

    function power_of_2(n) {
        if (typeof n !== 'number')
            return 'Not a number';

        return n && (n & (n - 1)) === 0;
    }
});