<div class="col-sm-12 col-md-12" id="content">
    <h3>Добавить фасад</h3>

    <form class="" method="post" accept-charset="UTF-8" action="<?php echo site_url('cities/add/') ?>">


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Основные параметры</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" name="name" placeholder="Название">
                </div>

                <div class="form-group">
                    <label for="category">Категория</label>
                    <select class="form-control" name="category" id="category">
                        <option selected value="0">МДФ</option>
                        <option value="1">Пластик</option>
                        <option value="2">ДСП</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="icon">Иконка</label>
                    <input type="file" name="icon" id="icon">
                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Модели</h4>
            </div>
            <div class="panel-body models_list">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="full">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_full">
                                Глухой фасад
                            </a>
                        </div>
                        <div id="collapse_full" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-primary add_model_button">Добавить модель</button>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Выберите файл</label>
                                                    <input type="file" class="test_1" id="exampleInputFile">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-sm btn-primary test_file">Тест</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="min_height">Минимальная высота</label>
                                                    <input type="text" class="form-control" name="min_height">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="min_width">Минимальная ширина</label>
                                                    <input type="text" class="form-control" name="min_width">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Выберите файл</label>
                                            <input type="file" id="exampleInputFile">
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="min_height">Минимальная высота</label>
                                                    <input type="text" class="form-control" name="min_height">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="min_width">Минимальная ширина</label>
                                                    <input type="text" class="form-control" name="min_width">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Выберите файл</label>
                                            <input type="file" id="exampleInputFile">
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="min_height">Минимальная высота</label>
                                                    <input type="text" class="form-control" name="min_height">
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="min_width">Минимальная ширина</label>
                                                    <input type="text" class="form-control" name="min_width">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="window">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_window">
                                Витрина
                            </a>
                        </div>
                        <div id="collapse_window" class="panel-collapse collapse">
                            <div class="panel-body">
                                <button type="button" class="btn btn-sm btn-primary add_model_button">Добавить модель</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="frame">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_frame">
                                Решетка
                            </a>
                        </div>
                        <div id="collapse_frame" class="panel-collapse collapse">
                            <div class="panel-body">
                                <button type="button" class="btn btn-sm btn-primary add_model_button">Добавить модель</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="radius_full">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_radius_full">
                                Радиус глухой
                            </a>
                        </div>
                        <div id="collapse_radius_full" class="panel-collapse collapse">
                            <div class="panel-body">
                                <button type="button" class="btn btn-sm btn-primary add_model_button">Добавить модель</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="radius_window">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_radius_window">
                                Радиус витрина
                            </a>
                        </div>
                        <div id="collapse_radius_window" class="panel-collapse collapse">
                            <div class="panel-body">
                                <button type="button" class="btn btn-sm btn-primary add_model_button">Добавить модель</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="radius_frame">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                               href="#collapse_radius_frame">
                                Радиус решетка
                            </a>
                        </div>
                        <div id="collapse_radius_frame" class="panel-collapse collapse">
                            <div class="panel-body">
                                <button type="button" class="btn btn-sm btn-primary add_model_button">Добавить модель</button>
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">Доступные материалы</div>
            <div class="panel-body materials_list">
                <button type="button" class="btn btn-primary add_material_button">Добавить материал</button>
            </div>
        </div>


        <input class="btn btn-success" type="submit" name="submit" value="Добавить"/>
        <a class="btn btn-danger" href="<?php echo site_url('cities/index/') ?>">Отмена</a>
    </form>
</div>

<div class="three_modal_wrapper">
    <div class="three_modal">
        <span class="close_three_modal glyphicon glyphicon-remove"></span>
        <div id="three_viewport">
        </div>
    </div>
</div>

<style>
    .three_modal_wrapper{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(31, 31, 31, 0.81);
        z-index: 9999;
        display: none;
    }

    .three_modal{
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        background: #ffffff;
    }

    .close_three_modal{
        position: absolute;
        top:10px;
        right:10px;
        cursor: pointer;
    }

    #three_viewport{
        width: 100%;
        height: 100%;
    }
</style>

<script>
    $(document).ready(function () {

        var models_list = $('.models_list');
        var models_count = 0;

        $('.add_model_button').click(function () {
            var panel = $('<div class="panel panel-default"></div>');
            var panel_body = $('<div class="panel-body"></div>');
            panel.append(panel_body);
            panel_body.append();
            // models_list.append();
        });

        $('.test_1').change(function (e) {

            var files = e.currentTarget.files;
            var dae_path;

            console.log(files);
        });

        $('.test_file').click(function () {
            console.log($('.test_1')[0].files[0])
        })
    });
</script>