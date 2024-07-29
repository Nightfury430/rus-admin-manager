<div>
    <div v-if="is_single == false">
        <table v-show="selected.length" class="table table-hover table-bordered">
            <thead>
            <tr>
                <th>{{lang['id_short']}}</th>
                <th v-if="item_cols && item_cols.icon">{{lang['icon']}}</th>
                <th v-if="item_cols && item_cols.color">{{lang['material']}}</th>
                <th v-if="item_cols && item_cols.name !== undefined">{{lang['name']}}</th>
                <th v-if="item_cols && item_cols.code !== undefined">{{lang['code']}}</th>
                <th v-if="is_modules">{{lang['sizes']}}</th>
                <th v-if="is_modules">{{lang['code']}}</th>
                <th v-if="item_cols && item_cols.category !== undefined">{{lang['category']}}</th>

                <th v-if="controller == 'modules_sets'"></th>
                <th v-if="controller == 'modules_sets'"></th>
                <th v-if="controller == 'modules_sets'"></th>
                <th v-if="count_mode">{{lang['count_short']}}</th>
                <th class="col-xs-2">{{lang['actions']}}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="item_cols" v-for="item in selected_hash">
                <td style="width: 50px">{{item.id}}</td>
                <td v-if="item.icon" style="width: 100px"><img style="max-width: 100px" class="img-fluid " :src="correct_url(item.icon)"></td>
                <td v-if="item.color" style="width: 100px">
                    <div class="ppip_item_image" :style="{ 'background':  get_map(item)  }"></div>
                </td>
                <td v-if="item_cols && item_cols.name !== undefined">{{item.name}}</td>
                <td v-if="item_cols && item_cols.code !== undefined" style="width: 100px">{{item.code}}</td>
                <td v-if="is_modules">
                    <p v-for="variant in item.params.variants">{{variant.width}}x{{variant.height}}x{{variant.depth}}</p>
                </td>
                <td v-if="is_modules">
                    <p v-for="variant in item.params.variants">{{variant.code}}</p>
                </td>
                <td v-if="item_cols && item_cols.category !== undefined" style="width: 250px">

                    <div v-if="is_catalogue ">
                        <div v-if="!item.category.length">
                            <span>{{lang['no']}}</span>
                        </div>
                        <div v-for="cat in item.category">
                            <span v-if="cat == 0">{{lang['no']}}</span>
                            <span v-else>
                                                <span v-if="$options.categories_hash[cat] && $options.categories_hash[cat].parent && $options.categories_hash[cat].parent != 0">
                                                    {{$options.categories_hash[$options.categories_hash[cat].parent].name}} /
                                                </span>
                                                <span v-if="$options.categories_hash[cat]">{{$options.categories_hash[cat].name}}</span><span v-else>{{lang['no']}}</span>

                                                <span v-if="!$options.categories_hash[cat]">{{lang['no']}}</span>
                            </span>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="controller == 'accessories'">
                            {{item.category}}
                        </div>
                        <div v-else>
                            <span v-if="item.category == 0">{{lang['no']}}</span>
                            <span v-else>
                                                <span v-if="$options.categories_hash[item.category] && $options.categories_hash[item.category].parent && $options.categories_hash[item.category].parent != 0">
                                                {{$options.categories_hash[$options.categories_hash[item.category].parent].name}} /
                                                </span>
                                                <span v-if="$options.categories_hash[item.category]">{{$options.categories_hash[item.category].name}}</span><span v-else>{{lang['no']}}</span>

                                                <span v-if="!$options.categories_hash[item.category]">{{lang['no']}}</span>
                                            </span>
                        </div>


                    </div>

                </td>
                <td v-if="controller == 'modules_sets'">
                    <a :href="base_url + '/catalog/categories_common/modules_sets_modules/' + item.id"> Редактировать категории </a>
                </td>
                <td v-if="controller == 'modules_sets'">
                    <a :href="base_url + '/catalog/items_common/modules_sets_modules/' + item.id"> Редактировать модули </a>
                </td>
                <td v-if="controller == 'modules_sets'">
                    <a :href="base_url + '/modules_sets_modules/preview_set/' + item.id"> Превью и иконки </a>
                </td>
                <td style="width: 100px" v-if="count_mode"><input @input="change_count(item.id, $event)" v-model="item.count" type="number"></td>
                <td style="width: 150px">
                    <div v-show="!selected_hash[item.id]" @click="add_selected(item)" class="btn btn-sm btn-primary btn-outline">
                        {{lang['add']}}
                    </div>
                    <div v-show="selected_hash[item.id]" @click="remove_selected(item.id)" class="btn btn-sm btn-danger btn-outline">
                        {{lang['remove']}}
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-primary btn-outline" @click="show_fm()">{{lang['add']}}</button>
    </div>
    <div  v-if="is_single">
        <div class="d-flex w-100 align-items-center" v-if="options_ready && view == 'materials'">
            <div v-show="get_icon()" class="mr-1 ppip_item_icon" :class="{ppip_item_icon_small: small == 1}" :style="{ 'background':  get_icon()  }"></div>
            <div style="flex-grow: 1" v-if="options_ready"><a @click.stop.prevent="show_fm()" href="#"><span v-show="item_data.id != 0">{{item_data.id}}:</span> {{item_data.name}} </a></div>
            <div class="ml-1">
                <a type="button" class="btn btn-xs btn-success btn-outline mr-1" :href="get_link()" target="_blank"><i class="fa fa-link"></i></a>
                <button type="button" class="btn btn-xs btn-success btn-outline" @click="show_fm()"><i class="fa fa-edit"></i></button>
                <button type="button" v-if="unselect  && item_data.id != 0" class="btn btn-xs btn-danger btn-outline" @click="unselect_item()"><i class="fa fa-trash"></i></button>
            </div>
        </div>
        <div class="w-100" v-if="options_ready && view == 'table'">
            <table v-if="item_data.id != 0" class="table table-bordered table-sm mb-0">
                <thead>
                    <tr>
                        <th>{{lang['id_short']}}</th>
                        <th>{{lang['code']}}</th>
                        <th>{{lang['name']}}</th>
                        <th>{{lang['price']}}</th>

                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{item_data.id}}
                        </td>
                        <td>{{item_data.code}}</td>
                        <td>{{item_data.name}}</td>
                        <td>{{item_data.price}}</td>

                        <td>
                            <div class="d-flex">

                                <button type="button" class="btn btn-xs btn-success btn-outline mr-1" @click="show_fm()"><i class="fa fa-edit"></i></button>
                                <button type="button" v-if="unselect" class="btn btn-xs btn-danger btn-outline" @click="unselect_item()"><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex w-100 align-items-center" v-if="item_data.id == 0">
                <div style="flex-grow: 1" v-if="options_ready"><a @click.stop.prevent="show_fm()" href="#"> {{lang['select_from_accs']}} </a></div>
                <div class="ml-1">
                    <button class="btn btn-xs btn-success btn-outline" @click="show_fm()"><i class="fa fa-edit"></i></button>
                </div>
            </div>
        </div>

    </div>

    <div class="pp_modal">
        <div ref="fm" class="modal inmodal image_filemanager" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">{{lang['ok']}}</span></button>
                        <h5 class="modal-title">{{lang['pick']}}</h5>
                    </div>
                    <div class="modal-body">

                        <div class="row align-items-center">
                            <div class="col-sm-6 d-flex align-items-center">

                            </div>
                            <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                <pagination_component v-on:change-page="change_page($event)" :pages="pages_count"></pagination_component>
                            </div>
                        </div>

                        <div class="row pt-3">

                            <div class="col-sm-4">
                                <form v-show="!is_modules" v-on:submit.prevent="do_search()">
                                    <div class="form-group">
                                        <label>{{lang['search']}}</label>
                                        <div class="input-group">
                                            <input v-model="filter.search" type="text" class="form-control" style="height: 30px">
                                            <span class="input-group-append" style="font-size: 11px">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>

                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-5">
                                <div v-if="cat_select" class="form-group">
                                    <label for="category">{{lang['category']}}</label>

                                    <v-select
                                        :clearable="false"
                                        :value="filter.category"
                                        label="name"
                                        :options="$options.categories_ordered"
                                        :reduce="category => category.id"
                                        v-model="filter.category"
                                        :key="filter.category"
                                        @input="filter_category($event)"
                                    >

                                        <template v-slot:selected-option="option">
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
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="per_page">{{lang['page_items_count']}}</label>
                                    <v-select
                                        :clearable="false"
                                        :value="filter.per_page"
                                        :options="$options.per_page"
                                        v-model="filter.per_page"
                                        @input="filter_per_page($event)"
                                    >
                                    </v-select>
                                </div>
                            </div>
                        </div>

                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>{{lang['id_short']}}</th>
                                <th v-if="items[0] && items[0].icon">{{lang['icon']}}</th>
                                <th v-if="items[0] && items[0].color">{{lang['material']}}</th>
                                <th v-if="items[0] && items[0].name !== undefined">{{lang['name']}}</th>
                                <th v-if="items[0] && items[0].code !== undefined">{{lang['code']}}</th>
                                <th v-if="is_modules">{{lang['sizes']}}</th>
                                <th v-if="is_modules">{{lang['code']}}</th>
                                <th v-if="items[0] && items[0].category !== undefined">{{lang['category']}}</th>
                                <th v-if="controller == 'modules_sets'"></th>
                                <th v-if="controller == 'modules_sets'"></th>
                                <th v-if="controller == 'modules_sets'"></th>

                                <th class="col-xs-2">{{lang['actions']}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="items[0]" v-for="(item, index) in items">
                                <td style="width: 50px">{{item.id}}</td>
                                <td style="width: 100px" v-if="item.icon"><img style="max-width: 100px" class="img-fluid " :src="correct_url(item.icon)"></td>
                                <td style="width: 100px" v-if="item.color">
                                    <div class="ppip_item_image" :style="{ 'background':  get_map(item)  }"></div>
                                </td>
                                <td v-if="items[0] && items[0].name !== undefined">{{item.name}}</td>
                                <td style="width: 100px" v-if="items[0] && items[0].code !== undefined">{{item.code}}</td>
                                <td v-if="is_modules">
                                    <p v-for="variant in item.params.variants">{{variant.width}}x{{variant.height}}x{{variant.depth}}</p>
                                </td>
                                <td v-if="is_modules">
                                    <p v-for="variant in item.params.variants">{{variant.code}}</p>
                                </td>
                                <td v-if="items[0] && items[0].category !== undefined" style="width: 250px">
                                    <div v-if="is_catalogue">
                                        <div v-if="!item.category.length">
                                            <span>{{lang['no']}}</span>
                                        </div>
                                        <div v-for="cat in item.category">
                                            <span v-if="cat == 0">{{lang['no']}}</span>
                                            <span v-else>
                                                <span v-if="$options.categories_hash[cat] && $options.categories_hash[cat].parent && $options.categories_hash[cat].parent != 0">
                                                    {{$options.categories_hash[$options.categories_hash[cat].parent].name}} /
                                                </span>
                                                <span v-if="$options.categories_hash[cat]">{{$options.categories_hash[cat].name}}</span><span v-else>{{lang['no']}}</span>

                                                <span v-if="!$options.categories_hash[cat]">{{lang['no']}}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div v-if="controller == 'accessories'">
                                            {{item.category}}
                                        </div>
                                        <div v-else>
                                             <span v-if="item.category == 0">
                                                {{lang['no']}}</span>
                                            <span v-else>
                                            <span v-if="$options.categories_hash[item.category] && $options.categories_hash[item.category].parent && $options.categories_hash[item.category].parent != 0">
                                                {{$options.categories_hash[$options.categories_hash[item.category].parent].name}} /
                                            </span>
                                            <span v-if="$options.categories_hash[item.category]">{{$options.categories_hash[item.category].name}}</span><span v-else>{{lang['no']}}</span>

                                            <span v-if="!$options.categories_hash[item.category]">{{lang['no']}}</span>
                                            </span>
                                        </div>


                                    </div>

                                </td>
                                <td v-if="controller == 'modules_sets'">
                                    <a :href="base_url + '/catalog/categories_common/modules_sets_modules/' + item.id"> Редактировать категории </a>
                                </td>
                                <td v-if="controller == 'modules_sets'">
                                    <a :href="base_url + '/catalog/items_common/modules_sets_modules/' + item.id"> Редактировать модули </a>
                                </td>
                                <td v-if="controller == 'modules_sets'">
                                    <a :href="base_url + '/modules_sets_modules/preview_set/' + item.id"> Превью и иконки </a>
                                </td>

                                <td style="width: 150px">
                                    <div v-if="is_single">
                                        <div @click="add_selected(item)" class="btn btn-sm btn-primary btn-outline">
                                            {{lang['pick']}}
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div v-show="!selected_hash[item.id]" @click="add_selected(item)" class="btn btn-sm btn-primary btn-outline">
                                            {{lang['add']}}
                                        </div>
                                        <div v-show="selected_hash[item.id]" @click="remove_selected(item.id)" class="btn btn-sm btn-danger btn-outline">
                                            {{lang['remove']}}
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="row align-items-center">
                            <div class="col-sm-6 d-flex align-items-center">

                            </div>
                            <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                <pagination_component v-on:change-page="change_page($event)" :pages="pages_count"></pagination_component>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">{{lang['ok']}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
