<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $header ?></h2>
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
                    <h5><?php echo $lang_arr['basic_params']?></h5>
                </div>
                <div class="ibox-content">
                    <form id="sub_form" method="post" accept-charset="UTF-8" action="<?php echo site_url($controller .'/categories_edit/'.$category['id']) ?>">

                        <input id="form_success_url" value="<?php echo site_url($controller . '/categories_index/') ?>" type="hidden">

                        <div class="alert alert-danger error_msg" style="display:none"></div>

                        <div class="form-group">
                            <label for="name"><?php echo $lang_arr['name']?>*</label>
                            <input value="<?php echo $category['name']?>" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                        </div>

	                    <?php if($has_children == 0):?>
                            <div class="form-group" >
                                <label for="parent"><?php echo $lang_arr['parent_category']?></label>
                                <select class="form-control" name="parent" id="parent">
                                    <option  value="0"><?php echo $lang_arr['no']?></option>
				                    <?php foreach ($categories as $cat):?>
					                    <?php if($cat['parent'] == 0 && $category['id'] != $cat['id'] )  : ?>
                                            <option <?php if($category['parent'] == $cat['id']){echo 'selected';}  ?> value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
					                    <?php endif;?>
				                    <?php endforeach;?>
                                </select>
                            </div>
	                    <?php else:?>
                            <div class="form-group" >
                                <label for="parent"><?php echo $lang_arr['parent_category']?></label>
                                <select disabled class="form-control"  id="parent">
                                    <option  value="0"><?php echo $lang_arr['no']?></option>
                                </select>
                                <input type="hidden" name="parent" value="0">
                                <span class="help-block"><?php echo $lang_arr['category_parent_edit_warning']?></span>
                            </div>
	                    <?php endif;?>

                        <div class="form-group">
                            <label for="active"><?php echo $lang_arr['active']?></label>
                            <select class="form-control" name="active" id="active">
                                <option <?php if($category['active'] == 1){echo 'selected';}?> value="1"><?php echo $lang_arr['yes']?></option>
                                <option <?php if($category['active'] == 0){echo 'selected';}?> value="0"><?php echo $lang_arr['no']?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description"><?php echo $lang_arr['category_description']?></label>
                            <div>
                                <textarea class="form-control" rows="5" id="description" name="description"><?php echo $category['description']?></textarea>
                            </div>
                        </div>


                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url( $controller . '/categories_index/') ?>"><?php echo $lang_arr['cancel']?></a>
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>

    document.addEventListener('DOMContentLoaded', function () {
        $('#parent').selectize({
            create: false
        });

        $('#sub_form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type:'POST',
                data: $(this).serialize(),

            }).done(function (msg) {
                console.log(msg);
                let data = JSON.parse(msg);
                console.log(data)
                if($.isEmptyObject(data.error)){
                    $(".error_msg").css('display','none');
                    window.location.href = $('#form_success_url').val();
                }else{
                    $(".error_msg").html(data.error).css('display','block');
                }
            });
        })
    });



</script>