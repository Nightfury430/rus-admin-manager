<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Фон 360</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<form @submit="submit" id="sub_form" ref="form" action="<?php echo site_url('catalog/add_item_ajax/' . $controller_name ) ?>">
    <input ref="success_url" id="form_success_url" value="<?php echo site_url('/catalog/items/' . $controller_name) ?>" type="hidden">
    <input id="controller_name" value="<?php echo $controller_name ?>" type="hidden">
    <?php if (isset($id)): ?>
        <input id="item_id" value="<?php echo $id ?>" type="hidden">
    <?php endif; ?>

    <div v-cloak class="wrapper wrapper-content  animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic_params_tab" class="tab-pane active">
                            <div class="panel-body">
                                <fieldset>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="text" v-model="item.name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                                        <div class="col-sm-10">
                                            <div class="icon_block">
                                                <img @click="file_target = 'icon';" data-toggle="modal" data-target="#material_filemanager" style="max-width: 78px" :src="correct_url(item.icon)" alt="">
                                                <i @click="file_target = 'icon';" data-toggle="modal" data-target="#material_filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i v-if="check_image('icon')" @click="remove_image('icon')" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['image'] ?></label>
                                        <div class="col-sm-10">
                                            <div class="icon_block">
                                                <img @click="file_target = 'src';" data-toggle="modal" data-target="#material_filemanager" style="max-width: 78px" :src="correct_url(item.src)" alt="">
                                                <i @click="file_target = 'src';" data-toggle="modal" data-target="#material_filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i v-if="check_image('src')" @click="remove_image('src')" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                            </div>
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
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items/' . $controller_name) ?>"><?php echo $lang_arr['cancel'] ?></a>
                                <?php if (isset($id)): ?>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add'] ?></button>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="modal inmodal" id="material_filemanager" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                    <h5 class="modal-title">Выбрать файл</h5>
                </div>
                <div class="modal-body">
                    <filemanager :mode="'images'" ref="m_fileman" @select_file="sel_file($event)"></filemanager>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                </div>
            </div>
        </div>
    </div>


</form>


<style>
    .icon_block {
        min-height: 60px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        opacity: 1;
    }

    #model_params_tab {
        user-select: none;
    }

</style>


<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/admin_js/vue/kitchen/background.js"></script>


