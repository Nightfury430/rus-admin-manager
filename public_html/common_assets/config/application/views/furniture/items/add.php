<div class="col-sm-12 col-md-12" id="content">
    <h3>Добавить модель фурнитуры</h3>



    <form class="add_item" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('furniture/items_add/') ?>">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['name']?></label>
                    <input type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                </div>

                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['code']?></label>
                    <input type="text" class="form-control" name="code" placeholder="<?php echo $lang_arr['code']?>">
                </div>


                <div class="form-group" >
                    <label for="category"><?php echo $lang_arr['category']?></label>
                    <select class="form-control" name="category" id="category">
                        <option data-data='{"class": "top_cat"}' value="0"><?php echo $lang_arr['no']?></option>
                        <?php foreach ($categories as $cat):?>
                                <option data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price"><?php echo $lang_arr['price']?></label>
                    <input type="text" class="form-control" name="price" placeholder="<?php echo $lang_arr['price']?>">
                </div>

            </div>
        </div>





        <input class="btn btn-success" type="submit" value="<?php echo $lang_arr['add']?>"/>
        <a class="btn btn-danger" href="<?php echo site_url('furniture/items_index/') ?>"><?php echo $lang_arr['cancel']?></a>
    </form>
</div>


<script>

    $(document).ready(function () {




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