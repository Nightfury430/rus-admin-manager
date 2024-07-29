
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="/common_assets/libs/bootstrap/bootstrap_w_theme.min.css">
    <link rel="stylesheet" href="/common_assets/libs/selectize/selectize.bootstrap3.css">
    <link rel="stylesheet" href="/common_assets/css/admin.css?<?php echo md5(date('m-d-Y-His A e'));?>">


    <script src="/common_assets/libs/jquery_1.12.5.js" type="text/javascript"></script>
    <script src="/common_assets/libs/selectize/selectize.min.js" type="text/javascript"></script>
    <script src="/common_assets/libs/bootstrap/bootstrap.min.js" type="text/javascript"></script>
    <script src="/common_assets/libs/lodash.js" type="text/javascript"></script>
    <script src="/common_assets/admin_js/production/modal.js" type="text/javascript"></script>

    <script src="/common_assets/libs/axios.min.js"></script>


</head>
<body>

<div class="common_loader">
    <?php echo $lang_arr['loading']?>
</div>

<?php if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false); ?>

<div class="hidden">
    <div id="delete_confirm_message"><?php echo $lang_arr['delete_confirm_message']?></div>
</div>


<div id="throbber" style="display:none; min-height:120px;"></div>
<div id="noty-holder"></div>
<div id="wrapper">

    <?php if($_SESSION['constructor'] == 'kitchen'):?>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img style="max-height: 30px;" src="/common_assets/images/bplanner_logo2.png" alt="">
                </a>
            </div>
            <ul class="nav navbar-left top-nav">
	            <?php if ($this->config->item('sub_account') == false): ?>
                <li>
                    <select style="max-width: 220px;margin-top: 4px;" class="form-control" id="constructor_change">
                        <option <?php if($_SESSION['constructor'] == 'kitchen') echo 'selected';?> value="kitchen"><?php echo $lang_arr['kitchen_constructor']?></option>
                        <option <?php if($_SESSION['constructor'] == 'coupe') echo 'selected';?> value="coupe"><?php echo $lang_arr['coupe_constructor']?></option>
                    </select>
                </li>
	            <?php endif;?>
                <li>
                    <a target="_blank" href="https://bplanner.me/ceny/">
	                    <?php echo $lang_arr['renew_license']?>
                    </a>
                </li>
                <!--            <li>-->
                <!--                <a target="_blank" href="https://bplanner.me/wp-content/uploads/2018/09/rukovodstvo-polzovatelja-bplanner.pdf">-->
                <!--                    Скачать руководство пользователя-->
                <!--                </a>-->
                <!--            </li>-->
			    <?php if ($this->config->item('sub_account') == false): ?>
                    <!--            <li>-->
                    <!--                <a target="_blank" href="--><?php //echo $this->config->item('const_path') .'data/db' ?><!--">-->
                    <!--                    Скачать бэкап БД-->
                    <!--                </a>-->
                    <!--            </li>-->
			    <?php endif;?>
                <li>
                    <a id="clear_settings" href="#">
	                    <?php echo $lang_arr['clear_settings']?>
                    </a>
                </li>

                <li>
                    <a target="_blank" href="https://bplanner.me/ru/support/">
	                    <?php echo $lang_arr['tech_support']?>
                    </a>
                </li>

            </ul>
            <ul class="nav navbar-right top-nav">
                <li>
                    <a id="go_to_constructor" target="_blank" href="<?php echo $this->config->item('const_path') ?>" >
	                    <?php echo $lang_arr['go_to_constructor']?>
                    </a>
                </li>
                <li>
