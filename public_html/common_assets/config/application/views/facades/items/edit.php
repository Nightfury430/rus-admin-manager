<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <?php if (isset($id)):?>
            <h2><?php echo $lang_arr['facade_edit_heading']?></h2>
        <?php else:?>
            <h2><?php echo $lang_arr['facade_add']?></h2>
        <?php endif;?>

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

<div id="sub_form" class="wrapper wrapper-content  animated fadeInRight">
    <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                <li><a class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['models']?></a></li>
                <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['facade_types_double']?></a></li>
                <li><a class="nav-link" data-toggle="tab" href="#tab-4"><?php echo $lang_arr['facade_types_triple']?></a></li>
                <li><a class="nav-link" data-toggle="tab" href="#tab-5"><?php echo $lang_arr['available_materials']?></a></li>
                <li><a class="nav-link" data-toggle="tab" href="#tab-6"><?php echo $lang_arr['prices']?></a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                            <div class="col-sm-10">
                                <input v-model="item.name" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('code')}}</label>
                            <div class="col-sm-10">
                                <input v-model="item.code" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('category')}}</label>
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

                                    <template #selected-option="option">
                                    <span style="pointer-events: none" :title="option.name" :class="{'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0" class="font-weight-bold">{{$options.categories_hash[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                    </template>

                                    <template v-slot:option="option">
                                    <span :title="option.name" :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.categories_hash[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                    </template>

                                </v-select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('order')}}</label>
                            <div class="col-sm-10">
                                <input v-model="item.order" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('kitchen_handles_label')}}</label>
                            <div class="col-sm-10">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.handle" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('handle_offset')}}</label>
                            <div class="col-sm-10">
                                <input placeholder="25" v-model="item.handle_offset" type="text" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('active')}}</label>
                            <div class="col-sm-10">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.active" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('icon')}}</label>
                            <div class="col-sm-5">

                                <div class="icon_block">
                                    <img @click="$refs.icon_file.click()" style="max-width: 78px" :src="get_icon_src(icon_file)" alt="">
                                    <i @click="$refs.icon_file.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                    <i v-if="icon_file != ''" @click="item.icon_file = ''; $refs.icon_file.value = ''" class="fa fa-trash delete_file"></i>
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

                    </div>
                </div>
                <div id="tab-3" class="tab-pane">
                    <div class="panel-body">

                    </div>
                </div>
                <div id="tab-4" class="tab-pane">
                    <div class="panel-body">

                    </div>
                </div>
                <div id="tab-5" class="tab-pane">
                    <div class="panel-body">

                    </div>
                </div>
                <div id="tab-6" class="tab-pane">
                    <div class="panel-body">

                    </div>
                </div>
            </div>
        </div>
</div>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/facades_new.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>