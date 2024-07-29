document.addEventListener('DOMContentLoaded', function () {


    app = new Vue({
        el: '#sub_form',
        data: {
            accessories: [],
            categories:[],
        },
        beforeMount(){

            let scope = this;

            axios({
                method: 'get',
                url: document.getElementById('ajax_base_url').value + '/accessories/get_accessories_data'
            }).then(function (msg) {
                console.log(msg.data);


                scope.accessories = msg.data;

                let cats = {};

                let cats_count = 0;

                for (let i = 0; i < scope.accessories.length; i++){

                    if( !(scope.accessories[i].category in cats) ){
                        cats[cats_count] = scope.accessories[i].category;
                        cats_count++;
                    }

                }

                scope.categories = cats;





            }).catch(function () {
                alert('Unknown error')
            });



        },
        mounted(){
        },
        methods: {
            add_row: function () {
              let obj = {
                  id:null,
                  code:'',
                  name:'',
                  category:'',
                  subcategory:'',
                  price:'',
                  images:'',
                  module_width:'',
                  module_height:'',
                  module_depth:'',
                  description:'',
                  tags:'',

              }

              this.accessories.push(obj)

            },
            remove_row: function (index) {
                this.accessories.splice(index,1);
            },
            shop_mode_change(e){
                let scope = this;
                console.log(this.settings.shop_mode)


                if(!scope.kitchen_models.length){
                    setTimeout(function () {
                        toastr.warning(document.getElementById('dealer_error_no_model_message').innerHTML + ' "' + document.getElementById('kitchen_models_message').innerHTML + '"');
                        scope.settings.shop_mode = 0;
                    },10)

                }
            },
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

                        console.log(msg.data)

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