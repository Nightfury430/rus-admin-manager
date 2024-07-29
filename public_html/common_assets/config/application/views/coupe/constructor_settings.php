<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?php echo $lang_arr['constructor_settings_heading']?></h2>
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

	<form ref="sub_form" id="sub_form" @submit="check_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url( 'constructor/save_data_coupe/' ) ?>">

		<div class="row">
			<div class="col-lg-12">


				<div class="ibox ">
					<div class="ibox-title">
						<h5><?php echo $lang_arr['reflection'] ?></h5>
					</div>
					<div class="ibox-content">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Модификатор цены</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" step="0.01" v-model="settings.price_modificator">

                            </div>
                        </div>

						<div class="form-group row">
							<label class="col-sm-4 col-form-label"><?php echo $lang_arr['reflection_image_label']?></label>
							<div class="col-sm-8">
								<label class="switch">
									<input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.use_custom_reflection" type="checkbox">
									<span class="slider round"></span>
								</label>
							</div>
						</div>

						<div v-show="settings.use_custom_reflection == 1" class="form-group  row">
							<label class="col-sm-4 col-form-label"><?php echo $lang_arr['reflection_image'] ?></label>
							<div class="col-sm-8">
								<div class="row">
									<div class="col-sm-5">
										<input ref="reflection_image" name="reflection_image" id="reflection_image" type="file" accept="image/jpeg,image/png" @change="reflection_image_preview"/>
									</div>
									<div class="col-sm-5">
										<div v-if="settings.custom_reflection_image != null">
											<img id="logo_preview" style="max-width: 125px; height: auto"  :src="settings.custom_reflection_image"/>
										</div>
									</div>
								</div>
							</div>
						</div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Отображать стоимость</label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input  v-bind:true-value="1" v-bind:false-value="0" v-model="settings.price_available" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Шаг размеров</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" step="1" v-model="settings.size_step">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><?php echo $lang_arr['coupe_fixed_back_wall']?></label>
                            <div class="col-sm-8">
                                <label class="switch">
                                    <input v-bind:true-value="1" v-bind:false-value="0" v-model="settings.fixed_back_wall" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>


					</div>
				</div>



				<div class="ibox ">
					<div class="ibox-content">

						<div class="hr-line-dashed"></div>

						<div class="form-group row">
							<div class="col-sm-4 col-sm-offset-2">
								<button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
							</div>
						</div>
					</div>
				</div>



			</div>
		</div>

	</form>
</div>





<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/admin_js/vue/coupe/constructor_settings.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>

<script>


</script>