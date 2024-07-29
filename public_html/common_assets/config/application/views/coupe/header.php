
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Личный кабинет PlanPlace</title>


    <!--    <link rel="stylesheet" href="/common_assets/libs/bootstrap/bootstrap_w_theme.min.css">-->
    <!--    <link rel="stylesheet" href="/common_assets/css/admin.css?--><?php //echo md5(date('m-d-Y-His A e'));?><!--">-->


    <link href="/common_assets/theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="/common_assets/theme/font-awesome/css/font-awesome.css" rel="stylesheet">
    <!--    <link href="/common_assets/theme/css/plugins/bootstrap-rtl/bootstrap-rtl.min.css" rel="stylesheet">-->

    <!-- Toastr style -->
    <link href="/common_assets/theme/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="/common_assets/theme/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">


    <!-- Sweet Alert -->
    <link href="/common_assets/theme/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <link rel="stylesheet" href="/common_assets/libs/selectize/selectize.bootstrap3.css">


    <link href="/common_assets/theme/css/animate.css" rel="stylesheet">
    <link href="/common_assets/theme/css/style.css" rel="stylesheet">



    <script src="/common_assets/theme/js/jquery-3.1.1.min.js"></script>



</head>

<?php if(!$this->config->item('sub_account')) $this->config->set_item('sub_account', false); ?>


<body class="fixed-nav fixed-nav-basic">


