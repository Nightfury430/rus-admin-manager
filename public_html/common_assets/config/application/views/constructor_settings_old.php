<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['constructor_settings_heading']?></h2>
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

    <form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url( 'constructor/save_data/' ) ?>">

        <div class="row">
            <div class="col-lg-12">

                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?php echo $lang_arr['prices_block_label'] ?></h5>
                    </div>
                    <div class="ibox-content">

	                    <?php if ($this->config->item('sub_account') == false): ?>

                            <div class="form-group">
                                <label for="price_enabled"><?php echo $lang_arr['prices']?></label>
                                <select class="form-control" name="price_enabled" id="price_enabled">
                                    <option <?php if($settings['price_enabled'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['price_enabled'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>



                            <div class="form-group">
                                <label for="shop_mode"><?php echo $lang_arr['kitchen_dealer_mode']?></label>
                                <select class="form-control" name="shop_mode" id="shop_mode">
                                    <option <?php if($settings['shop_mode'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['shop_mode'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>



                            <div class="form-group <?php if($settings['shop_mode'] != 1) echo 'hidden'?>" id="allow_common_mode_wrapper">
                                <label for="allow_common_mode"><?php echo $lang_arr['allow_common_mode_select']?></label>
                                <select class="form-control" name="allow_common_mode" id="allow_common_mode">
                                    <option <?php if($settings['allow_common_mode'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['allow_common_mode'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>


                            <div class="form-group <?php if($settings['shop_mode'] != 1) echo 'hidden'?>" id="default_kitchen_model_wrapper">
                                <label for="default_kitchen_model"><?php echo $lang_arr['default_kitchen_model']?></label>
                                <select class="form-control" name="default_kitchen_model" id="default_kitchen_model">
                                    <option value=""><?php echo $lang_arr['not_selected']?></option>
				                    <?php foreach ($kitchen_models as $km): ?>
                                        <option <?php if($settings['default_kitchen_model'] == $km['id'] ) echo 'selected'?> value="<?php echo $km['id'] ?>"><?php echo $km['name'] ?></option>
				                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group <?php if($settings['shop_mode'] != 1) echo 'hidden'?>" id="multiple_facades_mode_wrapper">
                                <label for="multiple_facades_mode"><?php echo $lang_arr['multiple_facades_in_kitchen_model']?></label>
                                <select class="form-control" name="multiple_facades_mode" id="multiple_facades_mode">
                                    <option <?php if($settings['multiple_facades_mode'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['multiple_facades_mode'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

	                    <?php endif;?>

                        <div class="form-group">
                            <label for="price_modificator"><?php echo $lang_arr['price_modificator']?></label>
                            <input value="<?php echo $settings['price_modificator']?>" type="text" class="form-control" name="price_modificator" id="price_modificator" placeholder="1.00">
                        </div>

                        <div class="form-group">
                            <label for="accessories_shop_enabled"><?php echo $lang_arr['accessories_shop']?> (<?php echo $lang_arr['accessories_shop_warning']?> <a href="<?php echo site_url('accessories/') ?>">"<?php echo $lang_arr['accessories_label']?>"</a>)</label>
                            <select class="form-control" name="accessories_shop_enabled" id="accessories_shop_enabled">
                                <option <?php if($settings['accessories_shop_enabled'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                <option <?php if($settings['accessories_shop_enabled'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                            </select>
                        </div>

                    </div>
                </div>

	            <?php if ($this->config->item('sub_account') == false): ?>


                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?php echo $lang_arr['facade_systems'] ?></h5>
                    </div>
                    <div class="ibox-content">

                        <div class="form-group">
                            <label for="facades_system_available"><?php echo $lang_arr['facade_systems_enabled']?></label>
                            <select class="form-control" name="facades_system_available" id="facades_system_available">
                                <option <?php if($settings['facades_system_available'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                <option <?php if($settings['facades_system_available'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?php echo $lang_arr['decorations'] ?></h5>
                    </div>
                    <div class="ibox-content">

                        <div class="form-group">
                            <label for="decorations_enabled"><?php echo $lang_arr['decorations_enabled']?></label>
                            <select class="form-control" name="decorations_enabled" id="decorations_enabled">
                                <option <?php if($settings['decorations_enabled'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                <option <?php if($settings['decorations_enabled'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                            </select>
                        </div>

                    </div>
                </div>

                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5><?php echo $lang_arr['tabs'] ?></h5>
                        </div>
                        <div class="ibox-content">

                            <div class="form-group">
                                <label for="show_kitchen_parameters"><?php echo $lang_arr['show_kitchen_params']?></label>
                                <select class="form-control" name="show_kitchen_parameters" id="show_kitchen_parameters">
                                    <option <?php if($settings['show_kitchen_parameters'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['show_kitchen_parameters'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tab_show_furniture"><?php echo $lang_arr['show_hinges_lockers']?></label>
                                <select class="form-control" name="tab_show_furniture" id="tab_show_furniture">
                                    <option <?php if($settings['tab_show_furniture'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['tab_show_furniture'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5><?php echo $lang_arr['functionality'] ?></h5>
                        </div>
                        <div class="ibox-content">

                            <div class="form-group">
                                <label for="cornice_available"><?php echo $lang_arr['cornice_available']?></label>
                                <select class="form-control" name="cornice_available" id="cornice_available">
                                    <option <?php if($settings['cornice_available'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['cornice_available'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="custom_sizes_available"><?php echo $lang_arr['custom_sizes_available']?></label>
                                <select class="form-control" name="custom_sizes_available" id="custom_sizes_available">
                                    <option <?php if($settings['custom_sizes_available'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['custom_sizes_available'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="facade_style_change_availabale"><?php echo $lang_arr['facade_style_change_availabale']?></label>
                                <select class="form-control" name="facade_style_change_availabale" id="facade_style_change_availabale">
                                    <option <?php if($settings['facade_style_change_availabale'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['facade_style_change_availabale'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="show_specs"><?php echo $lang_arr['show_specs']?></label>
                                <select class="form-control" name="show_specs" id="show_specs">
                                    <option <?php if($settings['show_specs'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['show_specs'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="frontend_configurator_available"><?php echo $lang_arr['frontend_configurator_available']?></label>
                                <select class="form-control" name="frontend_configurator_available" id="frontend_configurator_available">
                                    <option <?php if($settings['frontend_configurator_available'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['yes']?></option>
                                    <option <?php if($settings['frontend_configurator_available'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="icons_type"><?php echo $lang_arr['icons']?></label>
                                <select class="form-control" name="icons_type" id="icons_type">
                                    <option <?php if($settings['icons_type'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['icons_gray']?></option>
                                    <option <?php if($settings['icons_type'] == 0) echo 'selected'?> value="0"><?php echo $lang_arr['icons_red']?></option>
                                </select>
                            </div>

                        </div>
                    </div>

                <?php endif;?>



                <div class="ibox ">
                    <div class="ibox-content">

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





<script>

    $(document).ready(function () {

        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });

        $('#shop_mode').change(function () {

            let scope = $(this);

            if ($(this).val() == 1) {

                $.ajax({
                    url: "<?php echo site_url('kitchen_models/check_models_ajax/')?>",
                    type: 'post',
                }).done(function (msg) {
                    if(msg == 0){
                        scope.val(0);


                        toastr.warning('<?php echo $lang_arr['dealer_error_no_model']?> "<?php echo $lang_arr['kitchen_models']?>"')


                        return false;
                    } else {

                        $.ajax({
                            url: "<?php echo site_url('module_sets/check_sets_ajax/')?>",
                            type: 'post',
                        }).done(function (msg) {
                            console.log(msg)
                            if(msg == 0){
                                scope.val(0);

                                toastr.warning('<?php echo $lang_arr['dealer_error_no_sets']?> "<?php echo $lang_arr['kitchen_models']?>"')



                                return false;
                            } else {
                                $('#default_kitchen_model_wrapper').removeClass('hidden');
                                $('#multiple_facades_mode_wrapper').removeClass('hidden');
                                $('#allow_common_mode_wrapper').removeClass('hidden')
                            }
                        });

                    }
                });


            } else {
                $('#default_kitchen_model_wrapper').addClass('hidden');
                $('#multiple_facades_mode_wrapper').addClass('hidden');
                $('#allow_common_mode_wrapper').addClass('hidden')
            }
        });


        $('#sub_form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: $('#sub_form').attr('action'),
                type: 'post',
                data: $(this).serialize()
            }).done(function (msg) {
                // console.log(msg)
                toastr.success('<?php echo $lang_arr['success']?>')
                //alert('<?php //echo $lang_arr['success']?>//')
            });

        })


    });

</script>