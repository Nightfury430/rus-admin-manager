<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo $lang_arr['modules_templates_heading'] ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <a class="btn btn-w-m btn-primary" href="<?php echo site_url('modules_templates/add/') ?>" role="button"><?php echo $lang_arr['add_module_template']?></a>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-sm-6 d-flex align-items-center">
							<?php echo $pagination?>
                        </div>
                        <div class="col-sm-6">
                            <form id="filter_form" method="post" accept-charset="UTF-8" action="<?php echo site_url('modules_templates/index/') ?>">
                                <input type="hidden" name="filter" value="1">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label for="category"><?php echo $lang_arr['category']?></label>
                                            <select class="form-control" name="category" id="category">
                                                    <option <?php if($this->uri->segment(3) == 0) echo 'selected';?> value="0"><?php echo $lang_arr['all']?></option>
                                                    <option <?php if($this->uri->segment(3) == 1) echo 'selected';?> value="1"><?php echo $lang_arr['kitchen_bottom_modules']?></option>
                                                    <option <?php if($this->uri->segment(3) == 2) echo 'selected';?> value="2"><?php echo $lang_arr['kitchen_top_modules']?></option>
                                                    <option <?php if($this->uri->segment(3) == 3) echo 'selected';?> value="3"><?php echo $lang_arr['kitchen_penals_modules']?></option>
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
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-xs-2"><?php echo $lang_arr['icon']?></th>
                            <th><?php echo $lang_arr['name']?></th>
                            <th class="col-xs-2"><?php echo $lang_arr['order']?></th>
                            <th class="col-xs-2"><?php echo $lang_arr['category']?></th>
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
                                                <img class="img-fluid" src="<?php echo $item['icon'] ?>?<?php echo md5(date('m-d-Y-His A e'));?>" alt="">
						                    <?php else: ?>
                                                <img class="img-fluid" src="<?php echo $this->config->item('const_path') . $item['icon'] ?>?<?php echo md5(date('m-d-Y-His A e'));?>" alt="">
						                    <?php endif; ?>

					                    <?php endif; ?>



                                    </div>
                                </td>
                                <td>
				                    <?php echo $item['name']?>
                                </td>
                                <td>
				                    <?php echo $item['order']?>
                                </td>
                                <td>
				                    <?php if($item['category'] == 1) echo $lang_arr['kitchen_bottom_modules']?>
				                    <?php if($item['category'] == 2) echo $lang_arr['kitchen_top_modules']?>
				                    <?php if($item['category'] == 3) echo $lang_arr['kitchen_penals_modules']?>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a href="<?php echo site_url('modules_templates/edit/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a class="delete_button" href="<?php echo site_url('modules_templates/remove/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
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