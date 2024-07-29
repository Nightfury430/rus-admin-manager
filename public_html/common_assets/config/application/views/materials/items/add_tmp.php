<div class="col-sm-12 col-md-12" id="content">
    <h3><?php echo $lang_arr['materials_item_add']?></h3>

    <?php echo validation_errors(); ?>

<!--    <pre>-->
<!--        --><?php //print_r($materials_categories)?>
<!--    </pre>-->

    <form class="material_add" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('materials/items_add/') ?>">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['name']?></label>
                    <input type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                </div>

                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['code']?></label>
                    <input type="text" class="form-control" name="code" placeholder="<?php echo $lang_arr['code']?>">
                </div>

                <div class="form-group" >
                    <label for="category"><?php echo $lang_arr['category']?></label>
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

                <div class="form-group">
                    <label for="active"><?php echo $lang_arr['active']?></label>
                    <select class="form-control" name="active" id="active">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['view_parameters']?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-4">

                        <div class="form-group">
                            <label for="color"><?php echo $lang_arr['color']?></label>
                            <input type="text" class="form-control" name="color" id="color" value="#ffffff">
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="roughness"><?php echo $lang_arr['roughness']?></label>
                                    <input type="number" value="0.8" min="0" max="1" step="0.01" class="form-control" id="roughness" name="roughness" placeholder="0.15">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="metalness"><?php echo $lang_arr['metalness']?></label>
                                    <input type="number" value="0" min="0" max="1" step="0.01" class="form-control" id="metalness" name="metalness" placeholder="0.15">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="transparent"><?php echo $lang_arr['transparent']?></label>
                                    <select class="form-control" name="transparent" id="transparent">
                                        <option selected value="0"><?php echo $lang_arr['no']?></option>
                                        <option value="1"><?php echo $lang_arr['yes']?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label style="opacity: 0" for="opacity"><?php echo $lang_arr['transparent']?></label>
                                    <input type="number" value="1" min="0" max="1" step="0.01" class="form-control" id="opacity" name="opacity" placeholder="0.15">
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?php echo $lang_arr['texture']?>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="map_from_facade_material">Использовать цвет фасада</label>
                                    <select class="form-control" name="map_from_facade_material" id="map_from_facade_material">
                                        <option selected value="0"><?php echo $lang_arr['no']?></option>
                                        <option value="1"><?php echo $lang_arr['yes']?></option>
                                    </select>
                                </div>
                                <div class="form-group map_input">
                                    <label for="map"><?php echo $lang_arr['texture_file']?></label>
                                    <input type="file" name="map" id="map" accept="image/jpeg,image/png,image/gif">
                                    <span class="glyphicon glyphicon-trash remove_map" id="remove_map"></span>
                                </div>


                                <div class="texture_params">


                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="real_width"><?php echo $lang_arr['texture_real_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input disabled type="number" class="form-control" name="real_width" id="real_width" placeholder="256">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="real_height"><?php echo $lang_arr['texture_real_heght']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input disabled type="number" class="form-control" name="real_height" id="real_height" placeholder="256">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="stretch_width"><?php echo $lang_arr['stretch_width']?></label>
                                                <select disabled class="form-control" name="stretch_width" id="stretch_width">
                                                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                                                    <option value="1"><?php echo $lang_arr['yes']?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="stretch_height"><?php echo $lang_arr['stretch_height']?></label>
                                                <select disabled class="form-control" name="stretch_height" id="stretch_height">
                                                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                                                    <option value="1"><?php echo $lang_arr['yes']?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="wrapping"><?php echo $lang_arr['wrapping_type']?></label>
                                                <select disabled class="form-control" name="wrapping" id="wrapping">
                                                    <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror']?></option>
                                                    <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat']?></option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </div>
                        </div>



                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Альфа-текстура
                            </div>
                            <div class="panel-body">
                                <div class="form-group map_input">
                                    <label for="alpha_map">Файл альфа-текстуры</label>
                                    <input type="file" name="alpha_map" id="alpha_map" accept="image/jpeg,image/png,image/gif">
                                    <span class="glyphicon glyphicon-trash remove_map" id="remove_map_alpha"></span>
                                </div>

                                <div class="texture_params_alpha">

                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="real_width_alpha"><?php echo $lang_arr['texture_real_width']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input disabled type="number" class="form-control" name="real_width_alpha" id="real_width_alpha" placeholder="256">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="real_height_alpha"><?php echo $lang_arr['texture_real_heght']?> (<?php echo $lang_arr['units']?>)</label>
                                                <input disabled type="number" class="form-control" name="real_height_alpha" id="real_height_alpha" placeholder="256">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="stretch_width_alpha"><?php echo $lang_arr['stretch_width']?></label>
                                                <select disabled class="form-control" name="stretch_width_alpha" id="stretch_width_alpha">
                                                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                                                    <option value="1"><?php echo $lang_arr['yes']?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label for="stretch_height_alpha"><?php echo $lang_arr['stretch_height']?></label>
                                                <select disabled class="form-control" name="stretch_height_alpha" id="stretch_height_alpha">
                                                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                                                    <option value="1"><?php echo $lang_arr['yes']?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="wrapping_alpha"><?php echo $lang_arr['wrapping_type']?></label>
                                                <select disabled class="form-control" name="wrapping_alpha" id="wrapping_alpha">
                                                    <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror']?></option>
                                                    <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat']?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="col-xs-8">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="model_width"><?php echo $lang_arr['obj_width']?> (<?php echo $lang_arr['units']?>)</label>
                                    <input type="number" class="form-control" id="model_width" value="450">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="model_height"><?php echo $lang_arr['obj_height']?> (<?php echo $lang_arr['units']?>)</label>
                                    <input type="number" class="form-control" id="model_height" value="720">
                                </div>
                            </div>
                            <div class="col-xs-4 hidden">
                                <div class="form-group">
                                    <label for="model_depth"><?php echo $lang_arr['obj_thickness']?> (<?php echo $lang_arr['units']?>)</label>
                                    <input type="number" class="form-control" id="model_depth" value="18">
                                </div>
                            </div>
                        </div>
                        <div id="three_view" style="height: 560px">

                        </div>
                    </div>
                </div>
            </div>
        </div>




        <input class="btn btn-success" type="submit" value="<?php echo $lang_arr['add']?>"/>
        <a class="btn btn-danger" href="<?php echo site_url('materials/items_index/'.$_SESSION['selected_items_category'].'/'.$_SESSION['selected_items_per_page'].'/'.$_SESSION['selected_items_pagination']) ?>"><?php echo $lang_arr['cancel']?></a>
    </form>
</div>

<link rel="stylesheet" href="/common_assets/libs/spectrum/spectrum.css">
<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>
<script src="/common_assets/libs/three.js" type="text/javascript"></script>
<script src="/common_assets/libs/OrbitControls.js" type="text/javascript"></script>
<script src="/common_assets/js/v4/materials.js" type="text/javascript"></script>

<script>

    function build_material_params() {


        let obj = {
            type: 'Standart',
            params:{
                color: $("#color").spectrum("get").toHexString(),
                roughness: $('#roughness').val(),
                metalness: $('#metalness').val(),
                transparent: $('#transparent').val() === "1",
                opacity: $('#opacity').val()
            },
            add_params:{
                model_width: parseInt( $('#model_width').val() ),
                model_height: parseInt( $('#model_height').val() ),
            }
        };

        if( $('#map').val() ){
            obj.params.map = '' + URL.createObjectURL($('#map')[0].files[0]);
            obj.add_params.real_width = parseInt( $('#real_width').val() );
            obj.add_params.real_height = parseInt(  $('#real_height').val() );
            obj.add_params.stretch_width = parseInt( $('#stretch_width').val() );
            obj.add_params.stretch_height = parseInt(  $('#stretch_height').val() );
            obj.add_params.wrapping = $('#wrapping').val();
        }

        if( $('#alpha_map').val() ){
            obj.params.alphaMap = '' + URL.createObjectURL($('#alpha_map')[0].files[0]);
            obj.add_params.real_width = parseInt($('#real_width_alpha').val());
            obj.add_params.real_height = parseInt($('#real_height_alpha').val());
            obj.add_params.stretch_width = parseInt($('#stretch_width_alpha').val());
            obj.add_params.stretch_height = parseInt($('#stretch_height_alpha').val());
            obj.add_params.wrapping = $('#wrapping_alpha').val();
        }


        return obj;
    }

    $(document).ready(function () {

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
                cube.material.color.set(color.toHexString());
            },
            change: function(color) {
                cube.material.color.set(color.toHexString());
            }
        });

        $('#transparent').change(function () {
            cube.material.transparent = $(this).val() === "1";
        });

        $('#opacity').on('input', function () {
            cube.material.opacity = parseFloat($(this).val());
        });

        $('#map_from_facade_material').change(function () {
            $('#map').val('');
            $('#real_width').val('');
            $('#real_height').val('');
            $('#remove_map').hide();
            hide_texture_params();
            cube.material = create_material(build_material_params());
        })

        $('#map').change(function() {

            var $scope = $(this);

            if(this.files.length){

                show_texture_params();

                if(this.files[0].size > 1048576){
                    alert('<?php echo $lang_arr['file_too_big_1mb']?>');
                    $scope.val('');

                    $('#remove_map').hide();

                    hide_texture_params();

                    cube.material = create_material(build_material_params());
                    return false;
                }

                var fr = new FileReader;

                fr.onload = function() {
                    var img = new Image;

                    img.onload = function() {

                        if(power_of_2(img.width) === false || power_of_2(img.height) === false){
                            alert('<?php echo $lang_arr['power_of_2_warning']?>');
                        }

                        $('#real_width').val(img.width);
                        $('#real_height').val(img.height);

                        cube.material = create_material(build_material_params());
                    };

                    img.src = fr.result;
                };
                fr.readAsDataURL(this.files[0]);
            } else {
                $('#real_width').val('');
                $('#real_height').val('');
            }

            $('#remove_map').show();

            if($scope.val() == ''){


                hide_texture_params();

                cube.material = create_material(build_material_params());

                $('#remove_map').hide();
            }
        });

        $('#alpha_map').change(function() {

            var $scope = $(this);

            if(this.files.length){

                show_alpha_texture_params();

                if(this.files[0].size > 1048576){
                    alert('<?php echo $lang_arr['file_too_big_1mb']?>');
                    $scope.val('');

                    $('#remove_map_alpha').hide();

                    hide_texture_params();

                    cube.material = create_material(build_material_params());

                    return false;
                }

                var fr = new FileReader;

                fr.onload = function() {
                    var img = new Image;

                    img.onload = function() {

                        if(power_of_2(img.width) === false || power_of_2(img.height) === false){
                            alert('<?php echo $lang_arr['power_of_2_warning']?>');
                        }

                        $('#real_width_alpha').val(img.width);
                        $('#real_height_alpha').val(img.height);
                        cube.material = create_material(build_material_params());

                    };

                    img.src = fr.result;
                };
                fr.readAsDataURL(this.files[0]);
            } else {
                $('#real_width').val('');
                $('#real_height').val('');


            }

            $('#remove_map_alpha').show();

            if($scope.val() == ''){

                hide_alpha_texture_params();

                cube.material = create_material(build_material_params());

                $('#remove_map_alpha').hide();
            }
        });

        $('#remove_map').click(function () {
            $('#map').val('');
            $('#remove_map').hide();
            hide_texture_params();
            cube.material = create_material(build_material_params())
        });

        $('#remove_map_alpha').click(function () {
            $('#alpha_map').val('');
            $('#remove_map_alpha').hide();
            hide_alpha_texture_params();
            cube.material = create_material(build_material_params())
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
            });

            $('.texture_params select').each(function () {
                $(this).prop("disabled", true)
            })
        }


        function show_alpha_texture_params(){
            $('.texture_params_alpha').show();

            $('.texture_params_alpha input').each(function () {
                $(this).prop("disabled", false)
            });

            $('.texture_params_alpha select').each(function () {
                $(this).prop("disabled", false)
            })
        }

        function hide_alpha_texture_params(){
            $('.texture_params_alpha').hide();

            $('.texture_params_alpha input').each(function () {
                $(this).prop("disabled", true)
            });

            $('.texture_params_alpha select').each(function () {
                $(this).prop("disabled", true)
            })
        }



        $('#real_width').on('input', function(){
            cube.material = create_material(build_material_params())
        });

        $('#real_height').on('input', function(){
            cube.material = create_material(build_material_params())
        });

        $('#real_width_alpha').on('input', function(){
            cube.material = create_material(build_material_params())
        });

        $('#real_height_alpha').on('input', function(){
            cube.material = create_material(build_material_params())
        });

        $('#roughness').on('input', function(){
            cube.material.roughness = $(this).val();
        });

        $('#metalness').on('input', function(){
            cube.material.metalness = $(this).val();
        });

        $('#wrapping').change(function () {
            cube.material = create_material(build_material_params())
        });

        $('#stretch_height').change(function () {
            cube.material = create_material(build_material_params())
        });

        $('#stretch_width').change(function () {
            cube.material = create_material(build_material_params())
        });

        $('#wrapping_alpha').change(function () {
            cube.material = create_material(build_material_params())
        });

        $('#stretch_height_alpha').change(function () {
            cube.material = create_material(build_material_params())
        });

        $('#stretch_width_alpha').change(function () {
            cube.material = create_material(build_material_params())
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
                create_material(build_material_params())
            );
            scene.add(cube);
        }





        texture_loader = new THREE.TextureLoader();

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


        material = create_material(build_material_params());

        cube = new THREE.Mesh( geometry, material );
        scene.add( cube );

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
    });


    function power_of_2(n) {
        if (typeof n !== 'number')
            return 'Not a number';

        return n && (n & (n - 1)) === 0;
    }

</script>

<style>

    .texture_params{
        display: none;
    }

    .texture_params_alpha{
        display: none;
    }

    .map_input{
        display: block;
        width: 100%;
        position: relative;
    }

    .remove_map{
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