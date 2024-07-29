let cat_data = null;
let cat_hash = null;
let cat_ordered = null;
let item_data = null;
let preview_model = null;

let scene = null;
let camera = null;
let renderer = null;
let viewport = null;

let loader = null;

document.addEventListener('Glob_ready', async function () {
    let mat_params = {
        type: 'Standart',
        params:{
            color: '#ffffff',
            roughness: 0.8,
            metalness: 0,
            map: '',
            normalMap: '',
            transparent: 0,
        },
        add_params:{
            stretch_width: 0,
            stretch_height: 0,
            real_width: 0,
            real_height: 0,
            wrapping: 'mirror'
        }
    }

    let promises = [];
    promises.push(promise_request(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name))
    if(glob.item_id != 0) promises.push(promise_request(glob.base_url + '/catalog/' + glob.i_method_name + '/' + glob.controller_name + '/' + glob.item_id))
    let data = await Promise.all(promises)

    cat_data = data[0];
    cat_hash = get_hash(cat_data);
    cat_ordered = flatten(create_tree(cat_data));


    if(glob.item_id != 0){
        item_data = data[1];


        if(!cat_hash[item_data.category]) item_data.category = 0


        item_data.material = JSON.parse(item_data.material)

        item_data.material = deepAssign(mat_params, item_data.material)

        item_data.variants = JSON.parse(item_data.variants)

        for (let i = 0; i < item_data.variants.length; i++){
            if(!item_data.variants[i].offset_y) item_data.variants[i].offset_y = 0;
            if(!item_data.variants[i].name) item_data.variants[i].name = '';
        }

    }

    init_vue()

    console.log(glob)
    console.log(copy_object(item_data))
})