<!--                    <a href="#" id="save_settings" style="background: #4fa950;">Выгрузить настройки</a>-->
                    <a href="#" id="save_settings" style="background: #4fa950;"><?php echo $lang_arr['export_settings']?></a>
                </li>

                <li style="margin-left: 50px">
                    <a id="logout" href="<?php echo site_url('login/logout/') ?>"><?php echo $lang_arr['logout']?></a>
                </li>

            </ul>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav" id="left_menu">
                    <li>
                        <a href="<?php echo site_url('clients_orders/index') ?>"><?php echo $lang_arr['clients_orders_label'] ?></a>
                    </li>

                    <li>
                        <a href="<?php echo site_url('settings/') ?>"><?php echo $lang_arr['kitchen_account_settings_label'] ?></a>
                    </li>

                    <li>
                        <a href="<?php echo site_url('constructor/') ?>"><?php echo $lang_arr['kitchen_constructor_settings_label'] ?> </a>
                    </li>

				    <?php if ($this->config->item('sub_account') == false): ?>

                        <li>
                            <a href="#" ><?php echo $lang_arr['kitchen_modules_label'] ?></a>
                            <ul>
                                <li><a href="<?php echo site_url('modules/categories_index/') ?>"><?php echo $lang_arr['kitchen_modules_categories_label'] ?></a></li>
                                <li><a href="<?php echo site_url('modules/items_index/1/') ?>"><?php echo $lang_arr['kitchen_bottom_modules'] ?></a></li>
                                <li><a href="<?php echo site_url('modules/items_index/2/') ?>"><?php echo $lang_arr['kitchen_top_modules'] ?></a></li>
                                <li><a href="<?php echo site_url('modules/items_index/3/') ?>"><?php echo $lang_arr['kitchen_penals_modules'] ?></a></li>
                                <li><a href="<?php echo site_url('modules/not_active/1') ?>"><?php echo $lang_arr['kitchen_inactive_bottom_modules'] ?></a></li>
                                <li><a href="<?php echo site_url('modules/not_active/2') ?>"><?php echo $lang_arr['kitchen_inactive_top_modules'] ?></a></li>
                                <li><a href="<?php echo site_url('modules/not_active/3') ?>"><?php echo $lang_arr['kitchen_inactive_penals_modules'] ?></a></li>
                                <li>
								    <?php if(isset($_SESSION['selected_modules_templates_category'])):?>
                                        <a href="<?php echo site_url('modules_templates/index/'.$_SESSION['selected_modules_templates_category'].'/'.$_SESSION['selected_modules_templates_per_page'].'/'.$_SESSION['selected_modules_templates_pagination']) ?>"><?php echo $lang_arr['kitchen_modules_configurator'] ?></a>
								    <?php else:?>
                                        <a href="<?php echo site_url('modules_templates/index') ?>"><?php echo $lang_arr['kitchen_modules_configurator'] ?></a>
								    <?php endif;?>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" ><?php echo $lang_arr['kitchen_facades_label'] ?></a>
                            <ul >
                                <li><a href="<?php echo site_url('facades/categories_index/') ?>"><?php echo $lang_arr['kitchen_facades_categories'] ?></a></li>
                                <li><a href="<?php echo site_url('facades/items_index/') ?>"><?php echo $lang_arr['kitchen_facades_items'] ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" ><?php echo $lang_arr['kitchen_handles_label'] ?></a>
                            <ul>
                                <li><a href="<?php echo site_url('handles/categories_index/') ?>"><?php echo $lang_arr['kitchen_handles_categories_label'] ?></a></li>
                                <li><a href="<?php echo site_url('handles/items_index/') ?>"><?php echo $lang_arr['kitchen_handles_items'] ?></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" ><?php echo $lang_arr['kitchen_materials_label'] ?></a>
                            <ul >
                                <li><a href="<?php echo site_url('materials/categories_index/') ?>"><?php echo $lang_arr['kitchen_materials_categories_label'] ?></a></li>
							    <?php if(isset($_SESSION['selected_items_category'])):?>
                                    <li>
                                        <a href="<?php echo site_url('materials/items_index/'.$_SESSION['selected_items_category'].'/'.$_SESSION['selected_items_per_page'].'/'.$_SESSION['selected_items_pagination']) ?>">
	                                        <?php echo $lang_arr['kitchen_materials_items_label'] ?>
                                        </a>
                                    </li>
							    <?php else:?>
                                    <li><a href="<?php echo site_url('materials/items_index/') ?>"><?php echo $lang_arr['kitchen_materials_items_label'] ?></a></li>
							    <?php endif;?>
                                <li>
                                    <a href="<?php echo site_url('project_settings/') ?>"><?php echo $lang_arr['kitchen_project_settings_label'] ?></a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" ><?php echo $lang_arr['kitchen_glass_label'] ?> </a>
                            <ul >
                                <li><a href="<?php echo site_url('glass/categories_index/') ?>"><?php echo $lang_arr['kitchen_glass_categories_label'] ?> </a></li>

							    <?php if(isset($_SESSION['selected_glass_category'])):?>
                                    <li>
                                        <a href="<?php echo site_url('glass/items_index/'.$_SESSION['selected_glass_category'].'/'.$_SESSION['selected_glass_per_page'].'/'.$_SESSION['selected_glass_pagination']) ?>">
	                                        <?php echo $lang_arr['kitchen_glass_items_label'] ?>
                                        </a>
                                    </li>
							    <?php else:?>
                                    <li><a href="<?php echo site_url('glass/items_index/') ?>"><?php echo $lang_arr['kitchen_glass_items_label'] ?> </a></li>
							    <?php endif;?>

                            </ul>
                        </li>

                        <li>
                            <a href="<?php echo site_url('prices/') ?>"><?php echo $lang_arr['kitchen_prices_materials_label'] ?></a>
                        </li>

                        <li>
                            <a href="#" ><?php echo $lang_arr['tech_models_label'] ?></a>
                            <ul>
                                <li><a href="<?php echo site_url('tech/categories_index/') ?>"><?php echo $lang_arr['tech_categories_label'] ?></a></li>
                                <li><a href="<?php echo site_url('tech/items_index/') ?>"><?php echo $lang_arr['tech_items_label'] ?></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" ><?php echo $lang_arr['interior_models_label'] ?></a>
                            <ul>
                                <li><a href="<?php echo site_url('interior/categories_index/') ?>"><?php echo $lang_arr['interiors_categories_label'] ?></a></li>
                                <li><a href="<?php echo site_url('interior/items_index/') ?>"><?php echo $lang_arr['interiors_list_label'] ?></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" ><?php echo $lang_arr['comms_models_label'] ?></a>
                            <ul>
                                <li><a href="<?php echo site_url('comms/categories_index/') ?>"><?php echo $lang_arr['comms_categories_label'] ?></a></li>
                                <li><a href="<?php echo site_url('comms/items_index/') ?>"><?php echo $lang_arr['comms_list_label'] ?></a></li>
                            </ul>
                        </li>

