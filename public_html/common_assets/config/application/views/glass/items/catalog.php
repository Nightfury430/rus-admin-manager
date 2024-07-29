<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['glass_items_index_heading']?></h2>
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
                    <h5><?php echo $lang_arr['glass_items_index_heading'] ?></h5>
                </div>
                <div class="ibox-content">

                    <div class="row">
                        <div class="col-sm-6 d-flex align-items-center">
							<?php echo $pagination?>
                        </div>
                        <div class="col-sm-6">
                            <form id="filter_form" method="post" accept-charset="UTF-8" action="<?php echo site_url('glass/items_catalog_index/') ?>">
                                <input type="hidden" name="filter" value="1">
                                <div class="row">
                                    <div class="col-6">
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
                                    <div class="col-6">
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
                            <th><?php echo $lang_arr['category']?></th>
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
				                    <?php
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


				                    ?>
                                </td>

                                <td>
                                    <a title="<?php echo $lang_arr['add_model_to_your_catalog']?>" class="add_item" href="<?php echo site_url('glass/add_item_from_catalog/' . $item['id']) ?>"><span class="glyphicon glyphicon-copy"></span></a>
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




<div class="modal_wrapper">
    <div class="modal_content">
        <span class="modal_close glyphicon glyphicon-remove"></span>
        <div class="col-xs-12">

            <div class="form-group">
                <label for="selected_material_facade"><?php echo $lang_arr['choose_category']?></label>
                <select class="form-control" >
                    <option value="0">--- <?php echo $lang_arr['choose_category']?> ---</option>
                    <?php foreach ($acc_categories as $val): ?>
                        <option  <?php if($val['parent'] == null || $val['parent'] == 0) echo 'style="font-weight:bold;"' ?> value="<?php echo $val['id']?>"><?php echo $val['name']?></option>
                    <?php endforeach;?>
                </select>


                <div class="copy_confirm btn btn-success">Добавить</div>
            </div>



        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('.delete_button').click(function (e) {
            e.preventDefault();
            if (confirm('<?php echo $lang_arr['delete_confirm_message']?>')) {
                window.location.href = $(this).attr('href');
            } else {

            }
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



        $('.modal_content .modal_close').click(function () {
            $('.modal_wrapper').fadeOut();
            $('.copy_confirm').attr('data-href', '');
            $('.modal_wrapper').find('select').val(0)
        });

        $('.add_item').click(function (e) {
            e.preventDefault();
            $('.modal_wrapper').fadeIn();
            $('.copy_confirm').attr('data-href', $(this).attr('href'));

        });

        $('.copy_confirm').click(function () {

            if($('.modal_wrapper').find('select').val() != 0){
                    $.ajax({
                        url: $(this).attr('data-href') + '/' + $('.modal_wrapper').find('select').val(),
                        type: 'post',
                        data: {materials: $('#materials').val()}
                    }).done(function (msg) {
                        console.log(msg)

                        $('.modal_wrapper').fadeOut();
                        $('.copy_confirm').attr('data-href', '');
                        $('.modal_wrapper').find('select').val(0)
                        alert('Материал успешно добавлен')
                    });
            } else {
                alert('<?php echo $lang_arr['choose_category']?>')
            }


        })
    })
</script>

<style>

    .modal_wrapper{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 220px;
        right: 0;
        background: rgba(0, 0, 0, 0.36);
        z-index: 10;
        display: none;
    }

    .modal_content{
        position: absolute;
        background: #ffffff;
        width: 450px;
        left: 0;
        right: 0;
        margin: 0 auto;
        top: 200px;
        padding: 50px;
    }

    .modal_content .modal_close{
        position: absolute;
        right: 5px;
        top: 5px;
        cursor: pointer;
        font-size: 20px;
    }

    .copy_confirm{
        text-align: center;
        margin-top: 10px;
    }

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