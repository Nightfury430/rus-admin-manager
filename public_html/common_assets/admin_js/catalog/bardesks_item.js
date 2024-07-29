let app;
let data = {};
let materials = {};
let categories;
let item = null;
let decor_data = null;



document.addEventListener('Glob_ready', async function () {

    let requests = [];
    requests.push(promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name))
    requests.push(promise_request(glob.base_url + '/catalog/items_get/material_types'))
    requests.push(promise_request(glob.base_url + '/materials/get_all_data_ajax'))

    requests.push(promise_request(glob.base_url + '/catalog/categories_get/facades'));
    requests.push(promise_request(glob.base_url + '/catalog/items_get/facades'))

    requests.push(promise_request(glob.base_url + '/catalog/categories_get/handles'));
    requests.push(promise_request(glob.base_url + '/catalog/items_get/handles'))

    requests.push(promise_request(glob.base_url + '/catalog/categories_get/model3d'));
    requests.push(promise_request(glob.base_url + '/catalog/items_get/model3d'))

    requests.push(promise_request(glob.base_url + '/catalog/categories_get/bardesks'));
    requests.push(promise_request(glob.base_url + '/catalog/items_get/bardesks'))
    // requests.push(promise_request(glob.base_url + '/catalog/items_get/lockers'))

    // requests.push(promise_request(glob.base_url + '/catalog/items_get/handles'))

    let item_id_index = 0;

    if(glob.item_id != 0){
        item_id_index = requests.length;
        requests.push(promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id))
    }

    let result = await Promise.all(requests)


    cat_data = result[0];
    cat_hash = get_hash(cat_data);
    cat_ordered = flatten(create_tree(cat_data));

    let mats = result[1]

    for (let i = 0; i < mats.length; i++){
        let it = mats[i]
        it.data = JSON.parse(it.data);

        if(!it.data.variants[it.data.default]){
            it.data.default = Object.keys(it.data.variants)[0]

        }

        materials[it.key] = it.data;
    }




    decor_data = result[2];

    let category_0 = {
        id:0,
        parent: 0,
        code:'',
        name: 'Категория в базе не найдена',
        items: [],
        categories: []
    }
    let material_0 = {
        id:0,
        category: 0,
        code:'',
        name: 'Материал не найден',
        params:{
            color: '#ff0000',
            transparent: true,
            opacity:0.3
        },
        add_params:{}
    }

    for (let i = decor_data.items.length; i--;){
        if(decor_data.items[i].params) continue;
        decor_data.items[i].params = {};
        decor_data.items[i].params.color = decor_data.items[i].color;
        decor_data.items[i].params.map = decor_data.items[i].map;
        decor_data.items[i].params.metalness = decor_data.items[i].metalness;
        decor_data.items[i].params.roughness = decor_data.items[i].roughness;
        decor_data.items[i].params.transparent = decor_data.items[i].transparent;

        decor_data.items[i].add_params = {};
        decor_data.items[i].add_params.real_height = decor_data.items[i].real_height;
        decor_data.items[i].add_params.real_width = decor_data.items[i].real_width;
        decor_data.items[i].add_params.stretch_height = decor_data.items[i].stretch_height;
        decor_data.items[i].add_params.stretch_width = decor_data.items[i].stretch_width;
        decor_data.items[i].add_params.wrapping = decor_data.items[i].wrapping;
    }

    decor_categories_hash = get_hash(decor_data.categories);
    decor_categories_ordered = flatten(create_tree(decor_data.categories));


    facades_data = {
        categories: result[3],
        items: result[4]
    }

    facades_hash = get_hash(facades_data.items);
    facades_cat_hash = get_hash(facades_data.categories);

    for(let i = 0; i < facades_data.items.length; i++){
        facades_data.items[i].data = JSON.parse(facades_data.items[i].data)
        facades_data.items[i].materials = JSON.parse(facades_data.items[i].materials)
    }

    model_data = {
        categories: result[7],
        items: result[8]
    }

    for(let i = 0; i < model_data.items.length; i++){
        model_data.items[i].params = JSON.parse(model_data.items[i].data)
    }

    catalogue_data = {
        categories: result[9],
        items: result[10]
    }

    for(let i = 0; i < catalogue_data.items.length; i++){
        catalogue_data.items[i].params = JSON.parse(catalogue_data.items[i].data)
    }

    // lockers_data = {
    //     categories: [],
    //     items: result[11]
    // }
    //
    // for(let i = 0; i < lockers_data.items.length; i++){
    //     lockers_data.items[i] = JSON.parse(lockers_data.items[i].data)
    // }

    // handles_data = {
    //     categories: [],
    //     items: result[12]
    // }
    //
    // for(let i = 0; i < handles_data.items.length; i++){
    //     handles_data.items[i].material = JSON.parse(handles_data.items[i].material)
    //     handles_data.items[i].sizes = JSON.parse(handles_data.items[i].variants)
    // }


    if(glob.item_id != 0){
        item = result[item_id_index]
    }


    init_vue();

})