<!--                        <li>-->
<!--                            <a href="#" >Фурнитура</a>-->
<!--                            <ul>-->
<!--                                <li><a href="--><?php //echo site_url('furniture/categories_index/') ?><!--">Категории фурнитуры</a></li>-->
<!--                                <li><a href="--><?php //echo site_url('furniture/items_index/') ?><!--">Модели фурнитуры</a></li>-->
<!--                                <li><a href="--><?php //echo site_url('furniture/sets_index/') ?><!--">Варианты фурнитуры</a></li>-->
<!--                            </ul>-->
<!--                        </li>-->



                        <li>
                            <a href="#"><?php echo $lang_arr['kitchen_dealer_mode'] ?></a>
                            <ul>
                                <li><a href="<?php echo site_url('kitchen_models/index/') ?>"><?php echo $lang_arr['kitchen_models'] ?></a></li>
                                <li><a href="<?php echo site_url('module_sets/sets_index/') ?>"><?php echo $lang_arr['kitchen_modules_sets_label'] ?></a></li>
                            </ul>
                        </li>

                        <!--                <li>-->
                        <!--                    <a href="#" >Фурнитура </a>-->
                        <!--                    <ul>-->
                        <!--                        <li><a href="--><?php //echo site_url('furniture/categories_index/') ?><!--">Категории фурнитуры</a></li>-->
                        <!--                        <li><a href="--><?php //echo site_url('furniture/items_index/') ?><!--">Фурнитура</a></li>-->
                        <!--                    </ul>-->
                        <!--                </li>-->

                        <li>
                            <a href="<?php echo site_url('templates/') ?>"><?php echo $lang_arr['kitchen_templates'] ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('accessories/') ?>"><?php echo $lang_arr['accessories_label'] ?></a>
                        </li>

				    <?php endif;?>

                    <li>
                        <a href="<?php echo site_url('sync') ?>" ><?php echo $lang_arr['api'] ?></a>
                    </li>

                    <li>
                        <a href="<?php echo site_url('custom_scripts/') ?>"><?php echo $lang_arr['custom_code'] ?></a>
                    </li>

                    <li>
                        <a href="<?php echo site_url('languages/') ?>"><?php echo $lang_arr['languages'] ?></a>
                    </li>


                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

    <?php else:?>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img style="max-height: 30px;" src="/common_assets/images/bplanner_logo2.png" alt="">
                </a>
            </div>
            <ul class="nav navbar-left top-nav">

                <li>
                    <select style="max-width: 220px;margin-top: 4px;" class="form-control" id="constructor_change">
                        <option <?php if($_SESSION['constructor'] == 'kitchen') echo 'selected';?> value="kitchen"><?php echo $lang_arr['kitchen_constructor']?></option>
                        <option <?php if($_SESSION['constructor'] == 'coupe') echo 'selected';?> value="coupe"><?php echo $lang_arr['coupe_constructor']?></option>
                    </select>
                </li>

                <li>
                    <a target="_blank" href="https://bplanner.me/ceny/">
	                    <?php echo $lang_arr['renew_license']?>
                    </a>
                </li>

