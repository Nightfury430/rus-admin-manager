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
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-1"
                            aria-controls="tab-1"
                            aria-selected="true">
                                <?php echo $lang_arr['corpus_prices'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-10"
                            aria-controls="tab-10"
                            aria-selected="true">
                                <?php echo $lang_arr['edge'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-2"
                            aria-controls="tab-2"
                            aria-selected="true">
                                <?php echo $lang_arr['cokol_prices'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-3"
                            aria-controls="tab-3"
                            aria-selected="true">
                                <?php echo $lang_arr['tabletop_prices'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-4"
                            aria-controls="tab-4"
                            aria-selected="true">
                                <?php echo $lang_arr['wallpanel_prices'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-5"
                            aria-controls="tab-5"
                            aria-selected="true">
                                <?php echo $lang_arr['glass_prices'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-6"
                            aria-controls="tab-6"
                            aria-selected="true">
                                <?php echo $lang_arr['back_wall'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-9"
                            aria-controls="tab-9"
                            aria-selected="true">
                                <?php echo $lang_arr['glass_shelves'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-8"
                            aria-controls="tab-8"
                            aria-selected="true">
                                <?php echo $lang_arr['cornice'] ?>
                            </button>
                        </li>
                        <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-7"
                            aria-controls="tab-7"
                            aria-selected="true">
                                <?php echo $lang_arr['facades_prices'] ?>
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show active" role="tabpanel">
                                <div class="panel-body">
                                    <div class=" card-body">
                                        <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                        <table class="table   table-hover ">
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

                                    <div class=" card-body">
                                        <div class="form-group row">
                                            <div class="col-sm-4 col-sm-offset-2">
                                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div id="tab-10" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <div class=" card-body">
                                    <p>{{lang('price_for')}} {{lang('pogon_units')}}</p>
                                    <table class="table   table-hover ">
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
                                <div class=" card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <div class=" card-body">
                                    <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                    <table class="table   table-hover ">
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
                                <div class=" card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-3" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <div class=" card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p><b>{{lang('tabletop')}}</b> <span style="text-transform: lowercase">{{lang('price_for')}} {{lang('square_units')}}</span></p>
                                            <table class="table   table-hover ">
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
                                            <table class="table   table-hover ">
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
                                <div class=" card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-4" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <div class=" card-body">
                                    <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                    <table class="table   table-hover ">
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
                                <div class=" card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-5" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <div class=" card-body">
                                    <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                    <table class="table   table-hover ">
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
                                <div class=" card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div id="tab-6" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li @click="first_back_wall_key = null" :class="{active: first_back_wall_key == key}" v-for="(type, key) in back_wall" class="nav-item">
                                            <button
                                            type="button"
                                            class="nav-link active"
                                            role="tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="'#bw_'+key"
                                            aria-controls="'bw_'+key"
                                            aria-selected="true">
                                                {{$options.back_wall_selected[key].name}}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    <div :class="{active: first_back_wall_key == key}" v-for="(type, key) in back_wall" :id="'bw_'+key" class="tab-pane fade show active" role="tabpanel">
                                        <dic class="panel-body">
                                            <table class="table   table-hover ">
                                                <tbody>
                                                <tr v-for="(category, index) in type">
                                                    <td style="width: 20%" v-bind:class="[category.parent == 0 ? 'font-bold' : ['font-normal', 'pl-4']]">{{category.name}}</td>
                                                    <td>
                                                        <input min="0" step="0.01"  @input="change_back_wall_children_price(key, index)" class="form-control" v-model="back_wall[key][index].price" type="number">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </dic>
                                    </div>
                                </div>
                                <div class=" card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-9" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <p>{{lang('price_for')}} {{lang('square_units')}}</p>
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item" @click="first_glass_shelves_key = null" :class="{active: first_glass_shelves_key == key}" v-for="(type, key) in glass_shelves">
                                            <button
                                            type="button"
                                            class="nav-link active"
                                            role="tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="'#bw_'+key"
                                            aria-controls="'bw_'+key"
                                            aria-selected="true">
                                                {{$options.glass_shelves_selected[key].name}}
                                            </button>    
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    <div :class="{active: first_glass_shelves_key == key}" v-for="(type, key) in glass_shelves" :id="'bw_'+key" class="tab-pane fade show activity">
                                        <div class="panel-body">
                                            <table class="table   table-hover ">
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
                                </div>
                                <div class=" card-body">
                                    <div class="form-group row">
                                        <div class="col-sm-4 col-sm-offset-2">
                                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save'] ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-7" class="tab-pane fade" role="tabpanel">
                            <div class="panel-body">
                                <div class=" card-body">
                                    <p>{{lang('prices_facades_link')}}</p>
                                </div>
                            </div>
                        </div>

                        <div id="tab-8" class="tab-pane ">
                            <div class="panel-body">
                                <div class=" card-body">
                                    <p>{{lang('prices_cornice_link')}}</p>
                                    <div>
                                        <table class="table   ">
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
                                <div class=" card-body">
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