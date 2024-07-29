<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['accessories'] ?></h2>
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
    <div class="col-lg-2 pt-4">

    </div>
</div>

<div id="app">
    <div v-cloak class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#acc_list"><?php echo $lang_arr['accessories_list'] ?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#acc_types"><?php echo $lang_arr['accessories_auto_add'] ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="acc_list" class="tab-pane active">
                            <div class="panel-body">

                                <div class="row align-items-center">
                                    <div class="col-sm-6 d-flex align-items-center">
                                        <button @click="clear_item" type="button" data-toggle="modal" data-target="#acc_modal" class="mr-1 btn-sm btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                        <div class="btn-group btn-group-sm mr-1">
                                            <button data-toggle="dropdown" class="btn btn-success btn-outline dropdown-toggle" aria-expanded="false"> <?php echo $lang_arr['import_export']?></button>
                                            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 33px; left: 0px; will-change: top, left;">
                                                <li>
                                                    <a @click="export_csv"  class="dropdown-item" href="#">
                                                        <?php echo $lang_arr['export_csv']?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a  data-toggle="modal" data-target="#csv_modal" class="dropdown-item" href="#">
                                                        <?php echo $lang_arr['import_csv']?>
                                                    </a>
                                                </li>
                                                <li class="dropdown-divider"></li>
                                                <li>
                                                    <a @click="export_xls" class="dropdown-item" href="#">
                                                        <?php echo $lang_arr['export_xls']?>
                                                    </a>
                                                </li>

                                                <li >
                                                    <a @click="$refs.imp.click()" class="dropdown-item" href="#">
                                                        <?php echo $lang_arr['import_xls']?>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>

                                        <button style="display:none;" @click="export_csv" class="mr-1 btn btn-primary btn-xs" type="button"><?php echo $lang_arr['export_csv']?></button>
                                        <button style="display:none;" id="import_csv" data-toggle="modal" data-target="#csv_modal" class="mr-1 btn btn-primary btn-sm" type="button"><?php echo $lang_arr['import_csv']?></button>
                                        <button id="clear_db" class="mr-1 btn btn-danger btn-sm " type="button"><?php echo $lang_arr['clear_db']?></button>





                                        <input class="hidden"  @change="import_xls()" ref="imp" type="file" accept=".xlsx">
                                    </div>
                                    <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                        <pagination_component v-on:change-page="change_page($event)" :pages="pages_count"></pagination_component>
                                    </div>
                                </div>
                                <div class="row pt-3">

                                    <div class="col-sm-4">
                                        <form v-on:submit.prevent="do_search()">
                                            <div class="form-group">
                                                <label><?php echo $lang_arr['search'] ?></label>
                                                <div class="input-group">
                                                    <input v-model="filter.search" type="text" class="form-control" style="height: 30px">
                                                    <span class="input-group-append" style="font-size: 11px">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-search"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-5">

                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="per_page"><?php echo $lang_arr['page_items_count']?></label>
                                            <v-select
                                                    :clearable="false"
                                                    :value="filter.per_page"
                                                    :options="per_page"
                                                    v-model="filter.per_page"
                                                    @input="filter_per_page($event)"
                                            >
                                            </v-select>
                                        </div>
                                    </div>
                                </div>



                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <td><?php echo $lang_arr['id_short']?></td>
                                        <td><?php echo $lang_arr['code']?></td>
                                        <td><?php echo $lang_arr['name']?></td>
                                        <td><?php echo $lang_arr['category']?></td>
                                        <td><?php echo $lang_arr['price']?></td>
                                        <td><?php echo $lang_arr['type']?></td>
                                        <td><?php echo $lang_arr['is_visible']?></td>
                                        <td><?php echo $lang_arr['actions']?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in items">
                                        <td>{{item.id}}</td>
                                        <td>{{item.code}}</td>
                                        <td>{{item.name}}</td>
                                        <td>{{item.category}}</td>
                                        <td>{{item.price}}</td>
                                        <td>{{get_type_name(item.type)}}</td>
                                        <td class="text-center">
                                            <i @click="change_active(item)" :class="get_eye_class(item)" class="fa fa-eye btn btn-outline"></i>
                                        </td>
                                        <td style="width: 100px">
                                            <i data-toggle="modal" data-target="#acc_modal" :title="lang('edit')" @click="edit_item(item)" class="fa fa-edit btn btn-outline btn-success"></i>
                                            <i @click="remove_item(item)" :title="lang('delete')"  class="fa fa-trash btn btn-outline btn-danger"></i>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="row align-items-center">
                                    <div class="col-sm-6 d-flex align-items-center">
                                        <button @click="clear_item" type="button" data-toggle="modal" data-target="#acc_modal" class="btn-sm btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                    </div>
                                    <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                        <pagination_component v-on:change-page="change_page($event)" :pages="pages_count"></pagination_component>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="acc_types" class="tab-pane">
                            <div class="panel-body">



                                <button @click="clear_type" type="button" data-toggle="modal" data-target="#acc_type_modal" class="form-group btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>

                                <table class="table table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <td><?php echo $lang_arr['id_short']?></td>
                                        <td><?php echo $lang_arr['name']?></td>
                                        <td><?php echo $lang_arr['key']?></td>
                                        <td><?php echo $lang_arr['default']?></td>
                                        <td><?php echo $lang_arr['auto_calc']?></td>
                                        <td><?php echo $lang_arr['actions']?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in types">
                                        <td>{{item.id}}</td>
                                        <td>
                                            <span v-if="item.id > 5">{{item.name}}</span>
                                            <span v-else>{{get_type_name(item.key)}}</span>
                                        </td>
                                        <td>{{item.key}}</td>
                                        <td>
                                            {{item.default}}
                                            <button style="float: right" @click="get_items_by_type(item)" data-toggle="modal" data-target="#default_acc" type="button" class="btn-sm btn btn-outline btn-success">{{lang('pick')}}</button>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input @change="change_auto(item)" v-bind:true-value="1" v-bind:false-value="0" v-model="item.auto" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td style="width: 100px">
                                            <span v-if="item.id > 5">
                                            <i data-toggle="modal" data-target="#acc_type_modal" :title="lang('edit')" @click="edit_type_item(item)" class="fa fa-edit btn btn-outline btn-success"></i>
                                            <i @click="remove_type_item(item)" :title="lang('delete')"  class="fa fa-trash btn btn-outline btn-danger"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <button @click="clear_type" type="button" data-toggle="modal" data-target="#acc_type_modal" class="form-group btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
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
                        <button @click="save_data()" class="btn btn-primary" type="button"><?php echo $lang_arr['save'] ?></button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="modal inmodal" id="acc_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                    <h5 class="modal-title"><?php echo $lang_arr['add'] ?></h5>

                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('code')}}</label>
                        <div class="col-sm-10"><input v-model="item.code" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                        <div class="col-sm-10"><input v-model="item.name" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('category')}}</label>
                        <div class="col-sm-10"><input v-model="item.category" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('price')}}</label>
                        <div class="col-sm-10"><input v-model="item.price" min="0" step="0.01" type="number" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('type')}}</label>
                        <div class="col-sm-10">
                            <select v-model="item.type" class="form-control">
                                <option :value="'common'">{{lang('common')}}</option>
                                <option v-for="opt in types" :value="opt.key">{{get_type_name(opt.key)}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('tags')}}</label>
                        <div class="col-sm-10"><input v-model="item.tags" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('images')}}</label>
                        <div class="col-sm-10"><textarea v-model="item.images" class="form-control"></textarea></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('description')}}</label>
                        <div class="col-sm-10"><textarea v-model="item.description" class="form-control"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                    <button @click="add_item()" type="button" class="btn btn-primary" data-dismiss="modal" ><?php echo $lang_arr['save']?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="acc_type_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                    <h5 class="modal-title"><?php echo $lang_arr['add'] ?></h5>

                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                        <div class="col-sm-10"><input v-model="type_item.name" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('key')}}</label>
                        <div class="col-sm-10"><input v-model="type_item.key" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{lang('auto_calc')}}</label>
                        <div class="col-sm-10">
                            <label class="switch">
                                <input  v-bind:true-value="1" v-bind:false-value="0" v-model="type_item.auto" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                    <button @click="add_type_item()" type="submit" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="default_acc" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                    <h5 class="modal-title"><?php echo $lang_arr['add'] ?></h5>

                </div>
                <div class="modal-body">
                    <table v-if="items_by_type.length" class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <td><?php echo $lang_arr['id_short']?></td>
                            <td><?php echo $lang_arr['code']?></td>
                            <td><?php echo $lang_arr['name']?></td>
                            <td><?php echo $lang_arr['category']?></td>
                            <td><?php echo $lang_arr['price']?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr data-dismiss="modal" style="cursor: pointer" @click="select_default_type_item(item)" v-for="item in items_by_type">
                            <td>{{item.id}}</td>
                            <td>{{item.code}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.category}}</td>
                            <td>{{item.price}}</td>
                        </tr>
                        </tbody>
                    </table>
                    <p v-else>{{lang('no_type_accessories')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="csv_modal">
        <form id="csv_import">

            <div class="modal-dialog">
                <div class="modal-content animated ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <i class="fa fa-laptop modal-icon"></i>
                        <h4 class="modal-title"><?php echo $lang_arr['import_csv']?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>
                                <?php echo $lang_arr['choose_file'] ?></label>
                            <input id="modal_csv_file" name="modal_csv_file" type="file" accept=".csv" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="no_check_code">
                                Не проверять артикул
                            </label>
                            <input id="no_check_code" type="checkbox" name="no_check_code">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                        <button type="submit" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>


</div>
<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/exceljs.min.js"></script>
<script src="/common_assets/admin_js/vue/pagination.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/accessories_new.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>