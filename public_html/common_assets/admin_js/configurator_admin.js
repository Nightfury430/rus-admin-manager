let configurator_group = new THREE.Group();
let configurator_module = 0;
let inputs_obj;
let configurator_mode = 'new';






let doors_params = [];
let shelves_params = [];
let lockers_params = [];
let decoration_params = [];

let gray_icon = false;

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
let global_materials = {
    black: new THREE.MeshBasicMaterial( {color: '#000000'} ),
    white: new THREE.MeshBasicMaterial( {color: '#ffffff'} ),
    red: new THREE.MeshBasicMaterial( {color: '#880000'} ),
    black_front: new THREE.MeshBasicMaterial( {color: '#000000', side: THREE.BackSide} ),
    light_green: new THREE.MeshBasicMaterial( {color: '#bdffc3'} ),
    helper_material: new THREE.MeshBasicMaterial( {color: '#bdffc3', transparent: true, opacity:0.5} ),
    rail_material: new THREE.MeshBasicMaterial( {color: '#bdffc3'} ),
    door_empty_material: new THREE.MeshBasicMaterial( {color: '#ffffff', transparent: true, opacity:0.7} ),
    edges: new THREE.LineBasicMaterial({color: 0x000000}),
    edges_white_mat: new THREE.MeshPhongMaterial({
        color: 0xfffffff,
        side: THREE.DoubleSide,
        transparent: false,
        opacity: 0.7
    })

};
let common_box = new THREE.Box3();
function update_materials_panel() {
    return;
}

function update_price_info_panel() {
    return;
}

function get_total_price_test() {
    return;
}

lang = [];

function set_configurator_mode() {
    room.hide();

    for (let i = 0; i < room.active_objects.length; i++){
        room.active_objects[i].visible = false;
    }

    configurator_mode = 'new';

    scene.add(configurator_group);

    if(configurator_module){
        configurator_module.delete();
    }

    doors_params = [];
    shelves_params = [];
    lockers_params = [];
    decoration_params = [];


    if($('#template').val() != '' ){
        configurator_module = new Cabinet_new(JSON.parse($('#template').val()).params);
        shelves_params = configurator_module.params.shelves;
        doors_params = configurator_module.params.doors;
        lockers_params = configurator_module.params.lockers;
        decoration_params = configurator_module.params.decorations;
    } else {
        configurator_module = new Cabinet_new({
            tabletop:{
                offset:{
                    front: 35,
                    back: 35
                }
            }
        });
    }



    configurator_group.add(configurator_module);
    configurator_module.position.set(0,0,0);
    global_options.configurator_mode = 1;

    camera_persp_old_position = camera.position.clone();
    controls_old_target = controls.target.clone();

    controls.target.set(0,0,0);
    camera.position.set(0,0,300);
    controls.update();

    console.log(shelves_params)

    configurator_build_state_panel(configurator_module);
    $('#state_panel').addClass('configurator_panel');
    show_state_panel();
}

function exit_configuration_mode(bool) {

    room.show();
    for (let i = 0; i < room.active_objects.length; i++){
        room.active_objects[i].visible = true;
    }

    controls.target.copy(controls_old_target);
    camera.position.copy(camera_persp_old_position);
    controls.update();


    scene.remove(configurator_group);
    global_options.configurator_mode = 0;


    hide_state_panel();

    $('#state_panel').removeClass('configurator_panel');

    if(!bool){

        console.log(create_cabinet_with_params())

        let cab = new Cabinet_new(JSON.parse(JSON.stringify( create_cabinet_with_params() )));
        room.active_objects.push(cab);
        room.add(cab);
        dControls.set_selected(cab);
    }

}

function module_set_configurator_mode(cabinet) {
    doors_params = cabinet.params.doors;
    shelves_params = cabinet.params.shelves;
    lockers_params = cabinet.params.lockers;
    decoration_params = cabinet.params.decorations;

    configurator_mode = 'exist';

    let scope = $('#state_panel');
    scope.html('');
    let content_wrapper = $('<div class="state_panel_wrapper"></div>');

    content_wrapper.css('top',0);


    // let doors_acc_block = $('<div class="acc_block"></div>');
    // let doors_acc_heading = $('<div class="acc_heading">Двери</div>');
    // let doors_acc_body = $('<div class="acc_body configurator_doors_block"></div>');
    // doors_acc_block.append(doors_acc_heading);
    // doors_acc_block.append(doors_acc_body);
    // content_wrapper.append(doors_acc_block);
    //
    // for (let i = 0; i < doors_params.length; i++){
    //     doors_acc_block.append(build_doors_conf_block(i, doors_params[i]));
    // }
    //
    // scope.append(content_wrapper);

    $('#state_panel').addClass('configurator_panel');


    configurator_build_state_panel(cabinet);

    show_state_panel();

}

