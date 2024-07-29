<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['prices']?></h2>
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


<?php $price_data = json_decode($item['price_data'])?>

<script>
    console.log(<?=$item['price_data']?>)
</script>

<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">

                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>

                    <form id="sub_form" data-return="<?php echo site_url('kitchen_models/index/')?>" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('kitchen_models/prices_edit_save/'.$item['id']) ?>">

		                <?php foreach ($facades as $item):?>

			                <?php
			                if ($price_data != null) {
				                $price_facade = '';
				                foreach ($price_data as $pd) {
					                if ($pd->id == $item['id']) {
						                $price_facade = $pd;
					                }
				                }

			                }
			                ?>

                            <div style="margin-bottom: 10px" class="fac_row" data-id="<?php echo $item['id']?>">
                                <div class="row acc_heading">
                                    <div class="col-sm-2 vcenter">
						                <?php if($item['icon'] != null): ?>
                                            <?php
                                                $icon = $item['icon'];
                                                if (strpos($icon, 'common_assets') === false) {
                                                    $icon = $this->config->item('const_path') . $item['icon'];
                                                }
                                            ?>

                                            <div style="width: 50px">
                                                <img  class="img-fluid" src="<?php echo $icon?>" alt="">
                                            </div>

						                <?php else:?>
                                            <div style="background: #ffffff"></div>
						                <?php endif;?>
                                    </div>
                                    <div class="col-sm-4 vcenter">
						                <?php echo $item['name']?>
                                    </div>
                                    <div class="col-sm-4 vcenter">
						                <?php
						                $key = array_search($item['category'], array_column($categories, 'id'));
						                if($key == null){
							                echo $lang_arr['no'];
						                } else {
							                $cat = $categories[$key]['parent'];
							                if($cat == 0){
								                echo $categories[$key]['name'];
							                } else {
								                $key2 = array_search($cat, array_column($materials, 'id'));
								                echo $materials[$key2]['name'];
								                echo '/';
								                echo $categories[$key]['name'];
							                }

						                }
						                ?>
                                    </div>
                                    <div class="col-sm-2 vcenter"></div>
                                </div>
                                <div class="row acc_body">




					                <?php foreach ($modules as $module):?>

						                <?php
						                if ($price_data != null) {
							                $price_module = '';



							                if ($price_facade != '') {

								                foreach ($price_facade->modules as $pf) {
									                if ($module['id'] == $pf->id) {
										                $price_module = $pf;
									                }
								                }
							                }
						                }

						                ?>

                                        <div class="module col-sm-12" data-id="<?php echo $module['id']?>">
                                            <div class="col-sm-1 vcenter">
								                <?php if($item['icon'] != null): ?>
                                                    <img  class="img-fluid" src="<?php echo $this->config->item('const_path').$item['icon']?>" alt="">
								                <?php else:?>
                                                    <div style="background: #ffffff"></div>
								                <?php endif;?>
                                            </div>
                                            <div class="col-sm-2 vcenter">
								                <?php echo $item['name']?>
                                            </div>
                                            <div class="col-sm-2 vcenter">
								                <?php if ($module['icon'] != null): ?>
									                <?php if (strpos($module['icon'], 'common_assets') !== false): ?>
                                                        <img style="max-width: 150px;" class="img-fluid" src="<?php echo $module['icon'] ?>"
                                                             alt="">
									                <?php else: ?>
                                                        <img style="max-width: 150px;" class="img-fluid"
                                                             src="<?php echo $this->config->item('const_path') . $module['icon'] ?>" alt="">
									                <?php endif; ?>

								                <?php endif; ?>
                                            </div>

							                <?php

							                $params = json_decode($module['params']);
							                $variants = $params->params->variants;

							                ?>


                                            <div class="col-sm-3 vcenter tright module_vars">
								                <?php foreach ($variants as $key=>$variant): ?>


                                                    <p data-key="<?php echo $key?>">
										                <?php echo $variant->name . ' ' . $variant->width . 'x' . $variant->height . 'x' . $variant->depth?>
                                                        <input type="hidden" class="m_name" value="<?php echo $variant->name?>">
                                                        <input type="hidden" class="m_width" value="<?php echo $variant->width?>">
                                                        <input type="hidden" class="m_height" value="<?php echo $variant->height?>">
                                                        <input type="hidden" class="m_depth" value="<?php echo $variant->depth?>">
                                                    </p>

								                <?php endforeach;?>

                                            </div>

                                            <div class="col-sm-3 vcenter module_vars_price">
								                <?php foreach ($variants as $key=>$variant): ?>



									                <?php

									                $variant_price = '';
									                $variant_code = '';

									                if ($price_data != null) {
										                $price_variant = '';

										                if($price_module != ''){
											                foreach ($price_module->variants as $pm) {
												                if(
//													                $pm->name == $variant->name &&
													                $pm->width == $variant->width &&
													                $pm->height == $variant->height
												                ){
													                $variant_price = $pm->price;
													                if(isset($pm->code)){
														                $variant_code = $pm->code;
													                }

												                }
											                }
										                } else {
											                $variant_price = '';
											                $variant_code = '';
										                }


									                }

									                ?>

                                                    <p data-key="<?php echo $key?>">
                                                        <input class="code_input" placeholder="<?php echo $lang_arr['code']?>" value="<?php if ($price_data != null) { echo $variant_code; }?>" type="text">
                                                        <input class="price_input"  value="<?php if ($price_data != null) { echo $variant_price; }?>" type="number"> <?php echo $lang_arr['currency']?>.
                                                    </p>
                                                    <!--                                <p data-key="--><?php //echo $key?><!--"></p>-->
								                <?php endforeach; ?>
                                            </div>
                                        </div>
					                <?php endforeach;?>
                                </div>

                            </div>

		                <?php endforeach;?>



                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('kitchen_models/index/') ?>"><?php echo $lang_arr['cancel']?></a>
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>



                    </form>


                </div>
            </div>
        </div>
    </div>
