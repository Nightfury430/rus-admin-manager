<div v-cloak id="sub_form">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{lang('models')}}</h2>
        </div>
        <div class="col-lg-2">

        </div>
    </div>


    <div class="wrapper wrapper-content  animated fadeInRight">


        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">

                    <div class="ibox-content">

                        <a v-show="controller == 'module_sets'" class="btn btn-w-m btn-default" href="<?php echo site_url('module_sets/sets_index/') ?>" role="button"><?php echo $lang_arr['back_to_modules_sets']?></a>

                        <button v-show="check_controller()" @click="show_add_modal()" class="mb-3 btn btn-w-m btn-primary btn-outline" type="button">{{lang('add')}}</button>

                        <table class="table table-hover table-bordered" >
                            <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Изображение</th>
                                <th>Название</th>
                                <th>Артикул</th>
                                <th>Категория</th>
                                <th>Доступные материалы</th>
                                <th>Порядок</th>
                                <th>Видимость</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="text-center" v-for="(item, index) in items">
                                <td>{{item.id}}</td>
                                <td><img style="max-width: 100px" :src="correct_url(item.icon)"></td>
                                <td>{{item.name}}</td>
                                <td>{{item.code}}</td>
                                <td>
                                    <span v-if="$options.categories_hash[item.category].parent && $options.categories_hash[item.category].parent != 0">
                                        {{$options.categories_hash[$options.categories_hash[item.category].parent].name}} /
                                    </span>
                                    {{$options.categories_hash[item.category].name}}</td>
                                <td>
                                    <p v-for="mat in item.materials">

                                        <span v-if="$options.materials_hash[mat].parent && $options.materials_hash[mat].parent != 0">
                                        {{$options.materials_hash[$options.materials_hash[mat].parent].name}} /
                                        </span>

                                        {{$options.materials_hash[mat].name}}
                                    </p>
                                </td>
                                <td>
                                    {{item.order}}
                                </td>
                                <td>
                                    <i @click="change_active(item)" :class="get_eye_class(item)" class="fa fa-eye btn btn-outline"></i>
                                </td>
                                <td>
                                    <i @click="show_add_modal(item)" class="fa fa-edit btn btn-outline btn-success"></i>
                                    <i @click="show_swal(item, index)" class="fa fa-trash btn btn-outline btn-danger"></i>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <button v-show="check_controller()" @click="show_add_modal()" class="mt-3 btn btn-w-m btn-primary btn-outline" type="button">{{lang('add')}}</button>

                    </div>
                </div>


            </div>
        </div>
    </div>
    <div v-show="modals.add.show" class="bpl_modal_wrapper">
        <div class="bpl_modal_background" :class="{shown: modals.add.show}"></div>
        <div class="bpl_modal_content full">
            <div class="modal-dialog modal-xl">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button @click="modals.add.show = false" type="button" class="close"><span>&times;</span></button>
                        <h4 class="modal-title">{{$options.lang['add']}}</h4>
                    </div>
                    <div class="modal-body">


                        <div class="tabs-container">
                            <ul class="nav nav-tabs">
                                <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params']?></a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['price']?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                    <div class="panel-body">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name']?></label>
                                            <div class="col-sm-10">
                                                <input v-model="current_item.name" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['code']?></label>
                                            <div class="col-sm-10">
                                                <input v-model="current_item.code" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category']?></label>
                                            <div class="col-sm-10">
                                                <v-select
                                                        :clearable="false"
                                                        :value="current_item.category"
                                                        label="name"
                                                        :options="$options.cat_ordered"
                                                        :reduce="category => category.id"
                                                        v-model="current_item.category"
                                                        :key="current_item.category"
                                                >
                                                    <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{$options.categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>

                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order']?></label>
                                            <div class="col-sm-10">
                                                <input v-model="current_item.order" type="number" class="form-control" >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active']?></label>
                                            <div class="col-sm-10">
                                                <label class="switch">
                                                    <input v-model="current_item.active" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
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
                                                    <i v-if="current_item.icon != '' || icon_file != ''" @click="icon_file = ''; $refs.icon_file.value = ''" class="fa fa-trash delete_file"></i>
                                                </div>

                                                <div class="hidden">
                                                    <input type="file" ref="icon_file" accept="image/jpeg,image/png,image/gif" @change="process_icon_file($event)" >
                                                </div>

                                            </div>
                                            <div class="col-sm-5">

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div id="tab-2" class="tab-pane">
                                    <div class="panel-body">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{lang_data['model_file']}}</label>
                                            <div class="col-sm-10">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input id="inputGroupFile01" type="file" accept=".fbx" @change="process_model($event,0)" class="custom-file-input">
                                                        <label class="custom-file-label" for="inputGroupFile01">

                                                            <span v-if="model_file.name">{{model_file.name}}</span>
                                                            <span v-else-if="current_item.params.model">{{current_item.params.model}}</span>
                                                            <span v-else>{{lang_data['no']}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{lang_data['height']}}, {{lang_data['units']}}</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="current_item.params.height" min="0" step="1" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{lang_data['depth']}}, {{lang_data['units']}}</label>
                                            <div class="col-sm-2">
                                                <input type="number" v-model="current_item.params.depth" min="0" step="1" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{lang_data['available_materials']}}</label>
                                            <div class="col-sm-10">
                                                <v-select
                                                        multiple
                                                        label="name"
                                                        :value="current_item.materials"
                                                        :options="$options.materials_ordered"
                                                        :reduce="category => category.id"
                                                        :key="current_item.materials"
                                                        v-model="current_item.materials"
                                                        @input="materials_categories_change($event)"
                                                >
                                                    <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{$options.materials_hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <div id="tab-3" class="tab-pane">
                                    <div class="panel-body">
                                        <div class=" row">
                                            <div class="col-12"><h3><?php echo $lang_arr['prices']?></h3></div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-12">
                                                <table class="table table-bordered table-hover ">
                                                    <thead>
                                                    <tr>
                                                        <th>1</th>
                                                        <th>2</th>


                                                    </tr>
                                                    </thead>
                                                    <tbody>


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="modal-footer">
                        <button @click="modals.add.show = false" type="button" class="btn btn-white">{{$options.lang['cancel']}}</button>
                        <button @click="add_item()" type="button" class="btn btn-primary">{{$options.lang['add']}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <?php if(isset($set_id)):?>
        <input id="set_id" ref="set" value="<?php echo $set_id ?>" type="hidden">
    <?php endif;?>

    <input class="cat_controller" ref="controller" value="<?php echo $controller ?>" type="hidden">
</div>

<style>
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
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/items.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>