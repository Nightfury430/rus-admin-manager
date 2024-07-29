<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['materials_items_list']?></h2>
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
                    <h5><?php echo $lang_arr['materials_items_list'] ?></h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-w-m btn-primary" href="<?php echo site_url('materials/coupe_materials_items_add/') ?>" role="button"><?php echo $lang_arr['materials_item_add']?></a>

                            <div class="btn-group ">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="true">CSV</button>
                                <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 33px; left: 0px; will-change: top, left;">
                                    <li><a class="dropdown-item" id="import_csv" data-toggle="modal" data-target="#csv_modal" href="#"><?php echo $lang_arr['import_csv']?></a></li>
                                    <li><a class="dropdown-item" id="export_csv" href="#">Экспорт CSV</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/attachments/materials.csv">Скачать образец csv</a></li>
                                </ul>
                            </div>

                            <div class="btn-group ">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="true"><i style="font-size: 19px;" class="fa fa-wrench"></i></button>
                                <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 33px; left: 0px; will-change: top, left;">
                                    <li><a class="dropdown-item" id="fix_db" href="<?php echo site_url('materials/coupe_fix_db/') ?>">Починить базу</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" id="clear_db" href="<?php echo site_url('materials/coupe_clear_db/') ?>"><?php echo $lang_arr['clear_db']?></a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 d-flex align-items-center">
							<?php echo $pagination?>
                        </div>
                        <div class="col-sm-6">
                            <form id="filter_form" method="post" accept-charset="UTF-8" action="<?php echo site_url('materials/coupe_materials_items_index/') ?>">
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
                            <th><?php echo $lang_arr['material']?></th>
                            <th><?php echo $lang_arr['name']?></th>
                            <th><?php echo $lang_arr['code']?></th>
                            <th><?php echo $lang_arr['category']?></th>
                            <th><?php echo $lang_arr['is_visible']?></th>
                            <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                        </tr>
                        </thead>
                        <tbody>

	                    <?php foreach ($items as $item):?>
		                    <?php $params = json_decode($item['params'])->params?>
                            <tr>
                                <td>
                                    <div class="item_image">
					                    <?php if( isset($params->icon) && $params->icon != null): ?>
						                    <?php if (strpos($params->icon, 'common_assets') !== false): ?>
                                                <div style="background-size: cover!important; background: url('<?php echo $params->icon?>')"></div>
						                    <?php else:?>
                                                <div style="background-size: cover!important; background: url('<?php echo $this->config->item('const_path') . $params->icon?>')"></div>
						                    <?php endif;?>
					                    <?php elseif( isset($params->map) && $params->map != null): ?>
						                    <?php if (strpos($params->map, 'common_assets') !== false): ?>
                                                <div style="background-size: cover!important; background: url('<?php echo $params->map?>')"></div>
						                    <?php else:?>
                                                <div style="background-size: cover!important; background: url('<?php echo $this->config->item('const_path') . $params->map?>')"></div>
						                    <?php endif;?>
					                    <?php else:?>
                                            <div style="background: <?php echo $params->color?>"></div>
					                    <?php endif;?>
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
                                    $item['category'] = json_decode($item['category']);

                                    if(is_array($item['category'])){

                                        foreach ($item['category'] as $cat){
                                            $key = array_search($cat, array_column($categories, 'id'));
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
                                            }
                                            echo '<br>';
                                        }

                                    } else {
                                        if($item['category'] == 0){
                                            echo $lang_arr['no'];
                                        } else {
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
                                            }
                                        }
                                    }




				                    ?>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a class="is_visible <?php if($item['active'] == 0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a href="<?php echo site_url('materials/coupe_materials_items_edit/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a class="delete_button" href="<?php echo site_url('materials/coupe_materials_items_remove/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
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

<div class="modal inmodal fade" id="csv_modal" tabindex="-1" role="dialog">
    <form id="csv_import">

        <div class="modal-dialog">
            <div class="modal-content animated ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <i class="fa fa-laptop modal-icon"></i>
                    <h4 class="modal-title"><?php echo $lang_arr['import_csv']?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label><?php echo $lang_arr['choose_file']?></label> <input id="modal_csv_file" name="modal_csv_file" type="file" accept=".csv"  class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                    <button type="submit" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
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

        $('#clear_db').click(function (e) {
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

                send_xhr_get(scope.attr('href'), function (resp) {
                    console.log(resp)
                    location.reload();
                })

            });
        });


        $('#fix_db').click(function (e) {
            e.preventDefault();

            let scope = $(this);

            send_xhr_get(scope.attr('href'), function (resp) {
                console.log(resp)
            })

        });

        $('#export_csv').click(function () {

            window.open($('#ajax_base_url').val() + '/materials/coupe_export_csv/')

        });

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

        $('#csv_import').submit(function (e) {
            e.preventDefault();

            // data:  new FormData(this),
            $.ajax({
                url: $('#ajax_base_url').val() + '/materials/coupe_materials_add_from_csv',
                type: 'post',
                data: new FormData(this),
                contentType: false,
                processData: false
            }).done(function (msg) {
                // console.log(msg);
                location.reload();
            })

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
		width: 50px;
		height: 50px;
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