<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['edit_category']?></h2>
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


<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><?php echo $lang_arr['basic_params']?></h5>
                </div>
                <div class="ibox-content">

	                <?php echo validation_errors(); ?>


                    <form class="" method="post" accept-charset="UTF-8" action="<?php echo site_url('module_sets/categories_edit/'.$set['id'] .'/'.$item['id']) ?>">
                        <div class="form-group">
                            <label for="name"><?php echo $lang_arr['name']?>*</label>
                            <input value="<?php echo $item['name']?>" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                        </div>

                        <div class="form-group" >
                            <label for="parent"><?php echo $lang_arr['parent_category']?></label>
                            <select class="form-control" name="parent" id="parent">
                                <option  value=""><?php echo $lang_arr['no']?></option>
			                    <?php foreach ($categories as $cat):?>
				                    <?php if($cat['parent'] == 0): ?>
                                        <option <?php if($item['parent'] == $cat['id']) echo 'selected'?> value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
				                    <?php endif;?>
			                    <?php endforeach;?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="active"><?php echo $lang_arr['active']?></label>
                            <select class="form-control" name="active" id="active">
                                <option <?php if($item['active'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                <option <?php if($item['active'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                            </select>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('module_sets/categories_index/'.$set['id']) ?>"><?php echo $lang_arr['cancel']?></a>
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    $('#parent').selectize({
        create: false
    });
</script>