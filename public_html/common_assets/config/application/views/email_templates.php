<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?php echo $lang_arr['order_templates_heading']?> <a target="_blank" href="https://help.planplace.online/menuap-shablony-pisem"><span class="fa fa-question-circle"></span></a></h2>
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
        <h2></h2>
	</div>
</div>






<form id="sub_form" class="" enctype="multipart/form-data" method="post" action="<?php echo site_url('email/update_settings/') ?>" accept-charset="UTF-8">

	<div v-cloak class="wrapper wrapper-content  animated fadeInRight">

        <div v-if="errors.length" class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger error_msg">
                    <ul class="mb-0">
                        <li v-for="error in errors">{{error}}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
			<div class="col-lg-12">
				<div class="tabs-container">
					<ul class="nav nav-tabs">
						<li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['order_email_template']?></a></li>
						<li><a class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['order_email_template_client']?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['order_form_fields']?></a></li>

                    </ul>
					<div class="tab-content">

						<div id="tab-1" class="tab-pane active">
							<div class="panel-body">

                                <div class="form-group row">
                                    <div class="col-sm-1">
                                        <label @click="check_custom_form()"  class="switch">
                                            <input type="checkbox" v-bind:true-value="1" v-bind:false-value="0" v-model="use_email_template" v-bind:disabled="use_custom_form == 1">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <label class="col-sm-8 col-form-label"><?php echo $lang_arr['order_email_use_template']?></label>
                                    <div class="col-sm-3">
                                        <button id="client_to_designer" type="button" class="btn btn-w-m btn-success btn-block btn-outline"><?php echo $lang_arr['email_client_to_designer']?></button>
                                    </div>
                                </div>

                                <div v-pre class="form-group  row">
                                    <label class="col-sm-12 col-form-label">
										<?php echo $lang_arr['order_email_template_heading'] ?>
                                    </label>
                                    <div class="col-sm-12">
