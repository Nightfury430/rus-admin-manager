<?php
    if (isset($common) && $common == 1) {
        $submit_url = site_url( $controller_name . '/items_add_ajax_common/' );
        if(isset($id)) $submit_url = $submit_url . $id;
        $return_url = site_url('/catalog/items_common/'.$controller_name);
    } else {
        $submit_url = site_url( $controller_name . '/items_add_ajax/' );
        if(isset($id)) $submit_url = $submit_url . $id;
        $return_url = site_url('/catalog/items/'.$controller_name);
    }
?>
<form  id="sub_form">
    <input id="form_submit_url" value="<?php echo $submit_url ?>" type="hidden">
    <input id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
    <input id="controller_name" value="<?php echo $controller_name ?>" type="hidden">
    <?php if (isset($id)): ?>
        <input id="item_id" value="<?php echo $id ?>" type="hidden">
    <?php endif; ?>
    <div v-cloak class="wrapper wrapper-content  animated fadeInRight">
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
                            data-bs-target="#basic_params_tab"
                            aria-controls="basic_params_tab"
                            aria-selected="true">
                                <?php echo $lang_arr['basic_params'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#model_params_tab"
                            aria-controls="model_params_tab"
                            aria-selected="true">
                                <?php echo $lang_arr['model_params'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#variants_tab"
                            aria-controls="variants_tab"
                            aria-selected="true">
                                <?php echo $lang_arr['sizes'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#materials_tab"
                            aria-controls="materials_tab"
                            aria-selected="true">
                                <?php echo $lang_arr['additional_materials'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#custom_data_tab"
                            aria-controls="custom_data_tab"
                            aria-selected="true">
                                <?php echo $lang_arr['custom_data'] ?>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic_params_tab" class="tab-pane fade show active" role="tabpanel">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                <div class="col-sm-10">
                                    <input v-model="item.name" type="text" class="form-control" id="name" name="name">
                                </div>
                            </div>
                            <?php if ($this->config->item('username') === 'shop@avtobardak.net'): ?>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Ссылка</label>
                                    <div class="col-sm-10">
                                        <input v-model="item.link" type="text" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
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
                                            <span v-if="option.parent != 0">{{m_categories_hash[option.parent].name}} / &nbsp;</span>{{ option.name }}
                                        </template>
                                        <template v-slot:option="option">
                                                <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                    <span v-if="option.parent != 0">{{m_categories_hash[option.parent].name}} / </span>{{ option.name }}
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
                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['drag_type'] ?></label>
                                <div class="col-sm-10">
                                    <select @change="params_change()" v-model="item.cabinet_group" class="form-control">
                                        <option value="top"><?php echo $lang_arr['drag_as_top'] ?></option>
                                        <option value="bottom"><?php echo $lang_arr['drag_as_bottom'] ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['snap_type'] ?></label>
                                <div class="col-sm-10">
                                    <select @change="params_change()" v-model="item.drag_mode" class="form-control">
                                        <option selected value="common"><?php echo $lang_arr['snap_walls'] ?></option>
                                        <option value="surface"><?php echo $lang_arr['snap_all'] ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['show_in_report'] ?></label>
                                <div class="col-sm-10">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.show_in_report" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['show_sizes'] ?></label>
                                <div class="col-sm-10">
                                    <label class="switch">
                                        <input @change="params_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.sizes_available" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                                <div class="col-sm-5">
                                    <div class="icon_block">
                                        <img @click="file_target = 'icon'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" style="max-width: 78px" :src="correct_url(item.icon)" alt="">
                                        <i @click="file_target = 'icon'; $refs.fileman.data_mode = 'images';" data-toggle="modal" data-target="#filemanager" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                        <i v-if="item.icon != ''" @click="item.icon = ''" class="fa fa-trash delete_file" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                </div>
                            </div>
                        </div>
                        <div id="model_params_tab" class="tab-pane fade" role="tabpanel">
                        <div class="row form-group">
                                    <div class="col-4">
                                        <div class="row ">
                                            <div class="col-12 form-group">
                                                <div v-if="item.variants[0].model" class="col-12 mb-2 text=primary">
                                                    <?php echo $lang_arr['current_model'] ?>: <a download :href="correct_download_url(item.variants[0].model)"><b>{{correct_model_url(item.variants[0].model)}}</b></a>
                                                </div>

                                                <button @click="file_target = 0 ;$refs.fileman.data_mode = 'models'" type="button" data-toggle="modal" data-target="#filemanager" class="btn-block form-control-sm btn btn-outline-info" title="Выбрать или загрузить модель">
                                                    <span class="fa fa-folder-open"></span>
                                                    Выбрать
                                                </button>
                                            </div>
                                            <div class="col-4 form-group">
                                                <label><?php echo $lang_arr['width'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                <input @input="size_change()" class="form-control" v-model="item.variants[0].width" type="number">
                                            </div>
                                            <div class="col-4 form-group">
                                                <label><?php echo $lang_arr['height'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                <input @input="size_change()" class="form-control" v-model="item.variants[0].height" type="number">
                                            </div>
                                            <div class="col-4 form-group">
                                                <label><?php echo $lang_arr['depth'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                                <input @input="size_change()" class="form-control" v-model="item.variants[0].depth" type="number">
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <pp_material  @e_update_color="update_color($event)" @e_update="update_material($event)" :params_obj="{}"></pp_material>
                                            </div>
                                        </div>

                                    </div>
                                    <div id="preview_block" class="col-8">
                                        <div>
                                            <ul class="preview_buttons">
                                                <li>
                                                    <i title="Сделать иконку" @click="mi()"  class="icon icon_img"></i>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div id="variants_tab" class="tab-pane fade" role="tabpanel">
                        <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['params_blocks'] ?></label>
                                    <div class="col-sm-10">
                                        <v-select
                                                @input="change_params_blocks"
                                                :clearable="true"
                                                :value="item.params_blocks"
                                                label="name"
                                                multiple
                                                :close-on-select="false"
                                                :options="$options.params_blocks"
                                                :reduce="opt => opt.id"
                                                v-model="item.params_blocks"
                                                :key="item.id"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <draggable class="" v-model="item.variants" v-bind="dragOptions" handle=".draggable_panel" group="parent" :move="on_drag" @start="drag = true" @end="drag = false">
                                    <div v-for="(variant,index) in item.variants" :key="variant.id" class="d-flex flex-row flex-wrap form-group draggable_panel pr-5">
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['name'] ?></label>
                                            <input class="form-control" v-model="variant.name" type="text">
                                        </div>

                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['code'] ?></label>
                                            <input class="form-control" v-model="variant.code" type="text">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['price'] ?></label>
                                            <input class="form-control" v-model="variant.price" type="text">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['width'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                            <input class="form-control" v-model="variant.width" type="number">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['height'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                            <input class="form-control" v-model="variant.height" type="number">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['depth'] ?> (<?php echo $lang_arr['units'] ?>)</label>
                                            <input class="form-control" v-model="variant.depth" type="number">
                                        </div>
                                        <div v-if="index > 0" class="col-12 py-2">

                                            <div v-if="item.variants[index].model" class="col-12 mb-2 text=primary">
                                                <?php echo $lang_arr['current_model'] ?>: <a download :href="correct_download_url(item.variants[index].model)"><b>{{correct_model_url(item.variants[index].model)}}</b></a>
                                            </div>

                                            <button @click="file_target = index ;$refs.fileman.data_mode = 'models'" type="button" data-toggle="modal" data-target="#filemanager" class="btn-block form-control-sm btn btn-outline-info" title="Выбрать или загрузить модель">
                                                <span class="fa fa-folder-open"></span>
                                                Выбрать
                                            </button>
                                        </div>

                                        <button v-if="index > 0" @click="remove_variant(index)" style="position: absolute; right: 10px; top:10px; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>

                                        <div class="col-12 ">
                                            <div class="row py-2"  v-for="params in variant.params_blocks">
                                                <div class="col-12 text-center ">
                                                    <b>{{params.name}}</b>
                                                </div>
                                                <div class="col-12" v-for="opt in params.data">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            {{opt.name}}
                                                        </div>
                                                        <div v-if="opt.type == 'select'" class="col-12">
                                                            <div class="row" v-for="op in opt.params.options">
                                                                <div class="col-4">{{op.name}}</div>
                                                                <div class="col-4"><input class="form-control" type="number" v-model="op.price"></div>
                                                            </div>
                                                        </div>
                                                        <div v-if="opt.type == 'boolean'" class="col-12">
                                                            <div class="row">
                                                                <div class="col-4">{{opt.name}}</div>
                                                                <div class="col-4"><input class="form-control" type="number" v-model="opt.params.price"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

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
                        <div id="materials_tab" class="tab-pane fade" role="tabpanel">
                        <div class=" row">
                                <div class="col-12"><h3><?php echo $lang_arr['additional_materials'] ?></h3></div>
                                <div class="nav-align-top nav-tabs-shadow mb-6">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li @click="active_material_tab = name" v-for="(item, name) in item.self_additional_materials">
                                            <a v-bind:class="{ active: active_material_tab == name }" class="nav-link">{{item.name}}</a>
                                        </li>
                                        <li>
                                            <button @click="add_material()" type="button" class="btn btn-w-m btn-primary btn-outline btn-block"><?php echo $lang_arr['add'] ?></button>
                                        </li>
                                    </ul>

                                        <div class="tab-content">

                                            <div v-bind:class="{ active: active_material_tab == name }" v-show="active_material_tab == name" v-for="(item, name) in item.self_additional_materials"
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
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['type'] ?></label>
                                                                <div class="col-sm-8">
                                                                    <select v-model="item.type" class="form-control">
                                                                        <option :value="0">Материал из базы</option>
                                                                        <option :value="1">Свой материал</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div v-show="item.type == 0">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['material_fixed'] ?></label>
                                                                    <div class="col-sm-8">
                                                                        <label class="switch">
                                                                            <input v-bind:true-value="0" v-bind:false-value="1" v-model="item.fixed" type="checkbox">
                                                                            <span class="slider round"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['additional_materials_categories'] ?></label>
                                                                    <div class="col-8">
                                                                        <v-select
                                                                                :clearable="false"
                                                                                :value="item.materials"
                                                                                label="name"
                                                                                multiple
                                                                                :close-on-select="false"
                                                                                :options="categories_ordered"
                                                                                :reduce="name => name.id"
                                                                                v-model="item.materials"
                                                                                :key="name.category"

                                                                        >

                                                                            <template #selected-option="option">
                                                                            <span style="pointer-events: none" :title="option.name" :class="{'font-weight-bold': option.parent == 0}">
                                                                                <span v-if="option.parent != 0" class="font-weight-bold">{{categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                                            </span>
                                                                            </template>

                                                                            <template v-slot:option="option">
                                                                            <span :title="option.name" :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                                                <span v-if="option.parent != 0">{{categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                                            </span>
                                                                            </template>
                                                                        </v-select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_material'] ?></label>
                                                                    <div class="col-8">
                                                                        <v-select
                                                                                :clearable="false"
                                                                                :value="item.selected"
                                                                                label="name"
                                                                                :options="computed_mats"
                                                                                :reduce="name => name.id"
                                                                                v-model="item.selected"
                                                                                :key="name.id"
                                                                        >
                                                                            <template #selected-option="option">
                                                                                <div style="cursor:pointer; pointer-events:none; width: 50px; height: 50px;margin-right: 5px; display: inline-block" :style="{ 'background':  get_map(option)  }"></div>
                                                                                <div style="cursor:pointer; pointer-events:none; display: inline-block; vertical-align: middle">{{option.name}}</div>
                                                                            </template>
                                                                            <template v-slot:option="option">
                                                                                <div style="width: 50px; height: 25px;margin-right: 5px; display: inline-block;vertical-align: middle" :style="{ 'background':  get_map(option)  }"></div>
                                                                                <div style="display: inline-block; vertical-align: middle">{{option.name}}</div>
                                                                            </template>
                                                                        </v-select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div v-show="item.type == 1">
                                                                <div class="form-group ">
                                                                    <pp_material :comp_id="'pp_mat_' + name"  @e_update_color="update_color_self(name,$event)" @e_update="update_material_self(name,$event)" :params_obj="item.params"></pp_material>
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
                        <div id="custom_data_tab" class="tab-pane fade" role="tabpanel">
                            <div v-for="(item,key) in item.custom_data" class="d-flex flex-row flex-wrap form-group draggable_panel pr-5">
                                    <div class="col-4 py-2">
                                        <label><?php echo $lang_arr['name'] ?></label>
                                        <input class="form-control" v-model="item.name" type="text">
                                    </div>
                                    <div class="col-4 py-2">
                                        <label><?php echo $lang_arr['key'] ?></label>
                                        <input class="form-control" disabled v-model="item.key" type="text">
                                    </div>
                                    <div class="col-4 py-2">
                                        <label><?php echo $lang_arr['value'] ?></label>
                                        <input class="form-control" v-model="item.value" type="text">
                                    </div>

                                    <button @click="remove_custom_data(key)" style="position: absolute; right: 10px; top:10px; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>

                                </div>

                                <div class="row form-group">
                                    <div class="col-12">
                                        <button data-toggle="modal" data-target="#custom_data_modal" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
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
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <a class="btn btn-white btn-sm" href="<?php echo $return_url ?>"><?php echo $lang_arr['cancel'] ?></a>
                                <?php if (isset($id)): ?>
                                    <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['save'] ?></button>
                                <?php else: ?>
                                    <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add'] ?></button>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-8 text-right">
                                <div v-if="item.id">
                                    <button type="button" @click="download_assets()" class="btn btn-info btn-outline btn-sm">Скачать файлы модели</button>
                                </div>
                            </div>
                        </div>
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

                    <?php if (isset($common) && $common == 1) :?>
                        <filemanager :type="'common'" ref="fileman" @select_file="sel_file($event)"></filemanager>
                    <?php else:?>
                        <filemanager  ref="fileman" @select_file="sel_file($event)"></filemanager>
                    <?php endif;?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok'] ?></button>
                </div>
            </div>
        </div>
    </div>

</form>
<div style="display: none; height:100%" id="main_app">
    <div id="viewport"></div>
</div>
<style>

    #viewport {
        max-width: 100%;
        position: relative;
        height: 100%;
        max-height: 500px;

    }

    label {
        margin-bottom: 2px;
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


    .facades_panel_body {
        min-height: 500px;
    }

    .mat_table td {
        cursor: pointer;
    }

    .radial_menu {
        display: none !important;
    }

    .preview_buttons {
        position: absolute;
        z-index: 10;
        font-size: 20px;
        right: 20px;
        top: 0;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .preview_buttons i {
        cursor: pointer;
    }

</style>
<script>
    view_mode = true;
</script>
