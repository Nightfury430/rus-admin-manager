<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?php echo $lang_arr['report']?></h2>
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




<?php

    if(empty($settings['report_custom_settings'])){
	    $report_custom_settings = array();
	    $report_custom_settings['report_page_orientation'] = "L";
	    $report_custom_settings['report_margin_header'] = "20";
	    $report_custom_settings['report_margin_top'] = "20";
	    $report_custom_settings['report_margin_left'] = "35";
	    $report_custom_settings['report_margin_right'] = "10";
	    $report_custom_settings['report_margin_bottom'] = "20";
	    $report_custom_settings['report_margin_footer'] = "20";
	    $report_custom_settings['report_margin_footer'] = "20";
	    $report_custom_settings['custom_report_common_view'] = 1;
	    $report_custom_settings['custom_report_edges_view'] = 1;
	    $report_custom_settings['custom_report_comms_only_view'] = 1;
	    $report_custom_settings['custom_report_top_view'] = 1;
	    $report_custom_settings['custom_report_walls_view'] = 1;
	    $report_custom_settings['custom_report_show_logo'] = 1;
    } else {
	    $report_custom_settings = json_decode($settings['report_custom_settings'], true);

	    if(!isset($report_custom_settings['custom_report_common_view'])) $report_custom_settings['custom_report_common_view'] = 1;
	    if(!isset($report_custom_settings['custom_report_edges_view'])) $report_custom_settings['custom_report_edges_view'] = 1;
	    if(!isset($report_custom_settings['custom_report_comms_only_view'])) $report_custom_settings['custom_report_comms_only_view'] = 1;
	    if(!isset($report_custom_settings['custom_report_top_view'])) $report_custom_settings['custom_report_top_view'] = 1;
	    if(!isset($report_custom_settings['custom_report_walls_view'])) $report_custom_settings['custom_report_walls_view'] = 1;
	    if(!isset($report_custom_settings['custom_report_show_logo'])) $report_custom_settings['custom_report_show_logo'] = 1;

    }

?>

<div class="wrapper wrapper-content  animated fadeInRight">

	<form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url( 'report/update_report_settings/' ) ?>">


        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
<!--                        <li><a class="nav-link active" data-toggle="tab" href="#tab-1">Свой шаблон</a></li>-->
                        <li><a class="nav-link active" data-toggle="tab" href="#tab-2">Стандартный шаблон</a></li>

                    </ul>
                    <div class="tab-content">