<!--                                        <input id="email_template_heading" name="email_template_heading" value="--><?php //echo htmlspecialchars($settings['email_template_heading'])?><!--" type="text" class="form-control">-->
                                        <textarea id="email_template_heading" name="email_template_heading" class="form-control"  rows="1"><?php echo htmlspecialchars($settings['email_template_heading'])?></textarea>
                                    </div>
                                </div>


								<div v-pre class="form-group row">
                                    <label class="col-12 col-form-label">
	                                    <?php echo $lang_arr['order_email_template_body'] ?>
                                    </label>
									<div class="col-12">
										<textarea id="email_template" class="form-control"  rows="15"><?php echo htmlspecialchars($settings['email_template'])?></textarea>
									</div>
								</div>



                                <div class="form-group row">
                                    <label class="col-sm-12">
		                                <?php echo $lang_arr['order_email_attachments'] ?>
                                    </label>
                                    <div class="col-12">
                                        <label class="switch mr-2">
                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="attached_files.designer.screen" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="col-form-label"><?php echo $lang_arr['order_email_screenshot']?></label>
                                    </div>
                                    <div class="col-12">
                                        <label class="switch mr-2">
                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="attached_files.designer.project" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="col-form-label"><?php echo $lang_arr['order_email_save_file']?></label>
                                    </div>
                                    <div class="col-12">
                                        <label class="switch mr-2">
                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="attached_files.designer.pdf" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="col-form-label"><?php echo $lang_arr['order_email_pdf']?></label>
                                    </div>
                                </div>

							</div>
						</div>

						<div id="tab-2" class="tab-pane">
							<div class="panel-body">

                                <div class="form-group row">
                                    <div class="col-sm-1">
                                        <label class="switch">
                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="send_to_client" type="checkbox" >
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <label class="col-sm-8 col-form-label"><?php echo $lang_arr['order_email_send_to_client']?></label>
                                    <div class="col-sm-3">
                                        <button id="designer_to_client" type="button" class="btn btn-w-m btn-success btn-block btn-outline"><?php echo $lang_arr['email_designer_to_client']?></button>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-1">
                                        <label @click="check_custom_form()" class="switch">
                                            <input type="checkbox" v-bind:true-value="1" v-bind:false-value="0" v-model="use_email_template_client" v-bind:disabled="use_custom_form == 1">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <label class="col-sm-11 col-form-label"><?php echo $lang_arr['order_email_use_template']?></label>
                                </div>

                                <div v-pre class="form-group  row">
                                    <label class="col-sm-12 col-form-label">
										<?php echo $lang_arr['order_email_template_heading'] ?>
                                    </label>
                                    <div class="col-sm-12">
                                        <input id="email_template_heading_client" value="<?php echo htmlspecialchars($settings['email_template_heading_client'])?>" type="text" class="form-control">
                                    </div>
                                </div>


								<div v-pre class="form-group row">
                                    <label class="col-sm-12 col-form-label">
										<?php echo $lang_arr['order_email_template_body'] ?>
                                    </label>
									<div class="col-12">
										<textarea id="email_template_client" class="form-control"  rows="15"><?php echo htmlspecialchars($settings['email_template_client'])?></textarea>
									</div>
								</div>


                                <div class="form-group row">
                                    <label class="col-sm-12">
										<?php echo $lang_arr['order_email_attachments'] ?>
                                    </label>
                                    <div class="col-12">
                                        <label class="switch mr-2">
                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="attached_files.client.screen" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="col-form-label"><?php echo $lang_arr['order_email_screenshot']?></label>
                                    </div>
                                    <div class="col-12">
                                        <label class="switch mr-2">
                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="attached_files.client.project" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="col-form-label"><?php echo $lang_arr['order_email_save_file']?></label>
                                    </div>
                                    <div class="col-12">
                                        <label class="switch mr-2">
                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="attached_files.client.pdf" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="col-form-label"><?php echo $lang_arr['order_email_pdf']?></label>
                                    </div>
                                </div>

							</div>
						</div>

                        <div id="tab-3" class="tab-pane">
                            <div class="panel-body">

                                <div class="form-group row">
                                    <div class="col-sm-1">
                                        <label class="switch">
                                            <input v-bind:true-value="1" v-bind:false-value="0" @change="process_custom_form_fields()" v-model="use_custom_form" type="checkbox">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                    <label class="col-sm-11 col-form-label"><?php echo $lang_arr['order_use_custom_form']?></label>

                                </div>

                                <div class="form-group row">
                                    <div class="col-6">
                                        <p><?php echo $lang_arr['order_email_form_fields']?></p>

                                        <draggable class="" v-model="fields" v-bind="dragOptions"  handle=".drag_handle" group="parent" :move="on_drag" @start="drag = true" @end="drag = false">

                                            <div class="d-flex flex-row flex-wrap draggable_panel pr-4"  v-for="(item,index) in fields">

                                                <div class="drag_handle">
                                                    <span class="drag_handle_icon"></span>
                                                </div>

                                                <div class="col-6 py-2 ">
                                                    <label>
	                                                    <?php echo $lang_arr['name']?>*
                                                    </label>
                                                    <input @input="process_label(item, item.label)" class="form-control" v-model="item.label" type="text">
                                                </div>

                                                <div class="col-6 py-2">
                                                    <label>
	                                                    <?php echo $lang_arr['id']?>*
                                                    </label>
                                                    <input class="form-control" v-model="item.id" type="text">
                                                </div>

                                                <div class="col-12 py-2">
                                                    <label>
	                                                    <?php echo $lang_arr['type']?>
                                                    </label>
                                                    <select class="form-control" v-model="item.type">
                                                        <option value="text"><?php echo $lang_arr['input_type_text']?></option>
                                                        <option value="select"><?php echo $lang_arr['input_type_select']?></option>
                                                        <option value="checkbox"><?php echo $lang_arr['input_type_checkbox']?></option>
                                                        <option value="textarea"><?php echo $lang_arr['input_type_textarea']?></option>
                                                        <option value="file"><?php echo $lang_arr['input_type_file']?></option>
                                                    </select>
                                                </div>

                                                <div v-if="item.type == 'textarea'" class="col-12 py-2">
                                                    <label><?php echo $lang_arr['height']?></label>
                                                    <input class="form-control" v-model="item.rows" type="text">
                                                </div>

                                                <div v-if="item.type == 'select'" class="col-12 py-2">
                                                    <label><?php echo $lang_arr['input_options_list']?></label>

                                                    <draggable class="" v-model="item.values" v-bind="dragOptions" handle=".drag_handle" group="children" :move="on_drag" @start="drag = true" @end="drag = false">
                                                        <div class="d-flex flex-row flex-wrap draggable_panel inner pr-4"  v-for="(value, ind) in item.values">

                                                            <div class="drag_handle inner">
                                                                <span class="drag_handle_icon"></span>
                                                            </div>

                                                            <div class="col-12 py-2">
                                                                <input class="form-control" v-model="value.text" type="text">
                                                            </div>

                                                            <button @click="remove_value(item, ind)" title="<?php echo $lang_arr['delete']?>" style="position: absolute; right: 5px; top:5px; padding: 5px 5px 3px 4px;" type="button" class="del_btn inner btn btn-w btn-danger btn-outline">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </button>

                                                        </div>
                                                    </draggable>

                                                    <button @click="add_value(item)" type="button" class="btn btn-w-m btn-primary btn-block btn-outline"><?php echo $lang_arr['add']?></button>
                                                </div>

                                                <div v-if="item.type != 'file'" class="col-6 py-2">
                                                    <label><?php echo $lang_arr['input_required']?></label>
                                                    <div>
                                                        <label class="switch">
                                                            <input v-bind:true-value="1" v-bind:false-value="0" v-model="item.required" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div v-if="item.required" class="col-6 py-2">
                                                    <label v-if="item.type != 'select' && item.type != 'checkbox'" ><?php echo $lang_arr['input_required_type']?></label>
                                                    <select v-if="item.type != 'select' && item.type != 'checkbox'" class="form-control" v-model="item.required_check_type">
                                                        <option value="non_empty"><?php echo $lang_arr['not_empty']?></option>
                                                        <option value="text"><?php echo $lang_arr['text_only']?></option>
                                                        <option value="email"><?php echo $lang_arr['email']?></option>
                                                        <option value="phone"><?php echo $lang_arr['phone']?></option>
                                                    </select>

                                                    <label v-if="item.type == 'select'"><?php echo $lang_arr['not_value']?></label>
                                                    <select v-if="item.type == 'select'" class="form-control" v-model="item.select_check_val">
                                                        <option v-for="value in item.values" v-bind:value="value.text">{{value.text}}</option>
                                                    </select>

                                                </div>

                                                <div v-if="item.type == 'text'" class="col-12 py-2">
                                                    <label>
		                                                <?php echo $lang_arr['email_field_compatibility']?>*
                                                    </label>
                                                    <select class="form-control" v-model="item.compatibility">
                                                        <option value=""><?php echo $lang_arr['no']?></option>
                                                        <option value="name"><?php echo $lang_arr['client_name']?></option>
                                                        <option value="email"><?php echo $lang_arr['email']?></option>
                                                        <option value="phone"><?php echo $lang_arr['phone']?></option>
                                                    </select>
                                                </div>

                                                <div v-if="item.type == 'textarea'" class="col-12 py-2">
                                                    <label>
			                                            <?php echo $lang_arr['email_field_compatibility']?>*
                                                    </label>
                                                    <select class="form-control" v-model="item.compatibility">
                                                        <option value=""><?php echo $lang_arr['no']?></option>
                                                        <option value="comments"><?php echo $lang_arr['client_comments']?></option>
                                                    </select>
                                                </div>




                                                <button @click="remove_field(index)" title="<?php echo $lang_arr['delete']?>" style="position: absolute; right: 5px; top:5px; padding: 5px 5px 3px 4px;" type="button" class="del_btn btn btn-w btn-danger btn-outline">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </button>

                                            </div>


                                        </draggable>

                                        <button @click="add_field()" type="button" class="btn btn-w-m btn-primary btn-block btn-outline"><?php echo $lang_arr['add']?></button>
                                    </div>
                                    <div class="col-6">
                                        <p><?php echo $lang_arr['order_email_form_preview']?></p>

                                        <div v-bind:class="{'form-group': item.type != 'checkbox', 'form-check': item.type == 'checkbox'}" class="row" v-for="item in fields">



                                            <label v-if="item.type != 'checkbox' && item.type != 'textarea'" class="col-sm-4 col-form-label">{{item.label}}<span v-show="item.required">*</span></label>
                                            <div v-if="item.type != 'checkbox' && item.type != 'textarea'" class="col-sm-8">
                                                <input class="form-control" v-if="item.type == 'text'" name="item.id" type="text">
                                                <input class="form-control" v-if="item.type == 'file'" disabled name="item.id" type="file">

                                                <select v-if="item.type == 'select'" class="form-control" name="item.id">
                                                    <option v-for="value in item.values" value="value.text">{{value.text}}</option>
                                                </select>
                                            </div>

                                            <label v-if="item.type == 'textarea'" class="col-sm-4 col-form-label">{{item.label}}<span v-show="item.required">*</span></label>
                                            <div v-if="item.type == 'textarea'" class="col-sm-8">
                                                <textarea class="form-control" cols="30" v-bind:rows="item.rows"></textarea>
                                            </div>


                                            <label v-if="item.type == 'checkbox'" class="col-12 mb-2">
                                                <input type="checkbox" class="form-check-input">
                                                {{item.label}}<span v-show="item.required">*</span>
                                            </label>





                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-12">
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
	</div>
