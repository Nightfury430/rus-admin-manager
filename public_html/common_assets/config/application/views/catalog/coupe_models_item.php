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

                        <div class="tabs-container">
                            <ul class="nav nav-tabs">
                                <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params']?></a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['prices_and_materials']?></a></li>
                                <li><a class="nav-link" data-toggle="tab" href="#tab-4"><?php echo $lang_arr['accessories']?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                    <div class="panel-body">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name']?></label>
                                            <div class="col-sm-10">
                                                <input v-model="item.name" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['code']?></label>
                                            <div class="col-sm-10">
                                                <input v-model="item.code" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category']?></label>
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
                                                    <template v-slot:option="option">
                                                        <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{$options.categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>

                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['description']?></label>
                                            <div class="col-sm-10">
                                                <textarea v-model="item.description" class="form-control" ></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order']?></label>
                                            <div class="col-sm-10">
                                                <input v-model="item.order" type="number" class="form-control" >
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active']?></label>
                                            <div class="col-sm-10">
                                                <label class="switch">
                                                    <input v-model="item.active" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
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
                                                    <i v-if="item.icon != '' || icon_file != ''" @click="icon_file = ''; $refs.icon_file.value = ''" class="fa fa-trash delete_file"></i>
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
                                            <label class="col-sm-4 col-form-label">{{lang('model_file')}} {{lang('handle')}}</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input id="inputGroupFile01" type="file" accept=".fbx" @change="process_model($event,'handle')" class="custom-file-input">
                                                        <label class="custom-file-label" for="inputGroupFile01">

                                                            <span v-if="model_file.handle.name">{{model_file.handle.name}}</span>
                                                            <span v-else-if="item.params.models.handle">{{item.params.models.handle}}</span>
                                                            <span v-else>{{lang('no')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{lang('model_file')}} {{lang('top')}}</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input id="inputGroupFile02" type="file" accept=".fbx" @change="process_model($event,'top')" class="custom-file-input">
                                                        <label class="custom-file-label" for="inputGroupFile02">

                                                            <span v-if="model_file.top.name">{{model_file.top.name}}</span>
                                                            <span v-else-if="item.params.models.top">{{item.params.models.top}}</span>
                                                            <span v-else>{{lang('no')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{lang('model_file')}} {{lang('bottom')}}</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input id="inputGroupFile03" type="file" accept=".fbx" @change="process_model($event,'bottom')" class="custom-file-input">
                                                        <label class="custom-file-label" for="inputGroupFile03">

                                                            <span v-if="model_file.bottom.name">{{model_file.bottom.name}}</span>
                                                            <span v-else-if="item.params.models.bottom">{{item.params.models.bottom}}</span>
                                                            <span v-else>{{lang('no')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{lang('model_file')}} {{lang('divider')}}</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input id="inputGroupFile04" type="file" accept=".fbx" @change="process_model($event,'divider')" class="custom-file-input">
                                                        <label class="custom-file-label" for="inputGroupFile04">

                                                            <span v-if="model_file.divider.name">{{model_file.divider.name}}</span>
                                                            <span v-else-if="item.params.models.divider">{{item.params.models.divider}}</span>
                                                            <span v-else>{{lang('no')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>






                                    </div>
                                </div>
                                <div id="tab-3" class="tab-pane">
                                    <div class="panel-body">
                                        <div class="row form-group">
                                            <div class="col-12">

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">{{lang('available_materials')}}</label>
                                                    <div  class="col-sm-10">
                                                        <v-select
                                                            multiple
                                                            label="name"
                                                            :value="item.params.materials"
                                                            :options="$options.materials_categories_ordered"
                                                            :reduce="category => category.id"
                                                            v-model="item.params.materials"
                                                            @input="materials_categories_change($event)"
                                                        >
                                                            <template v-slot:option="option">
                                                        <span  :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{$options.materials_categories_hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                                                            </template>
                                                        </v-select>
                                                    </div>
                                                </div>

                                                <table class="table table-bordered table-hover ">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>{{lang('handle')}}</th>
                                                        <th>{{lang('top')}}</th>
                                                        <th>{{lang('bottom')}}</th>
                                                        <th>{{lang('divider')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(row,key) in item.params.prices">
                                                        <td>{{$options.materials_categories_hash[key].name}}</td>
                                                        <td><input class="form-control" type="number" step="0.01" min="0" v-model="row.handle" ></td>
                                                        <td><input class="form-control" type="number" step="0.01" min="0" v-model="row.top" ></td>
                                                        <td><input class="form-control" type="number" step="0.01" min="0" v-model="row.bottom" ></td>
                                                        <td><input class="form-control" type="number" step="0.01" min="0" v-model="row.divider" ></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-4" class="tab-pane">
                                    <div class="panel-body">
                                        <div class="row form-group">
                                            <div class="col-12">
                                                <div class="tabs-container">

                                                    <div class="tabs-left">

                                                        <ul class="nav nav-tabs tabs-inner">
                                                            <li @click="active_tab = index" v-for="(tab, index) in item.params.accessories">
                                                                <a v-bind:class="{ active: active_tab == index }" class="nav-link"><?php echo $lang_arr['door_width_from']?> (<?php echo $lang_arr['units']?>) {{tab.min_width}}</a>
                                                            </li>
                                                            <li> <button @click="add_accessories_size()" type="button" class="btn btn-w-m btn-primary btn-block btn-outline"><?php echo $lang_arr['add']?></button></li>
                                                        </ul>

                                                        <div class="tab-content">

                                                            <div v-bind:class="{ active: active_tab == index }" v-show="active_tab == index" v-for="(tab, index) in item.params.accessories" class="tab-pane">
                                                                <div class="panel-body facades_panel_body container-fluid">

                                                                    <div class="row form-group">
                                                                        <div class="col-10">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm-4 col-form-label"><label><?php echo $lang_arr['door_width_from']?> (<?php echo $lang_arr['units']?>).</label></label>
                                                                                <div class="col-8">
                                                                                    <input v-model="tab.min_width" class="form-control" type="text">
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="col-2">
                                                                            <div class="actions_block d-flex justify-content-end">
                                                                                <button @click="show_swal('size', index)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="hr-line-dashed"></div>

                                                                    <div class="row form-group">
                                                                        <div class="col-12">
                                                                            <h4><?php echo $lang_arr['accessories']?></h4>
                                                                        </div>
                                                                    </div>



                                                                    <div class="row form-group ">
                                                                        <div class="col-12 ">

                                                                            <table v-if=" item.params.accessories[index]" class="table table-bordered table-hover ">
                                                                                <thead>
                                                                                <tr>
                                                                                    <th>{{lang('name')}}</th>
                                                                                    <th>{{lang('code')}}</th>
                                                                                    <th>{{lang('category')}}</th>
                                                                                    <th>{{lang('price')}}</th>
                                                                                    <th>{{lang('count')}}</th>
                                                                                    <th>{{lang('delete')}}</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr v-for="(it, ind) in item.params.accessories[index].items">
                                                                                    <td>{{$options.accessories_items_hash[ind].name}}</td>
                                                                                    <td>{{$options.accessories_items_hash[ind].code}}</td>
                                                                                    <td>
                                                                                        <span v-if="$options.accessories_categories_hash[$options.accessories_items_hash[ind].category]">{{$options.accessories_categories_hash[$options.accessories_items_hash[ind].category].name}}</span>
                                                                                        <span v-else>{{lang('no')}}</span>
                                                                                    </td>
                                                                                    <td>{{$options.accessories_items_hash[ind].price}}</td>
                                                                                    <td class="text-center">{{it}}</td>
                                                                                    <td class="text-center">
                                                                                        <button @click="show_swal('accessory', ind)" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                                                            <span class="glyphicon glyphicon-trash"></span>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>



                                                                        </div>
                                                                    </div>

                                                                    <div class="row form-group">
                                                                        <div class="col-12">
                                                                            <button @click="acc_modal = true"  type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add']?></button>
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
                                <a href="<?php echo site_url('catalog/items/coupe_profile') ?>" class="btn btn-white btn-sm" ><?php echo $lang_arr['cancel']?></a>
                                <?php if (isset($id)):?>
                                    <button @click="submit()" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['save']?></button>
                                <?php else:?>
                                    <button @click="submit()" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add']?></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div v-show="acc_modal" class="bpl_modal_wrapper">
        <div class="bpl_modal_background" :class="{shown: acc_modal}"></div>
        <div class="bpl_modal_content full">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header">
                        <button @click="acc_modal = false" type="button" class="close"><span>&times;</span></button>
                        <h4 class="modal-title">{{lang['add']}}</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-3">
                                <h4>{{lang('categories')}}</h4>
                                <ul class="cat_list">
                                    <li :class="{active: acc_cat == cat.id, sub: cat.parent != 0}" @click="acc_cat = cat.id" v-for="cat in $options.accessories_categories_ordered">{{cat.name}}</li>
                                </ul>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered table-hover ">
                                    <thead>
                                    <tr>
                                        <th>{{lang('name')}}</th>
                                        <th>{{lang('code')}}</th>
                                        <th>{{lang('category')}}</th>
                                        <th>{{lang('price')}}</th>
                                        <th>{{lang('add')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in computed_accessories">
                                        <td>{{item.name}}</td>
                                        <td>{{item.code}}</td>
                                        <td>
                                            <span v-if="$options.accessories_categories_hash[item.category]">{{$options.accessories_categories_hash[item.category].name}}</span>
                                            <span v-else>{{lang('no')}}</span>
                                        </td>
                                        <td>{{item.price}}</td>
                                        <td  class="text-center"><i @click="add_accessory(item.id)" class="fa fa-plus btn btn-outline btn-primary"></i></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>




                    </div>

                    <div class="modal-footer">
                        <button @click="acc_modal = false" type="button" class="btn btn-white">{{lang['cancel']}}</button>
                        <button @click="add_item()" type="button" class="btn btn-primary">{{lang['add']}}</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" id="item_id" value="<?php echo $id?>">
</div>


<style>
    .cat_list{
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .cat_list li{
        margin: 0;
        padding: 0;
        list-style: none;
        cursor: pointer;
    }
    .cat_list li.active{
        color: red;
    }

    .cat_list li.sub{
        padding-left: 20px;
    }


</style>







<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<!--<script src="/common_assets/libs/vue.min.js"></script>-->
<script src="/common_assets/libs/vue.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<!--<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>-->
<!--<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>-->
<script src="/common_assets/admin_js/vue/coupe_profile_item.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
