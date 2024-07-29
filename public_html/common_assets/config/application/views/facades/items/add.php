<?php
if (isset($common) && $common == 1) {
    $submit_url = site_url($controller_name . '/items_add_ajax_common/');
    if (isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items_common/' . $controller_name);
} else {
    $submit_url = site_url($controller_name . '/items_add_ajax/');
    if (isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/' . $controller_name);
}
?>

    <form id="sub_form" @submit="submit" action="<?php echo $submit_url ?>">

        <input id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
        <?php if (isset($id)): ?>
            <input id="facade_id" value="<?php echo $id ?>" type="hidden">
        <?php endif; ?>
        <?php if (isset($id)): ?>
            <input id="item_id" value="<?php echo $id ?>" type="hidden">
        <?php endif; ?>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">

                <?php if (isset($common) && $common == 1): ?>
                    <?php if (isset($id)): ?>
                        <h2 style="color: red"> Редактировать фасад (ОБЩАЯ БАЗА)</h2>
                    <?php else: ?>
                        <h2 style="color: red"> Добавить фасад (ОБЩАЯ БАЗА)</h2>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (isset($id)): ?>
                        <h2><?php echo $lang_arr['facade_edit_heading'] ?> <span v-cloak>{{facade_obj.name}}</span></h2>
                    <?php else: ?>
                        <h2><?php echo $lang_arr['facade_add'] ?></h2>
                    <?php endif; ?>
                <?php endif; ?>


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
            <div class="col-lg-2">

            </div>
        </div>


        <div class="wrapper wrapper-content  animated fadeInRight">

            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger error_msg" style="display:none"></div>
                </div>
            </div>

            <div v-cloak class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li><a class="nav-link active" data-toggle="tab" href="#main_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#types_tab"><?php echo $lang_arr['models'] ?></a></li>
                            <!--                    <li><a class="nav-link" data-toggle="tab" href="#types_double_tab">--><?php //echo $lang_arr['facade_types_double']?><!--</a></li>-->
                            <!--                    <li><a class="nav-link" data-toggle="tab" href="#types_triple_tab">--><?php //echo $lang_arr['facade_types_triple']?><!--</a></li>-->
                            <li><a class="nav-link" data-toggle="tab" href="#materials_tab"><?php echo $lang_arr['available_materials'] ?></a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#prices_tab"><?php echo $lang_arr['prices'] ?></a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#additions_tab"><?php echo $lang_arr['accessories'] ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="main_params_tab" class="tab-pane active">
                                <div class="panel-body">

                                    <fieldset>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?>*</label>
                                            <div class="col-sm-10">
                                                <input v-model="facade_obj.name" type="text" class="form-control" id="name" name="name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['code'] ?></label>
                                            <div class="col-sm-10">
                                                <input v-model="facade_obj.code" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order'] ?></label>
                                            <div class="col-sm-10">
                                                <input v-model="facade_obj.order" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div v-if="options_ready == true" class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                                            <div class="col-sm-10">
                                                <pp_category @e_update="facade_obj.category = $event" :selected="facade_obj.category" :controller="'facades'"></pp_category>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['thickness'] ?>, <?php echo $lang_arr['units'] ?></label>
                                            <div class="col-sm-10">
                                                <input v-model="facade_obj.thickness" type="number" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['thickness_side'] ?>, <?php echo $lang_arr['units'] ?></label>
                                            <div class="col-sm-10">
                                                <input v-model="facade_obj.thickness_side" type="number" class="form-control">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Ручки</label>
                                            <div class="col-sm-10">
                                                <label class="switch">
                                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="facade_obj.handle" type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['handle_offset'] ?></label>
                                            <div class="col-sm-10">
                                                <input placeholder="25" v-model="facade_obj.handle_offset" type="text" class="form-control">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['cornice_recommended'] ?></label>
                                            <div class="col-sm-10">

                                                <select class="form-control" v-model="facade_obj.rec_cornice">
                                                    <option :value="it.id" v-for="it in $options.cornice_items">{{it.name}}</option>
                                                </select>


                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active'] ?></label>
                                            <div class="col-sm-10">
                                                <label class="switch">
                                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="facade_obj.active" type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                                            <div class="col-sm-5">

                                                <pp_image
                                                        v-if="options_ready"
                                                        ref="main_icon"
                                                        @e_update="facade_obj.icon=$event"
                                                        :src="facade_obj.icon"
                                                        :t_width = 78
                                                        :t_height = 125
                                                        :p_width = 78
                                                        :p_height = 125
                                                ></pp_image>


                                            </div>
                                            <div class="col-sm-5">


                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['normalMap'] ?></label>
                                            <div class="col-sm-5">

                                                <pp_image
                                                        v-if="options_ready"
                                                        @e_update="facade_obj.nmap=$event"
                                                        :src="facade_obj.nmap"
                                                        :t_width = 1024
                                                        :t_height = 1024
                                                        :p_width = 128
                                                        :p_height = 128
                                                ></pp_image>


                                            </div>
                                            <div class="col-sm-5">


                                            </div>
                                        </div>


                                    </fieldset>

                                </div>
                            </div>
                            <div id="types_tab" class="tab-pane">
                                <div class="panel-body models_list">
                                    <div class="tabs-container">
                                        <div class="tabs-left">

                                            <ul class="nav nav-tabs tabs-inner">
                                                <li @click="active_tab = name" v-for="(item, name) in facade_obj.types">
                                                    <a v-bind:class="{ active: active_tab == name }" class="nav-link">{{item.name}}</a>
                                                </li>
                                                <li>
                                                    <button @click="add_type()" type="button" class="btn btn-w-m btn-primary btn-block btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                </li>
                                            </ul>

                                            <div class="tab-content">
                                                <div v-bind:class="{ active: active_tab == name }" v-show="active_tab == name" v-for="(item, name) in facade_obj.types" class="tab-pane">
                                                    <div class="panel-body facades_panel_body container-fluid">

                                                        <div class="row form-group">
                                                            <div class="col-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                                                    <div class="col-8">
                                                                        <input v-model="item.name" class="form-control" type="text">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['weight'] ?></label>
                                                                    <div class="col-8">
                                                                        <input step="0.001" v-model="item.weight" class="form-control" type="number">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['thickness'] ?>, <?php echo $lang_arr['units']?></label>
                                                                    <div class="col-8">
                                                                        <input step="1" v-model="item.thickness" class="form-control" type="number">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['is_radius'] ?></label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.radius" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facade_side'] ?></label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.side" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>


                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['compatibility'] ?></label>
                                                                    <div class="col-8">
                                                                        <select v-bind:value="computed_compat_type(name)" @change="set_compat_type($event, name)" class="form-control">
                                                                            <option value=""><?php echo $lang_arr['no'] ?></option>
                                                                            <option value="full"><?php echo $lang_arr['facade_full'] ?></option>
                                                                            <option value="side"><?php echo $lang_arr['facade_side'] ?></option>
                                                                            <option value="window"><?php echo $lang_arr['facade_window'] ?></option>
                                                                            <option value="frame"><?php echo $lang_arr['facade_frame'] ?></option>
                                                                            <option value="radius"><?php echo $lang_arr['facade_radius_full'] ?></option>
                                                                            <option value="radius_window"><?php echo $lang_arr['facade_radius_window'] ?></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="row mb-2">
                                                                    <div class="col-10">
                                                                        <div>
                                                                            Иконка
                                                                        </div>
                                                                        <div>
                                                                            <pp_image
                                                                                    v-if="options_ready"
                                                                                    @e_update="item.icon=$event"
                                                                                    :src="item.icon"
                                                                                    :t_width = 78
                                                                                    :t_height = 125
                                                                                    :p_width = 78
                                                                                    :p_height = 125
                                                                            ></pp_image>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-2">
                                                                        <div class="actions_block d-flex justify-content-end">
                                                                            <button @click="yesno_type = 'delete_type'; show_swal(name)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                                <span class="glyphicon glyphicon-trash"></span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div>
                                                                            Карта нормали
                                                                        </div>
                                                                        <div>
                                                                            <pp_image
                                                                                    v-if="options_ready"
                                                                                    @e_update="item.nmap=$event"
                                                                                    :src="item.nmap"
                                                                                    :t_width=1024
                                                                                    :t_height=1024
                                                                                    :p_width=128
                                                                                    :p_height=128
                                                                            ></pp_image>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>


                                                        <div class="hr-line-dashed"></div>

                                                        <div class="row form-group">
                                                            <div class="col-12">
                                                                <h4><?php echo $lang_arr['facades_sizes_available'] ?></h4>
                                                            </div>
                                                        </div>

                                                        <div class="row form-group">
                                                            <div class="col-12 ">

                                                                <div v-for="(size, index) in item.items" class="row size_row">
                                                                    <div class="col-2">
                                                                        <?php echo $lang_arr['3dmodel_single'] ?>
                                                                    </div>
                                                                    <div class="col-10 mb-2">
                                                                        <pp_model :lang="$options.lang" :model="size.model" v-model="size.model"></pp_model>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['min_width'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input type="text" v-model="size.min_width" class="form-control form-control-sm">
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['max_width'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input type="text" v-model="size.max_width" class="form-control form-control-sm">
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['min_height'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input type="text" v-model="size.min_height" class="form-control form-control-sm">
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['max_height'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input type="text" v-model="size.max_height" class="form-control form-control-sm">
                                                                    </div>

                                                                    <div class="col-12" v-if="item.items[index+1]">
                                                                        <div class="mb-2">
                                                                            <span class="text-primary"><?php echo $lang_arr['this_facade_model_sizes_from'] ?> <b><?php echo $lang_arr['from'] ?> {{size.min_width}}x{{size.min_height}} <?php echo $lang_arr['units'] ?>.</b><b> <?php echo $lang_arr['to'] ?> {{item.items[index+1].min_width}}x{{item.items[index+1].min_height}} <?php echo $lang_arr['units'] ?>.</b> </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12" v-if="!item.items[index+1]">
                                                                        <div>
                                                                            <span class="text-primary"><?php echo $lang_arr['this_facade_model_sizes_from'] ?> <b><?php echo $lang_arr['from'] ?> {{size.min_width}}x{{size.min_height}} <?php echo $lang_arr['units'] ?>.</b></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-3">
                                                                        <label class="mb-0">Группа</label>
                                                                        <select v-model="size.group" class="form-control form-control-sm">
                                                                            <option value="all">Все</option>
                                                                            <option value="top">Верх</option>
                                                                            <option value="bottom">Низ</option>
                                                                            <option value="reversed">Интегрированная ручка</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <label class="mb-0">Открывание</label>
                                                                        <select v-model="size.open" class="form-control form-control-sm">
                                                                            <option value="all">Все</option>
                                                                            <option value="ltr">Направо</option>
                                                                            <option value="rtl">Низ</option>
                                                                        </select>
                                                                    </div>


                                                                    <div class="col-2">
                                                                        <button title="<?php echo $lang_arr['test_model'] ?>" @click="preview_model(name,index)" type="button" class="size_act_btn btn btn-w btn-primary btn-outline">
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
                                                            </div>
                                                        </div>


                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="types_double_tab" class="tab-pane">

                                <div class="panel-body models_list">

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['double_offset'] ?></label>
                                        <div class="col-4">
                                            <input v-model="facade_obj.double_offset" class="form-control" type="text">
                                        </div>
                                        <div class="col-4">

                                        </div>
                                    </div>

                                    <div class="tabs-container">
                                        <div class="tabs-left">
                                            <ul class="nav nav-tabs tabs-inner">
                                                <li @click="active_tab_double = name" v-for="(item, name) in facade_obj.types_double">
                                                    <a v-bind:class="{ active: active_tab_double == name }" class="nav-link">{{item.name}}</a>
                                                </li>
                                                <li>
                                                    <button @click="add_type_double()" type="button" class="btn btn-w-m btn-primary btn-block btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                </li>
                                            </ul>

                                            <div class="tab-content">

                                                <div v-bind:class="{ active: active_tab_double == name }" v-show="active_tab_double == name" v-for="(item, name) in facade_obj.types_double"
                                                     class="tab-pane">

                                                    <div class="panel-body facades_panel_body container-fluid">
                                                        <div class="row form-group">
                                                            <div class="col-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                                                    <div class="col-8">
                                                                        <input v-model="item.name" class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div>
                                                                    <div class="icon_block">
                                                                        <img @click="file_target = 'icon'; file_target_type = name; file_target_block = 'types_double'; $refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" style="max-width: 78px" :src="correct_url_type('types_double', name)" alt="">
                                                                        <i @click="file_target = 'icon'; file_target_type = name; file_target_block = 'types_double'; $refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                                        <i v-if="item.icon != ''" @click="item.icon = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="actions_block d-flex justify-content-end">
                                                                    <button @click="yesno_type = 'delete_type_double'; show_swal(name)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                        <span class="glyphicon glyphicon-trash"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="hr-line-dashed"></div>

                                                        <div class="row form-group">
                                                            <div class="col-12">
                                                                <h4><?php echo $lang_arr['facades_sizes_available'] ?></h4>
                                                            </div>
                                                        </div>


                                                        <div class="row form-group ">
                                                            <div class="col-12 ">

                                                                <div v-for="(size, index) in item.items" class="row size_row">
                                                                    <div v-if="size.model" class="col-12 mb-2 text=primary"><?php echo $lang_arr['current_model'] ?>: <b>{{correct_model_url(size.model)}}</b></div>

                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['min_width'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input placeholder="<?php echo $lang_arr['min_width'] ?> (<?php echo $lang_arr['units'] ?>)." type="text" v-model="size.min_width" class="form-control form-control-sm">
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['min_height'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input placeholder="<?php echo $lang_arr['min_height'] ?> (<?php echo $lang_arr['units'] ?>)." type="text" v-model="size.min_height" class="form-control form-control-sm">
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <label class="mb-0" style="opacity: 0;">Группа</label>
                                                                        <button @click="file_target = 'model'; file_target_type = name; file_target_item = index; file_target_block = 'types_double'; $refs.fileman.data_mode = 'models'" type="button" data-toggle="modal" data-target="#filemanager" class="btn-block form-control-sm btn btn-outline-info" title="Выбрать или загрузить модель">
                                                                            <span class="fa fa-folder-open"></span>
                                                                            Выбрать
                                                                        </button>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <button title="<?php echo $lang_arr['test_model'] ?>" @click="preview_model_new(item, index, name, 1)" type="button" class="size_act_btn btn btn-w btn-info btn-outline">
                                                                            <span class="fa fa-desktop"></span>
                                                                        </button>

                                                                        <button title="<?php echo $lang_arr['delete'] ?>" @click="yesno_type = 'delete_size_double'; show_swal(name, index)" type="button" class="size_act_btn btn btn-w btn-danger btn-outline">
                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="col-12" v-if="item.items[index+1]">
                                                                        <div class="mt-2">
                                                                            <span class="text-primary"><?php echo $lang_arr['this_facade_model_sizes_from'] ?> <b><?php echo $lang_arr['from'] ?> {{size.min_width}}x{{size.min_height}} <?php echo $lang_arr['units'] ?>.</b><b> <?php echo $lang_arr['to'] ?> {{item.items[index+1].min_width}}x{{item.items[index+1].min_height}} <?php echo $lang_arr['units'] ?>.</b> </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12" v-if="!item.items[index+1]">
                                                                        <div class="mt-2">
                                                                            <span class="text-primary"><?php echo $lang_arr['this_facade_model_sizes_from'] ?> <b><?php echo $lang_arr['from'] ?> {{size.min_width}}x{{size.min_height}} <?php echo $lang_arr['units'] ?>.</b></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-group">
                                                            <div class="col-12">
                                                                <button @click="add_size_double(item, name)" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div id="types_triple_tab" class="tab-pane">

                                <div class="panel-body models_list">


                                    <div class="form-group row">
                                        <div v-if="facade_obj.triple_decor_model.model" class="col-12 mb-1 text=primary"><?php echo $lang_arr['current_model'] ?>: {{correct_model_url(facade_obj.triple_decor_model.model)}}</div>
                                        <div class="col-2">
                                            <label><?php echo $lang_arr['choose_triple_decor_model'] ?></label>
                                            <button @click="file_target = 'triple_decor_model'; $refs.fileman.data_mode = 'models'" type="button" data-toggle="modal" data-target="#filemanager" class="btn-block form-control-sm btn btn-outline-info" title="Выбрать или загрузить модель">
                                                <span class="fa fa-folder-open"></span>
                                                Выбрать
                                            </button>

                                        </div>
                                        <div class="col-3">
                                            <label><?php echo $lang_arr['obj_height'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                            <input placeholder="<?php echo $lang_arr['obj_height'] ?> (<?php echo $lang_arr['units'] ?>)." type="text" v-model="facade_obj.triple_decor_model.height" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-3">
                                            <label><?php echo $lang_arr['obj_thickness'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                            <input placeholder="<?php echo $lang_arr['obj_thickness'] ?> (<?php echo $lang_arr['units'] ?>)." type="text" v-model="facade_obj.triple_decor_model.thickness" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-4">
                                            <label>Отступ моделей фасада (<?php echo $lang_arr['units'] ?>).</label>
                                            <input placeholder="Отступ моделей фасада (<?php echo $lang_arr['units'] ?>)." type="text" v-model="facade_obj.triple_decor_model.offset" class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="tabs-container">
                                        <div class="tabs-left">
                                            <ul class="nav nav-tabs tabs-inner">
                                                <li @click="active_tab_triple = name" v-for="(item, name) in facade_obj.types_triple">
                                                    <a v-bind:class="{ active: active_tab_triple == name }" class="nav-link">{{item.name}}</a>
                                                </li>
                                                <li>
                                                    <button @click="add_type_triple()" type="button" class="btn btn-w-m btn-primary btn-block btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                </li>
                                            </ul>

                                            <div class="tab-content">

                                                <div v-bind:class="{ active: active_tab_triple == name }" v-show="active_tab_triple == name" v-for="(item, name) in facade_obj.types_triple" class="tab-pane">

                                                    <div class="panel-body facades_panel_body container-fluid">
                                                        <div class="row form-group">
                                                            <div class="col-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                                                    <div class="col-8">
                                                                        <input v-model="item.name" class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <div>
                                                                    <div class="icon_block">
                                                                        <img @click="file_target = 'icon'; file_target_type = name; file_target_block = 'types_triple'; $refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" style="max-width: 78px" :src="correct_url_type('types_triple', name)" alt="">
                                                                        <i @click="file_target = 'icon'; file_target_type = name; file_target_block = 'types_triple'; $refs.fileman.data_mode = 'images'" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                                        <i v-if="item.icon != ''" @click="item.icon = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-2">
                                                                <div class="actions_block d-flex justify-content-end">
                                                                    <button @click="yesno_type = 'delete_type_triple'; show_swal(name)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                        <span class="glyphicon glyphicon-trash"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="hr-line-dashed"></div>

                                                        <div class="row form-group">
                                                            <div class="col-12">
                                                                <h4><?php echo $lang_arr['facades_sizes_available'] ?></h4>
                                                            </div>
                                                        </div>

                                                        <div class="row form-group ">
                                                            <div class="col-12 ">
                                                                <div v-for="(size, index) in item.items" class="row size_row">
                                                                    <div v-if="size.model" class="col-12 mb-2 text=primary"><?php echo $lang_arr['current_model'] ?>: <b>{{correct_model_url(size.model)}}</b></div>

                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['min_width'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input placeholder="<?php echo $lang_arr['min_width'] ?> (<?php echo $lang_arr['units'] ?>)." type="text" v-model="size.min_width" class="form-control form-control-sm">
                                                                    </div>
                                                                    <div class="col-3">
                                                                        <label class="mb-0"><?php echo $lang_arr['min_height'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                                        <input placeholder="<?php echo $lang_arr['min_height'] ?> (<?php echo $lang_arr['units'] ?>)." type="text" v-model="size.min_height" class="form-control form-control-sm">
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <label class="mb-0" style="opacity: 0;">Группа</label>
                                                                        <button @click="file_target = 'model'; file_target_type = name; file_target_item = index; file_target_block = 'types_triple'; $refs.fileman.data_mode = 'models'" type="button" data-toggle="modal" data-target="#filemanager" class="btn-block form-control-sm btn btn-outline-info" title="Выбрать или загрузить модель">
                                                                            <span class="fa fa-folder-open"></span>
                                                                            Выбрать
                                                                        </button>
                                                                    </div>
                                                                    <div class="col-2">

                                                                        <button title="<?php echo $lang_arr['test_model'] ?>" @click="preview_model_new(item,index, name, 2)" type="button" class="size_act_btn btn btn-w btn-info btn-outline">
                                                                            <span class="fa fa-desktop"></span>
                                                                        </button>

                                                                        <button title="<?php echo $lang_arr['delete'] ?>" @click="yesno_type = 'delete_size_triple'; show_swal(name, index)" type="button" class="size_act_btn btn btn-w btn-danger btn-outline">
                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                        </button>

                                                                    </div>

                                                                    <div class="col-12" v-if="item.items[index+1]">
                                                                        <div class="mt-2">
                                                                            <span class="text-primary"><?php echo $lang_arr['this_facade_model_sizes_from'] ?> <b><?php echo $lang_arr['from'] ?> {{size.min_width}}x{{size.min_height}} <?php echo $lang_arr['units'] ?>.</b><b> <?php echo $lang_arr['to'] ?> {{item.items[index+1].min_width}}x{{item.items[index+1].min_height}} <?php echo $lang_arr['units'] ?>.</b> </span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12" v-if="!item.items[index+1]">
                                                                        <div class="mt-2">
                                                                            <span class="text-primary"><?php echo $lang_arr['this_facade_model_sizes_from'] ?> <b><?php echo $lang_arr['from'] ?> {{size.min_width}}x{{size.min_height}} <?php echo $lang_arr['units'] ?>.</b></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row form-group">
                                                            <div class="col-12">
                                                                <button @click="add_size_double(item, name)" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div id="materials_tab" class="tab-pane">
                                <div class="panel-body materials_list">

                                    <div class=" row">
                                        <div class="col-12"><h3><?php echo $lang_arr['basic_materials'] ?></h3></div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['choose_categories'] ?></label>
                                        <div class="col-sm-10">

                                            <v-select
                                                    v-if="mat_cats.length"
                                                    multiple :close-on-select="false"
                                                    :reduce="name => name.id"
                                                    :value="facade_obj.materials"
                                                    @input="materials_categories_change($event)"
                                                    v-model="facade_obj.materials"
                                                    :options="mat_cats"
                                                    label="name">
                                                <template v-slot:option="option">
                                                    {{ option.name }}
                                                </template>
                                            </v-select>

                                            <!--                                <v-select multiple v-model="facade_obj.materials" :options="$options.materials.categories">-->

                                            <!--                                </v-select>-->
                                        </div>
                                    </div>

                                    <div class="hr-line-dashed"></div>

                                    <div class="row"></div>

                                    <div class=" row">
                                        <div class="col-12"><h3><?php echo $lang_arr['additional_materials'] ?></h3></div>
                                    </div>

                                    <div class="tabs-container">

                                        <div class="tabs-left">

                                            <ul class="nav nav-tabs tabs-inner">
                                                <li @click="active_material_tab = name" v-for="(item, name) in facade_obj.additional_materials">
                                                    <a v-bind:class="{ active: active_material_tab == name }" class="nav-link">{{item.name}}</a>
                                                </li>
                                                <li>
                                                    <button @click="add_material()" type="button" class="btn btn-w-m btn-primary btn-outline btn-block"><?php echo $lang_arr['add'] ?></button>
                                                </li>
                                            </ul>

                                            <div class="tab-content">

                                                <div v-bind:class="{ active: active_material_tab == name }" v-show="active_material_tab == name" v-for="(item, name) in facade_obj.additional_materials"
                                                     class="tab-pane">
                                                    <div class="panel-body facades_panel_body container-fluid">

                                                        <div class="row form-group">
                                                            <div class="col-10">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                                                    <div class="col-8">
                                                                        <input v-model="item.name" class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['key'] ?></label>
                                                                    <div class="col-8">
                                                                        <input v-model="item.key" class="form-control" type="text">
                                                                    </div>
                                                                </div>

                                                                <div style="display: none" class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['material_required'] ?></label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="0" v-bind:false-value="1" v-model="item.required" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['material_fixed'] ?></label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="0" v-bind:false-value="1" v-model="item.fixed" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div v-if="!item.fixed" class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['additional_materials_categories'] ?></label>
                                                                    <div class="col-8">
                                                                        <v-select v-if="mat_cats.length" multiple :close-on-select="false" @input="check_item_tmp()" :reduce="name => name.id" v-model="item.materials" :options="mat_cats" label="name">
                                                                            <template v-slot:option="option">
                                                                                {{ option.name }}
                                                                            </template>
                                                                        </v-select>
                                                                    </div>
                                                                </div>

                                                                <div v-if="item.fixed != 1 && item.materials.length || item.fixed == 1" class="form-group row">

                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_material'] ?></label>

                                                                    <div class="col-8">

                                                                        <div class="bg-muted p-2" v-if="$options.mats.length && item.selected">


                                                                            <div @click="show_modal(); material_select_modal = true; selected_add_mat = name" class="d-flex flex-row cp align-items-center">
                                                                                <div v-if="$options.mats[item.selected]">
                                                                                    <div v-if="$options.mats[item.selected].map != '' && $options.mats[item.selected].map">
                                                                                        <div v-if="$options.mats[item.selected].map.indexOf('common_assets') > -1">
                                                                                            <img style="max-width: 50px" :src="$options.mats[item.selected].map" alt="">
                                                                                        </div>
                                                                                        <div v-else>
                                                                                            <img style="max-width: 50px" :src="$options.acc_url+$options.mats[item.selected].map" alt="">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div v-else>
                                                                                        <div style="width: 50px; height: 50px" v-bind:style="{ background: $options.mats[item.selected].color}"></div>
                                                                                    </div>
                                                                                    <div class="p-2 bd-highlight">
                                                                                        {{$options.mats[item.selected].name}}
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        </div>

                                                                        <button class="btn btn-success btn-outline btn-block mt-2" @click="show_modal(); material_select_modal = true; selected_add_mat = name" type="button"><?php echo $lang_arr['choose_material'] ?></button>
                                                                    </div>


                                                                </div>


                                                            </div>

                                                            <div class="col-2">
                                                                <div class="actions_block d-flex justify-content-end">
                                                                    <button @click="yesno_type = 'delete_material'; show_swal(name)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                        <span class="glyphicon glyphicon-trash"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="row form-group ">
                                                            <div class="col-12 ">


                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="prices_tab" class="tab-pane">

                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li><a class="nav-link active" data-toggle="tab" href="#pr_main">Материалы фасадов</a></li>
                                        <li><a class="nav-link" data-toggle="tab" href="#pr_edges">Кромка</a></li>
                                        <li><a class="nav-link" data-toggle="tab" href="#pr_decor">Индивидуальные декоры</a></li>
                                        <li><a class="nav-link" data-toggle="tab" href="#pr_fixed">Точные размеры фасадов</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="pr_main" class="tab-pane active">
                                            <div class="panel-body">

                                                <div class=" row">
                                                    <div class="col-12"><h3><?php echo $lang_arr['prices'] ?>, <?php echo $lang_arr['square_units'] ?></h3></div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <table class="table table-bordered table-hover ">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th v-for="(type,name) in facade_obj.types">{{type.name}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr v-for="(cat,index) in computed_prices_categories">
                                                                <td v-bind:style="{ 'font-weight': mat_cats_all[cat.id].parent == 0 ? 'bold' : 'normal'}">{{mat_cats_all[cat.id].name}}</td>
                                                                <td v-for="(type,name) in facade_obj.types">
                                                                    <template v-if="facade_obj.prices[name] && facade_obj.prices[name][cat.id]">
                                                                    <input @input="change_children_price(name, cat.id)" v-model="facade_obj.prices[name][cat.id].price" class="form-control" type="text">
                                                                    </template>
                                                                </td>
                                                            </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div id="pr_edges" class="tab-pane">
                                            <div class="panel-body">

                                                <div class=" row">
                                                    <div class="col-12"><h3><?php echo $lang_arr['prices'] ?>, <?php echo $lang_arr['pogon_units'] ?></h3></div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12">
                                                        <table class="table table-bordered table-hover ">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th v-for="(type,name) in facade_obj.types">{{type.name}}</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr v-for="(cat,index) in computed_prices_categories">
                                                                <td v-bind:style="{ 'font-weight': mat_cats_all[cat.id].parent == 0 ? 'bold' : 'normal'}">{{mat_cats_all[cat.id].name}}</td>
                                                                <td v-for="(type,name) in facade_obj.types">
                                                                    <template v-if="facade_obj.prices_edge[name] && facade_obj.prices_edge[name][cat.id]">
                                                                        <input @input="change_children_price_edge(name, cat.id)" v-model="facade_obj.prices_edge[name][cat.id].price" class="form-control" type="text">
                                                                    </template>
<!--                                                                    <template v-else>-->
<!--                                                                        {{facade_obj.prices_edge[name][cat.id]}}-->
<!--                                                                    </template>-->
                                                                </td>
                                                            </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div id="pr_decor" class="tab-pane">
                                            <div class="panel-body">

                                                <table class="table table-bordered table-hover ">
                                                    <thead>
                                                    <tr>
                                                        <th>Артикул декора</th>
                                                        <th v-for="(type,name) in facade_obj.types">{{type.name}}, {{$options.lang['currency']}} / {{$options.lang['square_units']}}</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(item,index) in facade_obj.prices_decor">
                                                        <td>
                                                            <input type="text" v-model="item.mat_code" class="form-control">
                                                        </td>
                                                        <td v-for="(type,name) in facade_obj.types">
                                                            <input v-model="item.price[name]" class="form-control" type="number" step="0.01" min="0">
                                                        </td>
                                                        <td>
                                                            <button @click="remove_decor_item(index)" type="button" class="btn-xs btn btn-w btn-danger btn-outline"><span class="glyphicon glyphicon-trash"></span></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td :colspan="types_length+2">
                                                            <button @click="add_decor_item()" type="button" class="btn btn-w-m btn-primary btn-xs btn-outline "><?php echo $lang_arr['add'] ?></button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>


                                            </div>
                                        </div>
                                        <div id="pr_fixed" class="tab-pane">
                                            <div class="panel-body">

                                                <div v-for="(item, index) in facade_obj.prices_fixed" class="row size_row mb-2">
                                                    <div class="col-3">
                                                        <label class="mb-0"><?php echo $lang_arr['width'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                        <input type="number" step="1" v-model="item.width" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="mb-0"><?php echo $lang_arr['height'] ?> (<?php echo $lang_arr['units'] ?>).</label>
                                                        <input type="number" step="1" v-model="item.height" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="mb-0"><?php echo $lang_arr['type'] ?></label>
                                                        <select v-model="item.type" class="form-control form-control-sm">
                                                            <option v-for="(type, key) in facade_obj.types" :value="key">{{type.name}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-3">
                                                        <label class="mb-0">Артикул декора</label>
                                                        <input v-model="item.mat_code" type="text" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="mb-0"><?php echo $lang_arr['name'] ?></label>
                                                        <input v-model="item.name" type="text" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="mb-0"><?php echo $lang_arr['code'] ?></label>
                                                        <input v-model="item.code" type="text" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="col-2">
                                                        <label class="mb-0"><?php echo $lang_arr['price'] ?></label>
                                                        <input v-model="item.price" type="number" step="0.01" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="remove_fixed_item">
                                                        <button @click="remove_fixed_item(index)" type="button" class="btn-xs btn btn-w btn-danger btn-outline"><span class="glyphicon glyphicon-trash"></span></button>
                                                    </div>

                                                </div>
                                                <div class=" row">
                                                    <div class="col-3">
                                                        <button @click="add_fixed_item()" type="button" class="btn btn-w-m btn-primary btn-outline btn-block"><?php echo $lang_arr['add'] ?></button>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div id="additions_tab" class="tab-pane">
                                <div class="panel-body">

                                    <pp_items ref="accessories_picker" @e_update="facade_obj.accessories = $event" :count_mode="true" :unselect="true" :selected_items="facade_obj.accessories" :controller="'accessories'"></pp_items>

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
                                    <a class="btn btn-white btn-sm" href="<?php echo $return_url ?>"><?php echo $lang_arr['cancel'] ?></a>
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


        <div v-bind:style="{ display: add_modal == 'true' ? 'none' : 'block'}" v-show="add_modal == true" v-bind:class="{ show: add_modal == true }" class="modal inmodal" id="facade_cat" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content animated ">
                    <div class="modal-header">
                        <button @click="hide_modal(); add_modal = false" type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title"><?php echo $lang_arr['choose_category'] ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="ibox">
                            <div class="ibox-content no-padding">
                                <ul class="category_select">
                                    <li v-for="(category,index) in categories">
                                        <button @click="set_category(index)" type="button" class="btn btn-default btn-block">
                                            {{category.name}}
                                        </button>
                                        <ul v-if="category.children">
                                            <li v-for="(subcat,ind) in category.children">
                                                <button @click="set_category(ind)" type="button" class="btn btn-default btn-block">
                                                    {{subcat.name}}
                                                </button>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="hide_modal(); add_modal = false" class="btn btn-white"><?php echo $lang_arr['cancel'] ?></button>
                    </div>
                </div>
            </div>
        </div>

        <div  v-bind:style="{ display: material_select_modal == 'true' ? 'none' : 'block'}" v-show="material_select_modal == true" v-bind:class="{ show: material_select_modal == true }" class="modal inmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content animated ">
                    <div class="modal-header">
                        <button @click="hide_modal(); material_select_modal = false; selected_add_mat = null" type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title"><?php echo $lang_arr['choose_material'] ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="ibox">
                            <div class="ibox-content no-padding">

                                <div class="row form-group">
                                    <div class="col-sm-6">
                                        <input class="form-control" placeholder="<?php echo $lang_arr['enter_name'] ?>" v-model="mat_search" type="text">
                                    </div>
                                    <div class="col-sm-6 m-b-xs">
                                        <v-select v-if="mat_cats.length" :reduce="name => name.id" v-model="filter_cat" placeholder="<?php echo $lang_arr['choose_category'] ?>" :options="computed_cats" label="name">
                                            <template v-slot:option="option">
                                                <span style="font-weight: bold;" v-show="option.children">{{ option.name }}</span>
                                                <span style="padding-left: 10px;" v-show="!option.children">{{ option.name }}</span>
                                            </template>
                                        </v-select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="mat_table table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th><?php echo $lang_arr['image'] ?></th>
                                            <th><?php echo $lang_arr['name'] ?></th>
                                            <th><?php echo $lang_arr['code'] ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr @click="set_material(item.id); hide_modal(); material_select_modal = false; " v-for="(item,index) in computed_mats">
                                            <td>
                                                <div v-if="item.map != '' && item.map">
                                                    <div v-if="item.map.indexOf('common_assets') > -1">
                                                        <img style="max-width: 50px" :src="item.map" alt="">
                                                    </div>
                                                    <div v-else>
                                                        <img style="max-width: 50px" :src="$options.acc_url+item.map" alt="">
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <div style="width: 50px; height: 50px" v-bind:style="{ background: item.color}"></div>
                                                </div>
                                            </td>
                                            <td>
                                                {{item.name}}
                                            </td>
                                            <td>
                                                {{item.code}}
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="hide_modal(); material_select_modal = false; selected_add_mat = null" class="btn btn-white"><?php echo $lang_arr['cancel'] ?></button>
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
                        <?php if (isset($common) && $common == 1) : ?>
                            <filemanager :type="'common'" ref="fileman" @select_file="sel_file($event)"></filemanager>
                        <?php else: ?>
                            <filemanager ref="fileman" @select_file="sel_file($event)"></filemanager>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                    </div>
                </div>
            </div>
        </div>


    </form>


    <div class="three_modal_wrapper">
        <div class="three_modal">
            <span class="close_three_modal glyphicon glyphicon-remove"></span>
            <div id="three_viewport">
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">


    <style>

        .cp {
            cursor: pointer;
        }

        .size_row {
            background: rgba(243, 243, 243, 0.5);
            padding-top: 15px;
            padding-bottom: 14px;
            margin-bottom: 10px;
            border: 1px solid #e7eaec;
            position: relative;
        }

        .size_act_btn {
            margin-top: 19px;
            height: 31px;
        }

        select.form-control.form-control-sm {
            height: 31px !important;
            padding: 0;
        }

        button.form-control.form-control-sm {
            height: 31px !important;
            padding: 0;
            cursor: pointer;
        }

        .alert {
            margin-bottom: 0;
            padding: 2px 10px;
        }

        .size_divider {
            margin-top: 10px;
        }

        .category_select {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: left;
        }

        .category_select button {
            text-align: left;
        }

        .category_select ul {
            list-style: none;
            margin: 0;
            padding: 0 0 0 30px;
        }


        .panel-body .tabs-container {
            padding-bottom: 25px;
        }


        .three_modal_wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(31, 31, 31, 0.81);
            z-index: 9999;
            display: none;
        }

        .three_modal {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            background: #ffffff;
        }

        .close_three_modal {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        #three_viewport {
            width: 100%;
            height: 100%;
        }

        #three_viewport_controls {
            position: absolute;
            background: #333333;
            left: 0;
            top: 0;
            color: #fff;
            padding: 5px;
        }

        #three_viewport_controls button, #three_viewport_controls input {
            color: #000;
        }

        #three_viewport_controls span {
            display: inline-block;
            width: 110px;
        }

        #three_viewport_controls input {
            display: inline-block;
            width: 80px;
            margin: 5px;
            padding: 0 5px;
        }

        #three_viewport_controls button {
            display: inline-block;
            width: 60px;
            margin: 5px;
        }

        #three_viewport_mode {
            position: absolute;
            left: 0;
            top: 120px;
            display: flex;
            flex-direction: column;
        }


    </style>
    <style>

        .icon_block {
            display: inline-block;
            position: relative;
            min-height: 125px;
        }

        .icon_block img {
            cursor: pointer;
        }

        .icon_block .open_file {
            cursor: pointer;
            position: absolute;
            right: -35px;
            top: 0;
            font-size: 25px;
            color: #1c84c6;
        }

        .icon_block .delete_file {
            bottom: 0;
            cursor: pointer;
            position: absolute;
            right: -30px;
            font-size: 25px;
            color: #ed5565;
        }

        .modal_new {
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

        .facades_panel_body {
            min-height: 500px;
        }


        .border_row {
            border: 1px solid #cacaca;
            padding: 10px 0 0 0;
        }

        .tabs-inner .active {

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

        .mat_table td {
            cursor: pointer;
        }

        .clrpckr {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            text-align: left;
            background: #f0f0f0;
            margin-top: 10px;
            font-weight: bold;
            color: #000;
            align-items: center;
        }

        .remove_fixed_item {
            position: absolute;
            right: 2px;
            top: 2px;
        }

    </style>


    <!---->
<?php //include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php';?>
    <!--<script>-->
    <!--    console.log(view_mode);-->
    <!--    view_mode = true;-->
    <!--</script>-->

    <script src="/common_assets/libs/vue.js"></script>
    <script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
    <script src="/common_assets/admin_js/vue/pagination.js"></script>
    <script src="/common_assets/admin_js/vue/filemanager2.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>

<!---->
    <script src="/common_assets/libs/three106.js" type="text/javascript"></script>
    <script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
    <script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
    <script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>
    <script src="/common_assets/libs/obj_export.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">
    <script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<!---->
<!--        <script src="/common_assets/js/v4/functions.js" type="text/javascript"></script>-->
<!--        <script src="/common_assets/js/v4/globals.js" type="text/javascript"></script>-->
<!--        <script src="/common_assets/js/v4/Parts.js" type="text/javascript"></script>-->
<!--        <script src="/common_assets/js/v4/Facade_new.js" type="text/javascript"></script>-->
<!--        <script src="/common_assets/js/v4/Model_cache.js" type="text/javascript"></script>-->
<!--        <script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>-->
<!---->
<!--        <script src="/common_assets/admin_js/vue/kitchen/facades_model.js"></script>-->


    <script src="/common_assets/admin_js/production/facades/facades_model.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/items_picker.php'); ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/image_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/category_picker.php');?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/model_picker.php');?>