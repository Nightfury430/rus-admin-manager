<?php
if (isset($common) && $common == 1) {
    $submit_url = site_url( $controller_name . '/items_add_ajax_common/' );
    if(isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items_common/'.$controller_name);
} else {
    $submit_url = site_url( $controller_name . '/items_add_ajax/' );
    if(isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/'.$controller_name);
}
?>

<div id="app" v-cloak>
    <?php if (isset($id)): ?><input id="item_id" value="<?php echo $id ?>" type="hidden"><?php endif; ?>
    <input ref="success_url" id="form_success_url" value="<?php echo $return_url ?>" type="hidden">


    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{lang('kitchen_model_add')}}</h2>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div v-if="options_ready">
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params'] ?></h4>
            </div>
            <div class="ibox-content">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                    <div class="col-sm-10">
                        <input v-model="item.name" type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">{{lang('icon')}}</label>
                    <div class="col-sm-5">
                        <pp_image @e_update="item.icon=$event" :src="item.icon"></pp_image>
                    </div>
                    <div class="col-sm-5">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                    <div class="col-sm-10">
                        <pp_category @e_update="item.category = $event" :selected="item.category" :controller="'kitchen_models'"></pp_category>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active'] ?></label>
                    <div class="col-sm-10">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.active" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order'] ?></label>
                    <div class="col-sm-10">
                        <input type="number" v-model="item.order" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['facades_params'] ?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facades_bottom_as_top'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.bottom_as_top_facade_models" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div v-show="item.bottom_as_top_facade_models == 1" class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facades_materials_bottom_as_top'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.bottom_as_top_facade_materials" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $lang_arr['top_modules_facade_model'] ?>*</label>
                            <pp_items ref="facades_models_top" @e_update="change_facades($event, 'facades_models_top')" :selected_item="item.facades_models_top" :lang="$options.lang" :controller="'facades'"></pp_items>
                        </div>
                    </div>
                    <div v-show="item.bottom_as_top_facade_models == 0" class="col-sm-6">
                        <div class="form-group" id="facades_models_bottom_wrapper">
                            <label><?php echo $lang_arr['bottom_modules_facade_model'] ?>*</label>
                            <pp_items @e_update="change_facades($event, 'facades_models_bottom')" :selected_item="item.facades_models_bottom" :lang="$options.lang" :controller="'facades'"></pp_items>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <div v-if="item.facades_models_top != 0" class="form-group">
                            <label><?php echo $lang_arr['top_facades_materials'] ?>*</label>
                            <pp_items :categories="f_mats.facades_models_top" @e_update="item.facades_selected_material_top=$event" :selected_item="item.facades_selected_material_top" :lang="$options.lang" :controller="'materials'"></pp_items>
                        </div>
                    </div>
                    <div v-show="item.bottom_as_top_facade_materials == 0" class="col-sm-6">
                        <div v-if="item.facades_models_bottom != 0" class="form-group" id="facades_selected_materials_bottom_wrapper">
                            <label><?php echo $lang_arr['bottom_facades_materials'] ?>*</label>
                            <pp_items :categories="f_mats.facades_models_bottom" @e_update="item.facades_selected_material_bottom=$event" :selected_item="item.facades_selected_material_bottom" :lang="$options.lang" :controller="'materials'"></pp_items>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_facades_materials_select'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.allow_facades_materials_select" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>


            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['glass_params'] ?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group">
                    <label for="glass_materials"><?php echo $lang_arr['available_glass_materials'] ?>*</label>
                    <pp_categories @e_update="item.glass_materials = $event" :selected="item.glass_materials" :controller="'glass'" :top_only="1"></pp_categories>
                </div>

                <div class="form-group">
                    <label><?php echo $lang_arr['default_glass_material'] ?></label>
                    <pp_items @e_update="item.selected_glass_material = $event" :categories="item.glass_materials" :selected_item="item.selected_glass_material" :lang="$options.lang" :controller="'glass'"></pp_items>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_glass_materials_select'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.allow_glass_materials_select" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>


            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['corpus_params'] ?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['bottom_as_top_corpus_materials'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" @change="change_corpus_eq()" v-model="item.bottom_as_top_corpus_materials" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="corpus_materials_top"><?php echo $lang_arr['available_corpus_materials'] ?>*</label>
                            <pp_categories @e_update="change_corpus_categories($event)" :selected="item.corpus_materials_top" :controller="'materials'" :top_only="1"></pp_categories>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="selected_corpus_material_top"><?php echo $lang_arr['default_top_corpus_material'] ?>*</label>
                            <pp_items @e_update="change_corpus($event, 'selected_corpus_material_top')" :categories="item.corpus_materials_top" :selected_item="item.selected_corpus_material_top" :lang="$options.lang" :controller="'materials'"></pp_items>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div v-show="item.bottom_as_top_corpus_materials == 0" class="form-group">
                            <label for="selected_corpus_material_bottom"><?php echo $lang_arr['default_bottom_corpus_material'] ?>*</label>
                            <pp_items @e_update="change_corpus($event, 'selected_corpus_material_bottom')" :categories="item.corpus_materials_top" :selected_item="item.selected_corpus_material_bottom" :lang="$options.lang" :controller="'materials'"></pp_items>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_corpus_materials_select'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.allow_corpus_materials_select" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['cokol_params'] ?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['no_cokol'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="0" :false-value="1" v-model="item.cokol_active" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cokol_default_height'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                    <div class="col-sm-8">
                        <input type="number" step="1" class="form-control" v-model="item.cokol_height">
                    </div>
                </div>

                <div v-if="item.cokol_active == 1">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cokol_as_corpus'] ?></label>
                        <div class="col-sm-8">
                            <label class="switch">
                                <input :true-value="1" :false-value="0" @change="change_cokol_as_corpus()" v-model="item.cokol_as_corpus" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div v-if="item.cokol_as_corpus == 0">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label><?php echo $lang_arr['available_cokol_materials'] ?></label>
                                <pp_categories @e_update="item.cokol_materials = $event" :selected="item.cokol_materials" :controller="'materials'" :top_only="1"></pp_categories>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label><?php echo $lang_arr['cokol_default_material'] ?></label>
                                <pp_items @e_update="item.selected_cokol_material = $event" :categories="item.cokol_materials" :selected_item="item.selected_cokol_material" :controller="'materials'"></pp_items>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_cokol_material_select'] ?></label>
                        <div class="col-sm-8">
                            <label class="switch">
                                <input :true-value="1" :false-value="0" v-model="item.allow_cokol_materials_select" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['tabletop_params'] ?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_tabletop_thickness'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                    <div class="col-sm-8">
                        <input type="number" step="1" class="form-control" v-model="item.tabletop_thickness">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?php echo $lang_arr['available_tabletop_materials'] ?></label>
                        <pp_categories @e_update="item.tabletop_materials = $event" :selected="item.tabletop_materials" :controller="'materials'" :top_only="1"></pp_categories>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?php echo $lang_arr['tabletop_default_material'] ?></label>
                        <pp_items @e_update="item.selected_tabletop_material = $event" :categories="item.tabletop_materials" :selected_item="item.selected_tabletop_material" :controller="'materials'"></pp_items>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_tabletop_material_select'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.allow_tabletop_materials_select" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>


            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['wallpanel_params'] ?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['wallpanel_default_height'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                    <div class="col-sm-8">
                        <input type="number" step="1" class="form-control" v-model="item.wallpanel_height">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?php echo $lang_arr['wallpanel_available_materials'] ?></label>
                        <pp_categories @e_update="item.wallpanel_materials = $event" :selected="item.wallpanel_materials" :controller="'materials'" :top_only="1"></pp_categories>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_choose_wallpanel_material'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.allow_wallpanel_materials_select" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['handles_params'] ?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facades_no_handles'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.no_handle" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div v-if="item.no_handle == 0">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['handles_orientation'] ?></label>
                        <div class="col-sm-8">
                            <select class="form-control" v-model="item.handle_orientation">
                                <option :value="'vertical'"><?php echo $lang_arr['vertical'] ?></option>
                                <option :value="'horizontal'"><?php echo $lang_arr['horizontal'] ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['locker_handle_position'] ?></label>
                        <div class="col-sm-8">
                            <select class="form-control" v-model="item.handle_lockers_position">
                                <option :value="'top'"><?php echo $lang_arr['lockers_hande_top'] ?></option>
                                <option :value="'center'"><?php echo $lang_arr['lockers_handle_center'] ?></option>
                            </select>
                        </div>
                    </div>

                    <!--            <div class="form-group row">-->
                    <!--                <div class="col-sm-12">-->
                    <!--                    <label>--><?php //echo $lang_arr['available_handles'] ?><!--</label>-->
                    <!--                    <pp_categories @e_update="item.available_handles = $event" :selected="item.available_handles" :controller="'handles'"></pp_categories>-->
                    <!--                </div>-->
                    <!--            </div>-->


                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label><?php echo $lang_arr['handle_default_model'] ?></label>
                            <pp_items ref="handle_sel" @e_update="change_handle($event)" :categories="item.available_handles" :selected_item="item.handle_selected_model" :controller="'handles'"></pp_items>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['handle_default_size'] ?></label>
                        <div class="col-sm-8">
                            <select class="form-control" v-model="item.handle_preferable_size">
                                <option v-for="(it,index) in handle_variants" :value="index">
                                    <template v-if="it.axis_size">{{it.axis_size}} {{$options.lang['units']}}</template>
                                    <template v-else>{{it.width}} {{$options.lang['units']}}</template>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_handles_select'] ?></label>
                        <div class="col-sm-8">
                            <label class="switch">
                                <input :true-value="1" :false-value="0" v-model="item.allow_handles_select" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['cornice_params'] ?></h4>
            </div>
            <div class="ibox-content">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cornice_available_kitchen_model'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.cornice_available" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cornice_active_default_kithcen_model'] ?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input :true-value="1" :false-value="0" v-model="item.cornice_active" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

            </div>
        </div>


        <div class="ibox">
            <div class="ibox-content">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['modules_set'] ?></label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="item.available_modules">
                            <option v-for="it in module_sets" :value="it.id">
                                {{it.name}}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="ibox">
            <div class="ibox-content">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['additional_materials'] ?></label>
                    <div class="col-sm-8">
                        <input v-model="item.fixed_materials" type="text" class="form-control">
                    </div>
                </div>

            </div>
        </div>


        <div v-if="multiple_facades_mode == 1" class="ibox">
            <div class="ibox-content">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facades_categories_available'] ?></label>
                    <div class="col-sm-8">
                        <pp_categories @e_update="item.facades_categories = $event" :selected="item.facades_categories" :controller="'facades'" :top_only="1"></pp_categories>
                    </div>
                </div>
            </div>
        </div>

        <div class="ibox ">
            <div class="ibox-content">

                <div class="hr-line-dashed"></div>

                <div class="form-group row">
                    <div class="col-sm-4 col-sm-offset-2">
                        <a class="btn btn-white btn-sm" href="<?php echo $return_url ?>"><?php echo $lang_arr['cancel'] ?></a>
                        <button @click="submit()" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add'] ?></button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <input type="hidden" value="2" name="door_offset">
    <input type="hidden" value="10" name="shelve_offset">
    <input type="hidden" value="16" name="corpus_thickness">
    <input type="hidden" value="720" name="bottom_modules_height">
</div>


<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">

<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/pagination.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/kitchen_model.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/libs/exceljs.min.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/image_picker.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/model_picker.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/items_picker.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/categories_picker.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/category_picker.php'); ?>