<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<!--<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>-->
<!--<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>-->
<script src="/common_assets/admin_js/vue/catalog/prices.js?<?php echo md5(date('m-d-Y-His A e')); ?>"></script>
<script src="/common_assets/admin_js/vue/pagination.js"></script>

<?php if (!isset($set_id)) $set_id = '';?>

<div v-cloak id="sub_form">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{heading}}</h2>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">

                    <div class="ibox-content">
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
                                        <label ><?php echo $lang_arr['search']?></label>
                                        <div class="input-group">
                                            <input v-model="filter.search" type="text" class="form-control" style="height: 30px">
                                            <span class="input-group-append" style="font-size: 11px">
                                            <button type="submit" class="btn btn-primary" >
                                                <i class="fa fa-search"></i>
                                            </button>

                                        </span></div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group" >
                                    <label for="category"><?php echo $lang_arr['category']?></label>
                                    <!--                                            <select class="form-control" name="category" id="category">-->
                                    <!--                                                <option v-for="item in categories_ordered">{{item.name}}</option>-->
                                    <!--                                            </select>-->

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

                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th><?php echo $lang_arr['id_short']?></th>
                                <th v-if="items[0] && items[0].icon"><?php echo $lang_arr['icon']?></th>
                                <th v-if="items[0] && items[0].color"><?php echo $lang_arr['material']?></th>
                                <th v-if="items[0] && items[0].name !== undefined"><?php echo $lang_arr['name']?></th>
                                <th v-if="items[0] && items[0].code !== undefined"><?php echo $lang_arr['code']?></th>
                                <th v-if="is_modules"><?php echo $lang_arr['sizes']?></th>
                                <th v-if="is_modules"><?php echo $lang_arr['code']?></th>
                                <th><?php echo $lang_arr['category']?></th>

                                <th><?php echo $lang_arr['order']?></th>
                                <th><?php echo $lang_arr['is_visible']?></th>
                                <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="items[0]" v-for="item in items">
                                <td style="width: 50px">{{item.id}}</td>
                                <!--                                <td style="width: 100px" v-if="items[0] && items[0].icon"><img style="max-width: 100px" class="img-fluid " :src="correct_url(item.icon)"></td>-->
                                <td style="width: 100px" v-if="item.icon"><img style="max-width: 100px" class="img-fluid " :src="correct_url(item.icon)"></td>
                                <!--                                <td style="width: 100px" v-if="items[0] && items[0].color"><div class="item_image" :style="{ 'background':  get_map(item)  }"></div></td>-->
                                <td style="width: 100px" v-if="item.color"><div class="item_image" :style="{ 'background':  get_map(item)  }"></div></td>
                                <td v-if="items[0] && items[0].name !== undefined">{{item.name}}</td>
                                <td style="width: 100px" v-if="items[0] && items[0].code !== undefined">{{item.code}}</td>
                                <td v-if="is_modules">
                                    <p v-for="variant in item.params.variants">{{variant.width}}x{{variant.height}}x{{variant.depth}}</p>
                                </td>
                                <td v-if="is_modules">
                                    <p v-for="variant in item.params.variants">{{variant.code}}</p>
                                </td>
                                <td style="width: 250px">
                                    <span v-if="item.category == 0">{{lang('no')}}</span>
                                    <span v-else>
                                        <span v-if="categories_hash[item.category] && categories_hash[item.category].parent && categories_hash[item.category].parent != 0">
                                        {{categories_hash[categories_hash[item.category].parent].name}} /
                                    </span>
                                    <span v-if="categories_hash[item.category]">{{categories_hash[item.category].name}}</span><span v-else>{{lang['no']}}</span>

                                    <span v-if="!categories_hash[item.category]">{{lang('no')}}</span>
                                    </span>

                                </td>

                                <td style="width: 100px">{{item.order}}</td>
                                <td style="width: 100px">
                                    <i @click="change_active(item)" :class="get_eye_class(item)" class="fa fa-eye btn btn-outline"></i>
                                </td>
                                <td style="width: 100px">
                                    <i :title="lang('edit')" @click="edit_item(item)" class="fa fa-edit btn btn-outline btn-success"></i>
                                    <i :title="lang('delete')" @click="show_swal(item)" class="fa fa-trash btn btn-outline btn-danger"></i>
                                </td>
                            </tr>
                            </tbody>
                        </table>


                        <div class="row align-items-center">
                            <div class="col-sm-6 d-flex align-items-center">
                                <div class="btn btn-w-m btn-primary" @click="add_item()" role="button"><?php echo $lang_arr['add']?></div>
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






</div>







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