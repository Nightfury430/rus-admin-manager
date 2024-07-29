<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php if ($this->config->item('const_path') == 'https://planplace.ru/clients/test/'): ?>
        <title>Личный кабинет PlanPlace (Тест)</title>
    <?php else: ?>
        <title>Личный кабинет PlanPlace</title>
    <?php endif; ?>

    <link rel="shortcut icon" href="/common_assets/images/favicon_admin.ico" type="image/x-icon">

    <link href="/common_assets/theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="/common_assets/theme/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="/common_assets/theme/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="/common_assets/theme/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">


    <!-- Sweet Alert -->
    <link href="/common_assets/theme/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <link rel="stylesheet" href="/common_assets/libs/selectize/selectize.bootstrap3.css">
    <link href="/common_assets/theme/css/plugins/switchery/switchery.css" rel="stylesheet">
    <link href="/common_assets/theme/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">

    <link href="/common_assets/theme/css/animate.css" rel="stylesheet">
    <link href="/common_assets/css/microtip.css" rel="stylesheet">

    <link href="/common_assets/theme/css/style.css?<?php echo md5(date('m-d-Y-His A e')); ?>" rel="stylesheet">

    <!--    <link href="/common_assets/theme/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">-->


    <script src="/common_assets/theme/js/jquery-3.1.1.min.js"></script>


</head>

<?php if (!$this->config->item('sub_account')) $this->config->set_item('sub_account', false); ?>


<body class="fixed-nav fixed-nav-basic">


