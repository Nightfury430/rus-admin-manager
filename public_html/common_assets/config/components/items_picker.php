
<style>
    .ppip_item_image{
        width: 50px;
        height: 50px;
        border: 1px solid;
        background-size: cover!important;
    }

    .ppip_item_image > div{
        width: 100%;
        height: 100%;
    }

    .ppip_item_icon{
        width: 80px;
        height: 80px;
        background-size: contain!important;
        background-position: center!important;
        background-repeat: no-repeat!important;
    }

    .ppip_item_icon_small{
        width: 40px!important;
        height: 40px!important;
    }

</style>

<script>
    let pp_items_picker_template = `
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/items_picker_template.php'?>
    `

    Vue.component('pp_items', {
        template: pp_items_picker_template,
        // template: '#pp_items_picker_template',
        components: {
            'v-select': VueSelect.VueSelect
        },
        props: {
            controller: {
                type: String,
                default: 'materials'
            },
            src: {
                type: String,
                default: ''
            },
            small: {
                type: Number,
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
                        id_short: 'ID',
                        name: 'Название',
                        code: 'Артикул',
                        count_short: 'Кол-во',
                        choose_file: 'Выберите файл',
                        download_model: 'Скачать модель'
                    }
                }
            },
            unselect:{
                type: Boolean, Number,
                default: false,
            },
            selected_items: {
                type: Array,
                default: function () {
                    return [];
                }
            },
            count_mode: {
                type: Boolean, Number,
                default: false,
            },
            selected_item: {
                type: String, Number,
                default: null
            },
            categories: {
                type: Array,
                default: function () {
                    return [];
                }
            }
        },
        data: function () {
            return {
                selected: [],
                selected_hash: {},
                items: [],
                items_count: 0,
                page: 1,
                filter: {
                    category: "0",
                    per_page: "20",
                    start: 0,
                    search: ""
                },
                is_modules: false,
                is_catalogue: false,
                items_method: 'items_get_pagination_multi',
                categories_method: 'categories_get_multi',
                cat_select: 0,
                is_single: false,
                item_data: null,
                options_ready: false,
                cats: [],
                debounce: null,
            };
        },

        beforeCreate() {

        },
        created: async function () {

            if (this.controller == 'modules' || this.controller == 'module_sets') this.is_modules = true;
            if (this.controller == 'catalogue') this.is_catalogue = true;
            this.$options.per_page = ['10', '20', '50', '100']

            console.log(this.is_catalogue)

            await this.update_categories_data();

            if(this.selected_item != null){
                this.is_single = true;

                await this.set_item_data();
            }


            if(this.is_single == false && this.selected_items.length > 0) {
              await this.set_items_data();
            }

            this.$root.$on('set_item_data', async ()=> {
                await this.set_item_data();
            })

            this.options_ready = true;

        },
        computed: {
            view: function(){
                switch (this.controller) {
                    case 'accessories':
                        return 'table'
                    default:
                        return 'materials'
                }
            },
            pages_count: function () {
                return Math.ceil(this.items_count / this.filter.per_page);
            },
            item_cols: function () {
                let keys = Object.keys(this.selected_hash);
                if(keys.length > 0){
                    return this.selected_hash[keys[0]]
                }
                return null;
            }
        },
        watch: {
            selected_item: function (n_val, o_val) {
                this.set_item_data();
            },
            selected_items: function (n_val, o_val) {
                this.set_items_data();
            },
            categories: async function () {
                await this.update_categories_data();
            }
        },
        mounted: async function () {

        },
        methods: {

            update_categories_data: async function(){

                let categories_url = glob.base_url + '/catalog/' + this.categories_method

                let fdata = new FormData();

                fdata.append('name', this.controller)
                fdata.append('categories', JSON.stringify(this.categories))

                // if (this.is_modules) {
                //     categories_url = base_url + '/catalog/' + categories_method_name + '/' + this.controller + '/' + set_id
                // }

                this.$options.categories_data = await promise_request_post(categories_url, fdata);

                let cats = [];

                if(this.categories.length){
                    for (let i = 0; i < this.$options.categories_data.length;i++){
                        cats.push(this.$options.categories_data[i].id)
                    }
                }

                this.cats = cats;

                this.$options.categories_data.unshift({
                    id: "0",
                    name: this.lang['all'],
                    parent: "0"
                })


                this.$options.categories_hash = get_hash(this.$options.categories_data);
                this.$options.categories_ordered = flatten_tree(create_tree(this.$options.categories_data));
            },

            set_items_data: async function(){
                let url = glob.base_url + '/catalog/get_items_by_id/' + this.controller;

                if(this.controller == 'accessories') url += '/1'

                let fdata = new FormData();

                let dta;
                if(this.count_mode){
                    dta = [];
                    for (let i = 0; i < this.selected_items.length; i++) {
                        dta.push(this.selected_items[i].id)
                    }
                } else {
                    dta = this.selected_items
                }

                fdata.append('data', JSON.stringify(dta));
                let result = await promise_request_post(url, fdata)

                let sh = {};
                for(let i = 0; i < result.length; i++) {
                    sh[result[i].id] = result[i];
                    if(this.count_mode){
                        sh[result[i].id].count = this.selected_hash[result[i].id] ? this.selected_hash[result[i].id].count : 1
                    }
                }

                Vue.set(this, 'selected_hash', sh);
                this.selected = this.selected_items;

                if(this.count_mode){
                    for (let i = this.selected.length; i--;){
                        if(!this.selected_hash[this.selected[i].id]){
                            this.selected.splice(i, 1)
                        }  else {
                            this.selected_hash[this.selected[i].id].count = this.selected[i].count
                        }

                    }
                } else {
                    for (let i = this.selected.length; i--;){
                        if(!this.selected_hash[this.selected[i]]) this.selected.splice(i, 1)
                    }
                }

            },
            set_item_data: async function(){
                if(this.selected_item != 0){
                    let item = await promise_request(glob.base_url + '/catalog/get_item/' + this.controller + '/' + this.selected_item);

                    if(item.data) item.data = JSON.parse(item.data)
                    item.type = this.controller
                    Vue.set(this, 'item_data', item)
                } else {
                    this.item_data = {
                        id: '0',
                        name: this.lang['pick']
                    }
                }
            },
            get_item_data: function(){
                return copy_object(this.item_data)
            },
            get_link: function(){
                return glob.base_url + '/' + this.controller + '/item/' + this.selected_item
            },
            get_icon(){
                if(!this.item_data) return '';
                if (this.item_data.type == 'materials'){
                    if(this.item_data.data){
                        if(this.item_data.data.params.icon) return 'url(' + this.correct_url(this.item_data.data.params.icon) + ')'
                        if(this.item_data.data.params.map) return 'url(' + this.correct_url(this.item_data.data.params.map) + ')'
                        if(this.item_data.data.params.color) return this.item_data.data.params.color
                    } else {
                        if(this.item_data.icon) return 'url(' + this.correct_url(this.item_data.icon) + ')'
                        if(this.item_data.map) return 'url(' + this.correct_url(this.item_data.map) + ')'
                        if(this.item_data.color) return this.item_data.color
                    }
                }
                if(this.item_data.icon) return 'url(' + this.correct_url(this.item_data.icon) + ')'
            },
            get_map: function (item) {
                if (item.map) return 'url(' + this.correct_url(item.map) + ')'
                return item.color;
            },
            get_eye_class: function (item) {
                return item.active != 0 ? ['fa-eye', 'btn-primary'] : ['fa-solid fa-eye-low-vision', 'btn-default']
            },
            replace_search(input) {
                // let search = input.replaceAll('(', '^pp_lb^')
                // search = search.replaceAll(')', '^pp_rb^')
                // search = search.replaceAll(',', '^pp_com^')
                // search = search.replaceAll('!', '^pp_exc^')
                // search = search.replaceAll('@', '^pp_dog^')
                // search = search.replaceAll('$', '^pp_dol^')
                // search = search.replaceAll('%', '^pp_per^')
                // search = search.replaceAll('&', '^pp_amp^')
                // search = search.replaceAll('*', '^pp_mul^')
                // search = search.replaceAll('+', '^pp_plu^')
                // search = search.replaceAll('[', '^pp_slb^')
                // search = search.replaceAll(']', '^pp_srb^')
                // search = search.replaceAll('=', '^pp_eq^')

                return input;
            },

            get_items(){
                return copy_object(this.selected_hash);
            },

            change_count(id, e){
                clearTimeout(this.debounce)
                this.debounce = setTimeout( () => {
                    for (let i = this.selected.length; i--;){
                        if(this.selected[i].id == id){
                            this.selected[i].count = parseInt(e.target.value)
                            break;
                        }
                    }


                    this.emit_update();

                }, 600)
            },

            filter_category: function (val) {
                this.filter.category = val;
                this.filter.start = 0;
                this.make_request(true)
            },
            filter_per_page: function (val) {
                let scope = this;
                scope.filter.per_page = val;
                scope.filter.start = 0;
                scope.make_request(true)
            },
            change_page: function (val) {
                this.page = val;
                let scope = this;
                scope.filter.start = (val - 1) * scope.filter.per_page
                scope.make_request(false)
                this.$emit('change_page_sync', val);

            },
            do_search: function () {
                let scope = this;
                scope.filter.start = 0;
                scope.make_request(true)
            },

            add_selected(item){
                if(this.is_single){
                    this.selected_item = item.id;
                    Vue.set(this, 'item_data', item);
                    $(this.$refs.fm).modal('hide');
                } else {
                    if(this.count_mode){

                        let it = copy_object(item);
                        it.count = 1;

                        Vue.set(this.selected_hash, item.id, it)
                        this.selected.push({
                            id: item.id,
                            count: 1
                        });


                    } else {
                        Vue.set(this.selected_hash, item.id, copy_object(item))
                        this.selected.push(item.id);
                    }


                }

                this.emit_update();
            },

            unselect_item() {
                this.selected_item = 0;
                Vue.set(this, 'item_data', {
                    id: '0',
                    name: this.lang['pick']
                })
                this.emit_update();
            },

            remove_selected(id){

                for (let i = this.selected.length; i--;){
                    if(typeof this.selected[i] == 'object'){
                        if(this.selected[i].id == id){
                            this.selected.splice(i, 1)
                            break;
                        }
                    } else {
                        if(this.selected[i] == id){
                            this.selected.splice(i, 1)
                            break;
                        }
                    }

                }
                Vue.delete(this.selected_hash, id)

                this.emit_update();
            },

            make_request: async function (reset) {
                let scope = this;

                this.filter.search = this.filter.search.replaceAll('\'', '\"')
                let search = this.replace_search(this.filter.search)


                // let url = glob.base_url + '/catalog/' + this.items_method + '/' + this.controller + '/' + scope.filter.category + '/' + scope.filter.per_page + '/' + scope.filter.start + '/' + search;
                //
                // if (this.controller == 'module_sets' || this.controller == 'modules_sets_modules') {
                //     url = glob.base_url + '/catalog/' + this.items_method + '/' + this.controller + '/' + scope.filter.category + '/' + scope.filter.per_page + '/' + scope.filter.start + '/' + set_id;
                // }
                let url = glob.base_url + '/catalog/' + this.items_method
                let fdata = new FormData();

                fdata.append('name', this.controller)
                fdata.append('category', scope.filter.category)
                fdata.append('per_page', scope.filter.per_page)
                fdata.append('start', scope.filter.start)
                fdata.append('search', scope.filter.search)
                fdata.append('categories', JSON.stringify(this.cats))
                fdata.append('categories_multi', this.is_catalogue)

                let result = await promise_request_post(url, fdata)
                scope.items_count = result['count'];



                console.log(result)



                if (this.is_modules) {
                    for (let i = 0; i < result['items'].length; i++) {
                        result['items'][i].params = JSON.parse(result['items'][i].params).params
                    }
                }

                if (this.is_catalogue) {
                    for (let i = 0; i < result['items'].length; i++) {
                        result['items'][i].category = JSON.parse(result['items'][i].category)
                        result['items'][i].data = JSON.parse(result['items'][i].data)
                    }
                }

                if (this.controller == 'facades') {
                    for (let i = 0; i < result['items'].length; i++) {
                        result['items'][i].materials = JSON.parse(result['items'][i].materials)
                    }
                }

                if (this.controller == 'glass') {
                    for (let i = 0; i < result['items'].length; i++) {
                        result['items'][i].params = JSON.parse(result['items'][i].params).params

                        if (result['items'][i].params.icon && result['items'][i].params.icon != '') {
                            result['items'][i].icon = result['items'][i].params.icon
                        } else {
                            if (result['items'][i].params.color) result['items'][i].color = result['items'][i].params.color
                            if (result['items'][i].params.map) result['items'][i].map = result['items'][i].params.map
                        }
                    }
                }
                if(this.controller == 'materials'){
                    for (let i = 0; i < result['items'].length; i++) {
                        if(result['items'][i].data){
                            result['items'][i].data = JSON.parse(result['items'][i].data);
                            if (result['items'][i].data.params.icon && result['items'][i].data.params.icon != '') {
                                result['items'][i].icon = result['items'][i].data.params.icon
                            } else {
                                if (result['items'][i].data.params.color) result['items'][i].color = result['items'][i].data.params.color
                                if (result['items'][i].data.params.map) result['items'][i].map = result['items'][i].data.params.map
                            }
                        }

                    }
                }


                Vue.set(scope, 'items', result['items'])
                if (reset) scope.$emit('reset_page', 1);


            },

            show_fm: function () {
                this.make_request();
                this.show_modal = 1;
                this.cat_select = 1;
                $(this.$refs.fm).modal('show');
            },

            emit_update() {
                if(this.is_single){
                    this.$emit('e_update', copy_object(this.selected_item));
                } else {

                    this.$emit('e_update', copy_object(this.selected));
                }


            },

            correct_url: function (path) {
                if (path == '' || path == null) return '/common_assets/images/placeholders/256x256.png'

                let date_time = new Date().getTime();

                if (path.indexOf('common_assets') > -1) {
                    return path + '?' + date_time
                } else {
                    return glob.acc_url + path + '?' + date_time;
                }
            },
        }
    })
</script>