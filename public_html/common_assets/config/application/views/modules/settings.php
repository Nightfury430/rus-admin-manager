<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?php echo $lang_arr['modules_settings_heading']?></h2>
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


<style>

</style>

<div class="wrapper wrapper-content  animated fadeInRight">

<!--	<form ref="sub_form" id="sub_form" @submit="check_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="--><?php //echo site_url( 'constructor/save_data/' ) ?><!--">-->

		<div class="row">
			<div class="col-lg-12">

				<div class="ibox ">

					<div class="ibox-content">

						<div class="sk-spinner sk-spinner-wave">
							<div class="sk-rect1"></div>
							<div class="sk-rect2"></div>
							<div class="sk-rect3"></div>
							<div class="sk-rect4"></div>
							<div class="sk-rect5"></div>
						</div>

                        <div class="row form-group">
                        <div class="col-12">
                            <button id="generate_modules_names" class="btn btn-outline btn-primary" type="submit"><?php echo $lang_arr['add_modules_name']?></button>
                        </div>
                        </div>

                        <div class="hr-line-dashed"></div>


<!--                        <div class="form-group  row">-->
<!--                            <label class="col-sm-2 col-form-label">-->
<!--                                Использовать набор модулей вместо стандартного набора-->
<!--                            </label>-->
<!--                            <div class="col-sm-5">-->
<!--                                <select class="form-control">-->
<!--                                    <option value="0">Нет</option>-->
<!--		                            --><?php //foreach ($modules_sets as $set):?>
<!--                                        <option value="--><?php //echo $set['id']?><!--">--><?php //echo $set['name'] ?><!--</option>-->
<!--		                            --><?php //endforeach;?>
<!--                                </select>-->
<!--                            </div>-->
<!--                            <div class="col-sm-5">-->
<!--                                <button id="save_use_module_set" class="btn  btn-primary" type="submit">Применить</button>-->
<!--                            </div>-->
<!--                        </div>-->



					</div>
				</div>

			</div>
		</div>

<!--	</form>-->
</div>







<script>

    $(document).ready(function () {


        $('#generate_modules_names').click(function (e) {
            e.preventDefault();

            swal({
                title: "<?php echo $lang_arr['are_u_sure']?>",
                text: '<?php echo $lang_arr['modules_generate_names_warning']?>',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: true
            }, function () {

                $('.ibox-content').addClass('sk-loading');

                $.ajax({
                    url : "<?php echo site_url('modules/generate_names/') ?>",
                    type : 'POST',
                    data : {'autogen': true},
                }).done(function (msg) {
                    $('.ibox-content').removeClass('sk-loading');

                    toastr.success('<?php echo $lang_arr['success']?>')
                });
            });

        })

    })


</script>