function configurator_build_state_panel(cabinet) {


    console.log('--------------------');
    console.log(cabinet);

    let scope = $('#state_panel');
    scope.html('');
    let content_wrapper = $('<div class="state_panel_wrapper"></div>');

    content_wrapper.css('top',0);

    let cabinet_params_acc_block = $('<div class="acc_block"></div>');
    let cabinet_params_acc_heading = $('<div class="acc_heading">Основные параметры</div>');
    let cabinet_params_acc_body = $('<div class="acc_body"></div>');
    cabinet_params_acc_block.append(cabinet_params_acc_heading);
    cabinet_params_acc_block.append(cabinet_params_acc_body);
    content_wrapper.append(cabinet_params_acc_block);

    let cabinet_params_block = $('<div class="cabinet_params_block"></div>');

    let group_select = $('<select id="conf_cabinet_group_select"  class="form-control input-sm"></select>');
    group_select.append('<option selected value="bottom">Нижний</option>');
    group_select.append('<option value="top">Верхний</option>');

    // if($('#template').val() != '' ) {
        group_select.val(cabinet.params.cabinet_group)
    // }

        // cabinet_params_block.append($('<p>??? модуля</p>'));
    cabinet_params_block.append(group_select);

    group_select.change(function () {


        if(group_select.val() === 'top'){
            tabletop_offset_block.hide();
            cabinet_depth_input.val(300)
        } else {
            tabletop_offset_block.show();
            cabinet_depth_input.val(600)
        }

        build_cabinet_from_params();

    });

    let cabinet_types = [
        {
            type: 'common',
            name: 'Прямой'
        },
        // {
        //     type: 'corner',
        //     name: 'Угловой (трапеция)'
        // },
        // {
        //     type: 'corner_straight',
        //     name: 'Угловой'
        // },
        // {
        //     type: 'corner_open',
        //     name: 'Торцевой открытый'
        // },
        // {
        //     type: 'end_corner_facade',
        //     name: 'Торцевой с фасадом'
        // },
        // {
        //     type: 'corner_open_facade',
        //     name: 'Торцевой с 2 фасадами'
        // },
        // {
        //     type: 'end_radius_open',
        //     name: 'Торцевой радиус открытый'
        // },
        // {
        //     type: 'end_radius_facade',
        //     name: 'Торцевой радиус с фасадом'
        // },
    ];

    let type_select = $('<select></select>');

    for (let i = 0; i < cabinet_types.length; i++){
        type_select.append('<option value="'+ cabinet_types[i].type +'">'+ cabinet_types[i].name +'</option>')
    }

    // cabinet_params_block.append($('<p>Тип модуля</p>'));
    // cabinet_params_block.append(type_select);

    // type_select.val('common');

    // type_select.change(function () {
    //     build_cabinet_from_params();
    //     if(type_select.val() === 'corner'){
    //         cabinet_corner_right_wall_width_input.show();
    //         cabinet_corner_right_wall_width_heading.html('Глубина 2');
    //         cabinet_corner_right_wall_width_heading.show();
    //     } else if( type_select.val() === 'corner_straight'){
    //         cabinet_corner_right_wall_width_input.show();
    //         cabinet_corner_right_wall_width_heading.html('Ширина приставной части');
    //         cabinet_corner_right_wall_width_heading.show();
    //     }else {
    //         cabinet_corner_right_wall_width_input.hide();
    //         cabinet_corner_right_wall_width_heading.hide();
    //     }
    // });


    cabinet_params_acc_body.append(cabinet_params_block);

    let cabinet_corner_right_wall_width_heading = $('<p style="display: none">Ширина приставной части</p>');

    let cabinet_width_input = $('<input class="form-control input-sm" id="conf_cabinet_width_input" type="number" value="'+ cabinet.params.cabinet.width +'" min="0" max="9999" step="1">');
    let cabinet_height_input = $('<input class="form-control input-sm" id="conf_cabinet_height_input" type="number" value="'+ cabinet.params.cabinet.height +'" min="0" max="9999" step="1">');
    let cabinet_depth_input = $('<input class="form-control input-sm" id="conf_cabinet_depth_input" type="number" value="'+ cabinet.params.cabinet.depth +'" min="0" max="9999" step="1">');
    let cabinet_corner_right_wall_width_input = $('<input type="number" value="0" min="0" max="9999" step="1">');
    let cabinet_thickness_input = $('<input type="number" value="16" min="0" max="9999" step="1">');
    let cabinet_thickness_back_input = $('<input type="number" value="3" min="0" max="9999" step="1">');



    let cabinet_sizes_block = $('<div class="module_configurator_block"></div>');

    cabinet_sizes_block.append('<p class="module_configurator_block_heading">Размеры модуля</p>');
    cabinet_sizes_block.append('<p>Ширина, мм</p>');
    cabinet_sizes_block.append(cabinet_width_input);
    cabinet_sizes_block.append('<p>Высота корпуса, мм</p>');
    cabinet_sizes_block.append(cabinet_height_input);
    cabinet_sizes_block.append('<p>Глубина, мм</p>');
    cabinet_sizes_block.append(cabinet_depth_input);

    // cabinet_sizes_block.append(cabinet_corner_right_wall_width_heading);
    // cabinet_sizes_block.append(cabinet_corner_right_wall_width_input);

    cabinet_params_acc_body.append(cabinet_sizes_block);


    let tabletop_offset_block = $('<div class="module_configurator_block"></div>');
    tabletop_offset_block.append('<p class="module_configurator_block_heading">Параметры столешницы</p>');


    let tabletop_active_select = $('' +
        '<select class="form-control input-sm" id="conf_tabletop_active_select">' +
        '<option value="1">Да</option>' +
        '<option value="0">Нет</option>' +
        '</select>' +
        '');



    if(cabinet.params.tabletop.active){
        tabletop_active_select.val('1')
    } else {
        tabletop_active_select.val('0')
    }




    tabletop_offset_block.append('<p>Столешница</p>');
    tabletop_offset_block.append(tabletop_active_select);

    tabletop_active_select.change(function () {
        if($(this).val() === "1"){
            // tabletop_offset_left_input.show();
            // tabletop_offset_right_input.show();
            tabletop_offset_back_input.show();
            tabletop_offset_front_input.show();

            // $('.tabletop_offset_left_heading').show();
            // $('.tabletop_offset_right_heading').show();
            $('.tabletop_offset_back_heading').show();
            $('.tabletop_offset_front_heading').show();

        } else {
            // tabletop_offset_left_input.hide();
            // tabletop_offset_right_input.hide();
            tabletop_offset_back_input.hide();
            tabletop_offset_front_input.hide();

            // $('.tabletop_offset_left_heading').hide();
            // $('.tabletop_offset_right_heading').hide();
            $('.tabletop_offset_back_heading').hide();
            $('.tabletop_offset_front_heading').hide();
        }
    });



    let tabletop_offset_left_input = $('<input class="form-control input-sm" type="number" value="0" min="0" max="9999" step="1">');
    let tabletop_offset_right_input = $('<input class="form-control input-sm" type="number" value="0" min="0" max="9999" step="1">');
    let tabletop_offset_back_input = $('<input class="form-control input-sm" id="conf_tabletop_offset_back" type="number" value="35" min="0" max="9999" step="1">');
    let tabletop_offset_front_input = $('<input class="form-control input-sm" id="conf_tabletop_offset_front" type="number" value="35" min="0" max="9999" step="1">');


    if(cabinet !== configurator_group.children[0]){

        // group_select.val(cabinet.params.cabinet_group);

        group_select.hide();


        cabinet_width_input.val(cabinet.params.cabinet.width);
        cabinet_height_input.val(cabinet.params.cabinet.height);
        cabinet_depth_input.val(cabinet.params.cabinet.depth);

        tabletop_active_select.val( to_int(cabinet.params.tabletop.active) );

        tabletop_offset_back_input.val(cabinet.params.tabletop.offset.back);
        tabletop_offset_front_input.val(cabinet.params.tabletop.offset.front);
    }



    // tabletop_offset_block.append('<p class="tabletop_offset_left_heading">Свес слева, мм</p>');
    // tabletop_offset_block.append(tabletop_offset_left_input);

    // tabletop_offset_block.append('<p class="tabletop_offset_right_heading">Свес справа, мм</p>');
    // tabletop_offset_block.append(tabletop_offset_right_input);

    tabletop_offset_block.append('<p class="tabletop_offset_back_heading">Свес спереди, мм</p>');
    tabletop_offset_block.append(tabletop_offset_back_input);

    tabletop_offset_block.append('<p class="tabletop_offset_front_heading">Свес сзади, мм</p>');
    tabletop_offset_block.append(tabletop_offset_front_input);

    cabinet_params_acc_body.append(tabletop_offset_block);

    if(cabinet.params.cabinet_group == 'top'){
        setTimeout(function () {
            tabletop_active_select.val('0')

            tabletop_offset_back_input.hide();
            tabletop_offset_front_input.hide();

            $('.tabletop_offset_back_heading').hide();
            $('.tabletop_offset_front_heading').hide();
        },100)
    }

    let cabinet_walls_block = $('<div class="module_configurator_block"></div>');
    cabinet_walls_block.append('<p class="module_configurator_block_heading">Стенки</p>');

    let top_wall_checkbox = $('<div><div class="checkbox"><label><input id="top_wall_active" type="checkbox" value="">Верхняя стенка</label></div>');
    let left_wall_checkbox = $('<div class="checkbox"><label><input id="left_wall_active" type="checkbox" value="">Левая стенка</label></div>');
    let right_wall_checkbox = $('<div class="checkbox"><label><input id="right_wall_active" type="checkbox" value="">Правая стенка</label></div>');
    let bottom_wall_checkbox = $('<div class="checkbox"><label><input id="bottom_wall_active" type="checkbox" value="">Нижняя стенка</label></div>');
    let back_wall_checkbox = $('<div class="checkbox"><label><input id="back_wall_active" type="checkbox" value="">Задняя стенка</label></div>');





    cabinet_walls_block.append(top_wall_checkbox);
    cabinet_walls_block.append(left_wall_checkbox);
    cabinet_walls_block.append(right_wall_checkbox);
    cabinet_walls_block.append(bottom_wall_checkbox);
    cabinet_walls_block.append(back_wall_checkbox);
    cabinet_params_acc_body.append(cabinet_walls_block);

    if(cabinet.params.oven.pY === undefined) cabinet.params.oven.pY = 0;

    let oven_pos = 0;
    let mic_pos = 0;
    let oven_active = 0;
    let mic_active = 0;

    if(cabinet.params.fixed_models.length != 0){
        for(let i = cabinet.params.fixed_models.length; i--;){
            if(cabinet.params.fixed_models[i].model.indexOf('/tech/built_in_cookers/2/model.fbx') > -1){
                oven_pos = cabinet.params.fixed_models[i].pos.y;
                oven_active = true
            }
            if(cabinet.params.fixed_models[i].model.indexOf('/tech/built_in_cookers/2/mic.fbx') > -1){
                mic_pos = cabinet.params.fixed_models[i].pos.y;
                mic_active = true
            }
        }
    } else {

    }

    let oven_block = $('<div class="module_configurator_block"></div>');
    oven_block.append('<p class="module_configurator_block_heading">Встроенная духовка (Черная)</p>');
    oven_block.append($('<div class="checkbox"><label><input id="oven_active" type="checkbox" value="">Активна</label></div>'))
    oven_block.append('<p class="oven_heading">Позиция по вертикали, мм</p>');
    oven_block.append($('<input id="oven_position" class="form-control input-sm" value="'+ cabinet.params.oven.pY +'" type="number" value="0" min="0" max="9999" step="1">'));
    cabinet_params_acc_body.append(oven_block);


    let oven_block_new = $('<div class="module_configurator_block"></div>');
    oven_block_new.append('<p class="module_configurator_block_heading">Встроенная духовка (Белая)</p>');
    oven_block_new.append($('<div class="checkbox"><label><input id="oven_active_new" type="checkbox" value="">Активна</label></div>'))
    oven_block_new.append('<p class="oven_heading">Позиция по вертикали, мм</p>');
    oven_block_new.append($('<input id="oven_position_new" class="form-control input-sm" value="'+ oven_pos +'" type="number" value="0" min="0" max="9999" step="1">'));
    cabinet_params_acc_body.append(oven_block_new);


    let micro_block = $('<div class="module_configurator_block"></div>');
    micro_block.append('<p class="module_configurator_block_heading">Встроенная микроволновая печь (Белая)</p>');
    micro_block.append($('<div class="checkbox"><label><input id="mic_active" type="checkbox" value="">Активна</label></div>'))
    micro_block.append('<p class="oven_heading">Позиция по вертикали, мм</p>');
    micro_block.append($('<input id="mic_position" class="form-control input-sm" value="'+ mic_pos +'" type="number" value="0" min="0" max="9999" step="1">'));
    cabinet_params_acc_body.append(micro_block);


    let cabinet_size_accept_button = $('<button class="btn-block btn btn-default btn-sm">Применить</button>');

    cabinet_params_acc_body.append(cabinet_size_accept_button);

    cabinet_size_accept_button.click(function () {
        if(cabinet){
            console.log(cabinet)
            build_cabinet_from_params(false, cabinet);
        } else {
            build_cabinet_from_params();
        }

    });


    if(cabinet){

    }


    let shelves_acc_block = $('<div class="acc_block"></div>');
    let shelves_acc_heading = $('<div class="acc_heading">Полки</div>');
    let shelves_acc_body = $('<div class="acc_body shelves_params_block"></div>');
    shelves_acc_block.append(shelves_acc_heading);
    shelves_acc_block.append(shelves_acc_body);
    content_wrapper.append(shelves_acc_block);

    let add_shelve_button = $('<button class="btn-block btn btn-default btn-sm">Добавить</button>');
    shelves_acc_body.append(add_shelve_button);


    add_shelve_button.click(function () {
        let shelve_add_modal_body = $('<div></div>');


        let shelve_orientation_block = $('<div class="modal_configurator_block container-fluid"></div>');
        shelve_add_modal_body.append(shelve_orientation_block);

        let shelve_orientation_block_row = $('<div class="row"></div>');
        shelve_orientation_block.append(shelve_orientation_block_row);

        let shelves_orientation_block_left = $('<div class="col-xs-6"></div>');
        shelve_orientation_block_row.append(shelves_orientation_block_left);

        let shelves_orientation_select = $('' +
            '<select class="form-control" id="shelves_orientation_select">' +
            '<option value="horizontal">Горизонтальная</option>' +
            '<option value="vertical">Вертикальная</option>' +
            '</select>'
        );

        shelves_orientation_block_left.append('<label for="shelves_orientation_select">Ориентация</label>');
        shelves_orientation_block_left.append(shelves_orientation_select);


        let shelve_sizes_block = $('<div class="modal_configurator_block container-fluid"></div>');
        shelve_add_modal_body.append(shelve_sizes_block);

        let shelve_sizes_block_row = $('<div class="row"></div>');
        shelve_sizes_block.append(shelve_sizes_block_row);



        // shelve_sizes_block_row.append('<div class="col-xs-12"><h4 class="modal_configurator_block_heading">Размеры полки</h4></div>');


        let shelves_sizes_block_left = $('<div class="col-xs-6"></div>');
        let shelves_sizes_block_right = $('<div class="col-xs-6"></div>');


        shelve_sizes_block_row.append(shelves_sizes_block_left);
        shelve_sizes_block_row.append(shelves_sizes_block_right);





        let shelve_width_input = $('<input class="form-control" id="shelve_width_input" type="number" value="100" min="0" max="9999" step="1">');
        let shelve_thickness_input = $('<input class="form-control" id="shelve_thickness_input" type="number" value="16" min="0" max="9999" step="1">');

        let shelve_width_type = $('' +
            '<select class="form-control" id="shelve_width_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение (мм)</option>' +
            '</select>'
        );



        shelves_sizes_block_left.append('<label for="shelve_width_input">Ширина</label>');
        shelves_sizes_block_left.append(shelve_width_input);

        shelves_sizes_block_right.append('<label style="opacity: 0;" for="shelve_width_type">Тип значения</label>')
        shelves_sizes_block_right.append(shelve_width_type);

        shelves_sizes_block_left.append('<label for="shelve_thickness_input">Толщина</label>');
        shelves_sizes_block_left.append(shelve_thickness_input);






        let shelve_position_block = $('<div class="modal_configurator_block container-fluid"></div>');
        shelve_add_modal_body.append(shelve_position_block);

        let shelve_position_block_row = $('<div class="row"></div>');
        shelve_position_block.append(shelve_position_block_row);

        // shelve_position_block_row.append('<div class="col-xs-12"><h4 class="modal_configurator_block_heading">Позиция полки</h4></div>');


        let shelves_positions_block_left = $('<div class="col-xs-6"></div>');
        let shelves_positions_block_right = $('<div class="col-xs-6"></div>');


        shelve_position_block_row.append(shelves_positions_block_left);
        shelve_position_block_row.append(shelves_positions_block_right);


        let shelves_starting_point_x_input = $('<input class="form-control" id="shelves_starting_point_x_input" type="number" value="0" min="0" max="9999" step="1">');
        let shelves_starting_point_x_type = $('' +
            '<select class="form-control" id="shelves_starting_point_x_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        shelves_positions_block_left.append('<label for="shelves_starting_point_x_input">Позиция по горизонтали</label>');
        shelves_positions_block_left.append(shelves_starting_point_x_input);

        shelves_positions_block_right.append('<label style="opacity: 0" for="shelves_starting_point_x_type">Тип значения</label>');
        shelves_positions_block_right.append(shelves_starting_point_x_type);




        let shelves_starting_point_y_input = $('<input class="form-control" id="shelves_starting_point_y_input" type="number" value="50" min="0" max="9999" step="1">');
        let shelves_starting_point_y_type = $('' +
            '<select class="form-control" id="shelves_starting_point_y_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );


        shelves_positions_block_left.append('<label for="shelves_starting_point_y_input">Позиция по вертикали</label>');
        shelves_positions_block_left.append(shelves_starting_point_y_input);

        shelves_positions_block_right.append('<label style="opacity: 0" for="shelves_starting_point_y_type">Тип значения</label>');
        shelves_positions_block_right.append(shelves_starting_point_y_type);



        let shelves_position_offset_input = $('<input class="form-control" id="shelves_position_offset_input" type="number" value="0" min="0" max="9999" step="1">');
        let shelves_position_offset_type = $('' +
            '<select class="form-control" id="shelves_position_offset_type">' +
            '<option value="top">Сверху</option>' +
            '<option value="bottom">Снизу</option>' +
            '</select>'
        );

        shelves_positions_block_left.append('<label for="shelves_position_offset_input">Фиксированный отсутп</label>');
        shelves_positions_block_left.append(shelves_position_offset_input);

        shelves_positions_block_right.append('<label style="opacity: 0" for="shelves_starting_point_y_type">Тип отступа</label>');
        shelves_positions_block_right.append(shelves_position_offset_type);



        let shelve_add_modal = new Modal_window({
            heading: 'Добавить полку',
            body: shelve_add_modal_body,
            max_width: 600,
            class: 'yesno'
        });


        shelve_add_modal.show();

        shelve_add_modal.yes_button.click(function () {
            shelve_add_modal.close();

            let params = {};

            if( shelve_width_type.val() === 'percent' ){
                params.width = shelve_width_input.val() + '%'
            } else {
                params.width = parseInt( shelve_width_input.val() );
            }

            params.height = parseInt( shelve_thickness_input.val() );

            if( shelves_starting_point_y_type.val() === 'percent' ){
                params.starting_point_y = shelves_starting_point_y_input.val() + '%'
            } else {
                params.starting_point_y = parseInt( shelves_starting_point_y_input.val() );
            }


            if( shelves_starting_point_x_type.val() === 'percent' ){
                params.starting_point_x = shelves_starting_point_x_input.val() + '%'
            } else {
                params.starting_point_x = parseInt( shelves_starting_point_x_input.val() );
            }

            if( shelves_position_offset_type.val() === 'top' ){
                params.position_offset_top = parseInt( shelves_position_offset_input.val() );
            } else {
                params.position_offset_bottom = parseInt( shelves_position_offset_input.val() );
            }


            params.orientation = shelves_orientation_select.val();

            params.v2 = 1;

            let shelve_index;

            if(cabinet){
                shelve_index = configurator_module_add_shelves(params, cabinet);
            } else {
                shelve_index = configurator_module_add_shelves(params);
            }





            // let shelve_button = $('<button class="module_shelve" data-index="'+ shelve_index +'">Полка</button>');

            let shelve_button = build_shelves_conf_block(shelve_index, params);




            // shelve_button.click(function () {
            //     let scope = $(this);
            //     configurator_module_remove_shelves( $(this).attr('data-index') );
            //     shelves_acc_body.find('.module_shelve').each(function () {
            //         if( parseInt( scope.attr('data-index') ) < parseInt( $(this).attr('data-index') ) ){
            //             $(this).attr('data-index', parseInt( $(this).attr('data-index') - 1) );
            //         }
            //     });
            //     $(this).remove();
            // });

            shelves_acc_body.append(shelve_button);


        });

        shelve_add_modal.no_button.click(function () {
            shelve_add_modal.close();
        });

    });

    for (let i = 0; i < shelves_params.length; i++){
        shelves_acc_body.append(build_shelves_conf_block(i, shelves_params[i], cabinet));
    }


    let doors_acc_block = $('<div class="acc_block"></div>');
    let doors_acc_heading = $('<div class="acc_heading">Двери</div>');
    let doors_acc_body = $('<div class="acc_body configurator_doors_block"></div>');
    doors_acc_block.append(doors_acc_heading);
    doors_acc_block.append(doors_acc_body);
    content_wrapper.append(doors_acc_block);


    let add_door_button = $('<button class="btn-block btn btn-default btn-sm">Добавить</button>');
    doors_acc_body.append(add_door_button);


    let doors_types = [
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
            name: 'Фальшфасад',
            type: 'falsefacade'
        }
    ];


    add_door_button.click(function () {

        let door_add_modal_body = $('<div></div>');


        let door_style_block = $('<div class="modal_configurator_block container-fluid"></div>');
        door_add_modal_body.append(door_style_block);

        let door_style_block_row = $('<div class="row"></div>');
        door_style_block.append(door_style_block_row);




        let door_style_block_left = $('<div class="col-xs-6"></div>');
        let door_style_block_right = $('<div class="col-xs-6"></div>');

        door_style_block_row.append(door_style_block_left);
        door_style_block_row.append(door_style_block_right);


        let door_model_input = $('<input class="form-control" type="text">');
        let model_col = $('<div class="col-xs-12"></div>')

        door_style_block_row.append(model_col);
        model_col.append('<label>Фиксированная модель двери</label>');
        model_col.append(door_model_input);

        door_style_block_row.append(model_col)


        // let door_add_modal_body = $('<div></div>');

        let door_type_select = $('<select class="form-control"></select>');

        let door_style_select = $('' +
            '<select class="form-control">' +
            '<option value="full">Глухой</option> ' +
            '<option value="window">Витрина</option> ' +
            '<option value="frame">Решетка</option> ' +
            '</select>'
        );

        for (let i = 0; i < doors_types.length; i++){
            door_type_select.append('<option value="'+ doors_types[i].type +'">'+ doors_types[i].name +'</option>')
        }

        door_style_block_left.append('<label>Тип открывания</label>');
        door_style_block_left.append(door_type_select);

        door_style_block_right.append('<label>Тип фасада</label>');
        door_style_block_right.append(door_style_select);



        let door_width_input = $('<input class="form-control" id="door_width_input" type="number" value="100" min="0" max="9999" step="1">');

        let door_width_type = $('' +
            '<select class="form-control" id="door_width_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );


        door_style_block_left.append('<label>Ширина</label>');
        door_style_block_left.append(door_width_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_width_type);


        let door_height_input = $('<input class="form-control" id="door_width_input" type="number" value="100" min="0" max="9999" step="1">');

        let door_height_type = $('' +
            '<select class="form-control" id="door_height_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Высота</label>');
        door_style_block_left.append(door_height_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_height_type);


        let door_position_x_input = $('<input class="form-control" id="door_position_x_input" type="text" value="0" >')
        let door_position_x_type = $('' +
            '<select class="form-control" id="door_position_x_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Позиция по горизонтали</label>');
        door_style_block_left.append(door_position_x_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_position_x_type);


        let door_position_y_input = $('<input class="form-control" id="door_position_y_input" type="number" value="0" min="0" max="9999" step="1">')
        let door_position_y_type = $('' +
            '<select class="form-control" id="door_position_y_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Позиция по вертикали</label>');
        door_style_block_left.append(door_position_y_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_position_y_type);




        let door_offset_top_input = $('<input class="form-control" id="door_offset_top_input" type="number" value="0" min="0" max="9999" step="1">');
        let door_offset_bottom_input = $('<input class="form-control" id="door_offset_bottom_input" type="number" value="0" min="0" max="9999" step="1">');

        door_style_block_left.append('<label>Отступ сверху (мм)</label>');
        door_style_block_left.append(door_offset_top_input);

        door_style_block_right.append('<label >Отступ снизу (мм)</label>');
        door_style_block_right.append(door_offset_bottom_input);

        let handle_position_select = $('' +
            '<select class="form-control">' +
            '<option value="top">Сверху</option>' +
            '<option value="bottom">Снизу</option>' +
            '<option value="middle">По центру</option>' +
            '<option value="no_handle">Без ручки</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Положение ручки</label>');
        door_style_block_left.append(handle_position_select);


        let door_material_group_select = $('' +
            '<select class="form-control" id="door_material_group_select">' +
            '<option value="top">Верхние модули</option>' +
            '<option value="bottom">Нижние модули</option>' +
            '</select>'
        );

        door_material_group_select.val(group_select.val())


        door_style_block_right.append('<label>Цвет фасада</label>');
        door_style_block_right.append(door_material_group_select);


        let door_add_modal = new Modal_window({
            heading: 'Добавить дверь',
            body: door_add_modal_body,
            max_width: 600,
            class: 'yesno'
        });


        door_add_modal.show();

        door_add_modal.yes_button.click(function () {
            door_add_modal.close();

            let params = {};

            if( door_width_type.val() === 'percent' ){
                params.width = door_width_input.val() + '%'
            } else {
                params.width = parseInt( door_width_input.val() );
            }

            if( door_height_type.val() === 'percent' ){
                params.height = door_height_input.val() + '%'
            } else {
                params.height = parseInt( door_height_input.val() );
            }

            params.type = door_type_select.val();
            params.style = door_style_select.val();

            // params.starting_point_x = parseInt( door_position_x_input.val() ) / units;

            if( door_position_x_type.val() === 'percent' ){
                params.starting_point_x = door_position_x_input.val() + '%';
            } else {
                params.starting_point_x = parseInt( door_position_x_input.val() ) / units;
            }

            if( door_position_y_type.val() === 'percent' ){
                params.starting_point_y = door_position_y_input.val() + '%';
            } else {
                params.starting_point_y = parseInt( door_position_y_input.val() )
            }



            params.offset_top = parseInt( door_offset_top_input.val() );
            params.offset_bottom = parseInt( door_offset_bottom_input.val() );

            if( group_select.val() === 'bottom' ){
                params.handle_position = 'top';
            } else {
                params.handle_position = 'bottom';
            }


            if(handle_position_select.val() === 'no_handle'){
                params.no_handle_forced = true;
            } else {
                params.no_handle_forced = false;
            }

            params.handle_position = handle_position_select.val();


            params.group = door_material_group_select.val();

            if(door_model_input.val()!==''){
                params.fixed_facade_model = door_model_input.val();
            } else {
                params.fixed_facade_model = undefined;
            }



            // let door_index = configurator_module_add_door(params);

            let door_index;

            if(cabinet){
                door_index = configurator_module_add_door(params, cabinet);
            } else {
                door_index = configurator_module_add_door(params);
            }


            let door_button = build_doors_conf_block(door_index, params)




            doors_acc_body.append(door_button);


        });



        door_add_modal.no_button.click(function () {
            door_add_modal.close();
        });
    });

    for (let i = 0; i < doors_params.length; i++){
        doors_acc_body.append(build_doors_conf_block(i, doors_params[i], cabinet));
    }



    let lockers_acc_block = $('<div class="acc_block"></div>');
    let lockers_acc_heading = $('<div class="acc_heading">Ящики</div>');
    let lockers_acc_body = $('<div class="acc_body configurator_lockers_block"></div>');
    lockers_acc_block.append(lockers_acc_heading);
    lockers_acc_block.append(lockers_acc_body);
    content_wrapper.append(lockers_acc_block);

    let add_locker_button = $('<button class="btn-block btn btn-default btn-sm">Добавить</button>');
    lockers_acc_body.append(add_locker_button);

    add_locker_button.click(function () {
        let locker_add_modal_body = $('<div></div>');


        let locker_style_block = $('<div class="modal_configurator_block container-fluid"></div>');
        locker_add_modal_body.append(locker_style_block);

        let locker_style_block_row = $('<div class="row"></div>');
        locker_style_block.append(locker_style_block_row);


        let locker_style_block_left = $('<div class="col-xs-6"></div>');
        let locker_style_block_right = $('<div class="col-xs-6"></div>');

        locker_style_block_row.append(locker_style_block_left);
        locker_style_block_row.append(locker_style_block_right);




        let lockers_style_select = $('' +
            '<select class="form-control">' +
            '<option value="full">Глухой</option> ' +
            '<option value="window">Витрина</option> ' +
            '<option value="frame">Решетка</option> ' +
            '</select>'
        );

        let locker_type_select = $('' +
            '<select class="form-control">' +
            '<option value="common">Обычный</option> ' +
            '<option value="inner">Внутренний</option> ' +
            '</select>'
        );


        locker_style_block_left.append('<label for="lockers_style_select">Тип фасада</label>');
        locker_style_block_left.append(lockers_style_select);

        locker_style_block_right.append('<label for="lockers_style_select">Тип ящика</label>');
        locker_style_block_right.append(locker_type_select);


        let locker_sizes_block = $('<div class="modal_configurator_block container-fluid"></div>');
        locker_add_modal_body.append(locker_sizes_block);

        let locker_sizes_block_row = $('<div class="row"></div>');
        locker_sizes_block.append(locker_sizes_block_row);


        let locker_sizes_block_left = $('<div class="col-xs-6"></div>');
        let locker_sizes_block_right = $('<div class="col-xs-6"></div>');


        locker_sizes_block_row.append(locker_sizes_block_left);
        locker_sizes_block_row.append(locker_sizes_block_right);


        let locker_width_input = $('<input class="form-control" id="locker_width_input" type="number" value="100" min="0" max="9999" step="1">');
        let locker_height_input = $('<input class="form-control" id="locker_height_input" type="number" value="100" min="0" max="9999" step="1">');

        let locker_width_type = $('' +
            '<select class="form-control" id="locker_width_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        let locker_height_type = $('' +
            '<select class="form-control" id="locker_height_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        locker_sizes_block_left.append('<label for="locker_width_input">Ширина ящика</label>');
        locker_sizes_block_left.append(locker_width_input);

        locker_sizes_block_right.append('<label style="opacity: 0" for="locker_width_type">Тип значения</label>');
        locker_sizes_block_right.append(locker_width_type);


        locker_sizes_block_left.append('<label for="locker_width_input">Высота ящика</label>');
        locker_sizes_block_left.append(locker_height_input);

        locker_sizes_block_right.append('<label style="opacity: 0" for="locker_height_type">Тип значения</label>');
        locker_sizes_block_right.append(locker_height_type);



        let locker_position_x_input = $('<input class="form-control" id="locker_position_x_input" type="number" value="0" min="0" max="9999" step="1">');
        let locker_position_x_type = $('' +
            '<select class="form-control" id="locker_position_x_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        locker_sizes_block_left.append('<label for="locker_width_input">Позиция по горизонтали</label>');
        locker_sizes_block_left.append(locker_position_x_input);

        locker_sizes_block_right.append('<label style="opacity: 0" for="locker_width_type">Тип значения</label>');
        locker_sizes_block_right.append(locker_position_x_type);



        let locker_position_y_input = $('<input class="form-control" id="locker_position_y_input" type="number" value="0" min="0" max="9999" step="1">');
        let locker_position_y_type = $('' +
            '<select class="form-control" id="locker_position_y_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );


        locker_sizes_block_left.append('<label for="locker_width_input">Позиция по вертикали</label>');
        locker_sizes_block_left.append(locker_position_y_input);

        locker_sizes_block_right.append('<label style="opacity: 0" for="locker_width_type">Тип значения</label>');
        locker_sizes_block_right.append(locker_position_y_type);


        let handle_position_select = $('' +
            '<select class="form-control">' +
            '<option value="1">Да</option>' +
            '<option value="0">Нет</option>' +
            '</select>'
        );

        locker_sizes_block_left.append('<label>Ручка</label>');
        locker_sizes_block_left.append(handle_position_select);


        let locker_model_input = $('<input class="form-control" type="text">');
        let model_col = $('<div class="col-xs-12"></div>')

        locker_sizes_block_row.append(model_col);
        model_col.append('<label>Фиксированная модель фасада</label>');
        model_col.append(locker_model_input);

        locker_sizes_block_row.append(model_col)


        let locker_add_modal = new Modal_window({
            heading: 'Добавить ящик',
            body: locker_add_modal_body,
            max_width: 600,
            class: 'yesno'
        });


        locker_add_modal.show();

        locker_add_modal.yes_button.click(function () {
            locker_add_modal.close();

            let params = {};

            params.style = lockers_style_select.val();
            params.inner = locker_type_select.val() === 'inner';

            if( locker_width_type.val() === 'percent' ){
                params.width = locker_width_input.val() + '%'
            } else {
                params.width = parseInt( locker_width_input.val() );
            }

            if( locker_height_type.val() === 'percent' ){
                params.height = locker_height_input.val() + '%'
            } else {
                params.height = parseInt( locker_height_input.val() );
            }


            if( locker_position_x_type.val() === 'percent' ){
                params.starting_point_x = locker_position_x_input.val() + '%'
            } else {
                params.starting_point_x = parseInt( locker_position_x_input.val() );
            }

            if( locker_position_y_type.val() === 'percent' ){
                params.starting_point_y = locker_position_y_input.val() + '%'
            } else {
                params.starting_point_y = parseInt( locker_position_y_input.val() );
            }

            if(handle_position_select.val() === '1'){
                params.no_handle_forced = false;
            } else {
                params.no_handle_forced = true;
            }

            if(locker_model_input.val() !== ''){
                params.fixed_facade_model = locker_model_input.val();
            } else {
                params.fixed_facade_model = undefined;
            }


            let locker_index;

            if(cabinet){
                locker_index = configurator_module_add_locker(params, cabinet);
            } else {
                locker_index = configurator_module_add_locker(params);
            }





            // let locker_button = $('<button class="module_locker" data-index="'+ locker_index +'">Ящик</button>');

            let locker_button = build_lockers_conf_block(locker_index, params);

            // locker_button.click(function () {
            //     let scope = $(this);
            //     configurator_module_remove_locker( $(this).attr('data-index') );
            //     lockers_acc_body.find('.module_locker').each(function () {
            //         if( parseInt( scope.attr('data-index') ) < parseInt( $(this).attr('data-index') ) ){
            //             $(this).attr('data-index', parseInt( $(this).attr('data-index') - 1) );
            //         }
            //     });
            //     $(this).remove();
            // });

            lockers_acc_body.append(locker_button);


        });

        locker_add_modal.no_button.click(function () {
            locker_add_modal.close();
        });

    });

    for (let i = 0; i < lockers_params.length; i++){
        lockers_acc_body.append(build_lockers_conf_block(i, lockers_params[i], cabinet));
    }


    let decorations_acc_block = $('<div class="acc_block"></div>');
    let decorations_acc_heading = $('<div class="acc_heading">Декоративные элементы</div>');
    let decorations_acc_body = $('<div class="acc_body"></div>');
    decorations_acc_block.append(decorations_acc_heading);
    decorations_acc_block.append(decorations_acc_body);
    content_wrapper.append(decorations_acc_block);




    let add_decoration_button = $('<button class="btn-block btn btn-default btn-sm">Добавить</button>');
    decorations_acc_body.append(add_decoration_button);



    add_decoration_button.click(function () {

        let door_add_modal_body = $('<div></div>');


        let door_style_block = $('<div class="modal_configurator_block container-fluid"></div>');
        door_add_modal_body.append(door_style_block);

        let door_style_block_row = $('<div class="row"></div>');
        door_style_block.append(door_style_block_row);

        let decorations_model_input = $('<input class="form-control" id="decoration_model_input" type="text">');
        let model_col = $('<div class="col-xs-12"></div>')

        door_style_block_row.append(model_col);
        model_col.append('<label>Ссылка на модель</label>');
        model_col.append(decorations_model_input);


        let door_style_block_left = $('<div class="col-xs-6"></div>');
        let door_style_block_right = $('<div class="col-xs-6"></div>');

        door_style_block_row.append(door_style_block_left);
        door_style_block_row.append(door_style_block_right);



        // let door_add_modal_body = $('<div></div>');




        let door_width_input = $('<input class="form-control" id="door_width_input" type="number" value="100" min="0" max="9999" step="1">');

        let door_width_type = $('' +
            '<select class="form-control" id="door_width_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );


        door_style_block_left.append('<label>Ширина</label>');
        door_style_block_left.append(door_width_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_width_type);


        let door_height_input = $('<input class="form-control" id="door_width_input" type="number" value="100" min="0" max="9999" step="1">');

        let door_height_type = $('' +
            '<select class="form-control" id="door_height_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Высота</label>');
        door_style_block_left.append(door_height_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_height_type);


        let decor_depth_input = $('<input class="form-control" id="door_width_input" type="number" value="100" min="0" max="9999" step="1">');

        let decor_depth_type = $('' +
            '<select class="form-control" id="decor_depth_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Толщина</label>');
        door_style_block_left.append(decor_depth_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(decor_depth_type);


        let door_position_x_input = $('<input class="form-control" id="door_position_x_input" type="text" value="0" >')
        let door_position_x_type = $('' +
            '<select class="form-control" id="door_position_x_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Позиция по горизонтали</label>');
        door_style_block_left.append(door_position_x_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_position_x_type);


        let door_position_y_input = $('<input class="form-control" id="door_position_y_input" type="number" value="0" min="0" max="9999" step="1">')
        let door_position_y_type = $('' +
            '<select class="form-control" id="door_position_y_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Позиция по вертикали</label>');
        door_style_block_left.append(door_position_y_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_position_y_type);


        let door_position_z_input = $('<input class="form-control" id="door_position_z_input" type="number" value="0" min="0" max="9999" step="1">')
        let door_position_z_type = $('' +
            '<select class="form-control" id="door_position_y_type">' +
            '<option value="percent">Проценты</option>' +
            '<option value="number">Точное значение</option>' +
            '</select>'
        );

        door_style_block_left.append('<label>Позиция по глубине</label>');
        door_style_block_left.append(door_position_z_input);

        door_style_block_right.append('<label style="opacity: 0">Тип значения</label>');
        door_style_block_right.append(door_position_z_type);





        let door_add_modal = new Modal_window({
            heading: 'Добавить декоративный элемент',
            body: door_add_modal_body,
            max_width: 600,
            class: 'yesno'
        });


        door_add_modal.show();

        door_add_modal.yes_button.click(function () {
            door_add_modal.close();

            let params = {};

            if( door_width_type.val() === 'percent' ){
                params.width = door_width_input.val() + '%'
            } else {
                params.width = parseInt( door_width_input.val() );
            }

            if( door_height_type.val() === 'percent' ){
                params.height = door_height_input.val() + '%'
            } else {
                params.height = parseInt( door_height_input.val() );
            }

            if( decor_depth_type.val() === 'percent' ){
                params.thickness = decor_depth_input.val() + '%'
            } else {
                params.thickness = parseInt( decor_depth_input.val() );
            }


            // params.starting_point_x = parseInt( door_position_x_input.val() ) / units;

            if( door_position_x_type.val() === 'percent' ){
                params.starting_point_x = door_position_x_input.val() + '%';
            } else {
                params.starting_point_x = parseInt( door_position_x_input.val() ) / units;
            }

            if( door_position_y_type.val() === 'percent' ){
                params.starting_point_y = door_position_y_input.val() + '%';
            } else {
                params.starting_point_y = parseInt( door_position_y_input.val() )
            }

            if( door_position_z_type.val() === 'percent' ){
                params.starting_point_z = door_position_z_input.val() + '%';
            } else {
                params.starting_point_z = parseInt( door_position_z_input.val() )
            }





            if( group_select.val() === 'bottom' ){
                params.handle_position = 'top';
            } else {
                params.handle_position = 'bottom';
            }

            params.model = decorations_model_input.val();
                // http://3dkitchen.project/common_assets/models/custom/Kolonna_68x716.fbx
            // let door_index = configurator_module_add_door(params);

            let door_index;

            if(cabinet){
                door_index = configurator_module_add_decoration(params, cabinet);
            } else {
                door_index = configurator_module_add_decoration(params);
            }


            let door_button = build_decorations_conf_block(door_index, params)




            decorations_acc_body.append(door_button);


        });



        door_add_modal.no_button.click(function () {
            door_add_modal.close();
        });
    });

    for (let i = 0; i < decoration_params.length; i++){
        decorations_acc_body.append(build_decorations_conf_block(i, decoration_params[i], cabinet));
    }



    let create_module_button = $('<button style="margin-top: 20px;" class="btn btn-block btn-default btn-sm">Сохранить параметры</button>')

    if(configurator_mode === 'new') content_wrapper.append(create_module_button);



    create_module_button.click(function () {

        let save_modal = new Modal_window({
            heading: 'Цвет иконки',
            body: "Выберите цвет генерируемой иконки",
            max_width: 600,
            class: 'yesno'
        });

        save_modal.yes_button.html('Серая');
        save_modal.no_button.html('Красная');

        save_modal.yes_button.click(function () {
            gray_icon = false;
            make_screen_with_module();
            $('#template').val(JSON.stringify(create_cabinet_with_params()));
            $('.three_modal_wrapper').fadeOut();
            save_modal.close();
        })

        save_modal.no_button.click(function () {
            gray_icon = true;
            make_screen_with_module();
            $('#template').val(JSON.stringify(create_cabinet_with_params()));
            $('.three_modal_wrapper').fadeOut();
            save_modal.close();
        })

        save_modal.show();


    });


    scope.append(content_wrapper);

    scope.find('.acc_heading').off('click').click(function () {
        let body = $(this).parent().find('.acc_body');

        if(body.hasClass('opened')){
            body.removeClass('opened').slideUp();
        } else {
            $(this).parent().parent().find('.acc_body.opened').slideUp().removeClass('opened');
            body.addClass('opened').slideDown();
        }
    });

    $('#top_wall_active').prop('checked', cabinet.params.cabinet.top_wall);
    $('#bottom_wall_active').prop('checked', cabinet.params.cabinet.bottom_wall);
    $('#back_wall_active').prop('checked', cabinet.params.cabinet.back_wall);
    $('#left_wall_active').prop('checked', cabinet.params.cabinet.left_wall);
    $('#right_wall_active').prop('checked', cabinet.params.cabinet.right_wall);

    $('#oven_active').prop('checked', cabinet.params.oven.active);
    $('#mic_active').prop('checked', mic_active);
    $('#oven_active_new').prop('checked', oven_active);


    let return_obj = {
        cabinet_group: group_select,
        cabinet: {
            type: type_select,
            width: cabinet_width_input,
            height: cabinet_height_input,
            depth: cabinet_depth_input
        },
        tabletop: {
            active: tabletop_active_select,
            offset:{
                front: tabletop_offset_front_input,
                back: tabletop_offset_back_input,
                // left: tabletop_offset_left_input,
                // right: tabletop_offset_right_input
            }
        }
    };

    return return_obj;
}

function build_cabinet_from_params(bool, cabinet) {


    let params_obj = {
        cabinet: {},
        tabletop: {
            offset:{}
        },
        oven:{},
        fixed_models:[]
    };

    params_obj.cabinet_group = $('#conf_cabinet_group_select').val();
    // params_obj.cabinet.type = inputs_obj.cabinet.type.val();
    params_obj.cabinet.type = 'common';



    params_obj.cabinet.width = parseInt( $('#conf_cabinet_width_input').val() );
    params_obj.cabinet.depth = parseInt( $('#conf_cabinet_depth_input').val() );
    params_obj.cabinet.height = parseInt( $('#conf_cabinet_height_input').val() );

    params_obj.cabinet.top_wall = $('#top_wall_active').prop('checked');
    params_obj.cabinet.bottom_wall = $('#bottom_wall_active').prop('checked');
    params_obj.cabinet.left_wall = $('#left_wall_active').prop('checked');
    params_obj.cabinet.right_wall = $('#right_wall_active').prop('checked');
    params_obj.cabinet.back_wall = $('#back_wall_active').prop('checked');

    params_obj.oven.active = $('#oven_active').prop('checked');
    params_obj.oven.pY = parseInt( $('#oven_position').val() );



    if($('#oven_active_new').prop('checked') == 1){
        params_obj.fixed_models.push({
            model: '/common_assets/models/tech/built_in_cookers/2/model.fbx',
            material: {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.8",
                    "metalness": "0",
                    "map": "/common_assets/models/tech/built_in_cookers/2/map.jpg"
                },
                "add_params": {
                    "real_width": "1024",
                    "real_height": "1024",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            width: 595,
            height: 598,
            depth: 72,

            pos:{
                y: parseInt( $('#oven_position_new').val() ),
            },

            draggable: false
        })
    }

    if($('#mic_active').prop('checked') == 1){
        params_obj.fixed_models.push({
            model: '/common_assets/models/tech/built_in_cookers/2/mic.fbx',
            material: {
                "params": {
                    "color": "#ffffff",
                    "roughness": "0.8",
                    "metalness": "0",
                    "map": "/common_assets/models/tech/built_in_cookers/2/mic_map_w.jpg"
                },
                "add_params": {
                    "real_width": "1024",
                    "real_height": "1024",
                    "stretch_width": "1",
                    "stretch_height": "1",
                    "wrapping": "mirror"
                },
                "type": "Standart"
            },
            width: 595,
            height: 360,
            depth: 23,

            pos:{
                y: parseInt( $('#mic_position').val() ),
            },

            draggable: false
        })
    }

    params_obj.tabletop.active = $('#conf_tabletop_active_select').val() === "1";

    if(!params_obj.tabletop.active) params_obj.tabletop.height = 0;
    // conf_tabletop_offset_back
    // params_obj.tabletop.offset.left =  parseInt( inputs_obj.tabletop.offset.left.val() );
    // params_obj.tabletop.offset.right = parseInt( inputs_obj.tabletop.offset.right.val() );
    params_obj.tabletop.offset.front = parseInt( $('#conf_tabletop_offset_front').val() );
    params_obj.tabletop.offset.back =  parseInt( $('#conf_tabletop_offset_back').val() );


    if(!params_obj.tabletop.active) params_obj.tabletop.offset.front = 0;
    if(!params_obj.tabletop.active) params_obj.tabletop.offset.back = 0;


    params_obj.shelves = shelves_params;
    params_obj.doors = doors_params;
    params_obj.lockers = lockers_params;
    params_obj.decorations = decoration_params;


    for (let i = 0; i < params_obj.doors.length; i++){
        delete params_obj.doors[i].handle_model;
        delete params_obj.doors[i].handle_orientation;
        delete params_obj.doors[i].pX;
        delete params_obj.doors[i].pY;
        delete params_obj.doors[i].pZ;
        delete params_obj.doors[i].rX;
        delete params_obj.doors[i].rY;
        delete params_obj.doors[i].rZ;
        delete params_obj.doors[i].material;
        delete params_obj.doors[i].handle_size_index;
    }

    for (let i = 0; i < params_obj.lockers.length; i++){
        delete params_obj.lockers[i].handle_model;
        delete params_obj.lockers[i].handle_orientation;
        delete params_obj.lockers[i].pX;
        delete params_obj.lockers[i].pY;
        delete params_obj.lockers[i].pZ;
        delete params_obj.lockers[i].rX;
        delete params_obj.lockers[i].rY;
        delete params_obj.lockers[i].rZ;
        delete params_obj.lockers[i].material;
        delete params_obj.lockers[i].handle_size_index;
        delete params_obj.lockers[i].group;
        delete params_obj.lockers[i].corpus_material;
        delete params_obj.lockers[i].walls_visible;
        delete params_obj.lockers[i].depth;
    }

    for (let i = 0; i < params_obj.decorations.length; i++){
        delete params_obj.decorations[i].material;
    }


    if(bool){
        return params_obj;
    } else {

        if( configurator_mode === 'new' ){
            configurator_group.remove(configurator_module);
            configurator_module = new Cabinet_new(params_obj);
            configurator_group.add(configurator_module);
        } else {

            // cabinet.params.cabinet_group =  $('#conf_cabinet_group_select').val();

            cabinet.params.cabinet.width = parseInt( $('#conf_cabinet_width_input').val() );
            cabinet.params.cabinet.depth = parseInt( $('#conf_cabinet_depth_input').val() );
            cabinet.params.cabinet.height = parseInt( $('#conf_cabinet_height_input').val() );

            cabinet.params.tabletop.active = $('#conf_tabletop_active_select').val() === "1";

            if(!cabinet.params.tabletop.active) cabinet.params.tabletop.height = 0;

            cabinet.params.tabletop.offset.front = parseInt( $('#conf_tabletop_offset_front').val() );
            cabinet.params.tabletop.offset.back =  parseInt( $('#conf_tabletop_offset_back').val() );

            if(!cabinet.params.tabletop.active) cabinet.params.tabletop.offset.front = 0;
            if(!cabinet.params.tabletop.active) cabinet.params.tabletop.offset.back = 0;

            cabinet.build();
        }
    }

}

function create_cabinet_with_params() {
    return build_cabinet_from_params(true);
}



function build_shelves_conf_block(index, params, cabinet) {

    if(!cabinet) cabinet = configurator_group.children[0];

    let cabinet_params_block = $('<div data-index="'+ index +'" class="configurator_part_block module_shelve cabinet_params_block container-fluid"></div>');


    let delete_button = $('<span class="glyphicon glyphicon-trash remove_conf_block"></span>');
    cabinet_params_block.append(delete_button);



    delete_button.click(function () {
        let scope = $(this);
        configurator_module_remove_shelves( cabinet_params_block.attr('data-index'), cabinet );
        $('.shelves_params_block').find('.module_shelve').each(function () {
            if( parseInt( cabinet_params_block.attr('data-index') ) < parseInt( $(this).attr('data-index') ) ){
                $(this).attr('data-index', parseInt( $(this).attr('data-index') ) - 1 );
            }
        });
        cabinet_params_block.remove();
    });



    let row = $('<div class="row"></div>');

    let left = $('<div class="col-xs-6"></div>');
    let right = $('<div class="col-xs-6"></div>');



    let width_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.width) +'">');
    let width_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.width === 'string'){
        width_type_select.val('percent');
    } else {
        width_type_select.val('number');
    }

    left.append('<p>Ширина</p>');
    left.append(width_input);

    right.append('<p style="opacity: 0">Ширина</p>');
    right.append(width_type_select);



    let thickness_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.height) +'">');

    left.append('<p>Толщина</p>');
    left.append(thickness_input);


    right.append('<p style="opacity: 0">Ширина</p>');
    right.append('<input style="opacity: 0" class="form-control input-sm" type="text">');

    let starting_point_x = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_x) +'">');

    left.append('<p>Позиция по гор.</p>');
    left.append(starting_point_x);

    let starting_point_x_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.starting_point_x === 'string'){
        starting_point_x_type_select.val('percent');
    } else {
        starting_point_x_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_x_type_select);


    let starting_point_y = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_y) +'">');

    left.append('<p>Позиция по верт.</p>');
    left.append(starting_point_y);

    let starting_point_y_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );


    if(typeof params.starting_point_y === 'string'){
        starting_point_y_type_select.val('percent');
    } else {
        starting_point_y_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_y_type_select);


    let position_offset = $('<input class="form-control input-sm" type="number" value="">');
    let position_offset_type = $('' +
        '<select class="form-control input-sm">' +
        '<option value="top">Сверху</option>' +
        '<option value="bottom">Снизу</option>' +
        '</select>'
    );

    if(params.position_offset_bottom > 0){
        position_offset.val(parseInt(params.position_offset_bottom));
        position_offset_type.val('bottom')
    } else {
        position_offset.val(parseInt(params.position_offset_top));
        position_offset_type.val('top')
    }



    left.append('<p>Фикс. отступ</p>');
    left.append(position_offset);


    right.append('<p style="opacity: 0;">Тип значения</p>');
    right.append(position_offset_type);



    row.append('<p class="col-xs-12 module_configurator_block_heading">Полка</p>');

    row.append(left);
    row.append(right);

    let apply_button = $('<div class="col-xs-12"><button class="btn btn-block btn-default btn-xs">Применить</button></div>')

    row.append(apply_button);
    cabinet_params_block.append(row);


    apply_button.click(function () {


        if(width_type_select.val() === 'percent'){
            shelves_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val()) + '%';
        } else {
            shelves_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val());
        }

        shelves_params[cabinet_params_block.attr('data-index')].height = parseInt(thickness_input.val());

        if(starting_point_x_type_select.val() === 'percent'){
            shelves_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val()) + '%';
        } else {
            shelves_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val());
        }

        if(starting_point_y_type_select.val() === 'percent'){
            shelves_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val()) + '%';
        } else {
            shelves_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val());
        }

        if(position_offset_type.val() === 'top'){
            shelves_params[cabinet_params_block.attr('data-index')].position_offset_bottom = 0;
            shelves_params[cabinet_params_block.attr('data-index')].position_offset_top = parseInt( position_offset.val() );
        } else {
            shelves_params[cabinet_params_block.attr('data-index')].position_offset_bottom = parseInt( position_offset.val() );
            shelves_params[cabinet_params_block.attr('data-index')].position_offset_top = 0;
        }

       build_cabinet_from_params(false, cabinet);
    });


    cabinet_params_block.hover(
        function() {
            tmp_material = cabinet.shelves.children[cabinet_params_block.attr('data-index')].material;
            cabinet.shelves.children[cabinet_params_block.attr('data-index')].material = conf_sel_mat;
        },
        function() {
            cabinet.shelves.children[cabinet_params_block.attr('data-index')].material = tmp_material;
        }
    );


    return cabinet_params_block;
}

