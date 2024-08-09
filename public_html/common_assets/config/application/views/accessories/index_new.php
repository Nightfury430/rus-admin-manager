<div id="app">
    <div v-cloak class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="nav-align-top nav-tabs-shadow mb-6">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#acc_list"
                            aria-controls="acc_list"
                            aria-selected="true">
                                <?php echo $lang_arr['accessories_list'] ?>
                            </button>
                        </li>
                        <li>
                            <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#acc_types"
                            aria-controls="acc_types"
                            aria-selected="true">
                                <?php echo $lang_arr['accessories_list'] ?>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="acc_list" class="tab-pane fade show active" role="tabpanel">
                            <div class="row align-items-center">
                                <div class="col-sm-6 d-flex align-items-center">
                                    <button @click="clear_item" type="button" data-bs-toggle="modal" data-bs-target="#acc_modal" class="mr-1 btn-sm btn btn-w-m btn-primary m-3"><?php echo $lang_arr['add'] ?></button>
                                    <div class="btn-group btn-group-sm mr-1 m-3">
                                        <button data-bs-toggle="dropdown" class="btn btn-success dropdown-toggle" aria-expanded="false"> <?php echo $lang_arr['import_export']?></button>
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

                                    <!-- <button style="display:none;" @click="export_csv" class="mr-1 btn btn-primary btn-xs" type="button"><?php echo $lang_arr['export_csv']?></button> -->
                                    <!-- <button style="display:none;" id="import_csv" data-toggle="modal" data-target="#csv_modal" class="mr-1 btn btn-primary btn-sm" type="button"><?php echo $lang_arr['import_csv']?></button> -->
                                    <button id="clear_db" class="mr-1 btn btn-danger btn-sm m-3" type="button"><?php echo $lang_arr['clear_db']?></button>
                                    <input class="hidden m-3"  @change="import_xls()" ref="imp" type="file" accept=".xlsx">
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
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <td><?php echo $lang_arr['id_short']?></td>
                                        <td><?php echo $lang_arr['code']?></td>
                                        <td><?php echo $lang_arr['name']?></td>
                                        <td><?php echo $lang_arr['category']?></td>
                                        <td><?php echo $lang_arr['price']?></td>
                                        <td><?php echo $lang_arr['type']?></td>
                                        <td><?php echo $lang_arr['is_visible']?></td>
                                        <td style="width:200px;"><?php echo $lang_arr['actions']?></td>
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
                                        <button @click="change_active(item)" class="btn btn-primary"  >
                                            <i :class="get_eye_class(item)"></i>
                                        </button>
                                    </td>
                                    <td style="width: 100px">
                                        <button class="btn btn-success" :title="lang('edit')" @click="edit_item(item)" data-bs-toggle="modal" data-bs-target="#acc_modal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger" @click="remove_item(item)" :title="lang('delete')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="row align-items-center">
                                <div class="col-sm-6 d-flex align-items-center">
                                    <button @click="clear_item" type="button" data-toggle="modal" data-target="#acc_modal" class="btn-sm btn btn-w-m btn-primary  "><?php echo $lang_arr['add'] ?></button>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                    <pagination_component v-on:change-page="change_page($event)" :pages="pages_count"></pagination_component>
                                </div>
                            </div>
                        </div>
                        <div id="acc_types" class="tab-pane fade" role="tabpanel">
                            <button @click="clear_type" type="button" data-bs-toggle="modal" data-bs-target="#acc_type_modal" 
                            class="form-group btn btn-w-m btn-primary mb-3 "><?php echo $lang_arr['add'] ?></button>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td><?php echo $lang_arr['id_short']?></td>
                                    <td><?php echo $lang_arr['name']?></td>
                                    <td><?php echo $lang_arr['key']?></td>
                                    <td><?php echo $lang_arr['default']?></td>
                                    <td><?php echo $lang_arr['auto_calc']?></td>
                                    <td style="width : 200px"><?php echo $lang_arr['actions']?></td>
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
                                        <button style="float: right" @click="get_items_by_type(item)" data-toggle="modal" data-target="#default_acc" type="button" class="btn-sm btn   btn-success">{{lang('pick')}}</button>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input @change="change_auto(item)" v-bind:true-value="1" v-bind:false-value="0" v-model="item.auto" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td style="width: 100px">
                                        <span v-if="item.id > 5">
                                            <button :title="lang('edit')" @click="edit_type_item(item)" class="btn btn-success">
                                                <i data-bs-toggle="modal" data-bs-target="#acc_type_modal"  class="fa fa-edit"></i>
                                            </button>
                                            <button @click="remove_type_item(item)"class="btn btn-danger" :title="lang('delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <button @click="clear_type" type="button" data-bs-toggle="modal" data-bs-target="#acc_type_modal" class="form-group btn btn-w-m btn-primary  "><?php echo $lang_arr['add'] ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <button @click="save_data()" class="btn btn-primary" type="button"><?php echo $lang_arr['save'] ?></button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="acc_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only"><?php echo $lang_arr['ok'] ?></span>
                    </button>
                    <div class="text-center mb-6">
                        <h5 class="modal-title"><?php echo $lang_arr['add'] ?></h5>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('code')}}</label>
                        <div class="col-sm-10"><input v-model="item.code" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                        <div class="col-sm-10"><input v-model="item.name" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('category')}}</label>
                        <div class="col-sm-10"><input v-model="item.category" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('price')}}</label>
                        <div class="col-sm-10"><input v-model="item.price" min="0" step="0.01" type="number" class="form-control"></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('type')}}</label>
                        <div class="col-sm-10">
                            <select v-model="item.type" class="form-control">
                                <option :value="'common'">{{lang('common')}}</option>
                                <option v-for="opt in types" :value="opt.key">{{get_type_name(opt.key)}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('tags')}}</label>
                        <div class="col-sm-10"><input v-model="item.tags" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('images')}}</label>
                        <div class="col-sm-10"><textarea v-model="item.images" class="form-control"></textarea></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('description')}}</label>
                        <div class="col-sm-10"><textarea v-model="item.description" class="form-control"></textarea></div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-white" aria-label="Close" data-bs-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                        <button @click="add_item()" type="button" class="btn btn-primary" data-bs-dismiss="modal" ><?php echo $lang_arr['save']?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="acc_type_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span>
                    </button>
                    <div class="text-center mb-6">
                        <h5 class="modal-title"><?php echo $lang_arr['add'] ?></h5>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                        <div class="col-sm-10"><input v-model="type_item.name" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('key')}}</label>
                        <div class="col-sm-10"><input v-model="type_item.key" type="text" class="form-control"></div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-sm-2 col-form-label">{{lang('auto_calc')}}</label>
                        <div class="col-sm-10">
                            <label class="switch">
                                <input  v-bind:true-value="1" v-bind:false-value="0" v-model="type_item.auto" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-white" aria-label="Close" data-bs-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                        <button @click="add_type_item()" type="submit" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="default_acc" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                    <h5 class="modal-title"><?php echo $lang_arr['add'] ?></h5>
                </div>
                <div class="modal-body">
                    <table v-if="items_by_type.length" class="table">
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
    <div class="modal fade" id="csv_modal">
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
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                            <button type="submit" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>