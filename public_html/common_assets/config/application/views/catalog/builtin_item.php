<div class="row wrapper border-bottom white-bg page-heading" >
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['tech_add_item']?></h2>
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

<form @submit="submit" id="sub_form" action="<?php echo site_url('catalog/add_item_ajax_common/builtin/') ?><?php if(isset($id) && $id != 0) echo $id?>">
    <input id="form_success_url" value="<?php echo site_url('/catalog/items_common/builtin') ?>" type="hidden">

    <?php if (isset($id)):?>
        <input id="item_id" value="<?php echo $id?>" type="hidden">
    <?php endif;?>

    <div  class="wrapper wrapper-content  animated fadeInRight">

        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                        <li><a @click="resize_viewport" class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params']?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-4"><?php echo $lang_arr['additional_materials']?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">

                                <fieldset>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name']?></label>
                                        <div class="col-sm-10">
                                            <input v-model="item.name" type="text" class="form-control" id="name" name="name" >
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category'] ?></label>
                                        <div class="col-sm-10">
                                            <v-select
                                                    :clearable="false"
                                                    :value="item.category"
                                                    label="name"
                                                    :options="$options.categories_ordered"
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
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['builtin_type_label']?></label>
                                        <div class="col-sm-10">
                                            <select class="form-control" v-model="item.type">
                                                <option v-for="type in $options.bim_types" :value="type">{{lang('builtin_type_'+type)}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active']?></label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.active" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order']?></label>
                                        <div class="col-sm-10">
                                            <input type="number" v-model="item.order" class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['show_in_report']?></label>
                                        <div class="col-sm-10">
                                            <label class="switch">
                                                <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.show_in_report" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon']?></label>
                                        <div class="col-sm-5">

                                            <div class="icon_block">
                                                <img @click="$refs.icon_file.click()" style="max-width: 78px" :src="get_icon_src(icon_file)" alt="">
                                                <i @click="$refs.icon_file.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i v-if="item.icon != '' || icon_file != ''" @click="icon_file = '';$refs.icon_file.value = ''" class="fa fa-trash delete_file"></i>
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
                                    <div class="col-6">

                                        <div class="btn-group d-flex w-100 mb-2" role="group">
                                            <button @click="model_tab = 0;" :class="{'btn-outline': model_tab != 0}" class="w-100 btn btn-success btn-xs" type="button">Модель</button>
                                            <button @click="model_tab = 1;" :class="{'btn-outline': model_tab != 1}" class="w-100 btn btn-success btn-xs" type="button">Материал</button>
                                        </div>

                                        <div v-show="model_tab == 0">

                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <label class="switch">
                                                        <input @change="update_model()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.exact" type="checkbox">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                                <label class="col-sm-10 col-form-label">Загружать модели без парсинга конструктором</label>

                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <label class="switch">
                                                        <input @change="update_model()" v-bind:true-value="0" v-bind:false-value="1" v-model="single_model" type="checkbox">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                                <label class="col-sm-10 col-form-label">Использовать разные модели на каждый размер</label>

                                            </div>

                                            <draggable class="" v-model="item.variants" v-bind="dragOptions" handle=".handle" group="parent" :move="on_drag" @start="drag = true" @end="drag = false">

                                                <div v-for="(variant,index) in item.variants" :key="variant.id" >
                                                    <div @click="select_variant(index)" class="d-flex flex-row  flex-wrap form-group draggable_panel pl-3 py-1">
                                                        <i class="fa fa-align-justify handle"></i>



                                                        <div v-show="index == 0 || single_model == 0" class="col-12">
                                                            <label v-if="variant.model != ''"><?php echo $lang_arr['current_model']?>: {{variant.model.split('/').pop()}}</label>
                                                            <input class="form-control" type="file" accept=".fbx" @change="process_model($event,index)" >
                                                            <span v-show="index != 0 && variant.file" @click="clear_file($event,index)" class="clear_model fa fa-eraser btn btn-outline btn-info"></span>
                                                        </div>

                                                        <div class="col-4">
                                                            <label class="col-form-label col-form-label-sm"><?php echo $lang_arr['name']?></label>
                                                            <input class="form-control form-control-sm" v-model="variant.name" type="text">
                                                        </div>
                                                        <div class="col-4">
                                                            <label class="col-form-label col-form-label-sm"><?php echo $lang_arr['code']?></label>
                                                            <input class="form-control form-control-sm" v-model="variant.code" type="text">
                                                        </div>
                                                        <div class="col-4">
                                                            <label class="col-form-label col-form-label-sm"><?php echo $lang_arr['price']?></label>
                                                            <input class="form-control form-control-sm" v-model="variant.price" type="text">
                                                        </div>
                                                        <div class="col-4">
                                                            <label class="col-form-label col-form-label-sm"><?php echo $lang_arr['width']?> (<?php echo $lang_arr['units']?>)</label>
                                                            <input @input="size_change(index)" class="form-control form-control-sm" v-model="variant.width" type="number" min="0" step="1">
                                                        </div>
                                                        <div class="col-4">
                                                            <label class="col-form-label col-form-label-sm"><?php echo $lang_arr['height']?> (<?php echo $lang_arr['units']?>)</label>
                                                            <input @input="size_change(index)" class="form-control form-control-sm" v-model="variant.height" type="number" min="0" step="1">
                                                        </div>
                                                        <div class="col-4">
                                                            <label class="col-form-label col-form-label-sm"><?php echo $lang_arr['depth']?> (<?php echo $lang_arr['units']?>)</label>
                                                            <input @input="size_change(index)" class="form-control form-control-sm" v-model="variant.depth" type="number" min="0" step="1">
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="col-form-label col-form-label-sm"><?php echo $lang_arr['module_width']?></label>
                                                            <input class="form-control form-control-sm"  v-model="variant.module_width" min="0" step="1" type="number">
                                                        </div>

