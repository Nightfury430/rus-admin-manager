<div class="col-sm-12 col-md-12" id="content">
    <h3>Редактировать фасад</h3>

    <?php echo validation_errors(); ?>

    <?php
    $full = json_decode($item['full']);
    $window = json_decode($item['window']);
    $frame = json_decode($item['frame']);
    $radius_full = json_decode($item['radius_full']);
    $radius_window = json_decode($item['radius_window']);
    $radius_frame = json_decode($item['radius_frame']);

    if(count($full) < 1){ $full = array(); }
    if(count($window) < 1){ $window = array(); }
    if(count($frame) < 1){ $frame = array(); }
    if(count($radius_full) < 1){ $radius_full = array(); }
    if(count($radius_window) < 1){ $radius_window = array(); }
    if(count($radius_frame) < 1){ $radius_frame = array(); }
    ?>


    <form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('facades/items_edit/'.$item['id']) ?>">


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['name']?>*</label>
                    <input value="<?php echo $item['name']?>" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                </div>

                <div class="form-group" >
                    <label for="category"><?php echo $lang_arr['category']?></label>
                    <select class="form-control" name="category" id="category">
                        <option data-data='{"class": "top_cat"}' value="0"><?php echo $lang_arr['no']?></option>
                        <?php foreach ($categories as $cat):?>
                            <?php if($cat['parent'] == 0): ?>
                                <option <?php if($item['category']==$cat['id'])echo "selected";?> data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
                                <?php if (isset($cat['children'])):?>
                                    <?php foreach ($cat['children'] as $child):?>
                                        <option <?php if($item['category']==$child['id'])echo "selected";?>  data-data='{"class": "sub_cat"}' value="<?php echo $child['id']?>"><?php echo $child['name']?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="active"><?php echo $lang_arr['active']?></label>
                    <select class="form-control" name="active" id="active">
                        <option <?php if($item['active']==1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                        <option <?php if($item['active']==0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>

                <div class="form-group">
                    <div style="margin-bottom: 20px;">
                        <img style="max-width: 100px" src="<?php echo $this->config->item("const_path") . $item['icon']?>" alt="">
                    </div>
                    <label for="icon"><?php echo $lang_arr['change_icon']?></label>
                    <input type="file" name="icon" id="icon">

                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['models']?></h4>
            </div>
            <div class="panel-body models_list">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="full">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_full">
                                <?php echo $lang_arr['facade_full']?>
                            </a>
                        </div>
                        <div id="collapse_full" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <select class="hidden" multiple name="full_models_list[]" id="full_models_list">
                                    <?php foreach ($full as $key=>$facade):?>
                                        <option selected data-id="<?php echo ($key+1);?>" value="model_full_<?php echo $key+1;?>;min_width_full_<?php echo $key+1;?>;min_height_full_<?php echo $key+1;?>"></option>
                                    <?php endforeach;?>
                                </select>

                                <?php foreach ($full as $key=>$facade):?>
                                    <div class="panel panel-default panel-body model_panel">
                                        <span data-id="<?php echo $key+1;?>" class="glyphicon glyphicon-trash remove_panel"></span>

                                        <div class="row">
                                            <div style="margin-bottom: 10px;" class="col-xs-12">
                                                Модель:
                                                <?php
                                                $m = explode('/',$facade->model);
                                                echo end($m)
                                                ?>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="model_full_<?php echo $key+1;?>"><?php echo $lang_arr['change_model_file']?></label>
                                                <input type="file" name="model_full_<?php echo $key+1;?>" id="model_full_<?php echo $key+1;?>" accept=".fbx"/>
                                                <input type="hidden" name="current_model_full_<?php echo $key+1;?>" id="current_model_full_<?php echo $key+1;?>" value="<?php echo $facade->model?>">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="min_width_full_<?php echo $key+1;?>"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_width_full_<?php echo $key+1;?>" id="min_width_full_<?php echo $key+1;?>" value="<?php echo $facade->min_width?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-4">
                                                <label for="min_height_full_<?php echo $key+1;?>"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_height_full_<?php echo $key+1;?>" id="min_height_full_<?php echo $key+1;?>" value="<?php echo $facade->min_height?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label class="l_hack">1</label>
                                                <button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_full_<?php echo $key+1;?>">Тест модели</button>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach;?>

                                <button type="button" class="btn btn-sm btn-primary add_full add_model_button"><?php echo $lang_arr['add_model']?></button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="window">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_window">
                                <?php echo $lang_arr['facade_window']?>
                            </a>
                        </div>
                        <div id="collapse_window" class="panel-collapse collapse">
                            <div class="panel-body">
                                <select class="hidden" multiple name="window_models_list[]" id="window_models_list">
                                    <?php foreach ($window as $key=>$facade):?>
                                        <option selected data-id="<?php echo $key+1?>" value="model_window_<?php echo $key+1?>;min_width_window_<?php echo $key+1?>;min_height_window_<?php echo $key+1?>"></option>
                                    <?php endforeach;?>
                                </select>

                                <?php foreach ($window as $key=>$facade):?>

                                    <div class="panel panel-default panel-body model_panel">
                                        <span data-id="<?php echo $key+1?>" class="glyphicon glyphicon-trash remove_panel"></span>
                                        <div class="row">
                                            <div style="margin-bottom: 10px;" class="col-xs-12">
                                                Модель:
                                                <?php
                                                $m = explode('/',$facade->model);
                                                echo end($m)
                                                ?>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="model_window_<?php echo $key+1?>"><?php echo $lang_arr['change_model_file']?></label>
                                                <input type="file" name="model_window_<?php echo $key+1?>" id="model_window_<?php echo $key+1?>" accept=".fbx">
                                                <input type="hidden" name="current_model_window_<?php echo $key+1?>" id="current_model_window_<?php echo $key+1?>" value="<?php echo $facade->model?>">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="min_width_window_<?php echo $key+1?>"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_width_window_<?php echo $key+1?>" id="min_width_window_<?php echo $key+1?>" value="<?php echo $facade->min_width?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-4">
                                                <label for="min_height_window_<?php echo $key+1?>"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_height_window_<?php echo $key+1?>" id="min_height_window_<?php echo $key+1?>" value="<?php echo $facade->min_height?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label class="l_hack">1</label>
                                                <button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_window_<?php echo $key+1?>">Тест модели</button>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach;?>

                                <button type="button" class="btn btn-sm btn-primary add_window"><?php echo $lang_arr['add_model']?></button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="frame">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_frame">
                                <?php echo $lang_arr['facade_frame']?>
                            </a>
                        </div>
                        <div id="collapse_frame" class="panel-collapse collapse">
                            <div class="panel-body">
                                <select class="hidden" multiple name="frame_models_list[]" id="frame_models_list">
                                    <?php foreach ($frame as $key=>$facade):?>
                                        <option selected data-id="<?php echo $key+1?>" value="model_frame_<?php echo $key+1?>;min_width_frame_<?php echo $key+1?>;min_height_frame_<?php echo $key+1?>"></option>
                                    <?php endforeach;?>
                                </select>

                                <?php foreach ($frame as $key=>$facade):?>

                                    <div class="panel panel-default panel-body model_panel">
                                        <span data-id="<?php echo $key+1?>" class="glyphicon glyphicon-trash remove_panel"></span>
                                        <div class="row">
                                            <div style="margin-bottom: 10px;" class="col-xs-12">
                                                Модель:
                                                <?php
                                                $m = explode('/',$facade->model);
                                                echo end($m)
                                                ?>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="model_frame_<?php echo $key+1?>"><?php echo $lang_arr['change_model_file']?></label>
                                                <input type="file" name="model_frame_<?php echo $key+1?>" id="model_frame_<?php echo $key+1?>" accept=".fbx">
                                                <input type="hidden" name="current_model_frame_<?php echo $key+1?>" id="current_model_frame_<?php echo $key+1?>" value="<?php echo $facade->model?>">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="min_width_frame_<?php echo $key+1?>"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_width_frame_<?php echo $key+1?>" id="min_width_frame_<?php echo $key+1?>" value="<?php echo $facade->min_width?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-4">
                                                <label for="min_height_frame_<?php echo $key+1?>"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_height_frame_<?php echo $key+1?>" id="min_height_frame_<?php echo $key+1?>" value="<?php echo $facade->min_height?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label class="l_hack">1</label>
                                                <button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_frame_<?php echo $key+1?>">Тест модели</button>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach;?>

                                <button type="button" class="btn btn-sm btn-primary add_frame"><?php echo $lang_arr['add_model']?></button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="radius_full">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_radius_full">
                                <?php echo $lang_arr['facade_radius_full']?>
                            </a>
                        </div>
                        <div id="collapse_radius_full" class="panel-collapse collapse">
                            <div class="panel-body">
                                <select class="hidden" multiple name="radius_full_models_list[]" id="radius_full_models_list">
                                    <?php foreach ($radius_full as $key=>$facade):?>
                                        <option selected data-id="<?php echo $key+1?>" value="model_radius_full_<?php echo $key+1?>;min_width_radius_full_<?php echo $key+1?>;min_height_radius_full_<?php echo $key+1?>"></option>
                                    <?php endforeach;?>
                                </select>

                                <?php foreach ($radius_full as $key=>$facade):?>

                                    <div class="panel panel-default panel-body model_panel">
                                        <span data-id="<?php echo $key+1?>" class="glyphicon glyphicon-trash remove_panel"></span>
                                        <div class="row">
                                            <div style="margin-bottom: 10px;" class="col-xs-12">
                                                Модель:
                                                <?php
                                                $m = explode('/',$facade->model);
                                                echo end($m)
                                                ?>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="model_radius_full_<?php echo $key+1?>"><?php echo $lang_arr['change_model_file']?></label>
                                                <input type="file" name="model_radius_full_<?php echo $key+1?>" id="model_radius_full_<?php echo $key+1?>" accept=".fbx">
                                                <input type="hidden" name="current_model_radius_full_<?php echo $key+1?>" id="current_model_radius_full_<?php echo $key+1?>" value="<?php echo $facade->model?>">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="min_width_radius_full_<?php echo $key+1?>"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_width_radius_full_<?php echo $key+1?>" id="min_width_radius_full_<?php echo $key+1?>" value="<?php echo $facade->min_width?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-4">
                                                <label for="min_height_radius_full_<?php echo $key+1?>"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_height_radius_full_<?php echo $key+1?>" id="min_height_radius_full_<?php echo $key+1?>" value="<?php echo $facade->min_height?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label class="l_hack">1</label>
                                                <button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_radius_full_<?php echo $key+1?>">Тест модели</button>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach;?>

                                <button type="button" class="btn btn-sm btn-primary add_radius_full"><?php echo $lang_arr['add_model']?></button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="radius_window">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_radius_window">
                                <?php echo $lang_arr['facade_radius_window']?>
                            </a>
                        </div>
                        <div id="collapse_radius_window" class="panel-collapse collapse">
                            <div class="panel-body">
                                <select class="hidden" multiple name="radius_window_models_list[]" id="radius_window_models_list">
                                    <?php foreach ($radius_window as $key=>$facade):?>
                                        <option selected data-id="<?php echo $key+1?>" value="model_radius_window_<?php echo $key+1?>;min_width_radius_window_<?php echo $key+1?>;min_height_radius_window_<?php echo $key+1?>"></option>
                                    <?php endforeach;?>
                                </select>

                                <?php foreach ($radius_window as $key=>$facade):?>

                                    <div class="panel panel-default panel-body model_panel">
                                        <span data-id="<?php echo $key+1?>" class="glyphicon glyphicon-trash remove_panel"></span>
                                        <div class="row">
                                            <div style="margin-bottom: 10px;" class="col-xs-12">
                                                Модель:
                                                <?php
                                                $m = explode('/',$facade->model);
                                                echo end($m)
                                                ?>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="model_radius_window_<?php echo $key+1?>"><?php echo $lang_arr['change_model_file']?></label>
                                                <input type="file" name="model_radius_window_<?php echo $key+1?>" id="model_radius_window_<?php echo $key+1?>" accept=".fbx">
                                                <input type="hidden" name="current_model_radius_window_<?php echo $key+1?>" id="current_model_radius_window_<?php echo $key+1?>" value="<?php echo $facade->model?>">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="min_width_radius_window_<?php echo $key+1?>"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_width_radius_window_<?php echo $key+1?>" id="min_width_radius_window_<?php echo $key+1?>" value="<?php echo $facade->min_width?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-4">
                                                <label for="min_height_radius_window_<?php echo $key+1?>"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_height_radius_window_<?php echo $key+1?>" id="min_height_radius_window_<?php echo $key+1?>" value="<?php echo $facade->min_height?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label class="l_hack">1</label>
                                                <button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_radius_window_<?php echo $key+1?>">Тест модели</button>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach;?>

                                <button type="button" class="btn btn-sm btn-primary add_radius_window"><?php echo $lang_arr['add_model']?></button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="radius_frame">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_radius_frame">
                                <?php echo $lang_arr['facade_radius_frame']?>
                            </a>
                        </div>
                        <div id="collapse_radius_frame" class="panel-collapse collapse">
                            <div class="panel-body">
                                <select class="hidden" multiple name="radius_frame_models_list[]" id="radius_frame_models_list">
                                    <?php foreach ($radius_frame as $key=>$facade):?>
                                        <option selected data-id="<?php echo $key+1?>" value="model_radius_frame_<?php echo $key+1?>;min_width_radius_frame_<?php echo $key+1?>;min_height_radius_frame_<?php echo $key+1?>"></option>
                                    <?php endforeach;?>
                                </select>

                                <?php foreach ($radius_frame as $key=>$facade):?>

                                    <div class="panel panel-default panel-body model_panel">
                                        <span data-id="<?php echo $key+1?>" class="glyphicon glyphicon-trash remove_panel"></span>
                                        <div class="row">
                                            <div style="margin-bottom: 10px;" class="col-xs-12">
                                                Модель:
                                                <?php
                                                $m = explode('/',$facade->model);
                                                echo end($m)
                                                ?>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">

                                                <label for="model_radius_frame_<?php echo $key+1?>"><?php echo $lang_arr['change_model_file']?></label>
                                                <input type="file" name="model_radius_frame_<?php echo $key+1?>" id="model_radius_frame_<?php echo $key+1?>" accept=".fbx">
                                                <input type="hidden" name="current_model_radius_frame_<?php echo $key+1?>" id="current_model_radius_frame_<?php echo $key+1?>" value="<?php echo $facade->model?>">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-3">
                                                <label for="min_width_radius_frame_<?php echo $key+1?>"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_width_radius_frame_<?php echo $key+1?>" id="min_width_radius_frame_<?php echo $key+1?>" value="<?php echo $facade->min_width?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-4">
                                                <label for="min_height_radius_frame_<?php echo $key+1?>"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" name="min_height_radius_frame_<?php echo $key+1?>" id="min_height_radius_frame_<?php echo $key+1?>" value="<?php echo $facade->min_height?>" class="form-control">
                                            </div>
                                            <div class="form-group col-xs-2">
                                                <label class="l_hack">1</label>
                                                <button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_radius_frame_<?php echo $key+1?>">Тест модели</button>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach;?>

                                <button type="button" class="btn btn-sm btn-primary add_radius_frame"><?php echo $lang_arr['add_model']?></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $lang_arr['available_materials']?></div>
            <div class="panel-body materials_list">

                <div class="form-group">
                    <label for="materials"><?php echo $lang_arr['choose_categories']?></label>
                    <select multiple class="form-control" name="materials[]" id="materials">
                        <?php foreach ($materials as $cat): ?>
                            <?php if ($cat['parent'] == 0): ?>
                                <?php
                                $selected ='';
                                foreach (json_decode($item['materials']) as $sel_mat){
                                    if($sel_mat == $cat['id']){
                                        $selected = 'selected';
                                    }
                                }
                                ?>

                                <option <?php echo $selected?> value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>


        <input class="btn btn-success" type="submit" name="submit" value="<?php echo $lang_arr['save']?>"/>
        <a class="btn btn-danger" href="<?php echo site_url('facades/items_index/') ?>"><?php echo $lang_arr['cancel']?></a>
    </form>
</div>

<div class="three_modal_wrapper">
    <div class="three_modal">
        <span class="close_three_modal glyphicon glyphicon-remove"></span>
        <div id="three_viewport">
        </div>
    </div>
</div>

<style>

    .l_hack{
        opacity: 0;
    }

    .model_panel{
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

    .three_modal_wrapper{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(31, 31, 31, 0.81);
        z-index: 9999;
        display: none;
    }

    .three_modal{
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        background: #ffffff;
    }

    .close_three_modal{
        position: absolute;
        top:10px;
        right:10px;
        cursor: pointer;
    }

    #three_viewport{
        width: 100%;
        height: 100%;
    }

    .selectize-control.multi .selectize-input > div{
        background: #428bca;
        color: #ffffff;
        padding-left: 10px;
        padding-right: 10px;
    }


</style>

<script src="/common_assets/libs/three.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>


<script>
    $(document).ready(function () {


        $('.test_model').click(function () {
            var root_path = '<?php echo $this->config->item("const_path");?>';
            var cur_mod = $(this).parent().parent().parent().find('#current_'+ $(this).attr('data-target')).val();
            var full_path = root_path + cur_mod;

            var modal = $('.three_modal_wrapper');
            modal.fadeIn();

            var file = $(this).parent().parent().find('input[type="file"]');
            if(file.val() !== ''){

                var reader = new FileReader();
                reader.onload = function(event) {
                    init_three_test('three_viewport', event.target.result);
                };
                reader.readAsDataURL(file[0].files[0]);

            } else {
                init_three_test('three_viewport', full_path);
            }




            $('.close_three_modal').click(function () {
                modal.fadeOut();

                for (var i = scene.children.length - 1; i > 1; i--) {
                    if(scene.children[i].type === 'Mesh'){
                        scene.children[i].geometry.dispose();
                        scene.remove(scene.children[i]);
                    }
                }

                var gl = renderer.domElement.getContext('webgl');
                gl.getExtension('WEBGL_lose_context').loseContext();

                $('#three_viewport').html('');
                renderer.renderLists.dispose();


                window.removeEventListener('resize', onWindowResize, false);
            })
        });

        $('.remove_panel').click(function () {
            var select = $('#full_models_list');
            select.find('option[data-id="'+ $(this).attr('data-id') +'"]').remove();
            $(this).parent().remove();
        });

        $('#sub_form').submit(function (e) {
            $('.models_list input[type="file"]').each(function () {
                if($(this).val() === ''){
                    if( $(this).parent().find('input[type="hidden"]').length < 1 ){
                        $(this).parent().parent().parent().find('.remove_panel').click();
                    }
                }
            });

            return true;
        });

        var full_count = $('#collapse_full .panel-body .model_panel').length;
        var window_count = $('#collapse_window .panel-body .model_panel').length;
        var frame_count = $('#collapse_frame .panel-body .model_panel').length;
        var radius_full_count = $('#collapse_radius_full .panel-body .model_panel').length;
        var radius_window_count = $('#collapse_radius_window .panel-body .model_panel').length;
        var radius_frame_count = $('#collapse_radius_frame .panel-body .model_panel').length;

        $('.add_full').click(function () {
            full_count+=1;
            var panel = create_model_panel(full_count, 'full');
            panel.insertBefore($(this));

            var select = $('#full_models_list');
            select.append('<option data-id="'+ full_count +'" selected value="model_full_'+ full_count +';min_width_full_'+ full_count +';min_height_full_'+ full_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });

        });

        $('.add_window').click(function () {
            window_count+=1;
            var panel = create_model_panel(window_count, 'window');
            panel.insertBefore($(this));

            var select = $('#window_models_list');
            select.append('<option data-id="'+ window_count +'" selected value="model_window_'+ window_count +';min_width_window_'+ window_count +';min_height_window_'+ window_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_frame').click(function () {
            frame_count+=1;
            var panel = create_model_panel(frame_count, 'frame');
            panel.insertBefore($(this));

            var select = $('#frame_models_list');
            select.append('<option data-id="'+ frame_count +'" selected value="model_frame_'+ frame_count +';min_width_frame_'+ frame_count +';min_height_frame_'+ frame_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_radius_full').click(function () {
            radius_full_count+=1;
            var panel = create_model_panel(radius_full_count, 'radius_full');
            panel.insertBefore($(this));

            var select = $('#radius_full_models_list');
            select.append('<option data-id="'+ radius_full_count +'" selected value="model_radius_full_'+ radius_full_count +';min_width_radius_full_'+ radius_full_count +';min_height_radius_full_'+ radius_full_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_radius_window').click(function () {
            radius_window_count+=1;
            var panel = create_model_panel(radius_window_count, 'radius_window');
            panel.insertBefore($(this));

            var select = $('#radius_window_models_list');
            select.append('<option data-id="'+ radius_window_count +'" selected value="model_radius_window_'+ radius_window_count +';min_width_radius_window_'+ radius_window_count +';min_height_radius_window_'+ radius_window_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_radius_frame').click(function () {
            radius_frame_count+=1;
            var panel = create_model_panel(radius_frame_count, 'radius_frame');
            panel.insertBefore($(this));

            var select = $('#radius_frame_models_list');
            select.append('<option data-id="'+ radius_frame_count +'" selected value="model_radius_frame_'+ radius_frame_count +';min_width_radius_frame_'+ radius_frame_count +';min_height_radius_frame_'+ radius_frame_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });


        function create_model_panel(id, name){
            var panel = $('<div class="panel panel-default panel-body model_panel"></div>');
            var remove_panel = $('<span class="glyphicon glyphicon-trash remove_panel"></span>');
            var row = $('<div class="row"></div>');

            var file_wrapper = $('<div class="form-group col-xs-12 col-sm-3"></div>');
            var file_label = $('<label for="model_'+ name +'_'+ id +'"><?php echo $lang_arr['choose_file']?></label>');
            var file_input = $('<input type="file" name="model_'+ name +'_'+ id +'" id="model_'+ name +'_'+ id +'" accept=".fbx">');

            var min_width_wrapper = $('<div class="form-group col-xs-12 col-sm-3"></div>');
            var min_width_label = $('<label for="min_width_'+name+'_'+ id +'"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>');
            var min_width_input = $('<input type="number" name="min_width_'+name+'_'+ id +'" id="min_width_'+name+'_'+ id +'"  value="0" class="form-control">');

            var min_height_wrapper = $('<div class="form-group col-xs-12 col-sm-4"></div>');
            var min_height_label = $('<label for="min_height_'+name+'_'+ id +'"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>');
            var min_height_input = $('<input type="number" name="min_height_'+name+'_'+ id +'" id="min_height_'+name+'_'+ id +'"  value="0" class="form-control">');

            var test_wrapper = $('<div class="form-group col-xs-2"></div>');
            var test_label = $('<label class="l_hack">1</label>');
            var test_button = $('<button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_'+ name +'_'+ id +'">Тест модели</button>')


            panel.append(remove_panel);
            panel.append(row);

            row.append(file_wrapper);
            row.append(min_width_wrapper);
            row.append(min_height_wrapper);
            row.append(test_wrapper);

            file_wrapper.append(file_label);
            file_wrapper.append(file_input);

            min_width_wrapper.append(min_width_label);
            min_width_wrapper.append(min_width_input);

            min_height_wrapper.append(min_height_label);
            min_height_wrapper.append(min_height_input);

            test_wrapper.append(test_label);
            test_wrapper.append(test_button);

            panel.remove_panel = remove_panel;
            panel.panel_id = id;

            test_button.click(function () {

                var modal = $('.three_modal_wrapper');
                modal.fadeIn();


                var file = $(this).parent().parent().find('input[type="file"]');

                console.log(file);

                var reader = new FileReader();

                reader.onload = function(event) {
                    init_three_test('three_viewport', event.target.result);
                };

                reader.readAsDataURL(file[0].files[0]);


                $('.close_three_modal').click(function () {
                    modal.fadeOut();
                    $('#three_viewport').html('');
                    renderer.renderLists.dispose();
                })
            });

            return panel;

        }


        function init_three_test(element_id, model) {
            var viewport = document.getElementById(element_id);

            scene = new THREE.Scene();
            scene.fog = new THREE.Fog(0xE9E5CE, 500, 10000);
            var camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

            renderer = new THREE.WebGLRenderer({
                antialias: true
            });
            renderer.setClearColor(scene.fog.color);
            renderer.setSize( viewport.clientWidth, viewport.clientHeight );
            viewport.appendChild( renderer.domElement );

            fbx_manager = new THREE.LoadingManager();
            loader = new THREE.FBXLoader(fbx_manager);







            // var geometry = new THREE.BoxGeometry( 450/10, 720/10, 18/10 );
            // material = new THREE.MeshStandardMaterial({
            //     color: '#ffffff',
            //     roughness: 0.8,
            //     metalness: 0
            // });
            // var cube = new THREE.Mesh( geometry, material );
            // scene.add( cube );

            var amb_light = new THREE.AmbientLight( 0xffffff, 0.63);
            scene.add( amb_light );


            var directionalLight = new THREE.DirectionalLight( 0xffffff, 0.2 ,100  );
            directionalLight.position.set( 0, 450, 300 );


            directionalLight.target = new THREE.Group();
            directionalLight.target.position.set(0,0,0);
            directionalLight.target.name = 'Directional Light Target';

            scene.add(directionalLight);
            scene.add(directionalLight.target);

            camera.position.z = 150;


            var light1 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light1.position.set(0, 300,0);
            light1.decay = 5;

            var light2 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light2.position.set(300, 0,0);
            light2.decay = 5;

            var light3 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light3.position.set(-300, 0,0);
            light3.decay = 5;

            var light4 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light4.position.set(0, 0,300);
            light4.decay = 5;

            var light5 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light5.position.set(0, 0,-300);
            light5.decay = 5;

            scene.add(light1);
            scene.add(light2);
            scene.add(light3);
            scene.add(light4);
            scene.add(light5);


            loader.load(model, function (obj) {


                for (i = 0; i < obj.children.length; i++) {

                    var m = obj.children[i];

                    g = new THREE.Geometry().fromBufferGeometry(m.geometry.clone());
                    g.scale(m.scale.x, m.scale.y, m.scale.z);

                    if (m.name.indexOf('al') > -1) {
                        material = new THREE.MeshPhongMaterial({
                            color: 0xbdbebf
                        });
                    }

                    if (m.name.indexOf('glass') > -1) {
                        material = new THREE.MeshStandardMaterial({
                            color: 0xd1e2ff,
                            transparent: true,
                            opacity: 0.2,
                            roughness: 0,
                            metalness: 0
                        });
                    }

                    if (m.name.indexOf('gen') > -1) {
                        material = new THREE.MeshStandardMaterial({
                            color: '#ffffff',
                            roughness: 0.8,
                            metalness: 0
                        });
                    }


                    mesh = new THREE.Mesh(g, material);

                    mesh.name = m.name;
                    scene.add(mesh);
                }

                // scope.old_rotation = scope.rotation.clone();
                // scope.rotation.set(0, 0, 0);
                // scope.set_size(scope.params.width, scope.params.height, scope.params.thickness);
                // scope.rotation.copy(scope.old_rotation);


                renderer.render( scene, camera );
            });


            set_size = function () {

                var old_rot = this.model.rotation.clone();

                var world_rot = new THREE.Euler().setFromQuaternion(this.model.getWorldQuaternion().clone());
                this.model.rotation.set(
                    world_rot.x * -1,
                    world_rot.y * -1,
                    world_rot.z * -1
                );

                var scope = this,
                    i,
                    j,
                    v,
                    p,
                    s = new THREE.Vector3(scope.params.width / units, scope.params.height / units, scope.params.thickness / units),
                    box = new THREE.Box3().setFromObject(scope.model).getSize().clone()
                ;

                p = s.clone().sub(box).divideScalar(2);


                for (i = 0; i < scope.model.children.length; i++) {


                    for (j = 0; j < scope.model.children[i].geometry.vertices.length; j++) {
                        v = scope.model.children[i].geometry.vertices[j];

                        if (scope.model.children[i].name.indexOf('x') > -1) {
                            if (v.x < 0) {
                                v.x -= p.x;
                            } else {
                                v.x += p.x;
                            }
                        }

                        if (scope.model.children[i].name.indexOf('y') > -1) {
                            if (v.y < 0) {
                                v.y -= p.y;
                            } else {
                                v.y += p.y;
                            }
                        }

                        if (scope.model.children[i].name.indexOf('z') > -1) {
                            if (v.z < 0) {
                                v.z -= p.z;
                            } else {
                                v.z += p.z;
                            }
                        }
                    }



                }

                this.model.rotation.copy(old_rot);

                if(this.params.radius){
                    box = new THREE.Box3().setFromObject(scope.model).getSize().clone();

                    var cab = get_parent_recursive(this)

                    if(cab.rotation.y === 0 || cab.rotation.y === Math.PI){

                        this.model.scale.x = scope.params.width / units / box.x;
                        this.model.scale.z = this.model.scale.x

                        this.model.rotation.y = -1*this.parent.rotation.y;

                        box = new THREE.Box3().setFromObject(scope.model).getSize().clone();

                        this.model.position.z = box.z / 2;

                        if(this.params.orientation === 'right'){
                            this.model.position.x = -box.x / 2;
                        } else {
                            this.model.position.x = box.x / 2;
                        }

                    } else {

                        this.model.scale.z = scope.params.width / units / box.z;
                        this.model.scale.x = this.model.scale.z

                        this.model.rotation.y = -1*this.parent.rotation.y;



                        box = new THREE.Box3().setFromObject(scope.model).getSize().clone();

                        this.model.position.z = box.x / 2;

                        if(this.params.orientation === 'right'){
                            this.model.position.x = -box.z / 2;
                        } else {
                            this.model.position.x = box.z / 2;
                        }
                    }




                    this.model.rotation.y = 0;
                    //
                    // this.parent.rotation.y = -Math.PI/4
                    console.log(this.parent)
                    this.parent.set_handle_position();


                    function get_parent_recursive(obj) {
                        if(obj.type === 'Cabinet'){
                            return obj
                        } else {
                            return get_parent_recursive(obj.parent)
                        }
                    }

                }




            };

            controls = new THREE.OrbitControls(camera, renderer.domElement);


            animate = function () {
                requestAnimationFrame( animate );

                // cube.rotation.x += 0.01;
                // cube.rotation.y += 0.01;

                renderer.render( scene, camera );
            };

            animate();

            window.addEventListener('resize', onWindowResize, false);


        }

        function onWindowResize() {
            var viewport = document.getElementById('three_view');
            camera.aspect = viewport.clientWidth / viewport.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize( viewport.clientWidth, viewport.clientHeight );
        }

        var select = document.getElementById('materials');
        select.size = select.length;

        $('#materials option').mousedown(function (e) {
            e.preventDefault();
            $(this).prop('selected', !$(this).prop('selected'));
            return false;
        });


        $('#materials').selectize({
            create: false,
            plugins: ['remove_button'],
            dropdownDirection: 'up'
        });

        $('#category').selectize({
            create: false,
            render: {
                option: function (data, escape) {
                    return "<div class='option " + data.class + "'>" + data.text + "</div>"
                }
            }
        });

        if (!window.Selectize.prototype.positionDropdownOriginal) {
            window.Selectize.prototype.positionDropdownOriginal = window.Selectize.prototype.positionDropdown;
            window.Selectize.prototype.positionDropdown = function () {
                if (this.settings.dropdownDirection === 'up') {
                    var $control = this.$control;
                    var offset = this.settings.dropdownParent === 'body' ? $control.offset() : $control.position();

                    this.$dropdown.css({
                        width: $control.outerWidth(),
                        // top: offset.top - this.$dropdown.outerHeight() - 10,
                        left: offset.left,
                        bottom: 'calc(100% + 4px)'
                    });
                    this.$dropdown.addClass('direction-' + this.settings.dropdownDirection);
                    this.$control.addClass('direction-' + this.settings.dropdownDirection);
                    this.$wrapper.addClass('direction-' + this.settings.dropdownDirection);
                } else {
                    window.Selectize.prototype.positionDropdownOriginal.apply(this, arguments);
                }
            };
        }


    });
</script>

<style>



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