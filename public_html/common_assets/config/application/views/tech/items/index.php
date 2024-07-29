<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['tech_items_list']?></h2>
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
                    <h5><?php echo $lang_arr['tech_items_list'] ?></h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-w-m btn-default" href="<?php echo site_url('tech/items_add/') ?>" role="button"><?php echo $lang_arr['add_model']?></a>
                            <a class="btn btn-w-m btn-success" href="<?php echo site_url('tech/items_catalog_index/') ?>" role="button"><?php echo $lang_arr['facades_items_add_from_catalog']?></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 d-flex align-items-center">
							<?php echo $pagination?>
                        </div>
                        <div class="col-sm-6">
                            <form id="filter_form" method="post" accept-charset="UTF-8" action="<?php echo site_url('tech/items_index/') ?>">
                                <input type="hidden" name="filter" value="1">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="category"><?php echo $lang_arr['category']?></label>
                                            <select class="form-control" name="category" id="category">
                                                <option data-data='{"class": "top_cat"}' value="0"><?php echo $lang_arr['all']?></option>
												<?php foreach ($materials_categories as $cat):?>
													<?php if($cat['parent'] == 0): ?>
                                                        <option <?php if($this->uri->segment(3) == $cat['id']) echo 'selected';?> data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
														<?php if (isset($cat['children'])):?>
															<?php foreach ($cat['children'] as $child):?>
                                                                <option <?php if($this->uri->segment(3) == $child['id']) echo 'selected';?> data-data='{"class": "sub_cat"}' value="<?php echo $child['id']?>"><?php echo $child['name']?></option>
															<?php endforeach;?>
														<?php endif;?>
													<?php endif;?>
												<?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="per_page"><?php echo $lang_arr['page_items_count']?></label>
                                            <select class="form-control" name="per_page" id="per_page">
                                                <option <?php if($this->uri->segment(4) == 10) echo 'selected';?> value="10">10</option>
                                                <option <?php if($this->uri->segment(4) == 20) echo 'selected';?> value="20">20</option>
                                                <option <?php if($this->uri->segment(4) == 50) echo 'selected';?> value="50">50</option>
                                                <option <?php if($this->uri->segment(4) == 100) echo 'selected';?> value="100">100</option>
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
                            <th><?php echo $lang_arr['icon']?></th>
                            <th><?php echo $lang_arr['name']?></th>
                            <th><?php echo $lang_arr['code']?></th>
                            <th><?php echo $lang_arr['category']?></th>
                            <th><?php echo $lang_arr['order']?></th>
                            <th><?php echo $lang_arr['is_visible']?></th>
                            <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                        </tr>
                        </thead>
                        <tbody>

	                    <?php foreach ($items as $item):?>
                            <tr>
                                <td>
                                    <div class="item_image">
					                    <?php if($item['icon'] != null): ?>

						                    <?php if (strpos($item['icon'], 'common_assets') !== false): ?>
                                                <img class="img-fluid" src="<?php echo $item['icon'] ?>" alt="">
						                    <?php else: ?>
                                                <img class="img-fluid" src="<?php echo $this->config->item('const_path') . $item['icon']?>" alt="">
						                    <?php endif; ?>
					                    <?php endif; ?>


                                    </div>
                                </td>
                                <td>
				                    <?php echo $item['name']?>
                                </td>
                                <td>
				                    <?php echo $item['code']?>
                                </td>
                                <td>
				                    <?php

				                    $key = array_search($item['category'], array_column($categories, 'id'));

				                    if($key === null){
					                    echo $lang_arr['no'];
				                    } else {
					                    $cat = $categories[$key]['parent'];
					                    if($cat == 0){
						                    echo $categories[$key]['name'];
					                    } else {
						                    $key2 = array_search($cat, array_column($materials_categories, 'id'));
						                    echo $materials_categories[$key2]['name'];
						                    echo '/';
						                    echo $categories[$key]['name'];
					                    }
//                                print_r();

				                    }
				                    ?>
                                </td>
                                <td>
                                    <?php
                                    if(isset($item['order'])){
                                        echo $item['order'];
                                    } else {
                                        echo 100000;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a data-id="<?php echo $item['id']?>" class="is_visible <?php if($item['active'] ==0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a href="<?php echo site_url('tech/items_add/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a class="delete_button" href="<?php echo site_url('tech/items_remove/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
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

        $('.is_visible').click(function (e) {
            e.preventDefault();

            let val = 0;


            if($(this).hasClass('disabled')){
                $(this).removeClass('disabled')
                val = 1;
            } else {
                val = 0;
                $(this).addClass('disabled')
            }

            $.ajax({
                url: "<?php echo base_url('index.php/tech/set_active_ajax/')?>" + $(this).attr('data-id') + '/' + val,
                type: 'get'
            }).done(function(msg) {
                console.log(132)
            });

        })

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
    }
    .actions_list a.is_visible:hover{
        color: #0e7b0d;
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