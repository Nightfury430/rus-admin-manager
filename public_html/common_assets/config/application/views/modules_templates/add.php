<form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('modules_templates/add/') ?>">

<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-header">
                    <h5><?php echo $lang_arr['basic_params']?></h5>
                </div>
                <div class="card-body">
	                <?php echo validation_errors(); ?>
                    <div class="mb-4">
                        <label for="name" class="form-label" ><?php echo $lang_arr['name']?></label>
                        <input type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                    </div>

                    <div class="mb-4">
                        <label for="order"><?php echo $lang_arr['order']?></label>
                        <input type="text" class="form-control" name="order">
                    </div>


                    <div class="mb-4">
                        <label for="category"><?php echo $lang_arr['category']?></label>
                        <select class="form-control" name="category" id="category">
                            <option value=""><?php echo $lang_arr['choose_category']?></option>
                            <option value="1"><?php echo $lang_arr['kitchen_bottom_modules']?></option>
                            <option value="2"><?php echo $lang_arr['kitchen_top_modules']?></option>
                            <option value="3"><?php echo $lang_arr['kitchen_penals_modules']?></option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <a href="#" id="run_configurator" class="btn btn-success"><?php echo $lang_arr['run_configurator']?></a>
                    </div>

                    <div class="mb-4">
                        <img id="module_icon" src="" alt="">
                        <input id="module_icon_input" name="module_icon_input" type="hidden" value="">
                    </div>

                    <div class="mb-4">
                        <label for="template"><?php echo $lang_arr['module_template']?></label>
                        <div>
                            <textarea name="template" id="template"></textarea>
                        </div>
                    </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('modules_templates/index/') ?>"><?php echo $lang_arr['cancel']?></a>
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
</form>



<div class="three_modal_wrapper">
    <div class="three_modal">
        <span class="close_three_modal glyphicon glyphicon-remove"></span>
        <div id="main_app"></div>
        <div id="three_viewport">
            <div id="state_panel"></div>
        </div>
    </div>
</div>
<script>
    


</script>


<style>

    .bp_modal_wrapper{
        z-index: 10000;
    }

    .bp_modal_wrapper select.form-control{
        height: 35px!important;
    }

    .bp_modal_wrapper label{
        margin-top: 10px;
        margin-bottom: 0;
    }

    #module_icon{
        max-width: 200px;
        height: auto;
    }

    .l_hack{
        opacity: 0;
    }

    .model_panel{
        position: relative;
        padding-top: 30px;
        padding-bottom: 10px;
        margin-bottom: 10px!important;
    }

    .remove_panel{
        position: absolute;
        top:10px;
        right: 10px;
        color: #ff0000;
        cursor: pointer;
    }

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

    #three_viewport_controls{
        position: absolute;
        background: #333333;
        left: 0;
        top:0;
        color: #fff;
        padding: 5px;
    }

    #three_viewport_controls button, #three_viewport_controls input{
        color: #000;
    }

    #three_viewport_controls span{
        display: inline-block;
        width: 110px;
    }

    #three_viewport_controls input{
        display: inline-block;
        width: 80px;
        margin: 5px;
        padding: 0 5px;
    }

    #three_viewport_controls button{
        display: inline-block;
        width: 60px;
        margin: 5px;
    }

    .selectize-control.multi .selectize-input > div{
        background: #428bca;
        color: #ffffff;
        padding-left: 10px;
        padding-right: 10px;
    }


</style>
<style>
    textarea{
        width: 100%;
        min-height: 100px;
    }

    #state_panel{
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        background: #353537;
        z-index: 999;
        color: #fff;
    }

    #state_panel p{
        margin-bottom: 2px;
    }

    #state_panel input{
        color: #000000;
        width: 100%;
        margin-bottom: 5px;
    }

    #state_panel input[type="checkbox"]{
        width: auto;
        margin: 0 0 0 5px;
        vertical-align: middle;
    }

    #state_panel select{
        color: #000000;
        width: 100%;
        margin-bottom: 5px;
        height: 31px;
        padding: 0 8px;

    }

    #state_panel{
        padding: 10px;
    }

    #state_panel .materials_select{
        position: absolute;
        bottom: 20px;
        top: 50px;
        left: 10px;
        right: 10px;
        overflow: auto;
    }

    .configurator_panel{
        width: 350px!important;
    }

    .module_configurator_block_heading{
        text-align: center;
    }

    .module_configurator_block{
        padding: 5px;
    }

    .module_shelve{
        position: relative;
    }

    .module_door{
        position: relative;
    }

    .module_decoration{
        position: relative;
    }

    .module_locker{
        position: relative;
    }

    .remove_conf_block{
        color: #ff0000;
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        z-index: 99;
    }

    .configurator_part_block{
        margin: 10px 0;
        padding-top: 10px;
        padding-bottom: 10px;
        border: 1px solid #484848;
    }

    .configurator_part_block select{
        /*height: 26px;*/
    }

    .state_panel_wrapper{
        overflow: auto;
        position: absolute;
        top: 50px;
        bottom: 10px;
        left: 10px;
        right: 10px;
    }

    .acc_block{
        border: 1px solid #484848;
        overflow: auto;
    }

    .acc_body{
        display: none;
    }

    .acc_heading{
        padding: 10px;
        cursor: pointer;
    }

    .bp_modal{
        right: 30px!important;
        margin: 0!important;
        left: auto!important;
        min-width: 460px;
    }

    .bp_modal .col-xs-12{
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }
</style>

