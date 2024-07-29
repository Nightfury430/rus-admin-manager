document.addEventListener('DOMContentLoaded', function () {

    Vue.component('v-select', VueSelect.VueSelect)
    Vue.component('draggable', vuedraggable)

    app = new Vue({
        el: '#sub_form',
        data: {
            drag: false,
            accessories: [],
            categories:[],
            category: null,
            active: 1,
            sizes_available: 1,
            price_type: 1,
            variants:[],
            price_pm: 0,
            material_type: 'facade',
            id: 0,
            wall_distance: 0
        },
        beforeMount(){

            let scope = this;

            axios({
                method: 'get',
                url: document.getElementById('ajax_base_url').value + '/decorations/get_categories_ajax'
            }).then(function (msg) {
                console.log(msg.data);
                scope.categories = msg.data;
            }).catch(function () {
                alert('Unknown error')
            });



        },
        mounted(){



            $(document).ready(function () {





                $("#color").spectrum({
                    color: "#ffffff",
                    preferredFormat: "hex",
                    // cancelText: '<?php echo $lang_arr['cancel']?>',
                    // chooseText: '<?php echo $lang_arr['pick']?>',
                    showInput: true,
                    move: function(color) {
                        material.color.set(color.toHexString());
                    },
                    change: function(color) {
                        material.color.set(color.toHexString());
                    }
                });

                $('#transparent').change(function () {
                    if($(this).val() === "1"){
                        material.transparent = true;
                    } else {
                        material.transparent = false;
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

                                var box3 = new THREE.Box3().setFromObject(model);

                                $('#model_width_0').val(Math.round(box3.max.x - box3.min.x) * 10);
                                $('#model_height_0').val(Math.round(box3.max.y - box3.min.y) * 10);
                                $('#model_depth_0').val(Math.round(box3.max.z - box3.min.z) * 10);

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
                                if(obj.children[i].name.indexOf('facmat') !== -1){
                                    obj.children[i].material = new THREE.MeshStandardMaterial({color:'#ff0000'})
                                } else {
                                    obj.children[i].material = material;
                                }
                            }
                        }
                    }
                }


                $('#map').change(function() {
                    var $scope = $(this);
                    if(this.files.length){
                        show_texture_params();
                        if(this.files[0].size > 1048576){
                            // alert('<?php echo $lang_arr['file_too_big_1mb']?>');
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
                                    // alert('<?php echo $lang_arr['power_of_2_warning']?>');
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
                            select.append('<option selected value="'+ scope.find('.width_input').val() + ';' + scope.find('.height_input').val() + ';' + scope.find('.depth_input').val() +'"></option>')
                    });

                    return true;
                });
            });




            function power_of_2(n) {
                if (typeof n !== 'number')
                    return 'Not a number';

                return n && (n & (n - 1)) === 0;
            }

        },
        computed: {
            dragOptions() {
                return {
                    animation: 200,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            }
        },
        methods: {
            add_variant: function () {
                this.variants.push(
                    {
                        id: this.id,
                        name: '',
                        code: '',
                        price: '',
                        width: 0,
                        height: 0,
                        depth: 0
                    }
                )
                this.id++
            },
            remove_variant: function (i) {
                this.variants.splice(i,1)
            }
        }
    });

    function power_of_2(n) {
        if (typeof n !== 'number')
            return 'Not a number';

        return n && (n & (n - 1)) === 0;
    }
});