<div v-cloak id="sub_form">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <?php if(isset($common) && $common == 1):?>
                <h2 style="color: red">{{lang('categories')}} <span v-if="contr_names[controller_name]">{{contr_names[controller_name]}}</span> (ОБЩАЯ БАЗА)</h2>
            <?php else:?>
                <h2>{{lang('categories')}}</h2>
            <?php endif;?>

        </div>
        <div class="col-lg-2">

        </div>
    </div>


    <div class="wrapper wrapper-content  animated fadeInRight">

        <div v-if="data_ready" class="row">
            <div class="col-lg-12">
                <div class="ibox">

                    <div class="ibox-content">

                        <a v-show="controller == 'module_sets'" class="btn btn-outline btn-w-m btn-info" style="position: absolute; right: 35px" href="<?php echo site_url('module_sets/sets_index/') ?>" role="button"><?php echo $lang_arr['back_to_modules_sets']?></a>

                        <button v-show="check_controller()" @click="addHandler" class="mb-3 btn btn-sm btn-w-m btn-primary btn-outline" type="button">{{lang('add')}}</button>

                        <button @click="expandAll" class="mb-3 btn btn-sm btn-w-m btn-default btn-outline">Развернуть все</button>
                        <button @click="collapseAll" class="mb-3 btn btn-sm btn-w-m btn-default btn-outline">Свернуть все</button>
                        <button v-if="false" @click="removeAll" class="mb-3 btn btn-sm btn-w-m btn-danger btn-outline">Удалить все</button>

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

                                    <i @click="addHandler" :disabled="item.preventChildren" class="fa fa-plus btn btn-outline btn-primary"></i>
                                    <i @click="editHandler" class="fa fa-edit btn btn-outline btn-success"></i>
                                    <i @click="changeActiveHandler" :class="(item.active == 1) ? ['btn-primary'] : ['fa-eye-slash', 'btn-default']" class="fa fa-eye btn btn-outline"></i>
                                    <i @click="show_swal_n" :class="item.isLeaf ? ['btn-danger'] : ['btn-default', 'pointer-none', 'opacity-05']" class="fa fa-trash delete btn btn-outline"></i>
                                    <i v-if="is_common == 1 && controller_name == 'materials'" @click="show_swal_clear" class="fa fa-eraser delete btn btn-danger btn-outline"></i>
                                </div>
                            </template>
                        </pp-tree>

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
                        <h4 class="modal-title">{{ modals.edit_category.isNew ? lang('add_category') : lang('edit_category') }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                            <div class="col-sm-10"><input v-model="modals.edit_category.name" type="text" class="form-control"></div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Родительская категория</label>
                            <div class="col-sm-10">
                                <pp_category
                                    @e_update="modals.edit_category.parent = $event"
                                    :options="pp_category_options"
                                    :selected="modals.edit_category.parent"
                                    :controller="controller_name">
                                </pp_category>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('description')}}</label>
                            <div class="col-sm-10"><textarea rows="5" v-model="modals.edit_category.description" class="form-control"></textarea></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button @click="modals.edit_category.show = false" type="button" class="btn btn-white">{{lang('cancel')}}</button>
                        <button v-if="modals.edit_category.isNew" @click="addItem" type="button" class="btn btn-primary">{{ lang('add') }}</button>
                        <button v-else @click="updateItem" type="button" class="btn btn-primary">{{ lang('save') }}</button>
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

<link rel="stylesheet" href="/common_assets/fonts/icons/new/style.css?<?php echo md5(date('m-d-Y-His A e'));?>">

<link rel="stylesheet" href="/common_assets/libs/vue3/vue-select.css">
<link href="/common_assets/libs/vue3/pp-tree.css" rel="stylesheet">

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

<script src="/common_assets/libs/vue3/vue.global.js"></script>
<script src="/common_assets/libs/vue3/vueuse.shared.iife.min.js"></script>
<script src="/common_assets/libs/vue3/vueuse.core.iife.min.js"></script>
<script src="/common_assets/libs/vue3/vue-slicksort.umd.js"></script>
<script src="/common_assets/libs/vue3/vue-select.umd.js"></script>
<script src="/common_assets/libs/vue3/pp-tree.js"></script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/category_picker_v.php'); ?>

<script src="/common_assets/admin_js/vue/catalog/categories_new_v.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
