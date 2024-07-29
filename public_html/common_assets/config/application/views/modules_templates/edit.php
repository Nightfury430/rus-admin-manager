<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['edit_module_template']?></h2>
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

<form id="sub_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('modules_templates/edit/'.$item['id']) ?>">

    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?php echo $lang_arr['basic_params']?></h5>
                    </div>
                    <div class="ibox-content">

						<?php echo validation_errors(); ?>


                        <div class="form-group">
                            <label for="name"><?php echo $lang_arr['name']?></label>
                            <input value="<?php echo $item['name']?>" type="text" class="form-control" name="name" placeholder="<?php echo $lang_arr['name']?>">
                        </div>

                        <div class="form-group">
                            <label for="order"><?php echo $lang_arr['order']?></label>
                            <input value="<?php echo $item['order']?>" type="text" class="form-control" name="order">
                        </div>

                        <div class="form-group">
                            <label for="category"><?php echo $lang_arr['category']?></label>
                            <select class="form-control" name="category" id="category">
                                <option value=""><?php echo $lang_arr['choose_category']?></option>
                                <option <?php if($item['category'] == 1) echo 'selected'?> value="1"><?php echo $lang_arr['kitchen_bottom_modules']?></option>
                                <option <?php if($item['category'] == 2) echo 'selected'?> value="2"><?php echo $lang_arr['kitchen_top_modules']?></option>
                                <option <?php if($item['category'] == 3) echo 'selected'?> value="3"><?php echo $lang_arr['kitchen_penals_modules']?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <a href="#" id="run_configurator" class="btn btn-success"><?php echo $lang_arr['run_configurator']?></a>
                        </div>

                        <div class="form-group">
                            <img id="module_icon" src="<?php echo $item['icon']?>" alt="">
                            <input id="module_icon_input" name="module_icon_input" type="hidden" value="<?php echo $item['icon']?>">
                        </div>

                        <div class="form-group">
                            <label for="template"><?php echo $lang_arr['module_template']?></label>
                            <div>
                                <textarea name="template" id="template"><?php echo $item['params']?></textarea>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>

                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <a class="btn btn-white btn-sm" href="<?php echo site_url('modules_templates/index/') ?>"><?php echo $lang_arr['cancel']?></a>
                                <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
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






<!--<script src="/common_assets/libs/three.js"></script>-->
<!--<script src="/common_assets/libs/libs.min.js"></script>-->

<!--<script src="/common_assets/libs/three_old.js"></script>-->
<script src="/common_assets/libs/three106.js"></script>
<script src="/common_assets/libs/FBXLoader.js"></script>
<script src="/common_assets/libs/jquery_3_3_1.min.js"></script>
<script src="/common_assets/libs/inflate.min.js"></script>

<script src="/common_assets/libs/obj_export.js"></script>
<script src="/common_assets/libs/OrbitControls.js"></script>



<!--<script src="/common_assets/js/v4/0_BPObject.js"></script>-->
<!--<script src="/common_assets/js/v4/Cabinet_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Washer.js"></script>-->
<!---->
<!--<script src="/common_assets/js/v4/Cornice.js"></script>-->
<!--<script src="/common_assets/js/v4/Door_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Facade_new.js"></script>-->
<!--<script src="/common_assets/js/v4/functions.js"></script>-->
<!--<script src="/common_assets/js/v4/Handle.js"></script>-->
<!--<script src="/common_assets/js/v4/interface.js"></script>-->
<!--<script src="/common_assets/js/v4/Locker_new.js"></script>-->
<!--<script src="/common_assets/js/v4/materials.js"></script>-->
<!--<script src="/common_assets/js/v4/Model_cache.js"></script>-->
<!--<script src="/common_assets/js/v4/Mouse_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Parts.js"></script>-->
<!--<script src="/common_assets/js/v4/Room_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Sizes_obj.js"></script>-->
<!--<script src="/common_assets/js/v4/Decoration.js"></script>-->
<!--<script src="/common_assets/js/v4/info_panel.js"></script>-->
<!---->
<!--<script src="/common_assets/admin_js/configurator_add.js"></script>-->
<!--<script src="/common_assets/admin_js/configurator_admin.js"></script>-->
<!--<script src="/common_assets/js/v4/Model_new.js"></script>-->