function build_doors_conf_block(index, params, cabinet) {

    if(!cabinet) cabinet = configurator_group.children[0];

    let cabinet_params_block = $('<div data-index="'+ index +'" class="configurator_part_block module_door cabinet_params_block container-fluid"></div>');


    let delete_button = $('<span class="glyphicon glyphicon-trash remove_conf_block"></span>');
    cabinet_params_block.append(delete_button);



    delete_button.click(function () {
        let scope = $(this);
        configurator_module_remove_door( cabinet_params_block.attr('data-index'), cabinet );
        $('.configurator_doors_block').find('.module_door').each(function () {
            if( parseInt( cabinet_params_block.attr('data-index') ) < parseInt( $(this).attr('data-index') ) ){
                $(this).attr('data-index', parseInt( $(this).attr('data-index') ) - 1 );
            }
        });
        cabinet_params_block.remove();
    });



    let row = $('<div class="row"></div>');

    let left = $('<div class="col-xs-6"></div>');
    let right = $('<div class="col-xs-6"></div>');


    let doors_types = [
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
            name: 'Фальшфасад',
            type: 'falsefacade'
        }
    ];

    let door_type_select = $('<select class="form-control input-sm"></select>');

    let door_style_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="full">Глухой</option> ' +
        '<option value="window">Витрина</option> ' +
        '<option value="frame">Решетка</option> ' +
        '</select>'
    );

    for (let i = 0; i < doors_types.length; i++){
        door_type_select.append('<option value="'+ doors_types[i].type +'">'+ doors_types[i].name +'</option>')
    }

    left.append('<p>Тип открывания</p>');
    left.append(door_type_select);

    right.append('<p>Тип фасада</p>');
    right.append(door_style_select);

    door_style_select.val(params.style);
    door_type_select.val(params.type);

    let width_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.width) +'">');
    let width_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.width === 'string'){
        width_type_select.val('percent');
    } else {
        width_type_select.val('number');
    }

    left.append('<p>Ширина</p>');
    left.append(width_input);

    right.append('<p style="opacity: 0">Ширина</p>');
    right.append(width_type_select);



    let height_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.height) +'">');
    let height_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.width === 'string'){
        height_type_select.val('percent');
    } else {
        height_type_select.val('number');
    }

    left.append('<p>Высота</p>');
    left.append(height_input);

    right.append('<p style="opacity: 0">Высота</p>');
    right.append(height_type_select);



    let spx = 0;

    if(typeof params.starting_point_x === 'string'){
        spx = parseInt(params.starting_point_x);
    } else {
        spx = parseInt(params.starting_point_x * units);
    }

    let starting_point_x = $('<input class="form-control input-sm" type="number" value="'+ spx +'">');

    left.append('<p>Позиция по гор.</p>');
    left.append(starting_point_x);

    let starting_point_x_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.starting_point_x === 'string'){
        starting_point_x_type_select.val('percent');
    } else {
        starting_point_x_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_x_type_select);


    let starting_point_y = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_y) +'">');

    left.append('<p>Позиция по верт.</p>');
    left.append(starting_point_y);

    let starting_point_y_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );


    if(typeof params.starting_point_y === 'string'){
        starting_point_y_type_select.val('percent');
    } else {
        starting_point_y_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_y_type_select);


    let door_offset_top_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.offset_top) +'">');
    let door_offset_bottom_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.offset_bottom) +'">');

    left.append('<p>Отступ сверху (мм)</p>');
    left.append(door_offset_top_input);

    right.append('<p >Отступ снизу (мм)</p>');
    right.append(door_offset_bottom_input);


    let handle_position_select = $('' +
        '<select class="form-control input-sm" id="handle_position_select">' +
        '<option value="top">Сверху</option>' +
        '<option value="bottom">Снизу</option>' +
        '<option value="middle">По центру</option>' +
        '<option value="no_handle">Без ручки</option>' +
        '</select>'
    );

    handle_position_select.val(params.handle_position);

    console.log(params)

    if(params.no_handle_forced === true){
        handle_position_select.val('no_handle')
    }

    left.append('<p>Положение ручки</p>');
    left.append(handle_position_select);


    let door_material_group_select = $('' +
        '<select class="form-control" id="door_material_group_select_cnf">' +
        '<option value="top">Верхние модули</option>' +
        '<option value="bottom">Нижние модули</option>' +
        '</select>'
    );

    door_material_group_select.val(params.group)


    right.append('<p>Цвет фасада</p>');
    right.append(door_material_group_select);



    row.append(left);
    row.append(right);

    let door_model_input = $('<input class="form-control" type="text">');
    let model_col = $('<div class="col-xs-12"></div>')

    door_model_input.val(params.fixed_facade_model)

    row.append(model_col);
    model_col.append('<label>Фиксированная модель двери</label>');
    model_col.append(door_model_input);

    row.append(model_col)


    cabinet_params_block.append(row);

    let apply_button = $('<div class="col-xs-12"><button class="btn btn-block btn-default btn-xs">Применить</button></div>')

    row.append(apply_button);

    apply_button.click(function () {

        if(width_type_select.val() === 'percent'){
            doors_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val()) + '%';
        } else {
            doors_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val());
        }

        if(height_type_select.val() === 'percent'){
            doors_params[cabinet_params_block.attr('data-index')].height = parseInt(height_input.val()) + '%';
        } else {
            doors_params[cabinet_params_block.attr('data-index')].height = parseInt(height_input.val());
        }

        doors_params[cabinet_params_block.attr('data-index')].type = door_type_select.val();
        doors_params[cabinet_params_block.attr('data-index')].style = door_style_select.val();


        if(starting_point_x_type_select.val() === 'percent'){
            doors_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val()) + '%';
        } else {
            doors_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val() / units);
        }

        if(starting_point_y_type_select.val() === 'percent'){
            doors_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val()) + '%';
        } else {
            doors_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val());
        }

        doors_params[cabinet_params_block.attr('data-index')].offset_top = parseInt( door_offset_top_input.val() );
        doors_params[cabinet_params_block.attr('data-index')].offset_bottom = parseInt( door_offset_bottom_input.val() );

        if(handle_position_select.val() === 'no_handle'){
            doors_params[cabinet_params_block.attr('data-index')].no_handle_forced = true
        } else {
            doors_params[cabinet_params_block.attr('data-index')].no_handle_forced = false
        }

        doors_params[cabinet_params_block.attr('data-index')].handle_position = handle_position_select.val();
        doors_params[cabinet_params_block.attr('data-index')].group = door_material_group_select.val();

        if(door_model_input.val() !== ''){
            doors_params[cabinet_params_block.attr('data-index')].fixed_facade_model = door_model_input.val();
        } else {
            doors_params[cabinet_params_block.attr('data-index')].fixed_facade_model = undefined;
        }

        build_cabinet_from_params(false, cabinet);

    });

    cabinet_params_block.hover(

        function() {
            tmp_material = cabinet.doors.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material;
            cabinet.doors.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material = conf_sel_mat;
        },
        function() {
            cabinet.doors.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material = tmp_material;
        }
    );

    return cabinet_params_block;
}

