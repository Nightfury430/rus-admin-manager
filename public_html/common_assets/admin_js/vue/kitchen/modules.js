let app = null;
let ajax_base_url = null;
let controller_name = null;
let lang_data = null;
let templates_list = null;
let custom_templates_list = null;
let categories = null;
let accessories = null;
let accessories_types = null;
let accessories_hash = {};
let cat_tree = null;
let cat_ordered = null;
let categories_hash = null;
let item_id = null;
let cabinet = null;
let module_data = null;
let builtin_data = null;

let input_timeout_time = 750;
let input_timeout = null;

let base_params = {
    cabinet: {
        force_top_wall: 0,
        top_wall: true,
        left_wall: true,
        right_wall: true,
        back_wall: true,
        bottom_wall: true,
        end_corner_width: 200,
        end_corner_depth: 0,
        end_corner_side_wall: false,
        end_corner_side_wall_width: 0,
        end_radius: 0,
        tsarg_width: 100
    },
    tabletop: {
        active: 1,
        offset:{
            left: 0,
            right: 0,
            back: 35,
            front: 35
        },
        end_corner_cut_width: 0,
        end_corner_cut_depth: 0,
        end_radius: 0
    },
    shelves:{
        orientation: 'horizontal',
        width: '100%',
        starting_point_x: '0%',
        starting_point_y: '0%',
        position_offset_top: 0,
        position_offset_bottom: 0,
        v2:1
    },
    walls:{
        orientation: 'horizontal',
        width: '100%',
        starting_point_x: '0%',
        starting_point_y: '0%',
        position_offset_top: 0,
        position_offset_bottom: 0,
        v2:1
    },
    doors: {
        type: 'rtl',
        style: 'full',
        width: '100%',
        height: '100%',
        starting_point_x: '0%',
        starting_point_y: '0%',
        facade_type: 'normal',
        offset_top: 0,
        offset_bottom: 0,
        group: 'bottom',
        handle_position: 'top',
        no_handle_forced: false
    },
    lockers: {
        style: 'full',
        width: '100%',
        height: '100%',
        starting_point_x: '0%',
        starting_point_y: '0%',
        facade_type: 'normal',
        position_offset_top: 0,
        position_offset_bottom: 0,
        no_handle_forced: false,
        group: 'bottom',
        inner: 0
    },
    oven: {
        active: false,
        pY: 0
    },
    hob: {
        active: false,
    },
    sink: {
        active: false,
    },
    built_in_models:{
        type: 'common',
        active: true,
        id: 0,
        pY: 0,
        pX: 0,
        pZ: 0
    },
    sections: {
        name: 'Секция',
        width: '100%',
        height: '100%',
        starting_point_x: '0%',
        starting_point_y: '0%',

        direction_x: 'right',
        direction_y: 'top',

        offset_top: 0,
        offset_bottom: 0,
        position_offset_top: 0,
        position_offset_bottom: 0,
        group: 'bottom',

        doors: [],
        lockers: [],
        built_in_models:[],
        shelves:[],
        walls:[],

        shelves_auto: 0,
        shelves_auto_count: 'auto',

        pX: 0,
        pY: 0,
        pZ: 0,
        rX: 0,
        rY: 0,
        rZ: 0
    },
    shelves_auto: 0,
    washer: {
        offset_x: 0,
        offset_z: 0,
        rotation: 0
    }
}

let door_types = [
    {
        name: 'Налево',
        type: "rtl"
    },
    {
        name: 'Направо',
        type: "ltr"
    },
    {
        name: 'Вверх',
        type: "simple_top"
    },
    {
        name: 'Вниз',
        type: "simple_bottom"
    },
    {
        name: 'Aventos HL',
        type: 'front_top'
    },
    {
        name: 'Aventos HF',
        type: 'double_top'
    },
    {
        name: 'Aventos HS',
        type: 'angle_top'
    },
    {
        name: 'Фальшфасад',
        type: 'falsefacade'
    }
];
let facade_styles = [
    {
        name: 'Глухой',
        type: 'full'
    },
    {
        name: 'Витрина',
        type: 'window'
    },
    {
        name: 'Решетка',
        type: 'frame'
    }
];
let facade_types = [
    {
        name: 'Обычный',
        type: 'normal'
    },
    {
        name: 'Интегрированная ручка',
        type: 'reversed'
    }
];
let handles_position = [
    {
        name: 'Сверху',
        val: 'top'
    },
    {
        name: 'Снизу',
        val: 'bottom'
    },
    {
        name: 'По центру',
        val: 'middle'
    },
    {
        name: 'Без ручки',
        val: 'no_handle'
    }

];
let facade_groups = [
    {
        name: 'Верхние модули',
        val: 'top'
    },
    {
        name: 'Нижние модули',
        val: 'bottom'
    }

];

let builtin_types = [
    'common',
    'dryer',
    'oven',
    'microwave',
    'hob',
    'washer'
]

let models_data = {};

let template_height = {
    "1":[],
    "2":[],
    "3":[]
}

let custom_template_height = {
    "1":[],
    "2":[],
    "3":[]
}

let acc_url;
let lang_div;

let accs_by_type = {};

let shift_pressed = 0;

