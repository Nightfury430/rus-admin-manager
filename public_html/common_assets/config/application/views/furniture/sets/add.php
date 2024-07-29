<div class="col-sm-12 col-md-12" id="content" xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <h3>Добавить вариант фурнитуры</h3>


    <form id="sub_form" @submit="check_form" class="add_item" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('furniture/sets_add/') ?>">

        <div v-if="errors.length" v-cloak id="errors" class="alert alert-danger error_msg"  >
            <ul>
                <li v-for="error in errors">
                    {{ error }}
                </li>
            </ul>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['name']?></label>
                    <input v-model="name" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                </div>


                <div v-for="cat in categories">
                    <div class="form-group">
                        <label>{{cat.name}}</label>
                        <select v-model="cat.selected_item" class="form-control">
                            <option value="0">---Выберите элемент фурнитуры---</option>
                            <option v-for="item in cat.items" v-bind:value="item.id">{{item.name}}</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>






        <input class="btn btn-success" type="submit" value="<?php echo $lang_arr['add']?>"/>
        <a class="btn btn-danger" href="<?php echo site_url('furniture/sets_index/') ?>"><?php echo $lang_arr['cancel']?></a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>



    document.addEventListener('DOMContentLoaded', function(){

        items =  <?php echo json_encode($items) ?>;
        categories = <?php echo json_encode($categories) ?>;


        for (let c = 0; c < categories.length; c++){
            categories[c].selected_item = 0;
            if(!categories[c].items) categories[c].items = [];
            for (let i = 0; i < items.length; i++){
                if(items[i].category === categories[c].id){
                    categories[c].items.push(items[i])
                }
            }
        }





        const app = new Vue({
            el: '#sub_form',
            data: {
                categories: categories,
                success: "<?php echo site_url('furniture/sets_index/') ?>",
                errors: [],
                name: null,
            },
            methods: {
                check_form: function (e) {

                    let scope = this;

                    this.errors = [];

                    if (!this.name) {
                        this.errors.push('Название не может быть пустым.');
                    }

                    for (let i = 0; i < this.categories.length; i++){
                        if(this.categories[i].selected_item === 0){
                            this.errors.push(this.categories[i].name + ' не выбран.');
                        }
                    }




                    if (!this.errors.length) {
                        let formData = new FormData();

                        let data =[];

                        for (let i = 0; i < this.categories.length; i++){
                            data.push({
                                id: this.categories[i].id,
                                name: this.categories[i].name,
                                selected: this.categories[i].selected_item
                            })
                        }

                        formData.append('name', this.name);
                        formData.append('data', JSON.stringify(data));

                        axios.post(this.$el.action,
                            formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        ).then(function (msg) {

                            console.log(msg.data)

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


                }
            }
        })


    })


</script>

<style>

    .sizes_row{
        position: relative;
        padding-top: 30px;
        padding-bottom: 10px;
        margin-bottom: 10px!important;
    }

    .remove_panel{
        position: absolute;
        top:10px;
        right: 10px;
        color: #ff0000;
        cursor: pointer;
    }

    .texture_params{
        display: none;
    }

    .map_input{
        display: block;
        width: 100%;
        position: relative;
    }

    .remove_map, .remove_model{
        position: absolute;
        right: 0;
        bottom:0;
        color: #ff0000;
        display: none;
        cursor: pointer;
    }

    .option.top_cat{
        font-weight: bold;
    }

    .option.sub_cat{
        padding-left: 40px;
    }

    .sp-input{
        background: #ffffff;
    }

</style>