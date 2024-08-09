
<?php if (!isset($set_id)) $set_id = '';?>
<?php if (!isset($common)) $common = 0;?>
<?php if (!isset($catalog_add)) $catalog_add = 0;?>

<div v-cloak id="sub_form">
    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card ">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-sm-6 d-flex align-items-center">
                                <!-- <a v-show="controller_name == 'modules_sets_modules'" class="btn btn-sm btn-default btn-outline mr-1" v-bind:href="base_url + '/catalog/items_common/modules_sets'" role="button">Вернуться к наборам модулей</a> -->
                                <div v-if="is_catalog == 0" class="btn btn-sm btn-primary m-2 " @click="add_item()" role="button"><?php echo $lang_arr['add']?></div>
                                <!-- <div v-show="catalog_button" class="btn btn-sm btn-primary ml-1" @click="add_from_catalog()" role="button"><?php echo $lang_arr['add_from_catalog']?></div> -->
                                <!-- <div v-show="controller_name == 'materials'" class="btn btn-sm btn-primary ml-1" @click="add_multiple()" role="button"><?php echo $lang_arr['add_multiple_materials']?></div> -->
                                <div v-show="is_modules" class="btn btn-sm btn-primary m-2" data-bs-toggle="modal" data-bs-target="#mass_change_modal" role="button"><?php echo $lang_arr['mass_params_change']?> <?php echo $lang_arr['kitchen_bottom_modules']?></div>
                                <div v-show="is_modules" class="btn btn-sm btn-primary m-2" data-bs-toggle="modal" data-bs-target="#mass_change_modal_top" role="button"><?php echo $lang_arr['mass_params_change']?> <?php echo $lang_arr['kitchen_top_modules']?></div>
                                <!-- <div v-show="controller_name == 'facades_systems'" class="btn btn-sm btn-primary ml-1" @click="save_fs_data()" role="button">Выгрузить фасадные системы</div> -->
                                <a  class="ml-2" v-show="controller_name == 'facades_systems'" target="_blank" href="/clients/dev/?testfs" >Перейти в тест фасадных систем</a>
                            </div>
                            <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                <pagination_component v-on:change-page="change_page($event)" :pages="pages_count"></pagination_component>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-sm-4">
                                <form v-show="!is_modules" v-on:submit.prevent="do_search()">
                                <div class="form-group">
                                    <label ><?php echo $lang_arr['search']?></label>
                                    <div class="input-group">
                                        <input v-model="filter.search" type="text" class="form-control" style="height: 30px">
                                        <span class="input-group-append" style="font-size: 11px">
                                            <button type="submit" class="btn btn-primary" >
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group" >
                                    <label for="category"><?php echo $lang_arr['category']?></label>
                                    <v-select
                                        :clearable="false"
                                        :value="filter.category"
                                        label="name"
                                        :options="categories_ordered"
                                        :reduce="category => category.id"
                                        v-model="filter.category"
                                        :key="filter.category"
                                        @input="filter_category($event)"
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
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="per_page"><?php echo $lang_arr['page_items_count']?></label>
                                    <v-select
                                        :clearable="false"
                                        :value="filter.per_page"
                                        :options="per_page"
                                        v-model="filter.per_page"
                                        @input="filter_per_page($event)"
                                    >
                                    </v-select>
                                </div>
                            </div>
                        </div>
                        <div v-show="controller_name == 'materials'" class="row mb-3">
                            <div class="col-sm-4">
                                <?php echo $lang_arr['mass_action_with']?>
                            </div>
                            <div class="col-sm-5">
                                <select :disabled="selected_items.length < 1" ref="mass_action_select" v-model="mass_action" class="form-control">
                                    <option value="edit_category"><?php echo $lang_arr['mass_edit_category']?></option>
                                    <option value="copy_to_cat"><?php echo $lang_arr['mass_copy_to_cat']?></option>
                                    <option value="edit"><?php echo $lang_arr['mass_edit']?></option>
                                    <option value="delete"><?php echo $lang_arr['delete']?></option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button :disabled="selected_items.length < 1" type="button" class="btn btn-sm btn-primary ml-1" @click="do_mass_action()" role="button"><?php echo $lang_arr['next']?></button>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th v-show="controller_name == 'materials'"><input ref="select_all_checkbox" @change="toggle_all($event)" type="checkbox"></th>
                                    <th><?php echo $lang_arr['id_short']?></th>
                                    <th v-if="has_icons"><?php echo $lang_arr['icon']?></th>
                                    <th v-if="has_color"><?php echo $lang_arr['material']?></th>
                                    <th><?php echo $lang_arr['name']?></th>
                                    <th v-if="items[0] && items[0].code !== undefined"><?php echo $lang_arr['code']?></th>
                                    <th v-if="is_modules">{{lang('sizes')}}</th>
                                    <th v-if="is_modules">{{lang('code')}}</th>
                                    <th v-if="controller_name == 'module_sets'">{{lang('price')}}</th>
                                    <th v-if="items[0] && items[0].category !== undefined"><?php echo $lang_arr['category']?></th>
                                    <th v-if="items[0] && items[0].order !== undefined"><?php echo $lang_arr['order']?></th>
                                    <th v-if="is_catalog == 0 && items[0] && items[0].active !== undefined"><?php echo $lang_arr['is_visible']?></th>
                                    <th v-if="controller_name == 'modules_sets'"></th>
                                    <th v-if="controller_name == 'modules_sets'"></th>
                                    <th v-if="controller_name == 'modules_sets'"></th>
                                    <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="items[0]" v-for="(item,ind) in items">
                                    <td v-show="controller_name == 'materials'" style="width: 10px"><input @change="select_item($event, ind, item.id)" :value="item.id" v-model="selected_items" type="checkbox"></td>
                                    <td style="width: 50px">{{item.id}}</td>
                                    <td style="width: 100px" v-if="has_icons"><img v-if="item.icon" style="max-width: 100px" class="img-fluid " :src="correct_url(item.icon)"></td>
                                    <td style="width: 100px" v-if="has_color"><div class="item_image" :style="{ 'background':  get_map(item)  }"></div></td>
                                    <td v-if="items[0] && items[0].name !== undefined">{{item.name}}</td>
                                    <td v-if="is_modules">
                                        <p v-for="variant in item.params.variants">{{variant.name}}</p>
                                    </td>
                                    <td style="width: 100px" v-if="items[0] && items[0].code !== undefined">{{item.code}}</td>
                                    <td v-if="is_modules">
                                        <p v-for="variant in item.params.variants">{{variant.width}}x{{variant.height}}x{{variant.depth}}</p>
                                    </td>
                                    <td v-if="is_modules">
                                        <p v-for="variant in item.params.variants">{{variant.code}} </p>
                                    </td>
                                    <td v-if="controller_name == 'module_sets'">
                                        <p v-for="variant in item.params.variants">{{variant.price}} {{lang('currency')}}</p>
                                    </td>
                                    <td v-if="items[0] && items[0].category !== undefined" style="width: 250px">
                                        <div v-if="is_catalogue">
                                            <div v-if="!item.category.length">
                                                <span>{{lang('no')}}</span>
                                            </div>
                                            <div v-for="cat in item.category">
                                                <span v-if="cat == 0">{{lang('no')}}</span>
                                                <span v-else>
                                            <span v-if="categories_hash[cat] && categories_hash[cat].parent && categories_hash[cat].parent != 0">
                                            {{categories_hash[categories_hash[cat].parent].name}} /
                                            </span>
                                            <span v-if="categories_hash[cat]">{{categories_hash[cat].name}}</span><span v-else>{{lang['no']}}</span>

                                            <span v-if="!categories_hash[cat]">{{lang('no')}}</span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <span v-if="item.category == 0">{{lang('no')}}</span>
                                            <span v-else>
                                            <span v-if="categories_hash[item.category] && categories_hash[item.category].parent && categories_hash[item.category].parent != 0">
                                            {{categories_hash[categories_hash[item.category].parent].name}} /
                                            </span>
                                            <span v-if="categories_hash[item.category]">{{categories_hash[item.category].name}}</span><span v-else>{{lang['no']}}</span>

                                            <span v-if="!categories_hash[item.category]">{{lang('no')}}</span>
                                        </span>
                                        </div>
                                    </td>
                                    <td v-if="items[0] && items[0].order !== undefined" style="width: 100px">{{item.order}}</td>
                                    <td v-if="is_catalog == 0 && items[0] && items[0].active !== undefined" style="width: 100px">
                                        <button class="btn btn-icon btn-label-linkedin waves-effect">
                                            <i @click="change_active(item)"  class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td v-else style="width: 100px">
                                        <button class="btn btn-icon btn-label-linkedin waves-effect">
                                            <i @click="change_active(item)" class="fa fa-solid fa-eye-low-vision "></i>
                                        </button>
                                    </td>
                                    <td v-if="controller_name == 'modules_sets'">
                                        <a class="btn btn-icon btn-label-linkedin waves-effect" v-bind:href="base_url + '/catalog/categories_common/modules_sets_modules/' + item.id"> Редактировать категории </a>
                                    </td>
                                    <td v-if="controller_name == 'modules_sets'">
                                        <a class="btn btn-icon btn-label-linkedin waves-effect" v-bind:href="base_url + '/catalog/items_common/modules_sets_modules/' + item.id"> Редактировать модули </a>
                                    </td>
                                    <td v-if="controller_name == 'modules_sets'">
                                        <a class="btn btn-icon btn-label-linkedin waves-effect" v-bind:href="base_url + '/modules_sets_modules/preview_set/' + item.id"> Превью и иконки </a>
                                    </td>
                                    <td style="width: 150px">
                                        <template v-if="is_catalog == 0">
                                            <a class="btn btn-icon btn-label-linkedin waves-effect" :href="edit_item(item)"><i :title="lang('edit')"  class="fa fa-edit"></i></a>
                                            <button class="btn btn-icon btn-label-linkedin waves-effect">
                                                <i style="margin: 0 6px" title="Копировать" @click="copy_item(item)" class="fa fa-copy"></i>
                                            </button>
                                            <button class="btn btn-icon btn-label-linkedin waves-effect">
                                                <i :title="lang('delete')" @click="show_swal(item)" class="fa fa-trash"></i>
                                            </button>
                                            <template v-if="controller_name == 'modules_sets'">
                                                <button class="btn btn-icon btn-label-linkedin waves-effect">
                                                    <i @click="$emit('json_input',item)" class="fa fa-file-text btn btn-outline btn-success"></i>
                                                </button>
                                                <!--                                        <i @click="$emit('json_input',item)" class="fa fa-copy btn btn-outline btn-success"></i>-->
                                            </template>
                                        </template>
                                        <template v-else>
                                            <div @click="show_catalog_modal(item)" class="btn btn-xs btn-outline btn-w-m btn-primary"  role="button"><?php echo $lang_arr['add']?></div>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-show="controller_name == 'materials'" class="row mb-3">
                            <div class="col-sm-4">
                                <?php echo $lang_arr['mass_action_with']?>
                            </div>
                            <div class="col-sm-5">
                                <select :disabled="selected_items.length < 1" ref="mass_action_select" v-model="mass_action" class="form-control">
                                    <option value="edit_category"><?php echo $lang_arr['mass_edit_category']?></option>
                                    <option value="copy_to_cat"><?php echo $lang_arr['mass_copy_to_cat']?></option>
                                    <option value="edit"><?php echo $lang_arr['mass_edit']?></option>
                                    <option value="delete"><?php echo $lang_arr['delete']?></option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button :disabled="selected_items.length < 1" type="button" class="btn btn-sm btn-primary ml-1" @click="do_mass_action()" role="button"><?php echo $lang_arr['next']?></button>
                            </div>
                        </div>

                        <div class="row align-items-center mt-3">
                            <div class="col-sm-6 d-flex align-items-center">
                                <div v-if="is_catalog == 0" class="btn btn-w-m btn-primary" @click="add_item()" role="button"><?php echo $lang_arr['add']?></div>
                            </div>
                            <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                <pagination_component v-on:change-page="change_page($event)" :pages="pages_count"></pagination_component>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="cat_set_id" value="<?php echo $set_id?>">
    <input type="hidden" id="is_common" value="<?php echo $common?>">
    <input type="hidden" id="is_catalog" value="<?php echo $catalog_add?>">

    <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/common_assets/admin_js/vue/' . $controller_name . '_comp.php' )) include_once $_SERVER['DOCUMENT_ROOT'] . '/common_assets/admin_js/vue/' . $controller_name . '_comp.php'?>

    <div class="modal fade" id="mass_change_modal" tabindex="-1" aria-modal="true" role="dialog">
        <form id="mass_modal" data-cat="1" data-action="<?php echo site_url($controller_name . '/mass_modules_bottom/' . $set_id) ?>">
            <div style="max-width: 800px" class="modal-dialog modal-lg modal-simple">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn-close"></button>
                        <div class="text-center mb-6">
                            <h4 class="modal-title"><?php echo $lang_arr['mass_params_change']?> <?php echo $lang_arr['kitchen_bottom_modules']?></h4>
                        </div>
                        <div class="form-group row mb-4">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['initial_value']?></p></div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['new_value']?></p></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-3"><div ><?php echo $lang_arr['depth_tabletop']?>, <?php echo $lang_arr['units']?></div></div>
                            <div class="col-sm-3"><input id="bm_depth" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-1"><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div class="col-sm-3"><input id="bm_depth_to" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button id="bm_mass_depth" type="button" class="btn btn-primary" data-action="<?php echo site_url($controller_name . '/mass_modules_bottom/' . $set_id) ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group row mb-4">
                            <div class="col-sm-4"><?php echo $lang_arr['tabletop_offset_front']?>, <?php echo $lang_arr['units']?></div>
                            <div class="col-sm-6"> <input id="bm_fo" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button id="bm_mass_fo" type="button" class="btn btn-primary" data-action="<?php echo site_url($controller_name . '/mass_modules_bottom/' . $set_id) ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group row mb-4">
                            <div class="col-sm-4"><?php echo $lang_arr['tabletop_offset_back']?>, <?php echo $lang_arr['units']?></div>
                            <div class="col-sm-6"> <input id="bm_bo" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button id="bm_mass_bo" type="button" class="btn btn-primary" data-action="<?php echo site_url($controller_name . '/mass_modules_bottom/' . $set_id) ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group row mb-4">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['initial_value']?></p></div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['new_value']?></p></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-3"><div><?php echo $lang_arr['corpus_height']?>, <?php echo $lang_arr['units']?></div></div>
                            <div class="col-sm-3"><input id="bm_height" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-1"><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div class="col-sm-3"><input  id="bm_height_to" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button id="bm_mass_height" type="button" class="btn btn-primary" data-action="<?php echo site_url($controller_name . '/mass_modules_bottom/' . $set_id) ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="reset" class="btn btn-label-secondary" style="margin-right:2rem;" data-bs-dismiss="modal" aria-label="Close" >{{lang('cancel')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="mass_change_modal_top" tabindex="-1" role="dialog" aria-hidden="true">
        <form  data-cat="2" data-action="<?php echo site_url($controller_name . '/mass_modules_top/' . $set_id) ?>">
            <div style="max-width: 800px" class="modal-dialog modal-lg modal-simple">
                <div class="modal-content ">
                    <div class="modal-body">
                        <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <div class="text-center mb-6">
                            <h4 class="modal-title"><?php echo $lang_arr['mass_params_change']?> <?php echo $lang_arr['kitchen_top_modules']?></h4>
                        </div>
                        <div class="form-group d-flex flex-row align-items-center mb-3">
                            <div><div class="p-2"><?php echo $lang_arr['corpus_height']?>, <?php echo $lang_arr['units']?></div></div>
                            <div><input id="tm_height" type="number" value="0" class="form-control"></div>
                            <div><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div><input  id="tm_height_to" type="number" value="0" class="form-control"></div>
                            <div class="p-2"> <button id="tm_mass_height" type="button" class="btn btn-primary" data-action="<?php echo site_url($controller_name . '/mass_modules_top/' . $set_id) ?>"><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group d-flex flex-row align-items-center mb-3">
                            <div><div class="p-2"><?php echo $lang_arr['depth']?>, <?php echo $lang_arr['units']?></div></div>
                            <div><input id="tm_depth" type="number" value="0" class="form-control"></div>
                            <div><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div><input  id="tm_depth_to" type="number" value="0" class="form-control"></div>
                            <div class="p-2"> <button id="tm_mass_depth" type="button" class="btn btn-primary" data-action="<?php echo site_url($controller_name . '/mass_modules_top/' . $set_id) ?>"><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="reset" class="btn btn-label-secondary" style="margin-right:2rem;" data-bs-dismiss="modal" aria-label="Close" >{{lang('cancel')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div v-if="is_catalog" class="modal inmodal" id="catalog_modal" tabindex="-1" role="dialog"  aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">{{lang('add_model_to_your_catalog')}}</h4>
                </div>
                <div class="modal-body">


                    <div>
                        {{lang('choose_category')}}
                    </div>
                    <div>
                        <v-select v-if="catalog_categories.ready == 1"
                                  :clearable="false"
                                  :value="catalog_categories.value"
                                  label="name"
                                  :options="catalog_categories.ordered"
                                  :reduce="category => category.id"
                                  v-model="catalog_categories.value"
                                  :key="catalog_categories.value"

                        >

                            <template #selected-option="option">
                                                    <span style="pointer-events: none" :title="option.name" :class="{'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0" class="font-weight-bold">{{catalog_categories.hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                            </template>

                            <template v-slot:option="option">
                                                        <span :title="option.name" :class="{'pl-3': option.parent != 0, 'font-weight-bold': option.parent == 0}">
                                                            <span v-if="option.parent != 0">{{catalog_categories.hash[option.parent].name}} / </span>{{ option.name }}
                                                        </span>
                            </template>
                        </v-select>
                    </div>


                </div>

                <div class="modal-footer">
                    <div class="btn btn-w-m btn-primary" @click="add_item_from_catalog()" role="button">{{lang('add')}}</div>
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="catalog_modal_materials" tabindex="-1" role="dialog"  aria-modal="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">{{lang('catalog')}}</h4>
                </div>
                <div class="modal-body">

                    <table class="table table-hover  ">
                        <thead>
                        <tr>
                            <th ><?php echo $lang_arr['name']?></th>
                            <th class="text-center" ><?php echo $lang_arr['actions']?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in catalog_items">
                            <td>
                                {{item.name}}
                                <ul style="margin-bottom: 10px; margin-top: 10px; padding-left: 20px" v-if="item.children.length">
                                    <li style="font-size: 11px;" v-for="child in item.children">{{child.name}}</li>
                                </ul>
                            </td>
                            <td style="width: 110px; text-align: center">
                                <a @click="show_swal_catalog(item)" tabindex="100">{{lang('add')}}</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal inmodal" id="modal_mass_category" tabindex="-1" role="dialog"  aria-modal="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Изменить категорию</h4>
                </div>
                <div class="modal-body">
                    <pp_category ref="mass_category" :controller="controller_name"></pp_category>
                </div>

                <div class="modal-footer">
                    <div class="btn btn-w-m btn-primary btn-sm" @click="mass_category_do()" role="button">{{lang('apply')}}</div>
                    <div class="btn btn-white btn-sm" data-dismiss="modal">{{lang('cancel')}}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modal_mass_copy_to_category" tabindex="-1" role="dialog"  aria-modal="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Скопировать в категорию</h4>
                </div>
                <div class="modal-body">
                    <pp_category ref="mass_copy_to_category" :controller="controller_name"></pp_category>
                </div>

                <div class="modal-footer">
                    <div class="btn btn-w-m btn-primary btn-sm" @click="mass_copy_to_category_do()" role="button">{{lang('apply')}}</div>
                    <div class="btn btn-white btn-sm" data-dismiss="modal">{{lang('cancel')}}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="modal_mass_change" tabindex="-1" role="dialog"  aria-modal="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Массовое изменение</h4>
                </div>
                <div class="modal-body">
                    <mass_materials_form ref="mass_materials"></mass_materials_form>
                </div>

                <div class="modal-footer">
                    <div class="btn btn-w-m btn-primary" @click="mass_change_do()" role="button">{{lang('apply')}}</div>
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{lang('cancel')}}</button>
                </div>
            </div>
        </div>
    </div>


</div>




<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>

<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>

<script src="/common_assets/admin_js/vue/catalog/items.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/admin_js/vue/pagination.js"></script>

<style>
    label{
        margin-bottom: 0;
    }

    .table.items_table>tbody>tr>td {
        vertical-align: middle;
    }

    .actions_list a{
        margin-right: 10px;
        margin-top: 12px;
        display: inline-block;
        font-size: 16px;
    }

    .actions_list a.delete_button{
        color: #ff0000;
    }

    .actions_list a.delete_button.disabled{
        color: #9d9d9d;
        pointer-events: none;
    }

    .actions_list a.is_visible{
        color: #0e7b0d;
    }
    .actions_list a.is_visible:hover{
        color: #0e7b0d;
    }

    .actions_list a.is_visible.disabled{
        color: #9d9d9d;
    }

    .item_image{
        width: 50px;
        height: 50px;
        border: 1px solid;
        background-size: cover!important;
    }

    .item_image > div{
        width: 100%;
        height: 100%;
    }

    .option.top_cat{
        font-weight: bold;
    }

    .option.sub_cat{
        padding-left: 40px;
    }

    .sp-input{
        background: #ffffff;
    }

    .map{
        background-size: cover!important;
    }

    ul.pagination{
        margin-bottom: 0;
    }
</style>