<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['kitchen_models_list']?></h2>
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
                <div class="ibox-content">

                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <a  class="btn btn-w-m btn-primary <?php if($modules_sets == 0){echo 'hidden';}?>" href="<?php echo site_url('kitchen_models/add/') ?>" role="button"><?php echo $lang_arr['kitchen_model_add']?></a>
                        </div>
                    </div>

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th><?php echo $lang_arr['icon']?></th>
                            <th><?php echo $lang_arr['name']?></th>
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
                                            <img src="<?php echo $this->config->item('const_path').$item['icon']?>" alt="">
                                            <!--                            <div style="background: url('')"></div>-->
					                    <?php endif;?>
                                    </div>
                                </td>
                                <td>
				                    <?php echo $item['name']?>
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
					                    <?php if($settings['multiple_facades_mode'] == 1):?>
                                            <a href="<?php echo site_url('kitchen_models/prices_edit/' . $item['id']) ?>"><span class="glyphicon glyphicon-ruble"></span></a>
					                    <?php endif;?>
                                        <a href="<?php echo site_url('kitchen_models/edit/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                        <a class="delete_button" href="<?php echo site_url('kitchen_models/remove/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
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



<script>
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
            url: "<?php echo base_url('index.php/kitchen_models/set_active_ajax/')?>" + $(this).attr('data-id') + '/' + val,
            type: 'get'
        }).done(function(msg) {
            console.log(132)
        });

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