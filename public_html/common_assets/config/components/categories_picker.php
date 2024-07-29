<script>
    Vue.component('pp_categories', {
        template: `
        <div>
            <v-select v-if="options_ready"
                                :clearable="false"
                                :value="categories"
                                :multiple="true"
                                label="name"
                                :options="$options.cat_ordered"
                                :reduce="category => category.id"
                                v-model="categories"
                                :key="categories"
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
        // template: '#pp_items_picker_template',
        components: {
            'v-select': VueSelect.VueSelect
        },
        props: {
            controller: {
                type: String,
                default: 'materials'
            },
            top_only:{
                type: [Boolean, Number],
                default: 0
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
                type: Array,
                default: function () {
                    return [];
                }
            }
        },




        data: function () {
            return {
                categories: [],
                options_ready: false,
            };
        },

        beforeCreate() {

        },
        created: async function () {
            await this.get_data();
            console.log(this.categories)
            console.log(this.selected)
            Vue.set(this, 'categories', this.selected)
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

                this.$options.cat_hash = get_hash(data);
                this.$options.cat_ordered = flatten(create_tree(data));

                if(this.top_only){
                    for (let i =  this.$options.cat_ordered.length; i--;){
                        if(this.$options.cat_ordered[i].parent != 0) this.$options.cat_ordered.splice(i, 1)
                    }
                }

            },
            e_update: function () {
                console.log(this.categories)
                this.$emit('e_update', copy_object(this.categories));
            }
        }
    })
</script>