document.addEventListener('Glob_ready', async function(){



    document.addEventListener("keydown", function (e) {
        if(e.key == 'Shift') shift_pressed = 1;
    });
    document.addEventListener("keyup", function (e) {
        if(e.key == 'Shift') shift_pressed = 0;
    });


    ajax_base_url = document.getElementById('ajax_base_url').value;
    controller_name = document.getElementById('controller_name').value;
    if(document.getElementById('item_id')) item_id = document.getElementById('item_id').value;


    acc_url = document.getElementById('acc_base_url').value;
    lang_data = {};
    lang_div = document.getElementById('lang_phrases');
    for (let i = 0; i < lang_div.children.length; i++){
        lang_data[lang_div.children[i].id] = lang_div.children[i].innerHTML;
    }

    let data_url = ajax_base_url + '/'+ controller_name +'/get_data_ajax/' ;
    if(document.getElementById('set_id').value != '') data_url = ajax_base_url + '/'+ controller_name +'/get_data_ajax/'+ document.getElementById('set_id').value + '/' ;

    let promises = [];
    promises.push(promise_request(data_url))

    promises.push(promise_request(ajax_base_url + '/catalog/get_catalog_items/builtin'))

    if(item_id != null){
        let item_url = ajax_base_url + '/'+ controller_name +'/get_item_data_ajax/' + item_id;
        promises.push(promise_request(item_url))
    }

    let data = await Promise.all(promises);

    console.log(data);

    Promise.all(promises).then(function (results) {
        let data = results[0];

        data.categories.unshift({
            id:"0",
            name: lang_data['lang_no'],
            parent: "0"
        })
        cat_tree = create_tree(data.categories);
        cat_ordered = flatten(cat_tree);
        categories_hash = get_hash(data.categories)

        accessories = data.accessories;
        accessories_types = data.accessories_types;


        for (let i = 0; i < accessories_types.length; i++){
            accs_by_type[accessories_types[i].key] = [];
        }

        for (let i = 0; i < accessories.length; i++){
            if(accs_by_type[accessories[i].type]){
                accs_by_type[accessories[i].type].push(accessories[i])
            }

            accessories_hash[accessories[i].id] = accessories[i];
        }

        for (let i = 0; i < data.modules_templates_custom.length; i++){
            if(data.modules_templates_custom[i].icon){
                if(data.modules_templates_custom[i].icon.indexOf('/common_assets') < 0) data.modules_templates_custom[i].icon = acc_url + data.modules_templates_custom[i].icon
            }
        }
        custom_templates_list = data.modules_templates_custom;
        templates_list = data.modules_templates

        builtin_data = results[1]

        if(item_id != null){
            module_data = results[2];
            if(module_data.icon){
                if(module_data.icon.indexOf('/common_assets') < 0) module_data.icon = acc_url + module_data.icon
            }

            module_data.params = JSON.parse(module_data.params).params;


            if(!module_data.params.accessories_default) module_data.params.accessories_default = [];
            if(module_data.params.free_size == undefined) module_data.params.free_size = 1;
            if(!module_data.params.accessories_types) module_data.params.accessories_types = {};
            if(!module_data.params.cabinet) module_data.params.cabinet = {};
            if(!module_data.params.cabinet.materials) module_data.params.cabinet.materials = [];
            if(!module_data.params.cabinet.material) module_data.params.cabinet.material = 0;

            if(module_data.params.accessories_types.length !== undefined) module_data.params.accessories_types = {};

            for (let i = module_data.params.accessories_default.length; i--;){
                if(!accessories_hash[module_data.params.accessories_default[i].id]) module_data.params.accessories_default.splice(i,1)
            }

        }



        init_vue();

    })


});


