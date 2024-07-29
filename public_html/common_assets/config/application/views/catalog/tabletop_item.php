<?php
if (isset($common) && $common == 1) {
    $submit_url = site_url('catalog/item_add_common/' . $controller_name. '/');
    if (isset($id) && $id != 0) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items_common/' . $controller_name);
} else {
    $submit_url = site_url('catalog/item_add/' . $controller_name . '/');
    if (isset($id) && $id != 0) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/' . $controller_name);
}
?>
<div id="app" v-cloak>

    <input ref="submit_url" id="form_submit_url" value="<?php echo $submit_url ?>" type="hidden">
    <input ref="success_url" id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
    <input id="controller_name" value="<?php echo $controller_name ?>" type="hidden">
    <?php if(isset($id)):?><input id="item_id" value="<?php echo $id ?>" type="hidden"><?php endif;?>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <?php if(isset($common) && $common == 1):?>
                <?php if(isset($id)):?>
                    <h2 style="color: red"><?php echo $lang_arr[$controller_name . '_item_edit']?> <span> (ОБЩАЯ БАЗА)</span> <span v-cloak>{{item.name}}</span></h2>
                <?php else:?>
                    <h2 style="color: red"><?php echo $lang_arr[$controller_name . '_item_add'] ?> (ОБЩАЯ БАЗА)</h2>
                <?php endif;?>
            <?php else:?>
                <?php if (isset($id)):?>
                    <h2><?php echo $lang_arr[$controller_name . '_item_edit']?> <span v-cloak>{{item.name}}</span></h2>
                <?php else:?>
                    <h2><?php echo $lang_arr[$controller_name . '_item_add'] ?></h2>
                <?php endif;?>
            <?php endif;?>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#params_tab"><?php echo $lang_arr['params'] ?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#accessories_tab"><?php echo $lang_arr['accessories'] ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic_params_tab" class="tab-pane active">
                            <div class="panel-body">

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                    <div class="col-sm-10">
                                        <input  v-model="item.name" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['code'] ?></label>
                                    <div class="col-sm-10">
                                        <input v-model="item.code" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Есть трапеция</label>
                                    <div class="col-sm-10">
                                        <label class="switch">
                                            <input :true-value="1" :false-value="0" v-model="item.has_trap" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <div v-show="item.has_trap">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?> (Трапеция)</label>
                                        <div class="col-sm-10">
                                            <input v-model="item.name_trap" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['code'] ?> (Трапеция)</label>
                                        <div class="col-sm-10">
                                            <input v-model="item.code_trap" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                                    <div class="col-sm-10">
                                        <v-select
                                            :clearable="false"
                                            :value="item.category"
                                            label="name"
                                            :options="$options.cat_ordered"
                                            :reduce="category => category.id"
                                            v-model="item.category"
                                            :key="item.category"
                                        >
                                            <template v-slot:selected-option="option">
                                                <span v-if="option.parent != 0">{{categories_hash[option.parent].name}} / &nbsp;</span>{{ option.name }}
                                            </template>
                                            <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                                            </template>
                                        </v-select>
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
                                        <input type="number" v-model="item.order"  class="form-control">
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
                            </div>
                        </div>
                        <div id="params_tab" class="tab-pane">
                            <div class="panel-body">

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('width')}}, {{lang('units')}}/{{lang('price_type_pcs')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.params.width" min="0" step="1" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('depth')}}, {{lang('units')}}/{{lang('price_type_pcs')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.params.depth" min="0" step="1" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('thickness')}}, {{lang('units')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.params.height" min="0" step="1" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('price')}}, {{lang('currency')}}/{{lang('price_type_pcs')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.prices.common" min="0" step="1" class="form-control">
                                    </div>
                                </div>
                                <div v-show="item.has_trap" class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('price')}} (Трапеция), {{lang('currency')}}/{{lang('price_type_pcs')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.prices.common" min="0" step="1" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('material')}}</label>
                                    <div v-show="item.params.materials_mode == 'i'">
                                        <pp_items ref="materials_picker" @e_update="item.params.material=$event" :selected_item="item.params.material" :lang="$options.lang" :controller="'materials'"></pp_items>
                                    </div>
                                </div>



                            </div>
                        </div>
                        <div id="accessories_tab" class="tab-pane">
                            <div class="panel-body">

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Соединительная планка</label>
                                    <div>
                                        <pp_items :unselect="1" :small="1"  @e_update="item.accessories.conn=$event" :selected_item="item.accessories.conn" :lang="$options.lang" :controller="'accessories'"></pp_items>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Торцевая планка</label>
                                    <div>
                                        <pp_items :unselect="1"  :small="1"  @e_update="item.accessories.end=$event" :selected_item="item.accessories.end" :lang="$options.lang" :controller="'accessories'"></pp_items>
                                    </div>
                                </div>


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
                        <div class="row">
                            <div class="col-sm-4">
                                <a class="btn btn-white btn-sm" href="<?php echo $return_url ?>"><?php echo $lang_arr['cancel'] ?></a>
                                <?php if (isset($id)): ?>
                                    <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['save'] ?></button>
                                <?php else: ?>
                                    <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add'] ?></button>
                                <?php endif; ?>

                            </div>
                            <div class="col-sm-8 text-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">

<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/pagination.js"></script>
<script src="/common_assets/admin_js/catalog/tabletop_items.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/libs/exceljs.min.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/image_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/model_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/items_picker.php');?>
