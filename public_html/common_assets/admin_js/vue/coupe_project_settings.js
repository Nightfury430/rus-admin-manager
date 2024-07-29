document.addEventListener('DOMContentLoaded', function () {

    app = new Vue({
        el: '#sub_form',
        components: {
            Multiselect: window.VueMultiselect.default
        },
        data: {
            errors: [],
            show_success_message:false,
            available_materials_corpus_value: [],
            available_materials_corpus_options: [],
            default_corpus_material_value: null,
            default_corpus_material_options:[],

            default_back_wall_material_options:[],
            default_back_wall_material_value:{},

            available_materials_profile_value: [],
            available_materials_profile_options: [],
            default_profile_material_value: null,
            default_profile_material_options:[],

            available_materials_doors_value: [],
            available_materials_doors_options: [],

            prices:{
                corpus: {},
                profiles:{},
                doors: {},
                back_wall: 0,
                rail: 0,
                locker: 0
            },

            tree_hash:null,
            materials:[],
            categories:[],

            current_settings: null

        },
        created() {

            this.current_settings = JSON.parse(document.getElementById('settings_json').value)

            this.current_settings.available_materials_corpus = JSON.parse(this.current_settings.available_materials_corpus)
            this.current_settings.available_materials_profile = JSON.parse(this.current_settings.available_materials_profile)
            this.current_settings.available_materials_doors = JSON.parse(this.current_settings.available_materials_doors)


            this.materials = JSON.parse(document.getElementById('materials_json').value);
            this.categories = JSON.parse(document.getElementById('categories_json').value);

            this.tree_hash = build_materials_tree({
                categories: this.categories,
                items: this.materials,
            });

            this.available_materials_corpus_options = this.categories.filter(val => val.parent === "0");
            if(this.current_settings.available_materials_corpus === null) this.current_settings.available_materials_corpus = [];

            for (let i = 0; i < this.current_settings.available_materials_corpus.length; i++){

                if(this.tree_hash.categories[this.current_settings.available_materials_corpus[i]])
                this.available_materials_corpus_value.push(this.tree_hash.categories[this.current_settings.available_materials_corpus[i]])
            }
            this.default_corpus_material_options = this.available_materials_corpus_value;

            if(this.current_settings.default_material_corpus !== null) this.default_corpus_material_value = this.tree_hash.items[this.current_settings.default_material_corpus];




            this.available_materials_profile_options = this.categories.filter(val => val.parent === "0");
            if(this.current_settings.available_materials_profile === null) this.current_settings.available_materials_profile = [];



            for (let i = 0; i < this.current_settings.available_materials_profile.length; i++){
                if(this.tree_hash.categories[this.current_settings.available_materials_profile[i]])
                this.available_materials_profile_value.push(this.tree_hash.categories[this.current_settings.available_materials_profile[i]])
            }
            this.default_profile_material_options = this.available_materials_profile_value;

            if(this.current_settings.default_material_profile !== null) this.default_profile_material_value = this.tree_hash.items[this.current_settings.default_material_profile];



            this.available_materials_doors_options = this.categories.filter(val => val.parent === "0");
            if(this.current_settings.available_materials_doors === null) this.current_settings.available_materials_doors = [];



            for (let i = 0; i < this.current_settings.available_materials_doors.length; i++){
                if(this.tree_hash.categories[this.current_settings.available_materials_doors[i]])
                this.available_materials_doors_value.push(this.tree_hash.categories[this.current_settings.available_materials_doors[i]])
            }


            this.default_back_wall_material_options = this.materials;
            if(this.current_settings.default_back_wall_material !== null){
                if(this.tree_hash.items[this.current_settings.default_back_wall_material]){
                    this.default_back_wall_material_value = this.tree_hash.items[this.current_settings.default_back_wall_material];
                } else {
                    this.default_back_wall_material_value = {};
                }

            }

            if(this.current_settings.prices){
                Vue.set(this, 'prices', JSON.parse(this.current_settings.prices))
            }

            let karr = ['corpus', 'profiles', 'doors']
            let karr_keys = {
                'corpus': this.available_materials_corpus_value,
                'profiles': this.available_materials_profile_value,
                'doors': this.available_materials_doors_value
            };

            for (let k = 0; k < karr.length; k++){
                if(Object.keys(this.prices[karr[k]]).length < 1){
                    let nval = karr_keys[karr[k]];




                    for (let i = 0; i < nval.length; i++){
                        let cat = nval[i]
                        this.prices[karr[k]][cat.id] = 0
                        for (let s = 0; s < cat.categories.length; s++){
                            let s_cat = cat.categories[s];
                            this.prices[karr[k]][s_cat.id] = 0
                        }
                    }
                }
            }




        },
        methods: {
            available_materials_corpus_change: function (val) {
                this.default_corpus_material_options = this.available_materials_corpus_value
                this.default_corpus_material_value = null
                let karr = 'corpus'
                for (let i = 0; i < val.length; i++){
                    let cat = val[i]
                    if(this.prices[karr][cat.id] == undefined){
                        this.prices[karr][cat.id] = 0
                        for (let s = 0; s < cat.categories.length; s++){
                            let s_cat = cat.categories[s];
                            this.prices[karr][s_cat.id] = 0
                        }
                    }
                }
            },
            available_materials_profile_change: function (val) {
                this.default_profile_material_options = this.available_materials_profile_value
                this.default_profile_material_value = null
                let karr = 'profiles'
                for (let i = 0; i < val.length; i++){
                    let cat = val[i]
                    if(this.prices[karr][cat.id] == undefined){
                        this.prices[karr][cat.id] = 0
                        for (let s = 0; s < cat.categories.length; s++){
                            let s_cat = cat.categories[s];
                            this.prices[karr][s_cat.id] = 0
                        }
                    }
                }
            },
            available_materials_doors_change: function(val){
                let karr = 'doors'
                for (let i = 0; i < val.length; i++){
                    let cat = val[i]
                    if(this.prices[karr][cat.id] == undefined){
                        this.prices[karr][cat.id] = 0
                        for (let s = 0; s < cat.categories.length; s++){
                            let s_cat = cat.categories[s];
                            this.prices[karr][s_cat.id] = 0
                        }
                    }
                }
            },
            show_success: function () {
                let scope = this;
                this.show_success_message = true;
                setTimeout(function () {
                    scope.show_success_message = false;
                },1000)
            },
            check_form: function (e) {
                e.preventDefault();

                let scope = this;

                this.errors = [];

                if(!this.available_materials_corpus_value.length){
                    this.errors.push('Не выбраны доступные материалы корпуса');
                }

                if(!this.default_corpus_material_value){
                    this.errors.push('Не выбран материал корпуса по умолчанию');
                }

                // if(!this.available_materials_profile_value.length){
                //     this.errors.push('Не выбраны доступные материалы профиля');
                // }

                // if(!this.default_profile_material_value){
                //     this.errors.push('Не выбран материал профиля по умолчанию');
                // }

                if(!this.available_materials_doors_value.length){
                    this.errors.push('Не выбраны доступные материалы дверей');
                }

                // if(!this.default_back_wall_material_value.id){
                //     this.errors.push('Не выбран материал задней стенки');
                // }





                if (!this.errors.length) {

                    let karr = ['corpus', 'profiles', 'doors']
                    let karr_keys = {
                        'corpus': this.available_materials_corpus_value,
                        'profiles': this.available_materials_profile_value,
                        'doors': this.available_materials_doors_value
                    };

                    for (let k = 0; k < karr.length; k++){
                        let nval = karr_keys[karr[k]];
                        Object.keys(this.prices[karr[k]]).forEach(function (key) {
                            let flag = 0;
                            for (let i = 0; i < nval.length; i++){
                                let cat = nval[i];
                                if(key == cat.id){
                                    flag = 1
                                }

                                if(cat.categories){
                                    for (let s = 0; s < cat.categories.length; s++){
                                        let s_cat = cat.categories[s];
                                        if(key == s_cat.id){
                                            flag = 1
                                        }
                                    }
                                }


                            }

                            if(flag == 0) delete scope.prices[karr[k]][key]

                        })

                    }

                    let formData = new FormData();
                    formData.append('available_materials_corpus', JSON.stringify(this.available_materials_corpus_value.map(obj=>obj.id)) );
                    formData.append('default_material_corpus', this.default_corpus_material_value.id);
                    // formData.append('available_materials_profile', JSON.stringify(this.available_materials_profile_value.map(obj=>obj.id)) );
                    // formData.append('default_material_profile', this.default_profile_material_value.id );
                    formData.append('available_materials_doors', JSON.stringify(this.available_materials_doors_value.map(obj=>obj.id)) );
                    formData.append('default_back_wall_material', this.default_back_wall_material_value.id );
                    formData.append('prices', JSON.stringify(this.prices) );



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
                            toastr.success('Настройки успешно сохранены')
                        }

                    }).catch(function () {
                        alert('Unknown error')
                    });
                }

                return false;

            }
        }
    });

    function build_materials_tree(catalog) {

        let data = catalog;


        let cat_h = get_hash(data.categories);

        let it_h = get_hash(data.items)



        for (let i = 0; i < data.items.length; i++){
            data.items[i].params = JSON.parse(data.items[i].params);
            data.items[i].category = JSON.parse(data.items[i].category)
            console.log(data.items[i].category)

            if(Array.isArray(data.items[i].category)){
                console.log('arr')
                for (let c = 0; c < data.items[i].category.length; c++){



                    if ( data.items[i].category[c] in cat_h)  {

                        if(data.items[i].params.params.map != null){
                            if(data.items[i].params.params.map.indexOf('common_assets') < 0){
                                data.items[i].params.params.map = document.getElementById('const_path').value + data.items[i].params.params.map;
                            }
                        }

                        if(!cat_h[data.items[i].category[c]].items) cat_h[data.items[i].category[c]].items = [];

                        if(cat_h[data.items[i].category[c]].parent !== "0"){
                            if(!cat_h[cat_h[data.items[i].category[c]].parent].items) cat_h[cat_h[data.items[i].category[c]].parent].items = [];
                            cat_h[cat_h[data.items[i].category[c]].parent].items.push(data.items[i])
                        }

                        cat_h[data.items[i].category[c]].items.push(data.items[i])

                        data.items[i].cat_name = cat_h[data.items[i].category[c]].name;

                    }

                }
            } else {
                if ( data.items[i].category in cat_h)  {

                    if(data.items[i].params.params.map != null){
                        if(data.items[i].params.params.map.indexOf('common_assets') < 0){
                            data.items[i].params.params.map = document.getElementById('const_path').value + data.items[i].params.params.map;
                        }
                    }

                    if(!cat_h[data.items[i].category].items) cat_h[data.items[i].category].items = [];

                    if(cat_h[data.items[i].category].parent !== "0"){
                        if(!cat_h[cat_h[data.items[i].category].parent].items) cat_h[cat_h[data.items[i].category].parent].items = [];
                        cat_h[cat_h[data.items[i].category].parent].items.push(data.items[i])
                    }

                    cat_h[data.items[i].category].items.push(data.items[i])

                    data.items[i].cat_name = cat_h[data.items[i].category].name;

                }
            }


        }


        let tree = data.categories.filter(function (obj) {
            if(obj.parent === '0') obj.categories = [];
            return (obj.parent === '0')
        });

        let th = get_hash(tree);

        for (let i = 0; i < data.categories.length; i++){

            if ( data.categories[i].parent in th)  {

                th[data.categories[i].parent].categories.push(data.categories[i])

            }
        }

        return {
            categories: cat_h,
            items: it_h
        };
    }

    function get_hash(data) {
        return data.reduce(function(map, obj) {
            map[obj.id] = obj;
            return map;
        }, {});
    }
});