<!--                <li>-->
<!--                    <a id="clear_settings" href="#">-->
<!--                        Сброс и обновление-->
<!--                    </a>-->
<!--                </li>-->

                <li>
                    <a target="_blank" href="https://bplanner.me/ru/support/">
	                    <?php echo $lang_arr['tech_support']?>
                    </a>
                </li>

            </ul>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li>
                    <a id="go_to_constructor" target="_blank" href="<?php echo $this->config->item('const_path') . 'config/index.php/main_coupe' ?>" ><?php echo $lang_arr['go_to_constructor']?></a>
                </li>
                <li>
                    <a href="#" id="save_coupe" style="background: #4fa950;"><?php echo $lang_arr['export_settings']?></a>
                </li>

                <li style="margin-left: 50px">
                    <a id="logout" href="<?php echo site_url('login/logout/') ?>"><?php echo $lang_arr['logout']?></a>
                </li>

            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav" id="left_menu">

                    <li>
                        <a href="<?php echo site_url('settings/coupe_account_settings_index') ?>"><?php echo $lang_arr['coupe_account_settings_label']?></a>
                    </li>


				    <?php if ($this->config->item('sub_account') == false): ?>

                        <li>
                            <a href="#" ><?php echo $lang_arr['kitchen_materials_label']?></a>
                            <ul  >
                                <li><a href="<?php echo site_url('materials/coupe_categories_index/') ?>"><?php echo $lang_arr['kitchen_materials_categories_label']?> </a></li>
							    <?php if(isset($_SESSION['coupe_selected_materials_category'])):?>
                                    <li>
                                        <a href="<?php echo site_url('materials/coupe_materials_items_index/'.$_SESSION['coupe_selected_materials_category'].'/'.$_SESSION['coupe_selected_materials_per_page'].'/'.$_SESSION['coupe_selected_materials_pagination']) ?>">
	                                        <?php echo $lang_arr['kitchen_materials_items_label']?>
                                        </a>
                                    </li>
							    <?php else:?>
                                    <li><a href="<?php echo site_url('materials/coupe_materials_items_index/') ?>"><?php echo $lang_arr['kitchen_materials_items_label']?> </a></li>
							    <?php endif;?>
                                <li>
                                    <a href="<?php echo site_url('project_settings/coupe_project_settings_index') ?>"><?php echo $lang_arr['kitchen_project_settings_label']?></a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="<?php echo site_url('templates/index_coupe') ?>"><?php echo $lang_arr['kitchen_templates']?></a>
                        </li>

				    <?php endif;?>

<!--                    <li>-->
<!--                        <a href="--><?php //echo site_url('sync') ?><!--" >API</a>-->
<!--                    </li>-->
<!---->
<!--                    <li>-->
<!--                        <a href="--><?php //echo site_url('custom_scripts/') ?><!--">js и css</a>-->
<!--                    </li>-->



                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

    <?php endif;?>



    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row" id="main" >





