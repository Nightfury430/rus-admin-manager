<?php $contr_name = 'materials'; ?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Материал</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<form ref="form" id="sub_form" action="<?php echo site_url($contr_name . '/items_add_ajax/') ?><?php if (isset($id)) echo $id ?>">
    <input ref="success" id="form_success_url" value="<?php echo site_url('/catalog/items/' . $contr_name) ?>" type="hidden">
    <input id="controller_name" value="<?php echo $contr_name ?>" type="hidden">
    <?php if (isset($id)): ?>
        <input id="item_id" value="<?php echo $id ?>" type="hidden">
    <?php endif; ?>

    <div v-cloak class="wrapper wrapper-content  animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                        <li><a @click="resize_viewport" class="nav-link" data-toggle="tab" href="#model_params_tab"><?php echo $lang_arr['view_parameters'] ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic_params_tab" class="tab-pane active">
                            <div class="panel-body">
                                <fieldset>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="item.name" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                                        <div class="col-sm-10">
                                            <pp_image :t_width="180" :t_height="180" :p_width="75" :p_height="75" @e_update="item.params.icon=$event" :src="item.params.icon"></pp_image>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['code'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="item.code" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                                        <div class="col-sm-10">
                                            <pp_category @e_update="item.category = $event" :selected="item.category" :controller="'materials'"></pp_category>
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
                                            <input type="number" v-model.number="item.order" class="form-control" id="order" name="order">
                                        </div>
                                    </div>

                                </fieldset>
                            </div>
                        </div>
                        <div id="model_params_tab" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <pp_material  @e_update_color="update_color($event)" @e_update="update_preview($event)" :params_obj="{}"></pp_material>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row  mb-2">
                                            <div class="col-12">Размер превью</div>
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['width'] ?></label>
                                            <div class="col-4">
                                                <input @input="change_mesh_size" v-model="preview.mesh.size.x" class="form-control" type="number">
                                            </div>
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['height'] ?></label>
                                            <div class="col-4">
                                                <input @input="change_mesh_size" v-model="preview.mesh.size.y" class="form-control" type="number">
                                            </div>
                                        </div>
                                        <div style="width: 100%; height: 500px" id="three_view"></div>
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
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items/' . $contr_name) ?>"><?php echo $lang_arr['cancel'] ?></a>
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

    </div>


</form>


<!--<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">-->
<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select_3.18.3.css">

<style>
    .icon_block{
        min-height: 60px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        opacity: 1;
    }

    #model_params_tab{
        user-select: none;
    }

</style>


<script src="/common_assets/libs/three.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>
<script src="/common_assets/libs/vue.min.js"></script>
<!--<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>-->
<script src="/common_assets/libs/vue/vue_select/vue-select_3.18.3.js"></script>

<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/admin_js/vue/kitchen/materials.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>


<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/image_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/material_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/category_picker.php');?>