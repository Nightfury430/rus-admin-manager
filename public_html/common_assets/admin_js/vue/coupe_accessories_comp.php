<coupe_accessories_component></coupe_accessories_component>

<script>
const cpacctr = `
    <div v-show="modals.add.show" class="bpl_modal_wrapper">
        <div class="bpl_modal_background" :class="{shown: modals.add.show}"></div>
        <div class="bpl_modal_content">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="modals.add.show = false" type="button" class="close"><span>&times;</span></button>
                        <h4 class="modal-title">{{lang(modals.add.header)}}</h4>

                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                            <div class="col-sm-10"><input v-model="item.name" type="text" class="form-control"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('code')}}</label>
                            <div class="col-sm-10"><input v-model="item.code" type="text" class="form-control"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('category')}}</label>
                            <div class="col-sm-10">
                                <v-select
                                        :clearable="false"
                                        :value="item.category"
                                        label="name"
                                        :options="categories_ordered"
                                        :reduce="category => category.id"
                                        v-model="item.category"
                                        :key="item.category"
                                >

                                    <template #selected-option="option">
                                        <span style="pointer-events: none" :title="option.name" :class="{'font-weight-bold': option.parent == 0}">
                                            <span v-if="option.parent != 0" class="font-weight-bold">{{categories_hash[option.parent].name}} / </span>
                                                   <span v-if="option.id != 0">{{ option.name }}</span>
                                                <span v-else>{{ lang('no') }}</span>
                                        </span>
                                    </template>

                                    <template v-slot:option="option">
                                        <span :title="option.name" :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                            <span v-if="option.parent != 0">{{categories_hash[option.parent].name}} / </span>
                                                <span v-if="option.id != 0">{{ option.name }}</span>
                                                <span v-else>{{ lang('no') }}</span>
                                        </span>
                                    </template>
                                </v-select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('price')}}</label>
                            <div class="col-sm-10"><input v-model="item.price" min="0" step="0.01" type="number" class="form-control"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('description')}}</label>
                            <div class="col-sm-10"><textarea v-model="item.description" class="form-control"></textarea></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('images')}}</label>
                            <div class="col-sm-10"><textarea v-model="item.images" class="form-control"></textarea></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button @click="modals.add.show = false" type="button" class="btn btn-white">{{lang('cancel')}}</button>
                        <button @click="submit_data" type="button" class="btn btn-primary">{{lang(modals.add.mode)}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
`;



Vue.component("coupe_accessories_component",{
    props: [],
    components: {
        'v-select': VueSelect.VueSelect
    },
    data: function () {
        return {
            modals: {
                add:{
                    mode: 'add',
                    header: 'add',
                    show: false
                }
            },
            item:{
                id: 0,
                name: '',
                code: '',
                category: '0',
                price: 0,
                images: '',
                description: '',
                tags:'',
                order: 0
            },

        }
    },
    created: function(){
        let scope = this;

        this.$root.$on('add_item', function () {
            Vue.set(scope, 'item', {
                id: 0,
                name: '',
                code: '',
                category: '0',
                price: 0,
                images: '',
                description: '',
                tags:'',
                order: 0
            })
            scope.modals.add.mode = 'add'
            scope.modals.add.header = 'add'
            scope.modals.add.show = 1;
        })

        this.$root.$on('edit_item', function (item) {
            Vue.set(scope, 'item', item)
            scope.modals.add.mode = 'save'
            scope.modals.add.header = 'edit'
            scope.modals.add.show = 1;
        })


    },
    mounted: function(){

    },
    computed:{

    },
    methods: {
        lang: function(key){
            if(lang) return lang[key]
        },

        submit_data: function () {
            let scope = this;
            let data = copy_object(this.item);

            if(data.id == 0) delete data.id;

            let formData = new FormData();
            formData.append('data', JSON.stringify(data));



            send_xhr_post(
                base_url + '/catalog/add_item_ajax/' + controller_name,
                formData,
                function (result) {
                    scope.$root.change_page(1);
                    scope.modals.add.show = false;
                }
            )

        }
    },
    template: cpacctr
});
</script>
