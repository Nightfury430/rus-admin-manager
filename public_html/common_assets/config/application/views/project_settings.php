<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['kitchen_project_settings_label']?></h2>
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


<form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('project_settings/save_data/') ?>">


<input type="hidden" id="js_settings" value="<?php echo htmlentities(json_encode($settings), ENT_QUOTES, 'UTF-8'); ?>">

<input type="hidden" id="ajax_url" value="<?php echo site_url('kitchen_models/') ?>">
<input type="hidden" id="asset_path" value="<?php echo $this->config->item('const_path') ?>">

<div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['facades_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">

                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>

                        <div class="form-group">
                            <label for="selected_facade_model"><?php echo $lang_arr['default_facade_model']?>*</label>

                            <select class="form-control" name="selected_facade_model" id="selected_facade_model">
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

                        <div class="form-group">
                            <label for="selected_material_facade"><?php echo $lang_arr['default_facade_material']?>*</label>
                            <select class="form-control" name="selected_material_facade" id="selected_material_facade">
                                <option value="">--- <?php echo $lang_arr['choose_facade_material']?> ---</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['corpus_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="available_materials_corpus"><?php echo $lang_arr['available_corpus_materials']?>*</label>
                            <select multiple class="form-control" name="available_materials_corpus[]" id="available_materials_corpus">
                                <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
					            <?php foreach ($materials as $cat): ?>
						            <?php if ($cat['parent'] == 0): ?>
                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
						            <?php endif; ?>
					            <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_corpus"><?php echo $lang_arr['default_corpus_material']?>*</label>
                            <select class="form-control" name="selected_material_corpus" id="selected_material_corpus">
                                <option value="">--- <?php echo $lang_arr['choose_corpus_material']?> ---</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="available_corpus_thickness"><?php echo $lang_arr['available_corpus_thickness']?>*</label>
                            <select multiple class="form-control" name="available_corpus_thickness[]" id="available_corpus_thickness">
                                <option value="16">16</option>
                                <option value="18">18</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="default_corpus_thickness"><?php echo $lang_arr['default_corpus_thickness']?>*</label>
                            <select class="form-control" name="default_corpus_thickness" id="default_corpus_thickness">
                                <option selected value="16">16</option>
                                <option value="18">18</option>
                            </select>
                        </div>


                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['cokol_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="cokol_height"><?php echo $lang_arr['cokol_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                            <input value="120" type="number" step="1" class="form-control" id="cokol_height" name="cokol_height">
                        </div>

                        <div class="form-group">
                            <label for="available_materials_cokol"><?php echo $lang_arr['available_cokol_materials']?>*</label>
                            <select multiple class="form-control" name="available_materials_cokol[]" id="available_materials_cokol">
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
                            <label for="selected_material_cokol"><?php echo $lang_arr['cokol_default_material']?>*</label>
                            <select class="form-control" name="selected_material_cokol" id="selected_material_cokol">
                                <option value="">--- <?php echo $lang_arr['choose_cokol_material']?> ---</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['tabletop_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="tabletop_thickness"><?php echo $lang_arr['default_tabletop_thickness']?> (<?php echo $lang_arr['units']?>)</label>
                            <input value="40" type="number" step="1" class="form-control" id="tabletop_thickness" name="tabletop_thickness">
                        </div>

                        <div class="form-group">
                            <label for="available_materials_tabletop"><?php echo $lang_arr['available_tabletop_materials']?>*</label>
                            <select multiple class="form-control" name="available_materials_tabletop[]" id="available_materials_tabletop">
                                <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
					            <?php foreach ($materials as $cat): ?>
						            <?php if ($cat['parent'] == 0): ?>
                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
						            <?php endif; ?>
					            <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_tabletop"><?php echo $lang_arr['tabletop_default_material']?>*</label>
                            <select class="form-control" name="selected_material_tabletop" id="selected_material_tabletop">
                                <option value="">--- <?php echo $lang_arr['choose_tabletop_material']?> ---</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['wallpanel_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="wallpanel_active"><?php echo $lang_arr['wallpanel_default_active']?></label>
                            <select class="form-control" name="wallpanel_active" id="wallpanel_active">
                                <option value="1"><?php echo $lang_arr['yes']?></option>
                                <option selected value="0"><?php echo $lang_arr['no']?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="wallpanel_height"><?php echo $lang_arr['wallpanel_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                            <input value="550" type="number" step="1" class="form-control" id="wallpanel_height" name="wallpanel_height">
                        </div>

                        <div class="form-group">
                            <label for="available_materials_wallpanel"><?php echo $lang_arr['wallpanel_available_materials']?>*</label>
                            <select multiple class="form-control" name="available_materials_wallpanel[]" id="available_materials_wallpanel">
                                <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
					            <?php foreach ($materials as $cat): ?>
						            <?php if ($cat['parent'] == 0): ?>
                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
						            <?php endif; ?>
					            <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_wallpanel"><?php echo $lang_arr['wallpanel_default_material']?>*</label>
                            <select class="form-control" name="selected_material_wallpanel" id="selected_material_wallpanel">
                                <option value="">--- <?php echo $lang_arr['choose_wallpanel_color']?> ---</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['glass_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="available_materials_glass"><?php echo $lang_arr['available_glass_materials']?>*</label>
                            <select multiple class="form-control" name="available_materials_glass[]" id="available_materials_glass">
                                <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
					            <?php foreach ($glass_materials as $cat): ?>
						            <?php if ($cat['parent'] == 0): ?>
                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
						            <?php endif; ?>
					            <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_glass"><?php echo $lang_arr['default_glass_material']?>*</label>
                            <select class="form-control" name="selected_material_glass" id="selected_material_glass">
                                <option value="">--- <?php echo $lang_arr['choose_glass_material']?> ---</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['handles_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="handle_orientation"><?php echo $lang_arr['handles_orientation']?></label>
                            <select class="form-control" name="handle_orientation" id="handle_orientation">
                                <option selected value="vertical"><?php echo $lang_arr['vertical']?></option>
                                <option value="horizontal"><?php echo $lang_arr['horizontal']?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="handle_lockers_position"><?php echo $lang_arr['locker_handle_position']?></label>
                            <select class="form-control" name="handle_lockers_position" id="handle_lockers_position">
                                <option selected value="top"><?php echo $lang_arr['lockers_hande_top']?></option>
                                <option value="center"><?php echo $lang_arr['lockers_handle_center']?></option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="handle_selected_model"><?php echo $lang_arr['handle_default_model']?>*</label>
                            <select class="form-control" name="handle_selected_model" id="handle_selected_model">
                                <option value="">--- <?php echo $lang_arr['choose_handle_model']?> ---</option>
                                <option data-data='{"icon":"","name":"<?php echo $lang_arr['no_handle']?>"}' value="0">"<?php echo $lang_arr['no_handle']?></option>
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

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['walls_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="available_materials_walls"><?php echo $lang_arr['available_walls_materials']?>*</label>
                            <select multiple class="form-control" name="available_materials_walls[]" id="available_materials_walls">
                                <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
					            <?php foreach ($materials as $cat): ?>
						            <?php if ($cat['parent'] == 0): ?>
                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
						            <?php endif; ?>
					            <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_walls"><?php echo $lang_arr['default_walls_material']?>*</label>
                            <select class="form-control" name="selected_material_walls" id="selected_material_walls">
                                <option value="">--- <?php echo $lang_arr['choose_walls_material']?> ---</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['floor_params']?></h4>
                    </div>
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>
                        <div class="form-group">
                            <label for="available_materials_floor"><?php echo $lang_arr['available_floor_materials']?>*</label>
                            <select multiple class="form-control" name="available_materials_floor[]" id="available_materials_floor">
                                <option value="">--- <?php echo $lang_arr['choose_materials_categories']?> ---</option>
					            <?php foreach ($materials as $cat): ?>
						            <?php if ($cat['parent'] == 0): ?>
                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
						            <?php endif; ?>
					            <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_floor"><?php echo $lang_arr['default_floor_material']?>*</label>
                            <select class="form-control" name="selected_material_floor" id="selected_material_floor">
                                <option value="">--- <?php echo $lang_arr['choose_floor_material']?> ---</option>
                            </select>
                        </div>

                    </div>
                </div>


                <div class="ibox ">
                    <div class="ibox-content sk-loading">
                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button id="submit_button" class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

</div>


</form>



<script>
    $(document).ready(function () {

         settings = JSON.parse($('#js_settings').val());

        var act;
        if(settings.available_corpus_thickness == null || settings.available_corpus_thickness == ''){
            act = ''
        } else {
            act = JSON.parse(settings.available_corpus_thickness);
        }



        if(act == '' || act == '[]' ){
            settings.available_corpus_thickness = '["16"]'
            settings.default_corpus_thickness = 16
        }

        if(settings != ''){

            var stop_show_change = 1;

            setTimeout(function () {
                $('#selected_facade_model')[0].selectize.setValue(settings.selected_facade_model);
                $('#available_materials_corpus')[0].selectize.setValue(JSON.parse(settings.available_materials_corpus));

                $('#available_corpus_thickness')[0].selectize.setValue(JSON.parse(settings.available_corpus_thickness));
                $('#default_corpus_thickness').val(settings.default_corpus_thickness);

                $('#available_materials_glass')[0].selectize.setValue(JSON.parse(settings.available_materials_glass));

                $('#cokol_height').val(settings.cokol_height);
                $('#available_materials_cokol')[0].selectize.setValue(JSON.parse(settings.available_materials_cokol));
                $('#cokol_as_corpus').val(settings.cokol_as_corpus);
                $('#cokol_as_corpus').change();

                $('#tabletop_thickness').val(settings.tabletop_thickness);
                $('#available_materials_tabletop')[0].selectize.setValue(JSON.parse(settings.available_materials_tabletop));


                $('#wallpanel_active').val(settings.wallpanel_active);
                $('#wallpanel_active').change();
                $('#wallpanel_height').val(settings.wallpanel_height);
                $('#available_materials_wallpanel')[0].selectize.setValue(JSON.parse(settings.available_materials_wallpanel));


                $('#handle_orientation').val(settings.handle_orientation);
                $('#handle_orientation').change();
                $('#handle_lockers_position').val(settings.handle_lockers_position);
                $('#handle_lockers_position').change();
                $('#handle_selected_model')[0].selectize.setValue(JSON.parse(settings.handle_selected_model));


                $('#available_materials_walls')[0].selectize.setValue(JSON.parse(settings.available_materials_walls));

                var f_select = $('#available_materials_floor')[0].selectize;
                f_select.setValue(JSON.parse(settings.available_materials_floor));
                var flag = 1;
                var f_items = JSON.parse(settings.available_materials_floor);
                for (var v = 0; v < f_items.length; v++){
                    for ( var i = 0 ; i < f_select.options; i++){
                        if(f_items[v] == f_select.options[i].value ){
                            flag = 0;
                        }
                    }
                }
                if(flag === 1){
                    $('#sub_form').find('.ibox-content').toggleClass('sk-loading');
                }
            },100);
        }


        var xhr;
        var ajax_url = $('#ajax_url').val();


        var selected_facade_model = $('#selected_facade_model').selectize({
            create: false,
            render: {
                option: function(item, escape) {
                    return '<div style="margin-bottom: 10px">' +
                        '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.icon) +'">' +
                        '<span class="name">'+ escape(item.name) +'</span>' +
                        '</div>';
                }
            },
            onChange: function(value) {

                var select = selected_material_facade[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_materials_by_facade_model_ajax/' + value,
                        async: false,
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();

                            for (var i = 0; i < data.length; i++) {
                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;


                                    if(data[i].items[m].map !== null){
                                        map = data[i].items[m].map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    } else {
                                        map = null;
                                    }

                                    if(data[i].items[m].code !== null){
                                        name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                    } else {
                                        name = data[i].items[m].name;
                                    }


                                    select.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    });



                                }
                            }

                            if(stop_show_change === 0){
                                select.refreshOptions();
                            } else {
                                $('#selected_material_facade')[0].selectize.setValue(settings.selected_material_facade);
                            }

                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_facade = $('#selected_material_facade').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== null){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });


        var available_materials_corpus = $('#available_materials_corpus').selectize({
            create: false,
            plugins: ['remove_button'],
            onChange: function(value) {

                var select = selected_material_corpus[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_materials_by_category_ajax/',
                        type: 'post',
                        async: false,
                        data: {data:value},
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();
                            for (var i = 0; i < data.length; i++) {

                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;

                                    if(data[i].items[m].map !== null){
                                        map = data[i].items[m].map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    }else {
                                        map = null;
                                    }

                                    if(data[i].items[m].code !== null){
                                        name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                    } else {
                                        name = data[i].items[m].name;
                                    }

                                    select.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    });



                                }
                            }

                            if(stop_show_change !== 0){
                                $('#selected_material_corpus')[0].selectize.setValue(settings.selected_material_corpus);
                            }

                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_corpus = $('#selected_material_corpus').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== null){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });

        var available_corpus_thickness = $('#available_corpus_thickness').selectize({
            create: false,
            plugins: ['remove_button']
        });



        var available_materials_glass = $('#available_materials_glass').selectize({
            create: false,
            plugins: ['remove_button'],
            onChange: function(value) {

                var select = selected_material_glass[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_glass_materials_by_category_ajax/',
                        type: 'post',
                        async: false,
                        data: {data:value},
                        success: function (results) {
                            var data = JSON.parse(results);
                            console.log(data)

                            select.enable();
                            for (var i = 0; i < data.length; i++) {

                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;
                                    var icon = undefined;
                                    let params = JSON.parse(data[i].items[m].params);



                                    if(params.params.icon ){
                                        icon = params.params.icon;
                                        if(icon.indexOf('common_assets') !== -1){

                                        } else {
                                            icon = $('#asset_path').val() + icon;
                                        }
                                    }

                                    if(params.params.map ){
                                        map = params.params.map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    }

                                    if(params.code !== null){
                                        name = params.name + ' ' + params.code;
                                    } else {
                                        name = params.name;
                                    }

                                    select.addOption({
                                        value: params.id,
                                        text: name,
                                        map: icon !== undefined ? icon : map,
                                        color: params.params.color
                                    });



                                }
                            }

                            if(stop_show_change !== 0){
                                $('#selected_material_glass')[0].selectize.setValue(settings.selected_material_glass);
                            }

                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_glass = $('#selected_material_glass').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== undefined){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });


        $('#cokol_as_corpus').change(function () {
            if($(this).val() == 1){
                $('#selected_cokol_material_wrapper').addClass('hidden');
            } else {
                $('#selected_cokol_material_wrapper').removeClass('hidden');
            }
        });

        var available_materials_cokol = $('#available_materials_cokol').selectize({
            create: false,
            plugins: ['remove_button'],
            onChange: function(value) {

                var select = selected_material_cokol[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_materials_by_category_ajax/',
                        type: 'post',
                        async: false,
                        data: {data:value},
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();
                            for (var i = 0; i < data.length; i++) {

                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;

                                    if(data[i].items[m].map !== null){
                                        map = data[i].items[m].map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    }else {
                                        map = null;
                                    }

                                    if(data[i].items[m].code !== null){
                                        name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                    } else {
                                        name = data[i].items[m].name;
                                    }

                                    select.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    });



                                }
                            }

                            if(stop_show_change !== 0){
                                $('#selected_material_cokol')[0].selectize.setValue(settings.selected_material_cokol);
                            }

                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_cokol = $('#selected_material_cokol').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== null){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });


        var available_materials_tabletop = $('#available_materials_tabletop').selectize({
            create: false,
            plugins: ['remove_button'],
            onChange: function(value) {

                var select = selected_material_tabletop[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_materials_by_category_ajax/',
                        type: 'post',
                        async: false,
                        data: {data:value},
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();
                            for (var i = 0; i < data.length; i++) {

                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;

                                    if(data[i].items[m].map !== null){
                                        map = data[i].items[m].map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    }else {
                                        map = null;
                                    }

                                    if(data[i].items[m].code !== null){
                                        name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                    } else {
                                        name = data[i].items[m].name;
                                    }

                                    select.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    });



                                }
                            }

                            if(stop_show_change !== 0){
                                $('#selected_material_tabletop')[0].selectize.setValue(settings.selected_material_tabletop);
                            }

                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_tabletop = $('#selected_material_tabletop').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== null){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });


        var available_materials_wallpanel = $('#available_materials_wallpanel').selectize({
            create: false,
            plugins: ['remove_button'],
            onChange: function(value) {

                var select = selected_material_wallpanel[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_materials_by_category_ajax/',
                        type: 'post',
                        async: false,
                        data: {data:value},
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();
                            for (var i = 0; i < data.length; i++) {

                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;

                                    if(data[i].items[m].map !== null){
                                        map = data[i].items[m].map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    }else {
                                        map = null;
                                    }

                                    if(data[i].items[m].code !== null){
                                        name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                    } else {
                                        name = data[i].items[m].name;
                                    }

                                    select.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    });



                                }
                            }

                            if(stop_show_change !== 0){
                                $('#selected_material_wallpanel')[0].selectize.setValue(settings.selected_material_wallpanel);
                            }

                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_wallpanel = $('#selected_material_wallpanel').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== null){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });


        var handle_selected_model = $('#handle_selected_model').selectize({
            create: false,
            render: {
                option: function(item, escape) {
                    return '<div style="margin-bottom: 10px">' +
                        '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.icon) +'">' +
                        '<span class="name">'+ escape(item.name) +'</span>' +
                        '</div>';
                }
            },
            onChange: function(value) {

                if(value == 0){
                    $('#handle_preferable_size_wrapper').addClass('hidden');
                    return
                }

                $('#handle_preferable_size_wrapper').removeClass('hidden');

                var select = handle_preferable_size[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_handle_ajax/' + value,
                        async: false,
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();


                            for (var i = 0; i < data.length; i++) {

                                var name = 'Ширина ' + data[i].width + 'мм, <?php echo $lang_arr['axis_size']?> ' + data[i].axis_size + 'мм';

                                select.addOption({
                                    value: i,
                                    text: name
                                });

                            }

                            if(stop_show_change === 0){
                                select.refreshOptions();
                            } else {
                                if(settings.handle_preferable_size){
                                    $('#handle_preferable_size')[0].selectize.setValue(settings.handle_preferable_size);
                                }
                            }


                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var handle_preferable_size = $('#handle_preferable_size').selectize({
            create: false
        });


        var available_materials_walls = $('#available_materials_walls').selectize({
            create: false,
            plugins: ['remove_button'],
            onChange: function(value) {

                var select = selected_material_walls[0].selectize;

                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {
                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_materials_by_category_ajax/',
                        type: 'post',
                        async: false,
                        data: {data:value},
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();
                            for (var i = 0; i < data.length; i++) {

                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;

                                    if(data[i].items[m].map !== null){
                                        map = data[i].items[m].map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    }else {
                                        map = null;
                                    }

                                    if(data[i].items[m].code !== null){
                                        name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                    } else {
                                        name = data[i].items[m].name;
                                    }

                                    select.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    });



                                }
                            }

                            if(stop_show_change !== 0){
                                $('#selected_material_walls')[0].selectize.setValue(settings.selected_material_walls);
                            }

                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_walls = $('#selected_material_walls').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== null){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });


        var available_materials_floor = $('#available_materials_floor').selectize({
            create: false,
            plugins: ['remove_button'],
            onChange: function(value) {

                var select = selected_material_floor[0].selectize;



                select.disable();
                select.clearOptions();
                select.setValue("", true);

                select.load(function (callback) {



                    xhr && xhr.abort();
                    xhr = $.ajax({
                        url: ajax_url + 'get_materials_by_category_ajax/',
                        type: 'post',
                        async: false,
                        data: {data:value},
                        success: function (results) {
                            var data = JSON.parse(results);
                            select.enable();

                            console.log(data);

                            for (var i = 0; i < data.length; i++) {


                                for(var m = 0; m < data[i].items.length; m++){
                                    var name;
                                    var map;

                                    if(data[i].items[m].map !== null){
                                        map = data[i].items[m].map;
                                        if(map.indexOf('common_assets') !== -1){

                                        } else {
                                            map = $('#asset_path').val() + map;
                                        }
                                    }else {
                                        map = null;
                                    }

                                    if(data[i].items[m].code !== null){
                                        name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                    } else {
                                        name = data[i].items[m].name;
                                    }

                                    select.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    });
                                }
                            }



                            if(stop_show_change !== 0){


                                $('#selected_material_floor')[0].selectize.setValue(settings.selected_material_floor);

                                setTimeout(function () {
                                    $('.loader').fadeOut();
                                    stop_show_change = 1;
                                },300)

                            }

                            callback(results);
                        },
                        error: function () {

                            callback();
                        }
                    })
                });

            }
        });
        var selected_material_floor = $('#selected_material_floor').selectize({
            create:false,
            render: {
                option: function(item, escape) {
                    var string = '<div style="margin-bottom: 10px">';
                    if(item.map !== null){
                        string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                    } else {
                        string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                    }
                    string += '<span class="name">'+ escape(item.text) +'</span>';
                    string += '</div>';

                    return string;
                }
            }
        });


        $('#sub_form').submit(function (e) {

            e.preventDefault();

            $('#submit_button').attr('disabled', true);

            var errors = [];

            if($('#selected_facade_model').val() === ''){
                errors.push('Модель фасада не выбрана');
                $('label[for="selected_facade_model-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_facade_model-selectized"]').css('color', '#333')
            }

            if($('#selected_material_facade').val() === ''){
                errors.push('Цвет фасадов по умолчанию не выбран');
                $('label[for="selected_material_facade-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_material_facade-selectized"]').css('color', '#333')
            }


            if($('#available_materials_corpus').val() === null){
                errors.push('Доступные материалы корпуса не выбраны');
                $('label[for="available_materials_corpus-selectized"]').css('color', 'red')
            } else {
                $('label[for="available_materials_corpus-selectized"]').css('color', '#333')
            }

            if($('#selected_material_corpus').val() === ''){
                errors.push('Цвет корпуса по умолчанию не выбран');
                $('label[for="selected_material_corpus-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_material_corpus-selectized"]').css('color', '#333')
            }

            if($('#available_materials_cokol').val() === null){
                errors.push('Доступные материалы цоколя не выбраны');
                $('label[for="available_materials_cokol-selectized"]').css('color', 'red')
            } else {
                $('label[for="available_materials_cokol-selectized"]').css('color', '#333')
            }

            if($('#cokol_as_corpus').val() == 0){
                if($('#selected_material_cokol').val() === ''){
                    errors.push('Цвет цоколя по умолчанию не выбран');
                    $('label[for="selected_material_cokol-selectized"]').css('color', 'red')
                } else {
                    $('label[for="selected_material_cokol-selectized"]').css('color', '#333')
                }
            }



            if($('#available_materials_tabletop').val() === null){
                errors.push('Доступные материалы столешницы не выбраны');
                $('label[for="available_materials_tabletop-selectized"]').css('color', 'red')
            } else {
                $('label[for="available_materials_tabletop-selectized"]').css('color', '#333')
            }

            if($('#selected_material_tabletop').val() === ''){
                errors.push('Цвет столешницы по умолчанию не выбран');
                $('label[for="selected_material_tabletop-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_material_tabletop-selectized"]').css('color', '#333')
            }


            if($('#available_materials_wallpanel').val() === null){
                errors.push('<?php echo $lang_arr['wallpanel_available_materials']?> не выбраны');
                $('label[for="available_materials_wallpanel-selectized"]').css('color', 'red')
            } else {
                $('label[for="available_materials_wallpanel-selectized"]').css('color', '#333')
            }

            if($('#selected_material_wallpanel').val() === ''){
                errors.push('Фартук по умолчанию не выбран');
                $('label[for="selected_material_wallpanel-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_material_wallpanel-selectized"]').css('color', '#333')
            }


            if ($('#handle_selected_model').val() === '') {
                errors.push('Модель ручки не выбрана');
                $('label[for="handle_selected_model-selectized"]').css('color', 'red')
            } else {
                $('label[for="handle_selected_model-selectized"]').css('color', '#333')
            }

            if ($('#handle_selected_model').val() != 0 || $('#handle_selected_model').val() === "") {
                if ($('#handle_preferable_size').val() === '') {
                    errors.push('Предпочтительный размер ручки не выбран');
                    $('label[for="handle_preferable_size-selectized"]').css('color', 'red')
                } else {
                    $('label[for="handle_preferable_size-selectized"]').css('color', '#333')
                }
            }

            if($('#available_materials_walls').val() === null){
                errors.push('Доступные материалы стен не выбраны');
                $('label[for="available_materials_walls-selectized"]').css('color', 'red')
            } else {
                $('label[for="available_materials_walls-selectized"]').css('color', '#333')
            }

            if($('#selected_material_walls').val() === ''){
                errors.push('Материал стен по умолчанию не выбран');
                $('label[for="selected_material_walls-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_material_walls-selectized"]').css('color', '#333')
            }

            if($('#available_materials_floor').val() === null){
                errors.push('Доступные материалы пола не выбраны');
                $('label[for="available_materials_floor-selectized"]').css('color', 'red')
            } else {
                $('label[for="available_materials_floor-selectized"]').css('color', '#333')
            }

            if($('#selected_material_floor').val() === ''){
                errors.push('Материал пола по умолчанию не выбран');
                $('label[for="selected_material_floor-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_material_floor-selectized"]').css('color', '#333')
            }



            if(errors.length){
                var text = '';
                for(var i = 0; i < errors.length; i++){
                    text += (i+1) + ' ' + errors[i] + '\n'

                }
                alert(text);
                $('#submit_button').attr('disabled', false);

                return false;
            } else {

                console.log($('#available_corpus_thickness').val())

                $.ajax({
                    url: $('#sub_form').attr('action'),
                    type: 'post',
                    data: $(this).serialize()
                }).done(function(data) {
                    console.log(data);
                    $('#submit_button').attr('disabled', false);
                    toastr.success('<?php echo $lang_arr['success']?>')

                });
            }

        })

    });

</script>

<style>
    .selectize-control.multi .selectize-input > div{
        background: #428bca;
        color: #ffffff;
        padding-left: 10px;
        padding-right: 10px;
    }

    .selectize-input {
        z-index: 0;
    }

    form{
        position: relative;
    }

    .loader{

        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 999;
        background: #ffffff;
        text-align: center;
        font-weight: bold;
        font-size: 30px;
    }

</style>