function init_vue(){
    app = new Vue({
        el: '#app',
        components:{
            draggable: vuedraggable,
            'v-select': VueSelect.VueSelect
        },

        created: function(){
            this.$options.cat_ordered = cat_ordered
            this.$options.categories = categories

            this.$options.lang = glob.lang;

            this.$options.accessories = accessories
            this.$options.accessories_types = accessories_types
            this.$options.accessories_hash = accessories_hash
            this.$options.accs_by_type = accs_by_type


            this.$options.builtin = builtin_data
            this.$options.builtin_hash = get_hash(builtin_data.items)

            this.templates_list = templates_list;
            this.custom_templates_list = custom_templates_list;

            let scope = this;

            if(item_id != null){


                if(!categories_hash[module_data.category]) module_data.category = "0";

                Object.keys(module_data).forEach(function (key) {
                    if(key == 'params'){

                        Vue.set(scope.item, key, module_data[key])


                        if(scope.item.params.fixed_facade_material){
                            scope.fixed_material = scope.item.params.fixed_facade_material
                            console.log(scope.item.params.fixed_facade_material)
                        }

                        if(module_data.params.sections && module_data.params.sections.length){

                            for (let i = 0; i < module_data.params.sections.length; i++){
                                scope.sections.push({
                                    mode: 1,
                                    shelves: 0,
                                    walls: 0,
                                    doors: 0,
                                    lockers: 0,
                                    builtin: 0
                                })
                            }


                        }

                        if(scope.item.params.sizes_limit){
                            Vue.set(scope, 'sizes_limit', scope.item.params.sizes_limit)
                        }

                    } else {
                        Vue.set(scope.item, key, module_data[key])
                    }
                })



                Vue.set(scope.item, 'icon_file', '')
            }



        },

        data: {
            categories: [],
            item:{
                active: 1,
                order: 0,
                category: "0",
                icon: '',
                icon_file: '',
                template_id: '',
                is_custom_template: '',
                params:{
                    cabinet: {
                        materials: [],
                        material: 0,
                    },

                    variants:[
                        {
                            name: '',
                            code: '',
                            price: '',
                            width: '',
                            height: '',
                            depth: '',
                            default: 1
                        }
                    ],
                    accessories_default:[],
                    accessories_types:{},
                    variables: {}
                },

            },

            config_params: {},

            current_file_ref: '',
            current_file_index: '',

            drag: false,
            add_modal: false,
            templates_list:[],
            custom_templates_list:[],
            template_mode: 'template',
            edit_mode: 0,
            template_category: 1,
            template_height: '',

            ex_template_category: 0,
            ex_templates_list:[],

            errors:[],
            fixed_material: null,
            sizes_limit:{
                min_width: 0,
                max_width: 0,
                min_height: 0,
                max_height: 0,
                min_depth: 0,
                max_depth: 0
            },

            variables: [],
            active_variable_tab: 0,

            editor_tabs: {
                mode: 1,
                shelves: 0,
                walls: 0,
                sections: 0,
                doors: 0,
                lockers: 0,
                builtin: 0
            },

            sections:[

            ],



            select_model: 0,
            default_furniture: {}
        },
        mounted(){

            let scope = this;
            if (document.getElementById('item_id')) {
                let inter = setInterval(async function () {
                    if(scene !== undefined){

                        clearInterval(inter);


                        materials_lib.items['w_mat_0'] = {

                        };


                        let params = scope.get_data().params

                        if(params.washer && params.washer.id){
                            await scope.add_washer_to_lib(params.washer.id)
                        }

                        cabinet = sc.add_object('cabinet', params, false);
                        cabinet.position.set(100, 0, 100)

                        if(params.variables){
                            this.variables = [];
                            Object.keys(params.variables).forEach(function (k) {
                                scope.variables.push(params.variables[k])
                            })
                        }

                    }

                }, 100)
            }

            this.$options.door_types = door_types;
            this.$options.facade_types = facade_types;
            this.$options.facade_styles = facade_styles;
            this.$options.handles_position = handles_position;
            this.$options.facade_groups = facade_groups;



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
            show_delete: function () {
                if(this.item.icon_file != '') return true;
                if(this.item.icon.indexOf('module_sets_modules_icons') > -1) return true;
            },
            check_furni_auto: function () {

                if(!this.item.params.accessories_types) return false;



                return Object.keys(this.item.params.accessories_types).length == 0
            }
        },
        methods: {

            get_existing_modules: async function(){

            },

            check_codes: async function(){


                let codes = {};

                for (let i = 0; i < this.item.params.variants.length; i++) {
                    let variant = this.item.params.variants[i];
                    if(variant.code != ''){
                        if(codes[variant.code]){
                            alert('Найден дубль артикула в текущем модуле');
                            return ;
                        } else {
                            codes[variant.code] = variant.code;
                        }

                    }
                }

                if(Object.keys(codes).length == 0){
                    alert('Нет ни одного артикула в текущем модуле')
                    return ;
                }

                let res = await promise_request(ajax_base_url + '/'+ controller_name +'/get_modules_for_codes/')
                res = res['modules_items'];

                let doubles = [];

                for (let i = 0; i < res.length; i++){
                    if(item_id){
                        if(res[i].id == item_id) continue;
                    }
                    if(res[i].params){
                        let item = JSON.parse(res[i].params);
                        if(item.params && item.params.variants){
                            for (let v = 0; v < item.params.variants.length; v++){
                                if(codes[item.params.variants[v].code]){
                                    doubles.push({
                                        module: res[i],
                                        code: item.params.variants[v].code
                                    });
                                }
                            }
                        }
                    }

                }

                console.log(doubles)

                if(doubles.length){
                    let str = 'Артикул существует:\n'
                    for(let i = 0; i < doubles.length; i++){
                        str+= "ID модуля: " + doubles[i].module.id + ' Артикул: ' + doubles[i].code + '\n'
                    }
                    alert(str)
                } else {
                    alert('Артикулы уникальны')
                }



            },

            add_variable: function(){
                this.variables.push({
                    key: 'var' + (this.variables.length+1),
                    name: 'Переменная' + (this.variables.length+1),
                    type: 'number',
                    editable: 0,
                    variants: 0,
                    params: {
                        min: 0,
                        max: 0,
                        step: 1,
                    }
                })
            },

            remove_variable: function (index) {
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {
                    scope.variables.splice(index, 1)
                });
            },

            change_var_type: function(e, index){
                let types = {
                    select: {
                        values: []
                    },
                    number: {
                        min: 0,
                        max: 0,
                        step: 1,
                    },
                    bool: {}
                }


                this.variables[index].type = e.target.value;
                this.variables[index].params = types[e.target.value];
            },
            add_variable_value: function(item){
                item.params.values.push({
                    text: '',
                    value: ''
                });

                console.log(item)

            },
            remove_variable_value: function (item, index) {
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {
                    item.params.values.splice(index, 1)
                });
            },

            apply_variables:function(){

                let obj = {};

                for (let i = 0; i < this.variables.length; i++){
                    let v = this.variables[i];
                    obj[v.key] = v;
                }

                Vue.set(this.item.params,'variables', obj)


            },

            sel_file: function(file){

                let url = glob.acc_url;
                url.substr(-1) === '/' ? url = url.slice(0, -1) : url;

                this.$refs[this.current_file_ref][this.current_file_index].value = url + file.true_path;
                this.$refs[this.current_file_ref][this.current_file_index].dispatchEvent(new Event('change'));

            },
            lang: function(key){
                return glob.lang[key];
            },
            get_models_data: function(){

            },

            get_default_furniture: function(){
                let params = this.get_data().params;


                let result = {
                    door: {
                        count: 0,
                        id: null
                    },
                    locker: {
                        count: 0,
                        id: null
                    },
                    simple_top: {
                        count: 0,
                        id: null
                    },
                    double_top: {
                        count: 0,
                        id: null
                    },
                    front_top: {
                        count: 0,
                        id: null
                    }
                };


                if(params.cabinet && params.cabinet.type){
                    if(params.cabinet.type === 'false_facade' || params.cabinet.type === 'false_facade_facade' || params.cabinet.type === 'false_facade_tabletop'){
                        return result;
                    }
                }



                if(params.doors){
                    for (let i = 0; i < params.doors.length; i++) {
                        if (params.doors[i].type === 'ltr' || params.doors[i].type === 'rtl') {
                            result.door.count += 2;
                        }
                        if (params.doors[i].type === 'simple_top') {
                            result.door.count += 2;
                            result.simple_top.count += 2;
                        }

                        if (params.doors[i].type === 'double_top') {
                            result.door.count += 2;
                            result.double_top.count += 1;
                        }

                        if (params.doors[i].type === 'front_top') {
                            result.front_top.count += 1;
                        }

                        if (params.doors[i].type === 'corner_90_rtl' || params.doors[i].type === 'corner_90_ltr') {
                            result.door.count += 4;
                        }

                    }
                }


                if(params.lockers){
                    for (let i = 0; i < params.lockers.length; i++) {
                        result.locker.count += 1;
                    }
                }


                Vue.set(this,'default_furniture',result);

            },

            make_icon: function(gray){
                if(!cabinet) return;
                let scope = this;
                let camera_pos = camera.position.clone();
                let controls_target = controls.target.clone();
                let cab_pos = cabinet.position.clone();

                if(gray){
                    let mt = materials_lib.items[1];
                    mt.params.color = '#545454';
                    mt.params.transparent = true;
                    mt.params.opacity = 0.7;

                    gray_mat.transparent = true;
                    gray_mat.opacity = 0.7;

                    cabinet.change_facade_material_new(1,'gen',1,'all')
                }

                this.set_design_mode();

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

                if(cabinet.params.cabinet.type === 'corner' || cabinet.params.cabinet.type === 'corner_90'){
                    cabinet.rotation.y = Math.PI/3;
                }

                if (cabinet.params.cabinet.orientation === 'left'){
                    camera.position.set(
                        -140,
                        64,
                        205
                    );
                }


                let box = new THREE.Box3().setFromObject(cabinet).getSize();
                cabinet.position.set(
                    0,- box.y / 2,0
                );

                hide_sizes();
                room.hide();
                controls.update();


                setTimeout(function () {

                    let canvas = document.createElement('canvas');


                    if (cabinet.params.cabinet.height < 1250){
                        canvas.width = 200;
                        canvas.height = 200;
                        let ctx = canvas.getContext('2d');

                        let left0 = renderer.domElement.width / 2 - 110;
                        let top0 = renderer.domElement.height / 2 - 110;

                        if(
                            cabinet.params.cabinet.type == 'corner' ||
                            cabinet.params.cabinet.type == 'corner_90' ||
                            cabinet.params.cabinet.type == 'corner_straight'
                        ) {
                            left0-=10
                            top0-=10

                            ctx.drawImage(renderer.domElement,left0,top0 + 20,250,250,0,0,200,200);
                        } else{
                            ctx.drawImage(renderer.domElement,left0,top0+20,220,220,0,0,200,200);
                        }




                    }

                    if(cabinet.params.cabinet.height >= 1250){
                        canvas.width = 200;
                        canvas.height = 400;
                        let ctx = canvas.getContext('2d');

                        let left0 = renderer.domElement.width / 2 - 125;
                        let top0 = renderer.domElement.height / 2 - 250;


                        ctx.drawImage(renderer.domElement,left0,top0,250,500,0,0,200,400);
                    }
                    if(cabinet.params.cabinet.height >= 2200){
                        canvas.width = 200;
                        canvas.height = 400;
                        let ctx = canvas.getContext('2d');

                        let left0 = renderer.domElement.width / 2 - 150;
                        let top0 = renderer.domElement.height / 2 - 300;


                        ctx.drawImage(renderer.domElement,left0,top0,300,600,0,0,200,400);
                    }





                    let data = canvas.toDataURL('image/jpeg', 0.8);
                    console.log(data)

                    scope.item.icon_file = data;

                    controls.target.set(controls_target.x, controls_target.y, controls_target.z);
                    camera.position.set(camera_pos.x, camera_pos.y, camera_pos.z);
                    cabinet.position.set(cab_pos.x, cab_pos.y, cab_pos.z);
                    room.show();
                    controls.update();
                    show_sizes();

                    if(gray){
                        let mt = materials_lib.items[1];
                        mt.params.color = '#932129';
                        mt.params.transparent = false;

                        gray_mat.transparent = false;

                        cabinet.change_facade_material_new(1,'gen',1,'all')
                    }

                    scope.set_edit_mode();

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

            check_cabinet_type: function(doors = false){
                if(!this.item.params.cabinet) Vue.set(this.item.params, 'cabinet', {});
                if(doors == true){
                    return (
                        this.item.params.cabinet.type == undefined ||
                        this.item.params.cabinet.type == 'common' ||
                        this.item.params.cabinet.type == 'corner' ||
                        this.item.params.cabinet.type == 'corner_straight' ||
                        this.item.params.cabinet.type == 'end_corner_facade'
                    );
                } else {
                    return (
                        this.item.params.cabinet.type == undefined ||
                        this.item.params.cabinet.type == 'common' ||
                        this.item.params.cabinet.type == 'corner' ||
                        this.item.params.cabinet.type == 'corner_straight'
                    );
                }


            },

            check_bottom: function(){
                return this.item.params.cabinet_group == 'bottom' || !this.item.params.cabinet_group;
            },

            check_percent: function(val){
                val+='';
                if(val.indexOf('%') > -1){
                    return '%'
                }
                return 'mm'
            },

            change_percent: function(event, index, param, group){
                let val = event.target.value;
                console.log(val)

                if(this.item.params[group][index][param] === undefined) this.item.params[group][index][param] = base_params[group][param]

                if(val == '%'){
                    this.item.params[group][index][param] = parseInt(this.item.params[group][index][param]) + '%';
                } else {
                    this.item.params[group][index][param] = parseInt(this.item.params[group][index][param]);
                }
                if(cabinet){
                    this.build_parts(group)
                }
            },

            change_percent_sections: function(event, index, section_index, param, group){
                let val = event.target.value;



                if(this.item.params.sections[section_index][group][index][param] === undefined) this.item.params.sections[section_index][group][index][param] = base_params[group][param]

                if(val == '%'){
                    this.item.params.sections[section_index][group][index][param] = parseInt(this.item.params.sections[section_index][group][index][param]) + '%';
                } else {
                    this.item.params.sections[section_index][group][index][param] = parseInt(this.item.params.sections[section_index][group][index][param]);
                }
                if(cabinet){
                    this.build_parts('sections')
                }
            },


            build_parts: function(group){
                cabinet.params[group] = this.get_data().params[group];
                if(group == 'shelves') cabinet.build_shelves()
                if(group == 'doors') cabinet.build_doors()
                if(group == 'lockers') cabinet.build_lockers()
                if(group == 'walls') cabinet.build_common();
                if(group == 'sections') cabinet.build_sections_common()
                if(group == 'built_in_models') cabinet.build_fixed_models()
                cabinet.edges_mode(cabinet[group]);
            },

            change_shelves: function(event, group, index, param, check_select){
                let scope = this;
                debounce(function () {
                    let val = '';
                    let input = event.target;
                    let select;
                    val = parseInt(input.value);

                    console.log(val)

                    if(isNaN(val)) val = input.value;

                    if(check_select){
                        select = input.parentElement.getElementsByTagName('SELECT')[0];
                        if(select.value == '%'){
                            val = val+'%';
                        }
                    }

                    if(group == 'shelves'){
                        scope.item.params[group][index].v2 = 1;
                        if(!scope.item.params[group][index].starting_point_x) scope.item.params[group][index].starting_point_x = "0%"
                    }

                    if(group == 'doors'){
                        if(param == 'handle_position'){
                            if(val == 'no_handle'){
                                scope.item.params[group][index].no_handle_forced = 1;
                            } else {
                                if( scope.item.params[group][index].no_handle_forced) delete scope.item.params[group][index].no_handle_forced
                            }
                        }
                    }

                    scope.item.params[group][index][param] = val;

                    if(group == 'lockers'){
                        if(param == 'no_handle_forced'){
                            if(val == 'true'){
                                scope.item.params[group][index].no_handle_forced = 1;
                            } else {
                                if( scope.item.params[group][index].no_handle_forced){
                                    delete scope.item.params[group][index].no_handle_forced
                                    delete cabinet.params[group][index].no_handle_forced
                                }
                            }
                        }



                    }

                    if(cabinet){
                        scope.build_parts(group)
                    }
                })
            },

            change_shelves_sections: function(event, group, index, section_index, param, check_select){
                let scope = this;
                debounce(function () {
                    let val = '';
                    let input = event.target;
                    let select;
                    val = parseInt(input.value);

                    console.log(val)

                    if(isNaN(val)) val = input.value;

                    if(check_select){
                        select = input.parentElement.getElementsByTagName('SELECT')[0];
                        if(select.value == '%'){
                            val = val+'%';
                        }
                    }

                    if(group == 'shelves'){
                        scope.item.params.sections[section_index][group][index].v2 = 1;
                        if(!scope.item.params.sections[section_index][group][index].starting_point_x) scope.item.params.sections[section_index][group][index].starting_point_x = "0%"
                    }

                    if(group == 'doors'){
                        if(param == 'handle_position'){
                            if(val == 'no_handle'){
                                scope.item.params.sections[section_index][group][index].no_handle_forced = 1;
                            } else {
                                if( scope.item.params.sections[section_index][group][index].no_handle_forced) delete scope.item.params.sections[section_index][group][index].no_handle_forced
                            }
                        }
                    }

                    scope.item.params.sections[section_index][group][index][param] = val;

                    if(group == 'lockers'){
                        if(param == 'no_handle_forced'){
                            if(val == 'true'){
                                scope.item.params.sections[section_index][group][index].no_handle_forced = 1;
                            } else {
                                if( scope.item.params.sections[section_index][group][index].no_handle_forced){
                                    delete scope.item.params.sections[section_index][group][index].no_handle_forced
                                    delete cabinet.params.sections[section_index][group][index].no_handle_forced
                                }
                            }
                        }



                    }

                    if(cabinet){
                        scope.build_parts('sections')
                    }
                })
            },

            change_offset: function(event, side){
                let scope = this;
                debounce(function () {
                    let val = '';
                    let input = event.target;
                    val = parseInt(input.value);

                    scope.item.params.tabletop.offset[side] = val;
                    if(cabinet){
                        cabinet.params.tabletop.offset[side] = val;
                        cabinet.build();
                    }
                })
            },



            change_models: function(event, group, param) {
                let scope = this;
                debounce(function () {
                    let val = parseInt(event.target.value);
                    if(!scope.item.params[group]) scope.item.params[group] = {};
                    scope.item.params[group][param] = val;
                    let data = scope.get_data();
                    if(cabinet){
                        Object.keys(data.params[group]).forEach(function (k) {

                            if(typeof data.params[group][k] != 'object'){
                                cabinet.params[group][k] = data.params[group][k];
                            } else {
                                Object.keys(data.params[group][k]).forEach(function (key) {
                                    cabinet.params[group][k][key] = data.params[group][k][key];
                                })
                            }
                        })
                    }
                    cabinet.build();
                })

            },
            change_cabinet: function (event, group, param) {

                let val = event.target.checked;

                if(param == 'force_top_wall'){
                    val = event.target.checked ? 1 : 0
                }

                if(param == 'end_radius_left_wall_facade'){
                    this.item.params.cabinet.left_wall = event.target.checked ? 'facade_simple' : true
                }

                if(param == 'end_radius_right_wall_facade'){
                    this.item.params.cabinet.right_wall = event.target.checked ? 'facade_simple' : true
                }


                if (!this.item.params[group]) this.item.params[group] = {};

                if(group == 'shelves_auto'){

                    if(val){
                        this.item.params.shelves_auto = 1
                        Vue.set(this.item.params, 'shelves', [])
                    } else {
                        this.item.params.shelves_auto = 0;
                        Vue.set(this.item.params, 'shelves', [])
                    }

                    // this.item.params[group] = val;
                } else {
                    this.item.params[group][param] = val;
                }





                if (group == 'tabletop' && !val) {
                    this.item.params.tabletop.height = 0;
                } else {
                    delete this.item.params.tabletop.height;
                }

                let data = this.get_data();
                if (cabinet) {

                    if(group == 'shelves_auto'){
                        cabinet.params.shelves = [];
                        cabinet.params.shelves_auto = data.params.shelves_auto;

                    } else {
                        Object.keys(data.params[group]).forEach(function (k) {

                            if (typeof data.params[group][k] != 'object') {
                                cabinet.params[group][k] = data.params[group][k];
                            } else {
                                Object.keys(data.params[group][k]).forEach(function (key) {
                                    cabinet.params[group][k][key] = data.params[group][k][key];
                                })
                            }
                        })
                    }

                    if(group == 'sink'){

                        console.log('sink')
                        console.log(val)

                        if(val == false){
                           cabinet.remove_wahser_new();
                            cabinet.build();
                           // console.log(cabinet.params.washer)
                        }


                    }




                    if (group == 'tabletop') {
                        if (!val) {
                            cabinet.params.tabletop.height = 0;
                        } else {
                            cabinet.params.tabletop.height = 40;
                        }
                        cabinet.build_tabletop();
                    } else {
                        cabinet.build();
                    }
                }

            },

            change_cabinet_val: function(event, group, param) {
                let val = parseInt(event.target.value);

                let scope = this;
                debounce(function () {



                    if(group == 'cabinet' && Array.isArray(scope.item.params[group])){
                        scope.item.params[group] = {}
                    }

                    if(group == 'washer' && param == 'rotation'){
                        val = val * (Math.PI/180);
                    }

                    if (!scope.item.params[group]){
                        Vue.set(scope.item.params, group, {})
                    }
                    if (!cabinet.params[group]) cabinet.params[group] = {};
                    scope.item.params[group][param] = val;
                    cabinet.params[group][param] = val;
                    cabinet.build();
                })


            },


            change_90_or: function(event){
                console.log(event.target.value)



                if(event.target.value == 'left'){
                    Vue.set(this.item.params.cabinet, 'orientation', 'left')
                    Vue.set(this.item.params.doors[0], 'type', 'corner_90_rtl')
                    cabinet.params.cabinet.orientation = 'left';
                    cabinet.params.doors[0].type = 'corner_90_rtl';
                } else {
                    Vue.delete(this.item.params.cabinet, 'orientation')
                    delete cabinet.params.cabinet.orientation
                    cabinet.params.doors[0].type = 'corner_90_ltr';
                }

                cabinet.build();


            },
            get_90_or: function(){
                if (this.item.params.cabinet.orientation == 'left') return 'left';
                return 'right';
            },
            add_part: function(group){
                let scope = this;
                if(!this.item.params[group]) Vue.set(this.item.params, group, [])

                this.item.params[group].push(JSON.parse(JSON.stringify(base_params[group])))


                this.editor_tabs[group] = this.item.params[group].length - 1;

                if(group == 'sections'){
                    this.sections.push({
                        mode: 1,
                        shelves: 0,
                        walls: 0,
                        doors: 0,
                        lockers: 0,
                        builtin: 0
                    })
                }



                if(cabinet){
                    scope.build_parts(group)
                    // setTimeout(function () {
                    //     cabinet.shelves.children[scope.editor_tabs.shelves].material = red_mat_opac
                    // },150)
                }



            },

            add_part_to_section: function(group, index){
                let scope = this;
                if(!this.item.params.sections[index][group]) Vue.set(this.item.params.sections[index], group, [])

                this.item.params.sections[index][group].push(JSON.parse(JSON.stringify(base_params[group])))
                this.sections[index][group] = this.item.params.sections[index][group].length - 1;
                if(cabinet){
                    scope.build_parts('sections')
                }
            },


            add_model: function(id){
                let scope = this;
                let group = 'built_in_models'
                if(!this.item.params[group]) Vue.set(this.item.params, group, [])
                let params = JSON.parse(JSON.stringify(base_params[group]));

                let data = JSON.parse(this.$options.builtin_hash[id].model_data)
                console.log(data)
                params.id = 'bim' + id;
                params.type = data.type;
                this.item.params[group].push(params)
                this.editor_tabs[group] = this.item.params[group].length - 1;

                if(cabinet){
                    scope.build_parts(group)
                }

            },

            delete_part: function(index, group){
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {

                    scope.item.params[group].splice(index,1)
                    scope.editor_tabs[group] = scope.item.params[group].length - 1;
                    if(cabinet){
                        scope.build_parts(group)
                    }

                    if(group == 'sections'){
                        scope.sections.splice(index,1)
                    }


                });
            },

            delete_part_sections: function(index, section_index, group){
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {


                    scope.item.params.sections[section_index][group].splice(index,1)
                    scope.sections[section_index][group] = scope.item.params.sections[section_index][group].length - 1;
                    if(cabinet){
                        scope.build_parts('sections')
                    }




                });
            },


            copy_v_size(variant, param){

                let val = variant[param];

                let variants = this.item.params.variants

                for (let i = 0; i < variants.length; i++){
                    variants[i][param] = val;
                }

            },

            get_type_params(type, index, name){



                if(index == null){

                    if(name == 'shelves_auto'){
                        return this.item.params.shelves_auto
                    }

                    if(name == 'end_radius_left_wall_facade'){
                        if(this.item.params.cabinet.left_wall == 'facade_simple'){
                            return true;
                        } else {
                            return false;
                        }
                    }

                    if(name == 'end_radius_right_wall_facade'){
                        if(this.item.params.cabinet.right_wall == 'facade_simple'){
                            return true;
                        } else {
                            return false;
                        }
                    }

                    if(type == 'washer' && name == 'rotation'){
                        if(this.item.params[type][name] == undefined){
                            return base_params[type][name];
                        } else {

                            return parseInt(this.item.params[type][name]) * (180/Math.PI);
                        }
                    }

                    if(this.item.params[type] == undefined){
                        return base_params[type][name];
                    }
                    if(this.item.params[type][name] == undefined){
                        if(type == 'doors' && name == 'group'){
                            if(cabinet.params.cabinet_group == 'top') return 'top';
                        }

                        return base_params[type][name];
                    } else {
                        return this.item.params[type][name]
                    }

                } else {

                    if(!this.item.params[type]) this.item.params[type] = {};
                    if(!this.item.params[type][index]) this.item.params[type][index] = {};
                    if(this.item.params[type][index][name] == undefined){
                        if(type == 'doors' && name == 'group'){
                            if(cabinet.params.cabinet_group == 'top') return 'top';
                        }

                        return base_params[type][name];
                    } else {
                        return this.item.params[type][index][name]
                    }
                }
            },

            get_type_params_sections(type, index, section_index, name){
                    if(this.item.params.sections[section_index][type][index][name] == undefined){
                        if(type == 'doors' && name == 'group'){
                            if(cabinet.params.cabinet_group == 'top') return 'top';
                        }
                        return base_params[type][name];
                    } else {
                        return this.item.params.sections[section_index][type][index][name]
                    }
            },

            get_washer(){
                if(this.item.params.washer && this.item.params.washer.id !== undefined){
                    return this.item.params.washer.id
                } else {
                    return 0
                }
            },
            set_washer: async function (e){
                if(e == 0){
                    Vue.set(this.item.params, 'washer', {})
                    cabinet.remove_wahser_new(true)
                } else {

                    let w_item = await this.add_washer_to_lib();

                    if(!this.item.params.washer) Vue.set(this.item.params, 'washer', {})

                    let params =  {
                        id: w_item.id,
                        offset_x: this.item.params.washer.offset_x ? this.item.params.washer.offset_x : 0,
                        offset_z: this.item.params.washer.offset_z ? this.item.params.washer.offset_z : 0,
                        flipped: 0,
                        material: 0,
                        rotation: 0
                    }


                    Vue.set(this.item.params, 'washer', copy_object(params))
                    cabinet.params.washer = copy_object(params);
                    cabinet.build();
                }



            },
            add_washer_to_lib: async function(id){
                if(this.$refs.washes_picker) {
                    let w_item = this.$refs.washes_picker.get_item_data();
                    w_item = JSON.parse(w_item.data)
                    w_item.materials = ['w_mat_0']
                    washes_lib.items[w_item.id] = w_item;
                    return w_item;
                } else {
                    let w_item = await promise_request(glob.base_url + '/catalog/get_item/' + 'washes' + '/' + id);
                    w_item = JSON.parse(w_item.data)
                    w_item.materials = ['w_mat_0']
                    washes_lib.items[w_item.id] = w_item;
                    return w_item;
                }
            },
            get_data: function () {
                return JSON.parse(JSON.stringify(this.item))
            },

            resize_viewport(){
                document.getElementById('main_app').style.display = 'block'
                document.getElementById('preview_block').append(document.getElementById('main_app'))
                setTimeout(function () {
                    resize_viewport();
                },10)
            },

            get_json: function(){
              document.getElementById('json_input').value = JSON.stringify(this.get_data().params,null, 4);
            },

            apply_json: function(){
                let scope = this;
                try {
                    let params = JSON.parse(document.getElementById('json_input').value);
                    Vue.set(scope.item, 'params', JSON.parse(document.getElementById('json_input').value))

                    if(params.variables){
                        this.variables = [];
                        Object.keys(params.variables).forEach(function (k) {
                            scope.variables.push(params.variables[k])
                        })
                    }

                    scope.rebuild_module();
                    toastr.success(document.getElementById('success_message').innerHTML);
                } catch (e) {
                    console.log(e)
                    toastr.error(document.getElementById('mod_json_error').innerHTML)
                }
            },

            check_w2: function(w1, w2){
                if(w2 != w1){
                    Vue.delete(this.item.params.doors[0], 'available_opens')
                    Vue.set(this.item.params.doors[0], 'type', 'corner_90_ltr')
                }
                // if(w2 > w1){
                //     Vue.set(this.item.params.cabinet, 'orientation', 'right');
                //     Vue.set(this.item.params.cabinet, 'width2', w2);
                //
                // } else {
                //     Vue.set(this.item.params.cabinet, 'orientation', 'left');
                //     Vue.set(this.item.params.cabinet, 'width2', w2);
                // }
                // Vue.delete(this.item.params.doors[0], 'available_opens')
            },


            rebuild_module: function(){
                if(!cabinet) return;
                if(cabinet) cabinet.delete();

                dControls.s_helper.hide();


                cabinet = sc.add_object('cabinet', this.get_data().params, false);
                cabinet.position.set(100,0,100)
            },


            add_accessory: function(id){
                if(!this.item.params.accessories_default){
                    Vue.set(this.item.params, 'accessories_default', [])
                }

                let flag = 0;

                for (let i = 0; i < this.item.params.accessories_default.length; i++){
                    if(this.item.params.accessories_default[i].id == id){
                        flag = 1;
                        this.item.params.accessories_default[i].count++
                    }
                }

                if(flag == 0){
                    this.item.params.accessories_default.push({
                        id: id,
                        count: 1
                    })
                }

            },

            remove_accessory: function(index){
                this.item.params.accessories_default.splice(index, 1)
            },

            correct_type_name: function(key){
                if(key == 'door') return this.lang('hinges')
                if(key == 'locker') return this.lang('hinges_lockers')
                if(key == 'simple_top') return this.lang('hinges_simple_top')
                if(key == 'double_top') return this.lang('hinges_double_top')
                if(key == 'front_top') return this.lang('hinges_front_top')

                for (let i = 0; i < accessories_types.length; i++){
                    if(key == accessories_types[i].key) return  accessories_types[i].name;
                }

            },



            add_accessory_type: function(key){

                if(this.item.params.accessories_types[key]) return;


                Vue.set(this.item.params.accessories_types, key, {
                    count: 0,
                    excluded: []
                })


            },
            remove_accessory_type: function(key){
                Vue.delete(this.item.params.accessories_types, key);
            },
            acc_exclude: function(type, item){

                let ind = type.excluded.indexOf(item.id);

                if(ind > -1){
                    type.excluded.splice(ind, 1)
                } else {
                    type.excluded.push(item.id);
                }
            },

            select_template: function (template, custom) {
                let scope = this;

                setTimeout(function () {
                    Vue.set(scope.item, 'icon', template.icon);
                },1000)


                Vue.set(this.item, 'template_id', template.id);
                Vue.set(this.item, 'is_custom_template', custom);
                // Vue.set(this.item, 'variants', template.variants);

                let params = JSON.parse(template.params).params;

                if(!params.variants){
                    params.variants = [];
                }

                if(!params.shelves) params.shelves = [];
                if(!params.doors) params.doors = [];
                if(!params.lockers) params.lockers = [];
                if(!params.accessories_default) params.accessories_default = [];

                for (let i = 0; i < params.doors.length;i++){
                    if(params.doors[i].facade) delete params.doors[i].facade
                    if(params.doors[i].additional_materials) delete params.doors[i].additional_materials
                    if(params.doors[i].thickness) delete params.doors[i].thickness
                    if(params.doors[i].furniture_visible) delete params.doors[i].furniture_visible
                    if(params.doors[i].flip) delete params.doors[i].flip
                }

                for (let i = 0; i < params.lockers.length;i++){
                    if(params.lockers[i].facade) delete params.lockers[i].facade
                    if(params.lockers[i].additional_materials) delete params.lockers[i].additional_materials
                    if(params.lockers[i].thickness) delete params.lockers[i].thickness
                    if(params.lockers[i].furniture_visible) delete params.lockers[i].furniture_visible
                    if(params.lockers[i].flip) delete params.lockers[i].flip
                }


                if(this.fixed_material != null && this.fixed_material != 0) params.fixed_facade_material = this.fixed_material
                if(this.sizes_limit != null) params.sizes_limit = this.sizes_limit


                if(shift_pressed){
                    console.log(this.item.params.variants)
                    params.variants = copy_object(this.item.params.variants);
                }



                Vue.set(this.item, 'params', params);



                if(this.variables){
                    let obj = {};

                    for (let i = 0; i < this.variables.length; i++){
                        let v = this.variables[i];
                        obj[v.key] = v;
                    }

                    Vue.set(this.item.params,'variables', obj)
                }


                if(!shift_pressed) {
                    if (this.item.params.variants) {
                        for (let i = 0; i < this.item.params.variants.length; i++) {
                            this.item.params.variants[i].price = '';
                            this.item.params.variants[i].name = '';
                            this.item.params.variants[i].code = '';

                            this.item.params.variants[i].default == true ? this.item.params.variants[i].default = 1 : this.item.params.variants[i].default = 0;

                        }
                    }
                }




                if(cabinet) cabinet.delete();
                dControls.s_helper.hide();

                console.log(this.get_data().params);
                cabinet = sc.add_object('cabinet', this.get_data().params, false);
                cabinet.position.set(100,0,100)
            },

            add_variant:function () {
                this.item.params.variants.push(
                    {
                        name: '',
                        code: '',
                        price: '',
                        width: '',
                        height: '',
                        depth: '',
                        default: 0
                    }
                )

                if(this.item.params.variants.length == 1)  this.item.params.variants[0].default = 1;

            },
            remove_variant(index){

                let fl = 0;
                if(this.item.params.variants[index].default == 1) fl = 1;

                this.item.params.variants.splice(index,1);

                if(fl == 1){
                    if(this.item.params.variants.length) this.item.params.variants[0].default = 1;
                }


            },

            process_icon_file: function (event) {
                if(event.target.files.length){

                    if(!check_filename(event.target.files[0])){
                        event.target.value = '';
                        alert(document.getElementById('lang_incorrect_chars_in_file_name').innerHTML);
                        return;
                    }

                    this.item.icon_file = event.target.files[0];
                } else {
                    this.item.icon_file = '';
                }
            },
            get_icon_src:function (file) {
                if(this.item.icon != '' && this.item.icon_file == '') return this.item.icon;
                if(this.item.icon_file != ''){
                    if(typeof this.item.icon_file == 'object') return URL.createObjectURL(file);
                    return this.item.icon_file
                }
                if(this.item.icon == '' &&  this.item.icon_file == '') return 'https://via.placeholder.com/120x120';
            },
            remove_icon: function () {
                this.item.icon = '';
                this.item.icon_file = '';
                this.$refs.icon_file.value = '';

                if(this.item.template_id != ''){

                    if(this.item.is_custom_template == 1){
                        for (let i = 0; i < this.custom_templates_list.length; i++){
                            if(this.custom_templates_list[i].id == this.item.template_id) this.item.icon = this.custom_templates_list[i].icon
                        }
                    } else {
                        for (let i = 0; i < this.templates_list.length; i++){
                            if(this.templates_list[i].id == this.item.template_id) this.item.icon = this.templates_list[i].icon
                        }
                    }


                }

            },
            on_drag({ relatedContext, draggedContext }) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            },
            process_default: function (index) {
                for (let i = 0; i < this.item.params.variants.length; i++){

                    if(i == index){
                        this.item.params.variants[i].default = 1;
                    } else {
                        this.item.params.variants[i].default = 0;
                    }
                }
            },
            show_swal: function (index) {
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {
                    scope.remove_variant(index)
                });
            },
            submit: function (e) {
                e.preventDefault();
                let scope = this;
                let data = scope.get_data();
                data.icon = data.icon.replace(acc_url,'');
                delete data.icon_file;

                scope.errors = [];


                if(!data.params.variants) data.params.variants = [];

                for (let i = data.params.variants.length - 1; i >= 0; i--){
                    if(data.params.variants[i].width == '' || data.params.variants[i].height == '' || data.params.variants[i].height == ''){
                        data.params.variants.splice(i,1)
                    }
                }




                if(!data.params || !Object.keys(data.params).length)scope.errors.push(lang_data['error_no_template_selected']);
                if(!data.params.variants.length) scope.errors.push(lang_data['error_no_variants']);
                if(data.category == 0) scope.errors.push(lang_data['error_no_category_selected']);

                console.log(data);

                if(scope.errors.length) return false;


                if(this.fixed_material != null && this.fixed_material != 0) data.params.fixed_facade_material = this.fixed_material
                if(this.sizes_limit != null) data.params.sizes_limit = this.sizes_limit


                let formData = new FormData();

                if(scope.item.icon_file != ''){
                    formData.append('icon', scope.item.icon_file)
                }

                data.params = JSON.stringify({params:data.params});

                formData.append('data', JSON.stringify(data));

                $.ajax({
                    url : $('#sub_form').attr('action'),
                    type : 'POST',
                    data : formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success : function(msg) {
                        console.log(msg)
                        let response = JSON.parse(msg);
                        // console.log(response);
                        //
                        if(response['errors']){
                            for (let i = 0; i < response['errors'].length; i++){
                                scope.errors.push(lang_data[response['errors'][i]])
                            }
                        }
                        if(response['success']){
                            window.location = document.getElementById('form_success_url').value
                        }

                    }
                });

                return false;

            }
        }
    });

}




