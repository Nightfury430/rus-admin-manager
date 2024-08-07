<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><?php echo $lang_arr['basic_params']?></h5>
                </div>
                <div class="ibox-content">

					<?php echo validation_errors(); ?>

                    <form class="" method="post" accept-charset="UTF-8" action="<?php echo site_url('module_sets/sets_edit/'.$item['id']) ?>">

                        <div class="form-group">
                            <label for="name"><?php echo $lang_arr['name']?>*</label>
                            <input type="text" value="<?php echo $item['name']?>" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('module_sets/sets_index/') ?>"><?php echo $lang_arr['cancel']?></a>
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



