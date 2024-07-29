<div class="col-sm-12 col-md-12" id="content">
    <h3>Редактировать категорию фурнитуры</h3>

    <form class="" method="post" accept-charset="UTF-8" action="<?php echo site_url('furniture/categories_edit/'.$category['id']) ?>">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['name']?>*</label>
                    <input value="<?php echo $category['name']?>" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                </div>

                <?php if($has_children == 0):?>
                    <div class="form-group" >
                        <label for="parent"><?php echo $lang_arr['parent_category']?></label>
                        <select class="form-control" name="parent" id="parent">
                            <option  value="0"><?php echo $lang_arr['no']?></option>
                            <?php foreach ($categories as $cat):?>
                                <?php if($cat['parent'] == 0 && $category['id'] != $cat['id'] )  : ?>
                                    <option <?php if($category['parent'] == $cat['id']){echo 'selected';}  ?> value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
                                <?php endif;?>
                            <?php endforeach;?>
                        </select>
                    </div>
                <?php else:?>
                    <div class="form-group" >
                        <label for="parent"><?php echo $lang_arr['parent_category']?></label>
                        <select disabled class="form-control"  id="parent">
                            <option  value="0"><?php echo $lang_arr['no']?></option>
                        </select>
                        <input type="hidden" name="parent" value="0">
                        <span class="help-block"><?php echo $lang_arr['category_parent_edit_warning']?></span>
                    </div>
                <?php endif;?>

                <div class="form-group">
                    <label for="active"><?php echo $lang_arr['active']?></label>
                    <select class="form-control" name="active" id="active">
                        <option <?php if($category['active'] == 1){echo 'selected';}?> value="1"><?php echo $lang_arr['yes']?></option>
                        <option <?php if($category['active'] == 0){echo 'selected';}?> value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>
        </div>




        <input class="btn btn-success" type="submit" name="submit" value="<?php echo $lang_arr['save']?>"/>
        <a class="btn btn-danger" href="<?php echo site_url('furniture/categories_index/') ?>"><?php echo $lang_arr['cancel']?></a>
    </form>
</div>

<script>
    $('#parent').selectize({
        create: false
    });
</script>