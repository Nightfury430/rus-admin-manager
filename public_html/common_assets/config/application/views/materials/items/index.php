<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>
            <?php if(isset($common) && $common == 1): ?>
                ДЕКОРЫ ОБЩАЯ БАЗА
            <?php else:?>
                <?php echo $lang_arr['materials_items_list']?>
            <?php endif;?>

        </h2>
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


<?php



$add_path = 'materials/items_add/';
if(isset($common) && $common == 1) $add_path = 'materials/items_add_common/';

$add_path_multiple = 'materials/items_add_multiple/';
if(isset($common) && $common == 1) $add_path_multiple = 'materials/items_add_multiple_common/';

$edit_path = 'materials/items_edit/';
if(isset($common) && $common == 1) $edit_path = 'materials/items_edit_common/';

$index_path = 'materials/items_index/';
if(isset($common) && $common == 1) $index_path = 'materials/items_index_common/';

$remove_path = 'materials/items_remove/';
if(isset($common) && $common == 1) $remove_path = 'materials/items_remove_common/';
?>

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
                            <a class="btn btn-w-m btn-primary" href="<?php echo site_url($add_path) ?>" role="button"><?php echo $lang_arr['materials_item_add']?></a>
                            <a class="btn btn-w-m btn-success" href="<?php echo site_url($add_path_multiple) ?>" role="button"><?php echo $lang_arr['materials_item_add_multiple']?></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 d-flex align-items-center">
							<?php echo $pagination?>
                        </div>
                        <div class="col-sm-6">
                            <form id="filter_form" method="post" accept-charset="UTF-8" action="<?php echo site_url($index_path) ?>">
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



					                    <?php if($item['map'] != null): ?>

						                    <?php if (strpos($item['map'], 'common_assets') !== false): ?>
                                                <div style="background-size: cover!important; background: url('<?php echo $item['map']?>')"></div>
						                    <?php else:?>
                                                <div style="background-size: cover!important; background: url('<?php echo $this->config->item('const_path') . $item['map']?>')"></div>
						                    <?php endif;?>

					                    <?php else:?>
                                            <div style="background: <?php echo $item['color']?>"></div>
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
				                    $key = array_search($item['category'], array_column($categories, 'id'));
				                    if($key == null){
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
                                    <?php echo $item['order']?>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a data-id="<?php echo $item['id']?>" class="is_visible <?php if($item['active'] ==0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a href="<?php echo site_url($edit_path . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a class="delete_button" href="<?php echo site_url($remove_path . $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
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

        <?php if (!isset($common)):?>

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
                url: "<?php echo base_url('index.php/materials/set_active_ajax/')?>" + $(this).attr('data-id') + '/' + val,
                type: 'get'
            }).done(function(msg) {
                console.log(132)
            });

        })

        <?php endif;?>

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