
<form id="sub_form" @submit="check_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('furniture/save_data/') ?>">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Варианты фурнитуры</h4>
        </div>
        <div class="panel-body">

            <div v-for="item in items">

                <div class="panel panel-default panel-body sizes_row">
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                        <label><?php echo $lang_arr['name']?></label>
                        <input class="form-control" v-model="item.name" type="text">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                        <label><?php echo $lang_arr['code']?></label>
                        <input class="form-control" v-model="item.code" type="text">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="form-group">
                        <label>Цена за единицу</label>
                        <input class="form-control" v-model="item.price" type="text">
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Краткое описание</label>
                            <textarea v-model="item.description" class="form-control" rows="5"></textarea>
                        </div>
                    </div>
                    <span v-on:click="remove_item(item.id)" class="glyphicon glyphicon-trash remove_panel"></span>
                </div>

            </div>


            <div v-on:click="add_item()" class="btn btn-primary" href="#">Добавить</div>


        </div>
    </div>

    <input class="btn btn-success" type="submit" name="submit" value="<?php echo $lang_arr['save']?>"/>
    <a class="btn btn-danger" href="<?php echo site_url('templates/index/') ?>"><?php echo $lang_arr['cancel']?></a>
</form>

<style>
    .remove_panel{
        position: absolute;
        top:10px;
        right: 10px;
        color: #ff0000;
        cursor: pointer;
    }

    .sizes_row{
        position: relative;
        padding-top: 10px;
        padding-bottom: 10px;
        margin-bottom: 10px!important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


<script>
    $('#document').ready(function () {

        let id = 0;

        const app = new Vue({
            el: '#sub_form',
            data: {
                items:[],
                success: "<?php echo site_url('templates/index/') ?>",
                errors: [],
                name: null,
                icon: null,
                active: "1",
                project: null
            },
            methods: {
                check_form: function (e) {

                    let scope = this;

                    this.errors = [];

                    if (!this.name) {
                        this.errors.push('<?php echo $lang_arr['project_name_error']?>');
                    }
                    if (!this.icon) {
                        this.errors.push('<?php echo $lang_arr['project_icon_error']?>');
                    } else{
                        if(this.$refs.icon.files[0].size > 100 * 1024) this.errors.push('<?php echo $lang_arr['too_big_icon']?>');
                    }

                    if (!this.project) {
                        this.errors.push('<?php echo $lang_arr['no_project_file']?>');
                    } else {
                        if(get_file_extension(this.project) !== 'dbs') this.errors.push('<?php echo $lang_arr['wrong_file_extension']?>');
                    }

                    if (!this.errors.length) {
                        let formData = new FormData();
                        formData.append('icon', this.$refs.icon.files[0]);
                        formData.append('project', this.$refs.project.files[0]);

                        formData.append('name', this.name);
                        formData.append('active', this.active);

                        axios.post(this.$el.action,
                            formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        ).then(function (msg) {

                            if(msg.data.error) {
                                for (let i = 0; i < msg.data.error.length; i++){
                                    scope.errors.push(msg.data.error[i])
                                }
                            }

                            if(msg.data.success){
                                window.location.href = scope.success;
                            }

                        }).catch(function () {
                            alert('Unknown error')
                        });
                    }

                    e.preventDefault();
                    return false;

                },
                add_item: function () {
                    this.items.push({
                        id: id++,
                        name: null,
                        code: null,
                        price: null,
                        description: null
                    })

                },
                remove_item: function (id) {

                    this.items = this.items.filter(items => items.id !== id)


                },
                check_item: function () {
                    console.log(this.items)
                }
            }
        })

    })




</script>