<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="btn-group" style="width: 100%;">
                        <button data-constructor="kitchen" class="constructor_change btn <?php if($_SESSION['constructor'] == 'kitchen'){echo 'btn-primary';} else{echo 'btn-white';}?>" type="button" style="width: 50%;">Кухни</button>
                        <button data-constructor="coupe" class="constructor_change btn <?php if($_SESSION['constructor'] == 'coupe'){echo 'btn-primary';} else{echo 'btn-white';}?>" type="button" style="width: 50%;">Шкафы</button>
                    </div>
                </li>


                <li class="<?php if (strpos($this->uri->uri_string(), 'settings/coupe_account_settings_index') !== false && strpos($this->uri->uri_string(), 'project_settings') === false) echo 'active'?>">
                    <a href="<?php echo site_url('settings/coupe_account_settings_index') ?>">
                        <i class="fa fa-code"></i>
                        <span class="nav-label"><?php echo $lang_arr['coupe_account_settings_label']?></span>
                    </a>
                </li>

                <li class="<?php if (strpos($this->uri->uri_string(), 'constructor/index_coupe') !== false && strpos($this->uri->uri_string(), 'project_settings') === false) echo 'active'?>">
                    <a href="<?php echo site_url('constructor/index_coupe') ?>">
                        <i class="fa fa-cogs"></i>
                        <span class="nav-label"><?php echo $lang_arr['kitchen_constructor_settings_label']?></span>
                    </a>
                </li>



	            <?php if ($this->config->item('sub_account') == false): ?>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'materials/') !== false ) echo 'active'?>">
                        <a href="#">
                            <i class="fa fa-paint-brush"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_materials_label'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'materials/coupe_categories_index') !== false) echo 'active'?>">
                                <a href="<?php echo site_url('materials/coupe_categories_index/') ?>"><?php echo $lang_arr['kitchen_materials_categories_label'] ?></a>
                            </li>
				            <?php if(isset($_SESSION['coupe_selected_materials_category'])):?>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'materials/coupe_materials_items_index') !== false) echo 'active'?>">
                                    <a href="<?php echo site_url('materials/coupe_materials_items_index/'.$_SESSION['coupe_selected_materials_category'].'/'.$_SESSION['coupe_selected_materials_per_page'].'/'.$_SESSION['coupe_selected_materials_pagination']) ?>">
							            <?php echo $lang_arr['kitchen_materials_items_label'] ?>
                                    </a>
                                </li>
				            <?php else:?>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'materials/coupe_materials_items_index') !== false) echo 'active'?>">
                                    <a href="<?php echo site_url('materials/coupe_materials_items_index/') ?>"><?php echo $lang_arr['kitchen_materials_items_label'] ?></a>
                                </li>
				            <?php endif;?>

                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'coupe_profile') !== false || strpos($this->uri->uri_string(), 'sa1') !== false ) echo 'active'?>">
                        <a href="#">
                            <i class="fa fa-paint-brush"></i>
                            <span class="nav-label"><?php echo $lang_arr['profiles_set'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            <li class="<?php if (strpos($this->uri->uri_string(), 'categories/coupe_profile') !== false) echo 'active'?>">
                                <a href="<?php echo site_url('catalog/categories/coupe_profile/') ?>"><?php echo $lang_arr['categories'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'items/coupe_profile') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/coupe_profile') ?>"><?php echo $lang_arr['profiles_set'] ?></a>
                            </li>

                                <li class="<?php if (strpos($this->uri->uri_string(), 'categories/coupe_accessories/sa1') !== false) echo 'active'?>">
                                    <a href="<?php echo site_url('catalog/categories/coupe_accessories/sa1') ?>"><?php echo $lang_arr['accessories_categories'] ?></a>
                                </li>
                                <li class="<?php if (strpos($this->uri->uri_string(), 'items/coupe_accessories/sa1') !== false) echo 'active' ?>">
                                    <a href="<?php echo site_url('catalog/items/coupe_accessories/sa1') ?>"><?php echo $lang_arr['accessories'] ?></a>
                                </li>


                        </ul>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'accessories') !== false && strpos($this->uri->uri_string(), 'sa1') === false ) echo 'active'?>">
                        <a href="#">
                            <i class="fa fa-paint-brush"></i>
                            <span class="nav-label"><?php echo $lang_arr['accessories'] ?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">


                            <li class="<?php if (strpos($this->uri->uri_string(), 'categories/coupe_accessories') !== false) echo 'active'?>">
                                <a href="<?php echo site_url('catalog/categories/coupe_accessories/') ?>"><?php echo $lang_arr['accessories_categories'] ?></a>
                            </li>
                            <li class="<?php if (strpos($this->uri->uri_string(), 'items/coupe_accessories') !== false) echo 'active' ?>">
                                <a href="<?php echo site_url('catalog/items/coupe_accessories') ?>"><?php echo $lang_arr['accessories'] ?></a>
                            </li>


                        </ul>
                    </li>







                    <li class="<?php if (strpos($this->uri->uri_string(), 'coupe_project_settings_index') !== false) echo 'active'?>">

                        <a href="<?php echo site_url('project_settings/coupe_project_settings_index/') ?>">
                            <i class="fa fa-money"></i>
                            <span class="nav-label"><?php echo $lang_arr['prices_and_materials'] ?></span>
                        </a>
                    </li>

                    <li class="<?php if (strpos($this->uri->uri_string(), 'templates') !== false && strpos($this->uri->uri_string(), 'modules_templates') === false) echo 'active'?>">
                        <a href="<?php echo site_url('templates/index_coupe') ?>">
                            <i class="fa fa-magic"></i>
                            <span class="nav-label"><?php echo $lang_arr['kitchen_templates'] ?></span>
                        </a>
                    </li>

	            <?php endif;?>

                <li class="<?php if (strpos($this->uri->uri_string(), 'custom_scripts') !== false) echo 'active'?>">
                    <a href="<?php echo site_url('custom_scripts/coupe_index') ?>">
                        <i class="fa fa-file-code-o"></i>
                        <span class="nav-label"><?php echo $lang_arr['custom_code'] ?></span>
                    </a>
                </li>




            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">
                <ul class="nav navbar-top-links navbar-left pl-2">

                    <li style="text-align: center">
                        <?php if(isset($this->config->item('ini')['tariff'])):?>
                            <div style="font-size: 11px;">
                                <?php echo $lang_arr['tariff_end_date']?> <?php echo $this->config->item('ini')['tariff']['end_date'] ?>
                            </div>
                        <?php endif;?>


                        <div>
                            <a class="text-success" target="_blank" href="https://bplanner.me/ceny/">
                                <?php echo $lang_arr['renew_license']?>
                            </a>
                        </div>

                    </li>

                    <li>
                        <a class="text-success" target="_blank" href="https://bplanner.me/forum/forum/teh-podderzhka-bplanner/">
                            <?php echo $lang_arr['tech_support']?>
                        </a>
                    </li>


                </ul>
                <ul class="nav navbar-top-links navbar-right">

                    <li>
                        <button id="save_coupe" type="button" class="btn btn-outline btn-primary"><i class="fa fa-upload mr-2"></i><?php echo $lang_arr['export_settings']?></button>
                        <!--                        <a href="#" id="save_settings" >--><?php //echo $lang_arr['export_settings']?><!--</a>-->
                    </li>

                    <li >
                        <a  target="_blank" class="text-success" href="<?php echo $this->config->item('const_path') . 'config/index.php/main_coupe' ?>" >
                            <i class="fa fa-external-link"></i> <?php echo $lang_arr['go_to_constructor']?>
                        </a>
                    </li>



                    <li>
                        <a class="text-success" id="logout" href="<?php echo site_url('login/logout/') ?>">
                            <i class="fa fa-sign-out"></i> <?php echo $lang_arr['logout']?>
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









