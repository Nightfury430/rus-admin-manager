<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['add_module']?></h2>
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

<form class="add_item" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('module_sets/items_add/'.$set_id.'/'.$top_category) ?>">

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
                                    <option data-data='{"class": "top_cat"}' value="<? echo $top_category ?>"><?php echo $lang_arr['no']?></option>
				                    <?php foreach ($categories as $cat):?>
                                        <option data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
				                    <?php endforeach;?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="order"><?php echo $lang_arr['order_label']?></label>
                                <input type="text" class="form-control" name="order">
                            </div>

                            <div class="form-group">
                                <label for="active"><?php echo $lang_arr['active']?></label>
                                <select class="form-control" name="active" id="active">
                                    <option selected value="1"><?php echo $lang_arr['yes']?></option>
                                    <option value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>


                            <input type="hidden" name="cabinet_group" value="bottom">
                            <input type="hidden" id="cabinet_params" name="cabinet_params">
                            <input type="hidden" id="template_icon" name="template_icon">
                            <input type="hidden" id="template_id" name="template_id">


                            <div class="form-group">
                                <label for="icon"><?php echo $lang_arr['icon']?> <?php echo $lang_arr['icon_descr']?></label>
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
                                            <option selected value="template"><?php echo $lang_arr['template']?></option>
                                            <option value="custom_template"><?php echo $lang_arr['custom_template']?></option>
                                            <option value="params"><?php echo $lang_arr['params']?></option>
                                        </select>
                                    </div>

                                    <div class="params_mode_wrapper hidden">
                                        <div class="form-group">
                                            <label for="module_params"><?php echo $lang_arr['module_params']?></label>
                                            <div>
                                                <textarea id="module_params"></textarea>
                                            </div>
                                            <div class="btn btn-sm btn-primary parse_params">
							                    <?php echo $lang_arr['process_parameters']?>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="templates_wrapper" class="templates_mode_wrapper ">
                                        <b><?php echo $lang_arr['choose_module_template']?></b>
                                        <div class="modules_templates_wrapper">
						                    <?php foreach ($modules_templates as $template): ?>
                                                <div data-id="<?php echo $template['id']?>" class="module_template">
                                                    <img src="<?php echo $template['icon'] ?>" alt="">
                                                    <p><?php echo $template['name'] ?></p>
                                                    <input type="hidden" value="<?php echo htmlentities($template['params'], ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>
						                    <?php endforeach; ?>
                                        </div>
                                    </div>


                                    <div id="custom_templates_wrapper" class="templates_mode_wrapper hidden">
                                        <b><?php echo $lang_arr['choose_module_template']?></b>
                                        <div class="modules_templates_wrapper">
						                    <?php foreach ($modules_templates_custom as $template): ?>
                                                <div data-id="<?php echo $template['id']?>" class="module_template">

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

                            <input type="hidden" id="is_custom_template" name="is_custom_template" value="0">
                            <button type="button" class="btn btn-sm btn-primary add_size"><?php echo $lang_arr['add_size']?></button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">

                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-white btn-sm" href="<?php echo site_url('module_sets/items_index/'.$set_id.'/'.$top_category) ?>"><?php echo $lang_arr['cancel']?></a>
                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

</form>



<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">
<script src="/common_assets/libs/three.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/Tween.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>


<!--<script src="/common_assets/js/v4/Cabinet_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Door_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Facade_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/functions.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Handle.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Shelve_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Locker_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Model_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Parts.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Sizes_obj.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Model_cache.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Room_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Decoration.js" type="text/javascript"></script>-->
<!---->
<!---->
<!---->
<!---->
<!---->
<!---->
<!--<script src="/common_assets/admin_js/add_module.js" type="text/javascript"></script>-->

<script src="/common_assets/admin_js/production/modules_add_with_templates.js" type="text/javascript"></script>



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


<style>

    #module_params{
        width: 100%;
        height: 400px;
        resize: none;
        margin-bottom: 10px;
    }

    .sizes_row{
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        border: 1px solid #a8a8a8!important;
        cursor: move!important;
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

</style>