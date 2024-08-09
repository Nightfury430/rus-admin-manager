<div v-cloak id="sub_form">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a v-show="controller == 'module_sets'" class="btn  btn-w-m btn-info" style="position: absolute; right: 35px" href="<?php echo site_url('module_sets/sets_index/') ?>" role="button"><?php echo $lang_arr['back_to_modules_sets']?></a>
                        <button v-show="check_controller()" @click="show_add_modal(true)" data-bs-toggle="modal" data-bs-target="#add_category_modal" class="mb-3 btn btn-w-m btn-primary" type="button">{{lang('add')}}</button>
                        <draggable :swap-threshold="0.1" :disabled="!check_controller()" :animation="250" v-model="items" :ghostClass="'ghost'" :group="get_group()" :empty-insert-threshold="100" handle=".handle" @start="drag = true" @end="end_drag()">
                            <div class="draggable_parent " v-for="(element,index) in items">
                                <div class="dd-handle d-flex flex-row align-items-center">
                                    <div v-on:mousedown="check_md($event, element)" v-on:mouseup="check_mu(element)" v-show="check_controller()">
                                        <i class="fa fa-align-justify handle"></i>
                                    </div>
                                    <div class="pl-3 col-9">
                                        <span>{{element.name}} (ID {{element.id}})</span>
                                    </div>
                                    <div class="ml-auto">
                                        <button @click="show_add_modal(false, index)" data-bs-toggle="modal" data-bs-target="#add_category_modal" class="btn  btn-primary">
                                            <i  class="fa fa-plus"></i>
                                        </button>
                                        <button @click="show_edit_modal(element)" data-bs-toggle="modal" data-bs-target="#edit_category_modal" class="btn btn-success">
                                            <i  class="fa fa-edit "></i>
                                        </button>
                                        <button @click="change_active(element)" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button :class="get_delete_class(element)" @click="show_swal(element, index)" class="btn">
                                            <i   class="fa fa-trash delete  "></i>
                                        </button>
                                        <button @click="show_swal_clear(element, index)" v-if="is_common == 1 && controller_name == 'materials'" class="btn btn-danger">
                                            <i class="fa fa-eraser delete"></i>
                                        </button>
                                    </div>
                                </div>
                                <draggable :animation="250" :ghostClass="'ghost'" class="ml-5" :class="{drop_zone: md == true, drop_zone_hidden: md == false, hide_dz: hide_dz}" v-model="element.children" :group="get_group()" :empty-insert-threshold="100" handle=".handle" @start="drag = true" @end="end_drag()">
                                    <div class="draggable_child " v-for="(item, item_index) in element.children">
                                        <div  class="dd-handle d-flex flex-row align-items-center">
                                            <div v-on:mousedown="md = true" v-on:mouseup="md = false">
                                                <i class="fa fa-align-justify handle"></i>
                                            </div>
                                            <div class="pl-3 col-9">
                                                <div>{{item.name}} (ID {{item.id}})</div>
                                            </div>
                                            <div class="ml-auto ">
                                                <button @click="show_edit_modal(item)" data-bs-toggle="modal" data-bs-target="#edit_category_modal" class="btn btn-success">
                                                    <i class="fa fa-edit "></i>
                                                </button>
                                                <button @click="change_active(item)" class="btn">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button @click="show_swal(item, index, true, item_index)" :class="get_delete_class(item)" class="btn">
                                                    <i class="fa fa-trash delete"></i>
                                                </button>
                                                <button v-if="is_common == 1" @click="show_swal_clear(item, index)" class="btn btn-danger">
                                                    <i class="fa fa-eraser delete"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </draggable>
                                <div v-show="hide_dz" class="dz"></div>
                            </div>
                        </draggable>
                        <button v-show="check_controller()" @click="show_add_modal(true)" data-bs-toggle="modal" data-bs-target="#add_category_modal" class="mt-3 btn btn-w-m btn-primary" type="button">{{lang('add')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="add_category_modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                    <div class="text-center mb-6">
                        <h4 class="modal-title">{{lang('add_category')}}</h4>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                        <div class="col-sm-10"><input v-model="modals.add_category.name" type="text" class="form-control"></div>
                    </div>
                </div>
                <div class="cpl-md-12 text-center">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">{{lang('cancel')}}</button>
                    <button @click="add_item()" type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">{{lang('add')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div id="edit_category_modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" class="btn-close"><span>&times;</span></button>
                    <div class="text-center mb-6">
                        <h4 class="modal-title">{{lang('edit_category')}}</h4>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                        <div class="col-sm-10"><input v-model="modals.edit_category.name" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('description')}}</label>
                        <div class="col-sm-10"><textarea rows="5" v-model="modals.edit_category.description"  class="form-control"></textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('image')}}</label>
                        <div class="col-sm-10">
                            <div class="icon_block">
                                <img @click="$refs.fileman.data_mode = 'images'" data-bs-toggle="modal" data-bs-target="#filemanager" style="max-width: 125px" :src="correct_url(modals.edit_category.image)" alt="">
                                <i @click="$refs.fileman.data_mode = 'images'" data-bs-toggle="modal" data-bs-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                <i v-if="modals.edit_category.image != ''" @click="modals.edit_category.image = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">{{lang('cancel')}}</button>
                        <button @click="update_item()" type="button" class="btn btn-primary">{{lang('save')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="filemanager" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" class="btn-close"><span>&times;</span></button>
                    <div class="text-center mb-6">
                        <h5 class="modal-title">Выбрать файл</h5>
                    </div>
                    <filemanager ref="fileman" @select_file="sel_file($event)"></filemanager>
                </div>
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($set_id)):?>
        <input id="set_id" ref="set" value="<?php echo $set_id ?>" type="hidden">
    <?php endif;?>

    <?php if(isset($common)):?>
        <input id="is_common" ref="set" value="<?php echo $common ?>" type="hidden">
    <?php endif;?>

<!--    <input class="cat_controller" ref="controller" value="--><?php //echo $controller ?><!--" type="hidden">-->
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