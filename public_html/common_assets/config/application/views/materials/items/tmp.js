function create_model_panel(id, name){
    var panel = $('<div class="panel panel-default panel-body model_panel"></div>');
    var remove_panel = $('<span class="glyphicon glyphicon-trash remove_panel"></span>');
    var row = $('<div class="row"></div>');

    var file_wrapper = $('<div class="form-group col-xs-12 col-sm-4"></div>');
    var file_label = $('<label for="model_'+ name +'_'+ id +'"><?php echo $lang_arr["choose_file"]?></label>');
    var file_input = $('<input type="file" name="model_'+ name +'_'+ id +'" id="model_'+ name +'_'+ id +'" accept=".fbx">');

    var min_width_wrapper = $('<div class="form-group col-xs-12 col-sm-4"></div>');
    var min_width_label = $('<label for="min_width_'+name+'_'+ id +'"><?php echo $lang_arr['min_width']?> (<?php echo $lang_arr['units']?>)</label>');
    var min_width_input = $('<input type="number" name="min_width_'+name+'_'+ id +'" id="min_width_'+name+'_'+ id +'"  value="0" class="form-control">');

    var min_height_wrapper = $('<div class="form-group col-xs-12 col-sm-4"></div>');
    var min_height_label = $('<label for="min_height_'+name+'_'+ id +'"><?php echo $lang_arr['min_height']?> (<?php echo $lang_arr['units']?>)</label>');
    var min_height_input = $('<input type="number" name="min_height_'+name+'_'+ id +'" id="min_height_'+name+'_'+ id +'"  value="0" class="form-control">');

    var test_wrapper = $('<div class="form-group col-xs-2"></div>');
    var test_label = $('<label class="l_hack">1</label>');
    var test_button = $('<button type="button" class="btn btn-sm btn-primary btn-block test_model" data-target="model_'+ name +'_'+ id +'">Тест модели</button>')


    panel.append(remove_panel);
    panel.append(row);

    row.append(file_wrapper);
    row.append(min_width_wrapper);
    row.append(min_height_wrapper);
    row.append(test_wrapper);

    file_wrapper.append(file_label);
    file_wrapper.append(file_input);

    min_width_wrapper.append(min_width_label);
    min_width_wrapper.append(min_width_input);

    min_height_wrapper.append(min_height_label);
    min_height_wrapper.append(min_height_input);

    test_wrapper.append(test_label);
    test_wrapper.append(test_button);

    remove_panel.click(function () {
        panel.remove();
    });

    return panel;

}

a = {
    sizes:{

    }
};