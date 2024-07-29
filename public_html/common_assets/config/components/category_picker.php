<script>
    Vue.component('pp_category', {
        template: `
        <div>
            <v-select v-if="options_ready"
                                :clearable="false"
                                :value="l_value"
                                label="name"
                                :options="$options.cat_ordered"
                                :reduce="category => category.id"
                                v-model="l_value"
                                :key="l_value"
                                @input="e_update()"
                        >
                            <template v-slot:selected-option="option">
                                <span v-for="(name,index) in option.path">{{name}}<span v-if="index < option.path.length - 1"> / &nbsp;</span></span>
                            </template>
                            <template v-slot:option="option">
                                <span v-for="(name,index) in option.path">{{name}}<span v-if="index < option.path.length - 1"> / &nbsp;</span></span>
                            </template>
            </v-select>
        </div>
        `,
        components: {
            'v-select': VueSelect.VueSelect
        },
        props: {
            controller: {
                type: String,
                default: 'materials'
            },
            lang: {
                type: Object,
                default: function () {

                    // if(glob.lang !== undefined) return glob.lang

                    return {
                        ok: 'Ок',
                        add: 'Добавить',
                        remove: 'Убрать',
                        choose: 'Выбрать',
                        category: 'Категория',
                        search: 'Поиск',
                        pick: 'Выбрать',
                        page_items_count: 'Количество на странице',
                        all: 'Все',
                        choose_file: 'Выберите файл',
                        download_model: 'Скачать модель'
                    }
                }
            },
            unselect:{
                type: Boolean, Number,
                default: false,
            },
            selected: {
                type: String, Number,
                default: '0'
            }
        },
        data: function () {
            return {
                l_value: '0',
                options_ready: false,
            };
        },

        beforeCreate() {

        },
        created: async function () {
            await this.get_data();

            console.log(this.selected)

            if(!this.$options.cat_hash[this.selected]){
                Vue.set(this, 'l_value', '0')
                this.e_update();
            } else {
                Vue.set(this, 'l_value', this.selected)
            }


            this.options_ready = true;
        },
        computed: {

        },
        watch: {

        },
        mounted: async function () {

        },
        methods: {
            get_data: async function(){
                let data =  await promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + this.controller);
                data.unshift({
                    id: '0',
                    name: glob.lang['no'],
                    parent: '0'
                })


                this.$options.cat_hash = get_hash(data);
                this.$options.cat_ordered = flatten(create_tree(data));
            },
            e_update: function () {
                this.$emit('e_update', copy_object(this.l_value));
            }
        }
    })
</script>