<style>
    /*@import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');*/
    @media(min-width:768px) {
        body {
            margin-top: 50px;
        }
        /*html, body, #wrapper, #page-wrapper {height: 100%; overflow: hidden;}*/
    }

    #wrapper {
        padding-left: 0;
    }

    #page-wrapper {
        width: 100%;
        padding: 0;
        background-color: #fff;
    }

    @media(min-width:768px) {
        #wrapper {
            padding-left: 255px;
        }

        #page-wrapper {
            padding: 22px 10px;
        }
    }

    /* Top Navigation */

    .top-nav {
        padding: 0 15px;
    }

    .top-nav>li {
        display: inline-block;
        float: left;
    }

    .top-nav>li>a, .top-nav>li>span{
        padding-top: 10px;
        padding-bottom: 10px;
        line-height: 20px;
        color: #fff;
        font-size: 13px;
    }


    .top-nav>li>a:hover,
    .top-nav>li>a:focus,
    .top-nav>.open>a,
    .top-nav>.open>a:hover,
    .top-nav>.open>a:focus {
        color: #fff;
        background-color: #1a242f;
    }

    .top-nav>.open>.dropdown-menu {
        float: left;
        position: absolute;
        margin-top: 0;
        /*border: 1px solid rgba(0,0,0,.15);*/
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        background-color: #fff;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }

    .top-nav>.open>.dropdown-menu>li>a {
        white-space: normal;
    }

    .navbar-brand {
        float: left;
        height: 40px;
        padding: 15px 15px;
        font-size: 18px;
        line-height: 20px;
    }

    /* Side Navigation */

    @media(min-width:768px) {



        .side-nav {
            position: fixed;
            top: 40px;
            left: 225px;
            width: 210px;
            margin-left: -225px;
            border: none;
            border-radius: 0;
            border-top: 1px rgba(0,0,0,.5) solid;
            overflow-y: auto;
            background-color: #222;
            /* background-color: #5A6B7D; */
            bottom: 0;
            overflow-x: hidden;
            padding-bottom: 40px;
        }

        .side-nav>li>a {
            width: 225px;
            /*border-bottom: 1px rgba(0,0,0,.3) solid;*/
            border-top: 1px solid #9d9d9d;
        }

        .side-nav li a:hover,
        .side-nav li a:focus, .side-nav li a.active {
            outline: none;
            background-color: #424242 !important;
        }


        #left_menu{
            width: 255px;
        }



    }

    .side-nav>li>ul {
        padding: 0;
        border-bottom: 1px rgba(0,0,0,.3) solid;
    }

    .side-nav>li>ul>li>a {
        display: block;
        padding: 5px 15px 5px 38px;
        text-decoration: none;
        /* color: #999; */
        color: #fff;
        font-size: 13px;
    }

    .side-nav>li>ul>li>a:hover {
        color: #fff;
    }

    .navbar .nav > li > a > .label {
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        position: absolute;
        top: 14px;
        right: 6px;
        font-size: 10px;
        font-weight: normal;
        min-width: 15px;
        min-height: 15px;
        line-height: 1.0em;
        text-align: center;
        padding: 2px;
    }

    .navbar .nav > li > a:hover > .label {
        top: 10px;
    }

    .navbar-brand {
        padding: 5px 15px;
    }

    .navbar-nav > li > a {padding-top:5px !important; padding-bottom:5px !important;font-size: 13px;}
    .navbar {min-height:40px !important}


</style>

