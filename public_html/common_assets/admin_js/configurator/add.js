

function init_three_test(element_id) {
    console.log(123)



    function getParentUrl() {
        return parent !== window ? document.referrer : null;
    }


        $.when(
            $.ajax({
                type: "get",
                url: "data/materials.json?" + new Date().getTime(),
                data: {
                    data: 'materials'
                },
                success: function (data) {
                    materials_catalog = data;
                }
            }),
            $.ajax({
                type: "get",
                url: "data/glass_materials.json?" + new Date().getTime(),
                success: function (data) {
                    glass_materials_catalog = data;
                }
            }),
            $.ajax({
                type: "get",
                url: "data/modules.json?" + new Date().getTime(),
                data: {
                    data: 'modules'
                },
                success: function (data) {
                    modules_catalog = data;
                }
            }),
            $.ajax({
                type: "get",
                url: "data/facade_categories.json?" + new Date().getTime(),
                data: {
                    data: 'facade_categories'
                },
                success: function (data) {
                    facades_categories = data;
                }
            }),
            $.ajax({
                type: "get",
                url: "data/facade_sets.json?" + new Date().getTime(),

                success: function (data) {
                    facades_sets = data;
                }
            }),

            $.getJSON("data/kitchen_templates.json?" + new Date().getTime(), function (data) {
                kitchen_templates = data;
            }),
            $.getJSON("data/tech_catalog.json?" + new Date().getTime(), function (data) {
                tech_catalog = data;
            }),
            $.getJSON("data/comms_catalog.json?" + new Date().getTime(), function (data) {
                comms_catalog = data;
            }),
            $.getJSON("data/interior_catalog.json?" + new Date().getTime(), function (data) {
                other_models = data;
            }),
            $.getJSON("data/kitchen_models.json?" + new Date().getTime(), function (data) {
                kitchen_styles = data;
            }),
            $.getJSON("data/handles.json?" + new Date().getTime(), function (data) {
                handles_catalog = data;
            }),
            $.getJSON("data/prices.json?" + new Date().getTime(), function (data) {
                prices = data;
            }),
            $.getJSON("data/constructor_settings.json?" + new Date().getTime(), function (data) {
                constructor_settings = data;
                if (constructor_settings.multiple_facades_mode === undefined) {
                    constructor_settings.multiple_facades_mode = 0;
                }
                if (constructor_settings.cornice_available === undefined) {
                    constructor_settings.cornice_available = 1;
                }
                if (constructor_settings.custom_form === undefined) {
                    constructor_settings.custom_form = 0;
                }
                if (constructor_settings.custom_order_url === undefined) {
                    constructor_settings.custom_order_url = '';
                }
                if (constructor_settings.custom_sizes_available === undefined) {
                    constructor_settings.custom_sizes_available = "true";
                }
                if (constructor_settings.default_language === undefined || constructor_settings.default_language == '') {
                    constructor_settings.default_language = "ru";
                }
                if (constructor_settings.price_modificator === undefined) {
                    constructor_settings.price_modificator = 1;
                } else {
                    constructor_settings.price_modificator = parseFloat(constructor_settings.price_modificator);
                }

                if (constructor_settings.facade_style_change_availabale === undefined) {
                    constructor_settings.facade_style_change_availabale = 1;
                } else {
                    constructor_settings.facade_style_change_availabale = parseInt(constructor_settings.facade_style_change_availabale);
                }

                if (constructor_settings.show_specs === undefined) {
                    constructor_settings.show_specs = 1;
                } else {
                    constructor_settings.show_specs = parseInt(constructor_settings.show_specs);
                }

                if (constructor_settings.accessories_shop_enabled === undefined) {
                    constructor_settings.accessories_shop_enabled = 0;
                }

                if (constructor_settings.price_enabled == 0) {
                    constructor_settings.accessories_shop_enabled = 0;
                }

                if (!constructor_settings.decorations_enabled) {
                    constructor_settings.decorations_enabled = 0;
                }

                if (!constructor_settings.allow_common_mode) {
                    constructor_settings.allow_common_mode = 0;
                }

                if (!constructor_settings.frontend_configurator_available) {
                    constructor_settings.frontend_configurator_available = 0;
                } else {
                    constructor_settings.frontend_configurator_available = parseInt(constructor_settings.frontend_configurator_available);
                }

                constructor_settings.furniture_available = 1;

                if(!constructor_settings.furniture_available) constructor_settings.furniture_available = 0;

                if(!constructor_settings.icons_type) constructor_settings.icons_type = 0;

                global_options.forced_common = 0;


            }),
            // fbx_loader.load('/common_assets/models/furni/naves2.fbx', function (mesh) {
            //     furniture_models.naves = mesh.children[0].geometry;
            //     furniture_models.naves.sizes = box3.setFromObject(mesh).getSize(vec3).clone().divideScalar(100)
            // }),
            fbx_loader.load('/common_assets/models/furni/hinge_door_2.fbx', function (mesh) {
                furniture_models.hinge_door = mesh.children[0].geometry;
                furniture_models.hinge_door.sizes = box3.setFromObject(mesh).getSize(vec3).clone().divideScalar(100)

            }),
            fbx_loader.load('/common_assets/models/furni/hinge_facade.fbx', function (mesh) {
                furniture_models.hinge_facade = mesh.children[0].geometry;
                furniture_models.hinge_facade.sizes = box3.setFromObject(mesh).getSize(vec3).clone().divideScalar(100)

            }),

            fbx_loader.load('/common_assets/models/furni/napr_corp.fbx', function (mesh) {
                furniture_models.napr_corp = mesh.children[0].geometry;
                furniture_models.napr_corp.sizes = box3.setFromObject(mesh).getSize(vec3).clone().divideScalar(100)
            }),
            fbx_loader.load('/common_assets/models/furni/napr_locker.fbx', function (mesh) {
                furniture_models.napr_locker = mesh.children[0].geometry;
                furniture_models.napr_locker.sizes = box3.setFromObject(mesh).getSize(vec3).clone().divideScalar(100)
            })
            // fbx_loader.load('/common_assets/models/furni/aventos_hf.fbx', function (mesh) {
            //     furniture_models.aventos_hf_corp = mesh.children[0].geometry;
            //     furniture_models.aventos_hf_corp.sizes = box3.setFromObject(mesh.children[0]).getSize(vec3).clone().divideScalar(100);
            //
            //     furniture_models.aventos_hf_door = mesh.children[2].geometry;
            //     furniture_models.aventos_hf_door.sizes = box3.setFromObject(mesh.children[2]).getSize(vec3).clone().divideScalar(100);
            //
            //     furniture_models.aventos_hf_mech = mesh.children[1].geometry;
            //     furniture_models.aventos_hf_mech.sizes = box3.setFromObject(mesh.children[1]).getSize(vec3).clone().divideScalar(100);
            // })


        ).done(function () {

            let ps_url = "data/project_settings.json?" + new Date().getTime();

            if (getUrlParameter('forced_common')) {
                ps_url = "data/project_settings_common.json?" + new Date().getTime()

                constructor_settings.shop_mode = 0;
                constructor_settings.decorations_enabled = 1;
                constructor_settings.facade_style_change_availabale = 1;
                constructor_settings.facades_system_available = 1;
                constructor_settings.frontend_configurator_available = 1;
                constructor_settings.multiple_facades_mode = 0;

                global_options.forced_common = 1;
            }

            let lang_url = "/common_assets/data/languages.json?";

            if(constructor_settings.default_language === 'custom'){
                lang_url = 'data/language.json?';
            }



            $.when(
                $.getJSON(lang_url + new Date().getTime(), function (data) {
                    if (constructor_settings.default_language === 'custom') {
                        lang = data;
                    } else {
                        languages = data;
                    }

                }),
                $.ajax({
                    type: "get",
                    url: ps_url,
                    data: {
                        data: 'project_settings'
                    },
                    success: function (data) {
                        project_settings = data;


                        if (project_settings.bottom_as_top_corpus_materials === undefined) {
                            project_settings.bottom_as_top_corpus_materials = true;
                        }

                        if (project_settings.bottom_as_top_facade_materials === undefined) {
                            project_settings.bottom_as_top_facade_materials = true;
                        }

                        if (project_settings.bottom_as_top_facade_models === undefined) {
                            project_settings.bottom_as_top_facade_models = true;
                        }

                        if (project_settings.bottom_modules_height === undefined) {
                            project_settings.bottom_modules_height = 720;
                        }

                        if (project_settings.cokol_as_corpus === undefined) {
                            project_settings.cokol_as_corpus = true;
                        }

                        if (project_settings.cokol_height === undefined) {
                            project_settings.cokol_height = 120;
                        }

                        if (project_settings.cornice_active === undefined) {
                            project_settings.cornice_active = false;
                        }

                        if (project_settings.cornice_available === undefined) {
                            project_settings.cornice_available = constructor_settings.cornice_available;
                        }

                        if (project_settings.corpus_thickness === undefined) {
                            project_settings.corpus_thickness = 16;
                        }

                        if (project_settings.door_offset === undefined) {
                            project_settings.door_offset = 2;
                        }



                        if (project_settings.is_cokol_active === undefined) {
                            project_settings.is_cokol_active = true;
                        }
                        if (project_settings.handle.no_handle === undefined) {
                            project_settings.handle.no_handle = false;
                        }


                        if (project_settings.materials.cokol === undefined) {

                        }





                        if (project_settings.selected_materials.pat === undefined) {
                            project_settings.selected_materials.pat = 731;
                        }

                        if (project_settings.materials.pat === undefined) {
                            project_settings.materials.pat = project_settings.materials.top.facades;
                        }

                        if (project_settings.handle.lockers_position === 'center') {
                            project_settings.handle.lockers_position = 'middle';
                        }

                        project_settings.accessories = cart.accessories;

                        if (
                            project_settings.decorations === undefined && constructor_settings.shop_mode != 1 && constructor_settings.decorations_enabled == 1
                        ) {
                            project_settings.decorations = decorations
                        }


                        if (project_settings.models.top === undefined) project_settings.models.top = facades_sets[0].id;
                        if (project_settings.models.bottom === undefined) project_settings.models.bottom = facades_sets[0].id;

                        // temp_project_data.facades.top = find_obj_by_id(facades_sets, project_settings.models.top);
                        // temp_project_data.facades.bottom = find_obj_by_id(facades_sets, project_settings.models.bottom);
                        //
                        // temp_project_data.facades.top.category_data = find_obj_by_id(facades_categories, temp_project_data.facades.top.category)
                        // temp_project_data.facades.bottom.category_data = find_obj_by_id(facades_categories, temp_project_data.facades.bottom.category)

                        get_temp_project_data_facades(find_obj_by_id(facades_sets, project_settings.models.top), 'top');
                        get_temp_project_data_facades(find_obj_by_id(facades_sets, project_settings.models.bottom), 'bottom');


                        if (!project_settings.handle.model) {
                            project_settings.handle.model = handles_catalog.items[handles_catalog.items.length - 1];
                        }

                        if (!project_settings.handle.model.is_gola) {
                            project_settings.handle.model.is_gola = false;
                        }

                        if(!project_settings.selected_materials.top.facades_rotated) project_settings.selected_materials.top.facades_rotated = false;
                        if(!project_settings.selected_materials.bottom.facades_rotated) project_settings.selected_materials.bottom.facades_rotated = false;


                        if(custom_lockers === true){
                            project_settings.lo_type = 'custom';
                        }


                        project_settings.shelve_offset = 15;
                        // project_settings.materials.glass = [1,3];
                        // project_settings.selected_materials.glass = glass_materials_catalog.items[0].id;

                    }
                })
            ).done(function () {

                $.ajax({
                    type: "get",
                    // url: "/common_assets/fonts/ptsans.txt",
                    url: "/common_assets/fonts/arial.txt",
                    success: function (data) {
                        pdf_font = data;
                    }
                })

                if(window.location.href.indexOf('3dkitchen.project') > -1){



                    init(element_id);
                    // interface_init();



                } else {
                    try {
                        init(element_id);
                        // interface_init();
                    } catch (err) {
                        //console.log(err);
                        if($('.alignnone').length !== 1){
                            $('body').html('').append( '<p style="text-align: center; padding-top: 190px; font-size: 20px;">'+ lang['error_constructor_init'] +' support@bplanner.me</p><p style="text-align: center">'+ lang['error_constructor_screen'] +': <br>'+ err +'</p>' );
                        }

                    }
                }

                if (project_settings.is_kitchen_model == true) {
                    change_kitchen_style(project_settings.id, true, false)

                }

                //console.log('----' + global_options.mashprom_mode);

                if(!global_options.mashprom_mode) $('.preloader').hide();



                let t1 = performance.now();
                //console.log("bt " + (t1 - t0) + " milliseconds.");

            })

        });



}


function init() {
    state_panel = document.getElementById('state_panel');


    var viewport = document.getElementById(element_id);

    scene = new THREE.Scene();
    scene.fog = new THREE.Fog(0xFFFFFF, 500, 10000);
    camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

    renderer = new THREE.WebGLRenderer({
        antialias: true,
        preserveDrawingBuffer: true
    });
    renderer.setClearColor(scene.fog.color);
    renderer.setSize( viewport.clientWidth, viewport.clientHeight );

    viewport.appendChild( renderer.domElement );

    fbx_manager = new THREE.LoadingManager();
    loader = new THREE.FBXLoader(fbx_manager);




    amb_light = new THREE.AmbientLight(0xffffff, 0.03);
    scene.add(amb_light);






    room = new Room_new({
        width: 200 * units,
        height: 200 * units,
        depth: 200 * units
    });



    scene.add(room);






    let errors = [];





    controls = new THREE.OrbitControls(camera, renderer.domElement);

    set_configurator_mode();


    var animate = function () {
        requestAnimationFrame( animate );

        // cube.rotation.x += 0.01;
        // cube.rotation.y += 0.01;

        renderer.render( scene, camera );
    };

    animate();

}