<div v-cloak id="sub_form">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div v-if="data_ready" class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a v-show="controller == 'module_sets'" class="btn btn-success waves-effect waves-light" style="position: absolute; right: 35px" href="<?php echo site_url('module_sets/sets_index/') ?>" role="button"><?php echo $lang_arr['back_to_modules_sets']?></a>
                        <button v-show="check_controller()" data-bs-toggle="modal" data-bs-target="#categoryModal" @click="addHandler" class="mb-3 btn btn-primary waves-effect waves-light" type="button">{{lang('add')}}</button>
                        <button @click="expandAll" class="mb-3 btn btn-sm btn-w-m btn-default btn-outline">Развернуть все</button>
                        <button @click="collapseAll" class="mb-3 btn btn-sm btn-w-m btn-default btn-outline">Свернуть все</button>
                        <button v-if="false" @click="removeAll" class="mb-3 btn btn-success waves-effect waves-light">Удалить все</button>

                        <pp-tree ref="tree"
                            :nodes="nodes"
                            :max-height="maxHeight"
                            auto-max-height
                            @updated="updatedEventHandler">

                            <template v-slot:top="{ api }">
                                <tempalte></tempalte>
                            </template>

                            <template v-slot:item-title="{ item }">
                                <span>{{ item.title }}</span>
                            </template>

                            <template v-slot:item-buttons="{ item }">
                                <div class="pp-tree-buttons">
                                    <span>(ID:{{ item.id }})</span>
                                    <button class="btn btn-icon btn-label-linkedin waves-effect" data-bs-toggle="modal" data-bs-target="#categoryModal" style="margin-right : 0.5rem" @click="addHandler"><i :disabled="item.preventChildren" class="fa fa-plus "></i></button>
                                    <button @click="editHandler" class="btn btn-icon btn-label-github waves-effect" data-bs-toggle="modal" data-bs-target="#categoryModal" style="margin-right : 0.5rem"><i class="fa-solid fa-pen-to-square"></i></button>
                                    <button :class="(item.active == 1) ? ['btn-primary'] : ['fa-eye-slash', 'btn-default']" class="btn btn-icon" style="margin-right : 0.5rem" @click="changeActiveHandler"><i  class="fa fa-eye "></i></button>
                                    <button class="btn btn-icon" :class="item.isLeaf ? ['btn-danger'] : ['btn-default', 'pointer-none', 'opacity-05']" style="margin-right : 0.5rem" @click="show_swal_n" ><i class="fa fa-trash delete "></i></button>
                                    <button v-if="is_common == 1 && controller_name == 'materials'" class="btn btn-icon" @click="show_swal_clear" style="margin-right : 0.5rem"><i class="fa fa-eraser delete "></i></button>
                                </div>
                            </template>
                        </pp-tree>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="categoryModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                    <div class="text-center mb-6">
                        <h4 class="modal-title">{{ modals.edit_category.isNew ? lang('add_category') : lang('edit_category') }}</h4>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label class="form-label">{{lang('name')}}</label>
                        <div class="col-sm-12"><input v-model="modals.edit_category.name" type="text" class="form-control"></div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label class="form-label">Родительская категория</label>
                        <div class="col-sm-12">
                            <pp_category
                                @e_update="modals.edit_category.parent = $event"
                                :options="pp_category_options"
                                :selected="modals.edit_category.parent"
                                :controller="controller_name">
                            </pp_category>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 mb-3">
                        <label class="form-label">{{lang('description')}}</label>
                        <div class="col-sm-12"><textarea rows="5" v-model="modals.edit_category.description" class="form-control"></textarea></div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="reset" class="btn btn-label-secondary" style="margin-right:2rem;" data-bs-dismiss="modal" @click="clearField" aria-label="Close" >{{lang('cancel')}}</button>
                        <button v-if="modals.edit_category.isNew" @click="addItem" type="button" class="btn btn-primary me-3">{{ lang('add') }}</button>
                        <button v-else @click="updateItem" type="button" class="btn btn-primary me-3">{{ lang('save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="filemanager" tabindex="-1" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                    <h5 class="modal-title">Выбрать файл</h5>
                </div>
                <div class="modal-body">
                    <filemanager ref="fileman" @select_file="sel_file($event)"></filemanager>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
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

    .tree_node_title .icon{
        font-size: 20px;
        margin-top: -4px;
        width: 14px;
        display: inline-block;
        vertical-align: middle;
        cursor: pointer;
    }

    .sl-vue-tree-gap{
        width: 30px;
    }

    .sl-vue-tree-node-item{
        padding-top: 2px;
        padding-bottom: 2px;
    }

    .sl-vue-tree.sl-vue-tree-root{
        border: none;
    }

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

<style>
    .pp-tree,
    .pp-tree-helper {
        --pp-tree-background: #fff;
        --pp-tree-selected-background: #f3f3f4;
    }

    .pp-tree-buttons>span {
        display: inline-block;
        margin-left: 30px;
        margin-right: 5px;
        vertical-align: middle;
    }

    .pp-tree-buttons>i {
        margin-left: 5px;
    }
</style>