<script>

    $(document).ready(function () {

        var links = $('.side-nav a');
        links.each(function () {
            if(window.location.href.indexOf($(this).attr('href')) > -1 ) $(this).addClass('active')
        });


        $('#clear_settings').click(function (e) {
            e.preventDefault();

            var modal = new Modal_window({
                heading: 'Возврат к изначальным настройкам',
                body: '<p>Внимание, если Вы подтвердите возврат к изначальным настройкам, то все настройки конструктора будут идентичны демо версии.</p>' +
                '<p style="color:#962d2d">Следующие данные будут безвозвратно утеряны:</p> ' +
                '<ul style="color:#962d2d">' +
                '<li>Все старые проекты не будут загружаться, или будут загружаться с ошибками</li>' +
                '<li>Все данные о заявках</li>' +
                '<li>Все загруженные и переименованные материалы и категории</li>' +
                '<li>Все загруженные и переименованные модели и категории</li>' +
                '<li>Все отредактированные модули</li>' +
                '<li>Все проекты кухонь из раздела "Проекты"</li>' +
                '<li>Все модели кухни и отредактированные наборы модулей</li>' +
                '</ul>' +
                '<p style="color:#4c8613">Следующие данные останутся неизмененными:</p>' +
                '<ul style="color:#4c8613">' +
                '<li>Почта для получения заявок</li>' +
                '<li>Сайт установки</li>' +
                '<li>Логотип</li>' +
                '<li>Язык конструктора</li>' +
                '<li>ID приложения Вконтакте</li>' +
                '<li>Все пользовательские html, js, css</li>' +
                '</ul>' +
                '<div style="text-align: center"><p style="font-weight: bold;">Для подтверждения действия введите пароль от аккаунта</p><div><input id="modal_pwd" type="password"><br><small id="wr_m_p">Неверный пароль</small></div></div>',
                max_width: 600,
                class: 'yesno',
                additional_classes: 'bp_warning'
            });

            modal.show();

            modal.yes_button.click(function () {

                if($('#modal_pwd').val() !== ''){
                    $.ajax({
                        type: 'post',
                        data: {password: $('#modal_pwd').val()},
                        url: "<?php echo base_url('index.php/sync/reset_settings')?>",
                        success: function (msg) {

                            if(msg == 'wp'){
                                $('#wr_m_p').show();
                            } else {

                                console.log(msg);

                                $('#wr_m_p').hide();
                                modal.close();

                                var modal2 = new Modal_window({
                                    heading: 'Готово ',
                                    body: '<p>Настройки изменены на изначальные</p>',
                                });
                                modal2.show();

                            }
                        }
                    })
                } else {
                    $('#wr_m_p').show();
                }


            });

            modal.no_button.click(function () {
                modal.close();
            });



        });


        $('#constructor_change').change(function () {
            // console.log($(this).val())

            $.ajax({
                url: "<?php echo base_url('index.php/login/change_constructor')?>",
                data: {constructor:$(this).val()},
                type: 'post'
            }).success(function(msg) {
                location.href = msg;
            });

        });

        $('#save_settings').click(function (e) {
            e.preventDefault();

            if (confirm('Сохранить все настройки?')) {
                $.ajax({
                    url: "<?php echo base_url('index.php/save_data')?>",
                    data: {save:'save'},
                    type: 'post'
                }).success(function(msg) {
                    console.log(msg)
                    var obj = JSON.parse( msg );

                    if(obj.errors){

                        var errs = $('<div></div>');
                        var ul = $('<ul></ul>');
                        for (let i = 0; i < obj.errors.length; i++){
                            ul.append('<li>'+ obj.errors[i] +'</li>')
                        }

                        errs.append(ul)

                        var modal = new Modal_window({
                            heading: 'Ошибки настройки',
                            body: errs,
                            max_width: 600,
                            additional_classes: 'bp_warning'
                        });

                        modal.show();

                        return false;
                    }

                    if(obj.success){
                        var modal = new Modal_window({
                            heading: 'Настройки сохранены',
                            body: obj.success,
                            max_width: 600,
                        });

                        modal.show();

                        return false;
                    }

                    // console.log(obj)
                    // var text = '';
                    // for( var i = 0; i < obj.length; i++ ){
                    //     text += obj[i];
                    //     text += '\n';
                    // }
                    // alert(text)
                });
            } else {

            }
        });

        $('#logout').click(function (e) {
            e.preventDefault();
            var scope = $(this);
            if (confirm('Сохранить настройки?')) {
                $.ajax({
                    url: "<?php echo base_url('index.php/save_data')?>",
                    data: {save:'save'},
                    type: 'post'
                }).success(function(msg) {
                    var obj = JSON.parse( msg );
                    var text = '';
                    for( var i = 0; i < obj.length; i++ ){
                        text += obj[i];
                        text += '\n';
                    }
                    alert(text)
                    window.location.href = scope.attr('href');
                });
            } else {
                window.location.href = scope.attr('href');
            }
        });

        $('#go_to_constructor').click(function (e) {
            e.preventDefault();
            var scope = $(this);
            //if (confirm('Сохранить настройки?')) {
            //    $.ajax({
            //        url: "<?php //echo base_url('index.php/save_data')?>//",
            //        data: {save:'save'},
            //        type: 'post'
            //    }).success(function(msg) {
            //        var obj = JSON.parse( msg );
            //        var text = '';
            //        for( var i = 0; i < obj.length; i++ ){
            //            text += obj[i];
            //            text += '\n';
            //        }
            //        alert(text)
            //        window.open( scope.attr('href'),'_blank');
            //    });
            //} else {
                window.open( scope.attr('href'),'_blank');
            // }
        });


        $('#save_coupe').click(function (e) {
            e.preventDefault();
            let formData = new FormData();
            formData.append('save_coupe', 1)

            axios.post(
                "<?php echo base_url( 'index.php/save_data/save_coupe' )?>",
                formData,
                {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                }
            ).then(function (msg) {

               console.log(msg.data)

                var modal = new Modal_window({
                    heading: 'Настройки сохранены',
                    body: msg.data,
                    max_width: 600,
                });

                modal.show();

                return false;


            }).catch(function () {
                alert('Unknown error')
            });




        })


    })

    function get_file_extension(fname) {
        return fname.slice((Math.max(0, fname.lastIndexOf(".")) || Infinity) + 1);
    }




    // $(function(){
    //     $('[data-toggle="tooltip"]').tooltip();
    //     $(".side-nav .collapse").on("hide.bs.collapse", function() {
    //         $(this).prev().find(".fa").eq(1).removeClass("fa-angle-right").addClass("fa-angle-down");
    //     });
    //     $('.side-nav .collapse').on("show.bs.collapse", function() {
    //         $(this).prev().find(".fa").eq(1).removeClass("fa-angle-down").addClass("fa-angle-right");
    //     });
    // })
</script>