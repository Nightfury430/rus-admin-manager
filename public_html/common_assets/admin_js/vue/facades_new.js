let app = null;
let lang = null;
let controller_name = null;
let base_url = null;
let acc_url = null;

let categories_data = null;
let categories_hash = null;
let categories_ordered = null;

let items_data = null;
let items_hash = null;
let items_count = 0;

let is_modules = false;
let set_id = null;

let session_data = {
    category: "0",
    per_page: "20",
    start: 0,
    search: ""
};

let ls_keys = {};

let local_storage_available = 1;

let per_page = ["10", "20", "50", "100"]

let single_page_names = {
    coupe_accessories: 1
}

let add_urls = {}

let edit_urls = {}


document.addEventListener('DOMContentLoaded', async function () {

    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;
    lang = JSON.parse(document.getElementById('lang_json').value);

    let categories_url = base_url + '/catalog/categories_get/facades'
    categories_data = await promise_request(categories_url);
    categories_data.unshift({
        name: lang['no'],
        id: '0',
        parent: '0'
    })

    categories_hash = get_hash(categories_data);
    categories_ordered = flatten(create_tree(categories_data));

    init_vue();
})

function init_vue() {


    app = new Vue({
        el: '#sub_form',
        components: {
            'v-select': VueSelect.VueSelect
        },
        data: {
            item:{
                name: "",
                category: "0",
                icon: '',
                types: {

                }
            },

            icon_file: '',
        },
        created: function () {

            this.$options.categories_ordered = categories_ordered;
            this.$options.categories_hash = categories_hash;
        },
        computed:{

        },
        mounted: function () {

        },
        methods: {
            lang: function(key){
                return lang[key]
            },
            get_icon_src:function (file) {
                if(this.item.icon != '' && this.icon_file == '') return this.item.icon;
                if(this.icon_file != '') return URL.createObjectURL(file);
                if(this.item.icon == '' &&  this.icon_file == '') return '/common_assets/images/placeholders/78x125.png';
            },
            process_icon_file: function (event) {
                if(event.target.files.length){

                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.icon_file = event.target.files[0];
                } else {
                    this.icon_file = '';
                }
            },
        }
    });

}



