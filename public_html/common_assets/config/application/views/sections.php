<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Секции</h2>
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


<form @submit="check_form($event)" id="sub_form" action="<?php echo site_url('sections/save_data/') ?>">

    <div class="ibox ">

        <div class="ibox-content">



            <draggable class="" v-model="test" v-bind="dragOptions" handle=".handle" group="parent" @start="drag = true" @end="drag = false">
                    <div class="draggable_parent " v-for="(element,index) in items">
                        <div class="dd-handle d-flex flex-row align-items-center">
                            <div>
                                <i class="fa fa-align-justify handle"></i>
                            </div>
                            <div class="pl-3 col-5" >
                                <input class="form-control" v-model="element.name" type="text">
                            </div>
                            <div class="pl-5">
                                <i @click="add_child(index)" class="fa fa-plus btn btn-outline btn-primary" aria-hidden="true"></i>
                            </div>
                            <div class="ml-auto ">
                                <i @click="show_swal(index, 'parent' )" class="fa fa-trash delete btn btn-outline btn-danger"></i>
                            </div>
                        </div>



                        <draggable class="pl-5" v-model="element.children" v-bind="dragOptions" handle=".handle" group="'children' + index" @start="drag = true" @end="drag = false">
                            <div class="draggable_child " v-for="(item,name) in element.children">
                                <div class="dd-handle d-flex flex-row align-items-center">
                                    <div>
                                        <i class="fa fa-align-justify handle"></i>
                                    </div>
                                    <div class="pl-3 col-5" >
                                        <input class="form-control" v-model="item.name" type="text">
                                    </div>
                                    <div class="col-5">
                                        <v-select multiple :close-on-select="false" :reduce="name => name.id" v-model="item.categories" :options="categories" label="name">
                                            <template v-slot:option="option">
                                                {{ option.name }}
                                            </template>
                                        </v-select>
                                    </div>
                                    <div class="ml-auto ">
                                        <i @click="show_swal(index, 'child', name)" class="fa fa-trash delete btn btn-outline btn-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </draggable>

                    </div>
            </draggable>

            <button  @click="add_parent()" class="mt-3 btn btn-w-m btn-primary btn-outline" type="button"><?php echo $lang_arr['add']?></button>


            <div class="hr-line-dashed"></div>

            <div class="form-group row">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                </div>
            </div>

        </div>
    </div>






</form>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">

<style>

    .draggable_parent{
        /*background: #f3f3f4;*/
        /*margin-bottom: 3px;*/
    }

    .draggable_child{
        /*background: #ffffff;*/
        /*margin-left: 50px;*/
    }

    .dd-handle:hover {
        background: #f0f0f0;
        cursor: auto;
        font-weight: normal;
    }

    i.handle{
        cursor: move;
        font-size: 16px;
    }

    i.delete{

        cursor: pointer;
        font-size: 14px;
    }

    .v-select{
        background: #ffffff;
    }

</style>

<!--<script src="/common_assets/libs/vue/nestable/vue-nestable.js"></script>-->

<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>


<script src="//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js"></script>

<script src="/common_assets/admin_js/vue/kitchen/sections.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>