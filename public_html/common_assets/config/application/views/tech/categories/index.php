<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['tech_categories_list']?></h2>
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
                    <a class="btn btn-w-m btn-primary" href="<?php echo site_url('tech/categories_add/') ?>" role="button"><?php echo $lang_arr['add_category']?></a>

                    <ul class="cat_list">
		                <?php foreach ($categories as $cat): ?>
                            <li>
                                <div class="list_div">
					                <?php echo $cat['name']?>
                                    <div class="actions_list">
                                        <a class="is_visible <?php if($cat['active'] ==0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
                                        <a href="<?php echo site_url('tech/categories_edit/' . $cat['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
						                <?php if(!isset($cat['children'])):?>
                                            <a class="delete_button" href="<?php echo site_url('tech/categories_remove/'. $cat['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
						                <?php else:?>
                                            <a class="delete_button disabled" href="#"><span class="glyphicon glyphicon-trash"></span></a>
						                <?php endif;?>
                                    </div>
                                </div>
				                <?php if(isset($cat['children'])):?>
                                    <ul>
						                <?php foreach ($cat['children'] as $child): ?>
                                            <li>
                                                <div class="list_div">
									                <?php echo $child['name']?>
                                                    <div class="actions_list">
                                                        <a class="is_visible <?php if($child['active'] ==0) echo 'disabled' ?>"><span class="glyphicon glyphicon-eye-open"></a>
                                                        <a href="<?php echo site_url('tech/categories_edit/' . $child['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                                                        <a class="delete_button" href="<?php echo site_url('tech/categories_remove/'. $child['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
                                                    </div>
                                                </div>
                                            </li>
						                <?php endforeach; ?>
                                    </ul>
				                <?php endif;?>
                            </li>

		                <?php endforeach; ?>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>

<style>

    .cat_list{
        margin-top: 20px;
        padding: 0;
        border-bottom: 1px solid #ddd;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }

    .cat_list li{
        display: block;
        position: relative;
        line-height: 40px;
    }

    .list_div:hover{
        background: #f5f5f5;
    }

    .cat_list > li .list_div{
        padding-left: 15px;
        font-weight: bold;
        position: relative;
    }

    .cat_list > li:after {
        content: "\00A0";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        border-top: 1px solid #ddd;
        z-index: 1;
        pointer-events: none;
    }

    .cat_list li > ul{
        padding: 0;
    }

    .cat_list li > ul > li .list_div{
        padding-left: 45px;
        font-weight: normal;
        position: relative;
    }

    .cat_list li > ul > li:after {
        content: "\00A0";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        border-top: 1px solid #ddd;
        z-index: 1;
        pointer-events: none;
    }

    .actions_list{
        position: absolute;
        right: 20px;
        top:0;
        bottom:0;
    }

    .actions_list a{
        margin-right: 10px;
        margin-top: 12px;
        display: inline-block;
        font-size: 16px;
        color: #1ab394;
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

</style>

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
        })
    })
</script>