</form>

<div class="hidden">

    <div id="email_logo_url"><?php echo $logo?></div>

    <div id="email_lang_insert_email_tag"><?php echo $lang_arr['insert_email_tag']?></div>
    <div id="email_lang_order_number"><?php echo $lang_arr['order_number']?></div>
    <div id="email_lang_client_name"><?php echo $lang_arr['client_name']?></div>
    <div id="email_lang_client_email"><?php echo $lang_arr['client_email']?></div>
    <div id="email_lang_client_phone"><?php echo $lang_arr['client_phone']?></div>
    <div id="email_lang_client_comments"><?php echo $lang_arr['client_comments']?></div>
    <div id="email_lang_price"><?php echo $lang_arr['price']?></div>
    <div id="email_lang_project_filename"><?php echo $lang_arr['project_filename']?></div>
    <div id="email_lang_logo"><?php echo $lang_arr['logo']?></div>

    <div id="email_lang_file_too_big_kb"><?php echo $lang_arr['email_file_too_big_kb']?></div>
    <div id="email_lang_file_too_big"><?php echo $lang_arr['email_file_too_big']?></div>
    <div id="email_lang_must_custom_templates_message"><?php echo $lang_arr['email_must_custom_templates_message']?></div>

    <div id="lang_email_using_custom_template"><?php echo $lang_arr['email_using_custom_template']?></div>
    <div id="lang_email_using_custom_template_client"><?php echo $lang_arr['email_using_custom_template_client']?></div>

    <div id="lang_email_error_empty_heading"><?php echo $lang_arr['email_error_empty_heading']?></div>
    <div id="lang_email_error_empty_body"><?php echo $lang_arr['email_error_empty_body']?></div>
    <div id="lang_email_error_only_one_file_input"><?php echo $lang_arr['email_error_only_one_file_input']?></div>
    <div id="lang_email_error_no_email_type"><?php echo $lang_arr['email_error_no_email_type']?></div>


    <div id="lang_email_client_name"><?php echo $lang_arr['client_name']?></div>
    <div id="lang_email_email"><?php echo $lang_arr['email']?></div>
    <div id="lang_email_phone"><?php echo $lang_arr['phone']?></div>
    <div id="lang_email_comments"><?php echo $lang_arr['comments']?></div>
    <div id="lang_email_error_only_one_compatibility_type"><?php echo $lang_arr['email_error_only_one_compatibility_type']?></div>

    <div id="lang_email_warning_no_types"><?php echo $lang_arr['email_warning_no_types']?></div>
    <div id="lang_email_warning_no_types_end"><?php echo $lang_arr['email_warning_no_types_end']?></div>

    <div id="lang_email_error_empty_fields"><?php echo $lang_arr['email_error_empty_fields']?></div>
    <div id="lang_email_error_no_fields"><?php echo $lang_arr['email_error_no_fields']?></div>






    <?php foreach ($settings as $key=>$val):?>

    <?php if($key != 'email_template' && $key != 'email_template_client' && $key != 'email_template_heading' && $key != 'email_template_heading_client  '):?>

        <div id="settings_<?php echo $key?>"><?php echo $val?></div>

    <?php endif;?>

    <?php endforeach;?>





