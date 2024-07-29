<div class="row wrapper border-bottom white-bg page-heading" >
	<div class="col-lg-10">
		<h2><?php echo $lang_arr['interior_item_add']?></h2>
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

<form @submit="submit" id="sub_form" action="<?php echo site_url('interior/items_add_ajax/') ?><?php if(isset($id)) echo $id?>">
	<input id="form_success_url" value="<?php echo site_url('interior/items_index/') ?>" type="hidden">
	<?php if (isset($id)):?>
        <input id="item_id" value="<?php echo $id?>" type="hidden">
	<?php endif;?>

	<div v-cloak class="wrapper wrapper-content  animated fadeInRight">

		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-danger error_msg" style="display:none"></div>
			</div>
		</div>

		<div  class="row">
			<div class="col-lg-12">
				<div class="tabs-container">
					<ul class="nav nav-tabs">
						<li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
						<li><a @click="resize_viewport" class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params']?></a></li>
						<li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['sizes']?></a></li>
					</ul>
					<div class="tab-content">
						<div id="tab-1" class="tab-pane active">
							<div class="panel-body">

								<fieldset>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label"><?php echo $lang_arr['category']?></label>
										<div class="col-sm-10">

											<div v-if="all_categories[item.category].parent == 0">
												<button class="btn btn-outline btn-default" @click="show_modal(); add_modal = true" type="button"><b>{{all_categories[item.category].name}}</b></button>
											</div>
											<div v-else>
												<button class="btn btn-outline btn-default" @click="show_modal(); add_modal = true" type="button"><b>{{computed_parent_name}}</b> <i class="fa fa-angle-right"></i> {{all_categories[item.category].name}}</button>
											</div>

										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 col-form-label"><?php echo $lang_arr['active']?></label>
										<div class="col-sm-10">
											<label class="switch">
												<input v-bind:true-value="1" v-bind:false-value="0" v-model="item.active" type="checkbox">
												<span class="slider round"></span>
											</label>
										</div>
									</div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order']?></label>
                                        <div class="col-sm-10">
                                            <input type="number" v-model="item.order" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label @change="params_change()" class="col-sm-2 col-form-label"><?php echo $lang_arr['drag_type']?></label>
                                        <div class="col-sm-10">
                                            <select v-model="item.cabinet_group" class="form-control">
                                                <option value="top"><?php echo $lang_arr['drag_as_top']?></option>
                                                <option value="bottom"><?php echo $lang_arr['drag_as_bottom']?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['snap_type']?></label>
                                        <div class="col-sm-10">
                                            <select @change="params_change()" v-model="item.drag_mode" class="form-control">
                                                <option selected value="common"><?php echo $lang_arr['snap_walls']?></option>
                                                <option value="surface"><?php echo $lang_arr['snap_all']?></option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['wallpanel']?></label>
                                        <div class="col-sm-10">
                                            <label @change="params_change()" class="switch">
                                                <input @change="params_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.wall_panel" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['show_sizes']?></label>
                                        <div class="col-sm-10">
                                            <label @change="params_change()" class="switch">
                                                <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.sizes_available" type="checkbox">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>


									<div class="form-group row">
										<label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon']?></label>
										<div class="col-sm-5">

                                            <div class="icon_block">
                                                <img @click="$refs.icon_file.$el.click()" style="max-width: 78px" :src="get_icon_src(icon_file)" alt="">
                                                <i @click="$refs.icon_file.$el.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                <i v-if="item.icon != '' || icon_file != ''" @click="icon_file = ''" class="fa fa-trash delete_file"></i>
                                            </div>

											<div class="hidden">
												<file-input ref="icon_file" accept="image/jpeg,image/png,image/gif" v-model="icon_file"></file-input>
											</div>

										</div>
										<div class="col-sm-5">

										</div>
									</div>

								</fieldset>

							</div>
						</div>
						<div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <div class="row form-group">
                                    <div class="col-4">
                                        <div class="row ">
                                            <div class="col-12 form-group">
                                                <?php echo $lang_arr['model_file']?>
                                                <input type="file" accept=".fbx" class="form-control" @change="process_model($event,0)" >
                                            </div>
                                            <div class="col-4 form-group">
                                                <label><?php echo $lang_arr['width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input @input="size_change()" class="form-control" v-model="item.variants[0].width" type="number">
                                            </div>
                                            <div class="col-4 form-group">
                                                <label><?php echo $lang_arr['height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input @input="size_change()" class="form-control" v-model="item.variants[0].height" type="number">
                                            </div>
                                            <div class="col-4 form-group">
                                                <label><?php echo $lang_arr['depth']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input @input="size_change()" class="form-control" v-model="item.variants[0].depth" type="number">
                                            </div>
                                            <div class="col-12 form-group">
                                                <label ><?php echo $lang_arr['color']?></label>
                                                <input type="text" class="form-control" name="color" id="color" value="#ffffff">
                                            </div>
                                            <div class="col-6 form-group">
                                                <label><?php echo $lang_arr['roughness']?></label>
                                                <input @input="material_change()" type="number"  min="0" max="1" step="0.01" class="form-control" v-model.number="item.material.params.roughness"  placeholder="0.15">
                                            </div>
                                            <div class="col-6 form-group">
                                                <label><?php echo $lang_arr['metalness']?></label>
                                                <input @input="material_change()" type="number" min="0" max="1" step="0.01" class="form-control"  v-model.number="item.material.params.metalness" placeholder="0.15">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 form-group">
                                                <label style="display: block"><?php echo $lang_arr['texture_file']?></label>
                                                <div class="icon_block">
                                                    <img @click="$refs.map_file.click()" style="max-width: 78px" :src="get_map_src(map_file)" alt="">
                                                    <i @click="$refs.map_file.click()" class="fa fa-folder-open open_file" aria-hidden="true"></i>
                                                    <i v-if="item.material.params.map != '' || map_file != ''" @click="remove_map_file()" class="fa fa-trash delete_file"></i>
                                                </div>

                                                <div class="hidden">
                                                    <input ref="map_file" accept="image/jpeg,image/png,image/gif" @change="process_map_file($event)" type="file">
<!--                                                    <file-input ref="map_file" accept="image/jpeg,image/png,image/gif" v-model="map_file"></file-input>-->
                                                </div>
                                            </div>
                                        </div>

                                        <div v-show="item.material.params.map != '' || map_file != ''" class="texture_params">

                                            <div class="row">
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['stretch_width']?></label>
                                                    <div>
                                                        <label class="switch">
                                                            <input @change="material_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.material.add_params.stretch_width" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['stretch_height']?></label>
                                                    <div>
                                                        <label class="switch">
                                                            <input @change="material_change()" v-bind:true-value="1" v-bind:false-value="0" v-model="item.material.add_params.stretch_height" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['texture_real_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                    <input @input="material_change()" v-model="item.material.add_params.real_width" :disabled="item.material.add_params.stretch_width == 1" type="number" class="form-control" placeholder="256">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label><?php echo $lang_arr['texture_real_heght']?> (<?php echo $lang_arr['units']?>)</label>
                                                    <input @input="material_change()" v-model="item.material.add_params.real_width" :disabled="item.material.add_params.stretch_height == 1" type="number" class="form-control" placeholder="256">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 form-group">
                                                    <label ><?php echo $lang_arr['wrapping_type']?></label>
                                                    <select @change="material_change()" v-model="item.material.add_params.wrapping" class="form-control" >
                                                        <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror']?></option>
                                                        <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat']?></option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div id="bplanner_app">
                                        <div id="viewport">

                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
						<div id="tab-3" class="tab-pane">
                            <div class="panel-body models_list">

                                <draggable class="" v-model="item.variants" v-bind="dragOptions" handle=".draggable_panel" group="parent" :move="on_drag" @start="drag = true" @end="drag = false">
                                    <div v-for="(variant,index) in item.variants" :key="variant.id" class="d-flex flex-row flex-wrap form-group draggable_panel pr-5">
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['name']?></label>
                                            <input class="form-control" v-model="variant.name" type="text">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['code']?></label>
                                            <input class="form-control" v-model="variant.code" type="text">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['price']?></label>
                                            <input class="form-control" v-model="variant.price" type="text">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['width']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input class="form-control" v-model="variant.width" type="number">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['height']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input class="form-control" v-model="variant.height" type="number">
                                        </div>
                                        <div class="col-4 py-2">
                                            <label><?php echo $lang_arr['depth']?> (<?php echo $lang_arr['units']?>)</label>
                                            <input class="form-control" v-model="variant.depth" type="number">
                                        </div>
                                        <div v-if="index > 0" class="col-12 py-2">
                                            <input type="file" accept=".fbx" @change="process_file($event,index)" >
                                        </div>

                                        <button v-if="index > 0" @click="remove_variant(index)" style="position: absolute; right: 10px; top:10px; padding: 5px 5px 5px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>

                                    </div>
                                </draggable>


                                <div class="row form-group">
                                    <div class="col-12">
                                        <button @click="add_variant()" type="button" class="btn btn-w-m btn-primary btn-outline"><?php echo $lang_arr['add']?></button>
                                    </div>
                                </div>

                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-lg-12">
				<div class="ibox ">
					<div class="ibox-content">

						<div class="hr-line-dashed"></div>

						<div class="form-group row">
							<div class="col-sm-4 col-sm-offset-2">
								<a class="btn btn-white btn-sm" href="<?php echo site_url('facades/items_index/') ?>"><?php echo $lang_arr['cancel']?></a>
								<button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>



	<div v-bind:style="{ display: add_modal == 'true' ? 'none' : 'block'}" v-show="add_modal == true" v-bind:class="{ show: add_modal == true }" class="modal inmodal" id="facade_cat" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content animated ">
				<div class="modal-header">
					<button @click="hide_modal(); add_modal = false" type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title"><?php echo $lang_arr['choose_category']?></h4>
				</div>
				<div class="modal-body">
					<div class="ibox">
						<div class="ibox-content no-padding">
							<ul class="category_select">
								<li v-for="(category,index) in categories">
									<button @click="set_category(index)" type="button" class="btn btn-default btn-block">
										{{category.name}}
									</button>
									<ul v-if="category.children">
										<li v-for="(subcat,ind) in category.children">
											<button @click="set_category(ind)" type="button" class="btn btn-default btn-block">
												{{subcat.name}}
											</button>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" @click="hide_modal(); add_modal = false" class="btn btn-white"><?php echo $lang_arr['cancel']?></button>
				</div>
			</div>
		</div>
	</div>



</form>




<div class="three_modal_wrapper">
	<div class="three_modal">
		<span class="close_three_modal glyphicon glyphicon-remove"></span>
		<div id="three_viewport">
		</div>
	</div>
</div>

<link rel="stylesheet" href="/common_assets/libs/vue/vue_select/vue-select.css">
<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">


<style>

    #viewport{
        max-width: 100%;
        position: relative;
        height: 100%;
        max-height: 500px;

    }

    label{
        margin-bottom: 2px;
    }

    .sp-replacer {
        margin: 0;
        overflow: hidden;
        cursor: pointer;
        padding: 4px 15px 4px 4px;
        display: block;
        zoom: 1;
        border: solid 1px #e5e6e7;
        background: #fff;
        color: #333;
        vertical-align: middle;
        position: relative;
    }

    .sp-replacer:hover, .sp-replacer.sp-active {
        border-color: #e5e6e7;
        color: #111;
    }

    .sp-preview {
        position: relative;
        width: 100%;
        height: 25px;
        border: solid 1px #222;
        z-index: 0;
    }

    .sp-dd {
        padding: 2px 0;
        height: 16px;
        line-height: 16px;
        font-size: 10px;
        position: absolute;
        right: 2px;
        top: 7px;
    }

	.cp{
		cursor: pointer;
	}

	.size_row{
		background: rgba(243, 243, 243, 0.5);
		padding-top: 15px;
		padding-bottom: 14px;
		margin-bottom: 10px;
	}

	.size_act_btn{
		margin-top: 30px;
	}

	.size_divider{
		margin-top: 10px;
	}

	.category_select{
		list-style: none;
		margin: 0;
		padding: 0;
		text-align: left;
	}

	.category_select button{
		text-align: left;
	}

	.category_select ul{
		list-style: none;
		margin: 0;
		padding: 0 0 0 30px;
	}



	.panel-body .tabs-container{
		padding-bottom: 25px;
	}


	.three_modal_wrapper{
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(31, 31, 31, 0.81);
		z-index: 9999;
		display: none;
	}

	.three_modal{
		position: absolute;
		top: 20px;
		left: 20px;
		right: 20px;
		bottom: 20px;
		background: #ffffff;
	}

	.close_three_modal{
		position: absolute;
		top:10px;
		right:10px;
		cursor: pointer;
	}

	#three_viewport{
		width: 100%;
		height: 100%;
	}

	#three_viewport_controls{
		position: absolute;
		background: #333333;
		left: 0;
		top:0;
		color: #fff;
		padding: 5px;
	}

	#three_viewport_controls button, #three_viewport_controls input{
		color: #000;
	}

	#three_viewport_controls span{
		display: inline-block;
		width: 110px;
	}

	#three_viewport_controls input{
		display: inline-block;
		width: 80px;
		margin: 5px;
		padding: 0 5px;
	}

	#three_viewport_controls button{
		display: inline-block;
		width: 60px;
		margin: 5px;
	}




