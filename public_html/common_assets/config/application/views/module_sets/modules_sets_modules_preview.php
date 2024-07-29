<div  id="sub_form">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Сет превью</h2>

        </div>
        <div class="col-lg-2">

        </div>
    </div>


    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="modules_wrapper">
                        <div  class="module" v-for="item in $options.items">
                            <img @click="set_cabinet(item, $event)"  class="img-fluid" :src="item.icon" >
                        </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-content">

                        <div id="bplanner_app">
                            <div style="height: 500px" id="viewport">

                                <div class="make_icon">
                                    <button data-toggle="dropdown" class="btn btn-sm btn-success dropdown-toggle" aria-expanded="false">Сделать иконку</button>
                                    <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 33px; left: 0px; will-change: top, left;">
                                        <li @click="make_icon($event)" class="dropdown-item">Один модуль</li>
                                        <li @click="make_icons()" class="dropdown-item">Все модули</li>
                                    </ul>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            <div class="ibox">
                <div class="ibox-content">
                        <a  class="btn btn-sm btn-default btn-outline mr-1" v-bind:href="ajax_base_url + '/catalog/items_common/modules_sets'" role="button">Вернуться к наборам модулей</a>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <?php if(isset($set_id)):?>
        <input id="set_id" ref="set" value="<?php echo $set_id ?>" type="hidden">
    <?php endif;?>

    <?php if(isset($common)):?>
        <input id="is_common" ref="set" value="<?php echo $common ?>" type="hidden">
    <?php endif;?>

</div>

<style>
    .modules_wrapper{
        height: 500px;
        overflow: auto;
    }

    .module{
        width: 100%;
    }

    .make_icon{
        position: absolute;
        top: 5px;
        right: 20px;
        z-index: 9;
    }

</style>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/3d_preview.php'; ?>
<script>
    console.log(view_mode)
    view_mode = true;
    //view_path = "<?php //echo $this->config->item('const_path')?>//"
