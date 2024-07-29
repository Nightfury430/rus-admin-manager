<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Элемент каталога</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<?php
if (isset($common) && $common == 1) {
    $submit_url = site_url($controller_name . '/items_add_ajax_common/');
    if (isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items_common/' . $controller_name);
} else {
    $submit_url = site_url($controller_name . '/item_add/');
    if (isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/' . $controller_name);
}
?>

<div id="app">
    <div class="wrapper wrapper-content">
        <div ref="sub_form" id="sub_form" data-action="<?php echo site_url('material_types/item_add/') ?><?php if (isset($id)) echo $id ?>">
            <input id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
            <?php if (isset($id)): ?>
                <input id="item_id" value="<?php echo $id ?>" type="hidden">
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                            <li><a @click="resize_viewport()" class="nav-link " data-toggle="tab" href="#params_tab"><?php echo $lang_arr['params'] ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="basic_params_tab" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                        <div class="col-sm-10">
                                            <input v-model="item.name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['price_type'] ?></label>
                                        <div class="col-sm-10">
                                            <select v-model="item.params.price_type" class="form-control">
                                                <option value="p"><?php echo $lang_arr['price_type_pcs'] ?></option>
                                                <option value="m"><?php echo $lang_arr['price_type_pm'] ?></option>
                                                <option value="m2"><?php echo $lang_arr['price_type_m2'] ?></option>
                                                <option value="m3"><?php echo $lang_arr['price_type_m3'] ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                                        <div class="col-sm-10">
                                            <v-select
                                                    :close-on-select="false"
                                                    :clearable="true"
                                                    :value="item.category"
                                                    label="name"
                                                    :options="$options.cat_ordered"
                                                    :reduce="category => category.id"
                                                    v-model="item.category"
                                                    :key="item.category"
                                            >
                                                <template v-slot:selected-option="option">
                                                    <span v-if="option.parent != 0">{{cat_hash[option.parent].name}} / &nbsp;</span>{{ option.name }}
                                                </template>
                                                <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{cat_hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                                                </template>
                                            </v-select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active'] ?></label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.active" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                                        <div class="col-sm-5">

                                            <div class="icon_block">
                                                <img @click="file_target = 'icon'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" style="max-width: 78px" :src="correct_url(item.icon)" alt="">
                                                <i @click="file_target = 'icon'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i v-if="item.icon != ''" @click="item.icon = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                            </div>

                                        </div>
                                        <div class="col-sm-5">

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="params_tab" class="tab-pane">
                                <div class="panel-body">
                                    <div class="row form-group">
                                        <div class="col-6">
                                            <div class="configurator">
                                                <ul class="tab_menu_h object_menu_params_menu mb10">
                                                    <li :class="{active: tab == 'models'}" @click="change_tab('models')">Модели</li>
                                                    <li :class="{active: tab == 'materials'}" @click="change_tab('materials')">Материалы</li>
                                                </ul>
                                                <div v-show="tab == 'models'">

                                                    <div>
                                                        <div style="padding-right: 50px" class="rounded shadow p10 mb5 relative" v-for="(variant, ind) in item.params.models">
                                                            <div class="flex_row flex_wrap flex_vcenter">
                                                                <div class="w33 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Название</label>
                                                                        <input v-model="variant.name" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="w33 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Артикул</label>
                                                                        <input v-model="variant.code" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="w33 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Цена</label>
                                                                        <input v-model="variant.price" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex_row flex_wrap flex_vcenter">
                                                                <div class="w33 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Мин. ширина, мм</label>
                                                                        <input @input="change_preview_size()" v-model="variant.min_x" type="number">
                                                                    </div>
                                                                </div>
                                                                <div class="w33 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Мин. высота, мм</label>
                                                                        <input @input="change_preview_size()" v-model="variant.min_y" type="number">
                                                                    </div>
                                                                </div>
                                                                <div class="w33 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Мин. глубина, мм</label>
                                                                        <input @input="change_preview_size()" v-model="variant.min_z" type="number">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex_row flex_wrap flex_vcenter">
                                                                <div class="w75 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Модель</label>
                                                                        <input v-model="variant.model" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="w25 px5">
                                                                    <div class="p_input_group">
                                                                        <label style="opacity: 0" class="mb0">Модель</label>
                                                                        <button @click="file_target = ind; $refs.fileman.data_mode = 'models'" type="button" data-toggle="modal" data-target="#filemanager" class="btn-block btn-xs btn btn-outline-info" title="Выбрать или загрузить модель">
                                                                            <span class="fa fa-folder-open"></span>
                                                                            Выбрать
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="variant_controls">
                                                                <button title="Удалить" v-show="item.params.models.length > 1" class="v_del btn btn-sm btn-outline btn-danger" @click="remove_variant(ind)" type="button"><i class="icon icon_trash"></i></button>
                                                                <button title="Переместить выше" v-show="ind > 0" class="v_up btn btn-sm btn-outline btn-success" @click="variant_up(ind)" type="button"><i class="icon icon_expand_up"></i></button>
                                                                <button title="Переместить ниже" v-show="item.params.models.length != ind + 1" class="v_down btn btn-sm btn-outline btn-success" @click="variant_down(ind)" type="button"><i class="icon icon_expand_down"></i></button>
                                                                <button v-show="1==0" title="Предпросмотр" class="v_preview btn btn-sm btn-outline btn-success" @click="set_preview_size(ind)" type="button"><i class="icon icon_filter"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div>
                                                        <button class="btn btn-sm btn-outline btn-primary" @click="add_model()" type="button">Добавить</button>
                                                    </div>


                                                </div>
                                                <div v-show="tab == 'materials'">

                                                    <div class="tabs_container_left flex_row">
                                                        <div class="w25 tabs_left">
                                                            <div @click="materials_tab = ind" :class="get_mat_tab_class(ind)" v-for="(mat, ind) in materials">
                                                                <span>
                                                                    {{mat.key}}
                                                                </span>
                                                            </div>
                                                            <button style="margin-top: 10px" class="w100 btn btn-sm btn-outline btn-primary" @click="add_material()" type="button">Добавить</button>
                                                        </div>
                                                        <div class="w75">
                                                            <div v-show="ind == materials_tab" style="padding-right: 50px" class="rounded shadow p10 mb5 relative" v-for="(mat, ind) in materials">
                                                                <div class="p_input_group flex_row ">
                                                                    <label class="w50">Название</label>
                                                                    <input class="w50" v-model="mat.name" type="text">
                                                                </div>
                                                                <div class="p_input_group flex_row ">
                                                                    <label class="w50">Ключ</label>
                                                                    <input @input="change_material_key(mat, ind)" class="w50" v-model="mat.key" :disabled="ind == 0" type="text">
                                                                </div>
                                                                <div class="p_input_group flex_row ">
                                                                    <label class="w50">Режим</label>
                                                                    <select @change="change_material_mode(mat, ind)" v-model="mat.mode" class="p_input">
                                                                        <option value="m">Материал</option>
                                                                        <option value="f">Цвет фасадов</option>
                                                                        <option value="c">Категории декоров (разрешает выбор декора из выбранных в конструкторе)</option>
                                                                        <option value="i">Одиночный декор</option>
                                                                        <option value="v">Свой</option>
                                                                    </select>
                                                                </div>

                                                                <div class="p_input_group flex_row ">
                                                                    <label class="w50">Ось ширины</label>
                                                                    <select v-model="mat.tex_axis.x" class="p_input">
                                                                        <option value="x" >X</option>
                                                                        <option value="y" >Y</option>
                                                                        <option value="z" >Z</option>
                                                                    </select>
                                                                </div>
                                                                <div class="p_input_group flex_row ">
                                                                    <label class="w50">Ось глубины</label>
                                                                    <select  v-model="mat.tex_axis.y" class="p_input">
                                                                        <option value="x" >X</option>
                                                                        <option value="y" >Y</option>
                                                                        <option value="z" >Z</option>
                                                                    </select>
                                                                </div>

                                                                <div v-show="mat.mode == 'm'" class="p_input_group flex_row ">
                                                                    <label class="w50">Материал</label>
                                                                    <select @change="refresh_params()" v-model="mat.selected" class="p_input">
                                                                        <option v-for="opt in $options.materials" :value="opt.key" >{{opt.name}} ({{opt.key}})</option>
                                                                    </select>
                                                                </div>
                                                                <div v-show="mat.mode == 'm'" class="p_input_group flex_row ">
                                                                    <label class="w50">Группа</label>
                                                                    <input min="0" step="1" @input="change_material_group(mat, ind)" class="w50" v-model="mat.group"  type="number">
                                                                </div>
                                                                <div v-if="mat.mode == 'c'">
                                                                    <div class="flex_row">
                                                                        <label class="w50">Категории</label>
                                                                        <div class="w100 p_input_group">
                                                                            <v-select
                                                                                    :clearable="false"
                                                                                    :value="materials[ind].categories"
                                                                                    label="name"
                                                                                    multiple
                                                                                    :close-on-select="false"
                                                                                    :options="decor_categories_ordered"
                                                                                    :reduce="name => name.id"
                                                                                    v-model="materials[ind].categories"
                                                                                    :key="name.id"
                                                                            >

                                                                                <template #selected-option="option">
                                                                            <span style="pointer-events: none" :title="option.name" :class="{'font-weight-bold': option.parent == 0}">
                                                                                <span v-if="option.parent != 0" class="font-weight-bold">{{decor_categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                                            </span>
                                                                                </template>

                                                                                <template v-slot:option="option">
                                                                            <span :title="option.name" :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                                                <span v-if="option.parent != 0">{{decor_categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                                            </span>
                                                                                </template>
                                                                            </v-select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex_row">
                                                                        <label class="w50">По умолчанию</label>
                                                                        <div class="w100">
                                                                            <pp_items :lang="$options.lang" :small="1" :categories="materials[ind].categories"  @e_update="change_selected_material($event, ind)" :selected_item="materials[ind].selected"  :controller="'materials'"></pp_items>
                                                                        </div>

                                                                    </div>
                                                                    <div  class="p_input_group flex_row ">
                                                                        <label class="w50">Группа</label>
                                                                        <input min="0" step="1" @input="change_material_group(mat, ind)" class="w50" v-model="mat.group"  type="number">
                                                                    </div>
                                                                </div>
                                                                <div v-if="mat.mode == 'i'">

                                                                    <div class="flex_row">
                                                                        <label class="w50">Материал</label>
                                                                        <div class="w100">
                                                                            <pp_items :lang="$options.lang" :small="1" @e_update="change_selected_material($event, ind)" :selected_item="materials[ind].selected"  :controller="'materials'"></pp_items>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div v-show="mat.mode == 'v'" class="p_input_group flex_row ">
                                                                    <pp_material :comp_id="'_pp_mat' + ind" ref="pp_mat" @e_update_color="update_color($event, ind)" @e_update="update_material($event, ind)" :params_obj="{}"></pp_material>
                                                                </div>
                                                                <div class="variant_controls">
                                                                    <button v-show="mat.key != 'gen'" class="v_del btn btn-sm btn-outline btn-danger" @click="remove_material(ind)" type="button"><i class="icon icon_trash"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>

                                            </div>

                                        </div>
                                        <div id="preview_block" class="col-6">
                                            <div class="mb5 relative">
                                                <div class="flex_row">
                                                    <div class="w25">Размеры превью</div>
                                                    <div class="w25 px5">
                                                        <div class="p_input_group">
                                                            <label class="mb0">Ширина, мм</label>
                                                            <input class="w100" @input="change_preview_size()" v-model="preview_size.x" type="number">
                                                        </div>
                                                    </div>
                                                    <div class="w25 px5">
                                                        <div class="p_input_group">
                                                            <label class="mb0">Высота, мм</label>
                                                            <input class="w100" @input="change_preview_size()" v-model="preview_size.y" type="number">
                                                        </div>
                                                    </div>
                                                    <div class="w25">
                                                        <div class="p_input_group">
                                                            <label class="mb0">Глубина, мм</label>
                                                            <input class="w100" @input="change_preview_size()" v-model="preview_size.z" type="number">
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-content">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <a class="btn btn-white btn-sm" href="<?php echo $return_url ?>"><?php echo $lang_arr['cancel'] ?></a>
                                    <?php if (isset($id)): ?>
                                        <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['save'] ?></button>
                                    <?php else: ?>
                                        <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add'] ?></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal inmodal" id="filemanager" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                            <h5 class="modal-title">Выбрать файл</h5>
                        </div>
                        <div class="modal-body">

                            <?php if (isset($common) && $common == 1) : ?>
                                <filemanager :type="'common'" ref="fileman" @select_file="sel_file($event)"></filemanager>
                            <?php else: ?>
                                <filemanager ref="fileman" @select_file="sel_file($event)"></filemanager>
                            <?php endif; ?>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>


<div style="display: none; height:100%" id="main_app">
    <div id="viewport">

    </div>
</div>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_tree/sl-vue-tree-dark.css?<?php echo md5(date('m-d-Y-His A e')); ?>">
<link rel="stylesheet" href="/common_assets/fonts/icons/new/style.css?<?php echo md5(date('m-d-Y-His A e')); ?>">

<style>


    .make_icon {
        position: absolute;
        top: 5px;
        right: 20px;
        z-index: 9;
    }

    #preview_block {
        height: 700px;
    }

    #viewport {
        max-width: 100%;
        position: relative;
        height: 100%;
        max-height: 500px;
    }


    .tab_menu_h {
        display: flex;
    }

    .object_menu_params_block ul {
        display: flex;
        flex-direction: row;
        align-items: stretch;
    }

    .object_menu_params_menu {
        border-bottom: 1px solid #a3a3a3;
        padding: 0 10px;

    }

    .object_menu_params_menu > li {
        cursor: pointer;
        border: 1px solid rgba(0, 0, 0, 0);
        border-bottom: 0;
        padding: 2px 5px;
    }

    .object_menu_params_menu > li:hover {
        background-color: #f7f7f7;
        color: #0066cc !important;
    }

    .object_menu_params_menu > li.active {
        border-color: #a3a3a3 !important;
        background-color: #f7f7f7;
        color: #0066cc !important;
    }

</style>

<style>

    i.icon {
        font-weight: bold;
    }

    .v_del {
        position: absolute;
        right: 0;
        top: 0;
    }

    .v_up {
        position: absolute;
        right: 0;
        top: 50px;
    }

    .v_down {
        position: absolute;
        right: 0;
        top: 81px;
    }

    .v_preview{
        position: absolute;
        right: 0;
        bottom: 24px;
    }

    .variant_controls {
        position: absolute;
        right: 5px;
        top: 5px;
        bottom: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .configurator ul {
        margin: 0;
        padding: 0;
    }

    .configurator b {
        color: #222222;
    }

    .configurator table.conf_table {
        width: 100%;
    }

    .configurator table.conf_table td, .configurator table.conf_table th {
        padding: 2px 5px;
    }

    .t_bck_color {
        background-color: #ffffff !important;
    }

    .configurator .rounded {
        border-radius: 4px !important;
    }

    .configurator .shadow {
        box-shadow: 0 0 5px 0 rgb(0 0 0 / 10%) !important;
    }

    .configurator .current_params {
        max-height: 100%;
        overflow: auto;
    }

    .configurator .tree_block {
        max-height: 500px;
    }

    .px5 {
        padding-left: 5px;
        padding-right: 5px;
    }

    .p5 {
        padding: 5px;
    }

    .mb5 {
        margin-bottom: 5px;
    }


    .t_bck_color2 {
        background-color: #f7f7f7 !important;
    }

    .link {
        cursor: pointer;
        text-decoration: underline;
        color: #0066cc;
    }

    .var_heading {
        font-weight: bold;
    }

    .value_input {
        margin-bottom: 5px;
    }

    .value_input button {
        cursor: pointer;
        border: 1px solid #e5e6e7;
        background: none;

    }

    .value_input button:hover {
        color: #0066cc;;
        border-color: #0066cc;
    }


    .value_input .var_list {
        position: fixed;
        /*right: 0;*/
        /*top: 100%;*/
        z-index: 200;
        width: 300px;
        background: #ffffff;
        margin: 0;
        padding: 5px;
        max-height: 300px;
        overflow: auto;
    }


    .value_input > div {
        display: flex;
        position: relative;
    }


    .var_list li {
        margin: 6px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .var_list li button {
        width: 16px;
        font-weight: bold;
        border: 1px solid #e5e6e7;
        margin: 0;
        text-align: center;
        padding: 0;
    }

    .var_list li button:hover {
        color: #0066cc;;
        border-color: #0066cc;
    }

    .configurator {
        scrollbar-color: #0066cc #e0e0e0;
        scrollbar-width: thin;
    }

    .configurator *::-webkit-scrollbar {
        width: 1px;
        height: 1px;
    }

    .configurator *::-webkit-scrollbar-track {
        background-color: #cacaca;
    }

    .configurator *::-webkit-scrollbar-thumb {
        background: #0066cc;
        background: linear-gradient(#297ed4, #004f9d);
    }

    .configurator .p_input_group input {
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: block;
        padding: 3px 5px;
        width: 100%;
    }

    .configurator .p_input_group select {
        width: 100%;
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: block;
        padding: 3px 5px;
    }

    .a_list a {
        display: inline-block;
        font-size: 17px;
    }

    .relative {
        position: relative;
    }

    .m_close {
        position: absolute;
        right: 5px;
        top: 5px;
        font-size: 20px;
        line-height: 20px;
        font-weight: bold;
        cursor: pointer;
        opacity: 0.8;
    }

    .m_close:hover {
        opacity: 1;
    }

    .m_wrapper {
        position: absolute;
        z-index: 1;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
    }

    .m_content {
        position: absolute;
        z-index: 1;
        left: 5px;
        right: 5px;
        top: 5px;
        bottom: 5px;
        padding: 10px;
        background: #fff;
    }

    ul {
        list-style: none;
    }

    .flex {
        display: flex;
    }

    .flex_wrap {
        flex-wrap: wrap;
    }

    .p_input_group {
        margin-bottom: 10px;
        position: relative;
    }

    select.p_input {
        width: 100%;
        padding: 5px;
        border: 1px solid #cacaca;
        border-radius: 0;
        color: #333;
    }

    .flex_row {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        height: 100%;
        align-items: stretch;
    }

    .flex_vcenter {
        align-items: center;
    }

    .p10 {
        padding: 10px;
    }

    .mb0 {
        margin-bottom: 0;
    }

    .mt5 {
        margin-top: 5px;
    }

    .w25 {
        width: 25%;
    }

    .w33 {
        width: 33.333%;
    }

    .w50 {
        width: 50%;
    }

    .w75 {
        width: 75%;
    }

    .w100 {
        width: 100%;
    }

    .mb10{
        margin-bottom:10px;
    }

    ul.tab_menu_h{
        margin-bottom: 10px;
    }

    .tabs_left .active span{

        background-color: white;
        border-right: 5px solid white;
        margin-right: -5px;


    }
    .tabs_left span{
        display: block;
        position: relative;
        z-index: 1;
        cursor: pointer;
        padding: 10px;
    }


</style>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php'; ?>
<script>
    view_mode = true;
</script>
<script src="/common_assets/libs/vue.min.js"></script>
<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/pagination.js"></script>

<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>

<script src="/common_assets/admin_js/vue/kitchen/model3d_item.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/material_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/image_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/items_picker.php');?>