function init_vue() {
    app = new Vue({
        el: '#sub_form',
        components:{
            'v-select': VueSelect.VueSelect,
            draggable: vuedraggable
        },
        data: {
            active: 1,
            drag: false,
            item:{
                id: 0,
                name: '',
                type: 'common',
                order: 100000,
                icon: '',
                model: '',
                category: 0,
                code: '',
                active: 1,
                variants:[
                    {
                        name: '',
                        axis_size: 0,
                        code: '',
                        depth: 0,
                        height: 0,
                        price: 0,
                        width: 0,
                        offset_y: 0
                    }
                ],
                material:{
                    type: 'Standart',
                    params:{
                        color: '#ffffff',
                        roughness: 0.8,
                        metalness: 0,
                        map: '',
                        normalMap: '',
                    },
                    add_params:{
                        stretch_width: 0,
                        stretch_height: 0,
                        real_width: 0,
                        real_height: 0,
                        wrapping: 'mirror'
                    }
                }

            },
            errors: [],
            show_success_message: false,
            file_target: null
        },
        computed:{
            dragOptions() {
                return {
                    animation: 500,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },
            computed_mat_params: function () {
                let params = JSON.parse(JSON.stringify(this.item.material));
                params.add_params.model_width = this.item.variants[0].width;
                params.add_params.model_height = this.item.variants[0].height;
                if(params.params.map != '') params.params.map = this.correct_download_url(params.params.map)
                if(params.params.normalMap != '') params.params.normalMap = this.correct_download_url(params.params.normalMap)
                return params
            },
        },
        mounted: function(){
            let scope = this;

            $("#color").spectrum({
                color: scope.item.material.params.color,
                preferredFormat: "hex",
                showInput: true,
                move: function(color) {
                    scope.item.material.params.color = color.toHexString();
                    if(preview_model) change_material(preview_model, create_material(scope.computed_mat_params))
                },
                change: function(color) {
                    scope.item.material.params.color = color.toHexString();
                    if(preview_model) change_material(preview_model, create_material(scope.computed_mat_params))
                }
            });
        },
        created: function(){
            this.$options.cat_ordered = cat_ordered;
            if(glob.item_id != 0){
                Vue.set(this, 'item', item_data)
            }
        },
        methods: {

            sel_file: function(file){

                if(!glob.is_common){
                    file.true_path = file.true_path.substr(1);
                }

                switch (this.file_target) {
                    case 'icon':
                        this.item[this.file_target] = file.true_path
                        break;
                    case 'map':
                        this.item.material.params.map = file.true_path;
                        this.material_change();
                        break;
                    case 'normalMap':
                        this.item.material.params.normalMap = file.true_path;
                        this.material_change();
                        break;
                    case 'model':
                        console.log(file)
                        this.item.model = file.true_path;
                        change_model(true);
                        break;
                }

                $('#filemanager').modal('toggle')

                this.file_target = null;
            },

            correct_url: function(path){
                if(path == '') return '/common_assets/images/placeholders/256x256.png'

                let date_time = new Date().getTime();

                if(path.indexOf('common_assets') > -1){
                    return path +  '?' + date_time
                } else {
                    return glob.acc_url + path +  '?' + date_time;
                }
            },
            correct_model_url: function(path){
                if(path.indexOf('common_assets') > -1){
                    return path.split('/').pop()
                } else {
                    return path
                }
            },
            correct_download_url(path){
                if(path.indexOf('common_assets') > -1){
                    return path
                } else {
                    return glob.acc_url  + path
                }
            },


            on_drag({ relatedContext, draggedContext }) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;

                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            },

            material_change(){
                if(preview_model) change_material(preview_model, create_material(this.computed_mat_params))
            },



            resize_viewport(){
                document.getElementById('main_app').style.display = 'block'
                document.getElementById('preview_block').append(document.getElementById('main_app'))
                setTimeout(function () {
                    resize_viewport();
                },10)
            },

            get_data: function () {
                return JSON.parse(JSON.stringify(this.item));
            },
            set_data: function(key, data){
                Vue.set(this.item, key, data)
            },

            add_variant:function () {
                this.item.variants.push(
                    {
                        name: '',
                        axis_size: 0,
                        code: '',
                        depth: 0,
                        height: 0,
                        price: 0,
                        width: 0,
                        offset_y: 0
                    }
                )
            },
            remove_variant(index){
                this.item.variants.splice(index,1);
            },

            download_assets: async function(){

                let zip = new JSZip();

                let params = this.get_data();


                let map_path = '';
                let files = [];
                if(params.material.params.map){
                    if(params.material.params.map.indexOf('common_assets') > -1){
                        map_path = params.material.params.map;
                    } else {
                        map_path = glob.acc_url + params.material.params.map;
                    }
                }

                if(map_path != ''){
                    let obj = {};
                    obj.file = await get_binary(map_path);
                    let arr = map_path.split('/');
                    obj.name = arr[arr.length-1];
                    files.push(obj);
                }


                if(params.material.params.normalMap){
                    if(params.material.params.normalMap.indexOf('common_assets') > -1){
                        map_path = params.material.params.normalMap;
                    } else {
                        map_path = glob.acc_url + params.material.params.normalMap;
                    }
                }

                if(map_path != ''){
                    let obj = {};
                    obj.file = await get_binary(map_path);
                    let arr = map_path.split('/');
                    obj.name = arr[arr.length-1];
                    files.push(obj);
                }


                if(params.model){
                        let model = params.model;
                        let model_path = '';
                        if(model.indexOf('common_assets') > -1){
                            model_path = model;
                        } else {
                            model_path = glob.acc_url + model;
                        }

                        let obj = {};
                        obj.file = await get_binary(model_path);
                        let arr = model_path.split('/');
                        obj.name = arr[arr.length-1];
                        files.push(obj);
                }



                for (let i = 0; i < files.length; i++){
                    zip.file(files[i].name, files[i].file, {binary:true});
                }
                zip.generateAsync({type:"blob"}).then(function (content) {
                    save_file(content, params.name + '.zip');
                })

            },

            submit: function (e) {
                e.preventDefault();
                let scope = this;



                let formData = new FormData();
                formData.append('data', JSON.stringify(scope.get_data()));

                // console.log(scope.get_data())
                // return false

                $.ajax({
                    url : scope.$refs.sub_form.action,
                    type : 'POST',
                    data : formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success : function(msg) {
                        // console.log(msg);
                        window.location = document.getElementById('form_success_url').value
                    }
                });

                return false;

            }

        }
    });

    init_three();



    async function init_three() {
        viewport = document.getElementById('viewport');

        scene = new THREE.Scene();
        scene.fog = new THREE.Fog(0xE9E5CE, 500, 10000);
        camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

        renderer = new THREE.WebGLRenderer({
            antialias: true
        });
        renderer.setClearColor(scene.fog.color);
        renderer.setSize( viewport.clientWidth, viewport.clientHeight );
        viewport.appendChild( renderer.domElement );

        texture_loader = new THREE.TextureLoader();
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

        camera.position.z = 50;

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
            camera.aspect = viewport.clientWidth / viewport.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize( viewport.clientWidth, viewport.clientHeight );
        }



        if(app.item.model != ''){
            change_model();
        }

        // var box3 = new THREE.Box3().setFromObject(model).getSize();
        // $('#model_width_0').val(Math.round(box3.x.toFixed(2) * 10));
        // $('#model_height_0').val(Math.round(box3.y.toFixed(2) * 10));
        // $('#model_depth_0').val(Math.round(box3.z.toFixed(2) * 10));






    }

    function change_model(reset_variant = false) {
        if(preview_model){
            scene.remove(preview_model);
        }
        loader.load(app.correct_download_url(app.item.model), function (obj) {
            preview_model = obj;
            change_material(preview_model, create_material(copy_object(app.item.material)))
            scene.add(preview_model);

            var box3 = new THREE.Box3().setFromObject(preview_model).getSize();




            if(reset_variant){
                app.item.variants[0].width = Math.round(box3.x.toFixed(2) * 10);
                app.item.variants[0].height = (Math.round(box3.y.toFixed(2) * 10));
                app.item.variants[0].depth = (Math.round(box3.z.toFixed(2) * 10));
            }


        })
    }

    function change_material(obj, material) {
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

    function resize_viewport() {
        camera.aspect = viewport.clientWidth / viewport.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(viewport.clientWidth, viewport.clientHeight);
    }
    function create_material(params_obj) {

        if (!params_obj) params_obj = {};
        if (!params_obj.add_params) params_obj.add_params = {};


        let material;
        let texture;
        let repeat_x;
        let repeat_y;

        let normal_map;

        let repeat_already_set = 0;


        let params = {
            type: params_obj.type !== undefined ? params_obj.type : 'Phong',
            params: params_obj.params !== undefined ? JSON.parse(JSON.stringify(params_obj.params)) : {},
            add_params: {
                texture_width: params_obj.add_params.texture_width !== undefined ? params_obj.add_params.texture_width : 256,
                texture_height: params_obj.add_params.texture_height !== undefined ? params_obj.add_params.texture_height : 256,

                normal_map_width: params_obj.add_params.normal_map_width !== undefined ? params_obj.add_params.normal_map_width : 256,
                normal_map_height: params_obj.add_params.normal_map_height !== undefined ? params_obj.add_params.normal_map_height : 256,

                real_width: params_obj.add_params.real_width !== undefined ? params_obj.add_params.real_width : 256,
                real_height: params_obj.add_params.real_height !== undefined ? params_obj.add_params.real_height : 256,
                model_width: params_obj.add_params.model_width !== undefined ? params_obj.add_params.model_width : 256,
                model_height: params_obj.add_params.model_height !== undefined ? params_obj.add_params.model_height : 256,
                rotation: params_obj.add_params.rotation !== undefined ? params_obj.add_params.rotation : 'normal',
                stretch_width: params_obj.add_params.stretch_width !== undefined ? params_obj.add_params.stretch_width : false,
                stretch_height: params_obj.add_params.stretch_height !== undefined ? params_obj.add_params.stretch_height : false,
                normal_scale: params_obj.add_params.normal_scale !== undefined ? params_obj.add_params.normal_scale : false,

                wrapping: params_obj.add_params.wrapping !== undefined ? params_obj.add_params.wrapping : 'mirror',

                debug: params_obj.add_params.debug !== undefined ? params_obj.add_params.debug : false,

            }
        };


        if(params.params.transparent == 0) params.params.transparent = false
        if(params.params.transparent == 'false') params.params.transparent = false
        if(params.params.transparent == undefined) params.params.transparent = false

        if(params.params.transparent == 1) params.params.transparent = true
        if(params.params.transparent == 'true') params.params.transparent = true


        if(params.params.icon) delete params.params.icon;
        if(params.params.side){
            if(params.params.side == "double") params.params.side = THREE.DoubleSide;
        };

        if(params.add_params.normal_scale) params.params.normalScale = new THREE.Vector2(params.add_params.normal_scale, params.add_params.normal_scale)

        if(params.params.alphaMap){
            let alphaMap = texture_loader.load(params.params.alphaMap)

            if (params.add_params.wrapping === 'repeat') {
                alphaMap.wrapS = alphaMap.wrapT = THREE.RepeatWrapping;
            } else {
                alphaMap.wrapS = alphaMap.wrapT = THREE.MirroredRepeatWrapping;
            }

            if(params.add_params.stretch_width){
                repeat_x = 1;
            } else {
                repeat_x = params.add_params.model_width  / params.add_params.real_width ;
            }

            if(params.add_params.stretch_height){
                repeat_y = 1;
            } else {
                repeat_y = params.add_params.model_height /  params.add_params.real_height ;
            }

            alphaMap.repeat.set(repeat_x, repeat_y);
            params.params.alphaMap = alphaMap;
        }


        if(params.params.map){

            texture = texture_loader.load(params.params.map);

            if (params.add_params.wrapping === 'repeat') {
                texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
            } else {
                texture.wrapS = texture.wrapT = THREE.MirroredRepeatWrapping;
            }
            if(repeat_already_set === 0) {




                if (params.add_params.rotation === 'normal') {

                    if (params.add_params.stretch_width) {
                        repeat_x = 1;
                    } else {
                        repeat_x = params.add_params.model_width * params.add_params.texture_width / params.add_params.real_width / params.add_params.texture_width;
                    }

                    if (params.add_params.stretch_height) {
                        repeat_y = 1;
                    } else {
                        repeat_y = params.add_params.model_height * params.add_params.texture_height / params.add_params.real_height / params.add_params.texture_height;
                    }



                    texture.repeat.set(repeat_x, repeat_y);


                } else {

                    texture.center.set(0.5, 0.5);
                    texture.rotation = Math.PI / 2;

                    let tmp1 = JSON.parse(JSON.stringify(params.add_params.texture_width));
                    let tmp2 = JSON.parse(JSON.stringify(params.add_params.texture_height));

                    params.add_params.texture_width = tmp2;
                    params.add_params.texture_height = tmp1;

                    tmp1 = JSON.parse(JSON.stringify(params.add_params.real_width));
                    tmp2 = JSON.parse(JSON.stringify(params.add_params.real_height));

                    params.add_params.real_width = tmp2;
                    params.add_params.real_height = tmp1;


                    if (params.add_params.stretch_width) {
                        repeat_x = 1;
                    } else {
                        repeat_x = params.add_params.model_height / params.add_params.real_height;
                    }

                    if (params.add_params.stretch_height) {
                        repeat_y = 1;
                    } else {
                        repeat_y = params.add_params.model_width / params.add_params.real_width;
                    }


                    texture.repeat.set(repeat_x, repeat_y);

                }
                repeat_already_set = 1;
            }
            params.params.map = texture;

        }

        if(params.params.normalMap){


            normal_map = texture_loader.load(params.params.normalMap);
            normal_map.wrapS = normal_map.wrapT = THREE.RepeatWrapping;

            if(repeat_already_set === 0){

                if (params.add_params.stretch_width) {
                    repeat_x = 1;
                } else {
                    repeat_x = params.add_params.model_width * params.add_params.normal_map_width / params.add_params.real_width / params.add_params.normal_map_width;
                }
                if (params.add_params.stretch_height) {
                    repeat_y = 1;
                } else {
                    repeat_y = params.add_params.model_height * params.add_params.normal_map_height /  params.add_params.real_height / params.add_params.normal_map_height;
                }




                repeat_already_set = 1;
            }
            normal_map.repeat.set(repeat_x,repeat_y);
            params.params.normalMap = normal_map;
        }

        if(params.params.metalnessMap){
            params.params.metalnessMap = texture_loader.load(params.params.metalnessMap)
        }

        if(params.params.envMap){
            params.params.envMap = texture_loader.load(params.params.envMap)
        }

        if(params.params.bumpMap){


            normal_map = texture_loader.load(params.params.bumpMap);


            normal_map.wrapS = normal_map.wrapT = THREE.RepeatWrapping;
            if(repeat_already_set === 0) {
                repeat_x = params.add_params.model_width * params.add_params.normal_map_width / params.add_params.real_width / params.add_params.normal_map_width;
                repeat_y = params.add_params.model_height * params.add_params.normal_map_height / params.add_params.real_height / params.add_params.normal_map_height;
                repeat_already_set = 1;
            }
            normal_map.repeat.set(repeat_x, repeat_y);

            params.params.bumpMap = normal_map;
            params.params.bumpScale = 0.2;
        }


        if(params.params.metalness) params.params.metalness = parseFloat(params.params.metalness)
        if(params.params.roughness) params.params.roughness = parseFloat(params.params.roughness)
        if(params.params.opacity) params.params.opacity = parseFloat(params.params.opacity)


        switch (params.type) {
            case 'Phong':
                material = new THREE.MeshPhongMaterial(params.params);
                break;
            case 'Standart':
                if(params.params.transparent){
                    params.params.transparent = to_int(params.params.transparent) === 1;
                }
                material = new THREE.MeshStandardMaterial(params.params);
                break;
            case 'Basic':
                material = new THREE.MeshBasicMaterial(params.params);
                break;
        }


        return material;
    }




}
async function get_binary(path) {

    return new Promise(function (resolve, reject) {
        JSZipUtils.getBinaryContent(path, function (err, data) {
            if(err) {
                reject(err); // or handle the error
            }
            resolve(data);
        });
    });

}

function to_int(val) {
    let result = 0;
    if (typeof val === "boolean") {
        if (val === false) {
            result = 0;
        } else {
            result = 1;
        }
    }

    if (typeof val === "string") {
        if (val.toLowerCase() === "false" || val.toLowerCase() === "0" || val.toLowerCase() === "") {
            result = 0;
        } else {
            result = 1;
        }
    }

    if (typeof val === "number") {
        if (val === 0) {
            result = 0;
        } else {
            result = 1;
        }
    }

    return result;
}