document.addEventListener('DOMContentLoaded', function () {

    $(document).ready(function (){

        let clipboard = new Clipboard('.copy');

        clipboard.on('success', function(e) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "progressBar": true,
                "preventDuplicates": false,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "400",
                "hideDuration": "500",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            toastr.success('Код конструткора скопирован')
        });

    });


    app = new Vue({
        el: '#sub_form',
        data: {
            errors: [],
            order_mail: document.getElementById('order_mail').dataset.value,
            address_line: document.getElementById('address_line').dataset.value,
            logo_url: document.getElementById('logo_preview').dataset.value !== '' ? document.getElementById('logo_preview').dataset.value : null,
            vk_appid: document.getElementById('vk_appid').dataset.value,
            site_url: document.getElementById('site_url').dataset.value,
            do_delete_logo: 0,
            show_success_message:false
        },
        methods: {
            logo_preview(e) {
                const file = e.target.files[0];
                this.logo_url = URL.createObjectURL(file);
                this.do_delete_logo = 0;
            },
            delete_logo: function(){
                this.logo_url = null;
                app.$refs.logo.value = '';
                this.do_delete_logo = 1;
            },
            show_success: function () {

                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "progressBar": true,
                    "preventDuplicates": false,
                    "positionClass": "toast-top-right",
                    "onclick": null,
                    "showDuration": "400",
                    "hideDuration": "500",
                    "timeOut": "2000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }

                toastr.success('Настройки успешно сохранены')

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

                if (!this.order_mail) {
                    this.errors.push('Введите email для заявок');
                }

                if (!this.site_url) {
                    this.errors.push('Введите сайт установки');
                }

                if(this.$refs.logo.files.length){
                    if(this.$refs.logo.files[0].size > 100 * 1024) this.errors.push('Размер файла логотипа не должен превышать 100Кб');
                }




                if (!this.errors.length) {
                    let formData = new FormData();
                    formData.append('logo', this.$refs.logo.files[0]);
                    formData.append('order_mail', this.order_mail);
                    formData.append('address_line', this.address_line);
                    formData.append('delete_logo', this.do_delete_logo);
                    formData.append('vk_appid', this.vk_appid);
                    formData.append('site_url', this.site_url);

                    axios.post(this.$el.dataset.action,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function (msg) {

                        if(msg.data.error) {
                            for (let i = 0; i < msg.data.error.length; i++){
                                scope.errors.push(msg.data.error[i])
                            }

                            toastr.error('Ошибка')

                        }

                        if(msg.data.success){
                            scope.show_success();
                        }

                    }).catch(function () {
                        alert('Unknown error')
                    });
                } else{
                    toastr.error('Заполните все обязательные поля')
                }

                return false;

            }
        }
    })

});


