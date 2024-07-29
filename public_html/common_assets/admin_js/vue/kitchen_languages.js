document.addEventListener('DOMContentLoaded', function () {
    app = new Vue({
        el: '#sub_form',
        data: {
            errors: [],
            lang: {
                original:[],
                custom:[],
                original_front:[],
                custom_front:[]
            },
            not_translated: false,
            active_tab: 'back',
            use_custom_lang:false,
            show_success_message:false
        },
        computed:{

        },
        beforeMount () {
            let scope = this;
            if(document.getElementById('lang').value === 'custom')this.use_custom_lang = true;
            axios({
                method: 'post',
                url: '/common_assets/config/lng.php',
                data: 'get_lang',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (msg) {
                scope.lang.original = msg.data;


                axios({
                    method: 'get',
                    url: document.getElementById('lang_data').value
                }).then(function (msg) {

                    let custom_obj;

                    if(msg.data === ''){
                        custom_obj = {};
                    } else {

                        custom_obj = msg.data;
                    }

                    for(let key in scope.lang.original){
                        if(!custom_obj[key]) custom_obj[key] = '';
                    }

                    scope.lang.custom = custom_obj;


                }).catch(function () {
                    alert('Unknown error')
                });


            }).catch(function () {
                alert('Unknown error')
            });


            axios({
                method: 'post',
                url: '/common_assets/config/lng_front.php',
                data: 'get_lang',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function (msg) {

                scope.lang.original_front = msg.data;


                axios({
                    method: 'get',
                    url: document.getElementById('lang_data_front').value
                }).then(function (msg) {


                    let custom_obj_front;

                    if(msg.data === ''){
                        custom_obj_front = {};
                    } else {

                        custom_obj_front = msg.data;
                    }

                    for(let key in scope.lang.original_front){
                        if(!custom_obj_front[key]) custom_obj_front[key] = '';
                    }

                    scope.lang.custom_front = custom_obj_front;


                }).catch(function () {
                    alert('Unknown error')
                });


            }).catch(function () {
                alert('Unknown error')
            });




        },
        created() {

        },
        methods: {
            get_visible: function (index) {
              if(this.not_translated){

                  return this.lang.custom[index] == ''

              } else {
                  return true;
              }
            },
            change_tab: function (t) {
                this.active_tab = t;
            },
            show_success: function () {
                let scope = this;
                this.show_success_message = true;
                setTimeout(function () {
                    scope.show_success_message = false;
                    location.reload();
                },1000)
            },
            check_form: function (e) {
                e.preventDefault();

                let scope = this;

                this.errors = [];


                if (!this.errors.length) {



                    let formData = new FormData();
                    formData.append('data', JSON.stringify(this.lang.custom) );
                    formData.append('data_frontend', JSON.stringify(this.lang.custom_front) );
                    formData.append('use_custom_lang', JSON.stringify(this.use_custom_lang) );

                    axios.post(this.$el.dataset.action,
                        formData,
                        {
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            }
                        }
                    ).then(function (msg) {



                        if(msg.data.error) {
                            for (let i = 0; i < msg.data.error.length; i++){
                                scope.errors.push(msg.data.error[i])
                            }
                        }

                        if(msg.data.success){
                            scope.show_success();
                        }

                    }).catch(function () {
                        alert('Unknown error')
                    });
                }

                return false;

            },
            parse_csv: function (e) {
                let scope = this;
                Papa.parse(document.getElementById('csv_file').files[0], {
                    complete: function(results) {
                        console.log(results);

                        Object.keys(scope.lang.original_front).forEach(function (k) {
                            for (let i = 0; i < results.data.length; i++){


                                if(scope.lang.original_front[k] == results.data[i][0]){
                                    scope.lang.custom_front[k] = results.data[i][1]
                                }
                            }
                        })


                    }
                });
            },
            export_xls: function (type) {
                let scope = this;

                workbook = new ExcelJS.Workbook();
                workbook.creator = "bplanner";
                workbook.lastModifiedBy = "bplanner";
                workbook.created = new Date();
                workbook.modified = new Date();
                let sheet_back = workbook.addWorksheet('Язык личного кабинета');
                let sheet_front = workbook.addWorksheet('Язык конструктора');

                Object.keys(scope.lang.original).forEach(function (k) {
                    sheet_back.addRow([
                        k,
                        scope.lang.original[k],
                        scope.lang.custom[k]
                    ])
                })

                Object.keys(scope.lang.original_front).forEach(function (k) {
                    sheet_front.addRow([
                        k,
                        scope.lang.original_front[k],
                        scope.lang.custom_front[k]
                    ])
                })


                workbook.xlsx.writeBuffer().then(function (data) {
                    var blob = new Blob([data], {type: "application/xlsx"});
                    var downloadUrl = window.URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = downloadUrl;
                    a.download = 'language.xlsx';
                    document.body.appendChild(a);
                    a.click();
                });

            },
            import_xls: function () {
                const wb = new ExcelJS.Workbook();
                const reader = new FileReader()
                let scope = this;
                reader.readAsArrayBuffer(this.$refs.imp.files[0])
                reader.onload = () => {
                    const buffer = reader.result;
                    wb.xlsx.load(buffer).then(workbook => {
                        console.log(workbook, 'workbook instance')
                        workbook.eachSheet((sheet, id) => {
                            console.log(sheet)
                            sheet.eachRow((row, rowIndex) => {

                                if(id == 1){
                                    scope.lang.custom[row.values[1]] = row.values[3]
                                } else {
                                    scope.lang.custom_front[row.values[1]] = row.values[3]
                                }

                            })
                        })
                    })
                }
            }
        }
    });

});