<div id="wrapper" >
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header" style="height: 60px">
                    <div class="btn-group" style="width: 100%;">
                        <button data-constructor="kitchen" class="constructor_change btn <?php if ($_SESSION['constructor'] == 'kitchen') {
                            echo 'btn-primary';
                        } else {
                            echo 'btn-white';
                        } ?>" type="button" style="width: 50%;"><?php echo $lang_arr['kitchen'] ?></button>
                        <button data-constructor="coupe" class="constructor_change btn <?php if ($_SESSION['constructor'] == 'coupe') {
                            echo 'btn-primary';
                        } else {
                            echo 'btn-white';
                        } ?>" type="button" style="width: 50%;"><?php echo $lang_arr['coupe'] ?></button>
                    </div>
                </li>

                <!--                <li  class="nav-header">-->
                <!--                    <div class="form-group">-->
                <!--                        <select  class="form-control" id="constructor_change">-->
                <!--                            <option --><?php //if($_SESSION['constructor'] == 'kitchen') echo 'selected';?><!-- value="kitchen">--><?php //echo $lang_arr['kitchen_constructor']?><!--</option>-->
                <!--                            <option --><?php //if($_SESSION['constructor'] == 'coupe') echo 'selected';?><!-- value="coupe">--><?php //echo $lang_arr['coupe_constructor']?><!--</option>-->
                <!--                        </select>-->
                <!--                    </div>-->
                <!--                </li>-->

                <li class="<?php if (strpos($this->uri->uri_string(), 'clients_orders/index') !== false) echo 'active' ?>">
                    <a href="<?php echo site_url('clients_orders/index') ?>">
                        <i class="fa fa-address-book"></i>
                        <span class="nav-label"><?php echo $lang_arr['clients_orders_label'] ?></span>
                    </a>
                </li>
                <li class="<?php if (strpos($this->uri->uri_string(), 'settings') !== false && strpos($this->uri->uri_string(), 'project_settings') === false && strpos($this->uri->uri_string(), 'modules_settings') === false) echo 'active' ?>">
                    <a href="<?php echo site_url('settings/') ?>">
                        <i class="fa fa-code"></i>
                        <span class="nav-label"><?php echo $lang_arr['kitchen_account_settings_label'] ?></span>
                    </a>
                </li>
                <li class="<?php if (strpos($this->uri->uri_string(), 'constructor') !== false) echo 'active' ?>">
                    <a href="<?php echo site_url('constructor/') ?>">
                        <i class="fa fa-cogs"></i>
                        <span class="nav-label"><?php echo $lang_arr['kitchen_constructor_settings_label'] ?></span>
                    </a>
                </li>

                <?php if ($this->config->item('sub_account') == false && !$this->config->item('antar')): ?>
                    <li class="<?php if (strpos($this->uri->uri_string(), 'modules/') !== false ||
                        strpos($this->uri->uri_string(), 'modules_templates/') !== false ||
                        strpos($this->uri->uri_string(), 'catalog/items/modules') !== false ||
                        strpos($this->uri->uri_string(), 'catalog/categories/modules') !== false
                    ) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-archive"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_modules_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">

                            <!--                        <li class="--><?php //if (strpos($this->uri->uri_string(), 'modules/modules_settings') !== false) echo 'active'?><!--">-->
                            <!--                            <a href="--><?php //echo site_url('modules/modules_settings/') ?><!--">--><?php //echo $lang_arr['modules_settings_heading'] ?><!--</a>-->
                            <!--                        </li>-->

                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/modules') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/modules') ?>"><?php echo $lang_arr['kitchen_modules_categories_label'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/modules') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/modules') ?>"><?php echo $lang_arr['modules_list'] ?></a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'modules/not_active/1') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('modules/not_active/1') ?>"><?php echo $lang_arr['kitchen_inactive_bottom_modules'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'modules/not_active/2') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('modules/not_active/2') ?>"><?php echo $lang_arr['kitchen_inactive_top_modules'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'modules/not_active/3') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('modules/not_active/3') ?>"><?php echo $lang_arr['kitchen_inactive_penals_modules'] ?></a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'modules_templates/') !== false) echo 'active' ?>">
                                <?php if (isset($_SESSION['selected_modules_templates_category'])): ?>
                                    <a href="<?php echo site_url('modules_templates/index/' . $_SESSION['selected_modules_templates_category'] . '/' . $_SESSION['selected_modules_templates_per_page'] . '/' . $_SESSION['selected_modules_templates_pagination']) ?>"><?php echo $lang_arr['kitchen_modules_configurator'] ?></a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('modules_templates/index') ?>"><?php echo $lang_arr['kitchen_modules_configurator'] ?></a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </li>
                    <li class="<?php if (strpos($this->uri->uri_string(), 'facades') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-building"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_facades_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/facades') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/facades') ?>"><?php echo $lang_arr['kitchen_facades_categories'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/facades') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/facades') ?>"><?php echo $lang_arr['kitchen_facades_items'] ?></a>
                            </li>

                            <?php if (check_access_to_common_db()): ?>

                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/facades') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/categories_common/facades') ?>"><?php echo $lang_arr['kitchen_facades_categories'] ?> (Общая)</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/facades') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/items_common/facades') ?>"><?php echo $lang_arr['kitchen_facades_items'] ?> (Общая)</a>
                                </li>


                            <?php endif; ?>

                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'cornice') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-window-maximize"></i>
                            <span class="nav-label"><?php echo $lang_arr['cornice'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/cornice') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/cornice') ?>"><?php echo $lang_arr['cornice_categories'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/cornice') !== false || strpos($this->uri->uri_string(), 'catalog/item/cornice') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/cornice') ?>"><?php echo $lang_arr['cornice_items'] ?></a>
                            </li>

                            <?php if (check_access_to_common_db()): ?>

                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/cornice') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/categories_common/cornice') ?>"><?php echo $lang_arr['cornice_categories'] ?> (Общая)</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/cornice') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/items_common/cornice') ?>"><?php echo $lang_arr['cornice_items'] ?> (Общая)</a>
                                </li>


                            <?php endif; ?>

                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'tabletop') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-window-maximize"></i>
                            <span class="nav-label"><?php echo $lang_arr['tabletop'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/tabletop') !== false && strpos($this->uri->uri_string(), 'tabletop_plinth') === false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/tabletop') ?>"><?php echo $lang_arr['tabletop_categories'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/tabletop') !== false  && strpos($this->uri->uri_string(), 'tabletop_plinth') === false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/tabletop') ?>"><?php echo $lang_arr['tabletop_items'] ?></a>
                            </li>
                            <?php if (strpos($this->config->item('const_path'), 'zetta') !== false): ?>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/tabletop_plinth') !== false) echo 'active' ?>">
                                    <a href="<?php echo site_url('catalog/categories/tabletop_plinth') ?>">Категории плинтусов</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/tabletop_plinth') !== false) echo 'active' ?>">
                                    <a href="<?php echo site_url('catalog/items/tabletop_plinth') ?>">Плинтус</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <?php if (strpos($this->config->item('const_path'), 'zetta') !== false): ?>


                        <li class="<?php if (strpos($this->uri->uri_string(), 'cokol') !== false) echo 'active' ?>">
                            <a href="#">
                                <i class="fa fa-window-maximize"></i>
                                <span class="nav-label"><?php echo $lang_arr['cokol'] ?></span>
                                <span class="fa arrow"></span>
                            </a>
                            <ul class="nav nav-second-level collapse">
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/cokol') !== false) echo 'active' ?>">
                                    <a href="<?php echo site_url('catalog/categories/cokol') ?>"><?php echo $lang_arr['cokol_categories'] ?></a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/cokol') !== false || strpos($this->uri->uri_string(), 'catalog/item/cokol') !== false) echo 'active' ?>">
                                    <a href="<?php echo site_url('catalog/items/cokol') ?>"><?php echo $lang_arr['cokol_items'] ?></a>
                                </li>


                            </ul>
                        </li>
                    <?php endif; ?>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'handles/') !== false || strpos($this->uri->uri_string(), '/handles') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-hand-grab-o"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_handles_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/handles') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/handles') ?>"><?php echo $lang_arr['kitchen_handles_categories_label'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/handles') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/handles') ?>"><?php echo $lang_arr['kitchen_handles_items'] ?></a>
                            </li>

                            <?php if (check_access_to_common_db()): ?>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/handles') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/categories_common/handles') ?>"><?php echo $lang_arr['kitchen_handles_categories_label'] ?> (Общая)</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/handles') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/items_common/handles') ?>"><?php echo $lang_arr['kitchen_handles_items'] ?> (Общая)</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), '/materials') !== false || strpos($this->uri->uri_string(), 'project_settings') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-paint-brush"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_materials_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/materials') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/materials') ?>"><?php echo $lang_arr['kitchen_materials_categories_label'] ?></a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/materials') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/materials') ?>">
                                    <?php echo $lang_arr['kitchen_materials_items_label'] ?>
                                </a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'project_settings') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('project_settings/') ?>"><?php echo $lang_arr['kitchen_project_settings_label'] ?></a>
                            </li>

                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), '/glass') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-clone"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_glass_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/glass') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/glass') ?>"><?php echo $lang_arr['kitchen_glass_categories_label'] ?> </a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/glass') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/glass') ?>">
                                    <?php echo $lang_arr['kitchen_glass_items_label'] ?>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li class="<?php if (strpos($this->uri->uri_string(), 'prices') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('prices/') ?>">
                            <i class="fa fa-money"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_prices_materials_label'] ?></span>
                        </a>
                    </li>

                    <li style="display: none" class="<?php if (strpos($this->uri->uri_string(), '/builtin') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-calculator"></i>
                            <span class="nav-label"><?php echo $lang_arr['builtin_models_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/builtin') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/builtin') ?>"><?php echo $lang_arr['builtin_categories_label'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/builtin') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/builtin') ?>"><?php echo $lang_arr['builtin_items_label'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/prices/builtin') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/prices/builtin') ?>"><?php echo $lang_arr['builtin_items_list_common'] ?></a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), '/tech') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-calculator"></i>
                            <span class="nav-label"><?php echo $lang_arr['tech_models_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/tech') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/tech') ?>"><?php echo $lang_arr['tech_categories_label'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/tech') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/tech') ?>"><?php echo $lang_arr['tech_items_label'] ?></a>
                            </li>

                            <?php if (check_access_to_common_db()): ?>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/tech') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/categories_common/tech') ?>"><?php echo $lang_arr['tech_categories_label'] ?> (Общая)</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/tech') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/items_common/tech') ?>"><?php echo $lang_arr['tech_items_label'] ?> (Общая)</a>
                                </li>
                            <?php endif; ?>

                        </ul>


                    </li>


                    <li class="<?php if (strpos($this->uri->uri_string(), '/interior') !== false) echo 'active' ?>">

                        <a href="#">
                            <i class="fa fa-bed"></i>
                            <span class="nav-label"><?php echo $lang_arr['interior_models_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/interior') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/interior') ?>"><?php echo $lang_arr['interiors_categories_label'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/interior') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/interior') ?>"><?php echo $lang_arr['interiors_list_label'] ?></a>
                            </li>

                            <?php if (check_access_to_common_db()): ?>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/interior') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/categories_common/interior') ?>"><?php echo $lang_arr['interiors_categories_label'] ?> (Общая)</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/interior') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/items_common/interior') ?>"><?php echo $lang_arr['interior_models_label'] ?> (Общая)</a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), '/comms') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-wrench"></i>
                            <span class="nav-label"><?php echo $lang_arr['comms_models_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/comms') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/comms') ?>"><?php echo $lang_arr['comms_categories_label'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/comms') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/comms') ?>"><?php echo $lang_arr['comms_list_label'] ?></a>
                            </li>

                            <?php if (check_access_to_common_db()): ?>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/comms') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/categories_common/comms') ?>"><?php echo $lang_arr['comms_categories_label'] ?> (Общая)</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/comms') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/items_common/comms') ?>"><?php echo $lang_arr['comms_list_label'] ?> (Общая)</a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'washes') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-bath"></i>
                            <span class="nav-label"><?php echo $lang_arr['washes'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/washes') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/washes') ?>"><?php echo $lang_arr['washes_categories'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/washes') !== false || strpos($this->uri->uri_string(), 'catalog/item/washes') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/washes') ?>"><?php echo $lang_arr['washes_items'] ?></a>
                            </li>

                            <?php if (check_access_to_common_db()): ?>

                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/washes') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/categories_common/washes') ?>"><?php echo $lang_arr['washes_categories'] ?> (Общая)</a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/washes') !== false) echo 'active' ?>">
                                    <a style="color: red" href="<?php echo site_url('catalog/items_common/washes') ?>"><?php echo $lang_arr['washes_items'] ?> (Общая)</a>
                                </li>


                            <?php endif; ?>

                        </ul>
                    </li>


                    <li class="<?php if (strpos($this->uri->uri_string(), 'kitchen_models/') !== false || strpos($this->uri->uri_string(), 'module_sets/') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-gavel"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_dealer_mode'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/kitchen_models') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/kitchen_models') ?>"><?php echo $lang_arr['kitchen_models_categories'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'kitchen_models/') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('kitchen_models/index/') ?>"><?php echo $lang_arr['kitchen_models'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'module_sets/') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('module_sets/sets_index/') ?>"><?php echo $lang_arr['kitchen_modules_sets_label'] ?></a>
                            </li>
                        </ul>
                    </li>

                    <li
                            class="<?php if (
                                strpos($this->uri->uri_string(), 'bardesks/') !== false ||
                                strpos($this->uri->uri_string(), 'catalog/items/bardesks') !== false ||
                                strpos($this->uri->uri_string(), 'catalog/categories/bardesks') !== false
                            ) echo 'active' ?>"
                    >
                        <a href="#">
                            <i class="fa fa-bed"></i>
                            <span class="nav-label">Барные стойки</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/bardesks') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/bardesks') ?>">Категории барных стоек</a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/bardesks') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/bardesks') ?>">Барные стойки</a>
                            </li>
                        </ul>
                    </li>


                    <li
                            class="<?php if (
                                strpos($this->uri->uri_string(), 'catalogue/') !== false ||
                                strpos($this->uri->uri_string(), 'catalog/items/catalogue') !== false ||
                                strpos($this->uri->uri_string(), 'catalog/categories/catalogue') !== false ||
                                strpos($this->uri->uri_string(), 'catalog/categories/model3d') !== false ||
                                strpos($this->uri->uri_string(), 'catalog/items/lockers') !== false ||
                                strpos($this->uri->uri_string(), 'material_types') !== false ||
                                strpos($this->uri->uri_string(), 'catalog/items/model3d') !== false
                            ) echo 'active' ?>"
                    >
                        <div class="pro_mode"><span>Pro</span></div>
                        <a href="#">
                            <i class="fa fa-bed"></i>
                            <span class="nav-label">Корпусная мебель</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/catalogue') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/catalogue') ?>">Категории корпусной мебели</a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/catalogue') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/catalogue') ?>">Элементы корпусной мебели</a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories/model3d') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories/model3d') ?>">Категории 3D моделей</a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/model3d') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/model3d') ?>">3D Модели</a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items/lockers') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/lockers') ?>">Параметрические элементы</a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'material_types') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/material_types') ?>">Материалы</a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'accessories') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('accessories/') ?>">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="nav-label"><?php echo $lang_arr['accessories_label'] ?></span>
                        </a>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'params_blocks') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('/catalog/items/params_blocks') ?>">
                            <i class="fa fa-list-ul"></i>
                            <span class="nav-label"><?php echo $lang_arr['params_blocks'] ?></span>
                        </a>
                    </li>

                    <?php if ($this->config->item('ini')['modules']['360'] == 1): ?>
                        <li class="<?php if (strpos($this->uri->uri_string(), 'background') !== false) echo 'active' ?>">
                            <a href="<?php echo site_url('/catalog/items/background') ?>">
                                <i class="fa fa-camera"></i>
                                <span class="nav-label"><?php echo $lang_arr['background_items'] ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!$this->config->item('antar')): ?>
                    <li class="<?php if (strpos($this->uri->uri_string(), 'templates') !== false && strpos($this->uri->uri_string(), 'modules_templates') === false) echo 'active' ?>">
                        <a href="<?php echo site_url('templates/') ?>">
                            <i class="fa fa-magic"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_templates'] ?></span>
                        </a>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'report') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('report/') ?>">
                            <i class="fa fa-file-pdf-o"></i>
                            <span class="nav-label"><?php echo $lang_arr['report'] ?></span>
                        </a>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'sync') !== false) echo 'active' ?>">
                        <div class="pro_mode"><span>Pro</span></div>
                        <a href="<?php echo site_url('sync/') ?>">
                            <i class="fa fa-external-link-square"></i>
                            <span class="nav-label"><?php echo $lang_arr['api'] ?></span>
                        </a>
                    </li>

                <?php endif; ?>

                <li class="<?php if (strpos($this->uri->uri_string(), 'custom_scripts') !== false) echo 'active' ?>">
                    <div class="pro_mode"><span>Pro</span></div>
                    <a href="<?php echo site_url('custom_scripts/') ?>">
                        <i class="fa fa-file-code-o"></i>
                        <span class="nav-label"><?php echo $lang_arr['custom_code'] ?></span>
                    </a>
                </li>

