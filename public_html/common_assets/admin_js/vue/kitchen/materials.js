let textureCube2;


let app;

let global_options = {};
let c_data;
let i_data;

document.addEventListener('Glob_ready', async function () {

    // c_data = await promise_request(glob.base_url + '/catalog/'+ glob.c_method_name +'/' + glob.controller_name);
    // c_data.push({
    //     id: "0",
    //     name: 'Нет',
    //     parent: 0,
    //     order: 0
    // })
    //
    //
    // c_data.sort(function (a,b) {
    //     return a.order - b.order
    // })
    //
    // categories_hash = get_hash(c_data);
    // categories_ordered = flatten(create_tree(c_data));



    if(glob.item_id != 0){
        i_data = await promise_request(glob.base_url + '/catalog/'+ glob.i_method_name +'/' + glob.controller_name + '/' + glob.item_id);
        i_data.data = JSON.parse(i_data.data)
        console.log(copy_object(i_data))

        // if(!categories_hash[i_data.category]){
        //     i_data.category = "0";
        // }
        if(!i_data.data){

            if(!i_data.real_width) i_data.real_width = 0;
            if(!i_data.real_height) i_data.real_height = 0;
            if(!i_data.wrapping) i_data.wrapping = 'mirror';

            i_data.data = {
                id: i_data.id,
                name: i_data.name,
                code: i_data.code,
                order: i_data.order,
                category: i_data.category,
                active: i_data.active,
                params: {
                    icon: null,
                    alphaMap: null,
                    color: i_data.color,
                    map: i_data.map,
                    metalness: i_data.metalness,
                    normalMap: null,
                    roughness: i_data.roughness,
                    roughnessMap: null,
                    transparent: i_data.transparent,
                    opacity: 1,
                },
                add_params: {
                    real_width: i_data.real_width,
                    real_height: i_data.real_height,
                    rotation: 'normal',
                    stretch_width: i_data.stretch_width,
                    stretch_height: i_data.stretch_height,
                    normal_scale: false,
                    wrapping: i_data.wrapping,
                    normal_wrapping: 'mirror',
                },
                type: 'Standard'
            }
        }
    }
    global_options.acc_url = glob.acc_url;


    init_vue();
})


