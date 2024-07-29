
<?php

if(!isset($item['is_custom_template'])) $item['is_custom_template'] = 0;

$item_params = json_decode($item['params']);

$item_variants = $item_params->params->variants;

$item_params_without_variants = clone $item_params;

unset($item_params_without_variants->params->variants);



?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['edit_module']?></h2>
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


<form class="add_item" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('modules/items_edit/'.$top_category . '/' . $item['id']) ?>">

<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="row">
        <div class="col-12">
			<?php echo validation_errors(); ?>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                    <li><a class="nav-link resize_three" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params']?></a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['sizes']?></a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">

                            <div class="form-group" >
                                <label for="category"><?php echo $lang_arr['category']?></label>
                                <select class="form-control" name="category" id="category">
                                    <option data-data='{"class": "top_cat"}' value="0"><?php echo $lang_arr['no']?></option>
				                    <?php foreach ($categories as $cat):?>
                                        <option <?php if ($item['category'] == $cat['id']) echo 'selected';?> data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
				                    <?php endforeach;?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="order"><?php echo $lang_arr['order_label']?></label>
                                <input value="<?php echo $item['order']?>" type="text" class="form-control" name="order">
                            </div>

                            <div class="form-group">
                                <label for="active"><?php echo $lang_arr['active']?></label>
                                <select class="form-control" name="active" id="active">
                                    <option <?php if($item['active'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($item['active'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                            <input type="hidden" name="cabinet_group" value="bottom">
                            <input type="hidden" id="cabinet_params" name="cabinet_params" value="<?php echo htmlentities(json_encode($item_params_without_variants), ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" id="template_icon" name="template_icon" value="<?php echo $item['icon']?>">
                            <input type="hidden" id="template_id" name="template_id" value="<?php echo $item['template_id']?>">

                            <input type="hidden" id="item_params" value="<?php echo htmlentities($item['params'], ENT_QUOTES, 'UTF-8'); ?>">

                            <div class="form-group">
			                    <?php if (strpos($item['icon'], 'common_assets') !== false):?>
                                    <img style="display: block; max-width: 100px" src="<?php echo $item['icon'] ?>" alt="">
			                    <?php else:?>
                                    <img style="display: block; max-width: 100px" src="<?php echo $this->config->item('const_path') . $item['icon'] ?>" alt="">
			                    <?php endif;?>
                                <label for="icon"><?php echo $lang_arr['icon_descr']?></label>
                                <input type="file" name="icon" id="icon" accept="image/jpeg,image/png,image/gif">
                            </div>
                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label for="module_mode"><?php echo $lang_arr['module_mode']?></label>
                                        <select class="form-control" id="module_mode">
                                            <option <?php if($item['template_id'] != null && $item['is_custom_template'] == 0) echo 'selected';?> value="template"><?php echo $lang_arr['template']?></option>
                                            <option <?php if($item['template_id'] != null && $item['is_custom_template'] == 1) echo 'selected';?> value="custom_template"><?php echo $lang_arr['custom_template']?></option>
                                            <option <?php if($item['template_id'] == null) echo 'selected';?> value="params"><?php echo $lang_arr['params']?></option>
                                        </select>
                                    </div>

                                    <div class="params_mode_wrapper <?php if($item['template_id'] != null) echo 'hidden'?> ?>">
                                        <div class="form-group">
                                            <label for="module_params"><?php echo $lang_arr['model_params']?></label>
                                            <div>
                                                <textarea id="module_params"><?php if($item['template_id'] == null) echo htmlentities($item['params']);?></textarea>
                                            </div>
                                            <div class="btn btn-sm btn-primary parse_params">
							                    <?php echo $lang_arr['process_parameters']?>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="templates_wrapper" class="templates_mode_wrapper <?php if( $item['template_id'] == null || $item['is_custom_template'] == 1) echo 'hidden'?>">
                                        <b><?php echo $lang_arr['choose_module_template']?></b>
                                        <div class="modules_templates_wrapper">
						                    <?php foreach ($modules_templates as $template): ?>
                                                <div data-id="<?php echo $template['id'] ?>" class="module_template <?php if ( $item['template_id'] == $template['id'] && $item['is_custom_template'] == 0 ) echo 'selected'; ?>">
                                                    <img src="<?php echo $template['icon'] ?>" alt="">
                                                    <p><?php echo $template['name'] ?></p>
                                                    <input type="hidden" value="<?php echo htmlentities($template['params'], ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>
						                    <?php endforeach; ?>
                                        </div>
                                    </div>


                                    <div id="custom_templates_wrapper" class="templates_mode_wrapper <?php if( $item['template_id'] == null || $item['is_custom_template'] == 0) echo 'hidden'?>">
                                        <b><?php echo $lang_arr['choose_module_template']?></b>
                                        <div class="modules_templates_wrapper">
						                    <?php foreach ($modules_templates_custom as $template): ?>
                                                <div data-id="<?php echo $template['id']?>" class="module_template <?php if ( $item['template_id'] == $template['id'] && $item['is_custom_template'] == 1 ) echo 'selected'; ?>">

								                    <?php if($template['icon'] != null): ?>
									                    <?php if (strpos($template['icon'], 'common_assets') !== false): ?>
                                                            <img class="img-fluid" src="<?php echo $template['icon'] ?>" alt="">
									                    <?php else: ?>
                                                            <img class="img-fluid" src="<?php echo $this->config->item('const_path') . $template['icon'] ?>" alt="">
									                    <?php endif; ?>

								                    <?php endif; ?>


                                                    <p><?php echo $template['name'] ?></p>
                                                    <input type="hidden" value="<?php echo htmlentities($template['params'], ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>
						                    <?php endforeach; ?>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-sm-6">
                                    <button id="open_doors"><?php echo $lang_arr['open_close']?></button>
                                    <div id="three_view" style="height: 560px">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body sizes_panel" id="sortable-view">

                            <select class="hidden" multiple name="variants[]" id="sizes_list"></select>

		                    <?php foreach ($item_variants as $variant):?>

			                    <?php

			                    if(!isset($variant->default)) $variant->default = 0;

			                    ?>

                                <div class="panel panel-default panel-body sizes_row ui-sortable">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['name']?></label>
                                            <input type="text" class="form-control name_input" value="<?php echo $variant->name;?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['code']?></label>
                                            <input type="text" class="form-control code_input" value="<?php echo $variant->code;?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['default']?></label>
                                            <select class="form-control default_input">
                                                <option <?php if($variant->default == 0) echo 'selected';?> value="0"><?php echo $lang_arr['no']?></option>
                                                <option <?php if($variant->default == 1) echo 'selected';?> value="1"><?php echo $lang_arr['yes']?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['obj_width']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input type="number" min="0" class="form-control width_input" value="<?php echo $variant->width;?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['obj_height']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input type="number" min="0" class="form-control height_input" value="<?php echo $variant->height;?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['obj_depth']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input type="number" min="0" class="form-control depth_input" value="<?php echo $variant->depth;?>">
                                        </div>
                                    </div>
                                    <span class="glyphicon glyphicon-trash remove_panel"></span>
                                </div>
		                    <?php endforeach;?>

                            <button type="button" class="btn btn-sm btn-primary add_size"><?php echo $lang_arr['add_size']?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="is_custom_template" name="is_custom_template" value="<?php echo $item['is_custom_template']?>">

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">

                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-white btn-sm" href="<?php echo site_url('modules/items_index/'.$top_category) ?>"><?php echo $lang_arr['cancel']?></a>
                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
</form>

<script>
    $(document).ready(function(){
        $('.resize_three').click(function () {
            setTimeout(function () {
                window.dispatchEvent(new Event('resize'));
            },100)
        })

        $( "#sortable-view" ).sortable();
        $( "#sortable-view" ).disableSelection();



    })



</script>



<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">
<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<script src="/common_assets/libs/three106.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/Tween.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>
<script src="/common_assets/libs/exporters.js" type="text/javascript"></script>

<!---->
<!--<script src="/common_assets/js/v4/0_BPObject.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Cabinet_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Cornice.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Door_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Facade_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/functions.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Handle.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Locker_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Model_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Model_cache.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Parts.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Sizes_obj.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Room_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Decoration.js" type="text/javascript"></script>-->
<!---->
<!--<script src="/common_assets/admin_js/edit_module.js" type="text/javascript"></script>-->

<script src="/common_assets/admin_js/production/modules_edit_with_template_v2.js" type="text/javascript"></script>






<style>

    .sizes_row .col-xs-4:nth-child(3){
        opacity: 0;
    }

    .price_input{
        display: none;
    }



    #module_params{
        width: 100%;
        height: 400px;
        resize: none;
        margin-bottom: 10px;
    }

    #change_module_size{
        margin-right: 20px;
    }

    .modules_templates_wrapper{
        height: 560px;
        overflow-y: auto;
    }

    .module_template{
        cursor: pointer;
        border: 1px solid #ffffff;
        margin-bottom: 10px;
    }

    .module_template > *{
        display: inline-block;
        vertical-align: middle;
    }

    .module_template img{
        max-width: 100px;
        width: auto;
        padding: 5px;
    }

    .module_template p{
        padding-left: 10px;
    }

    .module_template.selected{
        border: 1px solid green;
    }

    #three_view{
        position: relative;
    }

    #open_doors{
        /*position: absolute;*/
        /*right: 0;*/
        /*bottom: 0;*/
    }

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

    .sizes_row{
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        cursor: move!important;
        border: 1px solid #a8a8a8!important;

    }

    .col-xs-4 {
        -ms-flex: 0 0 33.333333%;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;

        position: relative;
        width: 100%;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

</style>