</div>








<style>

    .loader{
        display: none;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 9999;
        padding-top: 200px;
        background: #ffffff;
        text-align: center;
        font-weight: bold;
        font-size: 30px;
    }

    .vcenter {
        display: inline-block;
        vertical-align: middle;
        float: none;
    }

    .tright{
        text-align: right;
    }

    .price_input{
        max-width: 90px;
        text-align: right;
    }

    .code_input{
        max-width: 90px;
    }

    .acc_body{
        display: none;
    }

    .row.fac_row {
        margin: 0 0 20px 0;
        border: 1px solid;
        padding: 10px;
    }

    .acc_heading{
        cursor: pointer;
    }

    .module_vars p{
        height: 50px;
    }

    .module_vars_price p{
        height: 50px;
    }

    .module{
        margin-bottom: 20px;
    }

    .fac_row {
        border: 1px solid;
        padding: 5px;
    }

</style>


<script>
    $(document).ready(function () {
        $('.acc_heading').click(function (e) {
            e.preventDefault();
            e.stopPropagation()
            var row = $(this).parent().find('.acc_body');

            if(row.hasClass('showed')){
                row.slideUp().removeClass('showed')
            } else {
                row.slideDown().addClass('showed')
            }

        });

        $('#sub_form').submit(function (e) {
            e.preventDefault();

            $('.ibox-content').addClass('sk-loading');


            var scope = $(this);

            var facade_rows = $('.fac_row');

            var price_data = [];

            for (var i = 0; i < facade_rows.length; i++){


                var object = {
                    id: parseInt( $(facade_rows[i]).attr('data-id') ),
                    modules:[]
                };

                var modules_rows = $(facade_rows[i]).find('.module');

                for (var m = 0; m < modules_rows.length; m++){

                    var module_variants = $(modules_rows[m]).find('.module_vars').find('p');
                    var module_prices = $(modules_rows[m]).find('.module_vars_price');

                    var module_object = {
                        id: $(modules_rows[m]).attr('data-id'),
                        variants:[]
                    };

                    for (var v = 0; v < module_variants.length; v++){

                        var variant_object = {};

                        var key = $(module_variants[v]).attr('data-key');

                        variant_object.name = $(module_variants[v]).find('.m_name').val();
                        variant_object.width = parseInt( $(module_variants[v]).find('.m_width').val() );
                        variant_object.height = parseInt( $(module_variants[v]).find('.m_height').val() );
                        variant_object.depth = parseInt( $(module_variants[v]).find('.m_depth').val() );

                        variant_object.price =  $(module_prices).find('p[data-key="'+ key +'"]').find('.price_input').val();
                        variant_object.code =  $(module_prices).find('p[data-key="'+ key +'"]').find('.code_input').val();


                        if(variant_object.price === null || variant_object.price === '') variant_object.price = 0;
                        if(variant_object.code === null || variant_object.code === '') variant_object.code = '';

                        variant_object.price = parseInt( variant_object.price )


                        module_object.variants.push(variant_object);

                    }

                    object.modules.push(module_object)

                }
                price_data.push(object)

            }


            $.ajax({
                url: $('#sub_form').attr('action'),
                type: 'post',
                data: {data:JSON.stringify(price_data)}
            }).done(function (msg) {
                window.location.href = scope.attr('data-return')
            });


        })


    })
</script>
