let item = null;

document.addEventListener('Glob_ready', async function () {

    categories_data = await promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name);

    categories_data.unshift({
        id: '0',
        name: glob.lang['no'],
        parent: '0'
    })

    categories_hash = get_hash(categories_data);
    categories_ordered = flatten(create_tree(categories_data));

    if(glob.item_id != 0){
        item = await promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id);
        item = JSON.parse(item.data);
        if(!item.mat_categories) item.mat_categories = [];
        if(!item.params.material) item.params.material = 0;

    }


    init_vue();
})

function init_vue() {
    app = new Vue({
        el: '#app',
        data: {
            item:{
                name: '',
                code: '',
                icon: '',
                category: '0',
                active: 1,
                order: 10000,
                models: {
                    common: ''
                },
                accs: [],
                params: {
                    height: 0,
                    depth: 0,
                    item_width: 0,
                    material: 0
                },
                price: 0,
                price_acc: 0,
            },
            options_ready: false,
            controller_name: glob.controller_name
        },
        components: {
            'v-select': VueSelect.VueSelect,
        },
        computed: {
            c_mat_categories: function () {
                let res = [
                    {
                        id: "0",
                        parent: '0',
                        children: [],
                        name: this.$options.lang['no']
                    }
                ];

                if(this.options_ready){
                    if(this.item.params.materials_mode == 'c'){


                        let hash = this.$options.m_categories_hash;
                        for (let i = this.item.mat_categories.length; i--;){
                            let cat = this.item.mat_categories[i];

                            if(hash[cat]){
                                res.push(copy_object(hash[cat]));
                            }
                        }
                    }
                }



                return flatten(res);

            },
            c_top_categories: function () {
                let res = [];
                if(this.options_ready){

                }
                return res;
            }
        },
        mounted: function(){

        },
        beforeCreate: function(){
            this.$options.lang = glob.lang;

        },
        created: async function () {
            this.$options.cat_ordered = categories_ordered;

            let categories_url = glob.base_url + '/catalog/' + glob.c_method_name + '/' + 'materials'

            this.$options.m_categories_data = await promise_request(categories_url);
            this.$options.m_categories_hash = get_hash(this.$options.m_categories_data);
            // this.$options.m_categories_tree = create_tree(this.$options.m_categories_data);
            this.$options.m_categories_ordered = flatten(create_tree(this.$options.m_categories_data));
            this.$options.m_top_categories = [];
            for (let i = 0; i < this.$options.m_categories_ordered.length; i++) {
                if(this.$options.m_categories_ordered[i].parent == 0){
                    this.$options.m_top_categories.push(this.$options.m_categories_ordered[i])
                }
            }

            this.options_ready = true;

        },
        beforeMount: async function () {
            if(glob.item_id != 0){
                Vue.set(this, 'item', item)
            }
        },
        methods: {
            lang: function(key){
                return glob.lang[key]
            },

            export_xls: function(){
                const workbook = new ExcelJS.Workbook();
                const sheet = workbook.addWorksheet('Карниз');

                sheet.addRow(
                    [
                        this.lang('name'),
                        this.lang('code') + ' ' + this.lang('cornice_common'),
                        this.lang('code') + ' ' + this.lang('cornice_radius'),
                        this.lang('code') + ' ' + this.lang('cornice_radius_i'),
                        this.lang('mat_code'),
                        this.lang('cornice_common'),
                        this.lang('cornice_radius'),
                        this.lang('cornice_radius_i'),
                    ]
                )

                for (let i = 0; i < this.item.items.length; i++) {
                    let mat_code = this.item.items[i].mat_code
                    if(i == 0){
                        mat_code = this.lang('all')
                    }

                    sheet.addRow(
                        [
                            this.item.items[i].name,
                            this.item.items[i].code,
                            this.item.items[i].code_radius,
                            this.item.items[i].code_radius_i,
                            this.item.items[i].price.common,
                            this.item.items[i].price.radius,
                            this.item.items[i].price.radius_i,
                            mat_code,
                        ]
                    )
                }


                workbook.xlsx.writeBuffer().then(function (data) {
                    var blob = new Blob([data], {type: "application/xlsx"});
                    var downloadUrl = window.URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = downloadUrl;
                    a.download = 'Карниз.xlsx';
                    document.body.appendChild(a);
                    a.click();
                });

            },
            import_xls: async function(e){
                const workbook = new ExcelJS.Workbook();
                const reader = new FileReader()

                reader.readAsArrayBuffer(e.target.files[0])
                reader.onload = () => {
                    const buffer = reader.result;
                    workbook.xlsx.load(buffer).then(workbook => {
                        let res = [];
                        workbook.eachSheet((sheet, id) => {
                            sheet.eachRow((row, rowIndex) => {
                                if(rowIndex == 1) return;
                                res.push({
                                    name: row.values[1],
                                    code: row.values[2],
                                    code_radius: row.values[3],
                                    code_radius_i: row.values[4],
                                    mat_code: rowIndex == 2 ? 'c_default_price' : row.values[8],
                                    price: {
                                        common: row.values[5],
                                        radius: row.values[6],
                                        radius_i: row.values[7]
                                    }
                                })
                            })
                        })

                        Vue.set(this.item, 'items', res)
                        e.target.value = "";
                    })
                }
            },
            fill_prices: function(){
                if(this.item.params.materials_mode == 'i'){
                    this.item.items.length = 1;

                    let selected = this.$refs.materials_picker.get_items();

                    Object.keys(selected).forEach((k)=>{
                        this.item.items.push({
                            name: '',
                            name_radius: '',
                            name_radius_i: '',
                            code: '',
                            code_radius: '',
                            code_radius_i: '',
                            mat_code: selected[k].code,
                            mat_category: '0',
                            price: {
                                common: 0,
                                radius: 0,
                                radius_i: 0
                            }
                        })
                    })


                }
                if(this.item.params.materials_mode == 'c'){
                    this.item.items.length = 1;

                    let selected = this.c_mat_categories;

                    for (let i = 0; i < selected.length; i++) {
                        if(selected[i].id == 0) continue
                        this.item.items.push({
                            name: '',
                            name_radius: '',
                            name_radius_i: '',
                            code: '',
                            code_radius: '',
                            code_radius_i: '',
                            mat_code: '',
                            mat_category: selected[i].id,
                            price: {
                                common: 0,
                                radius: 0,
                                radius_i: 0
                            }
                        })
                    }




                }
            },


            get_data(){
                return copy_object(this.item)
            },
            add_item: function(){
                this.item.items.push({
                    name: '',
                    name_radius: '',
                    name_radius_i: '',
                    code: '',
                    code_radius: '',
                    code_radius_i: '',
                    mat_code: '',
                    mat_category: '0',
                    price: {
                        common: 0,
                        radius: 0,
                        radius_i: 0
                    }
                })
            },
            remove_item(index) {
                this.item.items.splice(index, 1)
            },

            test: function($event){

            },
            submit: function (e) {
                e.preventDefault();


                let url = this.$refs.submit_url.value

                let fdata = new FormData();
                let data = app.get_data();
                fdata.append('id', data.id)
                fdata.append('name', data.name)
                fdata.append('icon', data.icon)
                fdata.append('category', data.category)
                fdata.append('data', JSON.stringify(data))
                fdata.append('active', data.active)
                fdata.append('order', data.order)

                let res = promise_request_post(url, fdata)
                console.log(res)
                location.href = this.$refs.success_url.value
                return false;
            }
        }
    });
}