<!--                                                        <span @click="select_variant(index)" class="select_variant fa fa-eye btn btn-outline btn-primary"></span>-->

                                                        <button v-show="index > 0" @click="remove_variant(index)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                            <span class="glyphicon glyphicon-trash"></span>
                                                        </button>
                                                    </div>
                                                </div>


                                            </draggable>

                                            <div class="row form-group">
                                                <div class="col-12">
                                                    <button @click="add_variant()" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add']?></button>
                                                </div>
                                            </div>

                                        </div>
                                        <div v-show="model_tab == 1">




                                            <div class="row ">
                                                <div class="col-4 form-group">
                                                    <label><?php echo $lang_arr['width']?> (<?php echo $lang_arr['units']?>)</label>
                                                    <input @input="size_change()" class="form-control" v-model="item.variants[0].width" type="number">
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label><?php echo $lang_arr['height']?> (<?php echo $lang_arr['units']?>)</label>
                                                    <input @input="size_change()" class="form-control" v-model="item.variants[0].height" type="number">
                                                </div>
                                                <div class="col-4 form-group">
                                                    <label><?php echo $lang_arr['depth']?> (<?php echo $lang_arr['units']?>)</label>
                                                    <input @input="size_change()" class="form-control" v-model="item.variants[0].depth" type="number">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label ><?php echo $lang_arr['color']?></label>
                                                    <input type="text" class="form-control" name="color" id="color" value="#ffffff">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['roughness']?></label>
                                                    <input @input="material_change()" type="number"  min="0" max="1" step="0.01" class="form-control" v-model.number="item.material.params.roughness"  placeholder="0.15">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['metalness']?></label>
                                                    <input @input="material_change()" type="number" min="0" max="1" step="0.01" class="form-control"  v-model.number="item.material.params.metalness" placeholder="0.15">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 form-group">
                                                    <label style="display: block"><?php echo $lang_arr['texture_file']?></label>
                                                    <div class="icon_block">
                                                        <img @click="$refs.map_file.click()" style="max-width: 78px" :src="get_map_src(map_file)" alt="">
                                                        <i @click="$refs.map_file.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                        <i v-if="item.material.params.map != '' || map_file != ''" @click="remove_map_file()" class="fa fa-trash delete_file"></i>
                                                    </div>

                                                    <div class="hidden">
                                                        <input ref="map_file" accept="image/jpeg,image/png,image/gif" @change="process_map_file($event)" type="file">
                                                        <!--                                                    <file-input ref="map_file" accept="image/jpeg,image/png,image/gif" v-model="map_file"></file-input>-->
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-show="item.material.params.map != '' || map_file != ''" class="texture_params">

                                                <div class="row">
                                                    <div class="col-6 form-group">
                                                        <label><?php echo $lang_arr['stretch_width']?></label>
                                                        <div>
                                                            <label class="switch">
                                                                <input @change="material_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.material.add_params.stretch_width" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <label><?php echo $lang_arr['stretch_height']?></label>
                                                        <div>
                                                            <label class="switch">
                                                                <input @change="material_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.material.add_params.stretch_height" type="checkbox">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6 form-group">
                                                        <label><?php echo $lang_arr['texture_real_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                        <input @input="material_change()" v-model="item.material.add_params.real_width" :disabled="item.material.add_params.stretch_width == 1" type="number" class="form-control" placeholder="256">
                                                    </div>
                                                    <div class="col-6 form-group">
                                                        <label><?php echo $lang_arr['texture_real_heght']?> (<?php echo $lang_arr['units']?>)</label>
                                                        <input @input="material_change()" v-model="item.material.add_params.real_width" :disabled="item.material.add_params.stretch_height == 1" type="number" class="form-control" placeholder="256">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-group">
                                                        <label ><?php echo $lang_arr['wrapping_type']?></label>
                                                        <select @change="material_change()" v-model="item.material.add_params.wrapping" class="form-control" >
                                                            <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror']?></option>
                                                            <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat']?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-6">
                                        <div id="bplanner_app">
                                            <div id="viewport">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-4" class="tab-pane">
                            <div class="panel-body models_list">

                                <div class=" row">
                                    <div class="col-12"><h3><?php echo $lang_arr['additional_materials']?></h3></div>
                                </div>

                                <div class="tabs-container">

                                    <div class="tabs-left">

                                        <ul class="nav nav-tabs tabs-inner">
                                            <li @click="active_material_tab = name" v-for="(item, name) in item.self_additional_materials">
                                                <a v-bind:class="{ active: active_material_tab == name }" class="nav-link">{{item.name}}</a>
                                            </li>
                                            <li><button @click="add_material()" type="button" class="btn btn-w-m btn-primary btn-outline btn-block"><?php echo $lang_arr['add']?></button></li>
                                        </ul>

                                        <div class="tab-content">

                                            <div v-bind:class="{ active: active_material_tab == name }" v-show="active_material_tab == name" v-for="(item, name) in item.self_additional_materials"
                                                 class="tab-pane">
                                                <div class="panel-body facades_panel_body container-fluid">

                                                    <div class="row form-group">
                                                        <div class="col-10">
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name']?></label>
                                                                <div class="col-8">
                                                                    <input v-model="item.name" class="form-control" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['key']?></label>
                                                                <div class="col-8">
                                                                    <input v-model="item.key" class="form-control" type="text">
                                                                </div>
                                                            </div>

                                                            <div style="display: none" class="form-group row">
                                                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['material_required']?></label>
                                                                <div class="col-sm-8">
                                                                    <label class="switch">
                                                                        <input v-bind:true-value="0" v-bind:false-value="1" v-model="item.required" type="checkbox">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['material_fixed']?></label>
                                                                <div class="col-sm-8">
                                                                    <label class="switch">
                                                                        <input v-bind:true-value="0" v-bind:false-value="1" v-model="item.fixed" type="checkbox">
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div v-if="item.fixed == 0" class="form-group row">
                                                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['additional_materials_categories']?></label>
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

                                                            <div v-if="item.fixed != 1 && item.materials.length || item.fixed == 1" class="form-group row">

                                                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_material']?></label>

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
                                                                            <div style="width: 50px; height: 50px;margin-right: 5px; display: inline-block" :style="{ 'background':  get_map(option)  }"></div><div style="display: inline-block; vertical-align: middle">{{option.name}}</div>
                                                                        </template>

                                                                        <template v-slot:option="option">
                                                                            <div style="width: 50px; height: 25px;margin-right: 5px; display: inline-block;vertical-align: middle" :style="{ 'background':  get_map(option)  }"></div><div style="display: inline-block; vertical-align: middle">{{option.name}}</div>
                                                                        </template>
                                                                    </v-select>



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
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items/tech') ?>"><?php echo $lang_arr['cancel']?></a>
                                <?php if (isset($id)):?>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                                <?php else:?>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                                <?php endif;?>
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
                    <filemanager ref="fileman"></filemanager>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>

                </div>
            </div>
        </div>
    </div>


</form>






<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">


<style>

    #viewport{
        max-width: 100%;
        position: relative;
        height: 100%;
        max-height: 500px;

    }

    label{
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
    }

    .category_select button{
        text-align: left;
    }

    .category_select ul{
        list-style: none;
        margin: 0;
        padding: 0 0 0 30px;
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
    .handle{
        position: absolute;
        z-index: 9;
        top: 11px;
        left: 3px;
        font-size: 15px!important;
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

    .radial_menu{
        display: none!important;
    }

    .clear_model{
        position: absolute;
        right: 20px;
        top: 28px;
    }

    .select_variant{
        position: absolute;
        left: 3px;
        top: 60px;
        padding: 5px 5px 5px 4px;
    }

    .del_btn{
        position: absolute;
        left: 5px;
        bottom: 5px;
        padding: 5px 5px 5px 4px;
    }

</style>




<?php include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php';?>
<script>
    console.log(view_mode)
    view_mode = true;
</script>

<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>

<script src="/common_assets/admin_js/vue/filemanager2.js"></script>

<!--<script src="/common_assets/libs/three106.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/obj_export.js" type="text/javascript"></script>-->

<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<script src="/common_assets/libs/jszip.min.js"></script>
<script src="/common_assets/libs/jszip.utils.min.js"></script>



<script src="/common_assets/admin_js/functions.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/3d_model_new.js"></script>
<!--<script src="/common_assets/js/v4/functions.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/globals.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Parts.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Facade_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Model_cache.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>-->


<!--<script src="/common_assets/admin_js/production/facades/facades_model.js?--><?php //echo md5(date('m-d-Y-His A e'));?><!--"></script>-->

