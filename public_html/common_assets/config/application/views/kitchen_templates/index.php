<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo $lang_arr['projects_list'] ?></h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-12">
                            <a class="btn btn-w-m btn-primary mb-3" href="<?php echo site_url('templates/add/') ?>" role="button"><?php echo $lang_arr['add_project']?></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th><?php echo $lang_arr['icon']?></th>
                                    <th><?php echo $lang_arr['name']?></th>
                                    <th><?php echo $lang_arr['is_visible']?></th>
                                    <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                                </tr>
                                </thead>
                                <tbody>
		                        <?php foreach ($templates as $item):?>
                                    <tr>
                                        <td>
                                            <div class="item_image">
						                        <?php if($item['icon'] != null): ?>
                                                    <img src="<?php echo $this->config->item('const_path').$item['icon']?>?<?php echo md5(date('m-d-Y-His A e'));?>" alt="">
                                                    <!--                            <div style="background: url('')"></div>-->
						                        <?php endif;?>
                                            </div>
                                        </td>
                                        <td>
					                        <?php echo $item['name']?>
                                        </td>

                                        <td>
                                            <div class="actions_list">
                                                <a data-id="<?php echo $item['id']?>" class="is_visible <?php if($item['active'] ==0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="actions_list">
                                                <a href="<?php echo site_url('templates/edit/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>

                                                <a title="Скачать" target="_blank" download="<?php echo $this->config->item('const_path').'templates/' . $item['id'].'/' .$item['id'] . '_project.dbs'?>" href="<?php echo $this->config->item('const_path').'templates/' . $item['id'].'/' .$item['id'] . '_project.dbs'?>"><span class="glyphicon glyphicon-link"></span></a>
                                                <a title="Открыть в новом окне" target="_blank" href="<?php echo $this->config->item('const_path').'?file_url='?><?php echo $this->config->item('const_path').'templates/' . $item['id'].'/' .$item['id'] . '_project.dbs'?>"><span class="glyphicon glyphicon-link"></span></a>
                                                <a class="delete_button" href="<?php echo site_url('templates/remove/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
                                            </div>
                                        </td>
                                    </tr>

		                        <?php endforeach; ?>
                                </tbody>
                            </table>
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
            Swal.fire({
                title: "<?php echo $lang_arr['are_u_sure']?>",
                text: $('#delete_confirm_message').html(),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: false,
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                },
            }).then((result) => {
                if(result.value){
                    window.location.href = scope.attr('href');
                }
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
                url: "<?php echo base_url('index.php/templates/set_active_ajax/')?>" + $(this).attr('data-id') + '/' + val,
                type: 'get'
            }).done(function(msg) {
                console.log(132)
            });
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
        width: 100px;
        /*height: 50px;*/
        border: 1px solid;
    }

    .item_image > img{
        width: 100%;
        height: auto;
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

<script src="/common_assets/theme/js/plugins/clipboard/clipboard.min.js"></script>