function build_lockers_conf_block(index, params, cabinet) {

    if(!cabinet) cabinet = configurator_group.children[0];

    let cabinet_params_block = $('<div data-index="'+ index +'" class="configurator_part_block module_locker cabinet_params_block container-fluid"></div>');


    let delete_button = $('<span class="glyphicon glyphicon-trash remove_conf_block"></span>');
    cabinet_params_block.append(delete_button);


    delete_button.click(function () {
        let scope = $(this);
        configurator_module_remove_locker( cabinet_params_block.attr('data-index'),  cabinet );
        $('.configurator_lockers_block').find('.module_locker').each(function () {
            if( parseInt( cabinet_params_block.attr('data-index') ) < parseInt( $(this).attr('data-index') ) ){
                $(this).attr('data-index', parseInt( $(this).attr('data-index') ) - 1 );
            }
        });
        cabinet_params_block.remove();
    });



    let row = $('<div class="row"></div>');

    let left = $('<div class="col-xs-6"></div>');
    let right = $('<div class="col-xs-6"></div>');




    let style_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="full">Глухой</option> ' +
        '<option value="window">Витрина</option> ' +
        '<option value="frame">Решетка</option> ' +
        '</select>'
    );

    let type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="common">Обычный</option> ' +
        '<option value="inner">Внутренний</option> ' +
        '</select>'
    );

    left.append('<p>Тип фасада</p>');
    left.append(style_select);

    right.append('<p>Тип ящика</p>');
    right.append(type_select);



    style_select.val(params.style);
    let v = 'common';
    if(params.inner == true) v = 'inner'
    type_select.val(v);


    let width_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.width) +'">');
    let width_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.width === 'string'){
        width_type_select.val('percent');
    } else {
        width_type_select.val('number');
    }

    left.append('<p>Ширина</p>');
    left.append(width_input);

    right.append('<p style="opacity: 0">Ширина</p>');
    right.append(width_type_select);



    let height_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.height) +'">');
    let height_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.width === 'string'){
        height_type_select.val('percent');
    } else {
        height_type_select.val('number');
    }

    left.append('<p>Высота</p>');
    left.append(height_input);

    right.append('<p style="opacity: 0">Высота</p>');
    right.append(height_type_select);



    let starting_point_x = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_x) +'">');

    left.append('<p>Позиция по гор.</p>');
    left.append(starting_point_x);

    let starting_point_x_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.starting_point_x === 'string'){
        starting_point_x_type_select.val('percent');
    } else {
        starting_point_x_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_x_type_select);


    let starting_point_y = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_y) +'">');

    left.append('<p>Позиция по верт.</p>');
    left.append(starting_point_y);

    let starting_point_y_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );


    if(typeof params.starting_point_y === 'string'){
        starting_point_y_type_select.val('percent');
    } else {
        starting_point_y_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_y_type_select);



    let handle_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="1">Да</option>' +
        '<option value="0">Нет</option>' +
        '</select>'
    );


    if(params.no_handle_forced === true){
        handle_select.val('0')
    } else {
        handle_select.val('1')
    }


    left.append('<p>Ручка</p>');
    left.append(handle_select);



    row.append(left);
    row.append(right);

    let locker_model_input = $('<input class="form-control" type="text">');
    let model_col = $('<div class="col-xs-12"></div>')

    row.append(model_col);
    model_col.append('<label>Фиксированная модель фасада</label>');
    model_col.append(locker_model_input);

    row.append(model_col)

    locker_model_input.val(params.fixed_facade_model);


    cabinet_params_block.append(row);

    let apply_button = $('<div class="col-xs-12"><button class="btn btn-block btn-default btn-xs">Применить</button></div>')

    row.append(apply_button);

    apply_button.click(function () {

        if(width_type_select.val() === 'percent'){
            lockers_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val()) + '%';
        } else {
            lockers_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val());
        }

        if(height_type_select.val() === 'percent'){
            lockers_params[cabinet_params_block.attr('data-index')].height = parseInt(height_input.val()) + '%';
        } else {
            lockers_params[cabinet_params_block.attr('data-index')].height = parseInt(height_input.val());
        }

        lockers_params[cabinet_params_block.attr('data-index')].style = style_select.val();


        if(starting_point_x_type_select.val() === 'percent'){
            lockers_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val()) + '%';
        } else {
            lockers_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val());
        }

        if(starting_point_y_type_select.val() === 'percent'){
            lockers_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val()) + '%';
        } else {
            lockers_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val());
        }


        if(handle_select.val() === '1'){
            lockers_params[cabinet_params_block.attr('data-index')].no_handle_forced = false;
        } else {
            lockers_params[cabinet_params_block.attr('data-index')].no_handle_forced = true;
        }

        if(locker_model_input.val() !== ''){
            lockers_params[cabinet_params_block.attr('data-index')].fixed_facade_model = locker_model_input.val();
        } else {
            lockers_params[cabinet_params_block.attr('data-index')].fixed_facade_model = undefined

        }

        if(type_select.val() === 'common'){
            lockers_params[cabinet_params_block.attr('data-index')].inner = false;
        } else {
            lockers_params[cabinet_params_block.attr('data-index')].inner = true;
        }

        build_cabinet_from_params(false, cabinet);

    });

    cabinet_params_block.hover(
        function() {
            if(cabinet.lockers.children[cabinet_params_block.attr('data-index')].facade) {
                tmp_material = cabinet.lockers.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material;
                cabinet.lockers.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material = conf_sel_mat;
            } else {
                if(cabinet.lockers.children[cabinet_params_block.attr('data-index')].front_wall){
                    tmp_material = cabinet.lockers.children[cabinet_params_block.attr('data-index')].front_wall;
                    cabinet.lockers.children[cabinet_params_block.attr('data-index')].front_wall = conf_sel_mat;
                }

            }
        },
        function() {
            if(cabinet.lockers.children[cabinet_params_block.attr('data-index')].facade){
                cabinet.lockers.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material = tmp_material;
            } else {
                if(cabinet.lockers.children[cabinet_params_block.attr('data-index')].front_wall)
                    cabinet.lockers.children[cabinet_params_block.attr('data-index')].front_wall = tmp_material;
            }

        }
    );

    return cabinet_params_block;
}


