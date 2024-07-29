

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">

        <?php if(isset($common) && $common == 1):?>
            <?php if(isset($id)):?>
                <h2 style="color: red"> Редактировать ручку (ОБЩАЯ БАЗА)</h2>
            <?php else:?>
                <h2 style="color: red"> Добавить ручку (ОБЩАЯ БАЗА)</h2>
            <?php endif;?>
        <?php else:?>
            <h2><?php echo $lang_arr['handles_items_add'] ?></h2>
        <?php endif;?>




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
    $submit_url = site_url( $controller_name . '/items_add_ajax/' );
    if(isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/'.$controller_name);
}
?>

<form @submit="submit" id="sub_form" ref="sub_form" action="<?php echo $submit_url ?>">
    <input id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
    <?php if (isset($id)): ?>
        <input id="item_id" value="<?php echo $id ?>" type="hidden">
    <?php endif; ?>


    <div v-cloak class="wrapper wrapper-content  animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                        <li><a @click="resize_viewport" class="nav-link" data-toggle="tab" href="#model_params_tab"><?php echo $lang_arr['model_params'] ?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#variants_tab"><?php echo $lang_arr['sizes'] ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic_params_tab" class="tab-pane active">
                            <div class="panel-body">

                                <fieldset>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                        <div class="col-sm-10">
                                            <input v-model="item.name" type="text" class="form-control" id="name" name="name">
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
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="number" v-model="item.order" class="form-control">
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

                                </fieldset>

                            </div>
                        </div>
                        <div id="model_params_tab" class="tab-pane">
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-4">

                                        <div class="form-group">
                                            <label for="type"><?php echo $lang_arr['model_type']?></label>
                                            <select v-model="item.type" class="form-control" name="type" id="type">
                                                <option :value="'common'"><?php echo $lang_arr['handle_model_type_common']?></option>
                                                <option :value="'facade_profile'"><?php echo $lang_arr['handle_model_type_profile']?></option>
                                                <option :value="'gola'"><?php echo $lang_arr['handle_model_type_gola']?></option>
                                                <option :value="'overhead'"><?php echo $lang_arr['handle_model_type_overhead']?></option>
                                                <option :value="'overhead_profile'"><?php echo $lang_arr['handle_model_type_overhead_profile']?></option>
                                            </select>
                                        </div>

                                        <div class="row ">
                                            <div class="col-12 form-group">
                                                <div v-if="item.model != ''" class="col-12 mb-2 text=primary">
                                                    <?php echo $lang_arr['current_model'] ?>: <a download :href="correct_download_url(item.model)"><b>{{correct_model_url(item.model)}}</b></a>
                                                </div>

                                                <button @click="file_target = 'model' ;$refs.fileman.data_mode = 'models'" type="button" data-toggle="modal" data-target="#filemanager" class="btn-block form-control-sm btn btn-outline-info" title="Выбрать или загрузить модель">
                                                    <span class="fa fa-folder-open"></span>
                                                    Выбрать
                                                </button>
                                            </div>

                                            <div class="col-12 form-group">
                                                <label><?php echo $lang_arr['color'] ?></label>
                                                <input type="text" class="form-control" name="color" id="color" value="#ffffff">
                                            </div>
                                            <div class="col-6 form-group">
                                                <label><?php echo $lang_arr['roughness'] ?></label>
                                                <input @input="material_change()" type="number" min="0" max="1" step="0.01" class="form-control" v-model.number="item.material.params.roughness" placeholder="0.15">
                                            </div>
                                            <div class="col-6 form-group">
                                                <label><?php echo $lang_arr['metalness'] ?></label>
                                                <input @input="material_change()" type="number" min="0" max="1" step="0.01" class="form-control" v-model.number="item.material.params.metalness" placeholder="0.15">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 form-group">
                                                <label style="display: block">Текстура</label>

                                                <div class="icon_block">
                                                    <img @click="file_target = 'map'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" style="max-width: 78px" :src="correct_url(item.material.params.map)" alt="">
                                                    <i @click="file_target = 'map'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                    <i v-if="item.material.params.map != ''" @click="item.material.params.map = ''; material_change()" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                                </div>

                                            </div>
                                            <div class="col-6 form-group">
                                                <label style="display: block">Карта нормалей</label>

                                                <div class="icon_block">
                                                    <img @click="file_target = 'normalMap'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" style="max-width: 78px" :src="correct_url(item.material.params.normalMap)" alt="">
                                                    <i @click="file_target = 'normalMap'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                    <i v-if="item.material.params.normalMap != ''" @click="item.material.params.normalMap = ''; material_change()" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                                </div>

                                            </div>
                                        </div>

                                        <div v-show="item.material.params.map != '' || item.material.params.normalMap != ''" class="texture_params">

                                            <div class="row">
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['stretch_width'] ?></label>
                                                    <div>
                                                        <label class="switch">
                                                            <input @change="material_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.material.add_params.stretch_width" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['stretch_height'] ?></label>
                                                    <div>
                                                        <label class="switch">
                                                            <input @change="material_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.material.add_params.stretch_height" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['texture_real_width'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                    <input @input="material_change()" v-model="item.material.add_params.real_width" :disabled="item.material.add_params.stretch_width == 1" type="number" class="form-control" placeholder="256">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['texture_real_heght'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                    <input @input="material_change()" v-model="item.material.add_params.real_height" :disabled="item.material.add_params.stretch_height == 1" type="number" class="form-control" placeholder="256">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 form-group">
                                                    <label><?php echo $lang_arr['wrapping_type'] ?></label>
                                                    <select @change="material_change()" v-model="item.material.add_params.wrapping" class="form-control">
                                                        <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror'] ?></option>
                                                        <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat'] ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 form-group">
                                                <label><?php echo $lang_arr['transparent'] ?></label>
                                                <div>
                                                    <label class="switch">
                                                        <input @change="material_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.material.params.transparent" type="checkbox">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div id="preview_block" class="col-8">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="variants_tab" class="tab-pane">
                            <div class="panel-body models_list">

                                <draggable class="" v-model="item.variants" v-bind="dragOptions" handle=".handle" group="parent" :move="on_drag" @start="drag = true" @end="drag = false">
                                    <div v-for="(variant,index) in item.variants"  class="d-flex flex-row flex-wrap form-group draggable_panel pl-2 pr-5">
                                        <i  class="fa fa-align-justify handle"></i>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['name'] ?></label>
                                            <input class="form-control" v-model="variant.name" type="text">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['code'] ?></label>
                                            <input class="form-control" v-model="variant.code" type="text">
                                        </div>

                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['axis_size'] ?></label>
                                            <input class="form-control" v-model="variant.axis_size" type="text">
                                        </div>


                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['width'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                            <input class="form-control" v-model="variant.width" type="number">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['height'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                            <input class="form-control" v-model="variant.height" type="number">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['depth'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                            <input class="form-control" v-model="variant.depth" type="number">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['price'] ?></label>
                                            <input class="form-control" v-model="variant.price" type="text">
                                        </div>
                                        <div v-show="item.type != 'common'" class="col-4 py-2">
                                            <label><?php echo $lang_arr['height_offset'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                            <input step="0.1" class="form-control" v-model="variant.offset_y" type="number">
                                        </div>

                                        <button  @click="remove_variant(index)" style="position: absolute; right: 10px; top:10px; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>

                                    </div>
                                </draggable>


                                <div class="row form-group">
                                    <div class="col-12">
                                        <button @click="add_variant()" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
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
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add'] ?></button>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-8 text-right">
                                <div v-if="item.id">
                                    <button type="button" @click="download_assets()" class="btn btn-info btn-outline btn-sm">Скачать файлы модели</button>
                                </div>
                            </div>
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
                        <filemanager  ref="fileman" @select_file="sel_file($event)"></filemanager>
                    <?php endif;?>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                </div>
            </div>
        </div>
    </div>

</form>
<div style="display: none" id="main_app">
    <div id="viewport">

    </div>
</div>

<style>
    #viewport {
        max-width: 100%;
        position: relative;
        height: 100%;
        max-height: 500px;
        min-height: 300px;
    }

    .handle {
        position: absolute;
        z-index: 9;
        top: 11px;
        left: 3px;
        font-size: 15px !important;
    }

    .drag_handle {
        z-index: 1;
        position: absolute;
        width: 16px;
        left: 2px;
        cursor: move;
        top: 5px;
        height: 100%;


    }

    .drag_handle_icon {
        background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAGCAYAAADgzO9IAAAAHUlEQVQYV2NkwAEYiZHwhSraDKKRdeCUQDEVpx0AjnMCB4l5XDkAAAAASUVORK5CYII=) repeat;
        height: 18px;
        width: 16px;
        display: block;
    }

    .drag_handle.inner {
        top: 16px;
    }

</style>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">

<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>

<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<script src="/common_assets/libs/jszip.min.js"></script>
<script src="/common_assets/libs/jszip.utils.min.js"></script>

<script src="/common_assets/libs/three106.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>


<script src="/common_assets/admin_js/vue/kitchen/handle.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>