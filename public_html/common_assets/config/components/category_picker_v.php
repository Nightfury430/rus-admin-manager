<script>

((window) => {
    const { ref, watch, onMounted, nextTick, shallowRef } = window.Vue;

    window.PpCategory = {
        template: `
            <div>
                <v-select v-if="options_ready"
                    v-model="l_value"
                    :modelValue="l_value"
                    :options="cat_ordered"
                    :clearable="false"
                    label="name"
                    :reduce="category => category.id"
                    @update:modelValue="e_update"
                >
                    <template v-slot:selected-option="option">
                        <span v-for="(name,index) in option.path">{{name}}<span v-if="index < option.path.length - 1"> / &nbsp;</span></span>
                    </template>
                    <template v-slot:option="option">
                        <span v-for="(name,index) in option.path">{{name}}<span v-if="index < option.path.length - 1"> / &nbsp;</span></span>
                    </template>
                    <template v-slot:no-options>
                        Ничего не найдено.
                    </template>
                </v-select>
            </div>
        `,

        components: {
            "v-select": window["vue-select"],
        },

        emits: ["e_update"],

        props: {
            controller: {
                type: String,
                default: "materials",
            },

            // lang: {
            //     type: Object,
            //     default: function () {

            //         // if(glob.lang !== undefined) return glob.lang

            //         return {
            //             ok: 'Ок',
            //             add: 'Добавить',
            //             remove: 'Убрать',
            //             choose: 'Выбрать',
            //             category: 'Категория',
            //             search: 'Поиск',
            //             pick: 'Выбрать',
            //             page_items_count: 'Количество на странице',
            //             all: 'Все',
            //             choose_file: 'Выберите файл',
            //             download_model: 'Скачать модель',
            //         }
            //     }
            // },

            // unselect: {
            //     type: [Boolean, Number],
            //     default: false,
            // },

            selected: {
                type: Array,
                default: function () {
                    return [];
                },
            },

            options: {
                type: Array,
            },
        },

        setup(props, { emit }) {
            const l_value = ref('0');
            const options_ready = ref(false);
            const cat_hash = shallowRef([]);
            const cat_ordered = shallowRef([]);

            watch(() => props.options, (options) => {
                if (!options_ready.value) {
                    return;
                }

                set_data(options || []);
            });

            watch(() => props.selected, (newValue) => {
                setSelected(newValue);
            });

            onMounted(() => {
                nextTick(() => {
                    init();
                });
            });

            function setSelected(value) {
                l_value.value = cat_hash.value[value] ? value : '0';
                e_update();
            }

            async function init() {
                // await get_data();

                // setSelected(props.selected);

                set_data(props.options || []);

                options_ready.value = true;
            }

            async function get_data() {
                const data = await promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + props.controller);

                data.unshift({
                    id: '0',
                    name: glob.lang['no'],
                    parent: '0',
                });

                cat_hash.value = get_hash(data);
                cat_ordered.value = flatten(create_tree(data));
            }

            function set_data(data) {
                const filtered = data.filter((option) => {
                    return !(option.preventChildren || option.disabled);
                });

                const options = [{
                    id: '0',
                    name: glob.lang['no'],
                    parent: '0',
                }, ...filtered];

                cat_hash.value = get_hash(options);
                cat_ordered.value = flatten(create_tree(options));
            }

            function e_update() {
                emit("e_update", copy_object(l_value.value));
            }

            return {
                l_value,
                options_ready,
                cat_hash,
                cat_ordered,
                get_data,
                e_update,
            }
        }
    };

})(window);

</script>