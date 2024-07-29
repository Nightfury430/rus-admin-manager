document.addEventListener('Glob_ready', function () {


    let clipboard = new Clipboard('.copy');

    clipboard.on('success', function (e) {
        toastr.success('Код конструткора скопирован')
    });

    app = new Vue({
        el: '#sub_form',
        data: {
            settings: {
                order_mail: '',
                site_url: '',
                address_line: '',
                logo: '',
                logo_top: '',
                vk_appid: '',
                interface: ''
            },
            options_ready: false,
            errors: [],
            show_success_message: false
        },

        beforeMount: async function () {
            let data = await promise_request(glob.base_url + '/settings/get_settings');
            this.$options.ini_data = glob.ini

            if(data.data) data = JSON.parse(data.data);
            console.log(data)
            Vue.set(this, 'settings', data);
            this.options_ready = true;
        },
        computed: {
            can_logo_top: function () {
                let res = false;
                if(glob.ini && glob.ini && glob.ini.tariff){
                    res = glob.ini.tariff.add_logo_full == 1 || glob.ini.tariff.add_logo_part == 1
                }
                return res;
            },
            can_vk: function () {
                let res = false;
                if(glob.ini && glob.ini && glob.ini.tariff){
                    res = glob.ini.tariff.add_vk == 1
                }
                return res;
            }


        },
        methods: {

            correct_url: function (path) {
                if (path == '') return '/common_assets/images/placeholders/125x125.png'
                return glob.acc_url + path + '?' + new Date().getTime();
            },

            sel_file: function (file) {
                this.settings.logo = file.true_path.substr(1);
                $('#filemanager').modal('toggle')
            },

            get_data: function () {
                return copy_object(this.settings)
            },

            check_form: function (e) {
                e.preventDefault();

                let form_data = new FormData();
                form_data.append('data', JSON.stringify(this.get_data()));
                send_xhr_post(
                    e.target.dataset.action,
                    form_data,
                    function (xhr) {
                        toastr.success(glob.lang.success)
                    }
                )

                return false;

            }
        }
    })

});