</style>
<style>

	.icon_block{
		display: inline-block;
		position: relative;
	}
	.icon_block img{
		cursor: pointer;
	}

	.icon_block .open_file{
		cursor: pointer;
		position: absolute;
		right: -35px;
		top: 0;
		font-size: 25px;
		color: #1c84c6;
	}

	.icon_block .delete_file{
		bottom: 0;
		cursor: pointer;
		position: absolute;
		right: -30px;
		font-size: 25px;
		color: #ed5565;
	}

	.modal_new{
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		z-index: 2050;
		overflow: hidden;
		outline: 0;
		background: rgba(0, 0, 0, 0.35);
	}

	.facades_panel_body{
		min-height: 500px;
	}


	.border_row{
		border: 1px solid #cacaca;
		padding: 10px 0 0 0;
	}

	.tabs-inner .active{

	}

	.file-select > .select-button {
		padding: 1rem;

		color: white;
		background-color: #2EA169;

		border-radius: .3rem;

		text-align: center;
		font-weight: bold;
	}

	.file-select > input[type="file"] {
		display: none;
	}

	.mat_table td{
		cursor: pointer;
	}
</style>




<?php include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php';?>
<script>
    console.log(view_mode)
    view_mode = true;
</script>

<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>


<!--<script src="/common_assets/libs/three106.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/libs/obj_export.js" type="text/javascript"></script>-->

<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>



<script src="/common_assets/admin_js/vue/kitchen/3d_model.js"></script>

<!--<script src="/common_assets/js/v4/functions.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/globals.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Parts.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Facade_new.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/Model_cache.js" type="text/javascript"></script>-->
<!--<script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>-->


<!--<script src="/common_assets/admin_js/production/facades/facades_model.js?--><?php //echo md5(date('m-d-Y-His A e'));?><!--"></script>-->

