$(document).ready(function () {

    var xhr;
    var ajax_url = $('#ajax_url').val();

    var bottom_as_top_facade_models = 1;
    var bottom_as_top_facade_materials = 1;
    var bottom_as_top_corpus_materials = 1;

    $('#facades_categories').selectize({
        create: false,
        plugins: ['remove_button']
    });

    $('#bottom_as_top_facade_models').change(function () {
        bottom_as_top_facade_models = $(this).val();
        if($(this).val() == 1){
            $('#facades_models_bottom_wrapper').addClass('hidden');
            $('#bottom_as_top_facade_materials').prop("disabled", false);
            facades_models_bottom[0].selectize.setValue($('#facades_models_top').val());

            bottom_as_top_facade_materials = 1;
            $('#bottom_as_top_facade_materials').val(1);

            $('#facades_selected_materials_bottom_wrapper').addClass('hidden');
        } else {
            $('#facades_models_bottom_wrapper').removeClass('hidden');

            bottom_as_top_facade_materials = 0;
            $('#bottom_as_top_facade_materials').val(0);

            $('#bottom_as_top_facade_materials').prop("disabled", true);
            $('#facades_selected_materials_bottom_wrapper').removeClass('hidden');
        }
    });

    $('#bottom_as_top_facade_materials').change(function () {
        if(bottom_as_top_facade_models == 1){
            bottom_as_top_facade_materials = $(this).val();
            if($(this).val() == 1){
                $('#facades_selected_materials_bottom_wrapper').addClass('hidden');
                facades_selected_materials_bottom[0].selectize.setValue($('#facades_selected_materials_top').val())
            } else {
                $('#facades_selected_materials_bottom_wrapper').removeClass('hidden');
            }
        }
    });

    var facades_models_top = $('#facades_models_top').selectize({
        create: false,
        render: {
            option: function(item, escape) {
                return '<div style="margin-bottom: 10px">' +
                    '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.icon) +'">' +
                    '<span class="name">'+ escape(item.name) +'</span>' +
                    '</div>';
            }
        },
        onChange: function(value) {
            if(bottom_as_top_facade_models == 1){
                facades_models_bottom[0].selectize.setValue(value)
            }

            var select = facades_selected_materials_top[0].selectize;

            if(bottom_as_top_facade_models == 1){
                var select_bottom = facades_selected_materials_bottom[0].selectize;

                select_bottom.disable();
                select_bottom.clearOptions();
                select_bottom.setValue("", true);

            }

            select.disable();
            select.clearOptions();
            select.setValue("", true);

            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_materials_by_facade_model_ajax/' + value,
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();

                        if(bottom_as_top_facade_models == 1) {
                            select_bottom.enable();
                        }
                        for (var i = 0; i < data.length; i++) {


                            for(var m = 0; m < data[i].items.length; m++){
                                var name;
                                var map;

                                if(data[i].items[m].map !== null){
                                    map = data[i].items[m].map;
                                    if(map.indexOf('common_assets') !== -1){

                                    } else {
                                        map = $('#asset_path').val() + map;
                                    }
                                }

                                if(data[i].items[m].code !== null){
                                    name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                } else {
                                    name = data[i].items[m].name;
                                }

                                select.addOption({
                                    value: data[i].items[m].id,
                                    text: name,
                                    map: map,
                                    color: data[i].items[m].color
                                })


                                if(bottom_as_top_facade_models == 1) {
                                    select_bottom.addOption({
                                        value: data[i].items[m].id,
                                        text: name,
                                        map: map,
                                        color: data[i].items[m].color
                                    })
                                }
                            }
                        }
                        select.refreshOptions();
                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var facades_models_bottom = $('#facades_models_bottom').selectize({
        create: false,
        render: {
            option: function(item, escape) {
                return '<div style="margin-bottom: 10px">' +
                    '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.icon) +'">' +
                    '<span class="name">'+ escape(item.name) +'</span>' +
                    '</div>';
            }
        },
        onChange: function(value) {


            var select = facades_selected_materials_bottom[0].selectize;


            select.disable();
            select.clearOptions();
            select.setValue("", true);

            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_materials_by_facade_model_ajax/' + value,
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();


                        for (var i = 0; i < data.length; i++) {


                            for(var m = 0; m < data[i].items.length; m++){
                                var name;
                                var map;

                                if(data[i].items[m].map !== null){
                                    map = data[i].items[m].map;
                                    if(map.indexOf('common_assets') !== -1){

                                    } else {
                                        map = $('#asset_path').val() + map;
                                    }
                                }

                                if(data[i].items[m].code !== null){
                                    name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                } else {
                                    name = data[i].items[m].name;
                                }

                                select.addOption({
                                    value: data[i].items[m].id,
                                    text: name,
                                    map: map,
                                    color: data[i].items[m].color
                                });



                            }
                        }
                        select.refreshOptions();

                        if(bottom_as_top_facade_models == 1){
                            facades_selected_materials_bottom[0].selectize.setValue($('#facades_selected_materials_top').val());
                        }


                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var facades_selected_materials_top = $('#facades_selected_materials_top').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        },
        onChange: function(value) {
            if(bottom_as_top_facade_materials == 1 || bottom_as_top_facade_models == 1){
                facades_selected_materials_bottom[0].selectize.setValue(value)
            }

        }

    });

    var facades_selected_materials_bottom = $('#facades_selected_materials_bottom').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        },
        onChange: function(value) {
            console.log(value)
        }
    });


    $('#bottom_as_top_corpus_materials').change(function () {
        bottom_as_top_corpus_materials = $(this).val();
        if($(this).val() == 1){
            $('#selected_corpus_material_bottom_wrapper').addClass('hidden');
            selected_corpus_material_bottom[0].selectize.setValue($('#selected_corpus_material_top').val());
        } else {
            $('#selected_corpus_material_bottom_wrapper').removeClass('hidden');
        }
    });

    var corpus_materials_top = $('#corpus_materials_top').selectize({
        create: false,
        plugins: ['remove_button'],
        onChange: function(value) {


            var select = selected_corpus_material_top[0].selectize;
            var bottom_select = selected_corpus_material_bottom[0].selectize

            select.disable();
            select.clearOptions();
            select.setValue("", true);

            bottom_select.disable();
            bottom_select.clearOptions();
            bottom_select.setValue("", true);


            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_materials_by_category_ajax/',
                    type: 'post',
                    data: {data:value},
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();
                        bottom_select.enable();
                        for (var i = 0; i < data.length; i++) {


                            for(var m = 0; m < data[i].items.length; m++){
                                var name;
                                var map;

                                if(data[i].items[m].map !== null){
                                    map = data[i].items[m].map;
                                    if(map.indexOf('common_assets') !== -1){

                                    } else {
                                        map = $('#asset_path').val() + map;
                                    }
                                }

                                if(data[i].items[m].code !== null){
                                    name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                } else {
                                    name = data[i].items[m].name;
                                }

                                select.addOption({
                                    value: data[i].items[m].id,
                                    text: name,
                                    map: map,
                                    color: data[i].items[m].color
                                });

                                bottom_select.addOption({
                                    value: data[i].items[m].id,
                                    text: name,
                                    map: map,
                                    color: data[i].items[m].color
                                });



                            }
                        }
                        // select.refreshOptions();




                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var selected_corpus_material_top = $('#selected_corpus_material_top').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        },
        onChange: function(value) {
            if(bottom_as_top_corpus_materials == 1){
                selected_corpus_material_bottom[0].selectize.setValue($('#selected_corpus_material_top').val());
            }

        }
    });

    var selected_corpus_material_bottom = $('#selected_corpus_material_bottom').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        },
        onChange: function(value) {
            // if(bottom_as_top_facade_materials == 1 || bottom_as_top_facade_models == 1){
            //     facades_selected_materials_bottom[0].selectize.setValue(value)
            // }

        }
    });


    $('#cokol_as_corpus').change(function () {
        if($(this).val() == 1){
            $('#selected_cokol_material_wrapper').addClass('hidden');
        } else {
            $('#selected_cokol_material_wrapper').removeClass('hidden');
        }
    })


    var cokol_materials = $('#cokol_materials').selectize({
        create: false,
        plugins: ['remove_button'],
        onChange: function(value) {


            var select = selected_cokol_material[0].selectize;

            select.disable();
            select.clearOptions();
            select.setValue("", true);

            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_materials_by_category_ajax/',
                    type: 'post',
                    data: {data:value},
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();
                        for (var i = 0; i < data.length; i++) {
                            for(var m = 0; m < data[i].items.length; m++){
                                var name;
                                var map;

                                if(data[i].items[m].map !== null){
                                    map = data[i].items[m].map;
                                    if(map.indexOf('common_assets') !== -1){

                                    } else {
                                        map = $('#asset_path').val() + map;
                                    }
                                }

                                if(data[i].items[m].code !== null){
                                    name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                } else {
                                    name = data[i].items[m].name;
                                }

                                select.addOption({
                                    value: data[i].items[m].id,
                                    text: name,
                                    map: map,
                                    color: data[i].items[m].color
                                });
                            }
                        }

                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var selected_cokol_material = $('#selected_cokol_material').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        }
    });


    var tabletop_materials = $('#tabletop_materials').selectize({
        create: false,
        plugins: ['remove_button'],
        onChange: function(value) {


            var select = selected_tabletop_materials[0].selectize;

            select.disable();
            select.clearOptions();
            select.setValue("", true);

            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_materials_by_category_ajax/',
                    type: 'post',
                    data: {data:value},
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();
                        for (var i = 0; i < data.length; i++) {
                            for(var m = 0; m < data[i].items.length; m++){
                                var name;
                                var map;

                                if(data[i].items[m].map !== null){
                                    map = data[i].items[m].map;
                                    if(map.indexOf('common_assets') !== -1){

                                    } else {
                                        map = $('#asset_path').val() + map;
                                    }
                                }

                                if(data[i].items[m].code !== null){
                                    name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                } else {
                                    name = data[i].items[m].name;
                                }

                                select.addOption({
                                    value: data[i].items[m].id,
                                    text: name,
                                    map: map,
                                    color: data[i].items[m].color
                                });
                            }
                        }

                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var selected_tabletop_materials = $('#selected_tabletop_materials').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        }
    });


    var glass_materials = $('#glass_materials').selectize({
        create: false,
        plugins: ['remove_button'],
        onChange: function(value) {


            var select = selected_glass_material[0].selectize;

            select.disable();
            select.clearOptions();
            select.setValue("", true);

            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_glass_materials_by_category_ajax/',
                    type: 'post',
                    data: {data:value},
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();
                        for (var i = 0; i < data.length; i++) {
                            for(var m = 0; m < data[i].items.length; m++){
                                var name;
                                var map;
                                var icon = undefined;
                                let params = JSON.parse(data[i].items[m].params);



                                if(params.params.icon ){
                                    icon = params.params.icon;
                                    if(icon.indexOf('common_assets') !== -1){

                                    } else {
                                        icon = $('#asset_path').val() + icon;
                                    }
                                }

                                if(params.params.map ){
                                    map = params.params.map;
                                    if(map.indexOf('common_assets') !== -1){

                                    } else {
                                        map = $('#asset_path').val() + map;
                                    }
                                }

                                if(params.code !== null){
                                    name = params.name + ' ' + params.code;
                                } else {
                                    name = params.name;
                                }

                                select.addOption({
                                    value: params.id,
                                    text: name,
                                    map: icon !== undefined ? icon : map,
                                    color: params.params.color
                                });
                            }
                        }

                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var selected_glass_material = $('#selected_glass_material').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        }
    });


    var wallpanel_materials = $('#wallpanel_materials').selectize({
        create: false,
        plugins: ['remove_button'],
        onChange: function(value) {


            var select = selected_wallpanel_materials[0].selectize;

            select.disable();
            select.clearOptions();
            select.setValue("", true);

            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_materials_by_category_ajax/',
                    type: 'post',
                    data: {data:value},
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();
                        for (var i = 0; i < data.length; i++) {
                            for(var m = 0; m < data[i].items.length; m++){
                                var name;
                                var map;

                                if(data[i].items[m].map !== null){
                                    map = data[i].items[m].map;
                                    if(map.indexOf('common_assets') !== -1){

                                    } else {
                                        map = $('#asset_path').val() + map;
                                    }
                                }

                                if(data[i].items[m].code !== null){
                                    name = data[i].items[m].name + ' ' + data[i].items[m].code;
                                } else {
                                    name = data[i].items[m].name;
                                }

                                select.addOption({
                                    value: data[i].items[m].id,
                                    text: name,
                                    map: map,
                                    color: data[i].items[m].color
                                });
                            }
                        }

                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var selected_wallpanel_materials = $('#selected_wallpanel_materials').selectize({
        create:false,
        render: {
            option: function(item, escape) {
                var string = '<div style="margin-bottom: 10px">';
                if(item.map !== undefined){
                    string += '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.map) +'">';
                } else {
                    string += '<div style="margin-right: 20px; vertical-align: middle; width: 50px; height: 50px; display: inline-block; background: '+ item.color +'"></div>'
                }
                string += '<span class="name">'+ escape(item.text) +'</span>';
                string += '</div>';

                return string;
            }
        }
    });



    $('#no_handle').change(function () {
       if($(this).val() == 0){
            $('#handle_orientation_wrapper').removeClass('hidden');
            $('#handle_lockers_position_wrapper').removeClass('hidden');
            $('#handle_selected_model_wrapper').removeClass('hidden');
            $('#handle_preferable_size_wrapper').removeClass('hidden');
            $('#allow_handles_select_wrapper').removeClass('hidden');
       } else {
           $('#handle_orientation_wrapper').addClass('hidden');
           $('#handle_lockers_position_wrapper').addClass('hidden');
           $('#handle_selected_model_wrapper').addClass('hidden');
           $('#handle_preferable_size_wrapper').addClass('hidden');
           $('#allow_handles_select_wrapper').addClass('hidden');
       }
    });


    var handle_selected_model = $('#handle_selected_model').selectize({
        create: false,
        render: {
            option: function(item, escape) {
                return '<div style="margin-bottom: 10px">' +
                    '<img style="max-width: 50px; margin-right: 20px" src="'+ escape(item.icon) +'">' +
                    '<span class="name">'+ escape(item.name) +'</span>' +
                    '</div>';
            }
        },
        onChange: function(value) {

            var select = handle_preferable_size[0].selectize;

            select.disable();
            select.clearOptions();
            select.setValue("", true);

            select.load(function (callback) {
                xhr && xhr.abort();
                xhr = $.ajax({
                    url: ajax_url + 'get_handle_ajax/' + value,
                    success: function (results) {
                        var data = JSON.parse(results);
                        select.enable();


                        for (var i = 0; i < data.length; i++) {

                            var name = 'Ширина ' + data[i].width + 'мм, Межосевое расстояние ' + data[i].axis_size + 'мм';

                            select.addOption({
                                value: i,
                                text: name
                            });

                        }
                        select.refreshOptions();


                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });

        }
    });

    var handle_preferable_size = $('#handle_preferable_size').selectize({
        create: false
    });


    $('#sub_form').submit(function () {

        var errors = [];

        if($('#name').val() === ''){
            errors.push('Название не указано');
            $('label[for="name"]').css('color', 'red')
        } else {
            $('label[for="name"]').css('color', '#333')
        }


        if($('#icon')[0].files.length === 0){
            errors.push('Иконка не выбрана');
            $('label[for="icon"]').css('color', 'red')
        } else {
            $('label[for="icon"]').css('color', '#333')
        }

        if($('#facades_models_top').val() === ''){
            errors.push('Модель фасада верхних модулей не выбрана');
            $('label[for="facades_models_top-selectized"]').css('color', 'red')
        } else {
            $('label[for="facades_models_top-selectized"]').css('color', '#333')
        }

        if( bottom_as_top_facade_models == 0){
            if($('#facades_models_bottom').val() === ''){
                errors.push('Модель фасада нижних модулей не выбрана');
                $('label[for="facades_models_bottom-selectized"]').css('color', 'red')
            } else {
                $('label[for="facades_models_bottom-selectized"]').css('color', '#333')
            }
        }

        if($('#facades_selected_materials_top').val() === ''){
            errors.push('Цвет фасада верхних модулей по умолчанию не выбран');
            $('label[for="facades_selected_materials_top-selectized"]').css('color', 'red')
        } else {
            $('label[for="facades_selected_materials_top-selectized"]').css('color', '#333')
        }

        if(bottom_as_top_facade_materials == 0 || bottom_as_top_facade_models == 0){
            if($('#facades_selected_materials_bottom').val() === ''){
                errors.push('Цвет фасада нижних модулей по умолчанию не выбрана');
                $('label[for="facades_selected_materials_bottom-selectized"]').css('color', 'red')
            } else {
                $('label[for="facades_selected_materials_bottom-selectized"]').css('color', '#333')
            }
        }


        if($('#corpus_materials_top').val() === null){
            errors.push('Доступные материалы корпуса не выбраны');
            $('label[for="corpus_materials_top-selectized"]').css('color', 'red')
        } else {
            $('label[for="corpus_materials_top-selectized"]').css('color', '#333')
        }

        if($('#selected_corpus_material_top').val() === ''){
            errors.push('Цвет корпуса верхних модулей по умолчанию не выбран');
            $('label[for="selected_corpus_material_top-selectized"]').css('color', 'red')
        } else {
            $('label[for="selected_corpus_material_top-selectized"]').css('color', '#333')
        }

        if($('#bottom_as_top_corpus_materials').val() == 0){
            if($('#selected_corpus_material_bottom').val() === ''){
                errors.push('Цвет корпуса нижних модулей по умолчанию не выбран');
                $('label[for="selected_corpus_material_bottom-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_corpus_material_bottom-selectized"]').css('color', '#333')
            }
        }

        if($('#cokol_materials').val() === null){
            errors.push('Доступные материалы цоколя не выбраны');
            $('label[for="cokol_materials-selectized"]').css('color', 'red')
        } else {
            $('label[for="cokol_materials-selectized"]').css('color', '#333')
        }

        if($('#cokol_as_corpus').val() == 0){
            if($('#selected_cokol_material').val() === ''){
                errors.push('Цвет цоколя по умолчанию не выбран');
                $('label[for="selected_cokol_material-selectized"]').css('color', 'red')
            } else {
                $('label[for="selected_cokol_material-selectized"]').css('color', '#333')
            }
        }



        if($('#tabletop_materials').val() === null){
            errors.push('Доступные материалы столешницы не выбраны');
            $('label[for="tabletop_materials-selectized"]').css('color', 'red')
        } else {
            $('label[for="tabletop_materials-selectized"]').css('color', '#333')
        }

        if($('#selected_tabletop_materials').val() === ''){
            errors.push('Цвет столешницы по умолчанию не выбран');
            $('label[for="selected_tabletop_materials-selectized"]').css('color', 'red')
        } else {
            $('label[for="selected_tabletop_materials-selectized"]').css('color', '#333')
        }


        if($('#wallpanel_materials').val() === null){
            errors.push('Доступные материалы фартука не выбраны');
            $('label[for="wallpanel_materials-selectized"]').css('color', 'red')
        } else {
            $('label[for="wallpanel_materials-selectized"]').css('color', '#333')
        }

        if($('#selected_wallpanel_materials').val() === ''){
            errors.push('Фартук по умолчанию не выбран');
            $('label[for="selected_wallpanel_materials-selectized"]').css('color', 'red')
        } else {
            $('label[for="selected_wallpanel_materials-selectized"]').css('color', '#333')
        }


        if($('#no_handle').val()==0){
            if($('#handle_selected_model').val() === ''){
                errors.push('Модель ручки не выбрана');
                $('label[for="handle_selected_model-selectized"]').css('color', 'red')
            } else {
                $('label[for="handle_selected_model-selectized"]').css('color', '#333')
            }

            if($('#handle_preferable_size').val() === ''){
                errors.push('Предпочтительный размер ручки не выбран');
                $('label[for="handle_preferable_size-selectized"]').css('color', 'red')
            } else {
                $('label[for="handle_preferable_size-selectized"]').css('color', '#333')
            }
        }


        if(errors.length){
            var text = '';
            for(var i = 0; i < errors.length; i++){
                text += (i+1) + ' ' + errors[i] + '\n'

            }
            alert(text);
            return false;
        } else {
            return true;
        }

    })

});