<div class="col-sm-12 col-md-12" id="content">
    <h3>Добавить категорию фурнитуры</h3>


    <form class="" method="post" accept-charset="UTF-8" action="<?php echo site_url('furniture/categories_add/') ?>">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['name']?>*</label>
                    <input type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                </div>

            </div>
        </div>



        <input class="btn btn-success" type="submit" name="submit" value="<?php echo $lang_arr['add']?>"/>
        <a class="btn btn-danger" href="<?php echo site_url('furniture/categories_index/') ?>"><?php echo $lang_arr['cancel']?></a>
    </form>

</div>

<script>

</script>