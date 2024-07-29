let base_data = {};

let tmp = '';
let c_data = '';
let modules_sets_data = '';
let i_data = '';

let base_item_params = {
    name: '',
    icon: '',
    category: '0',
    order: 100000,
    active: 1,
    facades: {
        types: {},
        additional_items: {},
    },
    modules: '',
    cokol: {},
    cornice: {},
    materials: []
}
project_settings = {
    models:{
        top: 0,
        bottom: 0
    },
    facades_data: {
        top: 1
    },
    selected_materials:{
        facades:{
            0:{}
        }
    }
};

let base_addittional_params = {
    name: '',
    code: '',
    price: '',
    order: ''
}

document.addEventListener('Glob_ready', async function () {
    c_data = await get_tree_async(glob.base_url + '/catalog/' + glob.c_method_name + '/' + glob.controller_name, {
        id: "0",
        name: glob.lang['no'],
        parent: "0"
    });

    modules_sets_data = await promise_request(glob.base_url + '/catalog/items_get_common/modules_sets/');


    if(glob.item_id != 0){
        i_data = await promise_request(glob.base_url + '/catalog/'+ glob.i_method_name +'/' + glob.controller_name + '/' + glob.item_id);

        if(!i_data.data){
            console.log(123123)
            i_data.data = copy_object(base_item_params)
            console.log(i_data.data)
        } else {
            i_data.data = JSON.parse(i_data.data);
        }





        console.log(i_data)

    }




    init_vue();
})
Vue.use(ColorPanel)
Vue.use(ColorPicker)
function init_vue() {
    app = new Vue({
        el: '#bp_app',
        components:{
            'v-select': VueSelect.VueSelect,
            draggable: vuedraggable,
            // 'color-picker': ColorPicker

        },
        data: {
            url: glob.base_url + '/catalog/' + 'file_manager',
            folder_url: glob.base_url + '/catalog/' + 'create_folder',
            file_url: glob.base_url + '/catalog/' + 'upload_files',
            remove_url: glob.base_url + '/catalog/' + 'remove_files',
            item: {
                name: '',
                icon: '',
                category: '0',
                order: 100000,
                active: 1,
                "facades": {
                    "active": 1,
                    "additional_materials": {},
                    "category": 6,
                    "compatibility_types": {
                        "full": 0,
                        "window": 1
                    },
                    "double_offset": 0,
                    "handle": 1,
                    "icon": "",
                    "id": '',
                    "name": "",
                    "order": 1,
                    "materials": [
                        0
                    ],
                    "types": {

                    },
                    materials_n: [
                        {
                            "key": "gen",
                            "name": "Основной",
                            "code": "",
                            "params": {
                                "color": "#ffffff",
                                "map": "",
                                "normalMap": "",
                                "alphaMap": "",
                                "roughness": 0.8,
                                "metalness": 0,
                                "transparent": false
                            },
                            "add_params": {
                                "real_width": 512,
                                "real_height": 512,
                                "stretch_width": false,
                                "stretch_height": false,
                                "wrapping": "mirror"
                            }
                        }
                    ],
                    "additional_items": {

                    },

                    "prices": {},
                    "triple_decor_model": {
                        "model": "",
                        "model_file": "",
                        "current_model": "",
                        "thickness": 0,
                        "height": 0,
                        "offset": 0
                    },
                    "types_double": {},
                    "types_triple": {},
                    "types_radius": {}
                },
                modules: '',
                modules_set_id: '0',
                temp: 0,
                cornice: {
                    name: '',

                    model: '',
                    model_name: '',
                    model_code: '',
                    model_price: 0,

                    radius: '',
                    radius_name: '',
                    radius_code: '',
                    radius_price: 0,

                    corner_model: '',
                    corner_model_name: '',
                    corner_model_code: '',
                    corner_model_price: 0,

                    corner_model_45: '',
                    corner_model_45_name: '',
                    corner_model_45_code: '',
                    corner_model_45_price: 0,

                    additional_corner: '',
                    additional_corner_name: '',
                    additional_corner_code: '',
                    additional_corner_price: 0,

                    additional_front: '',
                    additional_front_name: '',
                    additional_front_code: '',
                    additional_front_price: 0,

                },
                molding: {
                    name: '',

                    model: '',
                    model_name: '',
                    model_code: '',
                    model_price: 0,

                    radius: '',
                    radius_name: '',
                    radius_code: '',
                    radius_price: 0,

                    corner_model: '',
                    corner_model_name: '',
                    corner_model_code: '',
                    corner_model_price: 0,

                    corner_model_45: '',
                    corner_model_45_name: '',
                    corner_model_45_code: '',
                    corner_model_45_price: 0,

                    height: 0
                },
                cokol_top: {
                    model: '',
                    model_name: '',
                    model_code: '',
                    model_price: 0,

                    radius: '',
                    radius_name: '',
                    radius_code: '',
                    radius_price: 0,
                },
                cokol: {
                    model: '',
                    model_name: '',
                    model_code: '',
                    model_price: 0,

                    radius: '',
                    radius_name: '',
                    radius_code: '',
                    radius_price: 0,
                },
                materials: [

                ]
            },
            // item: JSON.parse(i_data.data),

            models: {
                cornice: {
                    name: glob.lang['cornice'],
                    models: {
                        model: 'Прямой',
                        corner_model: 'Угол 90',
                        corner_model_45: 'Угол 45',
                        radius: 'Радиус',
                        additional_corner: 'Накладка Угол',
                        additional_front: 'Накладка Прямая',

                    }
                },
                molding: {
                    name: 'Молдинг',
                    models: {
                        model: 'Прямой',
                        corner_model: 'Угол 90',
                        corner_model_45: 'Угол 45',
                        radius: 'Радиус'
                    }

                },
                cokol_top: {
                    name: glob.lang['cokol_top'],
                    models: {
                        model: 'Прямой',
                        radius: 'Радиус'
                    }
                },
                cokol: {
                    name: glob.lang['cokol'],
                    models: {
                        model: 'Прямой',
                        radius: 'Радиус'
                    }
                }
            },
            models_got: false,

            upload:{
                name: "",
                category: "0",
                categories: {
                    tree:{},
                    ordered:[],
                    hash:[]
                },
                items: [],
                file: '',

                selected_cat: '0'
            },

            preview_model: '',

            additional_items_array: [],

            icon_file: '',
            active_tab: 0,
            active_tab_materials: 0,
            additional_no_price: true,
            model_no_price: true,
            model_multi_sizes_type: null,
            drag: false,
            fm_mode: 'images',
            fm_item: null,
            fm_prop: null,
            fm_update_mat: false,
        },
        computed:{

            dragOptions() {
                return {
                    animation: 150,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost",
                    scrollSensitivity: 200,
                    forceFallback: true
                };
            },
            all_models: function () {
                let scope = this;

                let result = [];

                console.log(this.item.temp)

                Object.keys(this.item.facades.types).forEach(function (k) {

                    let type = {
                        is_type: 1,
                        name: scope.item.facades.types[k].name,
                        code: ''
                    }

                    result.push(type)

                    for (let i = 0; i < scope.item.facades.types[k].items.length; i++){

                        let obj = copy_object(scope.item.facades.types[k].items[i]);

                        obj.is_type = 0;
                        obj.type_name = type.name

                        result.push(obj)
                    }
                })

                console.log(result)

                return result;
            }

        },
        created: function(){
            this.$options.categories = c_data;
            this.$options.modules_sets = modules_sets_data;

            if(glob.item_id != 0){

                if(!i_data.data.facades.materials_n){
                    i_data.data.facades.materials_n = [];
                    i_data.data.facades.materials_n.push({
                        "key": "gen",
                        "name": "Основной",
                        "code": "",
                        "params": {
                            "color": "#ffffff",
                            "map": "",
                            "normalMap": "",
                            "alphaMap": "",
                            "roughness": 0.8,
                            "metalness": 0,
                            "transparent": false
                        },
                        "add_params": {
                            "real_width": 512,
                            "real_height": 512,
                            "stretch_width": false,
                            "stretch_height": false,
                            "wrapping": "mirror"
                        },
                        "type": "Standart"
                    })
                }

                Vue.set(this, 'item', i_data.data)
            }



            console.log(copy_object(this.item))

            let data = copy_object(this.item.facades.additional_items)

            Object.keys(data).forEach(function (k) {
                data[k]['key'] = k
            })

            Vue.set(this, 'additional_items_array', Object.values(data))



        },
        mounted(){

            const viewport = document.getElementById('preview_viewport')

            const scene = new THREE.Scene();
            scene.fog = new THREE.Fog(0xE9E5CE, 500, 10000);
            var camera = new THREE.PerspectiveCamera( 55, viewport.clientWidth/viewport.clientHeight, 0.1, 1000 );

            const renderer = new THREE.WebGLRenderer({
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


            env_urls = [
                "/common_assets/tests/prod/test12.jpg",
                "/common_assets/tests/prod/test12.jpg",
                "/common_assets/tests/prod/test12.jpg",
                "/common_assets/tests/prod/test12.jpg",
                "/common_assets/tests/prod/test12.jpg",
                "/common_assets/tests/prod/test12.jpg"
            ];





            textureCube2 = new THREE.CubeTextureLoader().load( env_urls );
            textureCube2.format = THREE.RGBFormat;
            textureCube2.mapping = THREE.CubeReflectionMapping;






            controls = new THREE.OrbitControls(camera, renderer.domElement);



            viewport.appendChild( renderer.domElement );
            renderer.setSize(viewport.offsetWidth, viewport.offsetHeight );


            for (let i = 0; i < this.item.facades.materials_n.length; i++){
                materials_lib.items[100000000 + i] = this.item.facades.materials_n[i]
            }


            const animate = function () {
                requestAnimationFrame( animate );



                renderer.render( scene, camera );
            };

            this.$options.three = {
                renderer: renderer,
                scene: scene,
                viewport: viewport,
                camera: camera
            }

            animate();
        },
        methods: {
            computed_compat_type:function (key) {

                let scope = this;

                let result = '';

                if(scope.item.facades.compatibility_types){
                    Object.keys(scope.item.facades.compatibility_types).forEach(function (k) {
                        if(scope.item.facades.compatibility_types[k] == key) result = k;
                    });
                }

                return result;
            },
            set_compat_type: function (val, key) {
                console.log(val.target.value)
                console.log(key)
                let scope = this;
                if(val.target.value == ''){
                    Object.keys(this.item.facades.compatibility_types).forEach(function (k) {
                        if(key == scope.item.facades.compatibility_types[k]){
                            Vue.delete(scope.item.facades.compatibility_types, k)
                        }

                    })
                } else {

                    Object.keys(this.item.facades.compatibility_types).forEach(function (k) {
                        if(key == scope.item.facades.compatibility_types[k]){
                            Vue.delete(scope.item.facades.compatibility_types, k)
                        }

                    })

                    this.item.facades.compatibility_types[val.target.value] = parseInt(key);
                }


            },
            select_facade: function(){

                let scope = this;



                if(this.preview_model == '') return;

                if(this.$options.three.facade) this.$options.three.scene.remove(this.$options.three.facade)

                this.$options.three.facade = new Facade_new({
                    width: this.preview_model.width,
                    height: this.preview_model.height,
                    fixed_model: this.preview_model.model,
                    demo_mode: true
                })


                setTimeout(function () {
                    scope.update_material();
                },200)



                this.$options.three.scene.add(this.$options.three.facade)

            },
            update_material: function(){
                if(this.preview_model == '') return;
                for (let i = 0; i < this.item.facades.materials_n.length; i++){

                    // if(i == 0){
                    //     this.$options.three.facade.change_material({
                    //         type: this.item.facades.materials_n[i].key,
                    //         id: 100000000 + i
                    //     })
                    // } else {

                        this.$options.three.facade.change_material({
                            material_key: this.item.facades.materials_n[i].key,
                            id: 100000000 + i
                        })
                    // }


                }

            },


            resize_preview:function(){
                // this.$options.three.renderer.set
                console.log(123)
                this.item.temp++;
                if(this.preview_model == '' && this.all_models[1]){
                    this.preview_model = this.all_models[1];
                    this.select_facade();
                }

                let scope = this;

                project_settings = {
                    models:{
                        top: 0,
                        bottom: 0
                    },
                    facades_data: {
                        top: this.get_data().facades
                    },
                    selected_materials:{
                        facades:{
                            0:{}
                        }
                    }
                };

                setTimeout(function () {
                    let camera = scope.$options.three.camera;
                    let renderer = scope.$options.three.renderer;
                    let viewport = scope.$options.three.viewport;


                    camera.aspect = viewport.offsetWidth / viewport.offsetHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(viewport.offsetWidth, viewport.offsetHeight);
                }, 100)
            },

            enlarge_preview: function(){
                let scope = this;

                let camera = scope.$options.three.camera;
                let renderer = scope.$options.three.renderer;
                let viewport = scope.$options.three.viewport;

                viewport.style.position = 'absolute';



            },

            check_model_visible: function(cat){

                if(this.upload.selected_cat == 0) return true
                if(this.upload.selected_cat == cat) return true
                if(this.upload.categories.hash[cat].parent == this.upload.selected_cat) return true



            },
            on_drag({ relatedContext, draggedContext }) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            },
            lang: function(key){
                return glob.lang[key];
            },
            add_additional_item: function(data){

                if(!data) data = base_addittional_params;

                let i = Object.keys(this.item.facades.additional_items).length;

                while (this.item.facades.additional_items[i]) {
                    i++;
                }

                let obj = copy_object(data);
                obj.order = i;

                Vue.set(this.item.facades.additional_items, i, obj)
                return i;
            },
            add_additional_from_model: function(){
                this.add_additional_item({
                    name: this.$refs.na_name.value,
                    code: this.$refs.na_code.value,
                    price: this.$refs.na_price.value,
                    order: ''
                })
            },
            add_multiple_additional_item: function(){

                let text = this.$refs.additional_textarea.value;
                let lines = text.split(/\r?\n/);



                for (let i = 0; i < lines.length; i++){

                    let price = 0;

                    let split = clean_string(lines[i]).split(' ')
                    let code = split[0];
                    if(this.additional_no_price == false){
                        price = parseFloat(split[split.length - 1]);
                        split.pop();
                    }
                    split.shift();

                    this.add_additional_item({
                        name: split.join(' '),
                        code: code,
                        price: price,
                        order: ''
                    })

                }

            },
            remove_additional_item: function(key){
                let scope = this;
                show_warning_yes_no(function () {
                    console.log(key)
                    Vue.delete( scope.item.facades.additional_items, key);
                })
            },



            add_type: function (data) {
                let i = Object.keys(this.item.facades.types).length;

                while (this.item.facades.types[i]) {
                    i++;
                }

                let obj = {};

                if(!data){
                    obj = {
                        name:'Без названия',
                        items: [],
                        icon: '',
                        icon_file: '',
                        picker: 1,
                        vheight: 0

                    };
                } else {
                    obj = copy_object(data)
                }


                Vue.set( this.item.facades.types, i, obj);


                this.active_tab = i;
            },
            add_material: function(){
                this.item.facades.materials_n.push({
                    "params": {
                        "color": "#ffffff",
                        "map": "",
                        "normalMap": "",
                        "alphaMap": "",
                        "roughness": 0.8,
                        "metalness": 0,
                        "transparent": 0
                    },
                    "add_params": {
                        "real_width": 512,
                        "real_height": 512,
                        "stretch_width": 0,
                        "stretch_height": 0,
                        "wrapping": "mirror"
                    },
                    "name": "Без названия",
                    "code": "",
                    "category": 0,
                    "type": "Standart"
                })

                materials_lib.items[100000000 + this.item.facades.materials_n.length - 1] = this.item.facades.materials_n[this.item.facades.materials_n.length-1]


            },

            remove_material: function(index){
                let scope = this;

                show_warning_yes_no(function () {
                    scope.item.facades.materials_n.splice(index, 1)
                    scope.active_tab_materials = index - 1;
                })


            },

            remove_type: function (k) {
                let scope = this;
                show_warning_yes_no(function () {
                    Vue.delete( scope.item.facades.types, k);
                    scope.active_tab = closest(Object.keys(scope.item.facades.types), k)
                })
            },


            add_size: function (item,name) {

                item.items.push({
                    width: 0,
                    height: 0,
                    name: '',
                    code: '',
                    price: '',
                    model: ''
                });

            },
            add_multiple_sizes: function(){
                console.log(this.model_multi_sizes_type)

                let text = this.$refs.sizes_textarea.value;
                let lines = text.split(/\r?\n/);



                for (let i = 0; i < lines.length; i++){

                    let price = 0;

                    let split = clean_string(lines[i]).split(' ')
                    let code = split[0];
                    if(this.model_no_price == false){
                        price = parseFloat(split[split.length - 1]);
                        split.pop();
                    }
                    split.shift();

                    let name = split.join(' ');
                    let sizes = name.replace( /^\D+/g, '').replace('х','x').split('x')

                    this.model_multi_sizes_type.items.push({
                        "width": parseInt(sizes[1]),
                        "height": parseInt(sizes[0]),
                        "name": name,
                        "code": code,
                        "price": price,
                        "model": ""
                    })

                    this.model_multi_sizes_type.items.sort(function (a,b) {
                        if (a.height === b.height) {
                            return a.width - b.width;
                        }
                        return a.height - b.height
                    })

                }

            },
            process_excel_file:function(item, name, event){
                console.log(item)
                console.log(name)
                if(!document.getElementById('xljs')){
                    const script = document.createElement('script')
                    script.id = 'xljs'
                    script.src = '/common_assets/libs/exceljs.min.js'
                    document.head.append(script)
                }



                let int = setInterval(function () {
                    if(typeof ExcelJS === 'object'){
                        clearInterval(int)
                        const wb = new ExcelJS.Workbook();
                        const reader = new FileReader()

                        let file = event.target.files[0]

                        reader.readAsArrayBuffer(file)
                        reader.onload = () => {
                            const buffer = reader.result;

                            let result = [];

                            wb.xlsx.load(buffer).then(workbook => {
                                console.log(workbook, 'workbook instance')
                                workbook.eachSheet((sheet, id) => {
                                    sheet.eachRow((row, rowIndex) => {

                                        if(row.values[1] == '' || row.values[2] == '') return

                                        let sizes = row.values[2].replace( /^\D+/g, '').replace('х','x').split('x')
                                        console.log(sizes)

                                        item.items.push({
                                            // result.push({
                                            "width": parseInt(sizes[1]),
                                            "height": parseInt(sizes[0]),
                                            "name": row.values[2],
                                            "code": row.values[1],
                                            "price": "",
                                            "model": ""
                                        })

                                    })
                                })

                                item.items.sort(function (a,b) {
                                    if (a.height === b.height) {
                                        return a.width - b.width;
                                    }
                                    return a.height - b.height
                                })

                                event.target.value = '';
                            })

                        }


                    }
                },100)
            },

            process_excel_file_multi: function(event){
                let scope = this;
                if(!document.getElementById('xljs')){
                    const script = document.createElement('script')
                    script.id = 'xljs'
                    script.src = '/common_assets/libs/exceljs.min.js'
                    document.head.append(script)
                }

                let int = setInterval(function () {
                    if(typeof ExcelJS === 'object'){
                        clearInterval(int)
                        const wb = new ExcelJS.Workbook();
                        const reader = new FileReader()

                        let file = event.target.files[0]

                        reader.readAsArrayBuffer(file)
                        reader.onload = () => {
                            const buffer = reader.result;

                            let result = [];

                            let ct = -1;


                            wb.xlsx.load(buffer).then(workbook => {
                                console.log(workbook, 'workbook instance')
                                workbook.eachSheet((sheet, id) => {
                                    sheet.eachRow((row, rowIndex) => {
                                        console.log(row.values[1])
                                        console.log(row.values[2])
                                        if(row.values[1] == '' && row.values[2] == '') return

                                        if(row.values[1] != '' && !row.values[2]) {

                                            ct++
                                            result.push({
                                                name: row.values[1],
                                                items: [],
                                                icon: '',
                                                icon_file: '',
                                                picker: 1,
                                                vheight: 0
                                            })



                                        } else {
                                            let sizes = row.values[2].replace( /^\D+/g, '').replace('х','x').split('x')
                                            result[ct].items.push({
                                                // result.push({
                                                "width": parseInt(sizes[1]),
                                                "height": parseInt(sizes[0]),
                                                "name": row.values[2],
                                                "code": row.values[1],
                                                "price": "",
                                                "model": ""
                                            })
                                        }






                                    })
                                })

                                for (let i = 0; i < result.length; i++){
                                    result[i].items.sort(function (a,b) {
                                        if (a.height === b.height) {
                                            return a.width - b.width;
                                        }
                                        return a.height - b.height
                                    })
                                    scope.add_type(result[i])


                                }





                                event.target.value = '';
                            })

                        }


                    }
                },100)
            },

            sort_sizes: function(item){
                item.items.sort(function (a,b) {
                    if (a.height === b.height) {
                        return a.width - b.width;
                    }
                    return a.height - b.height
                })
            },
            remove_size: function(item, type){
                let scope = this;
                show_warning_yes_no(function () {
                    scope.item.facades.types[item].items.splice(type, 1)
                })
            },

            show_fm: function(item, prop, mode, update_mat){
                let scope = this;

                console.log(prop)
                console.log(item)

                this.fm_prop = prop;
                this.fm_item = item;
                this.fm_mode = mode;
                this.fm_update_mat = update_mat

                $('#model_select_modal').modal('show')

            },


            select_cat: function(e, id){
                e.preventDefault();
                e.stopPropagation();
                this.upload.selected_cat = id
            },
            show_select_model_modal: async function(model, name){

                if(this.models_got == false){
                    let c_method_name = 'categories_get_common';
                    let i_method_name = 'items_get_common'

                    let categories = await get_tree_async(glob.base_url + '/catalog/' + c_method_name + '/uploaded_models', {
                        id: "0",
                        name: glob.lang['no'],
                        parent: "0"
                    });
                    let items = await promise_request(glob.base_url + '/catalog/'+ i_method_name +'/uploaded_models');
                    Vue.set(this.upload, 'categories', categories)
                    Vue.set(this.upload, 'items', items)
                    this.models_got = true;
                }

                $('#model_select_modal').modal('show')
            },
            show_upload_model_modal: async function(model, name){
                if(this.models_got == false){
                    let c_method_name = 'categories_get_common';
                    let i_method_name = 'items_get_common'

                    let categories = await get_tree_async(glob.base_url + '/catalog/' + c_method_name + '/uploaded_models', {
                        id: "0",
                        name: glob.lang['no'],
                        parent: "0"
                    });
                    let items = await promise_request(glob.base_url + '/catalog/'+ i_method_name +'/uploaded_models');
                    Vue.set(this.upload, 'categories', categories)
                    Vue.set(this.upload, 'items', items)
                    this.models_got = true;
                }

                $('#model_upload_modal').modal('show')
            },
            process_file: function(event){
                if(event.target.files.length){
                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }
                }
                this.upload.file = event.target.files[0];



            },
            make_upload: async function(){
                console.log(this.upload.name);
                console.log(this.upload.category);
                console.log(this.upload.file);

                if(!this.upload.file){
                    toastr.error(glob.lang['no_file'])
                    return;
                }






                let data = new FormData();

                data.append('name', this.upload.name)
                data.append('category', this.upload.category)
                data.append('file', this.upload.file)

                let file = await upload_file(data)
                console.log(file)

                let obj = {
                    name: this.upload.name,
                    category: this.upload.category,
                    url: file,
                }

                this.upload.items.push(obj)


                // send_xhr_post(glob.base_url + '/catalog/'+ method_name, data, function (xhr) {
                //     console.log(xhr.responseText)
                //     let resp = JSON.parse(xhr.responseText);
                //     console.log(resp)
                //
                //     if(resp.result == 'fail'){
                //         if(resp.message == "file_exists"){
                //
                //             swal({
                //                 title: glob.lang['file_exists'],
                //                 text: glob.lang['overwrite_file'],
                //                 type: "warning",
                //                 showCancelButton: true,
                //                 confirmButtonColor: "#DD6B55",
                //                 cancelButtonText: glob.lang['no'],
                //                 confirmButtonText: glob.lang['yes'],
                //                 closeOnConfirm: true
                //             }, function () {
                //                 send_xhr_post(glob.base_url + '/catalog/'+ method_name + '/rewrite', data, function (xhr) {
                //                     let resp = JSON.parse(xhr.responseText);
                //                     if(resp.result == 'success'){
                //                         toastr.success(glob.lang[resp.message])
                //                         $('#model_upload_modal').modal('hide')
                //                     }
                //                 })
                //             })
                //
                //         }
                //     } else {
                //         toastr.success(glob.lang[resp.message])
                //         $('#model_upload_modal').modal('hide')
                //     }
                //
                // })

            },


            show_swal: function(){

            },

            get_icon_src:function (file) {
                if(this.item.icon != '' && this.icon_file == ''){
                    if(this.item.icon.indexOf('/common_assets') < 0) return acc_url + this.item.icon
                    return this.item.icon;
                }
                if(this.icon_file != '') return URL.createObjectURL(file);
                if(this.item.icon == '' &&  this.icon_file == '') return 'https://via.placeholder.com/78x125';
            },
            get_icon_src_new:function (icon, size) {
                if(!size) size = '78x125';

                if(!icon ) return 'https://via.placeholder.com/' + size;
                return icon;





            },
            process_icon_file: function (type, key, event) {
                if(event.target.files.length){

                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.item.facades[type][key].icon_file = event.target.files[0];
                } else {
                    this.item.facades[type][key].icon_file = '';
                }

            },

            get_data: function(){
                return copy_object(this.item)
            },

            select_file: function(item, prop, ev, update_mat){
                console.log(item)
                console.log(prop)
                console.log(ev)





                item[prop] = ev.path;
                if(update_mat){
                    console.log(122123)
                    this.update_material()
                    this.fm_update_mat = false;
                }

                $('#model_select_modal').modal('hide')
            },
            get_json: function(){
                document.getElementById('json_input').value = JSON.stringify(this.get_data(),null, 4);
            },
            apply_json: function(){
                let scope = this;
                try {
                    let params = JSON.parse(document.getElementById('json_input').value);
                    Vue.set(scope, 'item', JSON.parse(document.getElementById('json_input').value))
                    toastr.success(document.getElementById('success_message').innerHTML);
                } catch (e) {
                    toastr.error(document.getElementById('mod_json_error').innerHTML)
                }
            },
            submit: async function (e) {
                e.preventDefault();
                let scope = this;
                let data = scope.get_data();

                let formData = new FormData();



                formData.append('name', data.name);
                formData.append('icon', data.icon);
                formData.append('category', data.category);
                formData.append('active', data.active);
                formData.append('data', JSON.stringify(data));

                let result = promise_request_post(
                    $('#sub_form').attr('action'),
                    formData
                )

                location.href = document.getElementById('form_success_url').value


                return false;

            },

            upload_data: function () {

            }

        }
    });
}



async function upload_file(formdata) {

    return new Promise(function (resolve, reject) {
        let method_name = 'upload_model_common';

        send_xhr_post(glob.base_url + '/catalog/'+ method_name, formdata, function (xhr) {
            let resp = JSON.parse(xhr.responseText);

            if(resp.result == 'fail'){
                if(resp.message == "file_exists"){

                    swal({
                        title: glob.lang['file_exists'],
                        text: glob.lang['overwrite_file'],
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: glob.lang['no'],
                        confirmButtonText: glob.lang['yes'],
                        closeOnConfirm: true
                    }, function () {
                        send_xhr_post(glob.base_url + '/catalog/'+ method_name + '/rewrite', formdata, function (xhr) {
                            let resp = JSON.parse(xhr.responseText);
                            if(resp.result == 'success'){
                                toastr.success(glob.lang[resp.message])
                                $('#model_upload_modal').modal('hide')
                                resolve(resp.data)
                            }
                        })

                    })

                }
            } else {
                toastr.success(glob.lang[resp.message])
                $('#model_upload_modal').modal('hide')
                resolve(resp.data)
            }

        })
    });


}



