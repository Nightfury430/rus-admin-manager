<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['facade_add']?></h2>
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

<form id="sub_form" action="<?php echo site_url('facades/items_add/') ?>">
    <input id="form_success_url" value="<?php echo site_url('facades/items_index/') ?>" type="hidden">


<div class="wrapper wrapper-content  animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger error_msg" style="display:none"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['models']?></a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['available_materials']?></a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">

                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name']?>*</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category']?></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="category" id="category">
                                            <option data-data='{"class": "top_cat"}' value="0"><?php echo $lang_arr['no']?></option>
		                                    <?php foreach ($categories as $cat):?>
			                                    <?php if($cat['parent'] == 0): ?>
                                                    <option data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
				                                    <?php if (isset($cat['children'])):?>
					                                    <?php foreach ($cat['children'] as $child):?>
                                                            <option data-data='{"class": "sub_cat"}' value="<?php echo $child['id']?>"><?php echo $child['name']?></option>
					                                    <?php endforeach;?>
				                                    <?php endif;?>
			                                    <?php endif;?>
		                                    <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['active']?></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="active" id="active">
                                            <option selected value="1"><?php echo $lang_arr['yes']?></option>
                                            <option value="0"><?php echo $lang_arr['no']?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['icon']?></label>
                                    <div class="col-sm-10">
                                        <input type="file" name="icon" id="icon" accept="image/jpeg,image/png,image/gif">
                                    </div>
                                </div>

                            </fieldset>

                        </div>
                    </div>
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body models_list">

                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="full">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_full">
					                        <?php echo $lang_arr['facade_full']?>
                                        </a>
                                    </div>
                                    <div id="collapse_full" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <select class="hidden" multiple name="full_models_list[]" id="full_models_list"></select>
                                            <button type="button" class="btn btn-sm btn-primary add_full add_model_button"><?php echo $lang_arr['add_model']?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="window">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse_window">
					                        <?php echo $lang_arr['facade_window']?>
                                        </a>
                                    </div>
                                    <div id="collapse_window" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <select class="hidden" multiple name="window_models_list[]" id="window_models_list"></select>
                                            <button type="button" class="btn btn-sm btn-primary add_window"><?php echo $lang_arr['add_model']?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="frame">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse_frame">
					                        <?php echo $lang_arr['facade_frame']?>
                                        </a>
                                    </div>
                                    <div id="collapse_frame" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <select class="hidden" multiple name="frame_models_list[]" id="frame_models_list"></select>
                                            <button type="button" class="btn btn-sm btn-primary add_frame"><?php echo $lang_arr['add_model']?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="radius_full">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse_radius_full">
					                        <?php echo $lang_arr['facade_radius_full']?>
                                        </a>
                                    </div>
                                    <div id="collapse_radius_full" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <select class="hidden" multiple name="radius_full_models_list[]" id="radius_full_models_list"></select>
                                            <button type="button" class="btn btn-sm btn-primary add_radius_full"><?php echo $lang_arr['add_model']?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="radius_window">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse_radius_window">
					                        <?php echo $lang_arr['facade_radius_window']?>
                                        </a>
                                    </div>
                                    <div id="collapse_radius_window" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <select class="hidden" multiple name="radius_window_models_list[]" id="radius_window_models_list"></select>
                                            <button type="button" class="btn btn-sm btn-primary add_radius_window"><?php echo $lang_arr['add_model']?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="radius_frame">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                                           href="#collapse_radius_frame">
					                        <?php echo $lang_arr['facade_radius_frame']?>
                                        </a>
                                    </div>
                                    <div id="collapse_radius_frame" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <select class="hidden" multiple name="radius_frame_models_list[]" id="radius_frame_models_list"></select>
                                            <button type="button" class="btn btn-sm btn-primary add_radius_frame"><?php echo $lang_arr['add_model']?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body materials_list">


			                <?php if ( isset( $materials ) ): ?>

                                <fieldset>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"><?php echo $lang_arr['choose_categories'] ?></label>
                                        <div class="col-sm-10">
                                            <select multiple class="form-control" name="materials[]" id="materials">
		                                        <?php foreach ( $materials as $cat ): ?>
			                                        <?php if ( $cat['parent'] == 0 ): ?>
                                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
			                                        <?php endif; ?>
		                                        <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>


			                <?php endif; ?>

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

