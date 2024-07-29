<div class="col-sm-12 col-md-12" id="content">
    <h3>Добавить фасад</h3>

    <?php echo validation_errors(); ?>

    <form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('facades/items_add/') ?>">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['basic_params']?></h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name"><?php echo $lang_arr['name']?>*</label>
                    <input type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                </div>

                <div class="form-group" >
                    <label for="category"><?php echo $lang_arr['category']?></label>
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

                <div class="form-group">
                    <label for="active"><?php echo $lang_arr['active']?></label>
                    <select class="form-control" name="active" id="active">
                        <option selected value="1"><?php echo $lang_arr['yes']?></option>
                        <option value="0"><?php echo $lang_arr['no']?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="icon"><?php echo $lang_arr['icon']?></label>
                    <input type="file" name="icon" id="icon">
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $lang_arr['models']?></h4>
            </div>
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


        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $lang_arr['available_materials']?></div>
            <div class="panel-body materials_list">
                <div class="form-group">
                    <label for="materials"><?php echo $lang_arr['choose_categories']?></label>
                    <select multiple class="form-control" name="materials[]" id="materials">
                        <?php foreach ($materials as $cat): ?>
                            <?php if ($cat['parent'] == 0): ?>
                                <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>


        <input class="btn btn-success" type="submit" name="submit" value="<?php echo $lang_arr['add']?>"/>
        <a class="btn btn-danger" href="<?php echo site_url('facades/items_index/') ?>"><?php echo $lang_arr['cancel']?></a>
    </form>
