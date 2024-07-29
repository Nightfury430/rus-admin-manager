<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['add_module'] ?></h2>
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


<form @submit="submit" id="sub_form" action="<?php echo site_url('modules/items_add_ajax/') ?><?php if (isset($id)) echo $id ?>">
    <input id="form_success_url" value="<?php echo site_url('catalog/items/modules') ?>" type="hidden">
    <input id="controller_name" value="<?php echo $controller_name?>" type="hidden">
    <input id="set_id" value="<?php echo $set_id?>" type="hidden">

    <?php if (isset($id)): ?>
        <input id="item_id" value="<?php echo $id ?>" type="hidden">
    <?php endif; ?>

    <div v-cloak class="wrapper wrapper-content  animated fadeInRight">

        <div v-if="errors.length" class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger error_msg">
                    <ul class="mb-0">
                        <li v-for="error in errors">{{error}}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params'] ?></a></li>
                        <li><a @click="resize_viewport(); rebuild_module();"  class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params'] ?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['sizes'] ?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-4"><?php echo $lang_arr['size_limit_tab'] ?></a></li>
                        <li><a @click="get_json" class="nav-link" data-toggle="tab" href="#tab-5"><?php echo $lang_arr['json_code'] ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">

                                <fieldset>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                                        <div class="col-sm-10">
                                            <v-select
                                                :clearable="false"
                                                :value="item.category"
                                                label="name"
                                                :options="$options.cat_ordered"
                                                :reduce="category => category.id"
                                                v-model="item.category"
                                                :key="item.category"
                                            >
                                                <template v-slot:selected-option="option">
                                                    <span v-if="option.parent != 0">{{categories_hash[option.parent].name}} / &nbsp;</span>{{ option.name }}
                                                </template>
                                                <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{categories_hash[option.parent].name}} / </span>{{ option.name }}
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
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order'] ?></label>
                                        <div class="col-sm-10">
                                            <input type="number" v-model="item.order" class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?> <br><small><?php echo $lang_arr['icon_descr'] ?></small></label>
                                        <div class="col-sm-5">

                                            <div class="icon_block">
                                                <img @click="$refs.icon_file.click()" style="max-width: 78px" :src="get_icon_src(item.icon_file)" alt="">
                                                <i @click="$refs.icon_file.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i v-if="item.icon_file != ''" @click="item.icon_file = ''; $refs.icon_file.value = ''" class="fa fa-trash delete_file"></i>
                                            </div>

                                            <div class="hidden">
                                                <input type="file" ref="icon_file" accept="image/jpeg,image/png,image/gif" @change="process_icon_file($event)">
                                            </div>

                                        </div>
                                        <div class="col-sm-5">

                                        </div>
                                    </div>


                                </fieldset>

                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-4">

                                        <div class="btn-group d-flex w-100 mb-2" role="group">
                                            <button @click="edit_mode = 0; set_design_mode();" :class="{'btn-outline': edit_mode != 0}" class="w-100 btn btn-success btn-xs" type="button">Шаблон</button>
                                            <button @click="edit_mode = 1; set_edit_mode();" :class="{'btn-outline': edit_mode != 1}" class="w-100 btn btn-success btn-xs" type="button">Редактировать</button>
                                        </div>

                                        <div v-if="edit_mode == 0">

                                            <div class="btn-group d-flex w-100 mb-2" role="group">
                                                <button @click="template_mode = 'template'" :class="{'btn-outline': template_mode != 'template'}" class="w-100 btn btn-success btn-xs" type="button"><?php echo $lang_arr['template'] ?></button>
                                                <button @click="template_mode = 'custom_template'" :class="{'btn-outline': template_mode != 'custom_template'}" class="w-100 btn btn-success btn-xs" type="button"><?php echo $lang_arr['custom_template'] ?></button>
                                            </div>

                                            <div class="btn-group d-flex w-100 mb-2" role="group">
                                                <button @click="template_category = 1" :class="{'btn-outline': template_category != 1}" class="w-100 btn btn-success btn-xs" type="button"><?php echo $lang_arr['kitchen_bottom_modules'] ?></button>
                                                <button @click="template_category = 2" :class="{'btn-outline': template_category != 2}" class="w-100 btn btn-success btn-xs" type="button"><?php echo $lang_arr['kitchen_top_modules'] ?></button>
                                                <button @click="template_category = 3" :class="{'btn-outline': template_category != 3}" class="w-100 btn btn-success btn-xs" type="button"><?php echo $lang_arr['kitchen_penals_modules'] ?></button>
                                            </div>

                                            <div v-if="template_mode == 'template'" class="modules_templates_wrapper">
                                                <div v-if="template_category == template.category" @click="select_template(template, 0)" class="module_template d-flex align-items-center mb-2" v-for="template in templates_list">
                                                    <div v-show="template_category == template.category" class="w-25">
                                                        <img class="img-fluid" :src="template.icon">
                                                    </div>
                                                    <div class="w-75 pl-2">
                                                        {{template.name}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="template_mode == 'custom_template'" class="modules_templates_wrapper">
                                                <div v-if="template_category == template.category" @click="select_template(template, 1)" class="module_template d-flex align-items-center mb-2" v-for="template in custom_templates_list">
                                                    <div class="w-25">
                                                        <img class="img-fluid" :src="template.icon">
                                                    </div>
                                                    <div class="w-75 pl-2">
                                                        {{template.name}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="edit_mode == 1">

                                            <div class="btn-group d-flex w-100 mb-2" role="group">
                                                <button @click="editor_tabs.mode = 1" :class="{'btn-outline': editor_tabs.mode != 1}" class="w-100 btn btn-success btn-xs" type="button">Общие</button>
                                                <button @click="editor_tabs.mode = 2" :class="{'btn-outline': editor_tabs.mode != 2}" class="w-100 btn btn-success btn-xs" type="button">Полки</button>
                                                <button v-if="check_cabinet_type()" @click="editor_tabs.mode = 3" :class="{'btn-outline': editor_tabs.mode != 3}" class="w-100 btn btn-success btn-xs" type="button">Двери</button>
                                                <button v-if="check_cabinet_type()" @click="editor_tabs.mode = 4" :class="{'btn-outline': editor_tabs.mode != 4}" class="w-100 btn btn-success btn-xs" type="button">Ящики</button>
                                                <button @click="editor_tabs.mode = 5; get_models_data()" :class="{'btn-outline': editor_tabs.mode != 5}" class="w-100 btn btn-success btn-xs" type="button">Модели</button>
                                            </div>


                                            <div v-if="editor_tabs.mode == 1">



                                                <div v-if="item && item.params && item.params.cabinet && item.params.cabinet.type == 'corner_90'">

                                                    <div class="form-group row">
                                                        <label class="col-12 col-form-label">Ориентация</label>
                                                        <div class="col-sm-12">
                                                            <select @change="change_90_or($event)" :value="get_90_or()" class="form-control col-12">
                                                                <option value="left">Левая</option>
                                                                <option value="right">Правая</option>
                                                            </select>
                                                        </div>

                                                    </div>



                                                </div>



                                                <div v-if="check_cabinet_type()">
                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Задняя стенка</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'cabinet', 'back_wall')" :checked="get_type_params('cabinet', null, 'back_wall')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Левая стенка</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'cabinet', 'left_wall')" :checked="get_type_params('cabinet', null, 'left_wall')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Правая стенка</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'cabinet', 'right_wall')" :checked="get_type_params('cabinet', null, 'right_wall')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Верхняя стенка</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'cabinet', 'top_wall')" :checked="get_type_params('cabinet', null, 'top_wall')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Нижняя стенка</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'cabinet', 'bottom_wall')" :checked="get_type_params('cabinet', null, 'bottom_wall')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div v-if="check_bottom()">
                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Столешница</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'tabletop', 'active')" :checked="get_type_params('tabletop', null, 'active')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div v-if="item.params && item.params.cabinet && item.params.cabinet.type != 'false_facade_facade'" v-show="item.params.tabletop.active != false">
                                                        <div class="form-group row">
                                                            <label class="col-12 col-form-label">Отступ столешницы сзади</label>
                                                            <input class="form-control col-8" @input="change_offset($event, 'front')" type="number" :value="get_type_params('tabletop', 'offset', 'front')">
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-12 col-form-label">Отступ столешницы спереди</label>
                                                            <input class="form-control col-8" @input="change_offset($event, 'back')" type="number" :value="get_type_params('tabletop', 'offset', 'back')">
                                                        </div>
                                                    </div>

                                                    <div  v-if="item.params && item.params.cabinet && item.params.cabinet.type == 'false_facade_facade'" v-show="item.params.tabletop.active != false">
                                                        <div class="form-group row">
                                                            <label class="col-12 col-form-label">Отступ столешницы сзади</label>
                                                            <input class="form-control col-8" @input="change_offset($event, 'back')" type="number" :value="get_type_params('tabletop', 'offset', 'back')">
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-12 col-form-label">Отступ столешницы спереди</label>
                                                            <input class="form-control col-8" @input="change_offset($event, 'front')" type="number" :value="get_type_params('tabletop', 'offset', 'front')">

                                                        </div>
                                                    </div>
                                                </div>

                                                <div v-if="check_cabinet_type()">


                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Варочная панель</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'hob', 'active')" :checked="get_type_params('hob', null, 'active')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-6 col-form-label">Мойка</label>
                                                        <div class="col-sm-6">
                                                            <label class="switch">
                                                                <input @change="change_cabinet($event, 'sink', 'active')" :checked="get_type_params('sink', null, 'active')" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>



                                                </div>





                                            </div>

                                            <div v-if="editor_tabs.mode == 2">
                                                <div class="tabs-container editor">
                                                    <div class="tabs-left">

                                                        <ul class="nav nav-tabs tabs-inner">
                                                            <li @click="editor_tabs.shelves = index" v-for="(shelve, index) in item.params.shelves">
                                                                <a v-bind:class="{ active: editor_tabs.shelves == index }" class="nav-link">{{index+1}}</a>
                                                            </li>
                                                            <li>
                                                                <button @click="add_part('shelves')" type="button" class="btn btn-primary btn-block btn-outline">+</button>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                            <div v-bind:class="{ active: editor_tabs.shelves == index }" v-show="editor_tabs.shelves == index" v-for="(shelve, index) in item.params.shelves" class="tab-pane">
                                                                <div class="panel-body facades_panel_body container-fluid">

                                                                    <div class="rel_div">
                                                                        <button @click="delete_part(index, 'shelves')" title="<?php echo $lang_arr['delete'] ?>" style="position: absolute; right: -25px; top:0; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                        </button>


                                                                        <div v-if="check_cabinet_type()" class="form-group row">
                                                                            <label class="col-12 col-form-label">Ориентация</label>
                                                                            <select @change="change_shelves($event, 'shelves', index, 'orientation', false)" :value="get_type_params('shelves', index, 'orientation')" class="form-control col-8">
                                                                                <option value="horizontal">Гор</option>
                                                                                <option value="vertical">Верт</option>
                                                                            </select>
                                                                            <input type="text" style="opacity: 0" class="form-control col-1" value="100">
                                                                        </div>

                                                                        <div v-if="check_cabinet_type()" class="form-group row">
                                                                            <label class="col-12 col-form-label">Ширина</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'shelves', index, 'width', true)" type="number" :value="parseInt(get_type_params('shelves', index, 'width'))">
                                                                            <select class="form-control col-4" :value="check_percent(get_type_params('shelves', index, 'width'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Позиция по вертикали</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'shelves', index, 'starting_point_y', true)" type="number" :value="parseInt(get_type_params('shelves', index, 'starting_point_y'))">
                                                                            <select @change="change_percent($event, index, 'starting_point_y', 'shelves')" class="form-control col-4" :value="check_percent(get_type_params('shelves', index, 'starting_point_y'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div v-if="check_cabinet_type()" class="form-group row">
                                                                            <label class="col-12 col-form-label">Позиция по горизонтали</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'shelves', index, 'starting_point_x', true)" type="number" :value="parseInt(get_type_params('shelves', index, 'starting_point_x'))">
                                                                            <select class="form-control col-4" :value="check_percent(get_type_params('shelves', index, 'starting_point_x'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Отступ сверху</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'shelves', index, 'position_offset_top', false)" type="number" :value="parseInt(get_type_params('shelves', index, 'position_offset_top'))">
                                                                            <input class="form-control col-4" type="text" disabled value=" <?php echo $lang_arr['units'] ?>">
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Отступ снизу</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'shelves', index, 'position_offset_bottom', false)" type="number" :value="parseInt(get_type_params('shelves', index, 'position_offset_bottom'))">
                                                                            <input class="form-control col-4" type="text" disabled value=" <?php echo $lang_arr['units'] ?>">
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="editor_tabs.mode == 3">
                                                <div class="tabs-container editor">

                                                    <div class="tabs-left">

                                                        <ul class="nav nav-tabs tabs-inner">

                                                            <li @click="editor_tabs.doors = index" v-for="(item, index) in item.params.doors">
                                                                <a v-bind:class="{ active: editor_tabs.doors == index }" class="nav-link">{{index+1}}</a>
                                                            </li>
                                                            <li>
                                                                <button @click="add_part('doors')" type="button" class="btn btn-primary btn-block btn-outline">+</button>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                            <div v-bind:class="{ active: editor_tabs.doors == index }" v-show="editor_tabs.doors == index" v-for="(item, index) in item.params.doors" class="tab-pane">
                                                                <div class="panel-body facades_panel_body container-fluid">

                                                                    <div class="rel_div">
                                                                        <button @click="delete_part(index, 'doors')" title="<?php echo $lang_arr['delete'] ?>" style="position: absolute; right: -25px; top:0; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                        </button>


                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Тип открывания</label>
                                                                            <select @change="change_shelves($event, 'doors', index, 'type', false)" :value="get_type_params('doors', index, 'type')" class="form-control col-8">
                                                                                <option v-for="opt in $options.door_types" :value="opt.type">{{opt.name}}</option>
                                                                            </select>
                                                                            <input type="text" style="opacity: 0" class="form-control col-1" value="100">
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <div class="col-6 pl">
                                                                                <label class="col-form-label">Тип фасада</label>
                                                                                <select @change="change_shelves($event, 'doors', index, 'style', false)" :value="get_type_params('doors', index, 'style')" class="form-control">
                                                                                    <option v-for="opt in $options.facade_styles" :value="opt.type">{{opt.name}}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6 pl pr">
                                                                                <label class="col-form-label">Цвет фасада</label>
                                                                                <select @change="change_shelves($event, 'doors', index, 'group', false)" :value="get_type_params('doors', index, 'group')" class="form-control">
                                                                                    <option v-for="opt in $options.facade_groups" :value="opt.val">{{opt.name}}</option>
                                                                                </select>
                                                                            </div>

                                                                            <!--                                                                            <input type="text" style="opacity: 0" class="form-control col-1" value="100">-->
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Положение ручки</label>
                                                                            <select @change="change_shelves($event, 'doors', index, 'handle_position', false)" :value="get_type_params('doors', index, 'handle_position')" class="form-control col-8">
                                                                                <option v-for="opt in $options.handles_position" :value="opt.val">{{opt.name}}</option>
                                                                            </select>
                                                                            <input type="text" style="opacity: 0" class="form-control col-1" value="100">
                                                                        </div>



                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Ширина</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'doors', index, 'width', true)" type="number" :value="parseInt(get_type_params('doors', index, 'width'))">
                                                                            <select @change="change_percent($event, index, 'width', 'doors')" class="form-control col-4" :value="check_percent(get_type_params('doors', index, 'width'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Высота</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'doors', index, 'height', true)" type="number" :value="parseInt(get_type_params('doors', index, 'height'))">
                                                                            <select @change="change_percent($event, index, 'height', 'doors')" class="form-control col-4" :value="check_percent(get_type_params('doors', index, 'height'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>


                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Позиция по вертикали</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'doors', index, 'starting_point_y', true)" type="number" :value="parseInt(get_type_params('doors', index, 'starting_point_y'))">
                                                                            <select @change="change_percent($event, index, 'starting_point_y', 'doors')" class="form-control col-4" :value="check_percent(get_type_params('doors', index, 'starting_point_y'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div v-if="check_cabinet_type()" class="form-group row">
                                                                            <label class="col-12 col-form-label">Позиция по горизонтали</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'doors', index, 'starting_point_x', true)" type="number" :value="parseInt(get_type_params('doors', index, 'starting_point_x'))">
                                                                            <select @change="change_percent($event, index, 'starting_point_x', 'doors')" class="form-control col-4" :value="check_percent(get_type_params('doors', index, 'starting_point_x'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Отступ сверху</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'doors', index, 'offset_top', false)" type="number" :value="parseInt(get_type_params('doors', index, 'offset_top'))">
                                                                            <input class="form-control col-4" type="text" disabled value=" <?php echo $lang_arr['units'] ?>">
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Отступ снизу</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'doors', index, 'offset_bottom', false)" type="number" :value="parseInt(get_type_params('doors', index, 'offset_bottom'))">
                                                                            <input class="form-control col-4" type="text" disabled value=" <?php echo $lang_arr['units'] ?>">
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="editor_tabs.mode == 4">
                                                <div class="tabs-container editor">

                                                    <div class="tabs-left">

                                                        <ul class="nav nav-tabs tabs-inner">

                                                            <li @click="editor_tabs.lockers = index" v-for="(item, index) in item.params.lockers">
                                                                <a v-bind:class="{ active: editor_tabs.lockers == index }" class="nav-link">{{index+1}}</a>
                                                            </li>
                                                            <li>
                                                                <button @click="add_part('lockers')" type="button" class="btn btn-primary btn-block btn-outline">+</button>
                                                            </li>
                                                        </ul>

                                                        <div class="tab-content">
                                                            <div v-bind:class="{ active: editor_tabs.lockers == index }" v-show="editor_tabs.lockers == index" v-for="(item, index) in item.params.lockers" class="tab-pane">
                                                                <div class="panel-body facades_panel_body container-fluid">

                                                                    <div class="rel_div">
                                                                        <button @click="delete_part(index, 'lockers')" title="<?php echo $lang_arr['delete'] ?>" style="position: absolute; right: -25px; top:0; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                        </button>


                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Тип ящика</label>
                                                                            <select @change="change_shelves($event, 'lockers', index, 'inner', false)" :value="get_type_params('lockers', index, 'inner')" class="form-control col-8">
                                                                                <option v-bind:value="0">Обычный</option>
                                                                                <option v-bind:value="1">Внутренний</option>
                                                                            </select>
                                                                            <input type="text" style="opacity: 0" class="form-control col-1" value="100">
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <div class="col-6 pl">
                                                                                <label class="col-form-label">Тип фасада</label>
                                                                                <select @change="change_shelves($event, 'lockers', index, 'style', false)" :value="get_type_params('lockers', index, 'style')" class="form-control">
                                                                                    <option v-for="opt in $options.facade_styles" :value="opt.type">{{opt.name}}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6 pl pr">
                                                                                <label class="col-form-label">Цвет фасада</label>
                                                                                <select @change="change_shelves($event, 'lockers', index, 'group', false)" :value="get_type_params('lockers', index, 'group')" class="form-control">
                                                                                    <option v-for="opt in $options.facade_groups" :value="opt.val">{{opt.name}}</option>
                                                                                </select>
                                                                            </div>

                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Ручка</label>
                                                                            <select @change="change_shelves($event, 'lockers', index, 'no_handle_forced', false)" :value="get_type_params('lockers', index, 'no_handle_forced')" class="form-control col-8">
                                                                                <option v-bind:value="false">Да</option>
                                                                                <option v-bind:value="true">Нет</option>
                                                                            </select>
                                                                            <input type="text" style="opacity: 0" class="form-control col-1" value="100">
                                                                        </div>


                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Ширина</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'lockers', index, 'width', true)" type="number" :value="parseInt(get_type_params('lockers', index, 'width'))">
                                                                            <select @change="change_percent($event, index, 'width', 'lockers')" class="form-control col-4" :value="check_percent(get_type_params('lockers', index, 'width'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Высота</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'lockers', index, 'height', true)" type="number" :value="parseInt(get_type_params('lockers', index, 'height'))">
                                                                            <select @change="change_percent($event, index, 'height', 'lockers')" class="form-control col-4" :value="check_percent(get_type_params('lockers', index, 'height'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>


                                                                        <div class="form-group row">
                                                                            <label class="col-12 col-form-label">Позиция по вертикали</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'lockers', index, 'starting_point_y', true)" type="number" :value="parseInt(get_type_params('lockers', index, 'starting_point_y'))">
                                                                            <select @change="change_percent($event, index, 'starting_point_y', 'lockers')" class="form-control col-4" :value="check_percent(get_type_params('lockers', index, 'starting_point_y'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>

                                                                        <div v-if="check_cabinet_type()" class="form-group row">
                                                                            <label class="col-12 col-form-label">Позиция по горизонтали</label>
                                                                            <input class="form-control col-8" @input="change_shelves($event,'lockers', index, 'starting_point_x', true)" type="number" :value="parseInt(get_type_params('lockers', index, 'starting_point_x'))">
                                                                            <select @change="change_percent($event, index, 'starting_point_x', 'lockers')" class="form-control col-4" :value="check_percent(get_type_params('lockers', index, 'starting_point_x'))">
                                                                                <option value="%">%</option>
                                                                                <option value="mm"><?php echo $lang_arr['units'] ?></option>
                                                                            </select>
                                                                        </div>


                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="editor_tabs.mode == 5">

                                                <div v-show="select_model == 1">
                                                    <button @click="select_model = 0" type="button" class="btn btn-primary btn-block btn-outline">Назад</button>

                                                    <div class="row">
                                                        <div @click="add_model(item.id); select_model = 0" v-for="item in $options.builtin.items" class="col-6">
                                                            <img class="img-fluid" :src="item.icon" alt="">
                                                            <p class="text-center">{{item.name}}</p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div v-show="select_model == 0">
                                                    <div class="tabs-container editor">

                                                        <div class="tabs-left">

                                                            <ul class="nav nav-tabs tabs-inner">
                                                                <li @click="editor_tabs.builtin = index" v-for="(built_in_models, index) in item.params.built_in_models">
                                                                    <a v-bind:class="{ active: editor_tabs.builtin == index }" class="nav-link">{{index+1}}</a>
                                                                </li>
                                                                <li>
                                                                    <button @click="select_model = 1" type="button" class="btn btn-primary btn-block btn-outline">+</button>
                                                                </li>

                                                            </ul>

                                                            <div class="tab-content">

                                                                <div v-bind:class="{ active: editor_tabs.builtin == index }" v-show="editor_tabs.builtin == index" v-for="(item, index) in item.params.built_in_models" class="tab-pane">
                                                                    <div class="panel-body facades_panel_body container-fluid">


                                                                        <div class="rel_div">
                                                                            <button @click="delete_part(index, 'built_in_models')" title="<?php echo $lang_arr['delete'] ?>" style="position: absolute; right: -25px; top:0; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                                <span class="glyphicon glyphicon-trash"></span>
                                                                            </button>




                                                                            <div class="form-group row">
                                                                                <label class="col-12 col-form-label">Позиция по вертикали</label>
                                                                                <input class="form-control col-8" @input="change_shelves($event,'built_in_models', index, 'pY', false)" type="number" :value="parseInt(get_type_params('built_in_models', index, 'pY'))">
                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-12 col-form-label">Позиция по горизонтали</label>
                                                                                <input class="form-control col-8" @input="change_shelves($event,'built_in_models', index, 'pX', false)" type="number" :value="parseInt(get_type_params('built_in_models', index, 'pX'))">

                                                                            </div>
                                                                            <div class="form-group row">
                                                                                <label class="col-12 col-form-label">Позиция по глубине</label>
                                                                                <input class="form-control col-8" @input="change_shelves($event,'built_in_models', index, 'pZ', false)" type="number" :value="parseInt(get_type_params('built_in_models', index, 'pZ'))">

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


                                    </div>

                                    <div class="col-8">
                                        <div id="bplanner_app">
                                            <div id="viewport">

                                            </div>

                                            <div v-if="edit_mode == 1" class="make_icon btn-group">
                                                <button data-toggle="dropdown" class="btn btn-sm btn-success dropdown-toggle" aria-expanded="false">Сделать иконку</button>
                                                <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 33px; left: 0px; will-change: top, left;">
                                                    <li @click="make_icon(false)" class="dropdown-item">Красная</li>
                                                    <li @click="make_icon(true)" class="dropdown-item">Серая</li>
                                                </ul>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-3" class="tab-pane">
                            <div class="panel-body models_list">

                                <draggable class="" v-model="item.params.variants" v-bind="dragOptions" handle=".handle" group="parent" :move="on_drag" @start="drag = true" @end="drag = false">
                                    <div v-for="(variant,index) in item.params.variants" :key="variant.id" >

                                        <div class="d-flex flex-row flex-wrap form-group draggable_panel pr-5 pl-2">
                                            <i class="fa fa-align-justify handle"></i>
                                            <div class="col-4 py-2">
                                                <label><?php echo $lang_arr['name'] ?></label>
                                                <input class="form-control" v-model="variant.name" type="text">
                                            </div>
                                            <div class="col-4 py-2">
                                                <label><?php echo $lang_arr['code'] ?></label>
                                                <input class="form-control" v-model="variant.code" type="text">
                                            </div>
                                            <div class="col-4 py-2">
                                                <label class="hidden"><?php echo $lang_arr['price'] ?></label>
                                                <input class="hidden form-control" v-model="variant.price" type="text">
                                            </div>

                                            <div class="col-4 py-2">
                                                <label><?php echo $lang_arr['width'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                <input class="form-control" v-model="variant.width" type="number">
                                            </div>
                                            <div class="col-4 py-2">
                                                <label><?php echo $lang_arr['corpus_height'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                <input class="form-control" v-model="variant.height" type="number">
                                            </div>

                                            <div class="col-4 py-2">
                                                <!--                                            --><?php //if($top_category == 1):?>
                                                <!--                                                <label>--><?php //echo $lang_arr['depth_tabletop']?><!-- (--><?php //echo $lang_arr['units']?><!--)</label>-->
                                                <!--                                            --><?php //else:?>
                                                <label><?php echo $lang_arr['depth'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                <!--                                            --><?php //endif;?>
                                                <input class="form-control" v-model="variant.depth" type="number">
                                            </div>

                                            <div v-if="item && item.params && item.params.cabinet && item.params.cabinet.type == 'corner_straight'" class="col-4 py-2">
                                                <label><?php echo $lang_arr['corner_straight_add_width'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                <input class="form-control" v-model="variant.corner_straight_wall_width" type="number">
                                            </div>

                                            <div v-if="item && item.params && item.params.cabinet && item.params.cabinet.type == 'corner_90'" class="col-4 py-2">
                                                <label><?php echo $lang_arr['width'] ?> 2 (<?php echo $lang_arr['units'] ?>)</label>
                                                <input @input="check_w2(variant.width, variant.width2)" class="form-control" v-model="variant.width2" type="number">
                                            </div>

                                            <button title="<?php echo $lang_arr['delete'] ?>" @click="show_swal(index)" style="position: absolute; right: 10px; top:10px; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>

                                            <div class="default_size" style="position: absolute; right: 0; bottom: 10px; transform: scale(0.6)">
                                                <label title="<?php echo $lang_arr['default'] ?>" class="switch">
                                                    <input @change="process_default(index)" v-bind:true-value="1" v-bind:false-value="0" v-model="variant.default" type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </draggable>


                                <div class="row form-group">
                                    <div class="col-12">
                                        <button @click="add_variant()" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="tab-4" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p><?php echo $lang_arr['size_limit'] ?></p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_min_width'] ?></label>
                                    <div class="col-sm-3">
                                        <input v-model="sizes_limit.min_width" type="number" min="0" class="form-control">
                                    </div>
                                    <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_max_width'] ?></label>
                                    <div class="col-sm-3">
                                        <input v-model="sizes_limit.max_width" type="number" min="0" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_min_height'] ?></label>
                                    <div class="col-sm-3">
                                        <input v-model="sizes_limit.min_height" type="number" class="form-control">
                                    </div>
                                    <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_max_height'] ?></label>
                                    <div class="col-sm-3">
                                        <input v-model="sizes_limit.max_height" type="number" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_min_depth'] ?></label>
                                    <div class="col-sm-3">
                                        <input v-model="sizes_limit.min_depth" type="number" class="form-control">
                                    </div>
                                    <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_max_depth'] ?></label>
                                    <div class="col-sm-3">
                                        <input v-model="sizes_limit.max_depth" type="number" class="form-control">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="tab-5" class="tab-pane">
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
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items_common/modules_sets_modules/') . $set_id ?>"><?php echo $lang_arr['cancel'] ?></a>
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
                    <button type="button" class="btn btn-white" data-dismiss="modal">Отмена</button>

                </div>
            </div>
        </div>
    </div>

</form>

<div id="lang_phrases" class="hidden">
    <div id="error_no_template_selected"><?php echo $lang_arr['error_no_template_selected'] ?></div>
    <div id="error_no_variants"><?php echo $lang_arr['error_no_variants'] ?></div>
    <div id="error_not_an_image"><?php echo $lang_arr['error_not_an_image'] ?></div>
    <div id="error_no_category_selected"><?php echo $lang_arr['error_no_category_selected'] ?></div>
    <div id="lang_no"><?php echo $lang_arr['no'] ?></div>
    <div id="mod_json_error"><?php echo $lang_arr['json_error'] ?></div>
</div>


<style>

    .make_icon{
        position: absolute;
        top: 5px;
        right: 20px;
        z-index: 9;
    }

    .editor {
        font-size: 13px;
    }

    .editor .panel-body {
        padding: 4px 30px 12px 30px;

    }

    .editor .rel_div {
        position: relative;
    }

    .editor .form-group {
        margin-bottom: 0.5em;
    }

    .editor .col-form-label {
        padding-top: calc(.275em);
        padding-bottom: calc(.175em);
        padding-left: 0;
        margin-bottom: 0;
        font-size: 1em;
        line-height: 1.5;
    }

    .editor .form-control {
        font-size: 1em;
        height: auto !important;
        padding: 0.2em 0.4em;
    }

    .editor select.form-control {
        height: auto !important;
        font-size: 1em;
        padding: 0.2em 0;

    }

    .editor .del_btn{
        position: absolute;
        right: -25px;
        top: 0px;
        padding: 5px 5px 5px 4px;
        z-index: 10;
    }

    select.form-control {
        height: calc(2.25rem + 2px) !important;
    }

    .editor .pl{
        padding-left: 0;
    }
    .editor .pr{
        padding-right: 0;
    }

    .modules_templates_wrapper {
        overflow: auto;
        max-height: 560px;
        cursor: pointer;
    }

    #viewport {
        max-width: 100%;
        position: relative;
        height: 100%;
        max-height: 500px;
        min-height: 500px;

    }

    #bplanner_app {
        min-width: 100%;
        min-height: 500px;
    }

    label {
        margin-bottom: 2px;
    }

    .sp-replacer {
        margin: 0;
        overflow: hidden;
        cursor: pointer;
        padding: 4px 15px 4px 4px;
        display: block;
        zoom: 1;
        border: solid 1px #e5e6e7;
        background: #fff;
        color: #333;
        vertical-align: middle;
        position: relative;
    }

    .sp-replacer:hover, .sp-replacer.sp-active {
        border-color: #e5e6e7;
        color: #111;
    }

    .sp-preview {
        position: relative;
        width: 100%;
        height: 25px;
        border: solid 1px #222;
        z-index: 0;
    }

    .sp-dd {
        padding: 2px 0;
        height: 16px;
        line-height: 16px;
        font-size: 10px;
        position: absolute;
        right: 2px;
        top: 7px;
    }

    .cp {
        cursor: pointer;
    }

    .size_row {
        background: rgba(243, 243, 243, 0.5);
        padding-top: 15px;
        padding-bottom: 14px;
        margin-bottom: 10px;
    }

    .size_act_btn {
        margin-top: 30px;
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


</style>
<style>

    .icon_block {
        display: inline-block;
        position: relative;
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

    .radial_menu {
        display: none !important;
    }

    .handle{
        position: absolute;
        z-index: 9;
        top: 11px;
        left: 3px;
        font-size: 15px!important;
    }

</style>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php'; ?>
<script>
    console.log(view_mode)
    view_mode = true;
    //view_path = "<?php //echo $this->config->item('const_path')?>//"
</script>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/filemanager.js"></script>

<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">


<script src="/common_assets/admin_js/vue/kitchen/modules_fs.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>