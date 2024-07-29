<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['kitchen_model_add']?></h2>
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


<form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('kitchen_models/add/') ?>">

    <div class="wrapper wrapper-content  animated fadeInRight">

    <?php echo validation_errors(); ?>


    <input type="hidden" id="ajax_url" value="<?php echo site_url('kitchen_models/') ?>">
    <input type="hidden" id="asset_path" value="<?php echo $this->config->item('const_path') ?>">

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
        </div>
        <div class="ibox-content">
            <div class="form-group">
                <label for="name"><?php echo $lang_arr['name']?>*</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="<?php echo $lang_arr['name']?>">
            </div>

            <div class="form-group">
                <label for="icon"><?php echo $lang_arr['icon']?>*</label>
                <input type="file" name="icon" id="icon" accept="image/jpeg,image/png,image/gif">
            </div>


            <input type="hidden" value="2" name="door_offset">
            <input type="hidden" value="10" name="shelve_offset">
            <input type="hidden" value="16" name="corpus_thickness">
            <input type="hidden" value="720" name="bottom_modules_height">


            <div class="form-group">
                <label for="active"><?php echo $lang_arr['active']?></label>
                <select class="form-control" name="active" id="active">
                    <option selected value="1"><?php echo $lang_arr['yes']?></option>
                    <option value="0"><?php echo $lang_arr['no']?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="order"><?php echo $lang_arr['order_label']?></label>
                <input type="text" class="form-control" name="order">
            </div>

        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['facades_params']?></h4>
        </div>
        <div class="ibox-content">
            <div class="form-group">
                <label for="bottom_as_top_facade_models"><?php echo $lang_arr['facades_bottom_as_top']?></label>
                <select class="form-control" name="bottom_as_top_facade_models" id="bottom_as_top_facade_models">
                    <option selected value="1"><?php echo $lang_arr['yes']?></option>
                    <option value="0"><?php echo $lang_arr['no']?></option>
                </select>
            </div>

            <div class="form-group bottom_as_top_facade_materials">
                <label for="bottom_as_top_facade_materials"><?php echo $lang_arr['facades_materials_bottom_as_top']?></label>
                <select class="form-control" name="bottom_as_top_facade_materials" id="bottom_as_top_facade_materials">
                    <option selected value="1"><?php echo $lang_arr['yes']?></option>
                    <option value="0"><?php echo $lang_arr['no']?></option>
                </select>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="facades_models_top"><?php echo $lang_arr['top_modules_facade_model']?>*</label>

                        <select class="form-control" name="facades_models_top" id="facades_models_top">
                            <option value="">--- <?php echo $lang_arr['choose_facade_model']?> ---</option>
                            <?php foreach ($facades as $item):?>

                            <?php
                            $data_json = new stdClass();
                            $data_json->icon = get_asset_path($item['icon'], $this->config->item('const_path'));
                            $data_json->name = $item['name'];
                                ?>

                            <option data-data="<?php echo htmlentities(json_encode($data_json), ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo $item['id']?>"><?php echo $item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group hidden" id="facades_models_bottom_wrapper">
                        <label for="facades_models_bottom"><?php echo $lang_arr['bottom_modules_facade_model']?>*</label>
                        <select class="form-control" name="facades_models_bottom" id="facades_models_bottom">
                            <option value="">--- <?php echo $lang_arr['choose_facade_model']?> ---</option>

                            <?php foreach ($facades as $item):?>

                                <?php
                                $data_json = new stdClass();
                                $data_json->icon = get_asset_path($item['icon'], $this->config->item('const_path'));
                                $data_json->name = $item['name'];
                                ?>

                                <option data-data="<?php echo htmlentities(json_encode($data_json), ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo $item['id']?>"><?php echo $item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="facades_selected_materials_top"><?php echo $lang_arr['top_facades_materials']?>*</label>
                        <select class="form-control" name="facades_selected_material_top" id="facades_selected_materials_top">
                            <option value="">--- <?php echo $lang_arr['choose_facade_material']?> ---</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group hidden" id="facades_selected_materials_bottom_wrapper">
                        <label for="facades_selected_materials_bottom"><?php echo $lang_arr['bottom_facades_materials']?>*</label>
                        <select class="form-control" name="facades_selected_material_bottom" id="facades_selected_materials_bottom">
                            <option value="">--- <?php echo $lang_arr['choose_facade_material']?> ---</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="allow_facades_materials_select"><?php echo $lang_arr['allow_facades_materials_select']?></label>
                    <select class="form-control" name="allow_facades_materials_select" id="allow_facades_materials_select">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['glass_params']?></h4>
        </div>
        <div class="ibox-content">

            <div class="form-group">
                <label for="glass_materials"><?php echo $lang_arr['available_glass_materials']?>*</label>
                <select multiple class="form-control" name="glass_materials[]" id="glass_materials">
                    <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
                    <?php foreach ($glass_materials as $cat): ?>
                        <?php if ($cat['parent'] == 0): ?>
                            <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="selected_glass_material"><?php echo $lang_arr['default_glass_material']?></label>
                <select class="form-control" name="selected_glass_material" id="selected_glass_material">
                    <option value="">--- <?php echo $lang_arr['choose_glass_material']?> ---</option>
                </select>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="allow_glass_materials_select"><?php echo $lang_arr['allow_glass_materials_select']?></label>
                    <select class="form-control" name="allow_glass_materials_select" id="allow_glass_materials_select">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['corpus_params']?></h4>
        </div>
        <div class="ibox-content">

            <div class="form-group">
                <label for="bottom_as_top_corpus_materials"><?php echo $lang_arr['bottom_as_top_corpus_materials']?></label>
                <select class="form-control" name="bottom_as_top_corpus_materials" id="bottom_as_top_corpus_materials">
                    <option selected value="1"><?php echo $lang_arr['yes']?></option>
                    <option value="0"><?php echo $lang_arr['no']?></option>
                </select>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="corpus_materials_top"><?php echo $lang_arr['available_corpus_materials']?>*</label>
                        <select multiple class="form-control" name="corpus_materials_top[]" id="corpus_materials_top">
                            <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
                            <?php foreach ($materials as $cat): ?>
                                <?php if ($cat['parent'] == 0): ?>
                                    <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="selected_corpus_material_top"><?php echo $lang_arr['default_top_corpus_material']?>*</label>
                        <select class="form-control" name="selected_corpus_material_top" id="selected_corpus_material_top">
                            <option value="">--- <?php echo $lang_arr['choose_corpus_material']?> ---</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group hidden" id="selected_corpus_material_bottom_wrapper">
                        <label for="selected_corpus_material_bottom"><?php echo $lang_arr['default_bottom_corpus_material']?>*</label>
                        <select class="form-control" name="selected_corpus_material_bottom" id="selected_corpus_material_bottom">
                            <option value="">--- <?php echo $lang_arr['choose_corpus_material']?> ---</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="allow_corpus_materials_select"><?php echo $lang_arr['allow_corpus_materials_select']?></label>
                    <select class="form-control" name="allow_corpus_materials_select" id="allow_corpus_materials_select">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['cokol_params']?></h4>
        </div>
        <div class="ibox-content">

            <div class="form-group">
                <label for="cokol_active"><?php echo $lang_arr['no_cokol']?></label>
                <select class="form-control" name="cokol_active" id="cokol_active">
                    <option value="0"><?php echo $lang_arr['yes']?></option>
                    <option selected value="1"><?php echo $lang_arr['no']?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="cokol_height"><?php echo $lang_arr['cokol_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                <input value="120" type="number" step="1" class="form-control" id="cokol_height" name="cokol_height">
            </div>


            <div class="form-group" id="cokol_materials_wrapper">
                <label for="cokol_materials"><?php echo $lang_arr['available_cokol_materials']?>*</label>
                <select multiple class="form-control" name="cokol_materials[]" id="cokol_materials">
                    <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
                    <?php foreach ($materials as $cat): ?>
                        <?php if ($cat['parent'] == 0): ?>
                            <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="form-group">
                <label for="cokol_as_corpus"><?php echo $lang_arr['cokol_as_corpus']?></label>
                <select class="form-control" name="cokol_as_corpus" id="cokol_as_corpus">
                    <option selected value="1"><?php echo $lang_arr['yes']?></option>
                    <option value="0"><?php echo $lang_arr['no']?></option>
                </select>
            </div>


            <div class="form-group hidden" id="selected_cokol_material_wrapper">
                <label for="selected_cokol_material"><?php echo $lang_arr['cokol_default_material']?>*</label>
                <select class="form-control" name="selected_cokol_material" id="selected_cokol_material">
                    <option value="">--- <?php echo $lang_arr['choose_cokol_material']?> ---</option>
                </select>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="allow_cokol_materials_select"><?php echo $lang_arr['allow_cokol_material_select']?></label>
                    <select class="form-control" name="allow_cokol_materials_select" id="allow_cokol_materials_select">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['tabletop_params']?></h4>
        </div>
        <div class="ibox-content">

            <div class="form-group">
                <label for="tabletop_thickness"><?php echo $lang_arr['default_tabletop_thickness']?> (<?php echo $lang_arr['units']?>)</label>
                <input value="40" type="number" step="1" class="form-control" id="tabletop_thickness" name="tabletop_thickness">
            </div>

            <div class="form-group">
                <label for="tabletop_materials"><?php echo $lang_arr['available_tabletop_materials']?>*</label>
                <select multiple class="form-control" name="tabletop_materials[]" id="tabletop_materials">
                    <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
                    <?php foreach ($materials as $cat): ?>
                        <?php if ($cat['parent'] == 0): ?>
                            <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="selected_tabletop_materials"><?php echo $lang_arr['tabletop_default_material']?>*</label>
                <select class="form-control" name="selected_tabletop_material" id="selected_tabletop_materials">
                    <option value="">--- <?php echo $lang_arr['choose_tabletop_material']?> ---</option>
                </select>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="allow_tabletop_materials_select"><?php echo $lang_arr['allow_tabletop_material_select']?></label>
                    <select class="form-control" name="allow_tabletop_materials_select" id="allow_tabletop_materials_select">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['wallpanel_params']?></h4>
        </div>
        <div class="ibox-content">

            <div class="form-group">
                <label for="wallpanel_active"><?php echo $lang_arr['wallpanel_active']?></label>
                <select class="form-control" name="wallpanel_active" id="wallpanel_active">
                    <option value="1"><?php echo $lang_arr['yes']?></option>
                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="wallpanel_height"><?php echo $lang_arr['wallpanel_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                <input type="number" value="550" step="1" class="form-control" id="wallpanel_height" name="wallpanel_height">
            </div>

            <div class="form-group">
                <label for="wallpanel_materials"><?php echo $lang_arr['wallpanel_available_materials']?>*</label>
                <select multiple class="form-control" name="wallpanel_materials[]" id="wallpanel_materials">
                    <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
                    <?php foreach ($materials as $cat): ?>
                        <?php if ($cat['parent'] == 0): ?>
                            <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="selected_wallpanel_materials"><?php echo $lang_arr['wallpanel_default_active']?>*</label>
                <select class="form-control" name="selected_wallpanel_material" id="selected_wallpanel_materials">
                    <option value="">--- <?php echo $lang_arr['choose_wallpanel_color']?> ---</option>
                </select>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="allow_wallpanel_materials_select"><?php echo $lang_arr['allow_choose_wallpanel_material']?></label>
                    <select class="form-control" name="allow_wallpanel_materials_select" id="allow_wallpanel_materials_select">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['handles_params']?></h4>
        </div>
        <div class="ibox-content">


            <div class="form-group">
                <label for="no_handle"><?php echo $lang_arr['facades_no_handles']?></label>
                <select class="form-control" name="no_handle" id="no_handle">
                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                    <option value="1"><?php echo $lang_arr['yes']?></option>
                </select>
            </div>

            <div class="form-group" id="handle_orientation_wrapper">
                <label for="handle_orientation"><?php echo $lang_arr['handles_orientation']?></label>
                <select class="form-control" name="handle_orientation" id="handle_orientation">
                    <option selected value="vertical"><?php echo $lang_arr['vertical']?></option>
                    <option value="horizontal"><?php echo $lang_arr['horizontal']?></option>
                </select>
            </div>

            <div class="form-group" id="handle_lockers_position_wrapper">
                <label for="handle_lockers_position"><?php echo $lang_arr['locker_handle_position']?></label>
                <select class="form-control" name="handle_lockers_position" id="handle_lockers_position">
                    <option selected value="top"><?php echo $lang_arr['lockers_hande_top']?></option>
                    <option value="center"><?php echo $lang_arr['lockers_handle_center']?></option>
                </select>
            </div>



            <div class="form-group" id="handle_selected_model_wrapper">
                <label for="handle_selected_model"><?php echo $lang_arr['handle_default_model']?>*</label>
                <select class="form-control" name="handle_selected_model" id="handle_selected_model">
                    <option value="">--- <?php echo $lang_arr['choose_handle_model']?> ---</option>
                        <?php foreach ($handles as $item):?>

                            <?php
                            $data_json = new stdClass();
                            $data_json->icon = get_asset_path($item['icon'], $this->config->item('const_path'));
                            $data_json->name = $item['name'];
                            ?>

                            <option data-data="<?php echo htmlentities(json_encode($data_json), ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo $item['id']?>"><?php echo $item['name']?></option>
                        <?php endforeach;?>
                </select>
            </div>

            <div class="form-group" id="handle_preferable_size_wrapper">
                <label for="handle_preferable_size"><?php echo $lang_arr['handle_default_size']?>*</label>
                <select class="form-control" name="handle_preferable_size" id="handle_preferable_size">
                    <option value="">--- <?php echo $lang_arr['choose_size']?> ---</option>
                </select>
            </div>

            <div class="row" id="allow_handles_select_wrapper">
                <div class="col-sm-12">
                    <label for="allow_handles_select"><?php echo $lang_arr['allow_handles_select']?></label>
                    <select class="form-control" name="allow_handles_select" id="allow_handles_select">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>

        </div>
    </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['cornice_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group">
                    <label for="cornice_available"><?php echo $lang_arr['cornice_available_kitchen_model']?></label>
                    <select class="form-control" name="cornice_available" id="cornice_available">
                        <option value="1"><?php echo $lang_arr['yes']?></option>
                        <option selected value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cornice_active"><?php echo $lang_arr['cornice_active_default_kithcen_model']?></label>
                    <select class="form-control" name="cornice_active" id="cornice_active">
                        <option value="1"><?php echo $lang_arr['yes']?></option>
                        <option selected value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>

            </div>
        </div>

    <div class="ibox">
        <div class="ibox-title">
            <h4 class="panel-title"><?php echo $lang_arr['available_modules_set']?></h4>
        </div>
        <div class="ibox-content">

            <div class="form-group">
                <label for="available_modules"><?php echo $lang_arr['modules_set']?></label>
                <select class="form-control" name="available_modules" id="available_modules">
                    <option value="">--- <?php echo $lang_arr['choose_modules_set']?> ---</option>
                    <?php foreach ($module_sets as $item):?>

                        <option value="<?php echo $item['id']?>"><?php echo $item['name']?></option>
                    <?php endforeach;?>
                </select>
            </div>

        </div>
    </div>


        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['additional_materials']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group">
                    <label for="fixed_materials"><?php echo $lang_arr['chose_additional_materials']?></label>
                    <input type="text" value="" class="form-control" id="fixed_materials" name="fixed_materials">
                </div>

            </div>
        </div>

        <?php if($settings['multiple_facades_mode'] == 1):?>
        <div class="ibox ">
            <div class="ibox-content">

            <div class="form-group" >
                <label for="facades_categories"><?php echo $lang_arr['facades_categories_available']?></label>
                <select multiple class="form-control" name="facades_categories[]" id="facades_categories">
                    <option value="">--- <?php echo $lang_arr['choose_additional_facades_categories']?> ---</option>
                    <?php foreach ($facades_categories as $cat):?>
                        <?php if($cat['parent'] == 0): ?>
                            <option value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
            </div>
            </div>
            </div>
        <?php endif;?>

        <div class="ibox ">
            <div class="ibox-content">

                <div class="hr-line-dashed"></div>

                <div class="form-group row">
                    <div class="col-sm-4 col-sm-offset-2">
                        <a class="btn btn-white btn-sm" href="<?php echo site_url('kitchen_models/index/') ?>"><?php echo $lang_arr['cancel']?></a>
                        <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

</form>

<script src="/common_assets/admin_js/production/kitchen_model_add.js"></script>

<style>
    .selectize-control.multi .selectize-input > div{
        background: #428bca;
        color: #ffffff;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>