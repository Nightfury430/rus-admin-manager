<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Элемент каталога</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<?php
if (isset($common) && $common == 1) {
    $submit_url = site_url( $controller_name . '/items_add_ajax_common/' );
    if(isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items_common/'.$controller_name);
} else {
    $submit_url = site_url( $controller_name . '/item_add/' );
    if(isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/'.$controller_name);
}
?>

<div id="app">
    <div class="wrapper wrapper-content">
        <div ref="sub_form" id="sub_form"  data-action="<?php echo site_url( 'material_types/item_add/' ) ?><?php if (isset($id)) echo $id ?>">
            <input id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
            <?php if (isset($id)): ?>
                <input id="item_id" value="<?php echo $id ?>" type="hidden">
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#restrictions_tab">Ограничения</a></li>
                            <li><a @click="resize_viewport()" class="nav-link " data-toggle="tab" href="#params_tab"><?php echo $lang_arr['params'] ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="basic_params_tab" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                        <div class="col-sm-10">
                                            <input @change="change_name()" v-model="item.name" type="text" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
<!--                                        <div class="col-sm-10">-->
<!--                                            <v-select-->
<!--                                                    multiple-->
<!--                                                    :close-on-select="false"-->
<!--                                                    :clearable="true"-->
<!--                                                    :value="item.category"-->
<!--                                                    label="name"-->
<!--                                                    :options="$options.cat_ordered"-->
<!--                                                    :reduce="category => category.id"-->
<!--                                                    v-model="item.category"-->
<!--                                                    :key="item.category"-->
<!--                                            >-->
<!--                                                <template v-slot:selected-option="option">-->
<!--                                                    <span v-if="option.parent != 0">{{cat_hash[option.parent].name}} / &nbsp;</span>{{ option.name }}-->
<!--                                                </template>-->
<!--                                                <template v-slot:option="option">-->
<!--                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">-->
<!--                                                            <span v-if="option.parent != 0">{{cat_hash[option.parent].name}} / </span>{{ option.name }}-->
<!--                                                        </span>-->
<!--                                                </template>-->
<!--                                            </v-select>-->
<!--                                        </div>-->
                                        <div v-if="options_ready" class="col-sm-10">
                                            <pp_categories ref="cat_pick" @e_update="item.category = $event" :selected="item.category" :controller="'catalogue'"></pp_categories>
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
                                            <input type="number" v-model="item.order" type="text" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Элемент наполнения</label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input @change="change_is_fill()" v-model="item.params.spec.is_fill" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div v-show="item.params.spec.is_fill">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Подстраивать размер по ширине</label>
                                            <div class="col-sm-10">
                                                <label class="switch">
                                                    <input v-bind:true-value="0" v-bind:false-value="1" @change="change_fill_size('x')" v-model="item.params.spec.fill.size.x" type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Подстраивать размер по высоте</label>
                                            <div class="col-sm-10">
                                                <label class="switch">
                                                    <input v-bind:true-value="0" v-bind:false-value="1" @change="change_fill_size('y')" v-model="item.params.spec.fill.size.y" type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Подстраивать размер по глубине</label>
                                            <div class="col-sm-10">
                                                <label class="switch">
                                                    <input v-bind:true-value="0" v-bind:false-value="1" @change="change_fill_size('z')" v-model="item.params.spec.fill.size.z" type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                                        <div class="col-sm-5">

                                            <div class="icon_block">
                                                <img @click="$refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" style="max-width: 78px" :src="correct_url(item.icon)" alt="">
                                                <i @click="$refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i v-if="item.icon != ''" @click="item.icon = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                            </div>

                                        </div>
                                        <div class="col-sm-5">

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="restrictions_tab" class="tab-pane">

                                <div class="panel-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Ограничения фасадов</label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input @change="change_rest_facades_on" :true-value="1" :false-value="0" v-model="use_facades_rest" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div v-if="use_facades_rest" class="form-group row">
                                        <label class="col-sm-2 col-form-label">Фасады</label>
                                        <div class="col-sm-10">
                                            <v-select
                                                    multiple
                                                    :close-on-select="false"
                                                    :clearable="true"
                                                    :value="rest_facades"
                                                    label="id"
                                                    @input="change_rest_facades"
                                                    :options="$options.facades_data.items"
                                                    :reduce="item => item.id"
                                                    v-model="rest_facades"
                                                    :key="item=>item.id"
                                            >

                                            <template v-slot:option="option">
                                                {{option.id}} {{ option.name }} Категория: {{option.category}}
                                            </template>
                                                <template v-slot:selected-option="option">
                                                    {{option.id}} {{ option.name }} Категория: {{option.category}}
                                                </template>
                                            </v-select>
                                        </div>
                                    </div>

                                    <div v-if="use_facades_rest" class="form-group row">
                                        <label class="col-sm-2 col-form-label">Фасад по умолчанию</label>
                                        <div class="col-sm-10">
                                            <select @change="change_rest_facades" class="form-control" v-model="rest_facade_default">
                                                <option :value="opt" v-for="opt in rest_facades">{{$options.facades_hash[opt].id}} {{$options.facades_hash[opt].name}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Разрешить смену типа фасадов</label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input @change="change_rest('change_facade_types', rest.change_facade_types)" :true-value="1" :false-value="0" v-model="rest.change_facade_types" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Ограничения ручек</label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input @change="change_rest_handles_on" :true-value="1" :false-value="0" v-model="use_handles_rest" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div v-if="use_handles_rest">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Режим ограничения</label>
                                            <div class="col-sm-10">
                                                <select @change="change_rest_handles_mode()" class="form-control" v-model="rest_handles_mode">
                                                    <option :value="'c'">Категории</option>
                                                    <option :value="'i'">Модели</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div v-if="rest_handles_mode == 'c'">
                                            <div v-if="rest_handles_mode == 'c'" class="form-group row">
                                                <label class="col-sm-2 col-form-label">Доступные категории ручек</label>
                                                <div class="col-sm-10">
                                                    <v-select
                                                            multiple
                                                            :close-on-select="false"
                                                            :clearable="true"
                                                            :value="rest_handles"
                                                            label="id"
                                                            @input="change_rest_handles"
                                                            :options="$options.handles_data.categories"
                                                            :reduce="item => item.id"
                                                            v-model="rest_handles"
                                                            :key="item=>item.id"
                                                    >
                                                        <template v-slot:option="option">
                                                            {{option.id}} {{ option.name }}
                                                        </template>
                                                        <template v-slot:selected-option="option">
                                                            {{option.id}} {{ option.name }}
                                                        </template>
                                                    </v-select>
                                                </div>
                                            </div>

                                            <div v-if="rest_handles_mode == 'c'" class="form-group row">
                                                <label class="col-sm-2 col-form-label">Ручки по умолчанию</label>
                                                <div class="col-sm-10">
                                                    <pp_items  ref="items_picker" @e_update="change_rest_handles_default($event)" :categories="get_cats()" :selected_item="rest_handles_default" :lang="$options.lang" :controller="'handles'"></pp_items>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="rest_handles_mode == 'i'">
                                            <div v-if="rest_handles_mode == 'i'" class="form-group row">
                                                <label class="col-sm-2 col-form-label">Доступные модели ручек</label>
                                                <div class="col-sm-10">
                                                    <pp_items ref="items_picker2" @e_update="rest_handles = $event; change_rest_handles();" :selected_items="rest_handles" :lang="$options.lang" :controller="'handles'"></pp_items>
                                                </div>
                                            </div>

                                            <div v-if="rest_handles_mode == 'i'" class="form-group row">
                                                <label class="col-sm-2 col-form-label">Ручка по умолчанию</label>
                                                <div class="col-sm-10">
                                                    <select @change="change_rest_handles" class="form-control" v-model="rest_handles_default">
                                                        <option :value="opt" v-for="opt in rest_handles">{{$options.handles_hash[opt].id}} {{$options.handles_hash[opt].name}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Разрешить смену ручек</label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input @change="change_rest('change_facade_types', rest.change_handle)" :true-value="1" :false-value="0" v-model="rest.change_handle" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Разрешить свободный ввод размеров</label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input @change="change_rest('free_size', rest.free_size)" :true-value="1" :false-value="0" v-model="rest.free_size" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div id="params_tab" class="tab-pane">
                                <div class="panel-body">
                                    <div class="row form-group">
                                        <div class="col-6">
                                            <configurator ref="config"></configurator>
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
                                            <div class="make_icon btn-group">
                                                <button data-toggle="dropdown" type="button" class="btn btn-sm btn-success dropdown-toggle" aria-expanded="false">Сделать иконку</button>
                                                <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 33px; left: 0px; will-change: top, left;">
                                                    <li @click="make_icon(false)" class="dropdown-item">Красная</li>
                                                    <li @click="make_icon(true)" class="dropdown-item">Серая</li>
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
                                        <button @click="submit"  class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['save'] ?></button>
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

            <div class="modal inmodal" id="filemanager" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                            <h5 class="modal-title">Выбрать файл</h5>
                        </div>
                        <div class="modal-body">

                            <?php if (isset($common) && $common == 1) :?>
                                <filemanager :type="'common'" ref="fileman" @select_file="sel_file($event)"></filemanager>
                            <?php else:?>
                                <filemanager ref="fileman" @select_file="sel_file($event)"></filemanager>
                            <?php endif;?>


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




<div style="display: none; height:100%"  id="main_app">
    <div id="viewport">

    </div>
</div>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_tree/sl-vue-tree-dark.css?<?php echo md5(date('m-d-Y-His A e'));?>">
<link rel="stylesheet" href="/common_assets/fonts/icons/new/style.css?<?php echo md5(date('m-d-Y-His A e'));?>">

<style>

    .preview_buttons{
        position: absolute;
        z-index: 10;
        font-size: 20px;
        right: 0;
        top: 0;
        margin: 0;
        padding: 0;
    }

    .preview_buttons i{
        cursor: pointer;
    }


    .make_icon {
        display: none;
        position: absolute;
        top: 5px;
        right: 20px;
        z-index: 9;
    }
    #preview_block{
        height: 700px;
    }

    #viewport {
        max-width: 100%;
        position: relative;
        height: 100%;
        max-height: 500px;
    }



    .tab_menu_h{
        display: flex;
    }

    .object_menu_params_block ul{
        display: flex;
        flex-direction: row;
        align-items: stretch;
    }

    .object_menu_params_menu{
        border-bottom: 1px solid #a3a3a3;
        padding: 0 10px;

    }

    .object_menu_params_menu  > li{
        cursor: pointer;
        border: 1px solid rgba(0, 0, 0, 0);
        border-bottom: 0;
        padding: 2px 5px;
    }

    .object_menu_params_menu > li:hover{
        background-color: #f7f7f7;
        color: #0066cc!important;
    }

    .object_menu_params_menu > li.active{
        border-color: #a3a3a3!important;
        background-color: #f7f7f7;
        color: #0066cc!important;
    }

</style>



<?php include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php'; ?>


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
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/configurator.php'?>

<script src="/common_assets/admin_js/vue/kitchen/catalogue_item.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/categories_picker.php'); ?>