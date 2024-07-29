function add_size_row(){
    var row = $('<div class="panel panel-default panel-body sizes_row"></div>');
    var remove_panel = $('<span class="glyphicon glyphicon-trash remove_panel"></span>');

    var width_col = $('<div class="col-xs-4"></div>');
    var width_fg = $('<div class="form-group"></div>');
    var width_label = $('<label>Ширина объекта (мм)</label>');
    var width_input = $('<input type="number" min="0" class="form-control width_input" value="">');

    var height_col = $('<div class="col-xs-4"></div>');
    var height_fg = $('<div class="form-group"></div>');
    var height_label = $('<label>Высота объекта (мм)</label>');
    var height_input = $('<input type="number" min="0" class="form-control height_input" value="">');

    var depth_col = $('<div class="col-xs-4"></div>');
    var depth_fg = $('<div class="form-group"></div>');
    var depth_label = $('<label>Глубина объекта (мм)</label>');
    var depth_input = $('<input type="number" min="0" class="form-control depth_input" value="">');

    var name_col = $('<div class="col-xs-4"></div>');
    var name_fg = $('<div class="form-group"></div>');
    var name_label = $('<label>Название</label>');
    var name_input = $('<input type="text" class="form-control name_input">');

    var code_col = $('<div class="col-xs-4"></div>');
    var code_fg = $('<div class="form-group"></div>');
    var code_label = $('<label>Артикул</label>');
    var code_input = $('<input type="text" class="form-control code_input">');

    var price_col = $('<div class="col-xs-4"></div>');
    var price_fg = $('<div class="form-group"></div>');
    var price_label = $('<label>Цена</label>');
    var price_input = $('<input type="number" class="form-control price_input">');

    var def_col = $('<div class="col-xs-4"></div>');
    var def_fg = $('<div class="form-group"></div>');
    var def_label = $('<label>По умолчанию?</label>');
    var def_input = $('<select class="form-control default_input"><option value="0">Нет</option><option value="1">Да</option></select>');


    name_col.append(name_fg);
    name_fg.append(name_label);
    name_fg.append(name_input);
    row.append(name_col);

    code_col.append(code_fg);
    code_fg.append(code_label);
    code_fg.append(code_input);
    row.append(code_col);

    price_col.append(price_fg);
    price_fg.append(price_label);
    price_fg.append(price_input);
    row.append(price_col);




    width_col.append(width_fg);
    width_fg.append(width_label);
    width_fg.append(width_input);
    row.append(width_col);

    height_col.append(height_fg);
    height_fg.append(height_label);
    height_fg.append(height_input);
    row.append(height_col);

    depth_col.append(depth_fg);
    depth_fg.append(depth_label);
    depth_fg.append(depth_input);
    row.append(depth_col);

    def_col.append(def_fg);
    def_fg.append(def_label);
    def_fg.append(def_input);
    row.append(def_col);

    row.append(remove_panel);

    remove_panel.click(function () {
        row.remove();
        add_def_change();
    });



    return row;

}


function add_def_change(){
    $('.default_input').off('change').change(function () {
        var scope = $(this);

        if(scope.val() == 1){
            $('.default_input').each(function () {
                $(this).val(0)
            });
            scope.val(1);
        }


        // console.log(scope.val());

    })
}

