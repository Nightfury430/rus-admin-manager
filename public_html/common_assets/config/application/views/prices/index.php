<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['materials_prices']?></h2>
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


<input type="hidden" id="js_prices" value="<?php echo htmlentities(json_encode($prices), ENT_QUOTES, 'UTF-8'); ?>">

<input type="hidden" id="mat_cats" value="<?php echo htmlentities(json_encode($mat_cats), ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" id="glass_cats" value="<?php echo htmlentities(json_encode($glass_cats), ENT_QUOTES, 'UTF-8'); ?>">

<input type="hidden" id="glass_sel" value="<?php echo htmlentities(json_encode($glass_sel), ENT_QUOTES, 'UTF-8')?>">
<input type="hidden" id="cokol_sel" value="<?php echo htmlentities(json_encode($cokol_sel), ENT_QUOTES, 'UTF-8')?>">
<input type="hidden" id="corpus_sel" value="<?php echo htmlentities(json_encode($corpus_sel), ENT_QUOTES, 'UTF-8')?>">
<input type="hidden" id="edge_sel" value="<?php echo htmlentities(json_encode($corpus_sel), ENT_QUOTES, 'UTF-8')?>">
<input type="hidden" id="tabletop_sel" value="<?php echo htmlentities(json_encode($tabletop_sel), ENT_QUOTES, 'UTF-8')?>">
<input type="hidden" id="tabletop_plints_sel" value="<?php echo htmlentities(json_encode($tabletop_sel), ENT_QUOTES, 'UTF-8')?>">
<input type="hidden" id="wallpanel_sel" value="<?php echo htmlentities(json_encode($wallpanel_sel), ENT_QUOTES, 'UTF-8')?>">
<input type="hidden" id="back_wall_sel" value="<?php echo htmlentities(json_encode($back_wall_sel), ENT_QUOTES, 'UTF-8')?>">
<?php if(isset($glass_shelves_sel)):?>
    <input type="hidden" id="glass_shelves_sel" value="<?php echo htmlentities(json_encode($glass_shelves_sel), ENT_QUOTES, 'UTF-8')?>">
<?php endif;?>
<input type="hidden" id="dsp_thickness" value="[16,18,22,25,28,32]">