let var_params = {
    number: {
        min: 1,
        max: '',
        step: 1,
    },
    select: {
        options: []
    },
    boolean: {}
}


function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components:{
            'v-select': VueSelect.VueSelect
        },
        data: {
            active: 1,
            drag: false,
            item:{
                id: 0,
                icon: '',
                name: '',
                order: 0,
                active: 1,
                category: [],
                params: copy_object(c_params_n),
            },

            use_facades_rest: 0,
            rest_facades: [],
            rest_facade_default: 0,
            rest: {
                size: {
                    min_x: 0,
                    max_x: 0,
                    min_y: 0,
                    max_y: 0,
                    min_z: 0,
                    max_z: 0
                },
                change_facade_types: 1,
                free_size: 0,
                facades: null,
                handles: null
            },

            edges: {
                is_on: false,
                transparent: false

            },

            errors: [],
            show_success_message: false,
        },
        computed:{

        },
        mounted: function(){
            let scope = this;
            let inter = setInterval(function () {

                if(scene !== undefined){

                    // custom_settings.forced_mat_opacity = true;

                    clearInterval(inter);
                    set_obj.materials = materials;

                    if(!set_obj.materials['__tabletop']){
                        set_obj.materials['__tabletop'] = {
                            "name": "Столешница",
                            "type": "m2",
                            "default": "v_16",
                            "key": "__tabletop",
                            "variants": {
                                "v_16": {
                                    "name": "Столешница",
                                    "key": "v_16",
                                    "id": project_settings.selected_materials.tabletop,
                                    "size": {
                                        "x": 0,
                                        "y": 0,
                                        "z": project_settings.tabletop_thickness
                                    },
                                    "categories": project_settings.materials.tabletop,
                                },
                            },
                            "id": "99999"
                        }
                    }


                    // materials_lib = new lib(material_0, category_0);
                    convert_lib(materials_lib, decor_data)

                    materials_catalog = decor_data;
                    materials_catalog.categories.push({
                        id: 0,
                        name: 'Без категории'
                    })
                    materials_catalog.items.push({
                        id: 0,
                        category: 0,
                        name: 'Материал не найден',
                        code: '',
                        params: {
                            color: '#ffffff'
                        },
                        add_params: {}
                    })

                    models3d_lib = new lib(material_0, category_0)
                    convert_lib(models3d_lib, model_data)

                    // catalog_lib = new lib(material_0, category_0)
                    // convert_lib(catalog_lib, catalogue_data)

                    // lockers_lib = new lib(lockers_0, category_0)
                    // convert_lib(lockers_lib, lockers_data)
                    //
                    // handles_lib = new lib(handles_0, category_0)
                    // convert_lib(handles_lib, handles_data)



                    let params;

                    if(item != null){
                        params = JSON.parse(item.data);
                        ce();
                        params = _.merge(copy_object(c_params_n), params)

                        Vue.set(scope.item, 'params', params)

                        console.log(params)
                        scope.item.active = item.active;
                        scope.item.icon = item.icon;
                        if(!item.order) item.order = 0;

                        if(params.spec.variants){
                            console.log(params.spec.variants)
                            for (let i = params.spec.variants.length; i--; ){
                                if(!params.spec.variants[i]){
                                    params.spec.variants.splice(i,1);
                                    continue;
                                }
                                if(params.spec.variants[i].variables.length !== undefined){
                                    params.spec.variants[i].variables = {};
                                }
                            }


                        }

                        if(params.variables.length !== undefined){
                            params.variables = {};
                        }

                        scope.item.order = item.order;
                        scope.item.name = params.spec.name;
                        scope.item.category = JSON.parse(item.category);
                        console.log(item)
                        if(params.spec.rest.change_facade_types === undefined)
                            params.spec.rest.change_facade_types = 1
                        if(params.spec.rest.free_size === undefined) {
                            params.spec.rest.free_size = 1
                        }
                        scope.rest = copy_object(params.spec.rest)

                    } else {
                        scope.item.params.spec.materials.push({key:'ldsp'})
                        params = scope.get_data().params
                    }



                    cabinet = sc.add_object('cabinet_cn', params, false);
                    cabinet.position.set(100, 0, 100)
                    cabinet.box.update();
                    // let s = cabinet.box.getSize();
                    // let shel = make_s_helper(s.x, s.y, s.z)
                    // shel.userData.renderable = false;
                    // shel.renderOrder = 1;
                    // scene.add(shel)
                    single_preview(true, cabinet)

                    scope.$selected_object = cabinet;
                    scope.$emit('set_selected', cabinet)

                    scope.get_rest_facades();
                    scope.resize_viewport();

                }

            }, 100)

        },
        created: function(){
            this.$options.cat_ordered = cat_ordered;
            this.$options.facades_data = facades_data;
            this.$options.facades_hash = facades_hash;
            this.$options.facades_cat_hash = facades_cat_hash;
        },
        methods: {

            edges_mode() {
                console.log(this.edges.is_on)
                this.edges.is_on = !this.edges.is_on
                if (this.edges.is_on) {
                    global_options.mode = 'edges';
                } else {
                    global_options.mode = 'design';
                    this.edges.transparent = false;
                    global_options.edges_transparent = false
                    transparent_edges(global_options.edges_transparent)
                }

                room.change_mode();
                room.hide();
            },

            edges_transparent() {

                if( !this.edges.is_on){
                    this.edges.is_on = true
                    global_options.mode = 'edges';
                    room.change_mode();
                    room.hide();
                }

                this.edges.transparent = !this.edges.transparent
                global_options.edges_transparent = this.edges.transparent
                transparent_edges(global_options.edges_transparent)
            },

            reset_camera(){
                single_preview(true, cabinet)
            },

            change_is_fill(){
                this.$selected_object.userData.params.spec.is_fill = this.item.params.spec.is_fill;
            },

            get_rest_facades: function(){
                let scope = this;
                let fac = this.$selected_object.userData.params.spec.rest.facades;
                if(fac != null){

                    for (let i = fac.items.length; i--;){
                        if(!this.$options.facades_hash[fac.items[i]]){
                            fac.items.splice(i, 1);
                        }
                    }

                    if(fac.items.length > 0){
                        this.use_facades_rest = 1;
                        this.rest_facades = copy_object(fac.items);
                        this.rest_facade_default = copy_object(fac.default);
                    }
                }
            },

            change_rest_facades(){
                let obj = {
                    "mode": "i",
                    "default": this.rest_facade_default,
                    "items": copy_object(this.rest_facades)
                }


                this.$selected_object.userData.params.spec.rest.facades = obj
            },

            change_rest_facades_on(){
                if(this.use_facades_rest == 0){
                    this.$selected_object.userData.params.spec.rest.facades = null
                } else {
                    this.change_rest_facades();
                }
            },

            change_rest(key, val){
                this.$selected_object.userData.params.spec.rest[key] = val
            },

            change_name: function(){
                cabinet.userData.params.spec.name = this.item.name;
            },

            submit: async function (e) {
                e.preventDefault();
                let scope = this;

                let params = cabinet.gp();

                delete params.size
                delete params.obj_id
                delete params.object_type
                delete params.materials
                delete params.spec.facades

                // console.log(params)
                // return

                let data = this.get_data();
                data.params = params;

                let errors = [];
                if(errors.length) {
                    alert(errors.join('\n'))
                    return
                }


                let form_data = new FormData();
                form_data.append('data', JSON.stringify(data));

                let url = this.$refs.submit_url.value


                let res = await promise_request_post(url, form_data)
                if(res == 'ok'){
                    window.location = document.getElementById('form_success_url').value
                }

                return false;
            },



            make_icon: function(gray){
                if(!cabinet) return;
                let scope = this;
                let camera_pos = camera.position.clone();
                let controls_target = controls.target.clone();
                let cab_pos = cabinet.position.clone();

                if(gray){
                    // let mt = materials_lib.items[1];
                    // mt.params.color = '#545454';
                    // mt.params.transparent = true;
                    // mt.params.opacity = 0.7;
                    //
                    // gray_mat.transparent = true;
                    // gray_mat.opacity = 0.7;
                    //
                    // cabinet.change_facade_material({id:1,group:'all'})
                }

                // this.set_design_mode();

                controls.target.set(
                    0,
                    0,
                    0
                );
                camera.position.set(
                    140,
                    64,
                    205
                );

                // if(cabinet.params.cabinet.type === 'corner' || cabinet.params.cabinet.type === 'corner_90'){
                //     cabinet.rotation.y = Math.PI/3;
                // }
                //
                // if (cabinet.params.cabinet.orientation === 'left'){
                //     camera.position.set(
                //         -140,
                //         64,
                //         205
                //     );
                // }

                dControls.s_helper.visible = false
                let box = new THREE.Box3().setFromObject(cabinet).getSize();
                cabinet.position.set(
                    0,- box.y / 2,0
                );

                hide_sizes();
                // room.hide();
                controls.update();


                setTimeout(function () {

                    let canvas = document.createElement('canvas');


                    if (cabinet.userData.params.size.y  < 1250){
                        canvas.width = 200;
                        canvas.height = 200;
                        let ctx = canvas.getContext('2d');

                        let left0 = renderer.domElement.width / 2 - 110;
                        let top0 = renderer.domElement.height / 2 - 110;

                        // if(
                        //     cabinet.params.cabinet.type == 'corner' ||
                        //     cabinet.params.cabinet.type == 'corner_90' ||
                        //     cabinet.params.cabinet.type == 'corner_straight'
                        // ) {
                        //     left0-=10
                        //     top0-=10
                        //
                        //     ctx.drawImage(renderer.domElement,left0,top0 + 20,250,250,0,0,200,200);
                        // } else{
                        ctx.drawImage(renderer.domElement,left0,top0+20,220,220,0,0,200,200);
                        // }

                    }

                    if(cabinet.userData.params.size.y  >= 1250){
                        canvas.width = 200;
                        canvas.height = 400;
                        let ctx = canvas.getContext('2d');

                        let left0 = renderer.domElement.width / 2 - 125;
                        let top0 = renderer.domElement.height / 2 - 250;


                        ctx.drawImage(renderer.domElement,left0,top0,250,500,0,0,200,400);
                    }
                    if(cabinet.userData.params.size.y >= 2200){
                        canvas.width = 200;
                        canvas.height = 400;
                        let ctx = canvas.getContext('2d');

                        let left0 = renderer.domElement.width / 2 - 150;
                        let top0 = renderer.domElement.height / 2 - 300;


                        ctx.drawImage(renderer.domElement,left0,top0,300,600,0,0,200,400);
                    }







                    // let data = canvas.toDataURL('image/jpeg', 0.8);

                    let data = canvas.toDataURL('image/jpeg', 1);
                    var link = document.createElement("a");
                    link.download = "image.jpeg";
                    console.log(data)


                    link.download = location.href.split('/').pop() + '_' + 'icon_common.png'

                    // if(common){
                    //     link.download = location.href.split('/').pop() + '_' + 'icon_common.png'
                    // } else {
                    //     link.download = location.href.split('/').pop() + '_' + 'icon_type_'+ type +'.png'
                    // }


                    canvas.toBlob(function (blob) {
                        link.href = URL.createObjectURL(blob);
                        link.click();
                    }, 'image/png');

                    // scope.item.icon_file = data;

                    controls.target.set(controls_target.x, controls_target.y, controls_target.z);
                    camera.position.set(camera_pos.x, camera_pos.y, camera_pos.z);
                    cabinet.position.set(cab_pos.x, cab_pos.y, cab_pos.z);
                    // room.show();
                    controls.update();
                    // show_sizes();

                    if(gray){
                        // let mt = materials_lib.items[1];
                        // mt.params.color = '#932129';
                        // mt.params.transparent = false;
                        //
                        // gray_mat.transparent = false;
                        //
                        // cabinet.change_facade_material({id:1,group:'all'})
                    }

                    // scope.set_edit_mode();

                },1000)

            },

            set_edit_mode: function(){
                if(cabinet){
                    global_options.mode = 'edges';
                    transparent_edges(true);
                    cabinet.edges_mode();
                }

            },
            set_design_mode: function(){
                if(cabinet) {
                    global_options.mode = 'design';
                    cabinet.build();
                }
            },

            resize_viewport: function(){
                document.getElementById('main_app').style.display = 'block'
                document.getElementById('preview_block').append(document.getElementById('main_app'))
                setTimeout(function () {
                    resize_viewport();
                },10)
            },

            sel_file: function(file){
                if(!glob.is_common){
                    file.true_path = file.true_path.substr(1);
                }
                this.item.icon = file.true_path

                $('#filemanager').modal('toggle')
            },

            correct_url: function(path){
                if(!path) return '';
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return glob.acc_url + path;
                }
            },

            get_data: function(){
                return JSON.parse(JSON.stringify(this.item));
            },
            mi: function(){
                controls.target.set(
                    0,
                    0,
                    0
                );
                camera.position.set(
                    140,
                    65,
                    225
                );




                dControls.s_helper.visible = false
                // hide_sizes();
                cabinet.hide_sizes();
                // // room.hide();
                controls.update();

                fitCameraToSelection(camera, controls, [cabinet], 1.2);
                renderer.render(scene, camera)

                let canvas = document.createElement('canvas');
                canvas.width = 400;
                canvas.height = 400;
                let ctx = canvas.getContext('2d');
                ctx.drawImage(renderer.domElement,0,0,renderer.domElement.width,renderer.domElement.height,0,0,400,400);
                let data = canvas.toDataURL('image/jpeg', 1);
                var link = document.createElement("a");
                link.download = "image.jpeg";
                console.log(data)


                link.download = location.href.split('/').pop() + '_' + 'icon_common.jpg'

                canvas.toBlob(function (blob) {
                    link.href = URL.createObjectURL(blob);
                    link.click();
                }, 'image/jpeg');

                cabinet.show_sizes();


            }

        }
    });
}



