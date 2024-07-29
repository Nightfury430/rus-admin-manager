<div id="sub_form">
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2><?php echo $lang_arr['kitchen_model_add']?></h2>
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
    <!--common-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params'] ?></h4>
            </div>
            <div class="ibox-content">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name']?></label>
                    <div class="col-sm-8">
                        <input v-model="item.name" type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['icon'] ?></label>
                    <div class="col-sm-5">

                        <div class="icon_block">
                            <img @click="$refs.icon_file.click()" style="max-width: 78px" :src="get_icon_src(icon_file)" alt="">
                            <i @click="$refs.icon_file.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                            <i v-if="item.icon != '' || icon_file != ''" @click="icon_file = ''; $refs.icon_file.value = ''" class="fa fa-trash delete_file"></i>
                        </div>

                        <div class="hidden">
                            <input type="file" ref="icon_file" accept="image/jpeg,image/png,image/gif" @change="process_icon_file($event)">
                        </div>

                    </div>
                    <div class="col-sm-3">

                    </div>
                </div>


                <input type="hidden" value="2" name="door_offset">
                <input type="hidden" value="10" name="shelve_offset">
                <input type="hidden" value="16" name="corpus_thickness">
                <input type="hidden" value="720" name="bottom_modules_height">


                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['active']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.active" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['order']?></label>
                    <div class="col-sm-8">
                        <input v-model="item.order" type="number" class="form-control" >
                    </div>
                </div>

            </div>
        </div>

    <!--facades-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['facades_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facades_bottom_as_top']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input @change="change_bottom_as_top_facade($event)" v-model="item.bottom_as_top_facade_models" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['top_modules_facade_model'] ?></label>
                    <div class="col-sm-8">
                        <v-select
                                :clearable="false"
                                :value="item.facades_models_top"
                                label="name"
                                :options="$options.facades_lib.items_arr"
                                :reduce="item => item.id"
                                v-model="item.facades_models_top"
                                :key="item.id"
                                @input="select_top_facade($event)"
                        >
                            <template #selected-option="option">
                                <div>
                                    <img style="max-height: 20px; margin-right: 10px;" :src="correct_url(option.icon)">
                                    <span v-if="$options.facades_lib.get_category(option.category).parent != 0">{{$options.facades_lib.categories[$options.facades_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.facades_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>
                            </template>

                            <template v-slot:option="option">
                                <div>
                                    <img style="max-width: 40px" :src="correct_url(option.icon)">
                                    <span v-if="$options.facades_lib.get_category(option.category).parent != 0">{{$options.facades_lib.categories[$options.facades_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.facades_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>

                            </template>
                        </v-select>
                    </div>
                </div>
                <div v-show="item.bottom_as_top_facade_models == 0" class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['bottom_modules_facade_model'] ?></label>
                    <div class="col-sm-8">
                        <v-select
                                :clearable="false"
                                :value="item.facades_models_top"
                                label="name"
                                :options="$options.facades_lib.items_arr"
                                :reduce="item => item.id"
                                v-model="item.facades_models_bottom"
                                :key="item.id"
                        >
                            <template #selected-option="option">
                                <div>
                                    <img style="max-height: 20px; margin-right: 10px;" :src="correct_url(option.icon)">
                                    <span v-if="$options.facades_lib.get_category(option.category).parent != 0">{{$options.facades_lib.categories[$options.facades_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.facades_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>
                            </template>

                            <template v-slot:option="option">
                                <div>
                                    <img style="max-width: 40px" :src="correct_url(option.icon)">
                                    <span v-if="$options.facades_lib.get_category(option.category).parent != 0">{{$options.facades_lib.categories[$options.facades_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.facades_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>
                            </template>
                        </v-select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facades_materials_bottom_as_top']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.bottom_as_top_facade_materials" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="facades_selected_materials_top"><?php echo $lang_arr['top_facades_materials']?>*</label>
                            <select class="form-control" name="facades_selected_material_top" id="facades_selected_materials_top">
                                <option value="">--- <?php echo $lang_arr['choose_facade_material']?> ---</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group hidden" id="facades_selected_materials_bottom_wrapper">
                            <label for="facades_selected_materials_bottom"><?php echo $lang_arr['bottom_facades_materials']?>*</label>
                            <select class="form-control" name="facades_selected_material_bottom" id="facades_selected_materials_bottom">
                                <option value="">--- <?php echo $lang_arr['choose_facade_material']?> ---</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_facades_materials_select']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.allow_facades_materials_select" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>


            </div>
        </div>

    <!--glass-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['glass_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="glass_materials"><?php echo $lang_arr['available_glass_materials'] ?>*</label>
                    <div class="col-sm-8">
                        <v-select
                                multiple
                                label="name"
                                :value="item.glass_materials"
                                :options="$options.glass_lib.categories_arr"
                                :reduce="category => category.id"
                                v-model="item.glass_materials"
                                @input="glass_categories_change($event, 'glass_lib', 'selected_glass_material')"
                        >
                            <template #selected-option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.glass_lib[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                            <template v-slot:option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.glass_lib[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="selected_glass_material"><?php echo $lang_arr['default_glass_material'] ?></label>
                    <div class="col-sm-8">
                        <v-select
                                label="name"
                                :value="item.selected_glass_material"
                                :options="computed_glass_materials"
                                :reduce="category => category.id"
                                v-model="item.selected_glass_material"
                                :key="item.selected_glass_material"
                        >
                            <template #selected-option="option">
                                <div>
                                    <span v-if="$options.glass_lib.get_category(option.category).parent != 0">{{$options.glass_lib.categories[$options.glass_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.glass_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>
                            </template>

                            <template v-slot:option="option">
                                <div>
                                    <span v-if="$options.glass_lib.get_category(option.category).parent != 0">{{$options.glass_lib.categories[$options.glass_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.glass_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>

                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_glass_materials_select']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.allow_glass_materials_select" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>


            </div>
        </div>

    <!--corpus-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['corpus_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['bottom_as_top_corpus_materials']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input @change="change_bottom_as_top_corpus($event)" v-model="item.bottom_as_top_corpus_materials" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="glass_materials"><?php echo $lang_arr['available_corpus_materials'] ?>*</label>
                    <div class="col-sm-8">
                        <v-select
                                multiple
                                auto
                                label="name"
                                :value="item.corpus_materials_top"
                                :options="$options.materials_lib.parent_cats"
                                :reduce="category => category.id"
                                v-model="item.corpus_materials_top"

                        >
                            <template #selected-option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                            <template v-slot:option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                        </v-select>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="selected_corpus_material_top"><?php echo $lang_arr['default_top_corpus_material'] ?>*</label>
                    <div class="col-sm-8">
                        <v-select
                                label="name"
                                :value="item.selected_corpus_material_top"
                                :options="computed_corpus_materials"
                                :reduce="category => category.id"
                                v-model="item.selected_corpus_material_top"
                                :key="item.selected_corpus_material_top"
                                @input="select_top_corpus($event)"
                        >
                            <template #selected-option="option">
                                <div>
                                    <span style="display: inline-block; width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                    <span v-if="option.code != '' && option.code != null"> - {{option.code}}</span>
                                </div>
                            </template>

                            <template v-slot:option="option">
                                <div>
                                    <span style="display: inline-block; width: 40px; height: 40px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                    <span v-if="option.code != '' && option.code != null"> - {{option.code}}</span>
                                </div>

                            </template>
                        </v-select>
                    </div>
                </div>
                <div v-show="item.bottom_as_top_corpus_materials == 0" class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_bottom_corpus_material'] ?></label>
                    <div class="col-sm-8">
                        <v-select
                                label="name"
                                :value="item.selected_corpus_material_bottom"
                                :options="computed_corpus_materials"
                                :reduce="category => category.id"
                                v-model="item.selected_corpus_material_bottom"
                                :key="item.selected_corpus_material_bottom"
                        >
                            <template #selected-option="option">
                                <div>
                                    <span style="display: inline-block; width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>
                            </template>

                            <template v-slot:option="option">
                                <div>
                                    <span style="display: inline-block; width: 40px; height: 40px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                </div>

                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_corpus_materials_select']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.allow_corpus_materials_select" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

    <!--cokol-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['cokol_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['no_cokol']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.cokol_active" v-bind:true-value="0" v-bind:false-value="1" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cokol_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                    <div class="col-sm-8">
                        <input v-model="item.cokol_height" type="number" min="0" max="1000" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['available_cokol_materials'] ?>*</label>
                    <div class="col-sm-8">
                        <v-select
                                multiple
                                auto
                                label="name"
                                :value="item.cokol_materials"
                                :options="$options.materials_lib.parent_cats"
                                :reduce="category => category.id"
                                v-model="item.cokol_materials"

                        >
                            <template #selected-option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                            <template v-slot:option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cokol_as_corpus']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.cokol_as_corpus" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div v-show="item.cokol_as_corpus == 0" class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cokol_default_material'] ?></label>
                    <div class="col-sm-8">
                        <v-select
                                label="name"
                                :value="item.selected_cokol_material"
                                :options="computed_cokol_materials"
                                :reduce="category => category.id"
                                v-model="item.selected_cokol_material"
                                :key="item.selected_cokol_material"
                        >
                            <template #selected-option="option">
                                <div>
                                    <span style="display: inline-block; width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                    <span v-if="option.code != '' && option.code != null"> - {{option.code}}</span>
                                </div>
                            </template>

                            <template v-slot:option="option">
                                <div>
                                    <span style="display: inline-block; width: 40px; height: 40px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                    <span v-if="option.code != '' && option.code != null"> - {{option.code}}</span>
                                </div>

                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_cokol_material_select']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.allow_cokol_materials_select" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

    <!--tabletop-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['tabletop_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_tabletop_thickness']?> (<?php echo $lang_arr['units']?>)</label>
                    <div class="col-sm-8">
                        <input v-model="item.tabletop_thickness" type="number" min="0" max="1000" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['available_tabletop_materials'] ?>*</label>
                    <div class="col-sm-8">
                        <v-select
                                multiple
                                auto
                                label="name"
                                :value="item.tabletop_materials"
                                :options="$options.materials_lib.parent_cats"
                                :reduce="category => category.id"
                                v-model="item.tabletop_materials"

                        >
                            <template #selected-option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                            <template v-slot:option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['tabletop_default_material'] ?></label>
                    <div class="col-sm-8">
                        <v-select
                                label="name"
                                :value="item.selected_tabletop_material"
                                :options="computed_tabletop_materials"
                                :reduce="category => category.id"
                                v-model="item.selected_tabletop_material"
                                :key="item.selected_tabletop_material"
                        >
                            <template #selected-option="option">
                                <div>
                                    <span style="display: inline-block; width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                    <span v-if="option.code != '' && option.code != null"> - {{option.code}}</span>
                                </div>
                            </template>

                            <template v-slot:option="option">
                                <div>
                                    <span style="display: inline-block; width: 40px; height: 40px; vertical-align: middle; margin-right: 5px;" :style="{ 'background':  get_map(option)}"></span>
                                    <span v-if="$options.materials_lib.get_category(option.category).parent != 0">{{$options.materials_lib.categories[$options.materials_lib.categories[option.category].parent].name}} / </span>
                                    <span>{{$options.materials_lib.get_category(option.category).name}} / </span>
                                    <span>{{option.name}}</span>
                                    <span v-if="option.code != '' && option.code != null"> - {{option.code}}</span>
                                </div>

                            </template>
                        </v-select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_tabletop_material_select']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.allow_tabletop_materials_select" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

    <!--wallpanel-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['wallpanel_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['wallpanel_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                    <div class="col-sm-8">
                        <input v-model="item.wallpanel_height" type="number" min="0" max="10000" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['wallpanel_available_materials'] ?>*</label>
                    <div class="col-sm-8">
                        <v-select
                                multiple
                                auto
                                label="name"
                                :value="item.wallpanel_materials"
                                :options="$options.materials_lib.parent_cats"
                                :reduce="category => category.id"
                                v-model="item.wallpanel_materials"

                        >
                            <template #selected-option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                            <template v-slot:option="option">
                                <div>
                                    <span :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                        <span v-if="option.parent != 0">{{$options.materials_lib.categories[option.parent].name}} / </span>{{ option.name }}
                                    </span>
                                </div>
                            </template>
                        </v-select>
                    </div>
                </div>

            </div>
        </div>

    <!--handles-->
        <div class="ibox">
            <div class="ibox-title">
                <h4 class="panel-title"><?php echo $lang_arr['handles_params']?></h4>
            </div>
            <div class="ibox-content">

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facades_no_handles']?></label>
                    <div class="col-sm-8">
                        <label class="switch">
                            <input v-model="item.no_handle" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>

                <div v-show="item.no_handle == 0">

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['handles_orientation']?></label>
                        <div class="col-sm-8">
                            <select v-model="item.handle_orientation"  class="form-control">
                                <option selected value="vertical"><?php echo $lang_arr['vertical']?></option>
                                <option value="horizontal"><?php echo $lang_arr['horizontal']?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['locker_handle_position']?></label>
                        <div class="col-sm-8">
                            <select v-model="item.handle_lockers_position"  class="form-control">
                                <option selected value="top"><?php echo $lang_arr['lockers_hande_top']?></option>
                                <option value="center"><?php echo $lang_arr['lockers_handle_center']?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['kitchen_handles_label'] ?></label>
                        <div class="col-sm-8">
                            <v-select
                                    multiple
                                    auto
                                    label="name"
                                    :value="item.available_handles"
                                    :options="$options.handles_lib.parent_cats"
                                    :reduce="category => category.id"
                                    v-model="item.available_handles"

                            >

                            </v-select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['handle_default_model'] ?></label>
                        <div class="col-sm-8">
                            <v-select
                                    :clearable="false"
                                    :value="item.handle_selected_model"
                                    label="name"
                                    :options="computed_handles"
                                    :reduce="item => item.id"
                                    v-model="item.handle_selected_model"
                                    :key="item.id"
                            >
                                <template #selected-option="option">
                                    <div>
                                        <img style="max-height: 20px; margin-right: 10px;" :src="correct_url(option.icon)">
                                        <span v-if="$options.handles_lib.get_category(option.category).parent != 0">{{$options.handles_lib.categories[$options.handles_lib.categories[option.category].parent].name}} / </span>
                                        <span>{{$options.handles_lib.get_category(option.category).name}} / </span>
                                        <span>{{option.name}}</span>
                                    </div>
                                </template>

                                <template v-slot:option="option">
                                    <div>
                                        <img style="max-width: 40px" :src="correct_url(option.icon)">
                                        <span v-if="$options.handles_lib.get_category(option.category).parent != 0">{{$options.handles_lib.categories[$options.handles_lib.categories[option.category].parent].name}} / </span>
                                        <span>{{$options.handles_lib.get_category(option.category).name}} / </span>
                                        <span>{{option.name}}</span>
                                    </div>

                                </template>
                            </v-select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['handle_default_size']?></label>
                        <div class="col-sm-8">
                            <select v-model="item.handle_preferable_size" class="form-control">
                                <option :value="index" v-for="(size, index) in computed_handles_sizes">{{lang('width')}} {{size.width}} {{lang('units')}} <template v-if="size.axis_size != ''">{{lang('axis_size')}} {{size.axis_size}} {{lang('units')}}</template></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_handles_select']?></label>
                        <div class="col-sm-8">
                            <label class="switch">
                                <input v-model="item.allow_handles_select" v-bind:true-value="1" v-bind:false-value="0" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="item_id" value="0">

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/kitchen_model.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>