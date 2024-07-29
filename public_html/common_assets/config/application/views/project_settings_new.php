<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['kitchen_project_settings_label']?></h2>
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

<div id="bp_app">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['facades_params']?></h4>
                    </div>
                    <div class="ibox-content">

                        <div class="form-group">
                            <label for="selected_facade_model"><?php echo $lang_arr['default_facade_model']?>*</label>

                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_facade_model"
                                    v-model="project_settings.selected_facade_model"
                                    :options="$options.facades_items"
                                    @input="select_facade($event)"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <img style="max-width: 50px; height: auto" :src="correct_url(option.icon)">&nbsp;
                                    <span v-if="$options.facades_categories[option.category]">
                                        <span v-if="$options.facades_categories[option.category].parent && $options.facades_categories[$options.facades_categories[option.category].parent]">
                                            {{$options.facades_categories[$options.facades_categories[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.facades_categories[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <img style="max-width: 50px; height: auto" :src="correct_url(option.icon)">&nbsp;
                                    <span v-if="$options.facades_categories[option.category]">
                                        <span v-if="$options.facades_categories[option.category].parent && $options.facades_categories[$options.facades_categories[option.category].parent]">
                                            {{$options.facades_categories[$options.facades_categories[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.facades_categories[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_facade"><?php echo $lang_arr['default_facade_material']?>*</label>
                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_material_facade"
                                    v-model="project_settings.selected_material_facade"
                                    :options="computed_facade_materials"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                            </v-select>
                        </div>
                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['corpus_params']?></h4>
                    </div>
                    <div class="ibox-content">
                       
                        <div class="form-group">
                            <label for="available_materials_corpus"><?php echo $lang_arr['available_corpus_materials']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :reduce="option => option.id"
                                    :value="project_settings.available_materials_corpus"
                                    v-model="project_settings.available_materials_corpus"
                                    :options="$options.materials_categories_top"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_corpus"><?php echo $lang_arr['default_corpus_material']?>*</label>

                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_material_corpus"
                                    v-model="project_settings.selected_material_corpus"
                                    :options="get_materials_corpus"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                            </v-select>

                        </div>

                        <div class="form-group">
                            <label for="available_corpus_thickness"><?php echo $lang_arr['available_corpus_thickness']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :value="project_settings.available_corpus_thickness"
                                    v-model="project_settings.available_corpus_thickness"
                                    :options="$options.available_dsp_thickness"
                            >

                                <template v-slot:selected-option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                                <template v-slot:option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                            </v-select>

                        </div>

                        <div class="form-group">
                            <label for="default_corpus_thickness"><?php echo $lang_arr['default_corpus_thickness']?>*</label>
                            <v-select
                                    :clearable="false"
                                    :close-on-select="true"
                                    :value="project_settings.default_corpus_thickness"
                                    v-model="project_settings.default_corpus_thickness"
                                    :options="project_settings.available_corpus_thickness"
                            >

                                <template v-slot:selected-option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                                <template v-slot:option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="available_corpus_thickness"><?php echo $lang_arr['available_shelves_thickness']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :value="project_settings.available_shelves_thickness"
                                    v-model="project_settings.available_shelves_thickness"
                                    :options="$options.available_dsp_thickness"
                            >

                                <template v-slot:selected-option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                                <template v-slot:option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                            </v-select>

                        </div>

                        <div class="form-group">
                            <label for="default_corpus_thickness"><?php echo $lang_arr['default_shelves_thickness']?>*</label>
                            <v-select
                                    :clearable="false"
                                    :close-on-select="true"
                                    :value="project_settings.default_shelves_thickness"
                                    v-model="project_settings.default_shelves_thickness"
                                    :options="project_settings.available_shelves_thickness"
                            >

                                <template v-slot:selected-option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                                <template v-slot:option="option">
                                    {{ option.label }} <?php echo $lang_arr['units']?>
                                </template>

                            </v-select>
                        </div>


                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['glass_shelves']?></h4>
                    </div>
                    <div class="ibox-content">

                        <div v-for="(type, key) in project_settings.available_materials_glass_shelves" class="ibox-content" style="position: relative">

                            <button @click="remove_glass_shelve_type(key)" type="button" class="del_btn btn btn-w btn-danger btn-outline" style="z-index: 10; position: absolute; right: -10px; top: 15px; padding: 5px 5px 3px 4px;">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                <div class="col-sm-8">
                                    <input v-model="type.name" type="text" class="form-control">
                                </div>
                            </div>

                            <div  class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['kitchen_project_settings_label'] ?>*</label>
                                <div class="col-sm-8">
                                    <v-select
                                            multiple
                                            :close-on-select="false"
                                            :reduce="option => option.id"
                                            :value="type.materials"
                                            v-model="type.materials"
                                            :options="$options.materials_categories_top"
                                            label="name">
                                        <template v-slot:option="option">
                                            {{ option.name }}
                                        </template>
                                    </v-select>
                                </div>
                            </div>

                            <div v-if="type.mode == 'custom'" class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_material'] ?>*</label>
                                <div class="col-sm-8">
                                    <v-select
                                            :close-on-select="true"
                                            :reduce="option => option.id"
                                            :clearable="false"
                                            :value="type.default"
                                            v-model="type.default"
                                            :options="get_materials_from_cats(type.materials)"
                                            label="name">

                                        <template v-slot:selected-option="option">
                                            <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                            {{ option.name }}
                                        </template>

                                        <template v-slot:option="option">
                                            <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                            {{ option.name }}
                                        </template>

                                    </v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['thickness'] ?>, <?php echo $lang_arr['units'] ?></label>
                                <div class="col-sm-8">
                                    <input v-model="type.thickness" step="0.1" type="number" class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <button @click="add_glass_shelve_type()" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add']?></button>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['cokol_params']?></h4>
                    </div>
                    <div class="ibox-content">

                        <div class="form-group">
                            <label for="cokol_height"><?php echo $lang_arr['cokol_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                            <input v-model="project_settings.cokol_height" type="number" step="1" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="available_materials_cokol"><?php echo $lang_arr['available_cokol_materials']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :reduce="option => option.id"
                                    :value="project_settings.available_materials_cokol"
                                    v-model="project_settings.available_materials_cokol"
                                    :options="$options.materials_categories_top"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cokol_as_corpus']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="project_settings.cokol_as_corpus" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>



                        <div v-if="project_settings.cokol_as_corpus == 0" class="form-group">
                            <label for="selected_material_cokol"><?php echo $lang_arr['cokol_default_material']?>*</label>
                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_material_cokol"
                                    v-model="project_settings.selected_material_cokol"
                                    :options="get_materials_cokol"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                            </v-select>
                        </div>

                    </div>
                </div>



                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['back_wall']?></h4>
                    </div>
                    <div class="ibox-content">

                        <div v-for="(type, key) in project_settings.available_materials_back_wall" class="ibox-content" style="position: relative">

                            <button @click="remove_back_wall_type(key)" type="button" class="del_btn btn btn-w btn-danger btn-outline" style="z-index: 10; position: absolute; right: -10px; top: 15px; padding: 5px 5px 3px 4px;">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                <div class="col-sm-8">
                                    <input v-model="type.name" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['material_type'] ?></label>
                                <div class="col-sm-8">
                                    <select class="form-control" v-model="type.mode">
                                        <option :value="'custom'"><?php echo $lang_arr['select_materials'] ?></option>
                                        <option :value="'custom_as_corpus'"><?php echo $lang_arr['as_corpus_materials'] ?></option>
                                        <option :value="'corpus'"><?php echo $lang_arr['corpus_materials'] ?></option>
                                        <option :value="'facade'"><?php echo $lang_arr['facades_materials'] ?></option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="type.mode == 'custom'" class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['kitchen_project_settings_label'] ?>*</label>
                                <div class="col-sm-8">
                                    <v-select
                                            multiple
                                            :close-on-select="false"
                                            :reduce="option => option.id"
                                            :value="type.materials"
                                            v-model="type.materials"
                                            :options="$options.materials_categories_top"
                                            label="name">
                                        <template v-slot:option="option">
                                            {{ option.name }}
                                        </template>
                                    </v-select>
                                </div>
                            </div>

                            <div v-if="type.mode == 'custom'" class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_material'] ?>*</label>
                                <div class="col-sm-8">
                                    <v-select
                                            :close-on-select="true"
                                            :reduce="option => option.id"
                                            :clearable="false"
                                            :value="type.default"
                                            v-model="type.default"
                                            :options="get_materials_from_cats(type.materials)"
                                            label="name">

                                        <template v-slot:selected-option="option">
                                            <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                            {{ option.name }}
                                        </template>

                                        <template v-slot:option="option">
                                            <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                            <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                            {{ option.name }}
                                        </template>

                                    </v-select>
                                </div>
                            </div>


                            <div v-if="type.mode == 'custom' || type.mode == 'custom_as_corpus'" class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['thickness'] ?>, <?php echo $lang_arr['units'] ?></label>
                                <div class="col-sm-8">
                                    <input v-model="type.thickness" step="0.1" type="number" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['offset_width'] ?>, <?php echo $lang_arr['units'] ?></label>

                                <div class="col-sm-4">
                                    <select class="form-control" v-model="type.offset.width_type">
                                        <option :value="'custom'"><?php echo $lang_arr['offset_type_custom'] ?></option>
                                        <option :value="'wall'"><?php echo $lang_arr['offset_type_wall'] ?></option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <input :disabled="type.offset.width_type != 'custom'" v-model="type.offset.width" step="0.1" type="number" class="form-control">
                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['offset_height'] ?>, <?php echo $lang_arr['units'] ?></label>

                                <div class="col-sm-4">
                                    <select class="form-control" v-model="type.offset.height_type">
                                        <option :value="'custom'"><?php echo $lang_arr['offset_type_custom'] ?></option>
                                        <option :value="'wall'"><?php echo $lang_arr['offset_type_wall'] ?></option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <input :disabled="type.offset.height_type != 'custom'" v-model="type.offset.height" step="0.1" type="number" class="form-control">
                                </div>
                            </div>


                        </div>

                        <div class="form-group">
                            <button @click="add_back_wall_type()" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add']?></button>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['tabletop_params']?></h4>
                    </div>
                    <div class="ibox-content">
                       
                        <div class="form-group">
                            <label for="tabletop_thickness"><?php echo $lang_arr['default_tabletop_thickness']?> (<?php echo $lang_arr['units']?>)</label>
                            <input v-model="project_settings.tabletop_thickness" type="number" step="1" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="available_materials_tabletop"><?php echo $lang_arr['available_tabletop_materials']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :reduce="option => option.id"
                                    :value="project_settings.available_materials_tabletop"
                                    v-model="project_settings.available_materials_tabletop"
                                    :options="$options.materials_categories_top"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_tabletop"><?php echo $lang_arr['tabletop_default_material']?>*</label>
                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_material_tabletop"
                                    v-model="project_settings.selected_material_tabletop"
                                    :options="get_materials_tabletop"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                            </v-select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['wallpanel_params']?></h4>
                    </div>
                    <div class="ibox-content">

                        <div class="form-group">
                            <label for="wallpanel_height"><?php echo $lang_arr['wallpanel_default_height']?> (<?php echo $lang_arr['units']?>)</label>
                            <input v-model="project_settings.wallpanel_height"  type="number" step="1" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="available_materials_wallpanel"><?php echo $lang_arr['wallpanel_available_materials']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :reduce="option => option.id"
                                    :value="project_settings.available_materials_wallpanel"
                                    v-model="project_settings.available_materials_wallpanel"
                                    :options="$options.materials_categories_top"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>



                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['cornice']?></h4>
                    </div>
                    <div class="ibox-content">

                        <div class="form-group">
                            <label><?php echo $lang_arr['cornice']?></label>
                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :value="project_settings.selected_cornice_model"
                                    v-model="project_settings.selected_cornice_model"
                                    :options="$options.cornice_items"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['glass_params']?></h4>
                    </div>
                    <div class="ibox-content">
                       
                        <div class="form-group">
                            <label for="available_materials_glass"><?php echo $lang_arr['available_glass_materials']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :reduce="option => option.id"
                                    :value="project_settings.available_materials_glass"
                                    v-model="project_settings.available_materials_glass"
                                    :options="$options.glass_categories_top"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_glass"><?php echo $lang_arr['default_glass_material']?>*</label>
                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_material_glass"
                                    v-model="project_settings.selected_material_glass"
                                    :options="get_materials_glass"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <span v-if="option.params.params.map" :style="{'background-image': 'url(' + correct_url(option.params.params.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else-if="option.params.params.icon" :style="{'background-image': 'url(' + correct_url(option.params.params.icon) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.params.params.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.glass_tree[option.category]">
                                        <span v-if="$options.glass_tree[option.category].parent && $options.glass_tree[$options.glass_tree[option.category].parent]">
                                            {{$options.glass_tree[$options.glass_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.glass_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <span v-if="option.params.params.map" :style="{'background-image': 'url(' + correct_url(option.params.params.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else-if="option.params.params.icon" :style="{'background-image': 'url(' + correct_url(option.params.params.icon) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.params.params.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.glass_tree[option.category]">
                                        <span v-if="$options.glass_tree[option.category].parent && $options.glass_tree[$options.glass_tree[option.category].parent]">
                                            {{$options.glass_tree[$options.glass_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.glass_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                            </v-select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['handles_params']?></h4>
                    </div>
                    <div class="ibox-content">
                       
                        <div class="form-group">
                            <label for="handle_orientation"><?php echo $lang_arr['handles_orientation']?></label>
                            <select class="form-control" v-model="project_settings.handle_orientation" >
                                <option :value="'vertical'"><?php echo $lang_arr['vertical']?></option>
                                <option :value="'horizontal'"><?php echo $lang_arr['horizontal']?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="handle_lockers_position"><?php echo $lang_arr['locker_handle_position']?></label>
                            <select class="form-control" v-model="project_settings.handle_lockers_position" >
                                <option :value="'top'"><?php echo $lang_arr['lockers_hande_top']?></option>
                                <option :value="'center'"><?php echo $lang_arr['lockers_handle_center']?></option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="handle_selected_model"><?php echo $lang_arr['handle_default_model']?>*</label>

                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.handle_selected_model"
                                    v-model="project_settings.handle_selected_model"
                                    :options="$options.handles"
                                    @input="select_handle"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <img style="max-width: 50px; height: auto" :src="correct_url(option.icon)">&nbsp;
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <img style="max-width: 50px; height: auto" :src="correct_url(option.icon)">&nbsp;
                                    {{ option.name }}
                                </template>
                            </v-select>


                        </div>

                        <div class="form-group" id="handle_preferable_size_wrapper">
                            <label for="handle_preferable_size"><?php echo $lang_arr['handle_default_size']?>*</label>
                            <select class="form-control" v-model="project_settings.handle_preferable_size" >
                                <option v-for="(variant, index) in current_handle_variants" :value="index">
                                    <?php echo $lang_arr['width']?> {{variant.width}}<template v-if="variant.axis_size">, <?php echo $lang_arr['axis_size']?> {{variant.axis_size}}</template>
                                </option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['walls_params']?></h4>
                    </div>
                    <div class="ibox-content">
                       
                        <div class="form-group">
                            <label for="available_materials_walls"><?php echo $lang_arr['available_walls_materials']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :reduce="option => option.id"
                                    :value="project_settings.available_materials_walls"
                                    v-model="project_settings.available_materials_walls"
                                    :options="$options.materials_categories_top"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_walls"><?php echo $lang_arr['default_walls_material']?>*</label>
                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_material_walls"
                                    v-model="project_settings.selected_material_walls"
                                    :options="get_materials_walls"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                            </v-select>
                        </div>

                    </div>
                </div>

                <div class="ibox">
                    <div class="ibox-title">
                        <h4 class="panel-title"><?php echo $lang_arr['floor_params']?></h4>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group">
                            <label for="available_materials_floor"><?php echo $lang_arr['available_floor_materials']?>*</label>
                            <v-select
                                    multiple
                                    :close-on-select="false"
                                    :reduce="option => option.id"
                                    :value="project_settings.available_materials_floor"
                                    v-model="project_settings.available_materials_floor"
                                    :options="$options.materials_categories_top"
                                    label="name">
                                <template v-slot:option="option">
                                    {{ option.name }}
                                </template>
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="selected_material_floor"><?php echo $lang_arr['default_floor_material']?>*</label>
                            <v-select
                                    :close-on-select="true"
                                    :reduce="option => option.id"
                                    :clearable="false"
                                    :value="project_settings.selected_material_floor"
                                    v-model="project_settings.selected_material_floor"
                                    :options="get_materials_floor"
                                    label="name">

                                <template v-slot:selected-option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                                <template v-slot:option="option">
                                    <span v-if="option.map" :style="{'background-image': 'url(' + correct_url(option.map) + ')'}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-else :style="{'background-color':option.color}" style="display:inline-block; vertical-align: middle; width: 50px; height: 50px;" class="mr-1"></span>
                                    <span v-if="$options.materials_tree[option.category]">
                                        <span v-if="$options.materials_tree[option.category].parent && $options.materials_tree[$options.materials_tree[option.category].parent]">
                                            {{$options.materials_tree[$options.materials_tree[option.category].parent].name}}&nbsp;
                                        </span>
                                        {{$options.materials_tree[option.category].name}}&nbsp;
                                    </span>
                                    {{ option.name }}
                                </template>

                            </v-select>
                        </div>

                    </div>
                </div>

                <div class="ibox ">
                    <div class="ibox-content">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button @click="send_data()" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select_3.18.3.css">
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select_3.18.3.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/project_settings.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>