</form>


<div class="three_modal_wrapper">
    <div class="three_modal">
        <span class="close_three_modal glyphicon glyphicon-remove"></span>
        <div id="three_viewport">
        </div>
    </div>
</div>

<style>

    .l_hack{
        opacity: 0;
    }

    .model_panel{
        position: relative;
        padding-top: 30px;
        padding-bottom: 10px;
        margin-bottom: 10px!important;
    }

    .remove_panel{
        position: absolute;
        top:10px;
        right: 10px;
        color: #ff0000;
        cursor: pointer;
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

    .selectize-control.multi .selectize-input > div{
        background: #428bca;
        color: #ffffff;
        padding-left: 10px;
        padding-right: 10px;
    }


</style>

<script src="/common_assets/libs/three.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>


<script>

    function get_object(arr){

        let obj_raw = [];

        for(let i = 0; i < arr.length;i++){

            let file = $(arr[i]).find('.model_file_input')[0].files[0];
            let min_width = $(arr[i]).find('.min_width_input').val();
            let min_height = $(arr[i]).find('.min_height_input').val();

            if(file){
                obj_raw.push({
                    model: file.name,
                    min_width: parseInt(min_width),
                    min_height: parseInt(min_height)
                })
            }
        }


        return _.sortBy(obj_raw, ['min_height', 'min_width']);
    }

    $(document).ready(function () {

        $('#sub_form').submit(function (e) {

            e.preventDefault();

            let full_items = $('#full').parent().find('.model_panel');
            let window_items = $('#window').parent().find('.model_panel');
            let frame_items = $('#frame').parent().find('.model_panel');

            let radius_full = $('#radius_full').parent().find('.model_panel');
            let radius_window = $('#radius_window').parent().find('.model_panel');
            let radius_frame = $('#radius_frame').parent().find('.model_panel');

            let full_objs = get_object(full_items);
            let window_objs = get_object(window_items);
            let frame_objs = get_object(frame_items);

            let radius_full_objs = get_object(radius_full);
            let radius_window_objs = get_object(radius_window);
            let radius_frame_objs = get_object(radius_frame);

            let all_objs = {
                full: full_objs,
                window: window_objs,
                frame: frame_objs,
                radius_full: radius_full_objs,
                radius_window: radius_window_objs,
                radius_frame: radius_frame_objs,
            };


            let files_arr = [];

            $('.model_file_input').each(function () {
                if($(this)[0].files[0]) files_arr.push($(this)[0].files[0])
            });

            let files_arr_done = _.uniqWith(
                files_arr,
                (one, two) =>
                    one.name === two.name &&
                    one.size === two.size
            );

            let formData = new FormData();

            for (let i = 0; i < files_arr_done.length; i++){
                formData.append('file'+i, files_arr_done[i]);
            }

            if($('#icon')[0].files[0] !== undefined){
                formData.append('icon', $('#icon')[0].files[0]);
            }

            if( $('#materials').length > 0 ){
                if($('#materials').val() === null){
                    formData.append('materials', '');
                } else {
                    formData.append('materials', JSON.stringify($('#materials').val()));
                }
            }

            formData.append('active', $('#active').val());


            formData.append('data', JSON.stringify(all_objs));
            formData.append('name', $('#name').val());
            formData.append('category', $('#category').val());
            $.ajax({
                url : $(this).attr('action'),
                type : 'POST',
                data : formData,
                cache: false,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success : function(msg) {
                    console.log(msg);
                    let data = JSON.parse(msg);
                    if($.isEmptyObject(data.error)){
                        $(".error_msg").css('display','none');
                        window.location.href = $('#form_success_url').val();
                    }else{
                        $(".error_msg").html(data.error).css('display','block');
                    }
                }
            });

            return false;

        });

        var full_count = 0;
        var window_count = 0;
        var frame_count = 0;
        var radius_full_count = 0;
        var radius_window_count = 0;
        var radius_frame_count = 0;

        $('.add_full').click(function () {
            full_count +=1;
            var panel = create_model_panel(full_count, 'full');
            panel.insertBefore($(this));

            var select = $('#full_models_list');
            select.append('<option data-id="'+ full_count +'" selected value="model_full_'+ full_count +';min_width_full_'+ full_count +';min_height_full_'+ full_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });

        });

        $('.add_window').click(function () {
            window_count+=1;
            var panel = create_model_panel(window_count, 'window');
            panel.insertBefore($(this));

            var select = $('#window_models_list');
            select.append('<option data-id="'+ window_count +'" selected value="model_window_'+ window_count +';min_width_window_'+ window_count +';min_height_window_'+ window_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_frame').click(function () {
            frame_count+=1;
            var panel = create_model_panel(frame_count, 'frame');
            panel.insertBefore($(this));

            var select = $('#frame_models_list');
            select.append('<option data-id="'+ frame_count +'" selected value="model_frame_'+ frame_count +';min_width_frame_'+ frame_count +';min_height_frame_'+ frame_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_radius_full').click(function () {
            radius_full_count+=1;
            var panel = create_model_panel(radius_full_count, 'radius_full');
            panel.insertBefore($(this));

            var select = $('#radius_full_models_list');
            select.append('<option data-id="'+ radius_full_count +'" selected value="model_radius_full_'+ radius_full_count +';min_width_radius_full_'+ radius_full_count +';min_height_radius_full_'+ radius_full_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_radius_window').click(function () {
            radius_window_count+=1;
            var panel = create_model_panel(radius_window_count, 'radius_window');
            panel.insertBefore($(this));

            var select = $('#radius_window_models_list');
            select.append('<option data-id="'+ radius_window_count +'" selected value="model_radius_window_'+ radius_window_count +';min_width_radius_window_'+ radius_window_count +';min_height_radius_window_'+ radius_window_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });

        $('.add_radius_frame').click(function () {
            radius_frame_count+=1;
            var panel = create_model_panel(radius_frame_count, 'radius_frame');
            panel.insertBefore($(this));

            var select = $('#radius_frame_models_list');
            select.append('<option data-id="'+ radius_frame_count +'" selected value="model_radius_frame_'+ radius_frame_count +';min_width_radius_frame_'+ radius_frame_count +';min_height_radius_frame_'+ radius_frame_count +'"></option>')
            panel.remove_panel.click(function () {
                select.find('option[data-id="'+ panel.panel_id +'"]').remove();
                panel.remove();
            });
        });


        function create_model_panel(id, name){
            var panel = $('<div class="panel panel-default panel-body model_panel"></div>');
            var remove_panel = $('<span class="glyphicon glyphicon-trash remove_panel"></span>');
            var row = $('<div class="row"></div>');

            var file_wrapper = $('<div class="form-group col-12 col-sm-3"></div>');
            var file_label = $('<label for="model_'+ name +'_'+ id +'"><?php echo $lang_arr["choose_file"]?></label>');
            var file_input = $('<input type="file" class="model_file_input" name="model_'+ name +'_'+ id +'" id="model_'+ name +'_'+ id +'" accept=".fbx">');

            var min_width_wrapper = $('<div class="form-group col-12 col-sm-3"></div>');
            var min_width_label = $('<label for="min_width_'+name+'_'+ id +'"><?php echo $lang_arr["min_width"]?> (<?php echo $lang_arr["units"]?>)</label>');
            var min_width_input = $('<input class="min_width_input" type="number" name="min_width_'+name+'_'+ id +'" id="min_width_'+name+'_'+ id +'"  value="0" class="form-control">');

            var min_height_wrapper = $('<div class="form-group col-12 col-sm-4"></div>');
            var min_height_label = $('<label for="min_height_'+name+'_'+ id +'"><?php echo $lang_arr["min_height"]?> (<?php echo $lang_arr["units"]?>)</label>');
            var min_height_input = $('<input class="min_height_input" type="number" name="min_height_'+name+'_'+ id +'" id="min_height_'+name+'_'+ id +'"  value="0" class="form-control">');

            var test_wrapper = $('<div class="form-group col-2"></div>');
            var test_label = $('<label class="l_hack">1</label>');
            var test_button = $('<button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_'+ name +'_'+ id +'"><?php echo $lang_arr["test_model"]?></button>')


            panel.append(remove_panel);
            panel.append(row);

            row.append(file_wrapper);
            row.append(min_width_wrapper);
            row.append(min_height_wrapper);
            row.append(test_wrapper);

            file_wrapper.append(file_label);
            file_wrapper.append(file_input);

            min_width_wrapper.append(min_width_label);
            min_width_wrapper.append(min_width_input);

            min_height_wrapper.append(min_height_label);
            min_height_wrapper.append(min_height_input);

            test_wrapper.append(test_label);
            test_wrapper.append(test_button);

            panel.remove_panel = remove_panel;
            panel.panel_id = id;

            test_button.click(function () {

                var file = $(this).parent().parent().find('input[type="file"]');

                if(file[0].files[0] !== undefined){

                    var modal = $('.three_modal_wrapper');
                    modal.fadeIn();

                    var reader = new FileReader();

                    var mw = $(this).parent().parent().find('.min_width_input').val();
                    var mh = $(this).parent().parent().find('.min_height_input').val();

                    reader.onload = function(event) {
                        init_three_test('three_viewport', event.target.result, mw, mh );
                    };

                    reader.readAsDataURL(file[0].files[0]);


                    $('.close_three_modal').click(function () {
                        modal.fadeOut();
                        $('#three_viewport').html('');
                        renderer.renderLists.dispose();
                    })

                } else {
                    alert('<?php echo $lang_arr['file_not_choosen']?>')
                }




            });

            file_input.change(function () {
                var name = $(this)[0].files[0].name;
                var n_arr = name.split('_');

                if(n_arr[n_arr.length-2].indexOf('w') > -1 ){
                    min_width_input.val(
                        parseInt(n_arr[n_arr.length-2].substr(1))
                    )
                }

                if(n_arr[n_arr.length-1].indexOf('h') > -1 ){
                    min_height_input.val(
                        parseInt(n_arr[n_arr.length-1].substr(1))
                    )
                }

            });

            return panel;

        }


        function init_three_test(element_id, model, mw, mh) {

            let cur_width = 396;
            let cur_height = 716;
            let cur_depth = 16;

            if(parseInt(mw) !== 0){
                cur_width = parseInt(mw);
            }

            if(parseInt(mh) !== 0){
                cur_height = parseInt(mh);
            }

            var controls_div = $('<div id="three_viewport_controls"></div>');
            $('#three_viewport').append(controls_div);

            var heights = [136, 176, 356, 536, 716, 916, 946];
            var widths = [96, 196, 296, 346, 396, 446, 496, 596, 796];


            var width_input = $('<input type="number" value="'+cur_width+'">');
            var height_input = $('<input type="number" value="'+cur_height+'">');


            controls_div.append('<span><?php echo $lang_arr["width"]?>: </span>');

            for (let i = 0; i < widths.length; i++){
                let btn = $('<button >'+ widths[i] +'</button >');
                btn.click(function () {
                    cur_width = widths[i];
                    width_input.val(cur_width);
                    set_size(cur_width,cur_height,cur_depth)
                });
                controls_div.append(btn);
            }

            controls_div.append('<br>');

            controls_div.append('<span><?php echo $lang_arr["height"]?>: </span>');

            for (let i = 0; i < heights.length; i++){
                let btn = $('<button >'+ heights[i] +'</button >');
                btn.click(function () {
                    cur_height = heights[i];
                    height_input.val(cur_height);
                    set_size(cur_width,cur_height,cur_depth)
                });
                controls_div.append(btn);
            }

            controls_div.append('<br>');
            controls_div.append('<span><?php echo $lang_arr['custom_sizes']?>: </span>');


            controls_div.append(width_input);
            controls_div.append(height_input);


            var button = $('<button style="width: 200px;"><?php echo $lang_arr['change_size']?></button>');
            controls_div.append(button);
            button.click(function () {
                cur_width = parseInt($(width_input).val());
                cur_height = parseInt(height_input.val());

                set_size(cur_width,cur_height,cur_depth)
            });

            var viewport = document.getElementById(element_id);

            scene = new THREE.Scene();
            scene.fog = new THREE.Fog(0xE9E5CE, 500, 10000);
            var camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

            renderer = new THREE.WebGLRenderer({
                antialias: true
            });
            renderer.setClearColor(scene.fog.color);
            renderer.setSize( viewport.clientWidth, viewport.clientHeight );
            viewport.appendChild( renderer.domElement );

            fbx_manager = new THREE.LoadingManager();
            loader = new THREE.FBXLoader(fbx_manager);

            var axesHelper = new THREE.AxesHelper( 50 );
            scene.add( axesHelper );



            var amb_light = new THREE.AmbientLight( 0xffffff, 0.63);
            scene.add( amb_light );


            var directionalLight = new THREE.DirectionalLight( 0xffffff, 0.2 ,100  );
            directionalLight.castShadow = false;
            directionalLight.position.set( 0, 450, 300 );
            directionalLight.shadow.mapSize.width = 256;
            directionalLight.shadow.mapSize.height = 256;
            directionalLight.shadow.camera.near = 0.5;
            directionalLight.shadow.camera.far = 1500;
            directionalLight.shadow.camera.left = -1000;
            directionalLight.shadow.camera.right = 1000;
            directionalLight.shadow.camera.top = 1000;
            directionalLight.shadow.camera.bottom = -1000;

            directionalLight.target = new THREE.Group();
            directionalLight.target.position.set(0,0,0);
            directionalLight.target.name = 'Directional Light Target';

            scene.add(directionalLight);
            scene.add(directionalLight.target);

            camera.position.z = 150;


            var light1 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light1.position.set(0, 300,0);
            light1.decay = 5;

            var light2 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light2.position.set(300, 0,0);
            light2.decay = 5;

            var light3 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light3.position.set(-300, 0,0);
            light3.decay = 5;

            var light4 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light4.position.set(0, 0,300);
            light4.decay = 5;

            var light5 = new THREE.PointLight( 0xffffff, 0.5, 2000);
            light5.position.set(0, 0,-300);
            light5.decay = 5;

            scene.add(light1);
            scene.add(light2);
            scene.add(light3);
            scene.add(light4);
            scene.add(light5);

            fac_model = '';

            set_size();


            let errors = [];


            function set_size() {





                loader.load(model, function (obj) {

                    if(fac_model!==''){
                        scene.remove(fac_model);
                    }

                    fac_model = new THREE.Group();

                    console.log(obj)

                    for (i = 0; i < obj.children.length; i++) {

                        var m = obj.children[i];

                        g = new THREE.Geometry().fromBufferGeometry(m.geometry.clone());
                        g.scale(m.scale.x, m.scale.y, m.scale.z);

                        if (m.name.indexOf('al') > -1) {
                            material = new THREE.MeshPhongMaterial({
                                color: 0xbdbebf
                            });
                        }

                        if (m.name.indexOf('glass') > -1) {
                            material = new THREE.MeshStandardMaterial({
                                color: 0xd1e2ff,
                                transparent: true,
                                opacity: 0.2,
                                roughness: 0,
                                metalness: 0
                            });
                        }

                        if (m.name.indexOf('gen') > -1) {
                            material = new THREE.MeshStandardMaterial({
                                color: '#bbb',
                                roughness: 0.8,
                                metalness: 0
                            });
                        }

                        if (m.name.indexOf('pat') > -1) {
                            material = new THREE.MeshStandardMaterial({
                                color: '#b99303',
                                roughness: 0.8,
                                metalness: 0
                            });
                        }






                        mesh = new THREE.Mesh(g, material);

                        mesh.name = m.name;
                        console.log(mesh);










                        fac_model.add(mesh);







                    }


                    for (i = 0; i < fac_model.children.length; i++) {


                        var scope = this,
                            i,
                            j,
                            v,
                            p,
                            s = new THREE.Vector3(cur_width / 10, cur_height / 10, cur_depth / 10),
                            box = new THREE.Box3().setFromObject(fac_model).getSize().clone()
                        ;
                        p = s.clone().sub(box).divideScalar(2);

                        for (j = 0; j < fac_model.children[i].geometry.vertices.length; j++) {
                            v = fac_model.children[i].geometry.vertices[j];

                            if (fac_model.children[i].name.indexOf('x') > -1) {
                                if (v.x < 0) {
                                    v.x -= p.x;
                                } else {
                                    v.x += p.x;
                                }
                            }

                            if (fac_model.children[i].name.indexOf('y') > -1) {
                                if (v.y < 0) {
                                    v.y -= p.y;
                                } else {
                                    v.y += p.y;
                                }
                            }

                            if (fac_model.children[i].name.indexOf('z') > -1) {
                                if (v.z < 0) {
                                    v.z -= p.z;
                                } else {
                                    v.z += p.z;
                                }
                            }
                        }

                        fac_model.children[i].geometry.verticesNeedUpdate = true;

                    }

                    scene.add(fac_model);
                })
            }



            controls = new THREE.OrbitControls(camera, renderer.domElement);

            var animate = function () {
                requestAnimationFrame( animate );

                // cube.rotation.x += 0.01;
                // cube.rotation.y += 0.01;

                renderer.render( scene, camera );
            };

            animate();


        }


        $('#materials').selectize({
            create: false,
            plugins: ['remove_button'],
            dropdownDirection: 'up'
        });

        $('#category').selectize({
            create: false,
            render: {
                option: function (data, escape) {
                    return "<div class='option " + data.class + "'>" + data.text + "</div>"
                }
            }
        });

        if (!window.Selectize.prototype.positionDropdownOriginal) {
            window.Selectize.prototype.positionDropdownOriginal = window.Selectize.prototype.positionDropdown;
            window.Selectize.prototype.positionDropdown = function () {
                if (this.settings.dropdownDirection === 'up') {
                    var $control = this.$control;
                    var offset = this.settings.dropdownParent === 'body' ? $control.offset() : $control.position();

                    this.$dropdown.css({
                        width: $control.outerWidth(),
                        // top: offset.top - this.$dropdown.outerHeight() - 10,
                        left: offset.left,
                        bottom: 'calc(100% + 4px)'
                    });
                    this.$dropdown.addClass('direction-' + this.settings.dropdownDirection);
                    this.$control.addClass('direction-' + this.settings.dropdownDirection);
                    this.$wrapper.addClass('direction-' + this.settings.dropdownDirection);
                } else {
                    window.Selectize.prototype.positionDropdownOriginal.apply(this, arguments);
                }
            };
        }


    });
</script>

<style>



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