</script>
<script src="/common_assets/libs/vue.min.js"></script>
<script src="/common_assets/libs/vue/vue_select/vue-select.js"></script>
<script src="/common_assets/admin_js/common_functions.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function(){

        ajax_base_url = document.getElementById('ajax_base_url').value;
        acc_url = document.getElementById('acc_base_url').value;
        fs_data = {};
        let promises = [];

        promises.push(promise_request(ajax_base_url + '/modules_sets_modules/get_preview_data_ajax/'+ document.getElementById('set_id').value + '/'))

        Promise.all(promises).then(function (results) {
            console.log(results)



            init_vue(results[0]);

        })


        function init_vue(data){
            app = new Vue({
                el: '#sub_form',


                created: function(){

                    for (let i = 0; i < data.items.length; i++){
                        data.items[i].params = JSON.parse(data.items[i].params).params;
                    }

                    this.$options.items = data.items;
                    this.$options.cabinet = null;
                    fs_data = {
                        1: JSON.parse(data.facade_set.data)
                    }

                    fs_data[1].materials = [];
                    fs_data[1].facades.additional_materials = {};

                    let start_id = 1000000000

                    for (let i = 0; i < fs_data[1].facades.materials_n.length; i++){

                        let it = fs_data[1].facades.materials_n[i];

                        fs_data[1].materials.push({
                            id: start_id + i,
                            add_params: it.add_params,
                            params: it.params,
                            name: it.name,
                            category: 0,
                            type: 'Standart',
                            code: ''
                        })

                        fs_data[1].facades.additional_materials[it.key] = {
                            fixed: 1,
                            key: it.key,
                            materials: [],
                            name: it.name,
                            required: false,
                            selected: start_id + i
                        }

                    }



                    fs_data[1].modules = "/common_assets/data/empty_modules.json"

                    let name = 'fs';
                    let key = 100000;

                    let category = {
                        id: key,
                        parent: 0,
                        code:'',
                        name: name,
                        items: [],
                        categories: []
                    }



                    let in2 = setInterval(function () {
                        if(facades_lib.tree){

                            Object.keys(fs_data).forEach(function (k) {
                                // console.log(fs_data[k])

                                let materials_category = {
                                    id: key + '-' + k,
                                    active: 0,
                                    parent: 0,
                                    code:'',
                                    name: name,
                                    items: [],
                                    categories: []
                                }

                                let facade = copy_object(fs_data[k].facades)
                                category.items.push(facade)

                                facade.id = key + '-' + k;
                                facade.category = key;
                                facade.icon = fs_data[k].icon;
                                facade.materials = [key + '-' + k]
                                Object.keys(facade.additional_materials).forEach(function (mat_key) {
                                    facade.additional_materials[mat_key].selected = key + '-' + k + '-' + facade.additional_materials[mat_key].selected;
                                })

                                facade.is_fs = true;
                                facade.fs_id = k

                                if(fs_data[k].cornice){
                                    if(fs_data[k].cornice.model !=''){

                                        let co = {
                                            model: fs_data[k].cornice.model,
                                            corner_model: fs_data[k].cornice.corner_model,
                                            corner_model_45: fs_data[k].cornice.corner_model_45,
                                            corner_model_45_code: fs_data[k].cornice.corner_model_45_code,
                                            corner_model_45_name: fs_data[k].cornice.corner_model_45_name,
                                            corner_model_code: fs_data[k].cornice.corner_model_code,
                                            corner_model_name: fs_data[k].cornice.corner_model_name,

                                            model_code: fs_data[k].cornice.model_code,
                                            model_name: fs_data[k].cornice.model_name,
                                            name: fs_data[k].cornice.name,
                                            radius_model: fs_data[k].cornice.radius,
                                            radius_code: fs_data[k].cornice.radius_code,
                                            radius_name: fs_data[k].cornice.radius_name
                                        }

                                        cornice_lib.add_item(key + '-' + k, co)
                                    }
                                }

                                if(fs_data[k].cokol){
                                    if(fs_data[k].cokol.model !=''){
                                        cokol_lib.add_item(key + '-' + k, fs_data[k].cokol)
                                    }
                                }

                                if(fs_data[k].cokol_top){
                                    if(fs_data[k].cokol_top.model !=''){
                                        cokol_top_lib.add_item(key + '-' + k, fs_data[k].cokol_top)
                                    }
                                }

                                if(fs_data[k].molding){
                                    if(fs_data[k].molding.model !=''){
                                        molded_lib.add_item(key + '-' + k, fs_data[k].molding)
                                    }
                                }

                                facades_lib.add_item(key + '-' + k, facade)

                                let materials = copy_object(fs_data[k].materials)

                                for (let i = 0; i < materials.length; i++){
                                    materials[i].id = key + '-' + k + '-' + materials[i].id;
                                    materials_lib.add_item(materials[i].id, materials[i])
                                    materials_category.items.push(materials[i])
                                }

                                materials_lib.add_category(key + '-' + k, materials_category)



                            })


                            facades_lib.add_category(key, category)
                            facades_lib.tree.push(category)

                            clearInterval(in2)
                            // test_fs('100000-1')


                        }
                    }, 100)

                    demo_mode = false;

                },

                data: {
                    item: null,
                    event: null
                },
                mounted(){

                    let scope = this;

                        let inter = setInterval(function () {

                            if(scene !== undefined && fs_data[1].modules){

                                clearInterval(inter);

                                console.log('ready')
                                resize_viewport();


                                materials_lib.add_item(999, {
                                    "params": {
                                        "color": "#ffffff",
                                        "map": "/common_assets/images/materials/1503.jpg",
                                        "roughness": 0.8,
                                        "metalness": 0,
                                        "transparent": false
                                    },
                                    "add_params": {
                                        "real_width": 800,
                                        "real_height": 600,
                                        "stretch_width": false,
                                        "stretch_height": false,
                                        "wrapping": "mirror"
                                    },
                                    "id": 1503,
                                    "name": "Чёрный королевский жемчуг",
                                    "code": null,
                                    "category": 58,
                                    "type": "Standart"
                                })

                                project_settings.selected_materials.tabletop = 999

                                project_settings.handle.top.model = {
                                    "id": 14,
                                    "category": 5,
                                    "name": "Классика серебро черное",
                                    "icon": "/common_assets/models/handles/15/icon_15.jpg",
                                    "model": "/common_assets/models/handles/15/model_15.fbx",
                                    "type": "common",
                                    "material": {
                                        "params": {
                                            "color": "#ffffff",
                                            "roughness": "0.4",
                                            "metalness": "0",
                                            "map": "/common_assets/models/handles/15/map_15.jpg"
                                        },
                                        "add_params": {
                                            "real_width": "512",
                                            "real_height": "512",
                                            "stretch_width": "1",
                                            "stretch_height": "1",
                                            "wrapping": "mirror"
                                        },
                                        "type": "Standart"
                                    },
                                    "sizes": [
                                        {
                                            "width": "126",
                                            "height": "37",
                                            "depth": "26",
                                            "code": "",
                                            "axis_size": "",
                                            "price": ""
                                        }
                                    ]
                                }

                                project_settings.handle.bottom.model = project_settings.handle.top.model
                                project_settings.handle.top.orientation = "horizontal"
                                project_settings.handle.bottom.orientation = project_settings.handle.top.orientation

                                // cabinet = sc.add_object('cabinet', scope.get_data().params, false);
                                // cabinet.position.set(100, 0, 100)
                                test_fs('100000-1')



                            }

                        }, 100)






                },

                computed:{

                },
                methods: {
                    set_cabinet(item, event){
                        if(this.$options.cabinet)  this.$options.cabinet.delete();

                        this.item = item;
                        this.event = event.target
                        this.$options.cabinet = sc.add_object('cabinet', item.params, false);
                        this.$options.cabinet.position.set(100, 0, 100)
                    },
                    make_icons: async function(){
                        let viewport = document.getElementById('viewport')
                        viewport.style.position = 'fixed';
                        viewport.style.left = 0;
                        viewport.style.top = 0;
                        viewport.style.zIndex = 9999;
                        viewport.style.width = '1000px';
                        viewport.style.height = '1000px';

                        resize_viewport();
                        room.hide();

                        for (let i = 0; i < this.$options.items.length; i++){
                            let mod = await add_mod_for_scr(copy_object(this.$options.items[i].params))
                            let scr = await make_mod_scr(mod)

                            let fd = new FormData();
                            fd.append('id', this.$options.items[i].id)
                            fd.append('icon', scr)
                            fd.append('set_id', document.getElementById('set_id').value)

                            let response = await promise_post( ajax_base_url + '/modules_sets_modules/make_icon', fd)
                            this.$options.items[i].icon = response;
                            console.log(response)
                        }





                        setTimeout(function () {
                            viewport.style.position = 'initial';
                            viewport.style.left = 0;
                            viewport.style.top = 0;
                            viewport.style.width = 'auto';
                            viewport.style.height = '500px';
                            resize_viewport();
                        },1000)

                    },
                    make_icon: async function(){

                        console.log(this.event)

                        if(!this.$options.cabinet) alert('Выберите модуль')

                        let scope = this;
                        let viewport = document.getElementById('viewport')
                        viewport.style.position = 'fixed';
                        viewport.style.left = 0;
                        viewport.style.top = 0;
                        viewport.style.zIndex = 9999;
                        viewport.style.width = '1000px';
                        viewport.style.height = '1000px';

                        resize_viewport();
                        room.hide();

                        let mod = await add_mod_for_scr(copy_object(this.$options.cabinet.params))
                        let scr = await make_mod_scr(mod)


                        room.show();

                        let fd = new FormData();
                        fd.append('id', scope.item.id)
                        fd.append('icon', scr)
                        fd.append('set_id', document.getElementById('set_id').value)

                        let response = await promise_post( ajax_base_url + '/modules_sets_modules/make_icon', fd)

                        scope.event.src = response;

                        clear_project();
                        scope.$options.cabinet = sc.add_object('cabinet', scope.item.params, false);
                        scope.$options.cabinet.position.set(100, 0, 100)
                        camera.position.set(34, 203.00000000000003, 401)
                        controls.target.set(238, 64, 17)
                        controls.update();


                        setTimeout(function () {
                            viewport.style.position = 'initial';
                            viewport.style.left = 0;
                            viewport.style.top = 0;
                            viewport.style.width = 'auto';
                            viewport.style.height = '500px';
                            resize_viewport();


                        },1000)


                    }

                }
            });

        }


        async function add_mod_for_scr(params) {
            clear_project();
            return new Promise(function (resolve,reject) {
                let cab = sc.add_object('cabinet', params, false)

                let box = box3.setFromObject(cab).getSize();

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

                if(cab.params.variants[0].height > 2100){
                    camera.position.set(155,70,225);
                }
                controls.update();

                if (cab.params.cabinet.type === 'corner') {
                    cab.rotation.y = Math.PI / 3;
                }

                if (
                    cab.params.cabinet.type === 'end_corner_facade' && cab.cabinet.orientation === 'left' ||
                    cab.params.cabinet.type === 'corner_open' && cab.params.cabinet.orientation === 'left' ||
                    cab.params.cabinet.type === 'end_radius_open' && cab.params.cabinet.orientation === 'left' ||
                    cab.params.cabinet.type === 'end_radius_facade' && cab.params.cabinet.orientation === 'left' ||
                    cab.params.cabinet.type === 'false_facade_facade' && cab.params.cabinet.orientation === 'left'
                ) {
                    camera.position.set(
                        -140,
                        64,
                        205
                    );
                    controls.update();
                }

                cab.position.set(
                    0,- box.y / 2,0
                );
                cab.sizes.visible = false;
                setTimeout(function () {
                    resolve( cab )
                },2000)

            })
        }

        async function make_mod_scr(cab) {

            return new Promise(function (resolve,reject) {
                setTimeout(function () {

                    var canvas = document.createElement('canvas');

                    if(cab.params.variants){
                        if(cab.params.variants[0].height > 1250){
                            canvas.width = 400;
                            canvas.height = 800;
                            var ctx = canvas.getContext('2d');

                            var left0 = renderer.domElement.width / 2 - 250;
                            var top0 = renderer.domElement.height / 2 - 500;


                            ctx.drawImage(renderer.domElement,left0,top0,500,1000,0,0,400,800);
                        } else {
                            canvas.width = 400;
                            canvas.height = 400;
                            var ctx = canvas.getContext('2d');

                            var left0 = renderer.domElement.width / 2 - 250;
                            var top0 = renderer.domElement.height / 2 - 250;


                            ctx.drawImage(renderer.domElement,left0,top0+20,500,500,0,0,400,400);
                        }
                    } else {
                        canvas.width = 400;
                        canvas.height = 400;
                        var ctx = canvas.getContext('2d');

                        var left0 = renderer.domElement.width / 2 - 250;
                        var top0 = renderer.domElement.height / 2 - 250;


                        ctx.drawImage(renderer.domElement,left0,top0+20,500,500,0,0,400,400);
                    }






                    var data = canvas.toDataURL('image/jpeg', 0.5);

                    resolve(data);

                }, 1000)
            })


        }

    });

</script>