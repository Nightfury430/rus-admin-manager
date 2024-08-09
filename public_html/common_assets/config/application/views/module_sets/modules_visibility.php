<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Модули</h2>
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a href="index.html">Home</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a>Tables</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item active">-->
        <!--                <strong>Code Editor</strong>-->
        <!--            </li>-->
        <!--        </ol>-->
    </div>
    <div class="col-lg-2">

    </div>
</div>

<form id="sub_form">
    <div v-cloak class="wrapper wrapper-content  animated fadeInRight">


        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-4">

                                <ul class="set_list">
                                    <li :class="{'selected': set.id==selected_set}"  v-for="set in sets">
                                        <a @click.prevent="select_set(set)">{{set.name}}</a>
                                    </li>
                                </ul>


                            </div>
                            <div class="col-8">

                                <div v-if="modules.length">
                                    <div class="row">
                                        <div class="col-3">
                                            Поиск по id
                                        </div>
                                        <div class="col-6">
                                            <input v-model="search" type="text" class="form-control">
                                        </div>
                                        <div class="col-3">
                                            <div @click="submit()" class="btn btn-primary">Сохранить</div>
                                        </div>
                                    </div>

                                    <table class="table table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <td>ID</td>
                                            <td>Иконка</td>
                                            <td>Размеры</td>
                                            <td>Категория</td>
                                            <td>Видимость</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="module in computed_modules">
                                            <td>{{module.id}}</td>
                                            <td><img style="max-width: 80px" class="img-fluid" :src="(module.icon)" alt=""></td>
                                            <td><p v-for="variant in module.params.variants">{{variant.width}}x{{variant.height}}x{{variant.depth}}</p></td>
                                            <td>
                                                <span v-if="module.category == 0">Нет</span>
                                                <span v-else>
                                                    <span v-if="categories_hash[module.category] && categories_hash[module.category].parent && categories_hash[module.category].parent != 0">
                                                        {{categories_hash[categories_hash[module.category].parent].name}} /
                                                     </span>
                                                    <span v-if="categories_hash[module.category]">{{categories_hash[module.category].name}}</span><span v-else>Нет</span>
                                                    <span v-if="!categories_hash[module.category]">Нет</span>
                                                </span>

                                            </td>
                                            <td><i @click="change_active(module)" :class="get_eye_class(module)" class="fa fa-eye btn btn-outline"></i></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>




                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>


    </div>
</form>

<style>
    .set_list{
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .set_list li{
        margin-bottom: 10px;
    }

</style>

<script src="/common_assets/libs/vue.min.js"></script>


<script>


    document.addEventListener('Glob_ready', async function () {


        init_vue();
    })

    function init_vue() {
        app = new Vue({
            el: '#sub_form',
            data: {
                sets: [],
                modules: [],
                categories: [],
                selected_set: 0,
                search: '',
                categories_hash:{}
            },
            computed: {
                computed_modules: function () {
                    if(this.search == '') return this.modules;

                    let result = [];
                    for (let i = 0; i < this.modules.length; i++){

                        if( (this.modules[i].id + '').indexOf(this.search) > -1) {
                            result.push(this.modules[i])
                        }
                    }

                    return result;

                }
            },
            created: async function () {
                this.sets = await promise_request(glob.acc_url + 'data/sets.json')
            },

            methods: {
                select_set: async function (set) {

                    let date_time = new Date().getTime();
                    let date_timestamp = '?' + date_time;

                    this.selected_set = set.id;
                    let modules_data = await promise_request(glob.acc_url + 'data/module_sets/' + set.id + '.json' + date_timestamp)
                    Vue.set(this, 'modules', modules_data.items)
                    Vue.set(this, 'categories', modules_data.categories)
                    Vue.set(this, 'categories_hash', get_hash(modules_data.categories))


                },
                change_active: function (item) {
                    if(item.active == 0){
                        item.active = 1
                    } else {
                        item.active = 0
                    }
                },
                get_eye_class: function (item) {
                    return item.active != 0 ? ['fa-eye', 'btn-primary'] : ['fa-solid fa-eye-low-vision', 'btn-default']
                },
                submit: async function () {

                    let data = {}
                    data.id = this.selected_set;
                    data.set_data = {
                        categories: copy_object(this.categories),
                        items: copy_object(this.modules),
                    }
                    data.hash = {}

                    for (let i = 0; i < this.modules.length; i++){
                        data.hash[this.modules[i].id] = this.modules[i].active;
                    }

                    console.log(data)



                    let form_data = new FormData();
                    form_data.append('data', JSON.stringify(data))

                    let result = await promise_request_post(glob.base_url + '/module_sets/save_modules_visibility', form_data)

                    console.log(result);

                    toastr.success(glob.lang['success'])


                }
            }
        });
    }


</script>

