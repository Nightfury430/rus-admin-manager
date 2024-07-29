<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['js_css_heading']?></h2>
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a href="index.html">Home</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a>Tables</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item active">-->
        <!--                <strong>Code Editor</strong>-->
        <!--            </li>-->
        <!--        </ol>-->
    </div>
    <div class="col-lg-2">

    </div>
</div>


<?php

$action = site_url('custom_scripts/save_data');

if(isset($coupe)){
    $action = site_url('custom_scripts/save_data_coupe');
}
?>


<form class="" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo $action ?>">

    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-toggle="tab" href="#tab-1"><?php echo $lang_arr['custom_html']?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-2"><?php echo $lang_arr['custom_js']?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-3"><?php echo $lang_arr['custom_css']?></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-4">JS Админ-панели</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <textarea name="custom_html" id="custom_html" class="form-control"  rows="50"><?php echo htmlspecialchars($custom_html) ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">


                                <div class="form-group row">
                                    <div class="col-12">
                                        <textarea  name="custom_js" id="custom_js" class="form-control"  rows="200"><?php echo $custom_js?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="alert alert-info" role="alert">
                                            <p>Callback функции javascript:</p>
                                            <p>В разделе “js и css” админ панели вы можете прописать свои функции, выполняющиеся в определенные моменты работы конструктора.</p>
                                            <ul>
                                                <li>interface_init_callback() – Выполняется после полной инициализации интерфейса.</li>
                                                <li>custom_order_callback(msg) – Выполняется после получения ответа от своего url. Параметр msg – ответ вашего сервера.</li>
                                            </ul>

                                            <p>Сторонние библиотеки, доступные в конструкторе:</p>
                                            <ul>
                                                <li>jQuery</li>
                                                <li>Lodash</li>
                                            </ul>

                                            <p>Т.е. например, вам нужно поменять надпись на кнопке “Отправить на расчет” на “Рассчитать кухню” для этого в разделе “js и css” админ панели в поле “js” определяем функцию interface_init_callback():</p>

                                            <code>
                                                function interface_init_callback(){ <br>
                                                &nbsp;&nbsp;&nbsp;$('#show_order_modal').html('Рассчитать кухню') <br>
                                                } <br>
                                            </code>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div id="tab-3" class="tab-pane">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <textarea name="custom_css" id="custom_css" class="form-control"  rows="50"><?php echo $custom_css?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tab-4" class="tab-pane">
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <textarea  name="custom_js_admin" id="custom_js_admin" class="form-control"  rows="200"><?php echo $custom_js_admin?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="ibox ">
                    <div class="ibox-content">

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<link href="/common_assets/theme/css/plugins/codemirror/codemirror.css" rel="stylesheet">
<link href="/common_assets/theme/css/plugins/codemirror/ambiance.css" rel="stylesheet">

<!-- CodeMirror -->
<script src="/common_assets/theme/js/plugins/codemirror/codemirror.js"></script>
<script src="/common_assets/theme/js/plugins/codemirror/mode/javascript/javascript.js"></script>
<script src="/common_assets/theme/js/plugins/codemirror/mode/css/css.js"></script>
<script src="/common_assets/theme/js/plugins/codemirror/mode/xml/xml.js"></script>
<script src="/common_assets/theme/js/plugins/codemirror/mode/htmlmixed/htmlmixed.js"></script>


<script>



    $(document).ready(function(){

        var editor_html = CodeMirror.fromTextArea(document.getElementById("custom_html"), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            smartIndent: true,
            mode:  "htmlmixed"
        });

        var editor_one = CodeMirror.fromTextArea(document.getElementById("custom_css"), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            smartIndent: true,
            mode:  "css"
        });

        var editor_two = CodeMirror.fromTextArea(document.getElementById("custom_js"), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            smartIndent: true,
            mode:  "javascript",
            lint: {options: {esversion: 2021}},
        });


        var editor_3 = CodeMirror.fromTextArea(document.getElementById("custom_js_admin"), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            smartIndent: true,
            mode:  "javascript",
            lint: {options: {esversion: 2021}},
        });



        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {
            editor_html.refresh();
            editor_one.refresh();
            editor_two.refresh();
            editor_3.refresh();
        });

    });

</script>

<style>
    .CodeMirror {
        resize: vertical;
        overflow-y: auto !important;
        min-height: 720px;
    }
</style>