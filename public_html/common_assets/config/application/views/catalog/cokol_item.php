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
                        <li><a class="nav-link " data-toggle="tab" href="#models_tab"><?php echo $lang_arr['models'] ?></a></li>
                        <li><a class="nav-link " data-toggle="tab" href="#params_tab"><?php echo $lang_arr['params'] ?></a></li>
                        <li><a class="nav-link " data-toggle="tab" href="#materials_tab"><?php echo $lang_arr['materials'] ?></a></li>
                        <li><a class="nav-link " data-toggle="tab" href="#prices_tab"><?php echo $lang_arr['prices'] ?></a></li>
                        <!--                        <li><a class="nav-link " data-toggle="tab" href="#json_tab">--><?php //echo $lang_arr['json_code'] ?><!--</a></li>-->
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
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['model_code'] ?></label>
                                    <div class="col-sm-10">
                                        <input  v-model="item.code" type="text" class="form-control">
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
                        <div id="models_tab" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div>{{lang('cornice_common')}}</div>
                                            <pp_model :lang="$options.lang" ref="test_model" :model="item.models.common" v-model="item.models.common"></pp_model>
                                        </div>
                                        <div class="form-group">
                                            <div>{{lang('cornice_radius')}}</div>
                                            <pp_model :lang="$options.lang" ref="test_model" :model="item.models.radius" v-model="item.models.radius"></pp_model>
                                        </div>
                                        <div class="form-group">
                                            <div>{{lang('cornice_radius_i')}}</div>
                                            <pp_model :lang="$options.lang" ref="test_model" :model="item.models.radius_i" v-model="item.models.radius_i"></pp_model>
                                        </div>
                                    </div>
                                    <div class="col-6">

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="params_tab" class="tab-pane">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('height')}}, {{lang('units')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.params.height" min="0" step="1" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('depth')}}, {{lang('units')}}/{{lang('price_type_pcs')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.params.depth" min="0" step="1" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('cornice_front_offset')}}, {{lang('units')}}/{{lang('price_type_pcs')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.params.front_offset" min="0" step="1" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{lang('cornice_item_width')}}, {{lang('units')}}/{{lang('price_type_pcs')}}</label>
                                    <div class="col-sm-10">
                                        <input type="number" v-model="item.params.item_width" min="0" step="1" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="materials_tab" class="tab-pane">
                            <div class="panel-body">

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{$options.lang['available_materials']}}</label>
                                    <div class="col-sm-10">
                                        <select  v-model="item.params.materials_mode" class="form-control">
                                            <option value="f">{{$options.lang['cornice_use_facade_materials']}}</option>
                                            <option value="c">{{$options.lang['kitchen_materials_categories_label']}}</option>
                                            <option value="i">{{$options.lang['materials']}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-show="item.params.materials_mode == 'c'">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{$options.lang['choose_categories']}}</label>
                                        <div class="col-sm-10">

                                            <v-select
                                                v-if="options_ready"
                                                multiple :close-on-select="false"
                                                :reduce="name => name.id"
                                                :value="item.mat_categories"
                                                v-model="item.mat_categories"
                                                :options="$options.m_top_categories"
                                                label="name">
                                                <template v-slot:option="option">
                                                    {{ option.name }}
                                                </template>
                                            </v-select>


                                        </div>
                                    </div>
                                </div>

                                <div v-show="item.params.materials_mode == 'i'">
                                    <pp_items ref="materials_picker" @e_update="item.materials=$event" :selected_items="item.materials" :lang="$options.lang" :controller="'materials'"></pp_items>
                                </div>
                            </div>



                        </div>
                        <div id="prices_tab" class="tab-pane">
                            <div class="panel-body">
                                <div class="form-group">
                                    <button @click="export_xls()" type="button" class="btn btn-sm btn-primary btn-outline">{{lang('export_xls')}}</button>
                                    <button @click="$refs.xls_input.click()" type="button" class="btn btn-sm btn-primary btn-outline">{{lang('import_xls')}}</button>
                                    <div class="d-none">
                                        <input @change="import_xls($event)" ref="xls_input" type="file" accept=".xlsx">
                                    </div>
                                    <button v-show="item.params.materials_mode !='f' " @click="fill_prices()" type="button" class="btn btn-sm btn-primary btn-outline">{{lang('price_fill_from_materials')}}</button>
                                </div>

                                <div>
                                    <table class="table table-bordered ">
                                        <thead>
                                        <tr>
                                            <th>{{lang('mat_code')}}</th>
                                            <th>{{lang('custom_data')}}</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <template v-for="(it,ind) in item.items">
                                            <tr>
                                                <td class="col-6">
                                                    <div class="form-group">
                                                        <label>{{lang('category')}}</label>
                                                        <v-select
                                                            :disabled="ind == 0"
                                                            v-if="options_ready"
                                                            :clearable="false"
                                                            :value="it.mat_category"
                                                            label="name"
                                                            :options="c_mat_categories"
                                                            :reduce="category => category.id"
                                                            v-model="it.mat_category"
                                                            :key="it.mat_category"
                                                        >
                                                            <template v-slot:selected-option="option">
                                                                <span v-if="option.parent != 0">{{$options.m_categories_hash[option.parent].name}} / &nbsp;</span>{{ option.name }}
                                                            </template>
                                                            <template v-slot:option="option">
                                                            <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                                <span v-if="option.parent != 0">{{$options.m_categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                            </span>
                                                            </template>
                                                        </v-select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{lang('mat_code')}}</label>
                                                        <input v-if="ind == 0" disabled :value="lang('all')" type="text" class="form-control form-control-sm">
                                                        <input v-if="ind != 0" v-model="it.mat_code" type="text" class="form-control form-control-sm">
                                                    </div>

                                                </td>

                                                <td class="col-6">

                                                    <table class="table table-bordered table-sm mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th>{{lang('type')}}</th>
                                                            <th>{{lang('name')}}</th>
                                                            <th>{{lang('code')}}</th>
                                                            <th>{{lang('price')}}, {{lang('currency')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                {{lang('cornice_common')}}
                                                            </td>
                                                            <td>
                                                                <input v-model="it.name" type="text" class="form-control form-control-sm">
                                                            </td>
                                                            <td>
                                                                <input v-model="it.code" type="text" class="form-control form-control-sm">
                                                            </td>
                                                            <td>
                                                                <input v-model="it.price.common" type="number" class="form-control form-control-sm">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                {{lang('cornice_radius')}}
                                                            </td>
                                                            <td>
                                                                <input v-model="it.name_radius" type="text" class="form-control form-control-sm">
                                                            </td>
                                                            <td>
                                                                <input v-model="it.code_radius" type="text" class="form-control form-control-sm">
                                                            </td>
                                                            <td>
                                                                <input v-model="it.price.radius" type="number" class="form-control form-control-sm">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                {{lang('cornice_radius_i')}}
                                                            </td>
                                                            <td>
                                                                <input v-model="it.name_radius_i" min="0" type="text" class="form-control form-control-sm">
                                                            </td>
                                                            <td>
                                                                <input v-model="it.code_radius_i" min="0" type="text" class="form-control form-control-sm">
                                                            </td>
                                                            <td>
                                                                <input v-model="it.price.radius_i" min="0" type="number" class="form-control form-control-sm">
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>



                                                </td>

                                                <td style="width: 45px">
                                                    <div v-show="ind != 0" @click="remove_item(ind)" class="btn btn-danger btn-outline btn-sm">
                                                        <i class="fa fa-trash delete_file" aria-hidden="true"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr>
                                            <td colspan="9">
                                                <button @click="add_item()" type="button" class="btn btn-sm btn-primary btn-outline">{{lang('add')}}</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--                        <div id="json_tab" class="tab-pane">-->
                        <!--                            <div class="panel-body">-->
                        <!--                               -->
                        <!--                            </div>-->
                        <!--                        </div>-->
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
<script src="/common_assets/admin_js/catalog/cokol_item.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/libs/exceljs.min.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/image_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/model_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/items_picker.php');?>
