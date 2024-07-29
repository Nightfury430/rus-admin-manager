
<?php

$langs = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/common_assets/data/languages.json'));

foreach ($langs as $item){
	if($item->code == 'ru') $current_language = $item;
}


?>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['theme_label']?></h2>
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
            <form id="sub_form" method="post" accept-charset="UTF-8" action="<?php echo site_url('theme/update/') ?>">

                <div class="ibox">

                    <div class="ibox-content">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="active"><?php echo $lang_arr['active']?></label>
                                    <select class="form-control" name="active" id="active">
                                        <option <?php if($theme['active'] == 1) echo 'selected'; ?> value="1"><?php echo $lang_arr['yes']?></option>
                                        <option <?php if($theme['active'] == 0) echo 'selected'; ?> value="0"><?php echo $lang_arr['no']?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_main_background']?></label>
                                    <input type="text" class="form-control" name="theme_main_background" id="theme_main_background" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_main_text_color']?></label>
                                    <input type="text" class="form-control" name="theme_main_text_color" id="theme_main_text_color" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_border_color']?></label>
                                    <input type="text" class="form-control" name="theme_border_color" id="theme_border_color" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_acc_body_color']?></label>
                                    <input type="text" class="form-control" name="theme_acc_body_color" id="theme_acc_body_color" value="#ffffff">
                                </div>
                            </div>


                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_send_order_color']?></label>
                                    <input type="text" class="form-control" name="theme_send_order_color" id="theme_send_order_color" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_send_order_text_color']?></label>
                                    <input type="text" class="form-control" name="theme_send_order_text_color" id="theme_send_order_text_color" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_send_order_hover_color']?></label>
                                    <input type="text" class="form-control" name="theme_send_order_hover_color" id="theme_send_order_hover_color" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_send_order_hover_text_color']?></label>
                                    <input type="text" class="form-control" name="theme_send_order_hover_text_color" id="theme_send_order_hover_text_color" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_selected_material_color']?></label>
                                    <input type="text" class="form-control" name="theme_selected_material_color" id="theme_selected_material_color" value="#ffffff">
                                </div>
                            </div>


                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_buttons_background']?></label>
                                    <input type="text" class="form-control" name="theme_buttons_background" id="theme_buttons_background" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_buttons_text_color']?></label>
                                    <input type="text" class="form-control" name="theme_buttons_text_color" id="theme_buttons_text_color" value="#ffffff">
                                </div>
                            </div>


                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_buttons_hover_background']?></label>
                                    <input type="text" class="form-control" name="theme_buttons_hover_background" id="theme_buttons_hover_background" value="#ffffff">
                                </div>
                            </div>

                            <div class="col-sm-3 clrpckr">
                                <div class="form-group">
                                    <label for="color"><?php echo $lang_arr['theme_buttons_hover_text_color']?></label>
                                    <input type="text" class="form-control" name="theme_buttons_hover_text_color" id="theme_buttons_hover_text_color" value="#ffffff">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-sm-12">

                                <div id="preview">
                                    <div class="interface">

                                        <div id="top_panel" class="clr_background">

                                            <div class="b_logo">
                                            </div>



                                            <div class="top_panel_group buttons_top_panel">

                                                <div title="Отменить последнее действие" id="undo_action" class="slide_button slide_right inactive">
                                                    <i class="glyphicon glyphicon-arrow-left"></i><span>Отменить последнее действие</span>
                                                </div>

                                                <div title="Повторить действие" id="redo_action" class="slide_button slide_right inactive">
                                                    <i class="glyphicon glyphicon-arrow-right"></i><span>Повторить действие</span>
                                                </div>


                                                <div title="Новый проект" id="clear_project" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon-file"></i><span>Новый проект</span>
                                                </div>
                                                <div title="Загрузить проект" id="load_button" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon-folder-open"></i><span>Загрузить проект</span>
                                                </div>
                                                <div title="Сохранить проект" id="save_button" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon-floppy-save"></i><span>Сохранить проект</span>
                                                </div>

                                                <div class="slide_button slide_right print_block_topmenu">
                                                    <i class="glyphicon glyphicon-print"></i>
                                                    <div class="top_submenu">
                                                        <ul>
                                                            <li title="Открыть PDF для печати (в новой вкладке)" id="print_button" class="submenu_button">
                                                                <i class="glyphicon glyphicon-print"></i> <span>Открыть PDF для печати (в новой вкладке)</span>
                                                            </li>
                                                            <li title="Скачать PDF" id="save_pdf_button" class="submenu_button">
                                                                <i class="glyphicon glyphicon-save"></i> <span>Скачать PDF</span>
                                                            </li>
                                                            <li title="Печать средствами браузера" id="print_button_old" class="submenu_button">
                                                                <i class="glyphicon glyphicon-print"></i> <span>Печать средствами браузера</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div title="Сохранить скриншот" id="screen_save" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon-picture"></i><span>Сохранить скриншот</span>
                                                </div>
                                                <div title="Контуры" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                    <div class="top_submenu">
                                                        <ul>
                                                            <li id="mode_button" class="submenu_button">
                                                                <i class="glyphicon glyphicon-pencil"></i> <span>Режим контуров</span>
                                                            </li>
                                                            <li id="transparent_edges_mode" class="submenu_button">
                                                                <i class="glyphicon glyphicon-pencil"></i> <span>Прозрачные контуры</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="slide_button slide_right views_block">
                                                    <i title="Проекции" class="glyphicon glyphicon-facetime-video"></i>
                                                    <div class="top_submenu">
                                                        <ul>
                                                            <li id="camera_3d_mode" class="submenu_button clicked">
                                                                <i class="mdi mdi-cube-outline"></i> <span>3D режим</span>
                                                            </li>
                                                            <li id="camera_projection_top" class="submenu_button">
                                                                <i></i> <span>Проекция сверху</span>
                                                            </li>
                                                            <li id="camera_projection_front" class="submenu_button">
                                                                <i></i> <span>Проекция передней стены</span>
                                                            </li>
                                                            <li id="camera_projection_back" class="submenu_button">
                                                                <i></i> <span>Проекция задней стены</span>
                                                            </li>
                                                            <li id="camera_projection_left" class="submenu_button">
                                                                <i></i> <span>Проекция левой стены</span>
                                                            </li>
                                                            <li id="camera_projection_right" class="submenu_button">
                                                                <i></i> <span>Проекция правой стены</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="slide_button slide_right">
                                                    <i title="Показывать размеры" class="glyphicon glyphicon-resize-horizontal"></i>
                                                    <div class="top_submenu">
                                                        <ul>
                                                            <li id="no_sizes_button" class="submenu_button">
                                                                <i class="glyphicon glyphicon-resize-horizontal"></i> <span>Без размеров</span>
                                                            </li>
                                                            <li id="basic_sizes_button" class="submenu_button clicked">
                                                                <i class="glyphicon glyphicon-resize-horizontal"></i> <span>Основные размеры</span>
                                                            </li>
                                                            <li id="all_sizes_button" class="submenu_button">
                                                                <i class="glyphicon glyphicon-resize-horizontal"></i> <span>Все размеры</span>
                                                            </li>
                                                            <li id="additional_sizes_button" class="submenu_button">
                                                                <i class="glyphicon glyphicon-resize-horizontal"></i> <span>Только дополнительные размеры</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div title="Показывать / скрыть комментарии" id="comments_button" class="slide_button slide_right clicked">
                                                    <i class="glyphicon glyphicon-comment"></i><span>Комментарии</span>
                                                </div>
                                                <div title="На весь экран" id="full_screen_button" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon-fullscreen"></i>
                                                </div>
                                                <div title="Экспортировать в obj" id="obj_export_button" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon glyphicon-compressed"></i>
                                                </div>
                                                <div title="Помощь" id="help_button" class="slide_button slide_right">
                                                    <i class="glyphicon glyphicon-question-sign"></i><span>Помощь</span>
                                                </div>

                                            </div>

                                            <div class="top_panel_group order_block">

                                                <button type="button" id="show_order_modal" class="btn btn-sm">
                                                    Отправить на расчет             </button>


                                                <p class="k_sum">
                                                    Стоимость: <span>128 658 р.</span>

                                                </p>

                                                <span class="custom_sizes_active">Итоговая цена может отличаться</span>

                                            </div>
                                            <div class="top_panel_group client_data">
                                            </div>


                                        </div>

                                        <div id="left_panel" class="clr_background">

                                            <div class="accordion_block clr_background">
                                                <div class="accordion_heading clr_background selected_heading">
                                                    <i class="mdi mdi-home-variant"></i><span><?php echo $current_language->data->room?></span>
                                                </div>
                                                <div class="accordion_body clr_background tvis">
                                                    <button type="button" class="ltab btn-block btn btn-default btn-sm" data-target="room_settings">
												        <?php echo $current_language->data->sizes?>
                                                    </button>
                                                    <button type="button" class="ltab btn-block btn btn-default btn-sm" data-target="room_panel">
												        <?php echo $current_language->data->constructions?>
                                                    </button>

                                                    <button style="display: none" type="button" class="ltab btn-block btn btn-default btn-sm communications_select_button" data-target="communications_panel">
												        <?php echo $current_language->data->communications?>
                                                    </button>

                                                    <button type="button" class="ltab btn-block btn btn-default btn-sm walls_materials" data-target="walls_panel">
												        <?php echo $current_language->data->walls?>
                                                    </button>
                                                    <button type="button" class="ltab btn-block btn btn-default btn-sm floor_materials" data-target="floor_panel">
												        <?php echo $current_language->data->floor?>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="accordion_block clr_background not_working ltab hidden kitchen_models" data-target="kitchen_models">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-brush"></i><span style="line-height: 15px"><?php echo $current_language->data->kitchen_models?></span>
                                                </div>
                                            </div>



                                            <div class="accordion_block clr_background not_working ltab" data-target="project_settings">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-settings"></i><span style="line-height: 15px"><?php echo $current_language->data->kitchen_settings?></span>
                                                </div>
                                            </div>




                                            <div class="accordion_block clr_background modules_accordion">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-fridge-filled-top"></i><span><?php echo $current_language->data->modules?></span>
                                                </div>

                                                <div class="accordion_body clr_background " role="group" aria-label="...">
                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm" data-target="bottom_modules_panel">
												        <?php echo $current_language->data->bottom?>
                                                    </button>
                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm" data-target="top_modules_panel">
												        <?php echo $current_language->data->top?>
                                                    </button>
                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm" data-target="penals_panel">
												        <?php echo $current_language->data->penals?>
                                                    </button>

                                                    <button style="display: none" id="add_module_configurator" type="button" class="ltab btn btn-block btn-default btn-sm">
                                                        Создать свой
                                                    </button>



                                                    <button type="button" style="display: none" class="decorations_button ltab btn btn-block btn-default btn-sm" data-target="decorations_panel">
                                                        Декоративные
                                                    </button>

                                                    <!--                <button type="button" class="ltab btn btn-block btn-default btn-sm" data-target="tech_panel">-->
                                                    <!--                    --><?php //echo $current_language->data->tech?>
                                                    <!--                </button>-->

                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm" data-target="shelves_panel">
												        <?php echo $current_language->data->shelves?>
                                                    </button>

                                                    <button style="padding-left: 0; padding-right: 0" type="button"
                                                            class="ltab btn btn-block btn-default btn-sm"
                                                            data-target="bardesks_panel">
												        <?php echo $current_language->data->bardesks?>
                                                    </button>

                                                    <!--                <button style="display: none" type="button" class="ltab btn btn-block btn-default btn-sm other_models_button" data-target="other_panel">-->
                                                    <!--                    --><?php //echo $current_language->data->interior?>
                                                    <!--                </button>-->


                                                </div>
                                            </div>

                                            <div class="accordion_block clr_background">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-format-paint"></i><span><?php echo $current_language->data->materials?></span>
                                                </div>

                                                <div class="accordion_body clr_background " role="group" style="display: block;" aria-label="...">

                                                    <button type="button" style="padding: 5px 0px;" class="ltab btn btn-block btn-default btn-sm facades_materials" data-target="facade_models_panel">
												        <?php echo $current_language->data->facades_material ?>
                                                    </button>

                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm facades_colors"
                                                            data-target="facade_materials_panel">
												        <?php echo $current_language->data->facades_color?>
                                                    </button>

                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm glass_materials"
                                                            data-target="glass_materials_panel">
                                                        Наполнение витрин
                                                    </button>


                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm corpus_materials" data-target="corpus_panel">
												        <?php echo $current_language->data->corpus?>
                                                    </button>
                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm tabletop_materials"
                                                            data-target="tabletop_panel">
												        <?php echo $current_language->data->tabletop?>
                                                    </button>

                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm wallpanel_materials"
                                                            data-target="wall_panel_panel">
												        <?php echo $current_language->data->wallpanel?>
                                                    </button>

                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm cokol_materials" data-target="cokol_panel">
												        <?php echo $current_language->data->cokol?>
                                                    </button>


                                                </div>
                                            </div>


                                            <div class="accordion_block clr_background not_working ltab" data-target="tech_panel">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-television"></i> <?php echo $current_language->data->tech?>
                                                </div>
                                            </div>


                                            <div class="accordion_block clr_background not_working ltab" data-target="other_panel">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-coffee"></i> <?php echo $current_language->data->interior?>
                                                </div>
                                            </div>




                                            <div class="accordion_block clr_background not_working ltab hidden facade_systems" data-target="facade_systems">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-book-open-variant"></i><span style="line-height: 15px">Фасадные системы</span>
                                                </div>
                                            </div>


                                            <div class="accordion_block clr_background furniture_block">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-hammer"></i><span><?php echo $current_language->data->furniture?></span>
                                                </div>

                                                <div class="accordion_body clr_background ">
                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm" data-target="handles_panel">
												        <?php echo $current_language->data->handles?>
                                                    </button>
                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm" data-target="hinges_panel">
												        <?php echo $current_language->data->loops_lockers?>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="accordion_block clr_background lightning_block">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-lightbulb-on"></i><span><?php echo $current_language->data->lighting?></span>
                                                </div>
                                                <div class="accordion_body clr_background">
                                                    <button class="btn btn-block btn-default btn-sm" id="toggle_lights"><?php echo $current_language->data->light_off ?></button>
                                                </div>
                                                <div class="accordion_body clr_background">
                                                    <p style="text-align: center"><?php echo $current_language->data->shadows?></p>
                                                    <select style="color: #000000; width: 100%" name="" id="shadow_settings">
                                                        <option selected value="no"><?php echo $current_language->data->off?></option>
                                                        <option value="yes"><?php echo $current_language->data->on?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="accordion_block clr_background not_working ltab" data-target="kitchen_style_panel">
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-auto-fix"></i><span><?php echo $current_language->data->kitchens?></span>
                                                </div>

                                                <div class="accordion_body clr_background ">
                                                    <button type="button" class="ltab btn btn-block btn-default btn-sm kitchen_style_button" data-target="kitchen_style_panel">
												        <?php echo $current_language->data->kitchen_models?>
                                                    </button>
                                                </div>
                                            </div>


                                            <div id="accessories_shop_button" class="accordion_block clr_background not_working" >
                                                <div class="accordion_heading clr_background">
                                                    <i class="mdi mdi-cart"></i><span style="line-height: 15px">Комплектующие</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="left_subpanel w200 facades_material_select active" data-panel="facade_materials_panel"
                                             style="left: 175px; opacity: 1;">
                                            <div class="mask">
                                                <div class="left_subpanel_heading">
                                                    <p>Цвет фасадов</p>
                                                    <div class="panel_close"><i class="glyphicon glyphicon-remove"></i></div>
                                                </div>

                                                <div class="left_subpanel_content">
                                                    <div class="left_subpanel_content_fixed_part">
                                                        <button class="set_different_facade_materials btn btn-block btn-default btn-sm same">Раздельные цвета
                                                        </button>
                                                        <p class="content_heading mt10">Фасады:</p>
                                                        <button style="margin-bottom: 5px;" class="btn btn-block btn-default btn-sm">Свернуть все</button>
                                                    </div>
                                                    <div class="left_subpanel_content_scrollable_part" style="height: 866px;">
                                                        <div class="acc_block">
                                                            <div class="acc_heading">Цвета RAL Classic</div>
                                                            <div class="acc_body opened" style="display: block;">
                                                                <div class="materials_category change_top_facade_material" data-target="1">
                                                                    <div class="material_subcategory">
                                                                        <div class="material_subcategory_heading">Желтые тона</div>
                                                                        <div class="material_subcategory_body">
                                                                            <div class="mat_wrapper selected" data-id="737" title="RAL 1014 Слоновая кость">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #ded09f"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="736" title="RAL 1013 Жемчужно-белый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #e9e5ce"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="735" title="RAL 1012 Лимонно-жёлтый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #d9c022"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="734" title="RAL 1011 Коричнево-бежевый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #af8a54"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="733" title="RAL 1007 Нарциссово-жёлтый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #e79c00"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="732" title="RAL 1006 Кукурузно-жёлтый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #e1a100"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="731" title="RAL 1005 Медово-жёлтый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #c89f04"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="730" title="RAL 1004 Жёлто-золотой">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #e2b007"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="729" title="RAL 1003 Сигнальный жёлтый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #f7ba0b"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="727" title="RAL 1001 Бежевый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #d1bc8a"></div>
                                                                            </div>
                                                                            <div class="mat_wrapper" data-id="726" title="RAL 1000 Зелёно-бежевый">
                                                                                <div title="Индивидуальный цвет фасадов" class="mat_colorpicker"><i
                                                                                            class="mdi mdi-eyedropper-variant"></i></div>
                                                                                <div class="material" style="background: #ccc58f"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button id="submit_button" class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>
                    </div>

                </div>