function build_decorations_conf_block(index, params, cabinet) {


    if(!cabinet) cabinet = configurator_group.children[0];

    let cabinet_params_block = $('<div data-index="'+ index +'" class="configurator_part_block module_decoration cabinet_params_block container-fluid"></div>');


    let delete_button = $('<span class="glyphicon glyphicon-trash remove_conf_block"></span>');
    cabinet_params_block.append(delete_button);



    delete_button.click(function () {
        let scope = $(this);
        configurator_module_remove_decoration( cabinet_params_block.attr('data-index'), cabinet );
        $('.configurator_doors_block').find('.module_decoration').each(function () {
            if( parseInt( cabinet_params_block.attr('data-index') ) < parseInt( $(this).attr('data-index') ) ){
                $(this).attr('data-index', parseInt( $(this).attr('data-index') ) - 1 );
            }
        });
        cabinet_params_block.remove();
    });



    let row = $('<div class="row"></div>');

    let left = $('<div class="col-xs-6"></div>');
    let right = $('<div class="col-xs-6"></div>');



    let decorations_model_input = $('<input class="form-control" id="decoration_model_input" type="text" value="'+ params.model +'">');
    let model_col = $('<div class="col-xs-12"></div>')

    row.append(model_col);
    model_col.append('<label>Ссылка на модель</label>');
    model_col.append(decorations_model_input);





    let width_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.width) +'">');
    let width_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.width === 'string'){
        width_type_select.val('percent');
    } else {
        width_type_select.val('number');
    }

    left.append('<p>Ширина</p>');
    left.append(width_input);

    right.append('<p style="opacity: 0">Ширина</p>');
    right.append(width_type_select);



    let height_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.height) +'">');
    let height_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.height === 'string'){
        height_type_select.val('percent');
    } else {
        height_type_select.val('number');
    }
    left.append('<p>Высота</p>');
    left.append(height_input);

    right.append('<p style="opacity: 0">Высота</p>');
    right.append(height_type_select);


    let depth_input = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.thickness) +'">');
    let depth_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.thickness === 'string'){
        depth_type_select.val('percent');
    } else {
        depth_type_select.val('number');
    }


    left.append('<p>Толщина</p>');
    left.append(depth_input);

    right.append('<p style="opacity: 0">Толщина</p>');
    right.append(depth_type_select);




    let starting_point_x = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_x) +'">');

    left.append('<p>Позиция по гор.</p>');
    left.append(starting_point_x);

    let starting_point_x_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );

    if(typeof params.starting_point_x === 'string'){
        starting_point_x_type_select.val('percent');
    } else {
        starting_point_x_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_x_type_select);


    let starting_point_y = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_y) +'">');

    left.append('<p>Позиция по верт.</p>');
    left.append(starting_point_y);

    let starting_point_y_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );


    if(typeof params.starting_point_y === 'string'){
        starting_point_y_type_select.val('percent');
    } else {
        starting_point_y_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_y_type_select);




    let starting_point_z = $('<input class="form-control input-sm" type="number" value="'+ parseInt(params.starting_point_z) +'">');

    left.append('<p>Позиция по глуб.</p>');
    left.append(starting_point_z);

    let starting_point_z_type_select = $('' +
        '<select class="form-control input-sm">' +
        '<option value="percent">Проценты</option>' +
        '<option value="number">Точное значение</option>' +
        '</select>'
    );


    if(typeof params.starting_point_z === 'string'){
        starting_point_z_type_select.val('percent');
    } else {
        starting_point_z_type_select.val('number');
    }


    right.append('<p>Тип значения</p>');
    right.append(starting_point_z_type_select);







    row.append(left);
    row.append(right);

    cabinet_params_block.append(row);

    let apply_button = $('<div class="col-xs-12"><button class="btn btn-block btn-default btn-xs">Применить</button></div>')

    row.append(apply_button);

    apply_button.click(function () {

        decoration_params[cabinet_params_block.attr('data-index')].model = decorations_model_input.val();


        if(width_type_select.val() === 'percent'){
            decoration_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val()) + '%';
        } else {
            decoration_params[cabinet_params_block.attr('data-index')].width = parseInt(width_input.val());
        }

        if(height_type_select.val() === 'percent'){
            decoration_params[cabinet_params_block.attr('data-index')].height = parseInt(height_input.val()) + '%';
        } else {
            decoration_params[cabinet_params_block.attr('data-index')].height = parseInt(height_input.val());
        }

        if(depth_type_select.val() === 'percent'){
            decoration_params[cabinet_params_block.attr('data-index')].thickness = parseInt(depth_input.val()) + '%';
        } else {
            decoration_params[cabinet_params_block.attr('data-index')].thickness = parseInt(depth_input.val());
        }



        if(starting_point_x_type_select.val() === 'percent'){
            decoration_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val()) + '%';
        } else {
            decoration_params[cabinet_params_block.attr('data-index')].starting_point_x = parseInt(starting_point_x.val());
        }

        if(starting_point_y_type_select.val() === 'percent'){
            decoration_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val()) + '%';
        } else {
            decoration_params[cabinet_params_block.attr('data-index')].starting_point_y = parseInt(starting_point_y.val());
        }

        if(starting_point_z_type_select.val() === 'percent'){
            decoration_params[cabinet_params_block.attr('data-index')].starting_point_z = parseInt(starting_point_z.val()) + '%';
        } else {
            decoration_params[cabinet_params_block.attr('data-index')].starting_point_z = parseInt(starting_point_z.val());
        }



        build_cabinet_from_params(false, cabinet);

    });

    cabinet_params_block.hover(

        // function() {
        //     tmp_material = cabinet.decorations.children[cabinet_params_block.attr('data-index')].model.children[0].material;
        //     cabinet.doors.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material = conf_sel_mat;
        // },
        // function() {
        //     cabinet.doors.children[cabinet_params_block.attr('data-index')].facade.model.children[0].material = tmp_material;
        // }
    );

    return cabinet_params_block;
}