function get_base_data(){
    let data = {};

    let item_id = document.getElementById('item_id');
    if(item_id != null){
        data.item_id = item_id.value;
    } else {
        data.item_id = 0;
    }

    let controller_name = document.getElementById('footer_controller_name');
    if(controller_name != null){
        data.controller_name = controller_name.value;
    } else {
        data.controller_name = '';
    }


    data.base_url = document.getElementById('ajax_base_url').value;
    data.acc_url = document.getElementById('acc_base_url').value;
    data.lang = JSON.parse(document.getElementById('lang_json').value);


    data.is_common = 0;

    if(document.getElementById('footer_is_common')){
        data.is_common = document.getElementById('footer_is_common').value;
    }

    data.c_method_name = 'categories_get';
    if(data.is_common) data.c_method_name += '_common';

    data.i_method_name = 'get_item';
    if(data.is_common) data.i_method_name += '_common';
    return data;
}

function create_tree(dataset) {
    let hashTable = Object.create(null)
    dataset.forEach(function (aData) {
        hashTable[aData.id] = aData;
        aData.children = [];
    })
    let dataTree = []
    dataset.forEach(function (aData) {
        if (aData.parent && aData.parent > 0) hashTable[aData.parent].children.push(hashTable[aData.id])
        else dataTree.push(hashTable[aData.id])
    })
    return dataTree
}




