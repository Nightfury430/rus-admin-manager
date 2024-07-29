<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['modules_list']?></h2>
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
                <div class="ibox-title">
                    <h5><?php echo $lang_arr['modules_list'] ?></h5>
                </div>
                <div class="ibox-content">
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-6">
                            <a class="btn btn-w-m btn-default" href="<?php echo site_url('module_sets/sets_index/') ?>" role="button"><?php echo $lang_arr['back_to_modules_sets']?></a>
                            <a class="btn btn-w-m btn-primary" href="<?php echo site_url('module_sets/items_add/'.$set_id) ?>" role="button"><?php echo $lang_arr['add_module']?></a>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button style="width: 220px; line-height: 12px; white-space: normal; font-size: 12px;" data-toggle="modal" data-target="#mass_change_modal" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['mass_params_change']?> <?php echo $lang_arr['kitchen_bottom_modules']?></button>
                            <button style="width: 220px; line-height: 12px; white-space: normal; font-size: 12px;" data-toggle="modal" data-target="#mass_change_modal_top" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['mass_params_change']?> <?php echo $lang_arr['kitchen_top_modules']?></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 d-flex align-items-center">
							<?php echo $pagination?>
                        </div>
                        <div class="col-sm-6">
                            <form id="filter_form" method="post" accept-charset="UTF-8" action="<?php echo site_url('module_sets/items_index/' . $set_id . '/') ?>">

                            <input type="hidden" name="filter" value="1">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label style="margin-bottom: 2px" for="category"><?php echo $lang_arr['category']?></label>
                                            <select class="form-control" name="category" id="category">
                                                <option data-data='{"class": "top_cat"}' value="0"><?php echo $lang_arr['all']?></option>
                                                <?php foreach ($cat_tree as $cat):?>
                                                    <?php if($cat['parent'] == 0): ?>
                                                        <option <?php if($this->uri->segment(4) == $cat['id']) echo 'selected';?> data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
                                                        <?php if (isset($cat['children'])):?>
                                                            <?php foreach ($cat['children'] as $child):?>
                                                                <option <?php if($this->uri->segment(4) == $child['id']) echo 'selected';?> data-data='{"class": "sub_cat"}' value="<?php echo $child['id']?>"><?php echo $cat['name']?> / <?php echo $child['name']?></option>
                                                            <?php endforeach;?>
                                                        <?php endif;?>
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label style="margin-bottom: 2px" for="per_page"><?php echo $lang_arr['page_items_count']?></label>
                                            <select class="form-control" name="per_page" id="per_page">
                                                <option <?php if($this->uri->segment(5) == 10) echo 'selected';?> value="10">10</option>
                                                <option <?php if($this->uri->segment(5) == 20) echo 'selected';?> value="20">20</option>
                                                <option <?php if($this->uri->segment(5) == 50) echo 'selected';?> value="50">50</option>
                                                <option <?php if($this->uri->segment(5) == 100) echo 'selected';?> value="100">100</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th><?php echo $lang_arr['code']?></th>
                            <th><?php echo $lang_arr['icon']?></th>
                            <th><?php echo $lang_arr['name']?></th>
                            <th><?php echo $lang_arr['price']?></th>
                            <th><?php echo $lang_arr['category']?></th>
                            <th><?php echo $lang_arr['order']?></th>
                            <th><?php echo $lang_arr['is_visible']?></th>
                            <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                        </tr>
                        </thead>
                        <tbody>

	                    <?php foreach ($items as $item):?>
		                    <?php

		                    $item_params = json_decode($item['params']);

		                    if(isset ($item_params->params->variants[0]) ){
			                    $variant_params = $item_params->params->variants[0];
		                    } else {
			                    $variant_params = new stdClass();
			                    $variant_params->code = '';
			                    $variant_params->name = '<p style="color:red; font-weight:bold;">'. $lang_arr['modules_sets_no_variants_warning'] .'</p>';
			                    $variant_params->price = '';
		                    }






		                    ?>
                            <tr>
                                <td>
				                    <?php echo $variant_params->code?>
                                </td>
                                <td>
                                    <div class="item_image">
					                    <?php if($item['icon'] != null): ?>
						                    <?php if (strpos($item['icon'], 'common_assets') !== false): ?>
                                                <img class="img-fluid" src="<?php echo $item['icon'] ?>" alt="">
						                    <?php else: ?>
                                                <img class="img-fluid" src="<?php echo $this->config->item('const_path') . $item['icon'] ?>" alt="">
						                    <?php endif; ?>

					                    <?php endif; ?>
                                    </div>
                                </td>

                                <td>
				                    <?php echo $variant_params->name?>
                                </td>

                                <td>
				                    <?php echo $variant_params->price?>
                                </td>

                                <td>

				                    <?php

					                    $key = array_search($item['category'], array_column($categories, 'id'));

					                    if ($key == null) {
						                    echo $lang_arr['no'];
					                    } else {
						                    echo $categories[$key]['name'];
					                    }
				                    ?>
                                </td>
                                <td>
				                    <?php echo $item['order']?>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a class="is_visible <?php if($item['active'] ==0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a href="<?php echo site_url('module_sets/items_add/'. $set_id  . '/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a class="delete_button" href="<?php echo site_url('module_sets/items_remove/'.$set_id.'/' . $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
                                    </div>
                                </td>
                            </tr>

	                    <?php endforeach; ?>
                        </tbody>
                    </table>


                    <div class="row">
                        <div class="col-sm-12">
							<?php echo $pagination ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal inmodal fade" id="mass_change_modal" tabindex="-1" role="dialog">
        <form id="mass_modal" data-cat="1" data-action="<?php echo site_url('modules/mass_modules_bottom/') ?>">

            <div style="max-width: 800px" class="modal-dialog">
                <div class="modal-content animated ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <i class="fa fa-laptop modal-icon"></i>
                        <h4 class="modal-title"><?php echo $lang_arr['mass_params_change']?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['initial_value']?></p></div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['new_value']?></p></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-3"><div ><?php echo $lang_arr['depth_tabletop']?>, <?php echo $lang_arr['units']?></div></div>
                            <div class="col-sm-3"><input id="bm_depth" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-1"><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div class="col-sm-3"><input  id="bm_depth_to" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button  id="bm_mass_depth" type="button" class="btn btn-primary" data-action="<?php echo site_url('module_sets/mass_modules_bottom/') . $set_id ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4"><?php echo $lang_arr['tabletop_offset_front']?>, <?php echo $lang_arr['units']?></div>
                            <div class="col-sm-6"> <input id="bm_fo" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button id="bm_mass_fo" type="button" class="btn btn-primary" data-action="<?php echo site_url('module_sets/mass_modules_bottom/') . $set_id  ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4"><?php echo $lang_arr['tabletop_offset_back']?>, <?php echo $lang_arr['units']?></div>
                            <div class="col-sm-6"> <input id="bm_bo" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button id="bm_mass_bo" type="button" class="btn btn-primary" data-action="<?php echo site_url('module_sets/mass_modules_bottom/') . $set_id ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['initial_value']?></p></div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3 text-center"><p style="margin-bottom: 3px"><?php echo $lang_arr['new_value']?></p></div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-3"><div><?php echo $lang_arr['corpus_height']?>, <?php echo $lang_arr['units']?></div></div>
                            <div class="col-sm-3"><input id="bm_height" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-1"><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div class="col-sm-3"><input  id="bm_height_to" type="number" value="0" class="form-control"></div>
                            <div class="col-sm-2"> <button id="bm_mass_height" type="button" class="btn btn-primary" data-action="<?php echo site_url('module_sets/mass_modules_bottom/') . $set_id  ?>" ><?php echo $lang_arr['apply']?></button></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                    </div>
                </div>

            </div>
        </form>
    </div>



    <div class="modal inmodal fade" id="mass_change_modal_top" tabindex="-1" role="dialog">
        <form  data-cat="2" data-action="<?php echo site_url('modules/mass_modules_top/') ?>">

            <div style="max-width: 800px"  class="modal-dialog">
                <div class="modal-content animated ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <i class="fa fa-laptop modal-icon"></i>
                        <h4 class="modal-title"><?php echo $lang_arr['mass_params_change']?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group d-flex flex-row align-items-center">
                            <div><div class="p-2"><?php echo $lang_arr['corpus_height']?>, <?php echo $lang_arr['units']?></div></div>
                            <div><input id="tm_height" type="number" value="0" class="form-control"></div>
                            <div><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div><input  id="tm_height_to" type="number" value="0" class="form-control"></div>
                            <div class="p-2"> <button id="tm_mass_height" type="button" class="btn btn-primary" data-action="<?php echo site_url('module_sets/mass_modules_top/') . $set_id  ?>"><?php echo $lang_arr['apply']?></button></div>
                        </div>
                        <div class="form-group d-flex flex-row align-items-center">
                            <div><div class="p-2"><?php echo $lang_arr['depth']?>, <?php echo $lang_arr['units']?></div></div>
                            <div><input id="tm_depth" type="number" value="0" class="form-control"></div>
                            <div><div class="p-2"><?php echo $lang_arr['for']?></div></div>
                            <div><input  id="tm_depth_to" type="number" value="0" class="form-control"></div>
                            <div class="p-2"> <button id="tm_mass_depth" type="button" class="btn btn-primary" data-action="<?php echo site_url('module_sets/mass_modules_top/') . $set_id  ?>"><?php echo $lang_arr['apply']?></button></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>


<script>
    $(document).ready(function () {
        $('.delete_button').click(function (e) {
            e.preventDefault();

            let scope = $(this);

            swal({
                title: "<?php echo $lang_arr['are_u_sure']?>",
                text: $('#delete_confirm_message').html(),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: false
            }, function () {
                window.location.href = scope.attr('href');
            });
        });

        $('#bm_mass_depth').click(function (e) {
            e.preventDefault();
            let data = {};
            let scope = $(this);
            if($('#bm_depth').val() > 0 && $('#bm_depth_to').val() > 0){
                data.depth = $('#bm_depth').val();
                data.depth_to = $('#bm_depth_to').val();
            }
            send_mass(scope.attr('data-action'), data)

        });

        $('#bm_mass_fo').click(function (e) {
            e.preventDefault();
            let data = {};
            let scope = $(this);
            if($('#bm_fo').val() > 0) data.front_offset = $('#bm_fo').val();
            send_mass(scope.attr('data-action'), data)
        });

        $('#bm_mass_bo').click(function (e) {
            e.preventDefault();
            let data = {};
            let scope = $(this);
            if($('#bm_bo').val() > 0) data.back_offset = $('#bm_bo').val();
            send_mass(scope.attr('data-action'), data)
        });

        $('#bm_mass_height').click(function (e) {
            e.preventDefault();
            let data = {};
            let scope = $(this);
            if($('#bm_height').val() > 0 && $('#bm_height_to').val() > 0){
                data.height = $('#bm_height').val();
                data.height_to = $('#bm_height_to').val();
            }
            send_mass(scope.attr('data-action'), data)

        });

        $('#tm_mass_depth').click(function (e) {
            e.preventDefault();
            let data = {};
            let scope = $(this);
            if($('#tm_depth').val() > 0 && $('#tm_depth_to').val() > 0){
                data.depth = $('#tm_depth').val();
                data.depth_to = $('#tm_depth_to').val();
            }
            send_mass(scope.attr('data-action'), data)

        });

        $('#tm_mass_height').click(function (e) {
            e.preventDefault();
            let data = {};
            let scope = $(this);
            if($('#tm_height').val() > 0 && $('#tm_height_to').val() > 0){
                data.height = $('#tm_height').val();
                data.height_to = $('#tm_height_to').val();
            }
            send_mass(scope.attr('data-action'), data)
        });

        function send_mass(url, data) {
            if(Object.keys(data).length > 0){

                data.data = 1;

                swal({
                    title: "<?php echo $lang_arr['are_u_sure']?>",
                    text: $('#change_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "<?php echo $lang_arr['no']?>",
                    confirmButtonText: "<?php echo $lang_arr['yes']?>",
                    closeOnConfirm: true
                }, function () {
                    $.ajax({
                        url: url,
                        type: 'post',
                        data:  data,
                    }).done(function (msg) {
                        console.log(msg)
                        toastr.success("<?php echo $lang_arr['success']?>")
                        $('#mass_change_modal').modal('hide')
                    })
                });


            } else {
                toastr.error("<?php echo $lang_arr['error']?>")
            }
        }

        $('#category').selectize({
            create: false,
            render: {
                option: function (data, escape) {
                    return "<div class='option " + data.class + "'>" + data.text + "</div>"
                }
            }
        });

        $('#category').change(function () {
            var action = $('#filter_form').attr('action');
            action += $('#category').val() + '/' + $('#per_page').val();
            window.location.href = action;
        });

        $('#per_page').change(function () {
            var action = $('#filter_form').attr('action');
            action += $('#category').val() + '/' + $('#per_page').val()
            window.location.href = action;
        })
    })
</script>

<style>
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
        pointer-events: none;
    }

    .actions_list a.is_visible.disabled{
        color: #9d9d9d;
    }

    .item_image{
        max-width: 100px;
        height: auto;
        border: 1px solid;
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
</style>