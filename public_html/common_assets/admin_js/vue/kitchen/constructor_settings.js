let app;
let settings_data;
let kitchen_models;
document.addEventListener('Glob_ready', async function () {
    settings_data = await promise_request_post(glob.base_url + '/constructor/get_settings_ajax')
    console.log(settings_data)
    if (settings_data.settings.settings) {
        settings_data.settings = JSON.parse(settings_data.settings.settings)
    }

    if (!settings_data.settings.tabletop_v2) {
        settings_data.settings.tabletop_v2 = 0;
    }

    settings_data.settings.price_enabled = parseInt(settings_data.settings.price_enabled)
    if (!settings_data.settings.sizes_limit || settings_data.settings.sizes_limit == 'null') {
        settings_data.settings.sizes_limit = {
            min_width: 0,
            max_width: 0,
            min_height: 0,
            max_height: 0,
            min_depth: 0,
            max_depth: 0
        }
    } else {
        if(typeof settings_data.settings.sizes_limit !== 'object') {
            settings_data.settings.sizes_limit = JSON.parse( settings_data.settings.sizes_limit)
        }
    }

    if(!settings_data.settings.check_intersection_default) settings_data.settings.check_intersection_default = 0;
    if(!settings_data.settings.price_modificator_furni){
        settings_data.settings.price_modificator_furni = settings_data.settings.price_modificator;
    }
    if(!settings_data.settings.price_modificator_acc){
        settings_data.settings.price_modificator_acc = settings_data.settings.price_modificator;
    }

    if(!settings_data.settings.use_corpus_direct_price) settings_data.settings.use_corpus_direct_price = 0;

    init_vue();
})

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        data: {
            errors: [],
            settings: {},
            kitchen_models: [],
            show_success_message: false
        },
        created() {
            this.settings = settings_data.settings;
            this.kitchen_models = settings_data.kitchen_models;
            let flag = 0;
            for (let i = 0; i < this.kitchen_models.length; i++) {
                if (this.kitchen_models[i].id == this.settings.default_kitchen_model) {
                    flag = 1;
                }
            }
            if (flag == 0) {
                this.settings.default_kitchen_model = '';
            }


        },
        beforeMount() {

        },
        mounted() {
        },
        methods: {
            shop_mode_change(e) {
                let scope = this;
                console.log(this.settings.shop_mode)

                if(this.settings.shop_mode == 0) this.settings.allow_common_mode = 0;


                if (!scope.kitchen_models.length) {
                    setTimeout(function () {
                        toastr.warning(document.getElementById('dealer_error_no_model_message').innerHTML + ' "' + document.getElementById('kitchen_models_message').innerHTML + '"');
                        scope.settings.shop_mode = 0;
                    }, 10)

                }
            },

            show_success: function () {

                toastr.success(document.getElementById('success_message').innerHTML)

            },
            check_form: function (e) {
                e.preventDefault();

                let scope = this;


                this.errors = [];

                if (this.settings.shop_mode === 1) {
                    if (this.settings.default_kitchen_model === '') {
                        this.errors.push('Не указана модель кухни по умолчанию');
                    }
                }


                if (!this.errors.length) {
                    let formData = new FormData();


                    formData.append('settings', JSON.stringify(this.settings));

                    axios({
                        method: 'post',
                        url: scope.$el.action,
                        data: formData,
                        headers: {'Content-Type': 'multipart/form-data'}
                    }).then(function (msg) {

                        console.log(msg)
                        console.log(msg.data)

                        scope.show_success();


                    }).catch(function (err) {
                        console.log(err)
                    });


                } else {

                    for (let i = 0; i < this.errors.length; i++) {
                        toastr.error(this.errors[i])
                    }


                }

                return false;

            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {


});