function get_hash(data) {
    return data.reduce(function(map, obj) {
        map[obj.id] = obj;
        return map;
    }, {});
}

function check_filename(file) {
    let split_arr = file.name.split('.');
    if(split_arr.length > 2) return false;
    return !(split_arr[0].match(/[^\u0000-\u007f]/));
}

function promise_request (url) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.send();
    return new Promise(function (resolve, reject) {
        xhr.onreadystatechange = function (e) {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let data = JSON.parse(xhr.responseText);
                resolve(data);
            } else if (xhr.readyState == 4) {
                reject();
            }
        };
    });
}

function send_xhr_get(url, ready) {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', url);
    xhr.send();
    xhr.addEventListener("readystatechange", function () {
        if (xhr.readyState === 4) {
            if(typeof ready == "function") ready(xhr);
        }
    });
}

function send_xhr_post(url, data, ready) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", url);
    xhr.send(data);
    xhr.addEventListener("readystatechange", function () {
        if (xhr.readyState === 4) {
            ready(xhr);
        }
    });
}

function copy_object(obj) {
    return JSON.parse(JSON.stringify(obj))
}

function save_file( blob, filename ) {
    var link = document.createElement( 'a' );
    link.style.display = 'none';
    document.body.appendChild( link );
    link.href = URL.createObjectURL( blob );
    link.download = filename;
    link.click();
    link.remove();
}



async function get_tree_async(url, no_item) {
    let result = {};
    let data = await promise_request(url)

    if(no_item){
        data.unshift(no_item)
    }

    console.log(data)

    result.tree = create_tree(copy_object(data));
    result.ordered = flatten(copy_object(result.tree))
    result.hash = get_hash(copy_object(data))

    return result;
}


function debounce(callback) {
    clearTimeout(input_timeout);
    input_timeout = setTimeout(callback, input_timeout_time);
}