<!--                        <div id="tab-1" class="tab-pane active">-->
<!--                            <div class="panel-body">-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <label class="col-sm-4 col-form-label">--><?php //echo $lang_arr['order_email_use_template']?><!--</label>-->
<!--                                    <div class="col-sm-2">-->
<!--                                        <label class="switch">-->
<!--                                            <input name="report_use_custom" --><?php //if ($settings['report_use_custom']) echo 'checked'?><!-- type="checkbox">-->
<!--                                            <span class="slider round"></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!---->
<!--                                    <label class="col-sm-4 col-form-label">Логотип в верхнем правом углу</label>-->
<!--                                    <div class="col-sm-2">-->
<!--                                        <label class="switch">-->
<!--                                            <input name="custom_report_show_logo" --><?php //if ($report_custom_settings['custom_report_show_logo']) echo 'checked'?><!-- type="checkbox">-->
<!--                                            <span class="slider round"></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <h3 class="col-12">--><?php //echo $lang_arr['report_images']?><!--</h3>-->
<!--                                </div>-->
<!---->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <label class="col-sm-4 col-form-label">--><?php //echo $lang_arr['report_common_view']?><!--</label>-->
<!--                                    <div class="col-sm-2">-->
<!--                                        <label class="switch">-->
<!--                                            <input name="custom_report_common_view" --><?php //if ($report_custom_settings['custom_report_common_view']) echo 'checked'?><!-- type="checkbox">-->
<!--                                            <span class="slider round"></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!---->
<!--                                    <label class="col-sm-4 col-form-label">--><?php //echo $lang_arr['report_edges_view']?><!--</label>-->
<!--                                    <div class="col-sm-2">-->
<!--                                        <label class="switch">-->
<!--                                            <input name="custom_report_edges_view" --><?php //if ($report_custom_settings['custom_report_edges_view']) echo 'checked'?><!-- type="checkbox">-->
<!--                                            <span class="slider round"></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <label class="col-sm-4 col-form-label">--><?php //echo $lang_arr['report_comms_only_view']?><!--</label>-->
<!--                                    <div class="col-sm-2">-->
<!--                                        <label class="switch">-->
<!--                                            <input name="custom_report_comms_only_view" --><?php //if ($report_custom_settings['custom_report_comms_only_view']) echo 'checked'?><!-- type="checkbox">-->
<!--                                            <span class="slider round"></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!---->
<!--                                    <label class="col-sm-4 col-form-label">--><?php //echo $lang_arr['report_top_view']?><!--</label>-->
<!--                                    <div class="col-sm-2">-->
<!--                                        <label class="switch">-->
<!--                                            <input name="custom_report_top_view" --><?php //if ($report_custom_settings['custom_report_top_view']) echo 'checked'?><!-- type="checkbox">-->
<!--                                            <span class="slider round"></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!---->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <label class="col-sm-4 col-form-label">--><?php //echo $lang_arr['report_walls_view']?><!--</label>-->
<!--                                    <div class="col-sm-2">-->
<!--                                        <label class="switch">-->
<!--                                            <input name="custom_report_walls_view" --><?php //if ($report_custom_settings['custom_report_walls_view']) echo 'checked'?><!-- type="checkbox">-->
<!--                                            <span class="slider round"></span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!---->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <h3 class="col-12">Настройка страниц по умолчанию</h3>-->
<!--                                </div>-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <label class="col-sm-4 col-form-label">Ориентация</label>-->
<!--                                    <div class="col-sm-8">-->
<!--                                        <select class="form-control" id="report_page_orientation">-->
<!--                                            <option --><?php //if($report_custom_settings['report_page_orientation'] == "L") echo "selected"?><!-- value="L">Альбомная</option>-->
<!--                                            <option --><?php //if($report_custom_settings['report_page_orientation'] == "P") echo "selected"?><!-- value="P">Книжная</option>-->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <label class="col-sm-4 col-form-label">Отступы</label>-->
<!--                                    <div class="col-sm-8 text-center">-->
<!--                                        <div class="row">-->
<!--                                            <div class="col-4"></div>-->
<!--                                            <div class="col-4 form-group">-->
<!--                                                <label>Отступ до верхнего колонтитула</label>-->
<!--                                                <input value="--><?php //echo $report_custom_settings['report_margin_header']?><!--" id="report_margin_header" type="text" class="form-control">-->
<!--                                            </div>-->
<!--                                            <div class="col-4"></div>-->
<!---->
<!--                                            <div class="col-4"></div>-->
<!--                                            <div class="col-4 form-group">-->
<!--                                                <label>Отступ от верхнего колонтитула</label>-->
<!--                                                <input value="--><?php //echo $report_custom_settings['report_margin_top']?><!--" id="report_margin_top" type="text" class="form-control">-->
<!--                                            </div>-->
<!--                                            <div class="col-4"></div>-->
<!---->
<!--                                            <div class="col-4">-->
<!--                                                <label>Отступ от левого края</label>-->
<!--                                                <input value="--><?php //echo $report_custom_settings['report_margin_left']?><!--" id="report_margin_left" type="text" class="form-control">-->
<!--                                            </div>-->
<!--                                            <div class="col-4">-->
<!---->
<!--                                            </div>-->
<!--                                            <div class="col-4">-->
<!--                                                <label>Отступ от правого края</label>-->
<!--                                                <input value="--><?php //echo $report_custom_settings['report_margin_right']?><!--" id="report_margin_right" type="text" class="form-control">-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="col-4"></div>-->
<!--                                            <div class="col-4">-->
<!--                                                <label>Отступ от нижнего колонтитула</label>-->
<!--                                                <input value="--><?php //echo $report_custom_settings['report_margin_bottom']?><!--" id="report_margin_bottom" type="text" class="form-control">-->
<!--                                            </div>-->
<!--                                            <div class="col-4"></div>-->
<!---->
<!--                                            <div class="col-4"></div>-->
<!--                                            <div class="col-4">-->
<!--                                                <label>Отступ до нижнего колонтитула</label>-->
<!--                                                <input value="--><?php //echo $report_custom_settings['report_margin_footer']?><!--" id="report_margin_footer" type="text" class="form-control">-->
<!--                                            </div>-->
<!--                                            <div class="col-4"></div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <h3 class="col-12">Настройка шаблона</h3>-->
<!--                                </div>-->
<!---->
<!--                                <div v-pre class="form-group row">-->
<!--                                    <label class="col-12 col-form-label">-->
<!--                                        Верхний колонтитул-->
<!--                                    </label>-->
<!--                                    <div class="col-12">-->
<!--                                        <textarea id="report_template_header" class="form-control"  rows="15">--><?php //echo htmlspecialchars($settings['report_template_header'])?><!--</textarea>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                                <div v-pre class="form-group row">-->
<!--                                    <label class="col-12 col-form-label">-->
<!--                                        Нижний колонтитул-->
<!--                                    </label>-->
<!--                                    <div class="col-12">-->
<!--                                        <textarea id="report_template_footer" class="form-control"  rows="15">--><?php //echo htmlspecialchars($settings['report_template_footer'])?><!--</textarea>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!---->
<!---->
<!--                                <div v-pre class="form-group row">-->
<!--                                    <label class="col-12 col-form-label">-->
<!--										--><?php //echo $lang_arr['order_email_template_body'] ?>
<!--                                    </label>-->
<!--                                    <div class="col-12">-->
<!--                                        <textarea id="report_template_body" class="form-control"  rows="15">--><?php //echo htmlspecialchars($settings['report_template_body'])?><!--</textarea>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <div class="col-sm-4 col-sm-offset-2">-->
<!--                                        <div  class="btn btn-primary btn-sm" data-action="--><?php //echo site_url( 'report/pdf/' ) ?><!--" id="testpdf">Предпросмотр</div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!---->
<!--                                <div  id="testpdfinner" class="btn">ТЕСТ2</div>-->
<!---->
<!--                                <div class="form-group row">-->
<!--                                    <div class="col-sm-4 col-sm-offset-2">-->
<!--                                        <button class="btn btn-primary btn-sm" type="submit">--><?php //echo $lang_arr['save']?><!--</button>-->
<!--                                    </div>-->
<!--                                </div>-->
<!---->
<!--                            </div>-->
<!--                        </div>-->

                        <div id="tab-2" class="tab-pane common_checkboxes active">
                            <div class="panel-body">

                                <div class="ibox-content">

                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label"><?php echo $lang_arr['report_images']?></label>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_common_view']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_common_view" <?php if ($settings['report_common_view']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_edges_view']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_edges_view" <?php if ($settings['report_edges_view']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_comms_only_view']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_comms_only_view" <?php if ($settings['report_comms_only_view']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_top_view']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_top_view" <?php if ($settings['report_top_view']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_walls_view']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_walls_view" <?php if ($settings['report_walls_view']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>


                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label"><?php echo $lang_arr['report_materials']?></label>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_facades_material']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_facades_material" <?php if ($settings['report_facades_material']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_glass_material']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_glass_material" <?php if ($settings['report_glass_material']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_corpus_material']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_corpus_material" <?php if ($settings['report_corpus_material']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_cokol_material']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_cokol_material" <?php if ($settings['report_cokol_material']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_tabletop_material']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_tabletop_material" <?php if ($settings['report_tabletop_material']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_wallpanel_material']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_wallpanel_material" <?php if ($settings['report_wallpanel_material']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_modules_list']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_modules_list" <?php if ($settings['report_modules_list']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_modules_exact_depth']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_modules_exact_depth" <?php if ($settings['report_modules_exact_depth']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_handle']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_handle" <?php if ($settings['report_handle']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_furniture']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_furniture" <?php if ($settings['report_furniture']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_accessories']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_accessories" <?php if ($settings['report_accessories']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_modules_comments_list']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_modules_comments_list" <?php if ($settings['report_modules_comments_list']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_modules_sizes_column']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_modules_sizes_column" <?php if ($settings['report_modules_sizes_column']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_modules_details']?></label>
                                        <div class="col-sm-8">
                                            <label class="switch">
                                                <input name="report_modules_details" <?php if ($settings['report_modules_details']) echo 'checked'?> type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['report_additional_text']?></label>
                                        <div class="col-sm-8">
                                            <textarea rows="10" class="form-control" name="report_additional_text" ><?php echo $settings['report_additional_text']?></textarea>
                                        </div>
                                    </div>



                                    <div class="hr-line-dashed"></div>

                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>


        <div class="">
            <div id="presave"></div>
        </div>

	</form>

    <div class="hidden">
        <input type="hidden" id="settings_use_custom_form" value="<?php echo $email_settings['use_custom_form'] ?>">
        <div id="settings_custom_form_data"><?php echo $email_settings['custom_form_data'] ?></div>
    </div>

</div>
<style>
    .tox-tinymce-aux{
        z-index: 99999999;
    }
</style>
<script src="/common_assets/libs/tinymce/tinymce.min.js"></script>
<script src="/common_assets/libs/tinymce/jquery.tinymce.min.js"></script>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/report.js"></script>





