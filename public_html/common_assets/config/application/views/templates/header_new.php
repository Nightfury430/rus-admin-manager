<!DOCTYPE html>
<html lang="en"></html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- theme meta -->
    <?php if ($this->config->item('const_path') == 'https://planplace.ru/clients/test/'): ?>
        <title>Личный кабинет PlanPlace (Тест)</title>
    <?php else: ?>
        <title>Личный кабинет PlanPlace</title>
    <?php endif; ?>

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="/common_assets/images/favicon_admin.ico" type="image/x-icon">
    <!-- Pignose Calender -->
    <link href="/common_assets/theme/plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="/common_assets/theme/plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="/common_assets/theme/plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="/common_assets/theme/css/style.css" rel="stylesheet">
    <link href="/common_assets/css/microtip.css" rel="stylesheet">
    <link href="/common_assets/theme/css/style.css?<?php echo md5(date('m-d-Y-His A e')); ?>" rel="stylesheet">
    <script src="/common_assets/theme/plugins/jquery/jquery.min.js"></script>
</head>
<?php if (!$this->config->item('sub_account')) $this->config->set_item('sub_account', false); ?>
<body>
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.html">
                    <b class="logo-abbr"><img src="/common_assets/theme/images/logo.png" alt=""> </b>
                    <span class="logo-compact"><img src="/common_assets/theme/images/logo-compact.png" alt=""></span>
                    <span class="brand-title">
                        <img src="/common_assets/theme/images/logo-text.png" alt="">
                    </span>
                </a>
            </div>
        </div>
        <!--**********************************-->


        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">    
            <div class="header-content clearfix">
                
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
        </div>
        <!--**********************************-->
        
        <!--**********************************
            Sidebar start
        ***********************************-->

        <div class="nk-sidebar">           
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label">Dashboard</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Dashboard</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./index.html">Home 1</a></li>
                            <!-- <li><a href="./index-2.html">Home 2</a></li> -->
                        </ul>
                    </li>
                    <li class="mega-menu mega-menu-sm">
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-globe-alt menu-icon"></i><span class="nav-text">Layouts</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./layout-blank.html">Blank</a></li>
                            <li><a href="./layout-one-column.html">One Column</a></li>
                            <li><a href="./layout-two-column.html">Two column</a></li>
                            <li><a href="./layout-compact-nav.html">Compact Nav</a></li>
                            <li><a href="./layout-vertical.html">Vertical</a></li>
                            <li><a href="./layout-horizontal.html">Horizontal</a></li>
                            <li><a href="./layout-boxed.html">Boxed</a></li>
                            <li><a href="./layout-wide.html">Wide</a></li>
                            
                            
                            <li><a href="./layout-fixed-header.html">Fixed Header</a></li>
                            <li><a href="layout-fixed-sidebar.html">Fixed Sidebar</a></li>
                        </ul>
                    </li>
                    <li class="nav-label">Apps</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-envelope menu-icon"></i> <span class="nav-text">Email</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./email-inbox.html">Inbox</a></li>
                            <li><a href="./email-read.html">Read</a></li>
                            <li><a href="./email-compose.html">Compose</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-screen-tablet menu-icon"></i><span class="nav-text">Apps</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./app-profile.html">Profile</a></li>
                            <li><a href="./app-calender.html">Calender</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-graph menu-icon"></i> <span class="nav-text">Charts</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./chart-flot.html">Flot</a></li>
                            <li><a href="./chart-morris.html">Morris</a></li>
                            <li><a href="./chart-chartjs.html">Chartjs</a></li>
                            <li><a href="./chart-chartist.html">Chartist</a></li>
                            <li><a href="./chart-sparkline.html">Sparkline</a></li>
                            <li><a href="./chart-peity.html">Peity</a></li>
                        </ul>
                    </li>
                    <li class="nav-label">UI Components</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-grid menu-icon"></i><span class="nav-text">UI Components</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./ui-accordion.html">Accordion</a></li>
                            <li><a href="./ui-alert.html">Alert</a></li>
                            <li><a href="./ui-badge.html">Badge</a></li>
                            <li><a href="./ui-button.html">Button</a></li>
                            <li><a href="./ui-button-group.html">Button Group</a></li>
                            <li><a href="./ui-cards.html">Cards</a></li>
                            <li><a href="./ui-carousel.html">Carousel</a></li>
                            <li><a href="./ui-dropdown.html">Dropdown</a></li>
                            <li><a href="./ui-list-group.html">List Group</a></li>
                            <li><a href="./ui-media-object.html">Media Object</a></li>
                            <li><a href="./ui-modal.html">Modal</a></li>
                            <li><a href="./ui-pagination.html">Pagination</a></li>
                            <li><a href="./ui-popover.html">Popover</a></li>
                            <li><a href="./ui-progressbar.html">Progressbar</a></li>
                            <li><a href="./ui-tab.html">Tab</a></li>
                            <li><a href="./ui-typography.html">Typography</a></li>
                        <!-- </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-layers menu-icon"></i><span class="nav-text">Components</span>
                        </a>
                        <ul aria-expanded="false"> -->
                            <li><a href="./uc-nestedable.html">Nestedable</a></li>
                            <li><a href="./uc-noui-slider.html">Noui Slider</a></li>
                            <li><a href="./uc-sweetalert.html">Sweet Alert</a></li>
                            <li><a href="./uc-toastr.html">Toastr</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="widgets.html" aria-expanded="false">
                            <i class="icon-badge menu-icon"></i><span class="nav-text">Widget</span>
                        </a>
                    </li>
                    <li class="nav-label">Forms</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-note menu-icon"></i><span class="nav-text">Forms</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./form-basic.html">Basic Form</a></li>
                            <li><a href="./form-validation.html">Form Validation</a></li>
                            <li><a href="./form-step.html">Step Form</a></li>
                            <li><a href="./form-editor.html">Editor</a></li>
                            <li><a href="./form-picker.html">Picker</a></li>
                        </ul>
                    </li>
                    <li class="nav-label">Table</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-menu menu-icon"></i><span class="nav-text">Table</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./table-basic.html" aria-expanded="false">Basic Table</a></li>
                            <li><a href="./table-datatable.html" aria-expanded="false">Data Table</a></li>
                        </ul>
                    </li>
                    <li class="nav-label">Pages</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-notebook menu-icon"></i><span class="nav-text">Pages</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="./page-login.html">Login</a></li>
                            <li><a href="./page-register.html">Register</a></li>
                            <li><a href="./page-lock.html">Lock Screen</a></li>
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                                <ul aria-expanded="false">
                                    <li><a href="./page-error-404.html">Error 404</a></li>
                                    <li><a href="./page-error-403.html">Error 403</a></li>
                                    <li><a href="./page-error-400.html">Error 400</a></li>
                                    <li><a href="./page-error-500.html">Error 500</a></li>
                                    <li><a href="./page-error-503.html">Error 503</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid mt-3">
                
