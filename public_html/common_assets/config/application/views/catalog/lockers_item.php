<?php


if (isset($common) && $common == 1) {
    $submit_url = site_url($controller_name . '/items_add_ajax_common/');
    if (isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items_common/' . $controller_name);
} else {
    $submit_url = site_url('catalog/item_add/' . $controller_name . '/');
    if (isset($id) && $id != 0) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/' . $controller_name);
}

?>

<div id="app">
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Параметрический элемент</h2>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div ref="sub_form" id="sub_form">
            <input ref="submit_url" id="form_submit_url" value="<?php echo $submit_url ?>" type="hidden">
            <input ref="success_url" id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
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
                                            <input  v-model="item.name" type="text" class="form-control">
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
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="number" v-model="item.order" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
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
                                    <div class="row form-group">
                                        <div class="col-6">
                                            <div class="configurator">
                                                <ul class="tab_menu_h object_menu_params_menu mb10">
                                                    <li style="display: none" :class="{active: tab == 'params'}" @click="change_tab('params')">Параметры</li>
                                                    <li :class="{active: tab == 'models'}" @click="change_tab('models')">Модели</li>
                                                    <li :class="{active: tab == 'config'}" @click="change_tab('config')">Конфигурация</li>
                                                </ul>
                                                <div v-show="tab == 'params'">
                                                    <div class="rounded shadow p10" v-show="param_add_vis == 1">

                                                        <div class="p_input_group flex_row flex_vcenter">
                                                            <div class="w50"><?php echo $lang_arr['name'] ?></div>
                                                            <div class="w50">
                                                                <input @input="var_change_name" v-model="param.name" type="text" >
                                                            </div>
                                                        </div>

                                                        <div class="p_input_group flex_row flex_vcenter">
                                                            <div class="w50"><?php echo $lang_arr['key'] ?></div>
                                                            <div class="w50">
                                                                <input v-model="param.key" type="text">
                                                            </div>
                                                        </div>
                                                            <div>
                                                                <div class="p_input_group flex_row">
                                                                    <div class="w25">Значения</div>
                                                                    <div class="w75">
                                                                        <div v-for="(opt, index) in param.values">
                                                                            <div class="p_input_group flex_row flex_vcenter">
                                                                                <div style="margin-right: 5px" class="w50">
                                                                                    <label class="mb0">Название</label>
                                                                                    <input type="text" v-model="opt.name">
                                                                                </div>

                                                                                <div>
                                                                                    <button @click="var_remove_option(index)" style="margin-top: 18px;" type="button" class="btn btn-xs btn-danger btn-outline"><i class="icon icon_trash"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div style="text-align: right">
                                                                            <label>&nbsp;</label>
                                                                            <button type="button" @click="var_add_option()" class="btn btn-sm btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="p_input_group flex_row flex_vcenter">
                                                                <div class="w50">Значение по умолчанию</div>
                                                                <div class="w50">
                                                                    <select v-model="param.value" class="p_input">
                                                                        <option :value="index" v-for="(opt, index) in param.values">{{opt.name}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>



                                                        <div>
                                                            <button type="button" @click="hide_var_add()" class="btn btn-sm btn-white"><?php echo $lang_arr['cancel'] ?></button>
                                                            <button type="button" @click="add_variable()" class="btn btn-sm btn-primary"><?php echo $lang_arr['add'] ?></button>
                                                        </div>
                                                    </div>
                                                    <div class="p10" v-show="param_add_vis == 0">
                                                        <table class="mb5 conf_table">
                                                            <thead>
                                                            <tr>
                                                                <th>Ключ</th>
                                                                <th>Название</th>
                                                                <th>Значение</th>
                                                                <th>Действия</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr v-for="(param, ind) in item.params">
                                                                <td>{{param.key}}</td>
                                                                <td>{{param.name}}</td>
                                                                <td>
                                                                    <select v-model="param.value" >
                                                                        <option :value="index" v-for="(opt,index) in param.values">{{opt.name}}</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <i @click="show_param_add(param)" class="btn btn-sm btn-success btn-outline icon icon_settings"></i>
                                                                    <i @click="remove_param(ind)" class="btn btn-sm btn-danger btn-outline icon icon_trash"></i>
                                                                </td>
                                                            </tr>
                                                            </tbody>

                                                        </table>
                                                        <div style="text-align: left">
                                                            <button class="btn btn-xs btn-outline btn-primary" @click="show_param_add()" type="button">Добавить</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-show="tab == 'models'">
                                                    <div>
                                                        <div style="padding-right: 50px" class="rounded shadow p10 mb5 relative" v-for="(variant, ind) in item.models">
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
                                                                <div class="w50 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Мин. ширина, мм</label>
                                                                        <input v-model="variant.min_x" type="number">
                                                                    </div>
                                                                </div>
                                                                <div class="w50 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Макс. ширина, мм</label>
                                                                        <input v-model="variant.max_x" type="number">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex_row flex_wrap flex_vcenter">
                                                                <div class="w50 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Мин. высота, мм</label>
                                                                        <input v-model="variant.min_y" type="number">
                                                                    </div>
                                                                </div>
                                                                <div class="w50 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Макс. высота, мм</label>
                                                                        <input v-model="variant.max_y" type="number">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex_row flex_wrap flex_vcenter">
                                                                <div class="w50 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Мин. глубина, мм</label>
                                                                        <input v-model="variant.min_z" type="number">
                                                                    </div>
                                                                </div>
                                                                <div class="w50 px5">
                                                                    <div class="p_input_group">
                                                                        <label class="mb0">Макс. глубина, мм</label>
                                                                        <input v-model="variant.max_z" type="number">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex_row flex_wrap flex_vcenter">
                                                                <div class="w100 px5">
                                                                    <?php echo $lang_arr['accessories'] ?>
                                                                    <pp_items ref="accessories_picker" @e_update="change_variant_accessories(ind, $event)" :lang="$options.lang" :count_mode="true" :unselect="true"  :selected_items="variant.accessories"  :controller="'accessories'"></pp_items>
                                                                </div>
                                                            </div>


                                                            <div class="variant_controls">
                                                                <button title="Удалить" v-show="item.models.length > 1" class="v_del btn btn-sm btn-outline btn-danger" @click="remove_variant(ind)" type="button"><i class="icon icon_trash"></i></button>
                                                                <button title="Переместить выше" v-show="ind > 0" class="v_up btn btn-sm btn-outline btn-success" @click="variant_up(ind)" type="button"><i class="icon icon_expand_up"></i></button>
                                                                <button title="Переместить ниже" v-show="item.models.length != ind + 1" class="v_down btn btn-sm btn-outline btn-success" @click="variant_down(ind)" type="button"><i class="icon icon_expand_down"></i></button>
                                                                <button title="Конфигурировать" class="v_preview btn btn-sm btn-outline btn-success" @click="configure_variant(ind)" type="button"><i class="icon icon_settings"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-sm btn-outline btn-primary" @click="add_model()" type="button">Добавить</button>
                                                    </div>
                                                </div>
                                                <div v-show="tab == 'config'">
                                                    <div class="p_input_group flex_row flex_vcenter mt5">
                                                        <div class="w50">Размер предпросмотра</div>
                                                        <div class="w50 flex_row flex_vcenter">
                                                            <div class="w33"><input @input="change_preview_size('x', $event)" :value="params.spec.variants[0].size.x" type="number"></div>x
                                                            <div class="w33"><input @input="change_preview_size('y', $event)" :value="params.spec.variants[0].size.y" type="number"></div>x
                                                            <div class="w33"><input @input="change_preview_size('z', $event)" :value="params.spec.variants[0].size.z" type="number"></div>
                                                        </div>
                                                    </div>
                                                    <div class="p_input_group flex_row flex_vcenter mt5">
                                                        <div class="w50">Выбранный размер</div>
                                                        <div class="w50">
                                                            <select :value="current_model" @change="change_current_model($event)">
                                                                <option :value="index" v-for="(mod,index) in item.models">{{index+1}} {{mod.name}} {{mod.min_x}}x{{mod.min_y}}x{{mod.min_z}}</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <configurator :root_name="'Ящик'" :tab_common="0" :tab_variants="0" :tab_materials="1"></configurator>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="preview_block" class="col-6">
                                            <div>
                                                <ul class="preview_buttons">
                                                    <li>
                                                        <i title="Режим контуров" :class="{active: edges.is_on}" @click="edges_mode" class="icon icon_contour"></i>
                                                    </li>
                                                    <li>
                                                        <i title="Прозрачные контуры" :class="{active: edges.transparent}" @click="edges_transparent" class="icon icon_transparent"></i>
                                                    </li>
                                                    <li>
                                                        <i title="Сброс камеры" @click="reset_camera" class="icon icon_camera"></i>
                                                    </li>
                                                    <li>
                                                        <i title="Сделать иконку" @click="make_icon(false)" class="icon icon_img"></i>
                                                    </li>
                                                </ul>
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
                                <div class="col-sm-8 text-right">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>



<link rel="stylesheet" href="/common_assets/libs/vue/vue_tree/sl-vue-tree-dark.css?<?php echo md5(date('m-d-Y-His A e')); ?>">
<link rel="stylesheet" href="/common_assets/fonts/icons/new/style.css?<?php echo md5(date('m-d-Y-His A e')); ?>">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php'; ?>
<div style="display: none; height:100%"  id="main_app">
    <div id="viewport">

    </div>
</div>
<style>

    .preview_buttons {
        position: absolute;
        z-index: 10;
        font-size: 20px;
        right: 0;
        top: 0;
        margin: 0;
        padding: 0;
    }

    .preview_buttons i {
        cursor: pointer;
    }


    .make_icon {
        display: none;
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
        /*max-height: 500px;*/
    }
    #viewport canvas{
        border: 1px solid;
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
        /*max-height: 500px;*/
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

    .my10{
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .configurator ul.tab_menu_h{
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


<script>
    console.log(view_mode)
    console.log(viewport)
    view_mode = true;
    //view_path = "<?php //echo $this->config->item('const_path')?>//"
</script>
<!--<script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>-->
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_tree/sl-vue-tree.js"></script>
<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/configurator.php' ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/image_picker.php');?>
<script src="/common_assets/admin_js/vue/kitchen/lockers_item.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