function configurator_module_add_door(params, cabinet) {
    doors_params.push(params);
    build_cabinet_from_params(false, cabinet);

    return doors_params.length - 1;
}

function configurator_module_remove_door(index, cabinet) {
    doors_params.splice(index,1);

    build_cabinet_from_params(false, cabinet);
}

function configurator_module_add_decoration(params, cabinet) {
    decoration_params.push(params);
    build_cabinet_from_params(false, cabinet);

    return decoration_params.length - 1;
}

function configurator_module_remove_decoration(index, cabinet) {
    decoration_params.splice(index,1);

    build_cabinet_from_params(false, cabinet);
}


function configurator_module_add_locker(params, cabinet) {
    lockers_params.push(params);
    build_cabinet_from_params(false, cabinet);

    return lockers_params.length - 1;
}

function configurator_module_remove_locker(index, cabinet) {
    lockers_params.splice(index,1);

    build_cabinet_from_params(false, cabinet);
}


function configurator_module_add_shelves(params, cabinet) {

    shelves_params.push(params);

    build_cabinet_from_params(false, cabinet);

    return shelves_params.length - 1;
}

function configurator_module_remove_shelves(index, cabinet) {

    shelves_params.splice(index,1);

    build_cabinet_from_params(false, cabinet);
}


function make_screen_with_module() {



    var cab = configurator_group.children[0];

    cab.sizes.visible = false;


    var box = new THREE.Box3().setFromObject(cab).getSize();


    if( gray_icon === false ){

        w_mat.transparent = true;
        w_mat.opacity = 0;

        let mt = find_obj_by_id(materials_catalog.items, 1)
        mt.params.color = '#545454';
        mt.params.transparent = true;
        mt.params.opacity = 0.7
        console.log(mt)

        gray_mat.transparent = true;
        gray_mat.opacity = 0.7;

        cab.change_facade_material({id:1,group:'all'})
    } else {
        let mt = find_obj_by_id(materials_catalog.items, 1)
        mt.params.color = '#932129';
        mt.params.transparent = false;



        cab.change_facade_material({id:1,group:'all'})
    }




    cab.position.set(
        0,- box.y / 2,0
    );



    controls.target.set(
        0,
        0,
        0
    );
    camera.position.set(
        140,
        64,
        205

        // 71,32,105
        // 83,38,122
        // -3,81,128

        // 221,
        // 100,
        // 324


        // 140,
        // 64,
        // 205
        // 222,
        // 101,
        // 325

        // 87,
        // 39,
        // 128,

        // 171,
        // 76,
        // 250

        // 189,
        // 86,
        // 277
        // 199,
        // 90,
        // 292
    );


    room.hide();


    controls.update();
    setTimeout(function () {
        var canvas = document.createElement('canvas');
        // canvas.width = 400;
        // canvas.height = 400;
        // var ctx = canvas.getContext('2d');
        //
        // var left0 = renderer.domElement.width / 2 - 250;
        // var top0 = renderer.domElement.height / 2 - 250;
        //
        //
        // ctx.drawImage(renderer.domElement,left0,top0+20,500,500,0,0,400,400);


        if(cab.params.cabinet.height > 1300){
            canvas.width = 400;
            canvas.height = 800;
            var ctx = canvas.getContext('2d');

            var left0 = renderer.domElement.width / 2 - 250;
            var top0 = renderer.domElement.height / 2 - 500;


            ctx.drawImage(renderer.domElement,left0,top0+20,500,1000,0,0,400,800);
        } else {
            canvas.width = 400;
            canvas.height = 400;
            var ctx = canvas.getContext('2d');

            var left0 = renderer.domElement.width / 2 - 250;
            var top0 = renderer.domElement.height / 2 - 250;


            ctx.drawImage(renderer.domElement,left0,top0+20,500,500,0,0,400,400);
        }



        var data = canvas.toDataURL();


        $('#module_icon').attr('src', data);
        $('#module_icon_input').val(data);


        let mt = find_obj_by_id(materials_catalog.items, 1)
        mt.params.color = '#932129';
        mt.params.transparent = false;
        gray_mat.transparent = false;


        let mt2 = find_obj_by_id(materials_catalog.items, 3)
        mt2.params.color = '#de761f';
        mt2.params.transparent = false;
        gray_mat.transparent = false;

        w_mat.transparent = false;

        cab.change_facade_material({id:1,group:'bottom'})
        cab.change_facade_material({id:3,group:'top'})


        // $.ajax({
        //     type: "POST",
        //     url: "admin/catalog_controller.php",
        //     data: {
        //         module_icon: data,
        //         id: params.id,
        //         data: JSON.stringify(modules_catalog),
        //         data_url: data_url
        //     }
        // }).done(function(o) {
        //     console.log('Успешно сохранено' + o);
        // });

        // var link = document.createElement("a");
        // link.download = "image.jpg";
        // canvas.toBlob(function (blob) {
        //     link.href = URL.createObjectURL(blob);
        //     link.click();
        // }, 'image/png');



    },1000)
}