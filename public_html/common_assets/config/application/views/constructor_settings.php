<div class="wrapper wrapper-content  animated fadeInRight">
    <form ref="sub_form" id="sub_form" @submit="check_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url( 'constructor/save_data/' ) ?>">
        <div class="row">
            <div class="col-lg-12">

                <div class="card mb-5">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['prices_block_label'] ?></h5>
                    </div>
                    <div v-cloak="1" class="card-body">
	                    <?php if ($this->config->item('sub_account') == false): ?>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['prices']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.price_enabled" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
	                    <?php endif;?>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['price_modificator']?></label>
                            <div class="col-sm-8">
                                <input v-model="settings.price_modificator" step="0.00001" type="number" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['accessories_shop']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input  v-bind:true-value="1" v-bind:false-value="0" v-model="settings.accessories_shop_enabled" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                                <span class="form-text m-b-none"><?php echo $lang_arr['accessories_shop_warning']?> <a href="<?php echo site_url('accessories/') ?>">"<?php echo $lang_arr['accessories_label']?>"</a></span>
                            </div>
                        </div>
                        <?php if(count($this->config->item('sub_accounts')) > 1): ?>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['accessories_sub_copy']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input  v-bind:true-value="1" v-bind:false-value="0" v-model="settings.accessories_sub_copy" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        <?php endif;?>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['use_multiplier_in_accessories']?></label>
                            <div class="col-sm-2">
                                <label class="switch">
                                    <input  v-bind:true-value="1" v-bind:false-value="0" v-model="settings.use_multiplier_in_accessories" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div v-show="settings.use_multiplier_in_accessories == 1" class="col-sm-4">
                                <input v-model="settings.price_modificator_acc" step="0.00001" type="number" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['use_multiplier_in_furnitypes']?></label>
                            <div class="col-sm-2">
                                <label class="switch">
                                    <input  v-bind:true-value="1" v-bind:false-value="0" v-model="settings.use_multiplier_in_furnitypes" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div v-show="settings.use_multiplier_in_furnitypes == 1" class="col-sm-4">
                                <input v-model="settings.price_modificator_furni" step="0.00001" type="number" class="form-control">
                            </div>
                        </div>


                    </div>
                </div>

                <?php if ($this->config->item('sub_account') == false): ?>
                <div class="card mb-5">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['kitchen_dealer_mode'] ?></h5>
                    </div>
                    <div v-cloak="1" class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['kitchen_dealer_mode']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input @change="shop_mode_change($event)" v-bind:true-value="1" v-bind:false-value="0" v-model="settings.shop_mode" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="tr_05" v-show="settings.shop_mode == 1">

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_common_mode_select']?></label>
                                    <div class="col-sm-8">
                                        <label class="switch">
                                            <input  v-bind:true-value="1" v-bind:false-value="0" v-model="settings.allow_common_mode" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['default_kitchen_model']?></label>
                                    <div class="col-sm-8">
                                        <select v-model="settings.default_kitchen_model" class="form-control">
                                            <option value=""><?php echo $lang_arr['not_selected']?></option>
                                            <option v-bind:value="item.id" v-for="item in kitchen_models">{{item.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"><?php echo $lang_arr['multiple_facades_in_kitchen_model']?></label>
                                    <div class="col-sm-8">
                                        <label class="switch">
                                            <input  v-bind:true-value="1" v-bind:false-value="0" v-model="settings.multiple_facades_mode" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                            </div>






                    </div>
                </div>
                <?php endif;?>

	            <?php if ($this->config->item('sub_account') == false): ?>

<!--                <div class="card mb-5 ">-->
<!--                    <div class="card-header">-->
<!--                        <h5>--><?php //echo $lang_arr['facade_systems'] ?><!--</h5>-->
<!--                    </div>-->
<!--                    <div class="card-body">-->
<!---->
<!--                        <div  class="form-group row ">-->
<!--                            <label class="col-sm-4 col-form-label">Фасадные системы "МакБерри"</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <label class="switch">-->
<!--                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.facades_system_available" type="checkbox">-->
<!--                                    <span class="slider round"></span>-->
<!--                                </label>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!---->
<!--                        <div class="form-group row">-->
<!--                            <label class="col-sm-4 col-form-label">Фасадные системы "Русский стандарт"</label>-->
<!--                            <div class="col-sm-8">-->
<!--                                <label class="switch">-->
<!--                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.russta" type="checkbox">-->
<!--                                    <span class="slider round"></span>-->
<!--                                </label>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!---->
<!--                    </div>-->
<!--                </div>-->

                <div class="card mb-5 ">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['decorations'] ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['decorations_enabled']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.decorations_enabled" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mb-5 ">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['tabs'] ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['show_kitchen_params']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.show_kitchen_parameters" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['show_shelves_panel']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.show_shelves_panel" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>


                    </div>
                </div>

	            <?php endif;?>


                <div class="card mb-5 ">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['pdf_use_password'] ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['pdf_use_password']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.pdf_use_password" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div v-show="settings.pdf_use_password == true" class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['password']?></label>
                            <div class="col-sm-8">
                                <input class="form-control" v-model="settings.pdf_password" type="password">
                            </div>
                        </div>




                    </div>
                </div>

                <div class="card mb-5 ">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['functionality'] ?></h5>
                    </div>
                    <div class="card-body">

	                    <?php if ($this->config->item('sub_account') == false): ?>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['tabletop_v2']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.tabletop_v2" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['use_corpus_direct_price']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.use_corpus_direct_price" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>


                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cornice_available']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.cornice_available" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['tabletop_plints']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.tabletop_plints_available" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['tabletop_plints_default']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.tabletop_plints_default" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['cokol_top']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.cokol_top_available" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['custom_sizes_available']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.custom_sizes_available" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['facade_style_change_availabale']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.facade_style_change_availabale" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_facade_group_change']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.allow_facade_group_change" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['show_specs']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.show_specs" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['frontend_configurator_available']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.frontend_configurator_available" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><?php echo $lang_arr['allow_top_modules_mass_depth_change']?></label>
                                <div class="col-sm-8">
                                    <label class="switch">
                                        <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.allow_top_modules_mass_depth_change" type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

	                    <?php endif;?>






                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['use_common_modules']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.use_common_modules" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['start_with_project_select']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.start_with_project_select" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['check_intersection_default']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.check_intersection_default" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>




                <div class="card mb-5 ">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['view_label'] ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['icons'] ?></label>
                            <div class="col-sm-8">
                                <select v-model="settings.icons_type" class="form-control">
                                    <option value="1"><?php echo $lang_arr['icons_gray'] ?></option>
                                    <option value="0"><?php echo $lang_arr['icons_red'] ?></option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['reflection_image_label']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.use_custom_reflection" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div v-show="settings.use_custom_reflection == 1" class="form-group  row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['reflection_image'] ?></label>
                            <div class="col-sm-8">

                                <pp_image @e_update="settings.custom_reflection_image=$event" :src="settings.custom_reflection_image"></pp_image>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['modules_interface'] ?></label>
                            <div class="col-sm-8">
                                <select v-model="settings.modules_interface" class="form-control">
                                    <option value="height"><?php echo $lang_arr['modules_interface_height'] ?></option>
                                    <option value="categories"><?php echo $lang_arr['modules_interface_categories'] ?></option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['show_modules_names_in_sizes_select'] ?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.show_modules_names_in_sizes_select" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['use_custom_lockers'] ?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.custom_lockers" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>


                <div v-if="settings.sizes_limit" class="card mb-5 ">
                    <div class="card-header">
                        <h5><?php echo $lang_arr['size_limit'] ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_min_width'] ?></label>
                            <div class="col-sm-3">
                                <input v-model="settings.sizes_limit.min_width" type="number" min="0" class="form-control">
                            </div>
                            <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_max_width'] ?></label>
                            <div class="col-sm-3">
                                <input v-model="settings.sizes_limit.max_width" type="number" min="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_min_height'] ?></label>
                            <div class="col-sm-3">
                                <input v-model="settings.sizes_limit.min_height" type="number" class="form-control">
                            </div>
                            <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_max_height'] ?></label>
                            <div class="col-sm-3">
                                <input v-model="settings.sizes_limit.max_height" type="number" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_min_depth'] ?></label>
                            <div class="col-sm-3">
                                <input v-model="settings.sizes_limit.min_depth" type="number" class="form-control">
                            </div>
                            <label class="col-sm-3 col-form-label"><?php echo $lang_arr['size_limit_max_depth'] ?></label>
                            <div class="col-sm-3">
                                <input v-model="settings.sizes_limit.max_depth" type="number" class="form-control">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card mb-5 ">
                    <div class="card-body">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>