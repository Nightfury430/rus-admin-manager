<div id="bp_app">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <?php if (isset($id)): ?>
                <h2><?php echo $lang_arr['facade_edit_heading'] ?></h2>
            <?php else: ?>
                <h2><?php echo $lang_arr['facade_add'] ?></h2>
            <?php endif; ?>
        </div>
        <div class="col-lg-2">

        </div>
    </div>

    <form @submit.prevent="" id="sub_form" action="<?php echo site_url('catalog/add_item_ajax_no_files_common/facades_systems/') ?><?php if(isset($id) && $id != 0) echo $id?>">
        <input id="form_success_url" value="<?php echo site_url('catalog/items_common/facades_systems') ?>" type="hidden">


        <?php if (isset($id)): ?>
            <input id="item_id" value="<?php echo $id ?>" type="hidden">
        <?php endif; ?>

        <div class="wrapper wrapper-content  animated fadeInRight">

            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger error_msg" style="display:none"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#models_tab"><?php echo $lang_arr['models'] ?></a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#additional_tab">Дополнительные</a></li>
                            <li><a @click="resize_preview" class="nav-link" data-toggle="tab" href="#materials_tab">Декоры</a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#cokol_tab">Погонаж</a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#modules_tab">Модули</a></li>
                            <li><a @click="get_json" class="nav-link" data-toggle="tab" href="#json_tab">JSON</a></li>

                        </ul>
                        <div class="tab-content">

                            <div id="basic_params_tab" class="tab-pane active">
                                <div class="panel-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?>*</label>
                                        <div class="col-sm-10">
                                            <input v-model="item.name" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order'] ?></label>
                                        <div class="col-sm-10">
                                            <input v-model="item.order" type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                                        <div class="col-sm-10">
                                            <v-select
                                                    :clearable="false"
                                                    :value="item.category"
                                                    label="name"
                                                    :options="$options.categories.ordered"
                                                    :reduce="category => category.id"
                                                    v-model="item.category"
                                                    :key="item.category"
                                            >
                                                <template v-slot:selected-option="option">
                                                    <span v-if="option.parent != 0">{{$options.categories.hash[option.parent].name}} / &nbsp;</span>{{ option.name }}
                                                </template>
                                                <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{$options.categories.hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                                                </template>
                                            </v-select>
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
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                                        <div class="col-sm-5">

                                            <div class="icon_block">
                                                <img @click="show_fm(item, 'icon', 'images')" style="max-width: 78px" :src="get_icon_src_new(item.icon)" alt="">
                                                <i @click="show_fm(item, 'icon', 'images')" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i @click="item.icon = ''" class="fa fa-trash delete_file"></i>
                                            </div>

<!--                                            <div class="icon_block">-->
<!--                                                <img @click="$refs.icon_file.click()" style="max-width: 78px" :src="get_icon_src(icon_file)" alt="">-->
<!--                                                <i @click="$refs.icon_file.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>-->
<!--                                                <i v-if="item.icon != '' || icon_file != ''" @click="icon_file = '';$refs.icon_file.value = ''" class="fa fa-trash delete_file"></i>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="hidden">-->
<!--                                                <input type="file" ref="icon_file" accept="image/jpeg,image/png,image/gif" @change="process_icon_file($event)">-->
<!--                                            </div>-->

                                        </div>
                                        <div class="col-sm-5">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div id="models_tab" class="tab-pane">
                                <div class="panel-body models_list">
                                    <div  class="tabs-container d-flex flex-column">
                                        <div class="tabs-left">

                                            <ul class="nav nav-tabs tabs-inner">
                                                <li @click="active_tab = name" v-for="(item, name) in item.facades.types">
                                                    <a :title="item.name" v-bind:class="{ active: active_tab == name }" class="nav-link">{{item.name}}</a>
                                                </li>
                                                <li>
                                                    <div class="btn-group dropright">
                                                        <button @click="add_type()" type="button" class="btn btn-primary  btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                        <button type="button" class="btn btn-primary  btn-outline dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a @click="$refs.excel_file_multi.click()"  class="dropdown-item" href="#">Добавить из XLSX</a>
                                                        </div>
                                                    </div>



                                                    <div class="hidden">
                                                        <input type="file" ref="excel_file_multi" accept=".xlsx" @change="process_excel_file_multi($event)">
                                                    </div>
                                                </li>
                                            </ul>

                                            <div  class="tab-content">

                                                <div v-bind:class="{ active: active_tab == name }" v-if="active_tab == name" v-for="(item, name) in item.facades.types" class="tab-pane">
                                                    <div class="panel-body facades_panel_body container-fluid">

<!--                                                        <div class="btn-group d-flex w-100 mb-2" role="group">-->
<!--                                                            <button  class="w-100 btn btn-success btn-xs" type="button">Параметры</button>-->
<!--                                                            <button  class="w-100 btn btn-success btn-xs btn-outline" type="button">Размеры и цены</button>-->
<!--                                                        </div>-->


                                                        <div  class="row form-group">
                                                            <div class="col-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                                                    <div class="col-8">
                                                                        <input v-model="item.name" class="form-control" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <label class="col-sm-4 col-form-label">Можно выбирать</label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="true" v-bind:false-value="false" v-model="item.picker" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <label class="col-sm-4 col-form-label">Погонаж</label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="true" v-bind:false-value="false" v-model="item.vheight" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['is_radius'] ?></label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="true" v-bind:false-value="false" v-model="item.radius" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['compatibility']?></label>
                                                                    <div class="col-8">
                                                                        <select v-bind:value="computed_compat_type(name)" @change="set_compat_type($event, name)" class="form-control">
                                                                            <option value=""><?php echo $lang_arr['no']?></option>
                                                                            <option value="full"><?php echo $lang_arr['facade_full']?></option>
                                                                            <option value="full1"><?php echo $lang_arr['facade_full']?> 1</option>
                                                                            <option value="full2"><?php echo $lang_arr['facade_full']?> 2</option>
                                                                            <option value="full3"><?php echo $lang_arr['facade_full']?> 3</option>
                                                                            <option value="window"><?php echo $lang_arr['facade_window']?></option>
                                                                            <option value="window1"><?php echo $lang_arr['facade_window']?> 1</option>
                                                                            <option value="window2"><?php echo $lang_arr['facade_window']?> 2</option>
                                                                            <option value="window3"><?php echo $lang_arr['facade_window']?> 3</option>
                                                                            <option value="frame"><?php echo $lang_arr['facade_frame']?></option>
                                                                            <option value="frame1"><?php echo $lang_arr['facade_frame']?> 1</option>
                                                                            <option value="frame2"><?php echo $lang_arr['facade_frame']?> 2</option>
                                                                            <option value="frame3"><?php echo $lang_arr['facade_frame']?> 3</option>
                                                                            <option value="decor">Декоративный</option>
                                                                            <option value="decor1">Декоративный 1</option>
                                                                            <option value="decor2">Декоративный 2</option>
                                                                            <option value="decor3">Декоративный 3</option>
                                                                            <option value="decor4">Декоративный 4</option>
                                                                            <option value="decor5">Декоративный 5</option>
                                                                            <option value="decor6">Декоративный 6</option>
                                                                            <option value="decor7">Декоративный 7</option>
                                                                            <option value="decor8">Декоративный 8</option>
                                                                            <option value="decor9">Декоративный 9</option>
                                                                            <option value="decor10">Декоративный 10</option>
                                                                            <option value="decor11">Декоративный 11</option>
                                                                            <option value="decor12">Декоративный 12</option>
                                                                            <option value="decor13">Декоративный 13</option>
                                                                            <option value="decor14">Декоративный 14</option>
                                                                            <option value="decor15">Декоративный 15</option>
                                                                            <option value="decor16">Декоративный 16</option>
                                                                            <option value="decor17">Декоративный 17</option>
                                                                            <option value="decor18">Декоративный 18</option>
                                                                            <option value="decor19">Декоративный 19</option>
                                                                            <option value="decor20">Декоративный 20</option>
                                                                            <option value="radius"><?php echo $lang_arr['facade_radius_full']?></option>
                                                                            <option value="radius1"><?php echo $lang_arr['facade_radius_full']?> 1</option>
                                                                            <option value="radius2"><?php echo $lang_arr['facade_radius_full']?> 2</option>
                                                                            <option value="radius3"><?php echo $lang_arr['facade_radius_full']?> 3</option>
                                                                            <option value="radius4"><?php echo $lang_arr['facade_radius_full']?> 4</option>
                                                                            <option value="radius_window"><?php echo $lang_arr['facade_radius_window']?></option>
                                                                            <option value="radius_window1"><?php echo $lang_arr['facade_radius_window']?> 1</option>
                                                                            <option value="radius_window2"><?php echo $lang_arr['facade_radius_window']?> 2</option>
                                                                            <option value="radius_window3"><?php echo $lang_arr['facade_radius_window']?> 3</option>
                                                                            <option value="radius_window4"><?php echo $lang_arr['facade_radius_window']?> 4</option>
                                                                        </select>




                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-4">
                                                                <div>


                                                                    <div class="icon_block">
                                                                        <img @click="show_fm(item, 'icon', 'images')" style="max-width: 78px" :src="get_icon_src_new(item.icon)" alt="">
                                                                        <i @click="show_fm(item, 'icon', 'images')" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                                        <i @click="item.icon = ''" class="fa fa-trash delete_file"></i>
                                                                    </div>

<!--                                                                    <div class="icon_block">-->
<!--                                                                        <img @click="$refs['types_' + name][0].click()" style="max-width: 78px" :src="get_icon_src_new('types', name)" alt="">-->
<!--                                                                        <i @click="$refs['types_' + name][0].click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>-->
<!--                                                                        <i v-if="item.icon != '' || item.icon_file != ''" @click="item.icon_file = ''" class="fa fa-trash delete_file"></i>-->
<!--                                                                    </div>-->
<!---->
<!---->
<!--                                                                    <div class="hidden">-->
<!--                                                                        <input :ref="'types_' + name" type="file" accept="image/jpeg,image/png,image/gif" @change="process_icon_file('types', name, $event)">-->
<!--                                                                    </div>-->


                                                                </div>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="actions_block d-flex justify-content-end">
                                                                    <button @click="remove_type(name)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                        <span class="glyphicon glyphicon-trash"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="hr-line-dashed"></div>

                                                        <div class="row form-group">
                                                            <div class="col-12">
                                                                <span class="mr-1"><?php echo $lang_arr['facades_sizes_available'] ?></span>
                                                                <button @click="sort_sizes(item)" type="button" class="float-right btn btn-xs btn-w-m btn-success btn-outline">Сортировать</button>
                                                            </div>
                                                        </div>

                                                        <table class="table table-bordered table-hover table-sm table_sizes">
                                                            <thead>
                                                            <tr>

                                                                <th width="100px" >{{lang('code')}}</th>
                                                                <th >{{lang('name')}}</th>
                                                                <th width="100px" >{{lang('height')}}, {{lang('units')}}</th>
                                                                <th width="100px">{{lang('width')}}, {{lang('units')}}</th>

                                                                <th width="100px">{{lang('price')}}, {{lang('currency')}}</th>
                                                                <th ></th>
                                                            </tr>
                                                            </thead>

                                                            <tbody>
                                                            <template v-for="(size, index) in item.items">
                                                            <tr >
                                                                <td scope="row"><input v-model="size.code" type="text" class="form-control form-control-sm"></td>
                                                                <td><input v-model="size.name" type="text" class="form-control form-control-sm"></td>
                                                                <td><input v-model="size.height" type="number" class="form-control form-control-sm"></td>
                                                                <td><input v-model="size.width" type="number" class="form-control form-control-sm"></td>

                                                                <td><input v-model="size.price" type="number" min="0" step="0.01" class="form-control form-control-sm"></td>
                                                                <td rowspan="2"><i @click="remove_size(name, index)" class="fa fa-trash delete btn btn-outline btn-danger"></i></td>
                                                            </tr>
                                                                <tr>
                                                                    <td colspan="5">
                                                                        <i style="float: right" @click="show_fm(size, 'model', 'models')" class="fa fa-folder-o"></i>
                                                                        <span style="float: right" @click="show_fm(size, 'model', 'models')" v-show="size.model == ''">
                                                                            <i class="fa fa-exclamation-triangle"></i>Модель не выбрана
                                                                        </span>

                                                                        <input style="float: left; width: 70%" v-model="size.model" type="text" class="form-control">

                                                                    </td>
                                                                </tr>
                                                            </template>
                                                            </tbody>
                                                        </table>

                                                        <div v-show="0==1" class="row form-group ">
                                                            <div class="col-12 ">

                                                                <div v-for="(size, index) in item.items" class="row size_row ">

                                                                    <div class="col-2">
                                                                        <label><?php echo $lang_arr['code']?></label>
                                                                        <input type="text" v-model="size.code" class="form-control form-control-sm">
                                                                    </div>

                                                                    <div class="col-4">
                                                                        <label><?php echo $lang_arr['name'] ?></label>
                                                                        <input type="text" v-model="size.name" class="form-control form-control-sm">
                                                                    </div>

                                                                    <div class="col-2">
                                                                        <label><?php echo $lang_arr['height'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input type="number" min="0" step="1" v-model="size.height" class="form-control form-control-sm">
                                                                    </div>

                                                                    <div class="col-2">
                                                                        <label><?php echo $lang_arr['width'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input type="number" min="0" step="1" v-model="size.width" class="form-control form-control-sm">
                                                                    </div>


                                                                    <div class="col-2">
                                                                        <label><?php echo $lang_arr['price']?></label>
                                                                        <input type="text" v-model="size.price" class="form-control form-control-sm">
                                                                    </div>

                                                                    <div class="col-9">
                                                                        <!--                                                                        <div v-if="size.model" class="col-12 mb-1 text-primary">--><?php //echo $lang_arr['current_model'] ?><!--: {{size.current_model.split('/').pop()}}</div>-->
                                                                        <label v-if="size.model"><?php echo $lang_arr['change_model_file'] ?></label>
                                                                        <label v-if="size.model == ''"><?php echo $lang_arr['choose_facade_model'] ?></label>
                                                                        <input @change="process_model_file(size, index, $event)" accept=".fbx" type="file">

                                                                    </div>
                                                                    <div class="col-3">

                                                                        <button title="<?php echo $lang_arr['test_model'] ?>" @click="preview_model(name,index)" type="button" class="size_act_btn btn btn-w btn-info btn-outline">
                                                                            <span class="fa fa-desktop"></span>
                                                                        </button>

                                                                        <button title="<?php echo $lang_arr['delete'] ?>" @click="yesno_type = 'delete_size'; show_swal(name, index)" type="button" class="size_act_btn btn btn-w btn-danger btn-outline">
                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                        </button>

                                                                    </div>


                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="row form-group">
                                                            <div class="col-12">
                                                                <button @click="add_size(item, name)" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                                <button data-toggle="modal" data-target="#sizes_from_text" @click="model_multi_sizes_type = item" type="button" class="btn btn-w-m btn-primary btn-outline">Добавить из текста</button>
                                                                <button @click="$refs.excel_file[0].click()" type="button" class="btn btn-w-m btn-primary btn-outline">Добавить из xlsx</button>
                                                                <div class="hidden">
                                                                    <input type="file" ref="excel_file" accept=".xlsx" @change="process_excel_file(item, name, $event)">
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="additional_tab" class="tab-pane ">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <button @click="add_additional_item()" type="button" class="btn btn-w-m btn-primary btn-sm btn-outline"><?php echo $lang_arr['add'] ?></button>
                                        <button data-toggle="modal" data-target="#additional_from_text" type="button" class="btn btn-w-m btn-primary btn-sm btn-outline">Добавить несколько</button>
                                    </div>

                                    <table class="table table-bordered table-hover table-sm">
                                        <thead>
                                        <tr>

                                            <th class="col-2">{{lang('code')}}</th>
                                            <th class="col-7">{{lang('name')}}</th>
                                            <th class="col-2">{{lang('price')}}</th>
                                            <th class="col-1"></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(item, key) in item.facades.additional_items">

                                            <td scope="row"><input v-model="item.code" type="text" class="form-control form-control-sm"></td>
                                            <td><input v-model="item.name" type="text" class="form-control form-control-sm"></td>
                                            <td><input v-model="item.price" type="number" min="0" step="0.01" class="form-control form-control-sm"></td>
                                            <td><i @click="remove_additional_item(key)" class="fa fa-trash delete btn btn-outline btn-danger"></i></td>
                                        </tr>
                                        </tbody>
                                    </table>

<!--                                    <table class="table table-bordered table-hover table-sm">-->
<!--                                        <thead>-->
<!--                                        <tr>-->
<!--                                            <th class="col-1">#</th>-->
<!--                                            <th class="col-2">{{lang('code')}}</th>-->
<!--                                            <th class="col-6">{{lang('name')}}</th>-->
<!--                                            <th class="col-2">{{lang('price')}}</th>-->
<!--                                            <th class="col-1"></th>-->
<!--                                        </tr>-->
<!--                                        </thead>-->
<!---->
<!--                                        <tbody is="draggable" group="people" :list="additional_items_array" tag="tbody" v-bind="dragOptions" handle=".handle" group="parent" :move="on_drag" @start="drag = true" @end="drag = false">-->
<!--                                        <tr v-for="item in additional_items_array" :key="item.name">-->
<!--                                            <td scope="row"><i class="fa fa-align-justify handle"></i></td>-->
<!--                                            <td scope="row"><input v-model="item.code" type="text" class="form-control form-control-sm"></td>-->
<!--                                            <td><input v-model="item.name" type="text" class="form-control form-control-sm"></td>-->
<!--                                            <td><input v-model="item.price" type="number" min="0" step="0.01" class="form-control form-control-sm"></td>-->
<!--                                            <td><i @click="remove_additional_item(key)" class="fa fa-trash delete btn btn-outline btn-danger"></i></td>-->
<!--                                        </tr>-->
<!--                                        </tbody>-->
<!--                                    </table>-->
                                    <button @click="add_additional_item()" type="button" class="btn btn-w-m btn-primary btn-sm btn-outline"><?php echo $lang_arr['add'] ?></button>
                                    <button data-toggle="modal" data-target="#additional_from_text" type="button" class="btn btn-w-m btn-primary btn-sm btn-outline">Добавить несколько</button>
                                </div>
                            </div>

                            <div id="materials_tab" class="tab-pane">
                                <div class="panel-body models_list">

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="tabs-container d-flex flex-column">
                                                <div class="tabs-left">

                                                    <ul class="nav nav-tabs tabs-inner">
                                                        <li @click="active_tab_materials = name" v-for="(item, name) in item.facades.materials_n">
                                                            <a :title="item.name" v-bind:class="{ active: active_tab_materials == name }" class="nav-link">{{item.name}}</a>
                                                        </li>
                                                        <li>
                                                            <div class="btn-group dropright">
                                                                <button @click="add_material()" type="button" class="btn btn-primary  btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                            </div>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content">

                                                        <div v-bind:class="{ active: active_tab_materials == name }" v-if="active_tab_materials == name" v-for="(item, name) in item.facades.materials_n" class="tab-pane">
                                                            <div class="panel-body facades_panel_body container-fluid">

                                                                <button v-if="name > 0" @click="remove_material(name)" type="button" style="position: absolute; right: 17px; top: 3px; z-index: 999; padding: 2px 3px 2px 2px; font-size: 10px;" class="del_btn btn btn-w btn-danger btn-outline">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                </button>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                                                    <div class="col-8">
                                                                        <input :disabled="name == 0" v-model="item.name" class="form-control form-control-sm" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label">Ключ</label>
                                                                    <div class="col-8">
                                                                        <input @change="update_material" :disabled="name == 0" v-model="item.key" class="form-control form-control-sm" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Цвет</label>
                                                                    <div class="col-6">
                                                                        <div style="border: 1px solid; display: inline-block">
                                                                        <color-picker @change="update_material" v-model="item.params.color"></color-picker>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label"><?php echo $lang_arr['texture'] ?></label>
                                                                    <div class="col-sm-6">

                                                                        <div class="icon_block">
                                                                            <img @click="show_fm(item.params, 'map', 'images', true)" style="max-width: 64px" :src="get_icon_src_new(item.params.map, '64x64')" alt="">
                                                                            <i @click="show_fm(item.params, 'map', 'images', true)" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                                            <i v-show="item.params.map != ''" @click="item.params.map = ''" class="fa fa-trash delete_file"></i>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">{{ lang('normalMap') }}</label>
                                                                    <div class="col-sm-6">

                                                                        <div class="icon_block">
                                                                            <img @click="show_fm(item.params, 'normalMap', 'images', true)" style="max-width: 64px" :src="get_icon_src_new(item.params.normalMap, '64x64')" alt="">
                                                                            <i @click="show_fm(item.params, 'normalMap', 'images', true)" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                                            <i v-show="item.params.normalMap != ''" @click="item.params.normalMap = ''" class="fa fa-trash delete_file"></i>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">{{ lang('alphaMap') }}</label>
                                                                    <div class="col-sm-6">

                                                                        <div class="icon_block">
                                                                            <img @click="show_fm(item.params, 'alphaMap', 'images', true)" style="max-width: 64px" :src="get_icon_src_new(item.params.alphaMap, '64x64')" alt="">
                                                                            <i @click="show_fm(item.params, 'alphaMap', 'images', true)" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                                            <i v-show="item.params.alphaMap != ''" @click="item.params.alphaMap = ''" class="fa fa-trash delete_file"></i>
                                                                        </div>

                                                                    </div>

                                                                </div>


                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">{{ lang('roughness') }}</label>
                                                                    <div class="col-6">
                                                                        <input @change="update_material" v-model="item.params.roughness" class="form-control form-control-sm" type="number" min="0" max="1" step="0.01">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">{{ lang('metalness') }}</label>
                                                                    <div class="col-6">
                                                                        <input @change="update_material" v-model="item.params.metalness" class="form-control form-control-sm" type="number" min="0" max="1" step="0.01">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-8 col-form-label">Прозрачность</label>
                                                                    <div class="col-4">
                                                                        <label class="switch">
                                                                            <input @change="update_material"  v-model="item.params.transparent" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Ширина текстуры</label>
                                                                    <div class="col-6">
                                                                        <input @change="update_material" v-model="item.add_params.real_width" class="form-control form-control-sm" type="number" min="0" step="1">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Высота текстуры</label>
                                                                    <div class="col-6">
                                                                        <input @change="update_material" v-model="item.add_params.real_height" class="form-control form-control-sm" type="number" min="0" step="1">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-8 col-form-label">Растягивать по горизонтали</label>
                                                                    <div class="col-4">
                                                                        <label class="switch">
                                                                            <input @change="update_material"  v-model="item.add_params.stretch_width" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-8 col-form-label">Растягивать по вертикали</label>
                                                                    <div class="col-4">
                                                                        <label class="switch">
                                                                            <input @change="update_material"  v-model="item.add_params.stretch_height" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Тип заполнения</label>
                                                                    <div class="col-6">

                                                                        <select @change="update_material" v-model="item.add_params.wrapping" class="form-control form-control-sm" :value="item.add_params.wrapping">
                                                                            <option value="repeat">Повтор</option>
                                                                            <option value="mirror">Зеркало</option>
                                                                        </select>

                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">

<!--                                            <select v-model="preview_model" @change="select_facade()">-->
<!--                                                <option :value="opt" v-for="(opt, index) in all_models" >{{opt.code}} {{opt.name}}</option>-->
<!--                                            </select>-->

                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <v-select
                                                            :clearable="false"
                                                            :value="item.model"
                                                            label="name"
                                                            :options="all_models"
                                                            v-model="preview_model"
                                                            :key="item.name"
                                                            :selectable="(option) => option.is_type != 1"
                                                            @input="select_facade()"
                                                    >

                                                        <template v-slot:option="option">
                                                        <span :title="option.code + ' ' + option.name + ' ' + option.type_name" style="display: block; overflow:hidden;" :class="{'pl-3': option.is_type != 1, 'font-weight-bold': option.is_type == 1}">
                                                            <span style="margin-right: 40px">{{ option.code }} {{ option.name }}</span> <span style="font-weight: bold" v-if="option.is_type == 0">{{option.type_name}}</span>
                                                        </span>
                                                        </template>

                                                    </v-select>
                                                </div>
                                            </div>

                                            <div style="width: 100%; height: 400px" id="preview_viewport"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div id="cokol_tab" class="tab-pane">
                                <div class="panel-body">
                                    <div class="panel panel-default" v-for="(type, key) in models">
                                        <div class="panel-heading">
                                            {{type.name}}
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-2"></div>
                                                <div class="col-2">Название</div>
                                                <div class="col-2">Артикул</div>
                                                <div class="col-2">Цена</div>
                                                <div class="col-4">Модель</div>
                                            </div>
                                            <div class="row" v-for="(model, name) in type.models">
                                                <div class="col-2">{{model}}</div>
                                                <div class="col-2">
                                                    <input class="form-control form-control-sm" v-model="item[key][name + '_name']" type="text">
                                                </div>
                                                <div class="col-2">
                                                    <input class="form-control form-control-sm" v-model="item[key][name + '_code']" type="text">
                                                </div>
                                                <div class="col-2">
                                                    <input class="form-control form-control-sm" v-model="item[key][name + '_price']" type="number" min="0" step="0.01">
                                                </div>
                                                <div class="col-4">
                                                    <i style="float: right" @click="show_fm(item[key], name, 'models')" class="fa fa-folder-o"></i>
                                                    {{item[key][name]}}
                                                    <span style="float: right" @click="show_fm(item[key], name, 'models')" v-show="!item[key][name]">
                                                        <i class="fa fa-exclamation-triangle"></i>Модель не выбрана
                                                    </span>
                                                </div>


                                            </div>

                                            <div v-if="key=='molding'" class="row">
                                                <div class="col-2">Высота, мм</div>
                                                <div class="col-4"><input class="form-control form-control-sm" type="number" min="0" step="1" v-model="item.molding.height"></div>
                                                <div class="col-2"></div>
                                                <div class="col-4"></div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div id="modules_tab" class="tab-pane">
                                <div class="panel-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Выберите набор модулей</label>
                                        <div class="col-sm-10">
                                            <v-select
                                                    :clearable="false"
                                                    :value="item.modules_set_id"
                                                    label="name"
                                                    :options="$options.modules_sets"
                                                    :reduce="category => category.id"
                                                    v-model="item.modules_set_id"
                                                    :key="item.modules_set_id"
                                            >

                                            </v-select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="json_tab" class="tab-pane">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <textarea class="form-control" style="height: 60vh" id="json_input"></textarea>
                                        </div>
                                        <div class="col-12 text-right">
                                            <button type="button" class="btn btn-primary btn-sm" @click="apply_json"><?php echo $lang_arr['apply'] ?></button>
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

                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items/facades') ?>"><?php echo $lang_arr['cancel'] ?></a>
                                    <?php if (isset($id)): ?>
                                        <button @click="submit" class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                    <?php else: ?>
                                        <button @click="submit" class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add'] ?></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </form>

    <div class="modal inmodal fade" id="additional_from_text"  role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h6 class="modal-title">Добавить несколько элементов</h6>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p>
                                Чтобы добавить несколько элементов, добавьте текст в формате: <b>Артикул Название Цена</b> <br>
                            </p>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <textarea rows="10" class="form-control" ref="additional_textarea"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="switch">
                                <input v-model="additional_no_price" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <label class="col-sm-4 col-form-label">Без цены</label>


                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>
                    <button @click="add_multiple_additional_item()" type="button" class="btn btn-primary" data-dismiss="modal">{{lang('apply')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal inmodal" id="sizes_from_text"  role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h6 class="modal-title">Добавить несколько размеров</h6>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p>
                                Чтобы добавить несколько размеров, добавьте текст в формате: <b>Артикул Название Цена</b> <br>
                            </p>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <textarea rows="10" class="form-control" ref="sizes_textarea"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-1">
                            <label class="switch">
                                <input v-model="model_no_price" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <label class="col-sm-4 col-form-label">Без цены</label>


                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>
                    <button @click="add_multiple_sizes()" type="button" class="btn btn-primary" data-dismiss="modal">{{lang('apply')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="additional_from_model" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h6 class="modal-title">Добавить дополнительный элемент</h6>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p>
                               Чтобы редактировать этот элемент в дальнейшем - воспользуйтесь вкладкой "Дополнительно"
                            </p>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-3">
                            <label>{{lang('code')}}</label>
                            <input ref="na_code" type="text" class="form-control">
                        </div>
                        <div class="col-6">
                            <label>{{lang('name')}}</label>
                            <input ref="na_name" type="text" class="form-control">
                        </div>
                        <div class="col-3">
                            <label>{{lang('price')}}</label>
                            <input ref="na_price" type="number" min="0" step="0.01" class="form-control">
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>
                    <button @click="add_additional_from_model()" type="button" class="btn btn-primary" data-dismiss="modal">{{lang('apply')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="model_select_modal"  role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h6 class="modal-title">Добавить несколько элементов</h6>

                </div>
                <div class="modal-body" style="height: 50vh">
                    <filemanager @select_file="select_file(fm_item, fm_prop, $event, fm_update_mat)" :url="url" :folder_url="folder_url" :file_url="file_url" :remove_url="remove_url" :mode="fm_mode" ></filemanager>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal fade" id="model_upload_modal"  role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h6 class="modal-title">Загрузить файл модели</h6>
                </div>
                <div class="modal-body">


                    <div class="row form-group">
                        <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                        <div class="col-sm-10">
                            <input v-model="upload.name" type="text" class="form-control">
                            <span class="form-text m-b-none">Если оставить пустым будет использовано имя файла</span>
                        </div>
                    </div>

                    <div v-if="models_got" class="row form-group">
                        <label class="col-sm-2 col-form-label">{{lang('category')}}</label>
                        <div class="col-sm-10">
                            <v-select
                                    :clearable="false"
                                    :value="upload.category"
                                    label="name"
                                    :options="upload.categories.ordered"
                                    :reduce="category => category.id"
                                    v-model="upload.category"
                                    :key="upload.category"
                            >
                                <template v-slot:selected-option="option">
                                    <span v-if="option.parent != 0">{{upload.categories.hash[option.parent].name}} / &nbsp;</span>{{ option.name }}
                                </template>
                                <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{upload.categories.hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                                </template>
                            </v-select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-2 col-form-label">{{lang('model')}}</label>
                        <div class="col-sm-10">
                            <input type="file" @change="process_file($event)" accept=".fbx" class="form-control-file">
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>
                    <button @click="make_upload()" type="button" class="btn btn-primary" >{{lang('apply')}}</button>
                </div>
            </div>
        </div>
    </div>

</div>




<div class="three_modal_wrapper">
    <div class="three_modal">
        <span class="close_three_modal glyphicon glyphicon-remove"></span>
        <div id="three_viewport">
        </div>
    </div>
</div>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">


<style>

    .cp{
        cursor: pointer;
    }

    .size_row{
        background: rgba(243, 243, 243, 0.5);
        padding-top: 15px;
        padding-bottom: 14px;
        margin-bottom: 10px;
    }

    .size_act_btn{
        margin-top: 30px;
    }

    .size_divider{
        margin-top: 10px;
    }

    .category_select{
        list-style: none;
        margin: 0;
        padding: 0;
        text-align: left;
        user-select: none;
    }

    .category_select span{
        cursor: pointer;
        padding-bottom: 3px;
        display: table;
        font-size: 14px;
    }

    .category_select button{
        text-align: left;

    }

    .category_select ul{
        list-style: none;
        margin: 0;
        padding: 0 0 0 15px;
    }

    .category_select li{

    }

    .category_select .active{
        font-weight: bold;
    }


    .panel-body .tabs-container{
        padding-bottom: 25px;
    }


    .three_modal_wrapper{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(31, 31, 31, 0.81);
        z-index: 9999;
        display: none;
    }

    .three_modal{
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        background: #ffffff;
    }

    .close_three_modal{
        position: absolute;
        top:10px;
        right:10px;
        cursor: pointer;
    }

    #three_viewport{
        width: 100%;
        height: 100%;
    }

    #three_viewport_controls{
        position: absolute;
        background: #333333;
        left: 0;
        top:0;
        color: #fff;
        padding: 5px;
    }

    #three_viewport_controls button, #three_viewport_controls input{
        color: #000;
    }

    #three_viewport_controls span{
        display: inline-block;
        width: 110px;
    }

    #three_viewport_controls input{
        display: inline-block;
        width: 80px;
        margin: 5px;
        padding: 0 5px;
    }

    #three_viewport_controls button{
        display: inline-block;
        width: 60px;
        margin: 5px;
    }




</style>
<style>

    .tabs-container .tabs-left > .nav-tabs a.active, .tabs-container .tabs-left > .nav-tabs a.active:hover, .tabs-container .tabs-left > .nav-tabs a.active:focus {
        border-color: #9b9b9b  transparent #9b9b9b  #9b9b9b ;
    }

    .icon_block{
        display: inline-block;
        position: relative;
    }
    .icon_block img{
        cursor: pointer;
    }

    .icon_block .open_file{
        cursor: pointer;
        position: absolute;
        right: -35px;
        top: 0;
        font-size: 25px;
        color: #1c84c6;
    }

    .icon_block .delete_file{
        bottom: 0;
        cursor: pointer;
        position: absolute;
        right: -30px;
        font-size: 25px;
        color: #ed5565;
    }

    .modal_new{
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 2050;
        overflow: hidden;
        outline: 0;
        background: rgba(0, 0, 0, 0.35);
    }

    .facades_panel_body{
        min-height: 500px;
    }


    .border_row{
        border: 1px solid #cacaca;
        padding: 10px 0 0 0;
    }

    .tabs-inner .active{

    }

    .file-select > .select-button {
        padding: 1rem;

        color: white;
        background-color: #2EA169;

        border-radius: .3rem;

        text-align: center;
        font-weight: bold;
    }

    .file-select > input[type="file"] {
        display: none;
    }

    .mat_table td{
        cursor: pointer;
    }

    .table_sizes > thead > tr > th, .table_sizes > tbody > tr > td {
        padding: 4px;
    }

    .table_sizes .form-control{
        padding: 2px;
        height: 25px;
        font-size: 14px;
        border: 0;
    }

    /*.tabs-container .tabs-left > .nav-tabs, .tabs-container .tabs-right > .nav-tabs{*/
    /*    width: 13%;*/
    /*}*/

    /*.tabs-container .tabs-left .panel-body{*/
    /*    width: 87%;*/
    /*    margin-left: 13%;*/
    /*}*/

    #models_tab .nav-tabs > li > a, #materials_tab .nav-tabs > li > a{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 9px 5px 9px 5px;
    }

</style>
<!---->
<?php //include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php';?>
<!--<script>-->
<!--    console.log(view_mode);-->
<!--    view_mode = true;-->
<!--</script>-->

<script src="/common_assets/libs/vue.js"></script>
<script src="/common_assets/libs/vue/colorpicker/colorpicker.min.js"></script>
<script src="/common_assets/admin_js/vue/filemanager.js"></script>




<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>

<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<!---->
<!---->
<script src="/common_assets/libs/three106.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>
<script src="/common_assets/libs/obj_export.js" type="text/javascript"></script>



<script src="/common_assets/js/v4/functions.js" type="text/javascript"></script>
<script src="/common_assets/js/v4/globals.js" type="text/javascript"></script>
<script src="/common_assets/js/v4/Facade_new.js" type="text/javascript"></script>
<script src="/common_assets/js/v4/Model_cache.js" type="text/javascript"></script>
<script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>
<script src="/common_assets/admin_js/vue/kitchen/facades_system.js"></script>
<!---->
<!---->
<!---->
<!--<script src="/common_assets/admin_js/production/facades/facades_system.js?--><?php //echo md5(date('m-d-Y-His A e'));?><!--"></script>-->
