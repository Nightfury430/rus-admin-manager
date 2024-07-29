<div style="display: none">
    <div id="configurator_template">
        <div v-if="show==1" class="menu configurator">

            <ul class="tab_menu_h object_menu_params_menu">
                <li v-show="tab_variables" :class="{active: tab == 'variables'}" @click="change_tab('variables')">Переменные</li>
                <li v-show="tab_computed" :class="{active: tab == 'computed'}" v-show="1 == 0" @click="change_tab('computed')">Вычисляемые</li>
                <li v-show="tab_tree" :class="{active: tab == 'tree'}" @click="change_tab('tree')">Конструктор</li>
                <li v-show="tab_variants" v-if="mode == 'back'" :class="{active: tab == 'variants'}" @click="change_tab('variants')">Размеры</li>
                <li v-show="tab_common" :class="{active: tab == 'common'}" @click="change_tab('common')">Основные</li>
                <li v-show="tab_materials" :class="{active: tab == 'materials'}" @click="change_tab('materials')">Материалы</li>
                <li v-show="tab_json" :class="{active: tab == 'json'}" @click="change_tab('json')">JSON</li>
                <li v-show="tab_events" :class="{active: tab == 'events'}" @click="change_tab('events')">События</li>

            </ul>
            <div v-if="tab == 'common'">
                <select @change="set_group($event)" :value="get_group()" class="p_input">
                    <option value="top">Верхний</option>
                    <option value="bottom">Нижний</option>
                </select>

                <select @change="set_drag_mode($event)" :value="get_drag_mode()" class="p_input">
                    <option value="common">По стенам/полу</option>
                    <option value="surface">По всем поверхностям</option>
                </select>

            </div>
            <div v-if="tab == 'variables'">
                <div class="rounded shadow p10" v-show="var_add_vis == 1">

                    <div class="p_input_group flex_row flex_vcenter">
                        <div class="w50"><?php echo $lang_arr['name'] ?></div>
                        <div class="w50">
                            <input @input="var_change_name" v-model="variable.name" type="text" >
                        </div>
                    </div>

                    <div class="p_input_group flex_row flex_vcenter">
                        <div class="w50"><?php echo $lang_arr['key'] ?></div>
                        <div class="w50">
                            <input v-model="variable.key" type="text">
                        </div>
                    </div>

                    <div class="p_input_group flex_row flex_vcenter">
                        <div class="w50">Выводить в параметры модуля</div>
                        <div class="w50">
                            <label class="switch">
                                <input :true-value="1" :false-value="0" v-model="variable.editable" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="p_input_group flex_row flex_vcenter">
                        <div class="w50">Локальная переменная</div>
                        <div class="w50">
                            <label class="switch">
                                <input :true-value="1" :false-value="0" v-model="variable.local" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="p_input_group flex_row flex_vcenter">
                        <div class="w50">Значения в типовых размерах</div>
                        <div class="w50">
                            <label class="switch">
                                <input :true-value="1" :false-value="0" v-model="variable.variants" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="p_input_group flex_row flex_vcenter">
                        <div class="w50"><?php echo $lang_arr['type'] ?></div>
                        <div class="w50">
                            <select @change="var_change_type()" v-model="variable.type" class="p_input">
                                <option value="number">Число</option>
                                <option value="select">Выбор из списка</option>
                                <option value="boolean">Да/нет</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="variable.type == 'number'">
                        <div class="p_input_group flex_row flex_vcenter">
                            <div class="w50">Значение по умолчанию</div>
                            <div class="w50">
                                <input v-model="variable.value" type="number" step="0.1">
                            </div>
                        </div>

                        <div class="p_input_group flex_row flex_vcenter">
                            <div class="w50">Минимальное значение</div>
                            <div class="w50">
                                <input v-model="variable.params.min" type="number" step="0.1">
                            </div>
                        </div>

                        <div class="p_input_group flex_row flex_vcenter">
                            <div class="w50">Максимальное значение</div>
                            <div class="w50">
                                <input v-model="variable.params.max" type="number" step="0.1">
                            </div>
                        </div>

                        <div class="p_input_group flex_row flex_vcenter">
                            <div class="w50">Шаг</div>
                            <div class="w50">
                                <input v-model="variable.params.step" type="number" step="0.1">
                            </div>
                        </div>
                    </div>

                    <div v-if="variable.type == 'select'">
                        <div>
                            <div class="p_input_group flex_row">
                                <div class="w25">Значения</div>
                                <div class="w75">
                                    <div v-for="(opt, index) in variable.params.options">
                                        <div class="p_input_group flex_row flex_vcenter">
                                            <div style="margin-right: 5px" class="w50">
                                                <label class="mb0">Название</label>
                                                <input type="text" v-model="opt.name">
                                            </div>
                                            <div style="margin-left: 5px; margin-right: 5px" class="w50">
                                                <label class="mb0">Значение</label>
                                                <input type="text" v-model="opt.value">
                                            </div>
                                            <div>
                                                <button @click="var_remove_option(index)" style="margin-top: 18px;" type="button" class="btn btn-xs btn-danger btn-outline"><i class="icon icon_trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align: right">
                                        <label>&nbsp;</label>
                                        <button type="button" @click="var_add_option()" class="btn btn-sm btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="p_input_group flex_row flex_vcenter">
                            <div class="w50">Значение по умолчанию</div>
                            <div class="w50">
                                <select v-model="variable.value" class="p_input">
                                    <option :value="opt.value" v-for="opt in variable.params.options">{{opt.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div v-if="variable.type == 'boolean'">
                        <div class="p_input_group flex_row flex_vcenter">
                            <div class="w50">Значение по умолчанию</div>
                            <div class="w50">
                                <label class="switch">
                                    <input :true-value="1" :false-value="0" v-model="variable.value" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="button" @click="hide_var_add()" class="btn btn-sm btn-white"><?php echo $lang_arr['cancel'] ?></button>
                        <button type="button" @click="add_variable()" class="btn btn-sm btn-primary"><?php echo $lang_arr['add'] ?></button>
                    </div>
                </div>
                <div class="p10" v-show="var_add_vis == 0">
                    <table class="mb5 conf_table">
                        <thead>
                            <tr>
                                <th>Ключ</th>
                                <th>Название</th>
                                <th>Значение</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-if="!item.global" v-for="item in variables">
                            <td>{{item.key}}</td>
                            <td>{{item.name}}</td>
                            <td>
                                <div v-if="item.type == 'select'">
                                    <select v-model="item.value" @change="change_variable(item.key, item.value, item.type)" >
                                        <option :value="opt.value" v-for="opt in item.params.options">{{opt.name}}</option>
                                    </select>
                                </div>
                                <div v-if="item.type == 'number'">
                                    <input step="0.1" v-model="item.value" type="number" @input="change_variable(item.key, item.value, item.type)">
                                </div>
                                <div v-if="item.type == 'boolean'">
                                    <label style="scale: 0.8" class="switch">
                                        <input :true-value="1" :false-value="0" @change="change_variable(item.key, item.value, item.type)" v-model="item.value" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>

                            </td>
                            <td>
                                <i @click="show_var_add(item)" class="btn btn-sm btn-success btn-outline icon icon_settings"></i>
                                <i @click="remove_variable(item)" class="btn btn-sm btn-danger btn-outline icon icon_trash"></i>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                    <div style="text-align: left">
                        <button class="btn btn-xs btn-outline btn-primary" @click="show_var_add()" type="button">Добавить</button>
                    </div>
                </div>
            </div>
            <div v-if="tab == 'computed'">
                <value_input :var_list="var_list"></value_input>
            </div>
            <div v-if="tab == 'materials'">
                <div v-if="material.key != '__no_mat'" style="padding-right: 50px" class="rounded shadow p10 mb5 relative" v-for="(material, ind) in cab_materials">
                    <div class="flex_row flex_wrap flex_vcenter">
                        <div>{{material.name}}, Группа {{material.group}} ({{material.key}}::{{material.group}})</div>
                    </div>
                    <div class="flex_row flex_wrap flex_vcenter">


                        <div class="w33 px5">
                            <div class="p_input_group">
                                <label class="mb0">Название</label>
                                <input @input="set_material_param(ind, 'label', material.label, material.group)" v-model="material.label" type="text">
                            </div>
                        </div>
                        <div class="w33 px5">
                            <div class="p_input_group">
                                <label class="mb0">Разрешить выбор</label>
                                <div>
                                    <label class="sc_08 switch">
                                        <input @change="set_material_param(ind, 'fixed', material.fixed, material.group)" :true-value="0" :false-value="1" v-model="material.fixed" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
<!--                        <div style="display: none" class="w33 px5">-->
<!--                            <div class="p_input_group">-->
<!--                                <label class="mb0">По умолчанию</label>-->
<!--                                <div>-->
<!--                                    <label class="sc_08 switch">-->
<!--                                        <input @change="set_material_param(ind, 'default', material.default, material.group)" :true-value="1" :false-value="0" v-model="material.default" type="checkbox">-->
<!--                                        <span class="slider round"></span>-->
<!--                                    </label>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                    <div v-if="material.key != '__tabletop'" class="flex_row flex_wrap flex_vcenter">
                        <div v-if="material.custom == 1">
                            <pp_items ref="items_picker" @e_update="set_material_param(ind, 'id', $event, material.group)" :unselect="true" :selected_item="material.id" :categories="material.categories" :controller="'materials'"></pp_items>
                        </div>
                        <div v-else>
                            <pp_items ref="items_picker" @e_update="set_material_param(ind, 'id', $event, material.group)" :unselect="true" :selected_item="material.id" :categories="get_mat_cats(material)" :controller="'materials'"></pp_items>
                        </div>
                    </div>
                    <div class="variant_controls sc_08">
<!--                        <button v-if="cab_materials.length > 1" class="v_del btn btn-sm btn-outline btn-danger" @click="remove_variant(ind)" type="button"><i class="icon icon_trash"></i></button>-->
                        <button v-if="ind > 0" class="v_up btn btn-sm btn-outline btn-success" @click="material_up(ind)" type="button"><i class="icon icon_expand_up"></i></button>
                        <button v-if="ind != cab_materials.length - 1" class="v_down btn btn-sm btn-outline btn-success" @click="material_down(ind)" type="button"><i class="icon icon_expand_down"></i></button>
                    </div>

                </div>
            </div>
            <div v-show="tab == 'variants'">
                <div>
                    <div class="p_input_group flex_row flex_vcenter mt5">
                        <div class="w50">Размер по умолчанию</div>
                        <div class="w50">
                            <select @change="change_def_variant" v-model="variant" class="p_input">
                                <option :value="index" v-for="(opt,index) in variants">{{opt.code}} {{opt.name}} {{opt.size.x}}x{{opt.size.y}}x{{opt.size.z}}</option>
                            </select>
                        </div>
                    </div>

                    <div style="padding-right: 50px" class="rounded shadow p10 mb5 relative" v-for="(variant, ind) in variants">
                        <div class="flex_row flex_wrap flex_vcenter">
                            <div class="w33 px5">
                                <div class="p_input_group">
                                    <label class="mb0">Название</label>
                                    <input @input="change_variant(ind)" v-model="variant.name" type="text">
                                </div>
                            </div>
                            <div class="w33 px5">
                                <div class="p_input_group">
                                    <label class="mb0">Артикул</label>
                                    <input @input="change_variant(ind)" v-model="variant.code" type="text">
                                </div>
                            </div>
                            <div class="w33 px5">
                                <div class="p_input_group">
                                    <label class="mb0">Цена</label>
                                    <input @input="change_variant(ind)" v-model="variant.price" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="flex_row flex_wrap flex_vcenter">
                            <div class="w33 px5">
                                <div class="p_input_group">
                                    <label class="mb0">Ширина, мм</label>
                                    <input @input="change_variant(ind)" v-model="variant.size.x" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="w33 px5">
                                <div class="p_input_group">
                                    <label class="mb0">Высота, мм</label>
                                    <input @input="change_variant(ind)" v-model="variant.size.y" type="number" step="0.1">
                                </div>
                            </div>
                            <div class="w33 px5">
                                <div class="p_input_group">
                                    <label class="mb0">Глубина, мм</label>
                                    <input @input="change_variant(ind)" v-model="variant.size.z" type="number" step="0.1">
                                </div>
                            </div>
                        </div>
                        <div class="flex_row flex_wrap flex_vcenter">
                            <div v-for="(variable, key) in variant.variables" class="w33 px5">
                                {{variable.type}}
                                <div v-if="variables[key].type == 'number'" class="p_input_group">
                                    <label class="mb0">{{variables[key].name}}</label>
                                    <input @input="change_variant(ind)" v-model="variable.value" type="number" step="0.1">
                                </div>
                                <div v-if="variables[key].type == 'select'" class="p_input_group">
                                    <label class="mb0">{{variables[key].name}}</label>
                                    <select @change="change_variant(ind)" v-model="variable.value" class="p_input">
                                        <option :value="opt.value" v-for="opt in variables[key].params.options">{{opt.name}}</option>
                                    </select>
                                </div>
                                <div v-if="variables[key].type == 'boolean'" class="p_input_group">
                                    <label class="mb0">{{variables[key].name}}</label>
                                    <select @change="change_variant(ind)" v-model="variable.value" class="p_input">
                                        <option :value="1">Да</option>
                                        <option :value="0">Нет</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div>
                            <?php echo $lang_arr['accessories'] ?>
                            <pp_items ref="accessories_picker" @e_update="change_variant_accessories(ind, $event)" :lang="$options.lang" :count_mode="true" :unselect="true"  :selected_items="variant.accessories"  :controller="'accessories'"></pp_items>
                        </div>
                        <div class="variant_controls">
                            <button v-if="variants.length > 1" class="v_del btn btn-sm btn-outline btn-danger" @click="remove_variant(ind)" type="button"><i class="icon icon_trash"></i></button>
                            <button v-if="ind > 0" class="v_up btn btn-sm btn-outline btn-success" @click="variant_up(ind)" type="button"><i class="icon icon_expand_up"></i></button>
                            <button v-if="ind != variants.length - 1" class="v_down btn btn-sm btn-outline btn-success" @click="variant_down(ind)" type="button"><i class="icon icon_expand_down"></i></button>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline btn-primary" @click="add_variant()" type="button">Добавить</button>
                    </div>
                </div>
            </div>
            <div v-if="tab == 'tree'">
                <div class="flex tree_block relative">
                    <div v-if="modal_add.show == 1" class="m_wrapper">
                        <div class="m_content">
                            <div @click="hide_modal_add" class="m_close">×</div>
                            <div class="m_content_heading mb-2">Добавить</div>
                            <div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label class="mb-0">Тип</label>
                                        <select @change="change_add_type($event)" :value="modal_add.type">
                                            <option :value="opt.key" v-for="opt in $options.item_types">{{opt.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <label class="mb-0">Название</label>
                                        <input v-model="modal_add.name" type="text">
                                    </div>
                                </div>

                                <div v-if="modal_add.type == 'wall'">

                                </div>

                                <div class="form-group" v-if="modal_add.type == 'model'">
                                    <label class="mb-0">Модель</label>
                                    <pp_items ref="items_picker" @e_update="modal_add.model_id = $event" :selected_item="modal_add.model_id" :controller="'model3d'"></pp_items>
                                </div>

                                <div class="form-group" v-if="modal_add.type == 'link'">
                                    <label class="mb-0">Модель</label>
                                    <pp_items ref="items_picker" @e_update="modal_add.model_id = $event" :selected_item="modal_add.model_id" :controller="'catalogue'"></pp_items>
                                </div>

                                <div class="form-group" v-if="modal_add.type == 'locker2'">
                                    <label class="mb-0">Ящик</label>
                                    <pp_items ref="items_picker" @e_update="choose_param_el($event)" :selected_item="modal_add.model_id" :controller="'lockers'"></pp_items>
                                </div>

                                <div class="form-group" v-if="modal_add.type == 'wall'">
                                   <ul class="flex_row flex_wrap">
                                       <li class="det_sel" @click="add_child({wall_type: 1})"><img src="/common_assets/assets/wall_icons/01.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 2})"><img src="/common_assets/assets/wall_icons/02.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 3})"><img src="/common_assets/assets/wall_icons/03.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 4})"><img src="/common_assets/assets/wall_icons/04.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 5})"><img src="/common_assets/assets/wall_icons/05.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 6})"><img src="/common_assets/assets/wall_icons/06.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 7})"><img src="/common_assets/assets/wall_icons/07.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 8})"><img src="/common_assets/assets/wall_icons/08.png"></li>
                                       <li class="det_sel" @click="add_child({wall_type: 9})"><img src="/common_assets/assets/wall_icons/09.png"></li>
                                   </ul>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <button type="button" @click="hide_modal_add" class="btn btn-sm btn-white">Отмена</button>
                                        <button type="button" @click="add_child" class="btn btn-sm btn-primary">Добавить</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w50 relative">
                        <sl-vue-tree
                                v-model="nodes"
                                ref="slVueTree"
                                :allow-multiselect="false"
                                :edge-size="10"
                                @select="nodeSelected"
                                @nodeclick="node_selected"
                                @drop="nodeDropped"
                                @nodecontextmenu="context_menu"
                        >

                            <template slot="title" slot-scope="{ node }">
                                <span :class="{tree_not_visible: node.data.visible == 0}" class="tree_node_title">
                                    <i v-if="node.level != 1 && node.data.type == 'section'" class="icon icon_flipx"></i>
                                    <i v-if="node.data.type == 'section_div'" class="icon icon_flipx"></i>
                                    <i v-if="node.data.type == 'divider'" class="icon icon_flipx"></i>
                                    <i v-if="node.data.type == 'wall'" class="icon icon_horizontal"></i>
                                    <i v-if="node.data.type == 'door'" class="icon icon_doors"></i>
                                    <i v-if="node.data.type == 'locker'" class="icon icon_boxes"></i>
                                    <i v-if="node.data.type == 'locker2'" class="icon icon_boxes"></i>
                                    <i v-if="node.data.type == 'model'" class="icon icon_box"></i>
                                    <i v-if="node.data.type == 'link'" class="icon icon_contour"></i>
                                    <i v-if="node.data.type == 'slider'" class="icon icon_menu_overview"></i>
                                    <i v-if="node.data.type == 'facade'" class="icon icon_facade_color"></i>
                                    <i v-if="node.data.type == 'rotator'" class="icon icon_rotateleft"></i>
                                    <i v-if="node.data.type == 'handle'" class="icon icon_furniture_handle"></i>
                                    <i v-if="node.level == 1" class="icon icon_wardrobe"></i>
                                    {{ node.title }}
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
                                    <i @click.stop.prevent @mousedown.stop.prevent="context_menu(node, $event)" class="icon icon_menu"></i>
                                </div>

                            </template>


                            <template slot="draginfo">
                                {{selectedNodesTitle}}
                            </template>

                        </sl-vue-tree>

                        <div ref="context_menu" class="node_context_menu shadow rounded" v-show="node_context_visible">
                            <ul>
                                <li v-show="node_can_add || node_is_root" @click="show_modal_add_selected()"><i class="icon icon_add"></i>Добавить</li>
                                <li v-show="!node_is_root" @click="copy_to_clipboard()"><i class="icon icon_duplicate"></i>Копировать</li>
                                <li v-show="!node_is_root" @click="cut_to_clipboard()"><i class="icon icon_cut"></i>Вырезать</li>
                                <li :class="{disabled: $options.clipboard == null}" v-show="node_can_add || node_is_root" @click="paste_from_clipboard()"><i class="icon icon_paste"></i>Вставить</li>
                                <li v-show="!node_is_root" @click="clone_selected()"><i class="icon icon_copying"></i>Клонировать</li>
                                <li v-show="node_can_add || node_is_root"><i class="icon icon_modules"></i>Сохранить как шаблон</li>
                                <li v-show="!node_is_root" @click="remove_selected()"><i class="icon icon_trash"></i>Удалить</li>
                            </ul>
                        </div>


                    </div>
                    <div class="w50 p5 t_bck_color2">

                        <div class="current_params" v-if="current_params">
                            <div class="p5 mb5 t_bck_color rounded shadow">
                                <label class="m-0"><b>Название</b></label>
                                <input @input="change_name($event)" type="text" :value="current_params.name">
                            </div>
                            <div v-if="$options.opts[current_params.type]">
                                <div class="p5 mb5 t_bck_color rounded shadow" v-for="block in $options.opts[current_params.type]">
                                    <div><b>{{block.name}}</b></div>
                                    <div v-if="block.type">
                                        <div v-if="block.type == 'input_cond'">
                                            <condition_input @change_inp="change_condition($event)" :value="current_params[block.key]" :label="block.name" :var_list="var_list"></condition_input>
                                        </div>
                                    </div>
                                    <div v-if="block.children" v-for="param in block.children">
                                        <div v-if="check_param_condition(param)">
                                            <div v-if="param.type == 'select'">
                                                <label :style="get_style(param.key)" class="m-0">{{param.name}}</label>
                                                <select @change="change_param(block.key, param.key, $event, 'select')" :value="get_current_param(block.key, param.key)">
                                                    <option :value="option.val" v-for="option in param.values">{{option.label}}</option>
                                                </select>
                                            </div>
                                            <div v-if="param.type == 'item_picker'">
                                                <label class="m-0">{{param.name}}</label>
                                                <div v-if="param.multiple">
                                                    <pp_items ref="items_picker" @e_update="change_param(block.key, param.key, $event, 'picker')"  :unselect="param.unselect" :selected_items="get_current_param(block.key, param.key)" :lang="$options.lang" :controller="param.catalog"></pp_items>
                                                </div>
                                                <div v-else>
                                                    <pp_items ref="items_picker" @e_update="change_param(block.key, param.key, $event, 'picker')"  :unselect="param.unselect" :selected_item="get_current_param(block.key, param.key)" :lang="$options.lang" :controller="param.catalog"></pp_items>
                                                </div>

                                            </div>
                                            <div v-if="param.type == 'bool'" class="flex_row flex_wrap flex_vcenter" >
                                                <div style="width: 20px">
                                                    <input @change="change_param(block.key, param.key, $event, 'checkbox')" :checked="get_current_param(block.key, param.key)" type="checkbox">
                                                </div>
                                                <label class="m-0">{{param.name}}</label>
                                            </div>
                                            <div v-if="param.type == 'number'">
                                                <label :style="get_style(param.key)" class="m-0">{{param.name}}</label>
                                                <input @change="change_param(block.key, param.key, $event, 'number')" :value="get_current_param(block.key, param.key)" type="number" step="0.1">
                                            </div>
                                            <div v-if="param.type == 'text'">
                                                <label :style="get_style(param.key)" class="m-0">{{param.name}}</label>
                                                <input @change="change_param(block.key, param.key, $event, 'text')" :value="get_current_param(block.key, param.key)" type="text">
                                            </div>
                                            <div v-if="param.type == 'input_ext'">

                                                <div v-if="current_params.type == 'wall' && block.key == 'size' && param.key == 'x'">
                                                    <div v-if="current_params.spec.orientation == 'f'">
                                                        <value_input :axis="'x'" @change_inp="change_param_ext(block.key, param.key, $event)" :value="current_params[block.key][param.key]" :label="'По оси X'" :var_list="var_list"></value_input>
                                                    </div>
                                                    <div v-if="current_params.spec.orientation == 'h'">
                                                        <value_input :axis="'x'" @change_inp="change_param_ext(block.key, param.key, $event)" :value="current_params[block.key][param.key]" :label="'По оси X'" :var_list="var_list"></value_input>
                                                    </div>
                                                    <div v-if="current_params.spec.orientation == 'v'">
                                                        <value_input :axis="'z'" @change_inp="change_param_ext(block.key, param.key, $event)" :value="current_params[block.key][param.key]" :label="'По оси Z'" :var_list="var_list"></value_input>
                                                    </div>
                                                </div>
                                                <div v-else-if="current_params.type == 'wall' && block.key == 'size' && param.key == 'y'">
                                                    <div v-if="current_params.spec.orientation == 'f'">
                                                        <value_input :axis="'y'" @change_inp="change_param_ext(block.key, param.key, $event)" :value="current_params[block.key][param.key]" :label="'По оси Y'" :var_list="var_list"></value_input>
                                                    </div>
                                                    <div v-if="current_params.spec.orientation == 'h'">
                                                        <value_input :axis="'z'" @change_inp="change_param_ext(block.key, param.key, $event)" :value="current_params[block.key][param.key]" :label="'По оси Z'" :var_list="var_list"></value_input>
                                                    </div>
                                                    <div v-if="current_params.spec.orientation == 'v'">
                                                        <value_input :axis="'y'" @change_inp="change_param_ext(block.key, param.key, $event)" :value="current_params[block.key][param.key]" :label="'По оси Y'" :var_list="var_list"></value_input>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <value_input :axis="param.key" @change_inp="change_param(block.key, param.key, $event, 'ext')" :value="get_current_param(block.key, param.key)" :label="param.name" :var_list="var_list"></value_input>
                                                </div>

                                            </div>
                                            <div v-if="param.type == 'filemanager'">
                                                <label class="m-0">{{param.name}}</label>
                                                <filemanager_input @change_inp="change_param(block.key, param.key, $event, 'ext')" :value="get_current_param(block.key, param.key)"></filemanager_input>
                                            </div>
                                            <div v-if="param.type == 'input_mat'">
                                                <material_input
                                                        @change_material="change_param_material($event)"
                                                        @change_group="change_param_material_group($event)"
                                                        @change_variant="change_param_material_variant($event)"
                                                        @change_rotated="change_param('material', 'rotated', $event, 'ext')"
                                                        @change_fixed="change_param('material', 'fixed', $event, 'ext')"
                                                        :params="get_material_params()">
                                                </material_input>
                                            </div>

                                            <div v-if="param.type == 'select_dynamic'">
                                                <label :style="get_style(param.key)" class="m-0">{{param.name}}</label>
                                                <select @change="change_param(block.key, param.key, $event, 'select')" :value="get_current_param(block.key, param.key)">
                                                    <option :value="option.val" v-for="option in get_dynamic_params(param.values)">{{option.label}}</option>
                                                </select>
                                            </div>

                                            <div v-if="(current_params.type == 'wall' || current_params.type == 'tabletop') && param.key == 'type' && current_params.spec.type != 'common' ">
                                                <div>
                                                    <div>Параметры формы</div>
                                                    <div v-for="item in $options.wall_types[current_params.spec.type].params">
                                                        <div v-if="item.type == 'input_ext'">
                                                            <value_input @change_inp="change_param_wall_type(item.key, $event)" :value="current_params.spec.type_params[item.key]" :label="item.name" :var_list="var_list"></value_input>
                                                        </div>

                                                        <div v-if="item.type == 'array'">
                                                            <div v-for="(object, index) in current_params.spec.type_params[item.key]">
                                                                <div class="flex flex_row">
                                                                    <div class="w50">{{$options.wall_types[current_params.spec.type].params[item.key].value_name}}</div>
                                                                    <div class="w50 tar"><i @click="remove_param_wall_type_array(item.key, index)" class="cp icon icon_trash"></i></div>
                                                                </div>
                                                                <div v-for="(val, key) in object">
                                                                    <value_input @change_inp="change_param_wall_type_array(item.key, index, key, $event)" :value="val" :label="key" :var_list="var_list"></value_input>
                                                                </div>
                                                            </div>
                                                            <div class="tac">
                                                                <button class="w50" type="button" @click="add_param_wall_type_array(item.key)">Добавить</button>
                                                            </div>

                                                        </div>

                                                        <div v-if="item.type == 'select'">
                                                            <label  class="m-0">{{item.name}}</label>
                                                            <select @change="change_param_wall_type(item.key, $event, true)" :value="current_params.spec.type_params[item.key]">
                                                                <option :value="option.val" v-for="option in item.values">{{option.label}}</option>
                                                            </select>
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

            </div>
            <div v-if="tab == 'events'">

                <div v-for="(ev, key) in events">
                    <label class="m-0">{{key}}</label>
                    <input @input="change_event(key)" v-model="events[key]" type="text">
                </div>
            </div>
            <div v-show="tab == 'json'">
                <div id="jsoneditor_asfjh" style="width: 100%; height: 500px;"></div>
                <button type="button" class="btn btn-primary btn-sm" @click="apply_json"><?php echo $lang_arr['apply'] ?></button>
            </div>
        </div>


    </div>

    <div id="value_input_template">
        <div class="value_input">
            <label :style="get_style()" class="m-0">{{label}}</label>
                <div>
                    <input ref="pretty" @change="change_pretty" v-show="mode == 'pretty'" v-model="pretty_val" type="text">
                    <input ref="real" @change="change_real" v-show="mode == 'real'" v-model="real_val" type="text">
                    <button @click="apply_val()" type="button">Ок</button>
                </div>
                <div>
                    <button title="Добавить переменную" class="w50" @click="show_var_list" type="button">+{a}</button>
                    <button title="Сменить вид" class="w50" @click="change_mode" type="button">{{mode_button_content}}</button>
                    <ul ref="var_list" v-show="show_variables" class="var_list rounded shadow">
                        <li class="var_heading">Размеры</li>
                        <li v-for="item in var_list.sizes">
                            <div @click="add_val(item)" class="link" >
                                {{item.name}}
                            </div>
                            <div>
                                <button title="Добавить переменную со знаком +" @click="add_val(item, '+')" type="button">+</button>
                                <button title="Добавить переменную со знаком -" @click="add_val(item, '-')" type="button">-</button>
                            </div>
                        </li>
                        <li class="var_heading">Переменные</li>
                        <li v-for="item in var_list.variables">
                            <div @click="add_val(item)" class="link" >
                                {{item.name}}
                            </div>
                            <div >
                                <button title="Добавить переменную со знаком +" @click="add_val(item, '+')" type="button">+</button>
                                <button title="Добавить переменную со знаком -" @click="add_val(item, '-')" type="button">-</button>
                            </div>
                        </li>

                        <li class="var_heading">Материалы</li>
                        <li class="flex_wrap">
                            <ul style="width: 100%" v-for="item in var_list.materials">
                                <li>{{item.name}} {{item.key}}</li>
                                <li>
                                    <div @click="add_val_mat(item, null)" class="link" >
                                        {{item.name}} текущий
                                    </div>
                                    <div>
                                        <button title="Добавить переменную со знаком +" @click="add_val_mat(item, null, '+')" type="button">+</button>
                                        <button title="Добавить переменную со знаком -" @click="add_val_mat(item, null, '-')" type="button">-</button>
                                    </div>
                                </li>
                                <li v-for="mat in item.variants">
                                    <div @click="add_val_mat(item, mat)" class="link" >
                                        {{mat.name}}
                                    </div>
                                    <div>
                                        <button title="Добавить переменную со знаком +" @click="add_val_mat(item, mat, '+')" type="button">+</button>
                                        <button title="Добавить переменную со знаком -" @click="add_val_mat(item, mat, '-')" type="button">-</button>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li class="var_heading">Вычисляемые свойства</li>
                        <li class="link" @click="add_val(item, 'c')" v-for="item in var_list.computed">{{item.name}}</li>
                    </ul>
                </div>
        </div>
    </div>

    <div id="condition_input_template">
        <div class="flex_row flex_vcenter">
            <select @change="on_change()" v-model="variable">
                <option :value="null">Нет</option>
                <option :value="opt.key" v-for="opt in var_list.variables">{{opt.name}}</option>
            </select>
            <div v-show="variable != null">=</div>
            <div v-show="variable != null" class="w25">
                <input v-model="val" type="text">
            </div>
            <div v-show="variable != null" class="w25">
                <button @click="apply_val()" type="button">Ок</button>
            </div>

        </div>
    </div>

    <div id="material_input_template">
        <div>
            <div class="p_input_group">
                <label class="mb0">Материал</label>
                <select @change="change_material" v-model="l_params.key">
                    <option :value="opt.key" v-for="opt in $options.materials">{{opt.name}} ({{opt.key}})</option>
                </select>
            </div>
            <div class="p_input_group">
                <label class="mb0">Вариант</label>
                <select @change="change_variant" v-model="l_params.variant">
                    <option :value="'current'">Материал модуля</option>
                    <option :value="opt.key" v-for="opt in $options.materials[l_params.key].variants">{{opt.name}}</option>
                </select>
            </div>
            <div class="p_input_group">
                <label class="mb0">Группа</label>
                <input @input="change_group" min="0" step="1" type="number" v-model="l_params.group">
            </div>
            <div class="p_input_group">
                <label class="flex_row">
                    <input @change="change_rotated" style="width: auto" v-model="l_params.rotated" type="checkbox">
                    Повернуть
                </label>

            </div>
            <div class="p_input_group">
                <label class="flex_row">
                    <input @change="change_fixed" style="width: auto" v-model="l_params.fixed" type="checkbox">
                    Зафиксировать
                </label>

            </div>
        </div>

    </div>

    <div id="filemanager_input_template">
        <div class="filemanager_input">
            <div class="flex_row">
                <input @change="on_change()" type="text" v-model="value">
                <button type="button" data-toggle="modal" data-target="#filemanager2" ><i class="icon icon_folder_open"></i></button>
            </div>

            <div class="modal inmodal" id="filemanager2" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang_arr['ok'] ?></span></button>
                            <h5 class="modal-title">Выбрать файл</h5>
                        </div>
                        <div class="modal-body">
                            <filemanager ref="fileman2" @select_file="sel_file2($event)"></filemanager>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<style>

    .tree_not_visible{
        /*opacity: 0.5;*/
    }

    .node_menu{
        position: absolute;
        right: 0;
        top:0;
    }

    .node_menu i {
        font-size: 20px;
    }

    .node_menu ul{
        position: fixed;
        z-index: 10;
        right: 0;
        top:100%;
    }

    .node_context_menu{
        position: fixed;
        background: #ffffff;
        padding: 5px 0;
        z-index: 99;
    }

    .node_context_menu li {
        padding: 5px 10px ;
        cursor: pointer;
        display: flex;
    }

    .node_context_menu li.disabled{
        opacity: 0.5;
    }

    .node_context_menu li:hover {
        background: #f4f4f4;
        color: #0066cc;
    }

    .node_context_menu i {
        font-size: 20px;
        margin-right: 2px;
    }


    .sl-vue-tree-gap {
        min-height: 1px;
        padding-left: 10px;
    }

    .sl-vue-tree-toggle {
        display: inline-block;
        text-align: left;
        width: 15px;
        position: absolute;
        left: 0;
    }
    .sl-vue-tree-title {
        position: relative;
        padding-left: 15px;
    }

    .sl-vue-tree-node-item {
        position: relative;
        display: flex;
        align-items: center;
        flex-direction: row;
        font-size: 13px;
        line-height: 20px;
        height: 27px;
        /*overflow: hidden;*/
        cursor: pointer;
        padding-left: 5px;
        padding-right: 25px;
        border: 1px solid transparent;
    }

    span.tree_node_title {
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: middle;
        overflow: hidden;
        white-space: nowrap;
    }

    .tree_node_title i{
        font-size: 20px;
        display: inline-block;
        vertical-align: middle;
    }


    .filemanager_input button{
        cursor: pointer;
        border: 1px solid #e5e6e7;
        background: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .filemanager_input button:hover {
        color: #0066cc;
        border-color: #0066cc;
    }

    .filemanager_input button i{
        font-size: 20px;
    }

    i.icon.active{
        color: #0066cc;
        box-shadow: 0 0 5px 0 rgb(0 0 0 / 10%)!important;
    }

    i.icon:hover{
        color: #0066cc;
        box-shadow: 0 0 5px 0 rgb(0 0 0 / 10%)!important;
    }

    i.icon{
        /*font-weight: bold;*/
    }

    .v_del{
        position: absolute;
        right: 0;
        top: 0;
    }
    .v_up{
        position: absolute;
        right: 0;
        top: 50px;
    }
    .v_down{
        position: absolute;
        right: 0;
        top: 81px;
    }

    .variant_controls{
        position: absolute;
        right: 5px;
        top: 5px;
        bottom: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .configurator ul{
        margin: 0;
        padding: 0;
    }

    .configurator b{
        color: #222222;
    }

    .configurator table.conf_table{
        width: 100%;
    }
    .configurator table.conf_table td, .configurator table.conf_table th{
        padding: 2px 5px;
    }

    .t_bck_color {
        background-color: #ffffff!important;
    }
    .configurator .rounded {
        border-radius: 4px!important;
    }
    .configurator .shadow {
        box-shadow: 0 0 5px 0 rgb(0 0 0 / 10%)!important;
    }

    .configurator .current_params{
        max-height: 100%;
        overflow: auto;
    }

    .configurator .tree_block{
        max-height: 500px;
    }

    .px5{
        padding-left: 5px;
        padding-right: 5px;
    }

    .p5{
        padding: 5px;
    }

    .mb5{
        margin-bottom: 5px;
    }
    .sc_08{
        transform: scale(0.8);
    }


    .t_bck_color2{
        background-color: #f7f7f7!important;
    }

    .link{
        cursor: pointer;
        text-decoration: underline;
        color: #0066cc;
    }

    .var_heading{
        font-weight: bold;
    }

    .value_input{
        margin-bottom: 5px;
    }

    .value_input button{
        cursor: pointer;
        border: 1px solid #e5e6e7;
        background: none;
    }

    .value_input button:hover{
        color: #0066cc;;
        border-color: #0066cc;
    }


    .value_input .var_list{
        position: fixed;
        /*right: 0;*/
        /*top: 100%;*/
        z-index: 200;
        width: 300px;
        background: #ffffff;
        margin: 0;
        padding: 5px;
        max-height: 300px;
        overflow: auto;
    }



    .value_input > div{
        display: flex;
        position: relative;
    }

    .current_params button{
        cursor: pointer;
        border: 1px solid #e5e6e7;
        background: none;
    }
    .current_params button:hover{
        color: #0066cc;;
        border-color: #0066cc;
    }

    .var_list li{
        margin: 6px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .var_list li button{
        width: 16px;
        font-weight: bold;
        border: 1px solid #e5e6e7;
        margin: 0;
        text-align: center;
        padding: 0;
    }

    .var_list li button:hover{
        color: #0066cc;;
        border-color: #0066cc;
    }

    .configurator{
        scrollbar-color: #0066cc #e0e0e0;
        scrollbar-width: thin;
    }

    .configurator *::-webkit-scrollbar {
        width: 1px;
        height: 1px;
    }

    .configurator *::-webkit-scrollbar-track {
        background-color: #cacaca;
    }

    .configurator *::-webkit-scrollbar-thumb {
        background: #0066cc;
        background: linear-gradient(#297ed4, #004f9d);
    }

    .configurator input{
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: block;
        padding: 3px 5px;
        width: 100%;
    }

    .configurator input[type="checkbox"]{
        width: auto;
        cursor: pointer;
    }

    .configurator select{
        width: 100%;
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        display: block;
        padding: 3px 5px;
    }

    .a_list a{
        display: inline-block;
        font-size: 17px;
    }

    .relative{
        position: relative;
    }

    .m_close{
        position: absolute;
        right: 5px;
        top: 5px;
        font-size: 20px;
        line-height: 20px;
        font-weight: bold;
        cursor: pointer;
        opacity: 0.8;
    }

    .m_close:hover{
        opacity: 1;
    }

    .m_wrapper{
        position: absolute;
        z-index: 1;
        left: 0;
        right: 0;
        top:0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
    }

    .m_content{
        position: absolute;
        z-index: 1;
        left: 5px;
        right: 5px;
        top:5px;
        bottom: 5px;
        padding: 10px;
        background: #fff;
    }

    ul {
        list-style: none;
    }

    .flex {
        display: flex;
    }

    .flex_wrap{
        flex-wrap: wrap!important;
    }

    .p_input_group{
        margin-bottom: 10px;
        position: relative;
    }

    select.p_input{
        width: 100%;
        padding: 5px;
        border: 1px solid #cacaca;
        border-radius: 0;
        color: #333;
    }

    .flex_row{
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        height: 100%;
        align-items: stretch;
    }

    .flex_vcenter{
        align-items: center;
    }

    .p10{
        padding: 10px;
    }

    .mb0{
        margin-bottom: 0;
    }

    .mt5{
        margin-top: 5px;
    }

    .w25 {
        width: 25%;
    }
    .w33 {
        width: 33.333%;
    }
    .w75 {
        width: 75%;
    }
    .w50 {
        width: 50%;
    }
    .w90 {
        width: 90%;
    }
    .w10 {
        width: 10%;
    }

    .tar{
        text-align: right;
    }
    .tac{
        text-align: center;
    }
    .cp{
        cursor: pointer;
    }

    .det_sel{
        width: 90px;
    }
    .det_sel img{
        max-width: 100%;
    }

</style>
<script src="/common_assets/admin_js/vue/pagination.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/configurator.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>
<script src="/common_assets/libs/json_editor/jsoneditor.min.js"></script>

<?php if(file_exists(dirname(FCPATH) .'/assets/custom_admin.js')):?>
    <script src="<?php echo$this->config->item('const_path')?>assets/custom_admin.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>
<?php endif;?>

<link rel="stylesheet" href="/common_assets/libs/json_editor/jsoneditor.min.css">
<?php include($_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/components/items_picker.php');?>
