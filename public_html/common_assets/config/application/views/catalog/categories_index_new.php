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

                        <button v-show="check_controller()" @click="show_add_modal_n(null)" class="mb-3 btn btn-sm btn-w-m btn-primary btn-outline" type="button">{{lang('add')}}</button>

                        <sl-vue-tree
                                v-model="items"
                                ref="sl_tree"
                                :allow-multiselect="false"
                                :edge-size="10"
                                @select="nodeSelected"
                                @nodeclick="node_selected"
                                @drop="nodeDropped"
                                @nodecontextmenu="context_menu"
                        >

                            <template slot="title" slot-scope="{ node }">
                                <span :class="{tree_not_visible: node.data.visible == 0}" class="tree_node_title">
                                    <i class="icon icon_menu"></i> {{ node.title }} <span>(ID:{{node.data.id}})</span>
                                </span>
                            </template>


                            <template slot="toggle" slot-scope="{ node }">
                                <span v-if="!node.isLeaf && node.children.length">
                                    <i v-if="node.isExpanded" class="fa fa-chevron-down"></i>
                                    <i v-if="!node.isExpanded" class="fa fa-chevron-right"></i>
                                </span>
                                <span v-else></span>
                            </template>


                            <template slot="sidebar" slot-scope="{ node }">
                                <div class="node_menu">
                                    <i @click="show_add_modal_n(node)" class="fa fa-plus btn  btn-outline btn-primary"></i>
                                    <i @click="show_edit_modal_n(node)" class="fa fa-edit btn  btn-outline btn-success"></i>
                                    <i @click="change_active_n(node)" :class="get_eye_class_n(node)" class="fa fa-eye btn  btn-outline"></i>
                                    <i @click="show_swal_n(node)" :class="get_delete_class(node)" class="fa fa-trash delete btn  btn-outline"></i>
                                    <i v-if="is_common == 1 && controller_name == 'materials'" @click="show_swal_clear(node)"  class="fa fa-eraser delete btn  btn-danger btn-outline"></i>
                                </div>

                            </template>


                            <template slot="draginfo">
                                {{selectedNodesTitle}}
                            </template>

                        </sl-vue-tree>



                        <button v-show="check_controller()" @click="show_add_modal_n(null)" class="mt-3 btn btn-sm btn-w-m btn-primary btn-outline" type="button">{{lang('add')}}</button>

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
                        <h4 class="modal-title">{{lang('add_category')}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                            <div class="col-sm-10"><input v-model="modals.add_category.name" type="text" class="form-control"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button @click="modals.add_category.show = false" type="button" class="btn btn-white">{{lang('cancel')}}</button>
                        <button @click="add_item_n()" type="button" class="btn btn-primary">{{lang('add')}}</button>
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
                        <h4 class="modal-title">{{lang('edit_category')}}</h4>
                    </div>
                    <div class="modal-body">
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
                                    <img @click="$refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" style="max-width: 125px" :src="correct_url(modals.edit_category.image)" alt="">
                                    <i @click="$refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                    <i v-if="modals.edit_category.image != ''" @click="modals.edit_category.image = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>


                    </div>



                    <div class="modal-footer">
                        <button @click="modals.edit_category.show = false" type="button" class="btn btn-white">{{lang('cancel')}}</button>
                        <button @click="update_item_n()" type="button" class="btn btn-primary">{{lang('save')}}</button>
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

<link rel="stylesheet" href="/common_assets/libs/vue/vue_tree/sl-vue-tree-dark.css?<?php echo md5(date('m-d-Y-His A e'));?>">
<link rel="stylesheet" href="/common_assets/fonts/icons/new/style.css?<?php echo md5(date('m-d-Y-His A e'));?>">
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

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">

<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_tree/sl-vue-tree.js"></script>

<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>

<script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>


<script src="/common_assets/admin_js/vue/catalog/categories_new.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>