<!--<script src="/common_assets/js/v4/0_BPObject.js"></script>-->
<!--<script src="/common_assets/js/v4/Cabinet_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Cabinet_new_common.js"></script>-->
<!--<script src="/common_assets/js/v4/Cabinet_new_corner.js"></script>-->
<!--<script src="/common_assets/js/v4/Cabinet_new_end.js"></script>-->
<!--<script src="/common_assets/js/v4/Washer.js"></script>-->
<!---->
<!--<script src="/common_assets/js/v4/Cornice.js"></script>-->
<!--<script src="/common_assets/js/v4/Door_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Facade_new.js"></script>-->
<!--<script src="/common_assets/js/v4/functions.js"></script>-->
<!--<script src="/common_assets/js/v4/Handle.js"></script>-->
<!--<script src="/common_assets/js/v4/cart.js"></script>-->
<!--<script src="/common_assets/js/v4/interface.js"></script>-->
<!--<script src="/common_assets/js/v4/Locker_new.js"></script>-->
<!--<script src="/common_assets/js/v4/materials.js"></script>-->
<!--<script src="/common_assets/js/v4/Model_cache.js"></script>-->
<!--<script src="/common_assets/js/v4/Mouse_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Parts.js"></script>-->
<!--<script src="/common_assets/js/v4/Room_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Sizes_obj.js"></script>-->
<!--<script src="/common_assets/js/v4/Decoration.js"></script>-->
<!--<script src="/common_assets/js/v4/info_panel.js"></script>-->
<!--<script src="/common_assets/js/v4/Model_new.js"></script>-->
<!--<script src="/common_assets/js/v4/scene_controller.js"></script>-->
<!---->
<!---->
<!--<script src="/common_assets/admin_js/configurator_add.js"></script>-->
<!--<script src="/common_assets/admin_js/configurator_admin.js"></script>-->


<script src="/common_assets/admin_js/production/configurator/modules_templates.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>



<!--<script src="/common_assets/js/v4/Room_new.js"></script>-->
<!--<script src="/common_assets/js/v4/functions.js"></script>-->
<!--<script src="/common_assets/js/v4/Parts.js"></script>-->
<!--<script src="/common_assets/js/v4/materials.js"></script>-->
<!--<script src="/common_assets/js/v4/Sizes_obj.js"></script>-->
<!--<script src="/common_assets/js/v4/Cabinet_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Facade_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Locker_new.js"></script>-->
<!--<script src="/common_assets/js/v4/Door_new.js"></script>-->
<!--<script src="/common_assets/js/v4/interface.js"></script>-->
<!--<script src="/common_assets/js/v4/Cornice.js"></script>-->
<!--<script src="/common_assets/js/v4/Model_cache.js"></script>-->
<!--<script src="/common_assets/js/v4/Handle.js"></script>-->
<!--<script src="/common_assets/js/v4/Mouse_new.js"></script>-->
<!---->
<!---->
<!--<script src="/common_assets/admin_js/configurator_add.js"></script>-->
<!--<script src="/common_assets/admin_js/configurator_admin.js"></script>-->

<!--<script src="/common_assets/admin_js/production/modules_templates.js"></script>-->

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


<script>
    // var textarea = document.getElementById('template');
    // var myCodeMirror = CodeMirror.fromTextArea(textarea);

    $(document).ready(function () {
        flag = 0

        let modal = $('.three_modal_wrapper');

        $('.close_three_modal').click(function () {
            modal.fadeOut();
        });

        $('#run_configurator').click(function (e) {
            e.preventDefault();
            modal.fadeIn();

            if(flag === 0){
                init_three_test('three_viewport' );
                flag = 1;
            }


        });

        $('#sub_form').submit(function (e) {


            var json = $('#template').val();

            if(json.charAt(0) !== '{'){
                json = '{' + json + '}';
            }

            try{
                var data = JSON.parse(json);

                if(data.params === undefined){
                    data = {params:data}
                }

                // if(data.params.variants !== undefined){
                //     delete data.params.variants;
                // }


                $('#template').val(JSON.stringify(data));
                return true;
            } catch (e) {
                alert('<?php echo $lang_arr['json_syntax_error']?>')
            }

        })


    })

</script>