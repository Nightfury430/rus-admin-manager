<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['decorations_add_item']?></h2>
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

<form id="sub_form" class="add_item" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('decorations/items_add/') ?>">

<div class="wrapper wrapper-content  animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
	        <?php echo validation_errors(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                    <li><a class="nav-link resize_three" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params']?></a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['sizes']?></a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['category']?></label>
                                <div class="col-sm-8">
                                <v-select :options="categories" label="name" v-model="category">
                                    <template v-slot:option="option">
                                        <span class="select_cat" v-bind:class="{main_cat: option.parent == 0}">{{ option.name }}</span>
                                    </template>
                                </v-select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['active']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="active" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['show_sizes']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="sizes_available" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['icon']?></label>
                                <div class="col-sm-8">
                                    <input ref="icon" type="file" name="icon" id="icon" accept="image/jpeg,image/png,image/gif">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-4">




                                    <div class="form-group model_input">
                                        <div class="form-group">
                                            <label for="icon"><?php echo $lang_arr['model_file']?></label>
                                            <input type="file" name="model" id="model" accept=".fbx">
                                        </div>
                                        <span class="glyphicon glyphicon-trash remove_model"></span>
                                    </div>

                                    <div class="form-group model_input">
                                        <div class="form-group">
                                            <label for="icon">Модель балки</label>
                                            <input type="file" name="model" id="model_rail" accept=".fbx">
                                        </div>
                                        <span class="glyphicon glyphicon-trash remove_model"></span>
                                    </div>

                                    <div class="form-group model_input">
                                        <div class="form-group">
                                            <label for="icon">Модель радиус</label>
                                            <input type="file" name="model" id="model" accept=".fbx">
                                        </div>
                                        <span class="glyphicon glyphicon-trash remove_model"></span>
                                    </div>


                                    <div class="form-group">
                                        <label for="sizes_available"><?php echo $lang_arr['decoration_material_type']?></label>
                                        <select v-model="material_type" class="form-control" name="material_type" id="material_type">
                                            <option selected value="facade"><?php echo $lang_arr['material_type_facade']?></option>
                                            <option value="corpus"><?php echo $lang_arr['material_type_corpus']?></option>
                                            <option value="own"><?php echo $lang_arr['material_type_own']?></option>
                                        </select>
                                    </div>


                                    <div v-show="material_type == 'own'" class="mat_block">
                                        <div class="form-group">
                                            <label for="color"><?php echo $lang_arr['color']?></label>
                                            <input type="text" class="form-control" name="color" id="color" value="#ffffff">
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="roughness"><?php echo $lang_arr['roughness']?></label>
                                                    <input type="number" value="0.8" min="0" max="1" step="0.01" class="form-control" id="roughness" name="roughness" placeholder="0.15">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="metalness"><?php echo $lang_arr['metalness']?></label>
                                                    <input type="number" value="0" min="0" max="1" step="0.01" class="form-control" id="metalness" name="metalness" placeholder="0.15">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group map_input">
                                            <label for="map"><?php echo $lang_arr['texture_file']?></label>
                                            <input type="file" name="map" id="map" accept="image/jpeg,image/png,image/gif">
                                            <span class="glyphicon glyphicon-trash remove_map"></span>
                                        </div>

                                        <div class="texture_params">

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="real_width"><?php echo $lang_arr['texture_real_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                        <input disabled type="number" class="form-control" name="real_width" id="real_width" placeholder="256">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="real_height"><?php echo $lang_arr['texture_real_heght']?> (<?php echo $lang_arr['units']?>)</label>
                                                        <input disabled type="number" class="form-control" name="real_height" id="real_height" placeholder="256">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="stretch_width"><?php echo $lang_arr['stretch_width']?></label>
                                                        <select disabled class="form-control" name="stretch_width" id="stretch_width">
                                                            <option value="0"><?php echo $lang_arr['no']?></option>
                                                            <option selected value="1"><?php echo $lang_arr['yes']?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="stretch_height"><?php echo $lang_arr['stretch_height']?></label>
                                                        <select disabled class="form-control" name="stretch_height" id="stretch_height">
                                                            <option value="0"><?php echo $lang_arr['no']?></option>
                                                            <option selected value="1"><?php echo $lang_arr['yes']?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="wrapping"><?php echo $lang_arr['wrapping_type']?></label>
                                                        <select disabled class="form-control" name="wrapping" id="wrapping">
                                                            <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror']?></option>
                                                            <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat']?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="transparent"><?php echo $lang_arr['transparent']?></label>
                                                        <select disabled class="form-control" name="transparent" id="transparent">
                                                            <option selected value="0"><?php echo $lang_arr['no']?></option>
                                                            <option value="1"><?php echo $lang_arr['yes']?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    </div>


                                <div class="col-sm-8">
                                    <div class="row hidden">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="model_width"><?php echo $lang_arr['obj_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" class="form-control" id="model_width" value="450">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="model_height"><?php echo $lang_arr['obj_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" class="form-control" id="model_height" value="720">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 hidden">
                                            <div class="form-group">
                                                <label for="model_depth"><?php echo $lang_arr['obj_thickness']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" class="form-control" id="model_depth" value="18">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="three_view" style="height: 560px">

                                    </div>
                                </div>
                                <input type="hidden" name="material" value="">
                            </div>
                        </div>

                    </div>

                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['wall_distance']?> (<?php echo $lang_arr['units'] ?>)</label>
                                <div class="col-sm-8">
                                    <input v-model="wall_distance" type="number" class="form-control" id="wall_distance" value="0">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['price_type']?></label>
                                <div class="col-sm-8">
                                    <select v-model="price_type" class="form-control" name="price_type" id="price_type">
                                        <option selected value="1"><?php echo $lang_arr['price_type_m2']?></option>
                                        <option value="0"><?php echo $lang_arr['price_type_pm']?></option>
                                    </select>
                                </div>
                            </div>


                            <div v-show="price_type == 0" class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['price']?></label>
                                <div class="col-sm-8">
                                    <input v-model="price_pm" type="text" class="form-control" value="0">
                                </div>
                            </div>



                            <div class="hr-line-dashed"></div>


                            <draggable
                                    v-bind="dragOptions"
                                    @start="drag = true"
                                    @end="drag = false"
                                    v-model="variants"
                                    handle=".drag_handle"
                                    draggable=".sizes_row">
                                <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                                <div v-for="(item, index) in variants" :key="item.id" class="sizes_row row">
                                    <span @click="remove_variant" class="glyphicon glyphicon-trash remove_panel"></span>
                                    <span class="fa fa-arrows drag_handle"></span>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['name']?></label>
                                            <input v-model="item.name" type="text" class="form-control name_input" >
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['code']?></label>
                                            <input v-model="item.code" type="text" class="form-control code_input" >
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div v-show="price_type == 1" class="form-group">
                                            <label><?php echo $lang_arr['price']?></label>
                                            <input v-model="item.price" type="text" min="0" class="form-control price_input">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['obj_width']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input v-model="item.width" type="number" min="0" class="form-control width_input">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['obj_height']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input v-model="item.height" type="number" min="0" class="form-control height_input" >
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label><?php echo $lang_arr['obj_depth']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input v-model="item.depth" type="number" min="0" class="form-control depth_input" >
                                        </div>
                                    </div>
                                </div>
                                </transition-group>

                            </draggable>

                            <button @click="add_variant" type="button" class="btn btn-sm btn-primary"><?php echo $lang_arr['add_size']?></button>
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

                    <div class="hr-line-dashed"></div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-white btn-sm" href="<?php echo site_url('decorations/items_index/'.$_SESSION['selected_decorations_items_category'].'/'.$_SESSION['selected_decorations_items_per_page'].'/'.$_SESSION['selected_decorations_items_pagination']) ?>"><?php echo $lang_arr['cancel']?></a>
                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</form>


<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">
<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<script src="/common_assets/libs/three.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>

<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>

<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/decorations.js" type="text/javascript"></script>



<style>



    span.select_cat{
        margin-left: 10px;
    }

    span.main_cat{
        font-weight: bold;
        margin-left: 0px;
    }

    .sizes_row{
        position: relative;
        padding-top: 10px;
        padding-bottom: 10px;
        margin-bottom: 10px!important;
        border: 1px solid #e5e6e7;
        padding-left: 15px;
        padding-right: 15px;
    }

    .remove_panel{
        position: absolute;
        top:10px;
        right: 10px;
        color: #ff0000;
        cursor: pointer;
        z-index: 5;
    }

    .drag_handle{
        position: absolute;
        top: 75px;
        left: 5px;
        z-index: 5;
        cursor: pointer;
        font-size: 17px;
    }

    .texture_params{
        display: none;
    }

    .map_input{
        display: block;
        width: 100%;
        position: relative;
    }

    .remove_map, .remove_model{
        position: absolute;
        right: 0;
        bottom:0;
        color: #ff0000;
        display: none;
        cursor: pointer;
    }

    .option.top_cat{
        font-weight: bold;
    }

    .option.sub_cat{
        padding-left: 40px;
    }

    .sp-input{
        background: #ffffff;
    }

</style>