<form @submit="submit" id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('prices/save_data/') ?>">

    <button class="d-none" @click="export_xl" type="button">Экспорт</button>

    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">

                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#tab-1">{{lang('corpus_prices')}}</a></li>
                        <li><a class="nav-link " data-toggle="tab" href="#tab-10">{{lang('edge')}}</a></li>
                        <li><a class="nav-link " data-toggle="tab" href="#tab-2">{{lang('cokol_prices')}}</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-3">{{lang('tabletop_prices')}}</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-4">{{lang('wallpanel_prices')}}</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-5">{{lang('glass_prices')}}</a></li>
                        <li><a class="nav-link " data-toggle="tab" href="#tab-6">{{lang('back_wall')}}</a></li>
                        <li><a class="nav-link " data-toggle="tab" href="#tab-9">{{lang('glass_shelves')}}</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-8">{{lang('cornice')}}</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-7">{{lang('facades_prices')}}</a></li>
                    </ul>
                    <div class="tab-content">

                        <div id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="ibox-content">
                                        <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                        <table class="table table-bordered table-hover ">
                                            <thead>
                                            <tr>
                                                <th style="width: 20%"></th>
                                                <th v-for="(thickness, key) in corpus">{{key}} {{lang('units')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(category, index) in corpus[16]">
                                                <td v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                                <td v-for="(thickness, key) in corpus">
                                                    <input min="0" step="0.01" @input="change_corpus_children_price(key, index)" class="form-control" v-model="corpus[key][index].price" type="number">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>

                                    <div class="ibox-content">
                                        <div class="form-group row">
                                            <div class="col-sm-4 col-sm-offset-2">
                                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div id="tab-10" class="tab-pane ">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <p>{{lang('price_for')}} {{lang('pogon_units')}}</p>
                                    <table class="table table-bordered table-hover ">
                                        <tbody>
                                        <tr v-for="(category, index) in edge">
                                            <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                            <td>
                                                <input min="0" step="0.01" @input="change_children_price('edge', index)"  class="form-control" v-model="edge[index].price" type="number">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane ">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                    <table class="table table-bordered table-hover ">
                                        <tbody>
                                        <tr v-for="(category, index) in cokol">
                                            <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                            <td>
                                                <input min="0" step="0.01" @input="change_children_price('cokol', index)"  class="form-control" v-model="cokol[index].price" type="number">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-3" class="tab-pane ">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><b>{{lang('tabletop')}}</b> <span style="text-transform: lowercase">{{lang('price_for')}} {{lang('square_units')}}</span></p>
                                            <table class="table table-bordered table-hover ">
                                                <tbody>
                                                <tr v-for="(category, index) in tabletop">
                                                    <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                                    <td>
                                                        <input min="0" step="0.01"  @input="change_children_price('tabletop', index)" class="form-control" v-model="tabletop[index].price" type="number">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-6">
                                            <p><b>{{lang('tabletop_plints')}}</b> <span style="text-transform: lowercase">{{lang('price_for')}} {{lang('pogon_units')}}</span></p>
                                            <table class="table table-bordered table-hover ">
                                                <tbody>
                                                <tr v-for="(category, index) in tabletop">
                                                    <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                                    <td>
                                                        <input min="0" step="0.01" @input="change_children_price('tabletop_plints', index)"   class="form-control" v-model="tabletop_plints[index].price" type="number">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-4" class="tab-pane ">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                    <table class="table table-bordered table-hover ">
                                        <tbody>
                                        <tr v-for="(category, index) in wallpanel">
                                            <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                            <td>
                                                <input min="0"  @input="change_children_price('wallpanel', index)" step="0.01" class="form-control" v-model="wallpanel[index].price" type="number">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-5" class="tab-pane ">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                    <table class="table table-bordered table-hover ">
                                        <tbody>
                                        <tr v-for="(category, index) in glass">
                                            <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                            <td>
                                                <input min="0" step="0.01"  @input="change_children_price('glass', index)" class="form-control" v-model="glass[index].price" type="number">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div id="tab-6" class="tab-pane ">
                            <div class="panel-body">
                                <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li @click="first_back_wall_key = null" :class="{active: first_back_wall_key == key}" v-for="(type, key) in back_wall"><a class="nav-link " data-toggle="tab" :href="'#bw_'+key">{{$options.back_wall_selected[key].name}}</a></li>
                                    </ul>
                                </div>
                                <div class="tab-content">

                                    <div :class="{active: first_back_wall_key == key}" v-for="(type, key) in back_wall" :id="'bw_'+key" class="tab-pane">
                                        <table class="table table-bordered table-hover ">
                                            <tbody>
                                            <tr v-for="(category, index) in type">
                                                <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                                <td>
                                                    <input min="0" step="0.01"  @input="change_back_wall_children_price(key, index)" class="form-control" v-model="back_wall[key][index].price" type="number">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-9" class="tab-pane ">
                            <div class="panel-body">
                                <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li @click="first_glass_shelves_key = null" :class="{active: first_glass_shelves_key == key}" v-for="(type, key) in glass_shelves"><a class="nav-link " data-toggle="tab" :href="'#bw_'+key">{{$options.glass_shelves_selected[key].name}}</a></li>
                                    </ul>
                                </div>
                                <div class="tab-content">

                                    <div :class="{active: first_glass_shelves_key == key}" v-for="(type, key) in glass_shelves" :id="'bw_'+key" class="tab-pane">
                                        <table class="table table-bordered table-hover ">
                                            <tbody>
                                            <tr v-for="(category, index) in type">
                                                <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                                <td>
                                                    <input min="0" step="0.01"  @input="change_glass_shelves_children_price(key, index)" class="form-control" v-model="glass_shelves[key][index].price" type="number">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-7" class="tab-pane ">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <p>{{lang('prices_facades_link')}}</p>
                                </div>
                            </div>
                        </div>

                        <div id="tab-8" class="tab-pane ">
                            <div class="panel-body">
                                <div class="ibox-content">
                                    <p>{{lang('prices_cornice_link')}}</p>
                                    <div>
                                        <table class="table table-bordered ">
                                            <thead>
                                            <tr>
                                                <th>{{lang('cornice_item_width')}}, {{lang('units')}}</th>
                                                <th>{{lang('price')}} {{lang('cornice_common')}}, {{lang('currency')}}/{{lang('price_type_pcs')}}</th>
                                                <th>{{lang('price')}} {{lang('cornice_radius')}}, {{lang('currency')}}/{{lang('price_type_pcs')}}</th>
                                                <th>{{lang('price')}} {{lang('cornice_radius_i')}}, {{lang('currency')}}/{{lang('price_type_pcs')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input v-model="cornice.width" type="number" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input v-model="cornice.common" type="number" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input v-model="cornice.radius" type="number" class="form-control form-control-sm">
                                                </td>
                                                <td>
                                                    <input v-model="cornice.radius_i" type="number" class="form-control form-control-sm">
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
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


</form>


<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/prices.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>