function init_vue() {


    app = new Vue({
        el: '#sub_form',
        components: {
            'v-select': VueSelect.VueSelect,
        },
        data: {
            item: dm_item,
            preview: {
                mesh: {
                    size: {
                        x: 450,
                        y: 720,
                        z: 16
                    }
                },
                model: null,
            },
            debounce: null,
            delay: 500
        },
        computed: {
            m_params: function () {
                return copy_object(this.item);
            }
        },
        mounted: function () {
            this.init_three();
            if(glob.item_id != 0){
                this.$emit('set_item_params', i_data.data)
            }

        },
        beforeCreate: function(){
            // this.$options.cat_ordered = categories_ordered
            // this.$options.cat_hash = categories_hash


        },
        created: function () {
            this.$options.three = {};
            // this.item.order = i_data.order;
            if(glob.item_id != 0){
                Vue.set(this, 'item', i_data.data)
            }
        },
        beforeMount: async function () {

        },
        methods: {
            init_three: async function(){
                let viewport = document.getElementById('three_view');
                let scene = new THREE.Scene();
                scene.fog = new THREE.Fog(0xE9E5CE, 500, 10000);
                let camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );
                let renderer = new THREE.WebGLRenderer({
                    antialias: true
                });
                renderer.setClearColor(scene.fog.color);
                renderer.setSize( viewport.clientWidth, viewport.clientHeight );
                viewport.appendChild( renderer.domElement );

                let amb_light = new THREE.AmbientLight( 0xffffff, 0.63);
                scene.add( amb_light );

                let directionalLight = new THREE.DirectionalLight( 0xffffff, 0.2 ,100  );
                directionalLight.position.set( 0, 450, 300 );
                directionalLight.target = new THREE.Group();
                directionalLight.target.position.set(0,0,0);
                directionalLight.target.name = 'Directional Light Target';


                let directionalLight2 = new THREE.DirectionalLight( 0xffffff, 0.2 ,100  );
                directionalLight2.position.set( 0, 450, -300 );
                directionalLight2.target = directionalLight.target;

                scene.add(directionalLight);
                scene.add(directionalLight.target);
                scene.add(directionalLight2);

                camera.position.z = 150;

                let light1 = new THREE.PointLight( 0xffffff, 0.5, 2000);
                light1.position.set(0, 300,0);
                light1.decay = 5;

                let light2 = new THREE.PointLight( 0xffffff, 0.5, 2000);
                light2.position.set(300, 0,0);
                light2.decay = 5;

                let light3 = new THREE.PointLight( 0xffffff, 0.5, 2000);
                light3.position.set(-300, 0,0);
                light3.decay = 5;

                let light4 = new THREE.PointLight( 0xffffff, 0.5, 2000);
                light4.position.set(0, 0,300);
                light4.decay = 5;

                let light5 = new THREE.PointLight( 0xffffff, 0.5, 2000);
                light5.position.set(0, 0,-300);
                light5.decay = 5;

                scene.add(light1);
                scene.add(light2);
                scene.add(light3);
                scene.add(light4);
                scene.add(light5);

                let controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.addEventListener( 'change', function () {
                    renderer.render( scene, camera )
                });

                env_urls = [
                    "/common_assets/tests/prod/test12.jpg",
                    "/common_assets/tests/prod/test12.jpg",
                    "/common_assets/tests/prod/test12.jpg",
                    "/common_assets/tests/prod/test12.jpg",
                    "/common_assets/tests/prod/test12.jpg",
                    "/common_assets/tests/prod/test12.jpg"
                ];


                textureCube2 = new THREE.CubeTextureLoader().load(env_urls);
                textureCube2.format = THREE.RGBFormat;
                textureCube2.mapping = THREE.CubeReflectionMapping;


                let geometry = new THREE.BoxGeometry( this.preview.mesh.size.x/10, this.preview.mesh.size.y/10, this.preview.mesh.size.z/10 );
                let params = this.m_params;
                params.add_params.model_width = this.preview.mesh.size.x;
                params.add_params.model_height = this.preview.mesh.size.y;
                let material = create_material(params)
                let mesh = new THREE.Mesh( geometry, material );
                scene.add( mesh );


                this.$options.three.camera = camera;
                this.$options.three.renderer = renderer;
                this.$options.three.viewport = viewport;
                this.$options.three.scene = scene;
                this.$options.three.controls = controls;
                this.$options.three.mesh = mesh;
                this.$options.three.material = material;

                window.addEventListener('resize', onWindowResize, false);

                function onWindowResize() {
                    camera.aspect = viewport.clientWidth / viewport.clientHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize( viewport.clientWidth, viewport.clientHeight );
                    renderer.render( scene, camera )
                }

                var animate = function () {
                    requestAnimationFrame( animate );

                    // cube.rotation.x += 0.01;
                    // cube.rotation.y += 0.01;

                    renderer.render( scene, camera );
                };

                animate();

            },
            resize_viewport: function(){
                setTimeout(() => {
                    let viewport = this.$options.three.viewport;
                    this.$options.three.camera.aspect = viewport.clientWidth / viewport.clientHeight;
                    this.$options.three.camera.updateProjectionMatrix();
                    this.$options.three.renderer.setSize( viewport.clientWidth, viewport.clientHeight );
                    this.$options.three.renderer.render( this.$options.three.scene, this.$options.three.camera )

                },100)

            },

            update_preview: function(e){
                Vue.set(this.item, 'params', e.params)
                Vue.set(this.item, 'add_params', e.add_params)
                let params = this.m_params;
                params.add_params.model_width = this.preview.mesh.size.x;
                params.add_params.model_height = this.preview.mesh.size.y;
                this.$options.three.mesh.material = create_material(params)
                this.$options.three.renderer.render( this.$options.three.scene, this.$options.three.camera )
            },
            change_mesh_size: function(e){
                clearTimeout(this.debounce)
                let scope = this;
                this.debounce = setTimeout( () =>{
                    this.$options.three.scene.remove(this.$options.three.mesh);
                    let geometry = new THREE.BoxGeometry( this.preview.mesh.size.x/10, this.preview.mesh.size.y/10, this.preview.mesh.size.z/10 );
                    this.$options.three.mesh = new THREE.Mesh( geometry, this.$options.three.material)
                    this.$options.three.scene.add( this.$options.three.mesh );
                }, this.delay)


            },

            update_color: function(e){
                this.item.params.color = e;
                this.$options.three.mesh.material.color = new THREE.Color(e);
            },
            get_data(){
                return copy_object(this.item)
            },
            get_json(){
                return JSON.stringify(this.item)
            },

            submit: function (e) {
                e.preventDefault();

                let url = this.$refs.form.action

                let fdata = new FormData();
                fdata.append('data', app.get_json())
                let res = promise_request_post(url, fdata)
                location.href = this.$refs.success.value
                return false;
            }
        }
    });
}


function power_of_2(n) {
    if (typeof n !== 'number')
        return 'Not a number';

    return n && (n & (n - 1)) === 0;
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
