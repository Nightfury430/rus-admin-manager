<script>

    let template = `<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/mass_materials_form_template.php'?>`

    Vue.component('mass_materials_form', {
        template: template,
        components: {
            'v-select': VueSelect.VueSelect
        },
        props: {

        },
        data: function () {
            return {
                enabled: {
                    roughness: 0,
                    metalness: 0,
                    transparent: 0,
                    opacity: 0,


                    rotation: 0,

                    real_width: 0,
                    real_height: 0,

                    stretch_width: 0,
                    stretch_height: 0,
                    wrapping: 0

                },
                value: {
                    roughness: 0.8,
                    metalness: 0,
                    transparent: 0,
                    opacity: 1,


                    rotation: 'normal',

                    real_width: 0,
                    real_height: 0,

                    stretch_width: 0,
                    stretch_height: 0,
                    wrapping: 'mirror'
                }
            };
        },

        beforeCreate() {

        },
        created: async function () {
            this.options_ready = true;
        },
        computed: {

        },
        watch: {

        },
        mounted: async function () {

        },
        methods: {
            get_data: function(){

                let obj = {
                    params: {},
                    add_params: {}
                }

                Object.keys(this.enabled).forEach((k)=>{

                    if(this.enabled[k] == 0) return

                    if(k == 'roughness' || k == 'metalness' || k == 'transparent' || k == 'opacity'){
                        obj.params[k] = parseFloat(this.value[k])
                    } else {

                        if(k == 'real_width' || k == 'real_height' || k == 'stretch_width' || k == 'stretch_height'){
                            obj.add_params[k] = parseFloat(this.value[k])
                        } else {
                            obj.add_params[k] = this.value[k]
                        }


                    }
                })

                // let obj = {
                //     enabled: copy_object(this.enabled),
                //     value: copy_object(this.value),
                // }
                return obj;
            },
            lang: function(key){
                return glob.lang[key];
            },
            e_update: function () {
                this.$emit('e_update', copy_object(123));
            }
        }
    })
</script>