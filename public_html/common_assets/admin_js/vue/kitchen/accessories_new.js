let types_default;
let session_data = {
    category: "0",
    per_page: "20",
    start: 0,
    search: ""
};
let per_page = ["10", "20", "50", "100"]
let app;

let account_name = ''

let local_storage_available = 1;

let ls_keys;

document.addEventListener('Glob_ready', function(){

    let acc_arr = glob.acc_url.split('/')
    account_name  = acc_arr[acc_arr.length-1];
    if(account_name == '') account_name = acc_arr[acc_arr.length-2]

    ls_keys = {
        category: 'bplanner_admin_' + account_name + '_accessories_category',
        per_page: 'bplanner_admin_' + account_name + '_accessories_per_page',
        start: 'bplanner_admin_' + account_name + '_accessories_start',
        search: 'bplanner_admin_' + account_name + '_accessories_search'
    }

    try{
        Object.keys(ls_keys).forEach(function (k) {
            if(localStorage.getItem(ls_keys[k])) session_data[k] = localStorage.getItem(ls_keys[k]);
        })

    } catch (e) {
        local_storage_available = 0;
        console.log(e)
    }


    init_vue();
})

function init_vue() {
    app = new Vue({
        el: '#app',
        components: {
            'v-select': VueSelect.VueSelect
        },
        data: {
            items:[],
            items_count: 0,
            page: 1,
            filter:{
                category: session_data.category,
                per_page: session_data.per_page,
                start: session_data.start,
                search: session_data.search
            },
            item: {
                id: 0,
                name:'',
                code: '',
                category: '',
                price: 0,
                type: 'common',
                tags: '',
                images: '',
                description: '',
                active: 1,
            },

            items_by_type: [],
            current_type: 0,

            types_default:[
                {
                    name: glob.lang['common'],
                    key: 'common'
                },
                {
                    name: glob.lang['hinges'],
                    key: 'door'
                },
                {
                    name: glob.lang['hinges_lockers'],
                    key: 'locker'
                },
                {
                    name: glob.lang['hinges_simple_top'],
                    key: 'simple_top'
                },
                {
                    name: glob.lang['hinges_double_top'],
                    key: 'double_top'
                },
                {
                    name: glob.lang['hinges_front_top'],
                    key: 'front_top'
                }
            ],
            types: [

            ],
            type_item:{
                id: '',
                name: '',
                key: '',
                default: '',
                auto: 1,
            }
        },
        created: function () {
        },
        mounted: function () {
            let scope = this;
            this.make_request();
            this.make_types_request();

            $('#csv_import').submit(function (e) {
                e.preventDefault();

                // data:  new FormData(this),
                $.ajax({
                    url: $('#ajax_base_url').val() + '/accessories/import_csv',
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    processData: false
                }).done(function (msg) {
                    // location.reload();
                    toastr.success(scope.lang('success'))
                    console.log(msg)
                })

            })

            $('#clear_db').click(function () {
                Swal.fire({
                    title: scope.lang('are_u_sure'),
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: scope.lang('no'),
                    confirmButtonText: scope.lang('yes'),
                    closeOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                      },
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: $('#ajax_base_url').val() + '/accessories/clear_db',
                            type: 'post',
                            data: {true:true}
                        }).done(function (msg) {
                            location.reload();
                        })
                    }
                });
            })

        },
        computed:{
            pages_count: function () {
                return Math.ceil(this.items_count / this.filter.per_page);
            },
        },

        methods: {
            lang: function (key) {
                return glob.lang[key];
            },
            clear_item: function () {
                Vue.set(this, 'item', {
                    id: 0,
                    name:'',
                    code: '',
                    category: '',
                    price: 0,
                    type: 'common',
                    tags: '',
                    images: '',
                    description: '',
                    active: 1
                })
            },
            clear_type: function () {
                Vue.set(this, 'type_item', {
                    id: '',
                    name: '',
                    key: '',
                    default: '',
                    auto: 1,
                })
            },

            get_type_name: function(type){

                for (let i = 0; i < this.types_default.length; i++){
                    if(this.types_default[i].key == type) return this.types_default[i].name
                }

                for (let i = 0; i < this.types.length; i++){
                    if(this.types[i].key == type) return this.types[i].name
                }

                return this.lang('no');

            },

            get_data: function(){
                return copy_object(this.item)
            },
            get_type_data: function(){
                return copy_object(this.type_item)
            },

            get_items_by_type(item){
                let scope = this;

                let formData = new FormData();
                formData.append('data', JSON.stringify({
                    type: item.key
                }));

                this.current_type = item;

                send_xhr_post(
                    glob.base_url + '/catalog/items_get_where/accessories/1',
                    formData,
                    function (result) {
                        scope.items_by_type = JSON.parse(result.responseText);
                    }
                )

            },

            select_default_type_item(item){
                this.current_type.default = item.id;

                let scope = this;
                let data = {
                    id: this.current_type.id,
                    default: item.id
                }

                let formData = new FormData();
                formData.append('data', JSON.stringify(data));

                send_xhr_post(
                    glob.base_url + '/catalog/add_item_ajax/accessories_types',
                    formData,
                    function (result) {

                    }
                )

            },

            add_item: function(){
                let scope = this;
                let data = copy_object(this.item);
                if(data.id == 0) delete data.id;

                let formData = new FormData();
                formData.append('data', JSON.stringify(data));

                send_xhr_post(
                    glob.base_url + '/catalog/add_item_ajax/accessories',
                    formData,
                    function (result) {
                        $('#acc_modal').modal('hide')
                        // scope.make_request();
                        scope.change_page(1)
                    }
                )


            },
            edit_item: function(item){
                Vue.set(this, 'item', item)
            },
            edit_type_item: function(item){
                Vue.set(this, 'type_item', item)
            },
            remove_item: function(item){
                let scope = this;

                Swal.fire({
                    title: scope.lang('are_u_sure'),
                    text: scope.lang('delete_confirm_message'),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: scope.lang('no'),
                    confirmButtonText: scope.lang('yes'),
                    closeOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                      }
                }).then((result) => {
                    if (result.value) {
                        let form_data = new FormData();
                        form_data.append('id', item.id)
    
                        let url = glob.base_url + '/catalog/remove_item/accessories'
    
                        send_xhr_post(
                            url,
                            form_data,
                            function (xhr) {
                                scope.make_request();
                            })
                    }
                });
            },

            add_type_item: function(){
                let scope = this;
                let data = copy_object(this.type_item);

                if(
                    data.key == 'door' ||
                    data.key == 'locker' ||
                    data.key == 'simple_top' ||
                    data.key == 'double_top' ||
                    data.key == 'front_top' ||
                    data.key == 'common'
                ){
                    toastr.error(scope.lang('key_already_exists'));
                    return;
                }


                if(data.id == 0){
                    for (let i = 0; i < this.types.length; i++){
                        if(
                            this.types[i].key == data.key
                        ){
                            toastr.error(scope.lang('key_already_exists'));
                            return;
                        }
                    }
                }
                if(data.id == 0) delete data.id;

                let formData = new FormData();
                formData.append('data', JSON.stringify(data));

                send_xhr_post(
                    glob.base_url + '/catalog/add_item_ajax/accessories_types',
                    formData,
                    function (result) {
                        $('#acc_type_modal').modal('hide')
                        scope.make_types_request();
                    }
                )
            },
            remove_type_item: function(item){
                let scope = this;

                Swal.fire({
                    title: scope.lang('are_u_sure'),
                    text: scope.lang('delete_confirm_message'),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: scope.lang('no'),
                    confirmButtonText: scope.lang('yes'),
                    closeOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                      },
                }).then((result) => {
                    if (result.value) {
                        let form_data = new FormData();
                        form_data.append('id', item.id)
    
                        let url = glob.base_url + '/catalog/remove_item/accessories_types'
    
                        send_xhr_post(
                            url,
                            form_data,
                            function (xhr) {
                                scope.make_types_request();
                            })
                    }
                });
            },

            change_auto: function(item){

                let scope = this;
                let data = {
                    id:item.id,
                    auto: item.auto
                }

                let formData = new FormData();
                formData.append('data', JSON.stringify(data));

                send_xhr_post(
                    glob.base_url + '/catalog/add_item_ajax/accessories_types',
                    formData,
                    function (result) {

                    }
                )

            },


            change_active: function (item) {
                if (item.active == 0) {
                    item.active = 1
                } else {
                    item.active = 0;
                }

                send_xhr_get(glob.base_url + '/catalog/item_set_active/accessories/' + item.id + '/' + item.active)
            },

            get_eye_class: function (item) {
                return item.active == 1 ? ['fa fa-eye'] : ['fa-solid fa-eye-low-vision']
            },

            do_search: function(){
                let scope = this;

                if(local_storage_available) localStorage.setItem(ls_keys.search, scope.filter.search);
                if(local_storage_available) localStorage.setItem(ls_keys.start, 0);
                scope.filter.start = 0;

                scope.make_request(true)

            },
            filter_per_page: function(val){
                console.log(val)
                let scope = this;
                scope.filter.per_page = val;
                if(local_storage_available) localStorage.setItem(ls_keys.per_page, val);

                if(local_storage_available) localStorage.setItem(ls_keys.start, 0);
                scope.filter.start = 0;

                scope.make_request(true)
            },
            change_page: function(val){
                this.page = val;
                let scope = this;

                console.log((val -1 )*scope.filter.per_page)

                scope.filter.start = (val -1 )*scope.filter.per_page
                if(local_storage_available) localStorage.setItem(ls_keys.start, scope.filter.start);

                scope.make_request(false)



                this.$emit('change_page_sync', val);

            },

            export_xls: async function(){
                let items = await promise_request(glob.base_url + '/catalog/items_get/accessories/1');

                let scope = this;

                workbook = new ExcelJS.Workbook();
                workbook.creator = "PlanPlace";
                workbook.lastModifiedBy = "PlanPlace";
                workbook.created = new Date();
                workbook.modified = new Date();
                let sheet = workbook.addWorksheet();

                sheet.addRow([
                    'id',
                    this.lang('code'),
                    this.lang('name'),
                    this.lang('category'),
                    this.lang('price'),
                    this.lang('images'),
                    this.lang('description'),
                    this.lang('tags'),
                    this.lang('type'),
                    this.lang('default'),
                    this.lang('active'),

                ])

                for (let i = 0; i < items.length; i++){
                    sheet.addRow([
                        items[i].id,
                        items[i].code,
                        items[i].name,
                        items[i].category,
                        items[i].price,
                        items[i].images,
                        items[i].description,
                        items[i].tags,
                        items[i].type,
                        items[i].default,
                        items[i].active
                    ])
                }

                workbook.xlsx.writeBuffer().then(function (data) {
                    var blob = new Blob([data], {type: "application/xlsx"});
                    var downloadUrl = window.URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = downloadUrl;
                    a.download = 'accessories.xlsx';
                    document.body.appendChild(a);
                    a.click();
                });

            },

            import_xls: async function(){
                const wb = new ExcelJS.Workbook();
                const reader = new FileReader()
                let scope = this;
                reader.readAsArrayBuffer(this.$refs.imp.files[0])
                reader.onload = () => {
                    let res = [];
                    const buffer = reader.result;
                    wb.xlsx.load(buffer).then(async workbook => {
                        workbook.eachSheet((sheet, id) => {
                            console.log(sheet)
                            sheet.eachRow((row, rowIndex) => {
                                let r = [];
                                r.length = 11;
                                row.eachCell(function(cell, colNumber) {

                                    if (cell.formula) {
                                        r[colNumber - 1] = cell.value.result;

                                    } else {
                                        r[colNumber - 1] = cell.value;
                                    }


                                });


                                res.push(r)
                            })
                        })

                        res.shift();

                        let fdata = new FormData()
                        fdata.append('items', JSON.stringify(res))



                        let result = await promise_request_post(glob.base_url + '/accessories/import_xls/', fdata)

                        scope.change_page(1)
                        toastr.success(scope.lang('success'))
                        console.log(result)
                    })
                }
            },

            export_csv: function(){
                window.open(glob.base_url + '/accessories/export_csv/')
            },

            clear_db: function(){
                let scope = this;
                Swal.fire({
                    title: scope.lang('are_u_sure'),
                    text: scope.lang('delete_confirm_message'),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: scope.lang('no'),
                    confirmButtonText: scope.lang('yes'),
                    closeOnConfirm: true,
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                      },
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: glob.base_url + '/accessories/clear_db',
                            type: 'post',
                            data: {true:true}
                        }).done(function (msg) {
                            scope.make_request();
                        })
                    }
                });
            },

            make_request: function(reset){
                let scope = this;
                let url = glob.base_url + '/catalog/items_get_pagination/accessories/0/' + scope.filter.per_page + '/' + scope.filter.start + '/' + scope.filter.search;
                promise_request(url)
                    .then(function (result) {
                        scope.items_count = result['count'];

                        Vue.set(scope,'items', result['items'])
                        if(reset) scope.$emit('reset_page', 1);

                    }).catch(function (err) {}
                )
            },
            make_types_request: function () {
                let scope = this;
                let url = glob.base_url + '/catalog/items_get/accessories_types';
                promise_request(url)
                    .then(function (result) {
                       Vue.set(scope, 'types', result)

                    }).catch(function (err) {}
                )
            },
            save_data: function () {
                let scope = this;
                let url = glob.base_url + '/accessories/save_data_ajax';
                promise_request(url)
                    .then(function (result) {
                        toastr.success(scope.lang('success'))

                    }).catch(function (err) {}
                )
            }
        }
    });
}