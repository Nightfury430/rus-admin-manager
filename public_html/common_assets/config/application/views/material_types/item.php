<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Тип материалов</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<?php
if (isset($common) && $common == 1) {
    $submit_url = site_url( $controller_name . '/items_add_ajax_common/' );
    if(isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items_common/'.$controller_name);
} else {
    $submit_url = site_url( $controller_name . '/item_add/' );
    if(isset($id)) $submit_url = $submit_url . $id;
    $return_url = site_url('/catalog/items/'.$controller_name);
}
?>


<div class="wrapper wrapper-content  animated fadeInRight">
    <form ref="sub_form" id="sub_form"  enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url( 'material_types/item_add/' ) ?><?php if (isset($id)) echo $id ?>">
        <input id="form_success_url" value="<?php echo $return_url ?>" type="hidden">
        <?php if (isset($id)): ?>
            <input id="item_id" value="<?php echo $id ?>" type="hidden">
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#basic_params_tab"><?php echo $lang_arr['basic_params'] ?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#prices_tab"><?php echo $lang_arr['prices'] ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="basic_params_tab" class="tab-pane active">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                    <div class="col-sm-10">
                                        <input @input="change_name" v-model="item.name" type="text" class="form-control" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['key'] ?></label>
                                    <div class="col-sm-10">
                                        <input :disabled="item.id != 0" v-model="item.key" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['type'] ?></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" v-model="item.type">
                                            <option value="m2">Площадь</option>
                                            <option value="m">Погонный метр</option>
                                            <option value="m3">Объем</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-12">Варианты</div>
                                    <div class="col-12">
                                        <div v-for="(variant, index) in item.variants" class="p-2 pr-5 draggable_panel">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name'] ?></label>
                                                <div class="col-sm-10">
                                                    <input @input="set_variant_key(variant)" v-model="variant.name" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['key'] ?></label>
                                                <div class="col-sm-10">
                                                    <input v-model="variant.key" type="text" class="form-control">
                                                </div>
                                            </div>


                                            <div v-if="item.type == 'm' && item.type != 'm3'" class="form-group row">
                                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['height'] ?>, <?php echo $lang_arr['units'] ?></label>
                                                <div class="col-sm-10">
                                                    <input @input="set_variant_key(variant)" v-model="variant.size.y" type="number" class="form-control">
                                                </div>
                                            </div>
                                            <div v-if="item.type != 'm3'" class="form-group row">
                                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['thickness'] ?>, <?php echo $lang_arr['units'] ?></label>
                                                <div class="col-sm-10">
                                                    <input @input="set_variant_key(variant)" v-model="variant.size.z" type="number" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"><?php echo $lang_arr['available_materials']?></label>
                                                <div class="col-sm-10">
                                                    <v-select
                                                            @input="change_variant_categories(index, variant.categories)"
                                                            multiple
                                                            :close-on-select="false"
                                                            :reduce="option => option.id"
                                                            :value="variant.categories"
                                                            v-model="variant.categories"
                                                            :options="$options.materials_categories_top"
                                                            label="name">
                                                        <template v-slot:option="option">
                                                            {{ option.name }}
                                                        </template>
                                                    </v-select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Декор по умолчанию</label>
                                                <div class="col-sm-10">
                                                <v-select
                                                        :close-on-select="true"
                                                        :reduce="option => option.id"
                                                        :clearable="false"
                                                        :value="variant.id"
                                                        v-model="variant.id"
                                                        :options="get_materials_from_cats(variant.categories)"
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

                                            <button v-if="index > 0" @click="remove_variant(index)" style="position: absolute; right: 10px; top:10px; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>

                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button @click="add_variant()" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add'] ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="prices_tab" class="tab-pane">
                            <div class="panel-body models_list">
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li v-for="(variant, index) in item.variants"><a :class="{active: index == 0}" class="nav-link" data-toggle="tab" :href="'#id_' + variant.key">{{variant.name}}</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div :id="'id_' + variant.key" v-for="(variant, index) in item.variants" :class="{active: index == 0}" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="row mb-2">
                                                    <div class="col-4">
                                                    </div>
                                                    <div class="col-4">
                                                        <b>Цена (<?php echo $lang_arr['currency'] ?>. за</b>  <b v-if="item.type == 'm2'">м2</b><b v-if="item.type == 'm'">м</b><b v-if="item.type == 'm3'">м3</b><b>)</b>
                                                    </div>
                                                    <div class="col-4">
                                                        <b>Артикул</b>
                                                    </div>
                                                </div>
                                                <template v-for="cat in format_variant_prices(variant)">
                                                    <div class="form-group">
                                                        <div class="row mb-1">
                                                            <div class="col-4">
                                                                <b>{{cat.name}}</b>
                                                            </div>
                                                            <div class="col-4 d-flex">
                                                                <input v-model="variant.prices[cat.id].price" type="number" class="form-control input-sm">
                                                                <button @click="copy_price(variant, cat.id)" title="Скопировать цену на подкатегории" type="button" class="btn btn-outline-info btn-sm" @click="">
                                                                    <i class="fa fa-copy"></i>
                                                                </button>
                                                            </div>
                                                            <div class="col-4">
                                                                <input v-model="variant.prices[cat.id].code" type="text" class="form-control input-sm">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-1" v-for="subcat in cat.categories">
                                                            <div class="col-4">
                                                                <div class="pl-2">
                                                                    {{subcat.name}}
                                                                </div>
                                                            </div>
                                                            <div class="col-4">
                                                                <input v-model="variant.prices[subcat.id].price" type="number" class="form-control input-sm">
                                                            </div>
                                                            <div class="col-4">
                                                                <input v-model="variant.prices[subcat.id].code" type="text" class="form-control input-sm">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </template>
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
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('/catalog/items/material_types') ?>"><?php echo $lang_arr['cancel'] ?></a>
                                <?php if (isset($id)): ?>
                                    <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['save'] ?></button>
                                <?php else: ?>
                                    <button @click="submit" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add'] ?></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>


<script src="/common_assets/libs/vue.min.js"></script>
<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>

<script src="/common_assets/admin_js/vue/kitchen/material_types.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>