</div>

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
    $(document).ready(function () {

        $('#sub_form').submit(function (e) {
            // e.preventDefault();
            $('.models_list input[type="file"]').each(function () {
                if($(this).val() === ''){


                    $(this).parent().parent().parent().find('.remove_panel').click();
                    // $(this).parent().parent().find('.remove_panel').click();
                }
            });

            return true;
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

            var file_wrapper = $('<div class="form-group col-xs-12 col-sm-3"></div>');
            var file_label = $('<label for="model_'+ name +'_'+ id +'"><?php echo $lang_arr['choose_file']?></label>');
            var file_input = $('<input type="file" name="model_'+ name +'_'+ id +'" id="model_'+ name +'_'+ id +'" accept=".fbx">');

            var min_width_wrapper = $('<div class="form-group col-xs-12 col-sm-3"></div>');
            var min_width_label = $('<label for="min_width_'+name+'_'+ id +'"><?php echo $lang_arr['min_width']?> (мм)</label>');
            var min_width_input = $('<input type="number" name="min_width_'+name+'_'+ id +'" id="min_width_'+name+'_'+ id +'"  value="0" class="form-control">');

            var min_height_wrapper = $('<div class="form-group col-xs-12 col-sm-4"></div>');
            var min_height_label = $('<label for="min_height_'+name+'_'+ id +'"><?php echo $lang_arr['min_height']?> (мм)</label>');
            var min_height_input = $('<input type="number" name="min_height_'+name+'_'+ id +'" id="min_height_'+name+'_'+ id +'"  value="0" class="form-control">');

            var test_wrapper = $('<div class="form-group col-xs-2"></div>');
            var test_label = $('<label class="l_hack">1</label>');
            var test_button = $('<button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_'+ name +'_'+ id +'">Тест модели</button>')


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

                var modal = $('.three_modal_wrapper');
                modal.fadeIn();


                var file = $(this).parent().parent().find('input[type="file"]');


                var reader = new FileReader();

                reader.onload = function(event) {

                    init_three_test('three_viewport', event.target.result);
                };

                reader.readAsDataURL(file[0].files[0]);

                // init_three_test('three_viewport', file[0].files[0].name);

               $('.close_three_modal').click(function () {
                   modal.fadeOut();
                   $('#three_viewport').html('');
                   renderer.renderLists.dispose();
               })
            });

            return panel;

        }


        function init_three_test(element_id, model) {
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







            // var geometry = new THREE.BoxGeometry( 450/10, 720/10, 18/10 );
            // material = new THREE.MeshStandardMaterial({
            //     color: '#ffffff',
            //     roughness: 0.8,
            //     metalness: 0
            // });
            // var cube = new THREE.Mesh( geometry, material );
            // scene.add( cube );

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


            loader.load(model, function (obj) {


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
                            color: '#ffffff',
                            roughness: 0.8,
                            metalness: 0
                        });
                    }

                    // if (m.name.indexOf('pat') > -1) {
                    //     material = create_material(objectFindByKey(materials_catalog.items, 'id', 40001))
                    // }


                    // if (m.name.indexOf('back') > -1) {
                    //     material = scope.params.back_material
                    // }



                    mesh = new THREE.Mesh(g, material);

                    mesh.name = m.name;
                    scene.add(mesh);
                }

                // scope.old_rotation = scope.rotation.clone();
                // scope.rotation.set(0, 0, 0);
                // scope.set_size(scope.params.width, scope.params.height, scope.params.thickness);
                // scope.rotation.copy(scope.old_rotation);



            });


            set_size = function () {

                var old_rot = this.model.rotation.clone();

                var world_rot = new THREE.Euler().setFromQuaternion(this.model.getWorldQuaternion().clone());
                this.model.rotation.set(
                    world_rot.x * -1,
                    world_rot.y * -1,
                    world_rot.z * -1
                );

                var scope = this,
                    i,
                    j,
                    v,
                    p,
                    s = new THREE.Vector3(scope.params.width / units, scope.params.height / units, scope.params.thickness / units),
                    box = new THREE.Box3().setFromObject(scope.model).getSize().clone()
                ;

                p = s.clone().sub(box).divideScalar(2);


                for (i = 0; i < scope.model.children.length; i++) {


                    for (j = 0; j < scope.model.children[i].geometry.vertices.length; j++) {
                        v = scope.model.children[i].geometry.vertices[j];

                        if (scope.model.children[i].name.indexOf('x') > -1) {
                            if (v.x < 0) {
                                v.x -= p.x;
                            } else {
                                v.x += p.x;
                            }
                        }

                        if (scope.model.children[i].name.indexOf('y') > -1) {
                            if (v.y < 0) {
                                v.y -= p.y;
                            } else {
                                v.y += p.y;
                            }
                        }

                        if (scope.model.children[i].name.indexOf('z') > -1) {
                            if (v.z < 0) {
                                v.z -= p.z;
                            } else {
                                v.z += p.z;
                            }
                        }
                    }



                }

                this.model.rotation.copy(old_rot);

                if(this.params.radius){
                    box = new THREE.Box3().setFromObject(scope.model).getSize().clone();

                    var cab = get_parent_recursive(this)

                    if(cab.rotation.y === 0 || cab.rotation.y === Math.PI){

                        this.model.scale.x = scope.params.width / units / box.x;
                        this.model.scale.z = this.model.scale.x

                        this.model.rotation.y = -1*this.parent.rotation.y;

                        box = new THREE.Box3().setFromObject(scope.model).getSize().clone();

                        this.model.position.z = box.z / 2;

                        if(this.params.orientation === 'right'){
                            this.model.position.x = -box.x / 2;
                        } else {
                            this.model.position.x = box.x / 2;
                        }

                    } else {

                        this.model.scale.z = scope.params.width / units / box.z;
                        this.model.scale.x = this.model.scale.z

                        this.model.rotation.y = -1*this.parent.rotation.y;



                        box = new THREE.Box3().setFromObject(scope.model).getSize().clone();

                        this.model.position.z = box.x / 2;

                        if(this.params.orientation === 'right'){
                            this.model.position.x = -box.z / 2;
                        } else {
                            this.model.position.x = box.z / 2;
                        }
                    }




                    this.model.rotation.y = 0;
                    //
                    // this.parent.rotation.y = -Math.PI/4
                    console.log(this.parent)
                    this.parent.set_handle_position();


                    function get_parent_recursive(obj) {
                        if(obj.type === 'Cabinet'){
                            return obj
                        } else {
                            return get_parent_recursive(obj.parent)
                        }
                    }

                }




            };

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
        }


        var select = document.getElementById('materials');
        select.size = select.length;

        $('#materials option').mousedown(function (e) {
            e.preventDefault();
            $(this).prop('selected', !$(this).prop('selected'));
            return false;
        });


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

        // var models_list = $('.models_list');
        // var models_count = 0;
        //
        // $('.add_model_button').click(function () {
        //     var panel = $('<div class="panel panel-default"></div>');
        //     var panel_body = $('<div class="panel-body"></div>');
        //     panel.append(panel_body);
        //     panel_body.append();
        //     // models_list.append();
        // });
        //
        // $('.test_1').change(function (e) {
        //
        //     var files = e.currentTarget.files;
        //     var dae_path;
        //
        //     console.log(files);
        // });
        //
        // $('.test_file').click(function () {
        //     console.log($('.test_1')[0].files[0])
        // })
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