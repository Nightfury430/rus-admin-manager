<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['handles_items_add']?></h2>
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

<form class="add_item" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('handles/items_add/') ?>">
<div class="wrapper wrapper-content  animated fadeInRight">


    <div class="row">
        <div class="col-lg-12">
			<?php echo validation_errors(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['basic_params']?></a></li>
                    <li><a class="nav-link resize_three" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['model_params']?></a></li>
                    <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['sizes']?></a></li>
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
											<?php foreach ($materials_categories as $cat):?>
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
                                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['order']?></label>
                                    <div class="col-sm-10">
                                        <input type="number" value="100000" class="form-control" id="order" name="order" >
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
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="type"><?php echo $lang_arr['model_type']?></label>
                                        <select class="form-control" name="type" id="type">
                                            <option selected value="common"><?php echo $lang_arr['handle_model_type_common']?></option>
                                            <option value="facade_profile"><?php echo $lang_arr['handle_model_type_profile']?></option>
                                            <option value="gola"><?php echo $lang_arr['handle_model_type_gola']?></option>
                                            <option value="overhead"><?php echo $lang_arr['handle_model_type_overhead']?></option>
                                        </select>
                                    </div>

                                    <div class="form-group model_input">
                                        <div class="form-group">
                                            <label for="icon"><?php echo $lang_arr['model_file']?></label>
                                            <input type="file" name="model" id="model" accept=".fbx">
                                        </div>
                                        <span class="glyphicon glyphicon-trash remove_model"></span>
                                    </div>


                                    <div class="form-group">
                                        <label for="color"><?php echo $lang_arr['color']?></label>
                                        <input type="text" class="form-control" name="color" id="color" value="#ffffff">
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="roughness"><?php echo $lang_arr['roughness']?></label>
                                                <input type="number" value="0.8" min="0" max="1" step="0.01" class="form-control" id="roughness" name="roughness" placeholder="0.15">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="metalness"><?php echo $lang_arr['metalness']?></label>
                                                <input type="number" value="0" min="0" max="1" step="0.01" class="form-control" id="metalness" name="metalness" placeholder="0.15">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group map_input">
                                        <label for="map"><?php echo $lang_arr['texture_file']?></label>
                                        <input type="file" name="map" id="map" accept="image/jpeg,image/png,image/gif">
                                        <span class="glyphicon glyphicon-trash remove_map"></span>
                                    </div>

                                    <div class="texture_params">


                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="real_width"><?php echo $lang_arr['texture_real_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                    <input disabled type="number" class="form-control" name="real_width" id="real_width" placeholder="256">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="real_height"><?php echo $lang_arr['texture_real_heght']?> (<?php echo $lang_arr['units']?>)</label>
                                                    <input disabled type="number" class="form-control" name="real_height" id="real_height" placeholder="256">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="stretch_width"><?php echo $lang_arr['stretch_width']?></label>
                                                    <select disabled class="form-control" name="stretch_width" id="stretch_width">
                                                        <option value="0"><?php echo $lang_arr['no']?></option>
                                                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="stretch_height"><?php echo $lang_arr['stretch_height']?></label>
                                                    <select disabled class="form-control" name="stretch_height" id="stretch_height">
                                                        <option value="0"><?php echo $lang_arr['no']?></option>
                                                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="wrapping"><?php echo $lang_arr['wrapping_type']?></label>
                                                    <select disabled class="form-control" name="wrapping" id="wrapping">
                                                        <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror']?></option>
                                                        <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat']?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="transparent"><?php echo $lang_arr['transparent']?></label>
                                                    <select disabled class="form-control" name="transparent" id="transparent">
                                                        <option selected value="0"><?php echo $lang_arr['no']?></option>
                                                        <option value="1"><?php echo $lang_arr['yes']?></option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-8">
                                    <div class="row hidden">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="model_width"><?php echo $lang_arr['obj_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" class="form-control" id="model_width" value="450">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="model_height"><?php echo $lang_arr['obj_height']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" class="form-control" id="model_height" value="720">
                                            </div>
                                        </div>
                                        <div class="col-4 hidden">
                                            <div class="form-group">
                                                <label for="model_depth"><?php echo $lang_arr['obj_thickness']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input type="number" class="form-control" id="model_depth" value="18">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="three_view" style="height: 560px">

                                    </div>
                                </div>
                                <input type="hidden" name="material" value="">
                            </div>


                        </div>
                    </div>
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">

                            <select class="hidden" multiple name="variants[]" id="sizes_list"></select>

                            <div class="panel panel-default panel-body sizes_row row">

                                <div class="col-4">
                                    <div class="form-group">
                                        <label><?php echo $lang_arr['code']?></label>
                                        <input type="text" class="form-control code_input" id="model_name_0" value="">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label><?php echo $lang_arr['axis_size']?></label>
                                        <input type="text" class="form-control axis_input" id="model_axis_size_0" value="">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label><?php echo $lang_arr['price']?></label>
                                        <input type="number" step="0.01" min="0" class="form-control price_input" id="model_price_0" value="">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label><?php echo $lang_arr['obj_width']?> (<?php echo $lang_arr['units']?>)</label>
                                        <input type="number" step="1" min="0" class="form-control width_input" id="model_width_0" value="">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label><?php echo $lang_arr['obj_height']?> (<?php echo $lang_arr['units']?>)</label>
                                        <input type="number" step="1" min="0" class="form-control height_input" id="model_height_0" value="">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label><?php echo $lang_arr['obj_depth']?> (<?php echo $lang_arr['units']?>)</label>
                                        <input type="number" step="1" min="0" class="form-control depth_input" id="model_depth_0" value="">
                                    </div>
                                </div>
                                <div class="col-4 offset_y_block hidden">
                                    <div class="form-group">
                                        <label><?php echo $lang_arr['height_offset']?> (<?php echo $lang_arr['units']?>)</label>
                                        <input type="number" step="0.1" min="0" class="form-control offset_y_input" id="model_offset_y_0" value="">
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-sm btn-primary add_size"><?php echo $lang_arr['add_size']?></button>

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
                    <div class="form-group row">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items/handles') ?>"><?php echo $lang_arr['cancel']?></a>
                            <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</form>


<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">
<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<script src="/common_assets/libs/three106.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/libs/inflate.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/FBXLoader.js" type="text/javascript"></script>

<script>

    function add_size_row(){
        var row = $('<div class="panel panel-default panel-body sizes_row row"></div>');
        var remove_panel = $('<span class="glyphicon glyphicon-trash remove_panel"></span>');

        var width_col = $('<div class="col-4"></div>');
        var width_fg = $('<div class="form-group"></div>');
        var width_label = $('<label><?php echo $lang_arr['obj_width']?> (<?php echo $lang_arr['units']?>)</label>');
        var width_input = $('<input type="number" class="form-control width_input" value="">');

        var height_col = $('<div class="col-4"></div>');
        var height_fg = $('<div class="form-group"></div>');
        var height_label = $('<label><?php echo $lang_arr['obj_height']?> (<?php echo $lang_arr['units']?>)</label>');
        var height_input = $('<input type="number" class="form-control height_input" value="">');

        var depth_col = $('<div class="col-4"></div>');
        var depth_fg = $('<div class="form-group"></div>');
        var depth_label = $('<label><?php echo $lang_arr['obj_depth']?> (<?php echo $lang_arr['units']?>)</label>');
        var depth_input = $('<input type="number" class="form-control depth_input" value="">');


        var offset_col = $('<div class="col-4 hidden offset_y_block"></div>');
        var offset_fg = $('<div class="form-group"></div>');
        var offset_label = $('<label><?php echo $lang_arr['height_offset']?> (<?php echo $lang_arr['units']?>)</label>');
        var offset_input = $('<input type="number" step="0.1" class="form-control offset_y_input" value="0">');



        var code_col = $('<div class="col-4"></div>');
        var code_fg = $('<div class="form-group"></div>');
        var code_label = $('<label><?php echo $lang_arr['code']?></label>');
        var code_input = $('<input type="text" class="form-control code_input" value="">');

        var axis_col = $('<div class="col-4"></div>');
        var axis_fg = $('<div class="form-group"></div>');
        var axis_label = $('<label><?php echo $lang_arr['axis_size']?></label>');
        var axis_input = $('<input type="text" class="form-control axis_input" value="">');


        var price_col = $('<div class="col-4"></div>');
        var price_fg = $('<div class="form-group"></div>');
        var price_label = $('<label><?php echo $lang_arr['price']?></label>');
        var price_input = $('<input type="number" step="0.01" min="0" class="form-control price_input" value="">');





        code_col.append(code_fg);
        code_fg.append(code_label);
        code_fg.append(code_input);
        row.append(code_col);

        axis_col.append(axis_fg);
        axis_fg.append(axis_label);
        axis_fg.append(axis_input);
        row.append(axis_col);

        price_col.append(price_fg);
        price_fg.append(price_label);
        price_fg.append(price_input);
        row.append(price_col);


        width_col.append(width_fg);
        width_fg.append(width_label);
        width_fg.append(width_input);
        row.append(width_col);

        height_col.append(height_fg);
        height_fg.append(height_label);
        height_fg.append(height_input);
        row.append(height_col);

        depth_col.append(depth_fg);
        depth_fg.append(depth_label);
        depth_fg.append(depth_input);
        row.append(depth_col);

        offset_col.append(offset_fg);
        offset_fg.append(offset_label);
        offset_fg.append(offset_input);
        row.append(offset_col);

        row.append(remove_panel);

        remove_panel.click(function () {
            row.remove();
        });

        if($('#type').val() !== 'common'){
            offset_col.removeClass('hidden')
        }


        return row;

    }


    $(document).ready(function () {

        $('#type').change(function () {
            if($(this).val() === 'common'){
                $('.offset_y_block').addClass('hidden')
            } else {
                $('.offset_y_block').removeClass('hidden')
            }

        });

        $('.add_size').click(function () {
            var row = add_size_row();
            row.insertBefore($(this));
        });

        $('#category').selectize({
            create: false,
            render: {
                option: function (data, escape) {
                    return "<div class='option " + data.class + "'>" + data.text + "</div>"
                }
            }
        });

        $("#color").spectrum({
            color: "#ffffff",
            preferredFormat: "hex",
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
                material.color.set(color.toHexString());
            },
            change: function(color) {
                material.color.set(color.toHexString());
            }
        });

        $('#model').change(function () {
            var $scope = $(this);

            if(this.files.length){
                var reader = new FileReader;
                reader.onload = function(event) {
                    loader.load(event.target.result, function (obj) {
                        if(model !== undefined){
                            scene.remove(model);
                        }
                        model = obj;
                        model_change_material(model,material);
                        scene.add(model);

                        var box3 = new THREE.Box3().setFromObject(model).getSize();



                        $('#model_width_0').val(Math.round(box3.x.toFixed(2) * 10));
                        $('#model_height_0').val(Math.round(box3.y.toFixed(2) * 10));
                        $('#model_depth_0').val(Math.round(box3.z.toFixed(2) * 10));

                    });
                };

                reader.readAsDataURL(this.files[0]);
            }
        });

        function model_change_material (obj, material) {
            if(obj.type === 'Mesh'){
                obj.material = material;
            }
            if(obj.type === 'Group'){
                if(obj.children.length){
                    for (var i = 0; i < obj.children.length; i++){
                        obj.children[i].material = material;
                    }
                }
            }
        }

        $('#transparent').change(function () {
            if($(this).val() === "1"){
                material.transparent = true;
            } else {
                material.transparent = false;
            }
        });


        $('#map').change(function() {
            var $scope = $(this);
            if(this.files.length){
                show_texture_params();
                if(this.files[0].size > 1048576){
                    alert('<?php echo $lang_arr['file_too_big_1mb']?>');
                    $scope.val('');
                    $('.remove_map').hide();
                    hide_texture_params();
                    material = new THREE.MeshStandardMaterial({
                        color: $("#color").spectrum("get").toHexString(),
                        roughness: $('#roughness').val(),
                        metalness: $('#metalness').val()
                    });
                    cube.material = material;
                    return false;
                }
                var fr = new FileReader;
                fr.onload = function() { // file is loaded
                    var img = new Image;
                    img.onload = function() {
                        if(power_of_2(img.width) === false || power_of_2(img.height) === false){
                            alert('<?php echo $lang_arr['power_of_2_warning']?>');
                        }
                        $('#real_width').val(img.width);
                        $('#real_height').val(img.height);
                        texture = texture_loader.load(img.src);
                        material.map = texture;
                        resize_texture()
                    };
                    img.src = fr.result; // is the data URL because called with readAsDataURL
                };
                fr.readAsDataURL(this.files[0]);
            } else {
                $('#real_width').val('');
                $('#real_height').val('');
            }

            $('.remove_map').show();

            if($scope.val() == ''){
                material = new THREE.MeshStandardMaterial({
                    color: $("#color").spectrum("get").toHexString(),
                    roughness: $('#roughness').val(),
                    metalness: $('#metalness').val()
                });

                hide_texture_params();

                model_change_material(model,material);

                $('.remove_map').hide();
            }
        });

        $('.remove_map').click(function () {
            material = new THREE.MeshStandardMaterial({
                color: $("#color").spectrum("get").toHexString(),
                roughness: $('#roughness').val(),
                metalness: $('#metalness').val()
            });

            model_change_material(model,material);

            $('#map').val('');

            $('.remove_map').hide();


            hide_texture_params();

        });


        function show_texture_params(){
            $('.texture_params').show();

            $('.texture_params input').each(function () {
                $(this).prop("disabled", false)
            });

            $('.texture_params select').each(function () {
                $(this).prop("disabled", false)
            })


        }

        function hide_texture_params(){
            $('.texture_params').hide();

            $('.texture_params input').each(function () {
                $(this).prop("disabled", true)
            })

            $('.texture_params select').each(function () {
                $(this).prop("disabled", true)
            })


        }

        $('#real_width').on('input', function(){
            resize_texture()
        });

        $('#real_height').on('input', function(){
            resize_texture()
        });


        $('#roughness').on('input', function(){
            material.roughness = $(this).val();
        });

        $('#metalness').on('input', function(){
            material.metalness = $(this).val();
        });

        $('#wrapping').change(function () {
            if($('#wrapping').val() === 'repeat'){
                texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
            } else {
                texture.wrapS = texture.wrapT = THREE.MirroredRepeatWrapping;
            }
            texture.needsUpdate = true;
            material.needsUpdate = true;
        });

        $('#stretch_height').change(function () {
            resize_texture();
        });

        $('#stretch_width').change(function () {
            resize_texture();
        });


        $('#model_width').on('input', function(){
            resize_cube(parseInt($('#model_width').val()), parseInt($('#model_height').val()),parseInt($('#model_depth').val()))
        });

        $('#model_height').on('input', function(){
            resize_cube(parseInt($('#model_width').val()), parseInt($('#model_height').val()),parseInt($('#model_depth').val()))
        });

        $('#model_depth').on('input', function(){
            resize_cube(parseInt($('#model_width').val()), parseInt($('#model_height').val()),parseInt($('#model_depth').val()))
        });


        var model_width = 450;
        var model_height = 720;
        var model_depth = 18;

        function resize_cube(w, h, d) {
            scene.remove(cube);
            model_width = w;
            model_height = h;
            model_depth = d;
            cube = new THREE.Mesh(
                new THREE.BoxGeometry(w/10,h/10,d/10),
                material
            );
            scene.add(cube);
            resize_texture();
        }


        function resize_texture() {
            if($('#wrapping').val() === 'repeat'){
                texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
            } else {
                texture.wrapS = texture.wrapT = THREE.MirroredRepeatWrapping;
            }

            if($('#stretch_width').val() == 1){
                repeat_x = 1;
            } else {
                repeat_x = model_width  / $('#real_width').val();
            }

            if($('#stretch_height').val() == 1){
                repeat_y = 1;
            } else {
                repeat_y = model_height  /  $('#real_height').val() ;
            }

            texture.repeat.set(repeat_x,repeat_y);
            texture.needsUpdate = true;

            material.needsUpdate = true;
        }


        var texture_loader = new THREE.TextureLoader();



        var viewport = document.getElementById('three_view');

        scene = new THREE.Scene();
        scene.fog = new THREE.Fog(0xE9E5CE, 500, 10000);
        var camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

        var renderer = new THREE.WebGLRenderer({
            antialias: true
        });
        renderer.setClearColor(scene.fog.color);
        renderer.setSize( viewport.clientWidth, viewport.clientHeight );
        viewport.appendChild( renderer.domElement );

        var geometry = new THREE.BoxGeometry( model_width/10, model_height/10, model_depth/10 );
        material = new THREE.MeshStandardMaterial({
            color: $("#color").spectrum("get").toHexString(),
            roughness: $('#roughness').val(),
            metalness: $('#metalness').val()
        });
        var cube = new THREE.Mesh( geometry, material );
        // scene.add( cube );


        fbx_manager = new THREE.LoadingManager();
        loader = new THREE.FBXLoader(fbx_manager);

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




        controls = new THREE.OrbitControls(camera, renderer.domElement);

        var animate = function () {
            requestAnimationFrame( animate );

            // cube.rotation.x += 0.01;
            // cube.rotation.y += 0.01;

            renderer.render( scene, camera );
        };

        animate();

        window.addEventListener('resize', onWindowResize, false);

        function onWindowResize() {
            var viewport = document.getElementById('three_view');
            camera.aspect = viewport.clientWidth / viewport.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize( viewport.clientWidth, viewport.clientHeight );
        }

        $('.resize_three').click(function () {
            setTimeout(function () {
                var viewport = document.getElementById('three_view');
                camera.aspect = viewport.clientWidth / viewport.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize( viewport.clientWidth, viewport.clientHeight );
            },100)

        })


        $('.add_item').submit(function (e) {

            var select = $('#sizes_list');

            $('.sizes_row').each(function () {
                var scope = $(this);

                if( parseFloat(scope.find('.width_input').val()) > 0 && parseFloat(scope.find('.height_input').val()) > 0 && parseFloat(scope.find('.depth_input').val()) > 0)
                    select.append('' +
                        '<option selected value="' +
                        ''+ scope.find('.width_input').val() + ';'
                        + scope.find('.height_input').val() + ';'
                        + scope.find('.depth_input').val() +';'
                        + scope.find('.code_input').val() + ';'
                        + scope.find('.axis_input').val() +';'
                        + scope.find('.price_input').val() +';'
                        + scope.find('.offset_y_input').val() +';'
                        +') "></option>')
            });

            return true;
        });
    });




    function power_of_2(n) {
        if (typeof n !== 'number')
            return 'Not a number';

        return n && (n & (n - 1)) === 0;
    }

</script>

<style>

    .sizes_row{
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

    .texture_params{
        display: none;
    }

    .map_input{
        display: block;
        width: 100%;
        position: relative;
    }

    .remove_map, .remove_model{
        position: absolute;
        right: 0;
        bottom:0;
        color: #ff0000;
        display: none;
        cursor: pointer;
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