<!--                <li class="--><?php //if (strpos($this->uri->uri_string(), 'theme') !== false) echo 'active' ?><!--">-->
<!--                    <a href="--><?php //echo site_url('theme/') ?><!--">-->
<!--                        <i class="fa fa-wrench"></i>-->
<!--                        <span class="nav-label">--><?php //echo $lang_arr['theme_label'] ?><!--</span>-->
<!--                    </a>-->
<!--                </li>-->

                <li class="<?php if (strpos($this->uri->uri_string(), 'languages') !== false) echo 'active' ?>">
                    <a href="<?php echo site_url('languages/') ?>">
                        <i class="fa fa-language"></i>
                        <span class="nav-label"><?php echo $lang_arr['languages'] ?></span>
                    </a>
                </li>

                <?php if ($this->config->item('antar')): ?>
                    <li class="<?php if (strpos($this->uri->uri_string(), 'modules_visibility/') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('module_sets/modules_visibility/') ?>"><?php echo $lang_arr['kitchen_modules_sets_label'] ?></a>
                    </li>
                <?php endif; ?>




                <?php if (!$this->config->item('antar')): ?>


<!--                    <li class="--><?php //if (strpos($this->uri->uri_string(), 'sections') !== false) echo 'active' ?><!--">-->
<!--                        <a href="--><?php //echo site_url('sections/') ?><!--">-->
<!--                            <i class="fa fa-list"></i>-->
<!--                            <span class="nav-label">Доп. меню</span>-->
<!--                        </a>-->
<!--                    </li>-->

                    <li class="<?php if (strpos($this->uri->uri_string(), 'email') !== false) echo 'active' ?>">
                        <a href="<?php echo site_url('email/') ?>">
                            <i class="fa fa-list"></i>
                            <span class="nav-label"><?php echo $lang_arr['order_templates_heading'] ?></span>
                        </a>
                    </li>

                <?php endif; ?>

                <?php if (check_access_to_common_db()): ?>
                    <li class="<?php if (strpos($this->uri->uri_string(), '_common/materials') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-paint-brush"></i>
                            <span class="nav-label">ДЕКОРЫ ОБЩАЯ БАЗА</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/materials') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories_common/materials') ?>">КАТЕГОРИИ ДЕКОРОВ ОБЩАЯ БАЗА</a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'materials/items_index_common') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('materials/items_index_common') ?>">
                                    ДЕКОРЫ ОБЩАЯ БАЗА
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), '/facades_systems') !== false) echo 'active' ?>">
                        <a href="#">
                            <i class="fa fa-paint-brush"></i>
                            <span class="nav-label">ФАСАДНЫЕ СИСТЕМЫ ОБЩАЯ БАЗА</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/facades_systems') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories_common/facades_systems') ?>">КАТЕГОРИИ ФАСАДНЫХ СИСТЕМ</a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'materials/facades_systems_index_common') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items_common/facades_systems') ?>">
                                    ФАСАДНЫЕ СИСТЕМЫ ОБЩАЯ БАЗА
                                </a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/modules_sets') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items_common/modules_sets') ?>">
                                    МОДУЛИ ДЛЯ ФАСАДНЫХ СИСТЕМ
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li>
                        <a href="#">
                            <i class="fa fa-paint-brush"></i>
                            <span class="nav-label">МОДЕЛИ ОБЩАЯ БАЗА</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/categories_common/builtin') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/categories_common/builtin') ?>">КАТЕГОРИИ МОДЕЛЕЙ</a>
                            </li>

                            <li class="<?php if (strpos($this->uri->uri_string(), 'catalog/items_common/builtin') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items_common/builtin') ?>">
                                    СПИСОК МОДЕЛЕЙ
                                </a>
                            </li>

                        </ul>
                    </li>


                <?php endif; ?>

            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">


                <ul class="nav navbar-top-links navbar-left pl-2">

                    <li style="text-align: left">

                        <?php
                        $tn = array();
                        $tn['10'] = 'LOW';
                        $tn['11'] = 'LOW+';
                        $tn['12'] = 'TEST LOW';
                        $tn['15'] = 'MASTER';
                        $tn['17'] = 'SUB';
                        $tn['20'] = 'TEST';
                        $tn['40'] = 'PRO';
                        $tn['50'] = 'PRO+';
                        $tn['20'] = 'TEST';

                        ?>

                        <?php if (isset($this->config->item('ini')['tariff']) && isset($this->config->item('ini')['tariff']['id']) && isset($tn[$this->config->item('ini')['tariff']['id']])): ?>
                            <div style="font-size: 11px;">
                                <?php echo $lang_arr['tariff'] ?>: <?php echo $tn[$this->config->item('ini')['tariff']['id']] ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($this->config->item('ini')['tariff'])): ?>
                            <div style="font-size: 11px;">
                                <?php echo $lang_arr['tariff_end_date'] ?>: <?php echo $this->config->item('ini')['tariff']['end_date'] ?>
                            </div>
                        <?php endif; ?>

                        <!--                        --><?php //if(isset($this->config->item('ini')['rendering'])):?>
                        <!--                            <div style="font-size: 11px;">-->
                        <!--                                --><?php //if($this->config->item('ini')['rendering']['available'] == 1):?>
                        <!--                                    <span>--><?php //echo $lang_arr['rendering']?><!--: --><?php //echo $this->config->item('ini')['rendering']['end_date'] ?><!--</span>-->
                        <!--                                --><?php //else:?>
                        <!--                                    <span>--><?php //echo $lang_arr['rendering']?><!--: --><?php //echo $lang_arr['no']?><!--</span>-->
                        <!--                                --><?php //endif;?>
                        <!--                            </div>-->
                        <!--                        --><?php //endif;?>


                        <div style="font-size: 11px;">
                            <a style="font-size: 11px; padding: 0" class="text-success" target="_blank" href="https://planplace.ru/">
                                <?php echo $lang_arr['renew_license'] ?>
                            </a>
                        </div>

                    </li>

                    <li>
                        <a class="text-success" target="_blank" href="https://help.planplace.online/support_form">
                            <?php echo $lang_arr['tech_support'] ?>
                        </a>
                    </li>


                    <li>
                        <div class="btn-group ml-3">
                            <button data-toggle="dropdown" class="btn btn-success btn-outline dropdown-toggle" aria-expanded="false">Дополнительно</button>
                            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; top: 33px; left: 0px; will-change: top, left;">

                                <li>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#backup_bd_modal" href="#">
                                        Резервная копия БД
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" data-toggle="modal" data-target="#change_password_modal" href="#">
                                        <?php echo $lang_arr['change_password'] ?>
                                    </a>
                                </li>

                                <?php if ($this->config->item('sub_account') == false): ?>
                                    <!--                                    <li class="dropdown-divider"></li>-->
                                    <!--                                    <li>-->
                                    <!--                                        <a class="text-danger dropdown-item" id="clear_settings" href="#">-->
                                    <!--                                            --><?php //echo $lang_arr['clear_settings'] ?>
                                    <!--                                        </a>-->
                                    <!--                                    </li>-->
                                <?php endif; ?>
                            </ul>
                        </div>


                    </li>


                </ul>

                <ul class="nav navbar-top-links navbar-right">

                    <li>
                        <button type="button" id="save_settings" class="btn btn-outline btn-primary"><i class="fa fa-upload mr-2"></i><?php echo $lang_arr['export_settings'] ?></button>
                        <!--                        <a href="#" id="save_settings" >--><?php //echo $lang_arr['export_settings']?><!--</a>-->
                    </li>

                    <li>

                        <?php $c_link = $this->config->item('const_path')?>

                        <?php

                        if (strpos($this->config->item('const_path'), 'mfsibir.ru') !== false){
                            $c_link.='?manager=1';
                        }

                        ?>


                        <a target="_blank" class="text-success" href="<?php echo $c_link ?>">
                            <i class="fa fa-external-link"></i> <?php echo $lang_arr['go_to_constructor'] ?>
                        </a>
                    </li>


                    <li>
                        <a class="text-success" id="logout" href="<?php echo site_url('login/logout/') ?>">
                            <i class="fa fa-sign-out"></i> <?php echo $lang_arr['logout'] ?>
                        </a>
                    </li>
                    <!--                    <li>-->
                    <!--                        <a class="right-sidebar-toggle">-->
                    <!--                            <i class="fa fa-tasks"></i>-->
                    <!--                        </a>-->
                    <!--                    </li>-->
                </ul>

            </nav>
        </div>