function parse_module_params(data){
    if(data.params.variants){

        $('.sizes_row').each(function () {
            $(this).remove();
        });

        var variants = data.params.variants;

        for(var i = 0; i < variants.length; i++){
            var row = add_size_row();
            row.insertBefore($('.add_size'));

            if(variants[i].default !== true){
                variants[i].default = false;
            }

            row.find('.width_input').val(variants[i].width);
            row.find('.height_input').val(variants[i].height);
            row.find('.depth_input').val(variants[i].depth);
            row.find('.name_input').val(variants[i].name);
            row.find('.code_input').val(variants[i].code);
            row.find('.price_input').val(variants[i].price);
            row.find('.default_input').val(variants[i].default * 1)
        }

        add_def_change();

        if(cab !== ''){
            scene.remove(cab)
        }
        cab = new Cabinet_new(JSON.parse(JSON.stringify(data.params)));


        scene.add(cab);

        cab.box.update();

        cab.position.y = -cab.box.getSize().y / 2;
    } else {

        var row = add_size_row();
        row.insertBefore($('.add_size'));



        row.find('.width_input').val(data.params.cabinet.width);
        row.find('.height_input').val(data.params.cabinet.height);
        row.find('.depth_input').val(data.params.cabinet.depth);
        row.find('.name_input').val(data.params.cabinet.name);
        row.find('.code_input').val(data.params.cabinet.code);
        row.find('.price_input').val(data.params.cabinet.price);
        row.find('.default_input').val(1)


        if(cab !== ''){
            scene.remove(cab)
        }
        cab = new Cabinet_new(JSON.parse(JSON.stringify(data.params)));


        scene.add(cab);

        cab.box.update();

        cab.position.y = -cab.box.getSize().y / 2;
    }
}
texture_loader = new THREE.TextureLoader();
shadow_quality = {
    no: 2,
    low: 256,
    medium: 1024,
    high: 2048
};
var models_catalog = {
    categories:[],
    items:[
        {
            id: 1000000,
            model: '/common_assets/models/bottle/bottle.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#dadada'
                }
            },
            variants: [
                {
                    width: 105,
                    height: 515,
                    depth: 450
                }
            ],
            draggable: false,
        },
        {
            id: 2000000,
            model: '/common_assets/models/tech/built_in_cookers/1/model.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/1/map.png',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 596,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 2000001,
            model: '/common_assets/models/tech/built_in_cookers/1/model.fbx',
            material: {
                type: 'Phong',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/built_in_cookers/1/map_2.png',
                }
            },
            variants: [
                {
                    width: 596,
                    height: 596,
                    depth: 527
                }
            ],
            draggable: false,
        },
        {
            id: 3000000,
            model: '/common_assets/models/tech/cooking_panels/1/model.fbx',
            material: {
                type: 'Standart',
                params: {
                    color: '#ffffff',
                    map: '/common_assets/models/tech/cooking_panels/1/map.jpg',
                    metalness: 0.1,
                    roughness: 0.1
                }
            },
            variants: [
                {
                    width: 583,
                    height: 8,
                    depth: 512
                }
            ],
            draggable: false,
        },
        {
            id: 4000000,
            model: '/common_assets/models/tech/sinks/1/sink.fbx',
            material: {
                type: 'Standart',
                params: {
                    color: '#dadada',
                    // map: '/common_assets/models/tech/cooking_panels/1/map.jpg',
                    metalness: 0.1,
                    roughness: 0.1
                }
            },
            variants: [
                {
                    width: 496,
                    height: 343,
                    depth: 480
                }
            ],
            draggable: false,
        },

    ]
};
$(document).ready(function () {


    $('.parse_params').click(function () {

        var module_params = $('#module_params');

        var json = module_params.val();

        if(json.charAt(0) !== '{'){
            json = '{' + json + '}';
        }

        console.log(json);

        try{
            var data = JSON.parse(json);

            if(data.params === undefined){
                data = {params:data}
            }

            module_params.val(JSON.stringify(data));

            parse_module_params(data);


        } catch (e) {
            alert('Ошибка в синтаксисе JSON')
        }
    });

    $('#module_mode').change(function () {

        if(cab !== ''){
            scene.remove(cab)
        }

        $('#template_icon').val('');
        $('#template_id').val('');
        $('#module_params').val('');

        $('.sizes_row').each(function () {
            $(this).remove();
        });

        $('.modules_templates_wrapper').find('.selected').removeClass('selected');

        var params_mode_wrapper = $('.params_mode_wrapper');
        var templates_mode_wrapper = $('#templates_wrapper');
        var custom_templates_mode_wrapper = $('#custom_templates_wrapper');

        if ($(this).val() === 'template') {
            params_mode_wrapper.addClass('hidden');
            custom_templates_mode_wrapper.addClass('hidden');
            templates_mode_wrapper.removeClass('hidden');

        } else if( $(this).val() === 'custom_template' ){
            params_mode_wrapper.addClass('hidden');
            templates_mode_wrapper.addClass('hidden');
            custom_templates_mode_wrapper.removeClass('hidden');
        }
        else {

            templates_mode_wrapper.addClass('hidden');
            custom_templates_mode_wrapper.addClass('hidden');
            params_mode_wrapper.removeClass('hidden');
        }

    });

    global_options = {
        mode:'design'
    };

    $('.add_size').click(function () {
        var row = add_size_row();
        row.insertBefore($(this));
        add_def_change();
    });

    $('#category').selectize({
        create: false,
        render: {
            option: function (data, escape) {
                return "<div class='option " + data.class + "'>" + data.text + "</div>"
            }
        }
    });

    cab = '';

    $('.module_template').click(function () {

        if($(this).hasClass('selected')){
            return false;
        }


        if($(this).parents('#templates_wrapper').length){
            $('#is_custom_template').val(0)
        } else {
            $('#is_custom_template').val(1)
        }


        params_json = $(this).find('input').val();


        console.log($(this).attr('data-id'))

        $('#cabinet_params').val(params_json);
        $('#template_icon').val($(this).find('img').attr('src'));
        $('#template_id').val($(this).attr('data-id'));
        params = JSON.parse(params_json);


        if(params.params.variants){

            $('.sizes_row').each(function () {
                $(this).remove();
            });

            variants = params.params.variants;

            for(var i = 0; i < variants.length; i++){
                var row = add_size_row();
                row.insertBefore($('.add_size'));

                if(variants[i].default !== true){
                    variants[i].default = false;
                }

                row.find('.width_input').val(variants[i].width);
                row.find('.height_input').val(variants[i].height);
                row.find('.depth_input').val(variants[i].depth);
                row.find('.name_input').val(variants[i].name);
                row.find('.code_input').val(variants[i].code);
                row.find('.default_input').val(variants[i].default * 1)
            }

            add_def_change();

        } else {
            $('.sizes_row').each(function () {
                $(this).remove();
            });

            var row = add_size_row();
            row.insertBefore($('.add_size'));


            row.find('.width_input').val(params.params.cabinet.width);
            row.find('.height_input').val(params.params.cabinet.height);
            row.find('.depth_input').val(params.params.cabinet.depth);
            row.find('.default_input').val(1)

        }


        $('.modules_templates_wrapper').find('.selected').removeClass('selected');
        $(this).addClass('selected');

        if(cab !== ''){
            scene.remove(cab)
        }
        cab = new Cabinet_new(JSON.parse(JSON.stringify(params.params)));

        scene.add(cab);

        cab.box.update();

        cab.position.y = -cab.box.getSize().y / 2;

    });

    $('#open_doors').click(function (e) {
        e.preventDefault();

        if(cab !== ''){
            for(var d=0; d<cab.doors.children.length; d++){
                cab.doors.children[d].click();
            }

            for(var l=0; l<cab.lockers.children.length; l++){
                cab.lockers.children[l].click();
            }
        }
    });

    box3 = new THREE.Box3();
    handles_catalog = {
        "categories": [
            {
                "id": 1,
                "name": "Ручки",
                "categories": [
                    {
                        "id": 2,
                        "name": "Рейлинги",
                        "parent": 1,
                        "items": [
                            {
                                "id": 9,
                                "category": 2,
                                "name": "Рейлинг 2",
                                "icon": "models/handles/9/icon_9.jpg",
                                "model": "models/handles/9/model_9.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.2",
                                        "metalness": "0.2"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "140",
                                        "height": "16",
                                        "depth": "28",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 1,
                                "category": 2,
                                "name": "Рейлинг 1",
                                "icon": "models/handles/1/icon_1.jpg",
                                "model": "models/handles/1/model_1.fbx",
                                "material": {
                                    "params": {
                                        "color": "#dadada",
                                        "roughness": "0.3",
                                        "metalness": "0"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "175",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "128",
                                        "price": ""
                                    },
                                    {
                                        "width": "207",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "160",
                                        "price": ""
                                    },
                                    {
                                        "width": "239",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "192",
                                        "price": ""
                                    },
                                    {
                                        "width": "367",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "320",
                                        "price": ""
                                    },
                                    {
                                        "width": "495",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "448",
                                        "price": ""
                                    },
                                    {
                                        "width": "527",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "480",
                                        "price": ""
                                    },
                                    {
                                        "width": "623",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "576",
                                        "price": ""
                                    },
                                    {
                                        "width": "815",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "768",
                                        "price": ""
                                    },
                                    {
                                        "width": "911",
                                        "height": "14",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "864",
                                        "price": ""
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "id": 3,
                        "name": "Скобы",
                        "parent": 1,
                        "items": [
                            {
                                "id": 5,
                                "category": 3,
                                "name": "Скоба 4",
                                "icon": "models/handles/5/icon_5.jpg",
                                "model": "models/handles/5/model_5.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.2",
                                        "metalness": "0.2"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "204",
                                        "height": "15",
                                        "depth": "35",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 4,
                                "category": 3,
                                "name": "Скоба 3",
                                "icon": "models/handles/4/icon_4.jpg",
                                "model": "models/handles/4/model_4.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.2",
                                        "metalness": "0.2"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "214",
                                        "height": "16",
                                        "depth": "18",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 3,
                                "category": 3,
                                "name": "Скоба 2",
                                "icon": "models/handles/3/icon_3.jpg",
                                "model": "models/handles/3/model_3.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.2",
                                        "metalness": "0.2"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "179",
                                        "height": "19",
                                        "depth": "20",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 2,
                                "category": 3,
                                "name": "Скоба 1",
                                "icon": "models/handles/2/icon_2.jpg",
                                "model": "models/handles/2/model_2.fbx",
                                "material": {
                                    "params": {
                                        "color": "#dadada",
                                        "roughness": "0.3",
                                        "metalness": "0"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "76",
                                        "height": "12",
                                        "depth": "24",
                                        "code": "",
                                        "axis_size": "64",
                                        "price": ""
                                    },
                                    {
                                        "width": "108",
                                        "height": "12",
                                        "depth": "24",
                                        "code": "",
                                        "axis_size": "96",
                                        "price": ""
                                    },
                                    {
                                        "width": "140",
                                        "height": "12",
                                        "depth": "24",
                                        "code": "",
                                        "axis_size": "128",
                                        "price": ""
                                    },
                                    {
                                        "width": "239",
                                        "height": "12",
                                        "depth": "24",
                                        "code": "",
                                        "axis_size": "215",
                                        "price": ""
                                    },
                                    {
                                        "width": "292",
                                        "height": "12",
                                        "depth": "24",
                                        "code": "",
                                        "axis_size": "280",
                                        "price": ""
                                    },
                                    {
                                        "width": "332",
                                        "height": "12",
                                        "depth": "24",
                                        "code": "",
                                        "axis_size": "320",
                                        "price": ""
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "id": 4,
                        "name": "Кнопки",
                        "parent": 1,
                        "items": [
                            {
                                "id": 7,
                                "category": 4,
                                "name": "Кнопка 2",
                                "icon": "models/handles/7/icon_7.jpg",
                                "model": "models/handles/7/model_7.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.2",
                                        "metalness": "0.2"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "38",
                                        "height": "38",
                                        "depth": "20",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 6,
                                "category": 4,
                                "name": "Кнопка 1",
                                "icon": "models/handles/6/icon_6.jpg",
                                "model": "models/handles/6/model_6.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.2",
                                        "metalness": "0.2"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "36",
                                        "height": "36",
                                        "depth": "34",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "id": 5,
                        "name": "Классика",
                        "parent": 1,
                        "items": [
                            {
                                "id": 15,
                                "category": 5,
                                "name": "Классика серебро",
                                "icon": "/common_assets/models/handles/16/icon_16.jpg",
                                "model": "/common_assets/models/handles/16/model_16.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.4",
                                        "metalness": "0",
                                        "map": "/common_assets/models/handles/16/map_16.jpg"
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
                            },
                            {
                                "id": 14,
                                "category": 5,
                                "name": "Классика серебро черное",
                                "icon": "/common_assets/models/handles/15/icon_15.jpg",
                                "model": "/common_assets/models/handles/15/model_15.fbx",
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
                            },
                            {
                                "id": 13,
                                "category": 5,
                                "name": "Классика бронза",
                                "icon": "/common_assets/models/handles/14/icon_14.jpg",
                                "model": "/common_assets/models/handles/14/model_14.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.4",
                                        "metalness": "0",
                                        "map": "/common_assets/models/handles/14/map_14.jpg"
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
                            },
                            {
                                "id": 12,
                                "category": 5,
                                "name": "Классика кнопка серебро",
                                "icon": "/common_assets/models/handles/13/icon_13.jpg",
                                "model": "/common_assets/models/handles/13/model_13.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.4",
                                        "metalness": "0.1",
                                        "map": "/common_assets/models/handles/13/map_13.jpg"
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
                                        "width": "30",
                                        "height": "23",
                                        "depth": "30",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 11,
                                "category": 5,
                                "name": "Классика кнопка серебро черное",
                                "icon": "/common_assets/models/handles/12/icon_12.jpg",
                                "model": "/common_assets/models/handles/12/model_12.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.4",
                                        "metalness": "0.1",
                                        "map": "/common_assets/models/handles/12/map_12.jpg"
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
                                        "width": "30",
                                        "height": "23",
                                        "depth": "30",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 10,
                                "category": 5,
                                "name": "Классика кнопка бронза",
                                "icon": "/common_assets/models/handles/11/icon_11.jpg",
                                "model": "/common_assets/models/handles/11/model_11.fbx",
                                "material": {
                                    "params": {
                                        "color": "#ffffff",
                                        "roughness": "0.4",
                                        "metalness": "0.1",
                                        "map": "/common_assets/models/handles/11/map_11.jpg"
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
                                        "width": "30",
                                        "height": "23",
                                        "depth": "30",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            },
                            {
                                "id": 8,
                                "category": 5,
                                "name": "Классика 1",
                                "icon": "models/handles/8/icon_8.jpg",
                                "model": "models/handles/8/model_8.fbx",
                                "material": {
                                    "params": {
                                        "color": "#d0cd6e",
                                        "roughness": "0.2",
                                        "metalness": "0.2"
                                    },
                                    "add_params": {},
                                    "type": "Standart"
                                },
                                "sizes": [
                                    {
                                        "width": "116",
                                        "height": "19",
                                        "depth": "28",
                                        "code": "",
                                        "axis_size": "",
                                        "price": ""
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                "id": 2,
                "name": "Рейлинги",
                "parent": 1,
                "items": [
                    {
                        "id": 9,
                        "category": 2,
                        "name": "Рейлинг 2",
                        "icon": "models/handles/9/icon_9.jpg",
                        "model": "models/handles/9/model_9.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.2",
                                "metalness": "0.2"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "140",
                                "height": "16",
                                "depth": "28",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 1,
                        "category": 2,
                        "name": "Рейлинг 1",
                        "icon": "models/handles/1/icon_1.jpg",
                        "model": "models/handles/1/model_1.fbx",
                        "material": {
                            "params": {
                                "color": "#dadada",
                                "roughness": "0.3",
                                "metalness": "0"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "175",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "128",
                                "price": ""
                            },
                            {
                                "width": "207",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "160",
                                "price": ""
                            },
                            {
                                "width": "239",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "192",
                                "price": ""
                            },
                            {
                                "width": "367",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "320",
                                "price": ""
                            },
                            {
                                "width": "495",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "448",
                                "price": ""
                            },
                            {
                                "width": "527",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "480",
                                "price": ""
                            },
                            {
                                "width": "623",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "576",
                                "price": ""
                            },
                            {
                                "width": "815",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "768",
                                "price": ""
                            },
                            {
                                "width": "911",
                                "height": "14",
                                "depth": "35",
                                "code": "",
                                "axis_size": "864",
                                "price": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 3,
                "name": "Скобы",
                "parent": 1,
                "items": [
                    {
                        "id": 5,
                        "category": 3,
                        "name": "Скоба 4",
                        "icon": "models/handles/5/icon_5.jpg",
                        "model": "models/handles/5/model_5.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.2",
                                "metalness": "0.2"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "204",
                                "height": "15",
                                "depth": "35",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 4,
                        "category": 3,
                        "name": "Скоба 3",
                        "icon": "models/handles/4/icon_4.jpg",
                        "model": "models/handles/4/model_4.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.2",
                                "metalness": "0.2"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "214",
                                "height": "16",
                                "depth": "18",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 3,
                        "category": 3,
                        "name": "Скоба 2",
                        "icon": "models/handles/3/icon_3.jpg",
                        "model": "models/handles/3/model_3.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.2",
                                "metalness": "0.2"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "179",
                                "height": "19",
                                "depth": "20",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 2,
                        "category": 3,
                        "name": "Скоба 1",
                        "icon": "models/handles/2/icon_2.jpg",
                        "model": "models/handles/2/model_2.fbx",
                        "material": {
                            "params": {
                                "color": "#dadada",
                                "roughness": "0.3",
                                "metalness": "0"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "76",
                                "height": "12",
                                "depth": "24",
                                "code": "",
                                "axis_size": "64",
                                "price": ""
                            },
                            {
                                "width": "108",
                                "height": "12",
                                "depth": "24",
                                "code": "",
                                "axis_size": "96",
                                "price": ""
                            },
                            {
                                "width": "140",
                                "height": "12",
                                "depth": "24",
                                "code": "",
                                "axis_size": "128",
                                "price": ""
                            },
                            {
                                "width": "239",
                                "height": "12",
                                "depth": "24",
                                "code": "",
                                "axis_size": "215",
                                "price": ""
                            },
                            {
                                "width": "292",
                                "height": "12",
                                "depth": "24",
                                "code": "",
                                "axis_size": "280",
                                "price": ""
                            },
                            {
                                "width": "332",
                                "height": "12",
                                "depth": "24",
                                "code": "",
                                "axis_size": "320",
                                "price": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 4,
                "name": "Кнопки",
                "parent": 1,
                "items": [
                    {
                        "id": 7,
                        "category": 4,
                        "name": "Кнопка 2",
                        "icon": "models/handles/7/icon_7.jpg",
                        "model": "models/handles/7/model_7.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.2",
                                "metalness": "0.2"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "38",
                                "height": "38",
                                "depth": "20",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 6,
                        "category": 4,
                        "name": "Кнопка 1",
                        "icon": "models/handles/6/icon_6.jpg",
                        "model": "models/handles/6/model_6.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.2",
                                "metalness": "0.2"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "36",
                                "height": "36",
                                "depth": "34",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    }
                ]
            },
            {
                "id": 5,
                "name": "Классика",
                "parent": 1,
                "items": [
                    {
                        "id": 15,
                        "category": 5,
                        "name": "Классика серебро",
                        "icon": "/common_assets/models/handles/16/icon_16.jpg",
                        "model": "/common_assets/models/handles/16/model_16.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.4",
                                "metalness": "0",
                                "map": "/common_assets/models/handles/16/map_16.jpg"
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
                    },
                    {
                        "id": 14,
                        "category": 5,
                        "name": "Классика серебро черное",
                        "icon": "/common_assets/models/handles/15/icon_15.jpg",
                        "model": "/common_assets/models/handles/15/model_15.fbx",
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
                    },
                    {
                        "id": 13,
                        "category": 5,
                        "name": "Классика бронза",
                        "icon": "/common_assets/models/handles/14/icon_14.jpg",
                        "model": "/common_assets/models/handles/14/model_14.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.4",
                                "metalness": "0",
                                "map": "/common_assets/models/handles/14/map_14.jpg"
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
                    },
                    {
                        "id": 12,
                        "category": 5,
                        "name": "Классика кнопка серебро",
                        "icon": "/common_assets/models/handles/13/icon_13.jpg",
                        "model": "/common_assets/models/handles/13/model_13.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.4",
                                "metalness": "0.1",
                                "map": "/common_assets/models/handles/13/map_13.jpg"
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
                                "width": "30",
                                "height": "23",
                                "depth": "30",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 11,
                        "category": 5,
                        "name": "Классика кнопка серебро черное",
                        "icon": "/common_assets/models/handles/12/icon_12.jpg",
                        "model": "/common_assets/models/handles/12/model_12.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.4",
                                "metalness": "0.1",
                                "map": "/common_assets/models/handles/12/map_12.jpg"
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
                                "width": "30",
                                "height": "23",
                                "depth": "30",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 10,
                        "category": 5,
                        "name": "Классика кнопка бронза",
                        "icon": "/common_assets/models/handles/11/icon_11.jpg",
                        "model": "/common_assets/models/handles/11/model_11.fbx",
                        "material": {
                            "params": {
                                "color": "#ffffff",
                                "roughness": "0.4",
                                "metalness": "0.1",
                                "map": "/common_assets/models/handles/11/map_11.jpg"
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
                                "width": "30",
                                "height": "23",
                                "depth": "30",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    },
                    {
                        "id": 8,
                        "category": 5,
                        "name": "Классика 1",
                        "icon": "models/handles/8/icon_8.jpg",
                        "model": "models/handles/8/model_8.fbx",
                        "material": {
                            "params": {
                                "color": "#d0cd6e",
                                "roughness": "0.2",
                                "metalness": "0.2"
                            },
                            "add_params": {},
                            "type": "Standart"
                        },
                        "sizes": [
                            {
                                "width": "116",
                                "height": "19",
                                "depth": "28",
                                "code": "",
                                "axis_size": "",
                                "price": ""
                            }
                        ]
                    }
                ]
            }
        ],
        "items": [
            {
                "id": 15,
                "category": 5,
                "name": "Классика серебро",
                "icon": "/common_assets/models/handles/16/icon_16.jpg",
                "model": "/common_assets/models/handles/16/model_16.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.4",
                        "metalness": "0",
                        "map": "/common_assets/models/handles/16/map_16.jpg"
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
            },
            {
                "id": 14,
                "category": 5,
                "name": "Классика серебро черное",
                "icon": "/common_assets/models/handles/15/icon_15.jpg",
                "model": "/common_assets/models/handles/15/model_15.fbx",
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
            },
            {
                "id": 13,
                "category": 5,
                "name": "Классика бронза",
                "icon": "/common_assets/models/handles/14/icon_14.jpg",
                "model": "/common_assets/models/handles/14/model_14.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.4",
                        "metalness": "0",
                        "map": "/common_assets/models/handles/14/map_14.jpg"
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
            },
            {
                "id": 12,
                "category": 5,
                "name": "Классика кнопка серебро",
                "icon": "/common_assets/models/handles/13/icon_13.jpg",
                "model": "/common_assets/models/handles/13/model_13.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.4",
                        "metalness": "0.1",
                        "map": "/common_assets/models/handles/13/map_13.jpg"
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
                        "width": "30",
                        "height": "23",
                        "depth": "30",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 11,
                "category": 5,
                "name": "Классика кнопка серебро черное",
                "icon": "/common_assets/models/handles/12/icon_12.jpg",
                "model": "/common_assets/models/handles/12/model_12.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.4",
                        "metalness": "0.1",
                        "map": "/common_assets/models/handles/12/map_12.jpg"
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
                        "width": "30",
                        "height": "23",
                        "depth": "30",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 10,
                "category": 5,
                "name": "Классика кнопка бронза",
                "icon": "/common_assets/models/handles/11/icon_11.jpg",
                "model": "/common_assets/models/handles/11/model_11.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.4",
                        "metalness": "0.1",
                        "map": "/common_assets/models/handles/11/map_11.jpg"
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
                        "width": "30",
                        "height": "23",
                        "depth": "30",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 9,
                "category": 2,
                "name": "Рейлинг 2",
                "icon": "models/handles/9/icon_9.jpg",
                "model": "models/handles/9/model_9.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.2",
                        "metalness": "0.2"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "140",
                        "height": "16",
                        "depth": "28",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 8,
                "category": 5,
                "name": "Классика 1",
                "icon": "models/handles/8/icon_8.jpg",
                "model": "models/handles/8/model_8.fbx",
                "material": {
                    "params": {
                        "color": "#d0cd6e",
                        "roughness": "0.2",
                        "metalness": "0.2"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "116",
                        "height": "19",
                        "depth": "28",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 7,
                "category": 4,
                "name": "Кнопка 2",
                "icon": "models/handles/7/icon_7.jpg",
                "model": "models/handles/7/model_7.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.2",
                        "metalness": "0.2"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "38",
                        "height": "38",
                        "depth": "20",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 6,
                "category": 4,
                "name": "Кнопка 1",
                "icon": "models/handles/6/icon_6.jpg",
                "model": "models/handles/6/model_6.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.2",
                        "metalness": "0.2"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "36",
                        "height": "36",
                        "depth": "34",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 5,
                "category": 3,
                "name": "Скоба 4",
                "icon": "models/handles/5/icon_5.jpg",
                "model": "models/handles/5/model_5.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.2",
                        "metalness": "0.2"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "204",
                        "height": "15",
                        "depth": "35",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 4,
                "category": 3,
                "name": "Скоба 3",
                "icon": "models/handles/4/icon_4.jpg",
                "model": "models/handles/4/model_4.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.2",
                        "metalness": "0.2"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "214",
                        "height": "16",
                        "depth": "18",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 3,
                "category": 3,
                "name": "Скоба 2",
                "icon": "models/handles/3/icon_3.jpg",
                "model": "models/handles/3/model_3.fbx",
                "material": {
                    "params": {
                        "color": "#ffffff",
                        "roughness": "0.2",
                        "metalness": "0.2"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "179",
                        "height": "19",
                        "depth": "20",
                        "code": "",
                        "axis_size": "",
                        "price": ""
                    }
                ]
            },
            {
                "id": 2,
                "category": 3,
                "name": "Скоба 1",
                "icon": "models/handles/2/icon_2.jpg",
                "model": "models/handles/2/model_2.fbx",
                "material": {
                    "params": {
                        "color": "#dadada",
                        "roughness": "0.3",
                        "metalness": "0"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "76",
                        "height": "12",
                        "depth": "24",
                        "code": "",
                        "axis_size": "64",
                        "price": ""
                    },
                    {
                        "width": "108",
                        "height": "12",
                        "depth": "24",
                        "code": "",
                        "axis_size": "96",
                        "price": ""
                    },
                    {
                        "width": "140",
                        "height": "12",
                        "depth": "24",
                        "code": "",
                        "axis_size": "128",
                        "price": ""
                    },
                    {
                        "width": "239",
                        "height": "12",
                        "depth": "24",
                        "code": "",
                        "axis_size": "215",
                        "price": ""
                    },
                    {
                        "width": "292",
                        "height": "12",
                        "depth": "24",
                        "code": "",
                        "axis_size": "280",
                        "price": ""
                    },
                    {
                        "width": "332",
                        "height": "12",
                        "depth": "24",
                        "code": "",
                        "axis_size": "320",
                        "price": ""
                    }
                ]
            },
            {
                "id": 1,
                "category": 2,
                "name": "Рейлинг 1",
                "icon": "models/handles/1/icon_1.jpg",
                "model": "models/handles/1/model_1.fbx",
                "material": {
                    "params": {
                        "color": "#dadada",
                        "roughness": "0.3",
                        "metalness": "0"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "175",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "128",
                        "price": ""
                    },
                    {
                        "width": "207",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "160",
                        "price": ""
                    },
                    {
                        "width": "239",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "192",
                        "price": ""
                    },
                    {
                        "width": "367",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "320",
                        "price": ""
                    },
                    {
                        "width": "495",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "448",
                        "price": ""
                    },
                    {
                        "width": "527",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "480",
                        "price": ""
                    },
                    {
                        "width": "623",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "576",
                        "price": ""
                    },
                    {
                        "width": "815",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "768",
                        "price": ""
                    },
                    {
                        "width": "911",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "864",
                        "price": ""
                    }
                ]
            }
        ]
    };
    units = 10;

    project_settings = {
        "is_kitchen_model": false,
        "door_offset": 2,
        "shelve_offset": 10,
        "corpus_thickness": 16,
        "tabletop_thickness": 40,
        "bottom_modules_height": 720,
        "bottom_as_top_facade_models": true,
        "bottom_as_top_facade_materials": true,
        "bottom_as_top_corpus_materials": true,
        "cokol_as_corpus": true,
        "cokol_height": 120,
        "models": {
            "top": 1,
            "bottom": 1
        },
        "materials": {
            "top": {
                "facades": [
                    0,
                    0
                ],
                "corpus": [
                    0,
                    0
                ]
            },
            "bottom": {
                "facades": [
                    0,
                    0
                ],
                "corpus": [
                    0,
                    0
                ]
            },
            "cokol": [
                0,
                0
            ],
            "tabletop": [
                0,
                0
            ],
            "walls": [
                0
            ],
            "floor": [
                0
            ],
            "wall_panel": [
                0,
                0,
                0
            ]
        },
        "selected_materials": {
            "top": {
                "facades": 1,
                "corpus": 0
            },
            "bottom": {
                "facades": 1,
                "corpus": 0
            },
            "cokol": 0,
            "tabletop": 2,
            "wall_panel": 0,
            "walls": 0,
            "floor": 0
        },
        "handle": {
            "orientation": "vertical",
            "lockers_position": "top",
            "selected_model": 1,
            "preferable_size": 2,
            "model": {
                "id": 1,
                "category": 2,
                "name": "Рейлинг 1",
                "icon": "/common_assets/models/handles/1/icon_1.jpg",
                "model": "/common_assets/models/handles/1/model_1.fbx",
                "size_index": 2,
                "material": {
                    "params": {
                        "color": "#dadada",
                        "roughness": "0.3",
                        "metalness": "0"
                    },
                    "add_params": {},
                    "type": "Standart"
                },
                "sizes": [
                    {
                        "width": "175",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "128",
                        "price": ""
                    },
                    {
                        "width": "207",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "160",
                        "price": ""
                    },
                    {
                        "width": "239",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "192",
                        "price": ""
                    },
                    {
                        "width": "367",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "320",
                        "price": ""
                    },
                    {
                        "width": "495",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "448",
                        "price": ""
                    },
                    {
                        "width": "527",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "480",
                        "price": ""
                    },
                    {
                        "width": "623",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "576",
                        "price": ""
                    },
                    {
                        "width": "815",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "768",
                        "price": ""
                    },
                    {
                        "width": "911",
                        "height": "14",
                        "depth": "35",
                        "code": "",
                        "axis_size": "864",
                        "price": ""
                    }
                ]
            },
            "no_handle": false
        },
        "hinges": {
            "id": 0,
            "name": "С доводчиком",
            "icon": "/common_assets/images/with_dovod.jpg",
            "door_hinges_price": 1000,
            "locker_hinges_price": 1000,
            "dovodchik": true
        },
        "wall_panel": {
            "active": false,
            "height": 560
        }
    };
    constructor_settings = {
        "price_enabled": "1",
        "shop_mode": "0",
        "default_kitchen_model": "",
        "show_kitchen_parameters": "1",
        "show_furniture": null,
        "multiple_facades_mode": 0,
        "cornice_available": "1",
        "custom_form": "0",
        "custom_order_url": "",
        "custom_sizes_available": "1",
        "tab_show_furniture": "1",
        "price_modificator": 1,
        "facade_style_change_availabale": 1,
        "show_specs": 0,
        "accessories_shop_enabled": "1",
        "facades_system_available": "1",
        "decorations_enabled": "1",
        "frontend_configurator_available": 1,
        "default_language": "ru"
    };



    white_mat = new THREE.MeshPhongMaterial({
        color: 0xfffffff,
        side: THREE.DoubleSide
    });

    aluminium_material = new THREE.MeshPhongMaterial({
        color: 0xbdbebf
    });

    glass_mat = new THREE.MeshStandardMaterial({
        color: 0xd1e2ff,
        transparent: true,
        opacity: 0.2,
        roughness: 0,
        metalness: 0
    });

    gray_mat = new THREE.MeshPhongMaterial({
        color: 0xdadada,
        side: THREE.DoubleSide
    });

    facades_sets = [
        {
            "id": 1,
            "name": "Модерн",
            "category": 2,
            "icon": "models/facades/1/MDF_modern_gladkiy.jpg",
            "materials": [1, 11],
            "facades": {},
            "full": [{"min_width": "0", "min_height": "0", "model": "/common_assets/models/facades/mdf_full.fbx"}],
            "window": [{"min_width": "0", "min_height": "0", "model": "/common_assets/models/facades/mdf_frame.fbx"}],
            "frame": [{"min_width": "0", "min_height": "0", "model": "models/facades/1/al_frame.fbx"}],
            "radius": [{"min_width": "0", "min_height": "0", "model": "/common_assets/models/facades/mdf_full_radius.fbx"}],
            "radius_window": [{
                "min_width": "0",
                "min_height": "0",
                "model": "/common_assets/models/facades/mdf_window_radius.fbx"
            }],
            "radius_frame": null,
            "top_category": 1
        }
    ];

    materials_catalog = {
        categories:[
            {
                id:0,
                name:0
            }
        ],
        items:[
            {
                "id": 0,
                "category": 0,
                "code": "RAL 1000",
                "name": "Зелёно-бежевый",
                "params": {
                    "color": "#ffffff"
                }
            },
            {
                "id": 1,
                "category": 0,
                "code": "RAL 1000",
                "name": "Зелёно-бежевый",
                "params": {
                    "color": "#932129"
                }
            },
            {
                "id": 2,
                "category": 0,
                "code": "RAL 1000",
                "name": "Зелёно-бежевый",
                "params": {
                    "color": "#decebe"
                }
            }
        ]
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

    var geometry = new THREE.BoxGeometry( 100/10, 100/10, 100/10 );
    material = new THREE.MeshStandardMaterial({
        color: '#ffffff',
        roughness: 0.8,
        metalness: 0
    });
    // var cube = new THREE.Mesh( geometry, material );
    // scene.add( cube );

    leg_mat = new THREE.MeshPhongMaterial({color:'#000000'})

    fbx_manager = new THREE.LoadingManager();
    fbx_loader = new THREE.FBXLoader(fbx_manager);

    var amb_light = new THREE.AmbientLight( 0xffffff, 0.63);
    scene.add( amb_light );

    room = new Room_new()


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

    var animate = function (time) {
        TWEEN.update(time);
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

    $('.add_item').submit(function (e) {

        var errors = [];

        var select = $('#sizes_list');

        $('.sizes_row').each(function () {
            var scope = $(this);

            if( parseFloat(scope.find('.width_input').val()) > 0 && parseFloat(scope.find('.height_input').val()) > 0 && parseFloat(scope.find('.depth_input').val()) > 0)
                select.append('<option selected value="'+ scope.find('.width_input').val() + ';'
                    + scope.find('.height_input').val() + ';'
                    + scope.find('.depth_input').val() + ';'
                    + scope.find('.name_input').val() + ';'
                    + scope.find('.code_input').val() + ';'
                    + scope.find('.default_input').val() + ';'
                    + scope.find('.price_input').val() +'"></option>')
        });



        if($('#module_mode').val() === 'template' || $('#module_mode').val() === 'custom_template'){
            if($('#template_id').val() == null || $('#template_id').val() == ''){
                errors.push('Не выбран шаблон модуля');
            }
        } else {
            if($('#icon')[0].files.length < 1){
                errors.push('Иконка модуля не выбрана')
            }
            if($('#module_params').val() == null || $('#module_params').val() == ''){
                errors.push('Не указаны параметры модул')
            } else {
                $('#cabinet_params').val($('#module_params').val())
            }


        }

        if(select.val() == '' || select.val() == null){
            errors.push('Не указано ни одного размера')
        }


        if(errors.length > 0){
            var text = '';
            for(var i = 0; i < errors.length; i++){
                text += (i+1) + ' ' + errors[i] + '\n'

            }
            alert(text);
            return false;
        } else {
            return true;
        }
    });
});

function Wall(params_obj) {

    // obj = {
    //     width: float,
    //     height: float,
    //     rX: float,
    //     rY: float,
    //     rZ: float,
    //     pX: float,
    //     pY: float,
    //     pZ: float,
    //     name: string,
    // }

    var width = params_obj.width ? params_obj.width : 0;
    var height = params_obj.height ? params_obj.height : 0;

    var rX = params_obj.rX ? params_obj.rX : 0;
    var rY = params_obj.rY ? params_obj.rY : 0;
    var rZ = params_obj.rZ ? params_obj.rZ : 0;

    var pX = params_obj.pX ? params_obj.pX : 0;
    var pY = params_obj.pY ? params_obj.pY : 0;
    var pZ = params_obj.pZ ? params_obj.pZ : 0;

    var name = params_obj.name ? params_obj.name : 'wall';


    var material;
    if (params_obj.material) {
        material = params_obj.material;
    } else {
        material = new THREE.MeshPhongMaterial({
            color: 0xffffff,
            specular: 0x000000,
            // side: THREE.DoubleSide
        });
    }

    var geometry = new THREE.PlaneGeometry(width, height);


    var mesh = new THREE.Mesh(geometry, material);
    mesh.rotation.set(rX, rY, rZ);
    mesh.position.set(pX, pY, pZ);
    mesh.receiveShadow = true;
    mesh.castShadow = false;
    mesh.name = name;
    mesh.is_wall = true;

    return mesh;
}