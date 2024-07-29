<modules_sets_component></modules_sets_component>

<script>
const cpacctr = `
<div>
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
                            <label class="col-sm-2 col-form-label">Фасадная система</label>
                            <div class="col-sm-10">
                                <select class="form-control" v-model="item.facade_set_id">
                                    <option value="0">---------Выберите фасадную систему---------</option>
                                    <option :value="item.id" v-for="item in $options.facades_systems">{{item.name}}</option>
                                </select>
                            </div>
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

    <div v-show="modals.json.show" class="bpl_modal_wrapper">
        <div class="bpl_modal_background" :class="{shown: modals.json.show}"></div>
        <div class="bpl_modal_content">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="modals.json.show = false" type="button" class="close"><span>&times;</span></button>
                        <h4 class="modal-title">Добавить из JSON</h4>

                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">JSON</label>
                            <div class="col-sm-10"><textarea ref="json_value" class="form-control" rows="30"></textarea></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button @click="modals.json.show = false" type="button" class="btn btn-white">{{lang('cancel')}}</button>
                        <button @click="submit_json" type="button" class="btn btn-primary">{{lang(modals.json.mode)}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
`;



Vue.component("modules_sets_component",{
    props: [],
    data: function () {
        return {
            modals: {
                add:{
                    mode: 'add',
                    header: 'add',
                    show: false
                },
                json: {
                    mode: 'add',
                    header: 'add',
                    show: false
                }
            },
            item:{
                id: 0,
                name: '',
                facade_set_id: "0"
            },

        }
    },
    created: async function(){
        let scope = this;

        this.$options.facades_systems = await promise_request(glob.base_url + '/catalog/items_get_common/facades_systems');

        this.$root.$on('add_item', function () {
            Vue.set(scope, 'item', {
                id: 0,
                name: '',
                facade_set_id: "0"
            })
            scope.modals.add.mode = 'add'
            scope.modals.add.header = 'add'
            scope.modals.add.show = 1;
        })

        this.$root.$on('json_input', function (item) {
            console.log(item)



            Vue.set(scope, 'item', item)

            scope.modals.json.mode = 'add'
            scope.modals.json.header = 'add'
            scope.modals.json.show = 1;

            console.log(scope.modals)

        })

        this.$root.$on('edit_item', function (item) {

            if(!item.facade_set_id) item.facade_set_id = "0"

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


            let formData = new FormData();

            formData.append('name', data.name);
            formData.append('facade_set_id', data.facade_set_id);

            let url = base_url + '/catalog/add_item_ajax_no_files_common/' + controller_name;

            if(data.id != 0) url = base_url + '/catalog/add_item_ajax_no_files_common/' + controller_name + '/' + data.id;

            send_xhr_post(
                url,
                formData,
                function (result) {
                    scope.$root.change_page(1);
                    scope.modals.add.show = false;
                }
            )

        },
        submit_json: function () {
            let scope = this;


            let val = this.$refs.json_value.value;

            let url = base_url + '/catalog/add_modules_data_from_json_common/';

            try {
                let json = JSON.parse(val)
                console.log(json)

                if(json.modules) json = json.modules


                json.categories = create_tree(json.categories)

                let formData = new FormData();

                formData.append('data', JSON.stringify(json));
                formData.append('id', scope.item.id);

                send_xhr_post(
                    url,
                    formData,
                    function (result) {
                        console.log(result)
                        scope.modals.json.show = 0;
                    }
                )


            } catch (e) {
                alert('Ошибка в JSON')
                console.log(e)
            }



        }
    },
    template: cpacctr
});
</script>