</div>

<!--<link href="/common_assets/theme/css/plugins/codemirror/codemirror.css" rel="stylesheet">-->
<!--<link href="/common_assets/theme/css/plugins/codemirror/ambiance.css" rel="stylesheet">-->

<!-- CodeMirror -->
<!--<script src="/common_assets/theme/js/plugins/codemirror/codemirror.js"></script>-->
<!--<script src="/common_assets/theme/js/plugins/codemirror/mode/javascript/javascript.js"></script>-->
<!--<script src="/common_assets/theme/js/plugins/codemirror/mode/css/css.js"></script>-->
<!--<script src="/common_assets/theme/js/plugins/codemirror/mode/xml/xml.js"></script>-->
<!--<script src="/common_assets/theme/js/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>-->

<script src="/common_assets/libs/tinymce/tinymce.min.js"></script>
<script src="/common_assets/libs/tinymce/jquery.tinymce.min.js"></script>

<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/draggable/sortable.min.js"></script>
<script src="/common_assets/libs/vue/draggable/vuedraggable.min.js"></script>
<script src="/common_assets/admin_js/vue/kitchen/email.js"></script>




<style>
    .tox-tinymce-aux{
        z-index: 99999999;
    }

    .draggable_panel{
        padding-left: 10px;
    }

    .draggable_panel.inner{
        border: 1px solid #bfbfbf!important;
    }

    .del_btn{
        color: #bf0015!important;
        border-color: #bf0015;
    }

    .del_btn:hover, .del_btn:focus, .del_btn:active{
        background-color: #bf0015!important;
        color: #ffffff!important;
    }

    .del_btn.inner{
        top: 11px!important;
    }


    .drag_handle{
        z-index: 1;
        position: absolute;
        width: 16px;
        left: 2px;
        cursor: move;
        top: 5px;
        height: 100%;


    }

    .drag_handle_icon{
        background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAYAAAAGCAYAAADgzO9IAAAAHUlEQVQYV2NkwAEYiZHwhSraDKKRdeCUQDEVpx0AjnMCB4l5XDkAAAAASUVORK5CYII=) repeat;
        height: 18px;
        width: 16px;
        display: block;
    }

    .drag_handle.inner{
        top: 16px;
    }

</style>

<script>



</script>