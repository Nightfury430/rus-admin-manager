<div v-cloak id="sub_form">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{lang('categories')}}</h2>
        </div>
        <div class="col-lg-2">

        </div>
    </div>


    <div class="wrapper wrapper-content  animated fadeInRight">


        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">

                    <div class="ibox-content">

                        <a v-show="controller == 'module_sets'" class="btn btn-w-m btn-default" href="<?php echo site_url('module_sets/sets_index/') ?>" role="button"><?php echo $lang_arr['back_to_modules_sets']?></a>

                        <button v-show="check_controller()" @click="show_add_modal(true)" class="mb-3 btn btn-w-m btn-primary btn-outline" type="button">{{lang('add')}}</button>

                        <draggable :swap-threshold="0.1" :disabled="!check_controller()" :animation="250" v-model="items" :ghostClass="'ghost'" :group="get_group()" :empty-insert-threshold="100" handle=".handle" @start="drag = true" @end="end_drag()">
                            <div class="draggable_parent " v-for="(element,index) in items">
                                <div class="dd-handle d-flex flex-row align-items-center">
                                    <div v-on:mousedown="check_md($event, element)" v-on:mouseup="check_mu(element)" v-show="check_controller()">
                                        <i class="fa fa-align-justify handle"></i>
                                    </div>
                                    <div class="pl-3 col-5">
                                        <span>{{element.name}}</span>
                                    </div>
                                    <div class="ml-auto">
                                        <i @click="show_add_modal(false, index)" class="fa fa-plus btn btn-outline btn-primary"></i>
                                        <i @click="show_edit_modal(element)" class="fa fa-edit btn btn-outline btn-success"></i>
                                        <i @click="change_active(element)" :class="get_eye_class(element)" class="fa fa-eye btn btn-outline"></i>
                                        <i @click="show_swal(element, index)" :class="get_delete_class(element)" class="fa fa-trash delete btn btn-outline"></i>
                                    </div>
                                </div>



                                <draggable  :animation="250" :ghostClass="'ghost'" class="ml-5" :class="{drop_zone: md == true, drop_zone_hidden: md == false, hide_dz: hide_dz}" v-model="element.children" :group="get_group()" :empty-insert-threshold="100" handle=".handle" @start="drag = true" @end="end_drag()">
                                    <div class="draggable_child " v-for="(item, item_index) in element.children">
                                        <div  class="dd-handle d-flex flex-row align-items-center">
                                            <div v-on:mousedown="md = true" v-on:mouseup="md = false">
                                                <i class="fa fa-align-justify handle"></i>
                                            </div>
                                            <div class="pl-3 col-5">
                                                <div>{{item.name}}</div>
                                            </div>
                                            <div class="ml-auto ">
                                                <i @click="show_edit_modal(item)" class="fa fa-edit btn btn-outline btn-success"></i>
                                                <i @click="change_active(item)" :class="get_eye_class(item, element)" class="fa fa-eye btn btn-outline"></i>
                                                <i @click="show_swal(item, index, true, item_index)" :class="get_delete_class(item)" class="fa fa-trash delete btn btn-outline"></i>
                                            </div>
                                        </div>
                                    </div>
                                </draggable>
                                <div v-show="hide_dz" class="dz"></div>
                            </div>
                        </draggable>

                        <button v-show="check_controller()" @click="show_add_modal(true)" class="mt-3 btn btn-w-m btn-primary btn-outline" type="button">{{lang('add')}}</button>

                    </div>
                </div>


            </div>
        </div>
    </div>
    <div v-show="modals.add_category.show" class="bpl_modal_wrapper">
        <div class="bpl_modal_background" :class="{shown: modals.add_category.show}"></div>
        <div class="bpl_modal_content">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="modals.add_category.show = false" type="button" class="close"><span>&times;</span></button>
                        <h4 class="modal-title">{{$options.lang['add_category']}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{$options.lang['name']}}</label>
                            <div class="col-sm-10"><input v-model="modals.add_category.name" type="text" class="form-control"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button @click="modals.add_category.show = false" type="button" class="btn btn-white">{{$options.lang['cancel']}}</button>
                        <button @click="add_item()" type="button" class="btn btn-primary">{{$options.lang['add']}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div v-show="modals.edit_category.show" class="bpl_modal_wrapper">
        <div class="bpl_modal_background" :class="{shown: modals.edit_category.show}"></div>
        <div class="bpl_modal_content">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="modals.edit_category.show = false" type="button" class="close"><span>&times;</span></button>
                        <h4 class="modal-title">{{$options.lang['edit_category']}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{$options.lang['name']}}</label>
                            <div class="col-sm-10"><input v-model="modals.edit_category.name" type="text" class="form-control"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{$options.lang['description']}}</label>
                            <div class="col-sm-10"><textarea rows="5" v-model="modals.edit_category.description"  class="form-control"></textarea></div>
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button @click="modals.edit_category.show = false" type="button" class="btn btn-white">{{$options.lang['cancel']}}</button>
                        <button @click="update_item()" type="button" class="btn btn-primary">{{$options.lang['save']}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php if(isset($set_id)):?>
        <input id="set_id" ref="set" value="<?php echo $set_id ?>" type="hidden">
    <?php endif;?>

    <input class="cat_controller" ref="controller" value="<?php echo $controller ?>" type="hidden">
</div>

<style>
    .drop_zone{
        border: 1px dashed #cccccc;
        padding: 2px;
        background: #fbfbfb;
    }
    .drop_zone_hidden{
        border: 1px solid rgba(255,255,255,0);
        padding: 2px;

        background: none;
    }

    .sortable-choosen.drop_zone{
        border: 1px solid rgba(255,255,255,0);
        padding: 2px;

        background: none;
    }

    .hide_dz{
        pointer-events: none;
        padding: 0;
        border: 0;
    }

    .dz{
        border: 1px solid rgba(255,255,255,0);
        padding: 2px;
        background: none;
    }

</style>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/categories.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>