function fitCameraToSelection(camera, controls, selection, fitOffset = 1.2) {
    const size = new THREE.Vector3();
    const center = new THREE.Vector3();
    const box = new THREE.Box3();
    box.makeEmpty();
    for(const object of selection) {
        box.expandByObject(object.userData.object);
    }
    selection[0].box.update()
    // box.getSize(size);
    // box.getCenter(center );
    // const helper = new THREE.Box3Helper( selection[0].box, 0xffff00 );
    // scene.add( helper );

    selection[0].box.getSize(size);
    selection[0].box.getCenter(center);

    console.log(size)
    console.log(center)

    const maxSize = Math.max(size.x, size.y, size.z);
    const fitHeightDistance = maxSize / (2 * Math.atan(Math.PI * camera.fov / 360));
    const fitWidthDistance = fitHeightDistance / camera.aspect;
    const distance = fitOffset * Math.max(fitHeightDistance, fitWidthDistance);

    const direction = controls.target.clone()
        .sub(camera.position)
        .normalize()
        .multiplyScalar(distance);

    controls.maxDistance = distance * 10;
    controls.target.copy(center);

    camera.near = distance / 100;
    camera.far = distance * 100;
    camera.updateProjectionMatrix();

    camera.position.copy(controls.target).sub(direction);

    controls.update();
}