<!--                <input class="btn btn-w btn-success" type="submit" name="submit" value="--><?php //echo $lang_arr['save']?><!--"/>-->
            </form>

        </div>
    </div>
</div>

<link rel="stylesheet" href="/common_assets/css/style.css">


<style>

    @media (min-width: 768px){
        /*#page-wrapper {*/
            /*padding: 22px 10px 52px 10px;*/
        /*}*/
    }

    .clrpckr{
        min-height: 90px;
    }

    #preview{
        position: relative;
        width: 100%;
        min-height: 600px;
        background: url("/common_assets/images/preview_background.jpg");
    }

    .material{
        height: 77px!important;
    }

    .views_block .top_submenu{
        display: block!important;
        margin-left: -10px;
    }

    #preview #show_order_modal{
        transition: 0s;
    }

    body{
        overflow: auto!important;
    }

    label{
        display: block;
    }


</style>



<link rel="stylesheet" href="/common_assets/libs/mdi/materialdesignicons.min.css">

<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">

<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>

<script>

    $(document).ready(function () {

        let theme_main_background = '<?php echo $theme['theme_main_background']?>';
        let theme_main_text_color = '<?php echo $theme['theme_main_text_color']?>';
        let theme_border_color = '<?php echo $theme['theme_border_color']?>';

        let theme_send_order_color = '<?php echo $theme['theme_send_order_color']?>';
        let theme_send_order_text_color = '<?php echo $theme['theme_send_order_text_color']?>';
        let theme_send_order_hover_color = '<?php echo $theme['theme_send_order_hover_color']?>';
        let theme_send_order_hover_text_color = '<?php echo $theme['theme_send_order_hover_text_color']?>';

        let theme_buttons_background = '<?php echo $theme['theme_buttons_background']?>';
        let theme_buttons_text_color = '<?php echo $theme['theme_buttons_text_color']?>';

        let theme_buttons_hover_background = '<?php echo $theme['theme_buttons_hover_background']?>';
        let theme_buttons_hover_text_color = '<?php echo $theme['theme_buttons_hover_text_color']?>';

        let theme_acc_body_color = '<?php echo $theme['theme_acc_body_color']?>';

        let theme_selected_material_color = '<?php echo $theme['theme_selected_material_color']?>';

        let main_bg_arr = [
            '#top_panel',
            '#left_panel',
            '#state_panel',
            '.accordion_heading',
            '.left_subpanel .mask',
            '.left_subpanel .left_subpanel',
            '.top_submenu',
            '#materials_panel',
            '#materials_panel_button',
        ];

        let acc_body_bg = [
            '.accordion_block',
        ];

        let selected_arr = [
            '.mat_wrapper.selected',
        ];

        let border_arr = [
            '#top_panel',
            '#left_panel',
            '.accordion_heading',
            '.accordion_block',
            '.left_subpanel .mask',
            '.left_subpanel .left_subpanel',
            '.top_submenu',
            '#materials_panel',
            '#materials_panel_button',
            '.top_panel_group',
            '.acc_block',
            '.materials_panel_body > div',
            '.submenu_button',
        ];

        let main_text_arr = [
            '#top_panel',
            '.k_sum',
            '#left_panel',
            '#state_panel',
            '.accordion_heading',
            '.accordion_block',
            '.left_subpanel .mask',
            '.left_subpanel .left_subpanel',
            '.top_submenu',
            '#materials_panel',
            '#materials_panel_button',
            '.top_panel_group',
            '.acc_block',
            '.materials_panel_body > div',
            '.left_subpanel .panel_close',
            '.material_subcategory_heading',
        ];

        let main_text_camera_icons = [
            '#camera_projection_front i',
            '#camera_projection_back i',
            '#camera_projection_left i',
            '#camera_projection_right i',
        ]

        let send_order_arr = [
            '.submenu_button.clicked',
            '#show_order_modal'
        ];

        let button_bg_arr = [
            '.btn-default',
        ];

        let button_hover_bg_arr = [
            '.btn-default:hover',
            '.btn-default.active, .btn-default:active',
            '.btn-default:focus, .btn-default:hover',
            '.btn-default.active.focus, .btn-default.active:focus, .btn-default.active:hover, .btn-default:active.focus, .btn-default:active:focus, .btn-default:active:hover, .open>.dropdown-toggle.btn-default.focus, .open>.dropdown-toggle.btn-default:focus, .open>.dropdown-toggle.btn-default:hover',
        ];


        let clicked = '.slide_button.slide_right.clicked';


        for (let i = 0; i < main_bg_arr.length; i++){
            $(main_bg_arr[i]).css('background', theme_main_background)
        }
        for (let i = 0; i < main_text_arr.length; i++){
            $(main_text_arr[i]).css('color', theme_main_text_color)
        }
        for (let i = 0; i < main_text_camera_icons.length; i++){
            $(main_text_camera_icons[i]).css({
                'color': theme_main_text_color,
                'border-color': theme_main_text_color
            })
        }
        for (let i = 0; i < border_arr.length; i++){
            $(border_arr[i]).css('border-color', theme_border_color)
        }
        for (let i = 0; i < acc_body_bg.length; i++){
            $(acc_body_bg[i]).css('background', theme_acc_body_color)
        }
        for (let i = 0; i < send_order_arr.length; i++){
            $(send_order_arr[i]).css('background', theme_send_order_color)
            $(send_order_arr[i]).css('color', theme_send_order_text_color)
            $(clicked).css('border-color', theme_send_order_color)
        }
        for (let i = 0; i < selected_arr.length; i++){
            $(selected_arr[i]).css('border-color', theme_selected_material_color)
        }
        for (let i = 0; i < button_bg_arr.length; i++){
            $(button_bg_arr[i]).css('background', theme_buttons_background)
        }
        for (let i = 0; i < button_bg_arr.length; i++){
            $(button_bg_arr[i]).css('color', theme_buttons_text_color)
        }

        $('#camera_projection_top i').css('background', theme_main_text_color);

        $("#theme_main_background").spectrum({
            color: theme_main_background,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < main_bg_arr.length; i++){
                    $(main_bg_arr[i]).css('background', color.toRgbString())
                }

                theme_main_background = color.toRgbString();
            },
            change: function(color) {
                for (let i = 0; i < main_bg_arr.length; i++){
                    $(main_bg_arr[i]).css('background', color.toRgbString())
                }

                theme_main_background = color.toRgbString();

            }
        });

        $("#theme_main_text_color").spectrum({
            color: theme_main_text_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < main_text_arr.length; i++){
                    $(main_text_arr[i]).css('color', color.toRgbString())
                }

                for (let i = 0; i < main_text_camera_icons.length; i++){
                    $(main_text_camera_icons[i]).css({
                        'color': color.toRgbString(),
                        'border-color': color.toRgbString()
                    })
                }

                theme_main_text_color = color.toRgbString();

                $('#camera_projection_top i').css('background', color.toRgbString());

            },
            change: function(color) {
                for (let i = 0; i < main_text_arr.length; i++){
                    $(main_text_arr[i]).css('color', color.toRgbString())
                }

                for (let i = 0; i < main_text_camera_icons.length; i++){
                    $(main_text_camera_icons[i]).css({
                        'color': color.toRgbString(),
                        'border-color': color.toRgbString()
                    })
                }

                theme_main_text_color = color.toRgbString();

                $('#camera_projection_top i').css('background', color.toRgbString());
            }
        });

        $("#theme_border_color").spectrum({
            color: theme_border_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < border_arr.length; i++){
                    $(border_arr[i]).css('border-color', color.toRgbString())
                }

                theme_border_color = color.toRgbString();
            },
            change: function(color) {
                for (let i = 0; i < border_arr.length; i++){
                    $(border_arr[i]).css('border-color', color.toRgbString())
                }

                theme_border_color = color.toRgbString();
            }
        });

        $("#theme_acc_body_color").spectrum({
            color: theme_acc_body_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < acc_body_bg.length; i++){
                    $(acc_body_bg[i]).css('background', color.toRgbString())
                }

                theme_acc_body_color = color.toRgbString();
            },
            change: function(color) {
                for (let i = 0; i < border_arr.length; i++){
                    $(acc_body_bg[i]).css('background', color.toRgbString())
                }

                theme_acc_body_color = color.toRgbString();
            }
        });

        $("#theme_send_order_color").spectrum({
            color: theme_send_order_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < send_order_arr.length; i++){
                    $(send_order_arr[i]).css('background', color.toRgbString())
                    $(clicked).css('border-color', color.toRgbString())
                }

                theme_send_order_color = color.toRgbString()
            },
            change: function(color) {
                for (let i = 0; i < send_order_arr.length; i++){
                    $(send_order_arr[i]).css('background', color.toRgbString())
                    $(clicked).css('border-color', color.toRgbString())
                }

                theme_send_order_color = color.toRgbString()
            }
        });

        $("#theme_send_order_text_color").spectrum({
            color: theme_send_order_text_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < send_order_arr.length; i++){
                    $(send_order_arr[i]).css('color', color.toRgbString())

                }

                theme_send_order_text_color = color.toRgbString()
            },
            change: function(color) {
                for (let i = 0; i < send_order_arr.length; i++){
                    $(send_order_arr[i]).css('color', color.toRgbString())

                }

                theme_send_order_text_color = color.toRgbString()
            }
        });


        $("#theme_send_order_hover_color").spectrum({
            color: theme_send_order_hover_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                theme_send_order_hover_color = color.toRgbString();
            },
            change: function(color) {
                theme_send_order_hover_color = color.toRgbString();
            }
        });

        $("#theme_send_order_hover_text_color").spectrum({
            color: theme_send_order_hover_text_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                theme_send_order_hover_text_color = color.toRgbString();
            },
            change: function(color) {
                theme_send_order_hover_text_color = color.toRgbString();
            }
        });

        $("#theme_selected_material_color").spectrum({
            color: theme_selected_material_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < selected_arr.length; i++){
                    $(selected_arr[i]).css('border-color', color.toRgbString())
                }

                theme_selected_material_color = color.toRgbString();
            },
            change: function(color) {
                for (let i = 0; i < selected_arr.length; i++){
                    $(selected_arr[i]).css('border-color', color.toRgbString())
                }

                theme_selected_material_color = color.toRgbString();
            }
        });


        $("#theme_buttons_background").spectrum({
            color: theme_buttons_background,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < button_bg_arr.length; i++){
                    $(button_bg_arr[i]).css('background', color.toRgbString())
                }

                theme_buttons_background = color.toRgbString();

            },
            change: function(color) {
                for (let i = 0; i < button_bg_arr.length; i++){
                    $(button_bg_arr[i]).css('background', color.toRgbString())
                }

                theme_buttons_background = color.toRgbString();
            }
        });

        $("#theme_buttons_text_color").spectrum({
            color: theme_buttons_text_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                for (let i = 0; i < button_bg_arr.length; i++){
                    $(button_bg_arr[i]).css('color', color.toRgbString())
                }
                theme_buttons_text_color = color.toRgbString();
            },
            change: function(color) {
                for (let i = 0; i < button_bg_arr.length; i++){
                    $(button_bg_arr[i]).css('color', color.toRgbString())
                }
                theme_buttons_text_color = color.toRgbString();
            }
        });

        $("#theme_buttons_hover_background").spectrum({
            color: theme_buttons_hover_background,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                theme_buttons_hover_background = color.toRgbString();
            },
            change: function(color) {
                theme_buttons_hover_background = color.toRgbString();
            }
        });

        $("#theme_buttons_hover_text_color").spectrum({
            color: theme_buttons_hover_text_color,
            preferredFormat: "hex",
            showAlpha: true,
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                theme_buttons_hover_text_color = color.toRgbString();
            },
            change: function(color) {
                theme_buttons_hover_text_color = color.toRgbString();
            }
        });


        $('#preview .btn-default')
            .on("mouseenter", function () {
                $(this).css({
                    "background-color": theme_buttons_hover_background,
                    "color": theme_buttons_hover_text_color
                });
            })
            .on("mouseleave", function () {
                $(this).css({
                    "background-color": theme_buttons_background,
                    "color": theme_buttons_text_color
                });
            });

        $('#preview #show_order_modal')
            .on("mouseenter", function () {
                $(this).css({
                    "background-color": theme_send_order_hover_color,
                    "color": theme_send_order_hover_text_color
                });
            })
            .on("mouseleave", function () {
                $(this).css({
                    "background-color": theme_send_order_color,
                    "color": theme_send_order_text_color
                });
            });



        $('#sub_form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: $('#sub_form').attr('action'),
                type: 'post',
                data: {
                    active: $('#active').val(),
                    theme_main_background: theme_main_background,
                    theme_main_text_color: theme_main_text_color,
                    theme_border_color: theme_border_color,
                    theme_send_order_color: theme_send_order_color,
                    theme_send_order_text_color: theme_send_order_text_color,
                    theme_send_order_hover_color: theme_send_order_hover_color,
                    theme_send_order_hover_text_color: theme_send_order_hover_text_color,
                    theme_buttons_background: theme_buttons_background,
                    theme_buttons_text_color: theme_buttons_text_color,
                    theme_buttons_hover_background: theme_buttons_hover_background,
                    theme_buttons_hover_text_color: theme_buttons_hover_text_color,
                    theme_acc_body_color: theme_acc_body_color,
                    theme_selected_material_color: theme_selected_material_color,
                }
            }).done(function(data) {
                console.log(data);
                toastr.success('<?php echo $lang_arr['success']?>')

            });
        })




    });



</script>