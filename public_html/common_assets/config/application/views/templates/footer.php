<div class="footer fixed">
    <div class="float-right">
    </div>
    <div class="hidden">
        <span>{elapsed_time}</span>
        <span>{memory_usage}</span>
    </div>

</div>


<div class="modal inmodal" id="backup_bd_modal" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Резервная копия БД</h4>
            </div>
            <div class="modal-body">
                <div class="m_content">

                </div>
                <div class="buttons">
                    <button id="make_backup" type="button" class="btn btn-outline btn-primary">Сделать резервную копию</button>
                    <button id="restore_backup" type="button" class="btn btn-outline btn-info">Восстановить резервную копию</button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['ok']?></button>
            </div>
        </div>
    </div>
</div>



<div class="modal inmodal" id="change_password_modal" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?php echo $lang_arr['change_password']?></h4>
            </div>
            <div class="modal-body">

                    <div class="row form-group">
                        <label class="col-sm-12"><?php echo $lang_arr['old_password']?></label>
                        <div class="col-sm-12">
                            <input type="text" id="password_change_old" class="form-control">
                            <span id="password_old_error" style="opacity: 0" class="form-text m-b-none text-danger"></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-sm-12"><?php echo $lang_arr['new_password']?></label>
                        <div class="col-sm-12">
                            <input type="text" id="password_change_new" class="form-control">
                            <span id="password_new_error" style="opacity: 0" class="form-text m-b-none text-danger"></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <span id="password_same_error" style="opacity: 0" class="form-text m-b-none text-danger"></span>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                <button type="button" class="btn btn-primary" id="run_password_change"><?php echo $lang_arr['apply']?></button>
            </div>
        </div>
    </div>
</div>



<div class="modal inmodal " id="export_settings_errors" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?php echo $lang_arr['error']?></h4>
            </div>
            <div class="modal-body export_settings_errors_list">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $lang_arr['ok']?></button>
            </div>
        </div>
    </div>
</div>



<div class="modal inmodal " id="clear_settings_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?php echo $lang_arr['error']?></h4>
            </div>
            <div class="modal-body export_settings_errors_list">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $lang_arr['ok']?></button>
            </div>
        </div>
    </div>
</div>


</div>
</div>





<script src="/common_assets/libs/selectize/selectize.min.js" type="text/javascript"></script>
<script src="/common_assets/libs/lodash.js" type="text/javascript"></script>
<script src="/common_assets/admin_js/production/modal.js" type="text/javascript"></script>
<script src="/common_assets/libs/axios.min.js"></script>



<!-- Custom and plugin javascript -->
<script src="/common_assets/theme/js/inspinia.js"></script>
<script src="/common_assets/theme/js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI -->
<script src="/common_assets/theme/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="/common_assets/theme/js/plugins/gritter/jquery.gritter.min.js"></script>

<!-- Sparkline -->
<script src="/common_assets/theme/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- Mainly scripts -->
<script src="/common_assets/theme/js/popper.min.js"></script>
<script src="/common_assets/theme/js/bootstrap.js"></script>
<script src="/common_assets/theme/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/common_assets/theme/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Sweet alert -->
<script src="/common_assets/theme/js/plugins/sweetalert/sweetalert.min.js"></script>

<!-- Toastr -->
<script src="/common_assets/theme/js/plugins/toastr/toastr.min.js"></script>
<!--<script src="/common_assets/theme/js/plugins/summernote/summernote-bs4.min.js"></script>-->

<!-- Switchery -->
<script src="/common_assets/theme/js/plugins/switchery/switchery.js"></script>
<!-- Jasny -->
<script src="/common_assets/theme/js/plugins/jasny/jasny-bootstrap.min.js"></script>
<script src="/common_assets/theme/js/popper.min.js"></script>
<script src="/common_assets/theme/js/tippy.min.js"></script>

<link href="/common_assets/theme/css/plugins/footable/footable.bootstrap.min.css" rel="stylesheet">
<!--<link href="/common_assets/theme/css/plugins/footable/footable.core.css" rel="stylesheet">-->
<!--<script src="/common_assets/theme/js/plugins/footable/footable.all.min_old.js"></script>-->
<script src="/common_assets/theme/js/plugins/footable/footable.min.js"></script>
<script src="/common_assets/admin_js/common_functions.js"></script>

<!--</div>-->
<!--</div>-->
<!--</div>-->
<div class="hidden">
    <div id="delete_confirm_message"><?php echo $lang_arr['delete_confirm_message']?></div>
    <div id="change_confirm_message"><?php echo $lang_arr['change_confirm_message']?></div>
    <div id="success_message"><?php echo $lang_arr['success']?></div>
    <div id="dealer_error_no_model_message"><?php echo $lang_arr['dealer_error_no_model']?></div>
    <div id="kitchen_models_message"><?php echo $lang_arr['kitchen_models']?></div>
    <div id="must_be_power_of_two_message"><?php echo $lang_arr['must_be_power_of_two']?></div>
    <div id="are_u_sure_message"><?php echo $lang_arr['are_u_sure']?></div>
    <div id="lang_no_message"><?php echo $lang_arr['no']?></div>
    <div id="lang_yes_message"><?php echo $lang_arr['yes']?></div>
    <div id="lang_incorrect_chars_in_file_name"><?php echo $lang_arr['incorrect_chars_in_file_name']?></div>
    <div id="lang_no_name"><?php echo $lang_arr['no_name']?></div>
    <div id="lang_save_warning"><?php echo $lang_arr['save_warning']?></div>
</div>

<input type="hidden" id="ajax_base_url" value="<?php echo site_url()?>">
<input type="hidden" id="acc_base_url" value="<?php echo$this->config->item('const_path')?>">
<input type="hidden" id="current_language" value="<?php echo $this->config->item('ini')['language']['language']?>">
<input type="hidden" id="ini_json" value='<?php echo htmlspecialchars(json_encode($this->config->item('ini')),ENT_QUOTES)?>'>
<input type="hidden" id="lang_json" value='<?php echo htmlspecialchars(json_encode($lang_arr),ENT_QUOTES)?>'>
<?php if(isset($controller_name)):?>
    <input type="hidden" id="footer_controller_name" value='<?php echo $controller_name?>'>
<?php endif;?>

<?php if(isset($common)):?>
    <input type="hidden" id="footer_is_common" value='<?php echo $common?>'>
<?php endif;?>





<script>

    $(document).ready(function () {



        $('#backup_bd_modal').on('show.bs.modal', async function (e) {
            let body = $('#backup_bd_modal .m_content')

            let no_backup = 0;

            update_backup_info();

            $('#make_backup').off().click(async function () {
                if(no_backup == 1){
                    let res = await promise_request( $('#ajax_base_url').val() + '/settings/make_db_backup')
                    if(res == 'success'){
                        toastr.success('Резервная копия сохранена')
                        update_backup_info();
                    } else {
                        toastr.error('Произошла ошибка')
                    }

                } else {
                    if (confirm('Вы уверены что хотите перезаписать резервную копию')) {
                        let res = await promise_request( $('#ajax_base_url').val() + '/settings/make_db_backup')
                        if(res == 'success'){
                            toastr.success('Резервная копия обновлена')
                            update_backup_info();
                        } else {
                            toastr.error('Произошла ошибка')
                        }
                    }
                }



            })

            $('#restore_backup').off().click(async function () {
                if (confirm('Вы уверены что хотите восстановить резервную копию')) {
                    let res = await promise_request( $('#ajax_base_url').val() + '/settings/restore_db_backup')
                    if(res == 'success'){
                        toastr.success('Резервная копия восстановлена')
                    } else {
                        toastr.error('Произошла ошибка')
                    }

                }
            })

            async function update_backup_info(){
                let res = await promise_request( $('#ajax_base_url').val() + '/settings/get_backup_info')
                console.log(res)
                if(res == 'no_backup'){
                    no_backup = 1;
                    body.html('<p>Резервная копия: не создана</p>');
                    $('#restore_backup').hide();
                } else {
                    body.html('<p>Резервная копия: '+ res +'</p>');
                    $('#restore_backup').show();
                }
            }

        })




        document.getElementById('run_password_change').addEventListener('click', function () {
            let old_pass = document.getElementById('password_change_old')
            let new_pass = document.getElementById('password_change_new')

            let old_pass_error = document.getElementById('password_old_error');
            let new_pass_error = document.getElementById('password_new_error');
            let password_same_error = document.getElementById('password_same_error');




            old_pass.addEventListener('input', function () {
                old_pass_error.style.opacity = 0;
                password_same_error.style.opacity = 0;
            })
            new_pass.addEventListener('input', function () {
                new_pass_error.style.opacity = 0;
                password_same_error.style.opacity = 0;
            })


            let errors = 0;

            if(!check_foldername(old_pass.value)){
                old_pass_error.innerHTML = "<?php echo $lang_arr['error_wrong_password']?>"
                old_pass_error.style.opacity = 1;
                errors++;
            }

            if(!check_foldername(new_pass.value)){
                new_pass_error.innerHTML = "<?php echo $lang_arr['error_wrong_password']?>"
                new_pass_error.style.opacity = 1;
                errors++
            }



            if(errors > 0){
                return;
            }

            if(old_pass.value == new_pass.value){
                password_same_error.innerHTML = "<?php echo $lang_arr['error_same_password']?>"
                password_same_error.style.opacity = 1;
            }


            let f_data = new FormData();
            f_data.append('old_pass', old_pass.value)
            f_data.append('new_pass', new_pass.value)

            promise_request_post( $('#ajax_base_url').val() + '/settings/change_password', f_data ).then(function (response) {
                console.log(response)

                if(response == 'old_pass_wrong'){
                    password_same_error.innerHTML = "<?php echo $lang_arr['old_pass_wrong']?>"
                    password_same_error.style.opacity = 1;
                }

                if(response == 'success'){
                    $('#change_password_modal').modal('hide')
                }

            })



            // $('#change_password_modal').modal('hide')
        })



        $('.constructor_change').click(function (e) {
            let scope = $(this);
            e.preventDefault();
            console.log({constructor:scope.attr('data-constructor')})
            $.ajax({
                //url: "<?php //echo base_url('index.php/login/change_constructor')?>//",
                url: $('#ajax_base_url').val() + '/login/change_constructor',
                data: {constructor:scope.attr('data-constructor')},
                type: 'post',
                // processData: false,
                // contentType: false,
                // dataType: "json",
            }).done(function(msg) {
                location.href = msg;
            });

        });


        $('#save_settings').click(function (e) {
            e.preventDefault();

            swal({
                title: "<?php echo $lang_arr['are_u_sure']?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: true
            }, function () {

                $.ajax({
                    url: "<?php echo base_url('index.php/save_data')?>",
                    data: {save:'save'},
                    type: 'post'
                }).done(function(msg) {
                    console.log(msg)

                    let obj = JSON.parse( msg );

                    console.log(obj)

                    if(obj.errors){

                        let errs = $('.export_settings_errors_list');
                        errs.html('');
                        let ul = $('<ul></ul>');
                        for (let i = 0; i < obj.errors.length; i++){
                            ul.append('<li>'+ obj.errors[i] +'</li>')
                        }
                        errs.append(ul);

                        $('#export_settings_errors').modal('show');

                        return false;
                    }

                    if(obj.success){
                        toastr.success('<?php echo $lang_arr['success']?>');

                        return false;
                    }


                });

            });


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

                toastr.success('<?php echo $lang_arr['success']?>');


                return false;


            }).catch(function () {
                alert('Unknown error')
            });




        });

        $('#logout').click(function (e) {
            e.preventDefault();

            let scope = $(this);

            swal({
                title: "<?php echo $lang_arr['are_u_sure']?>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: false
            }, function () {
                window.location.href = scope.attr('href');
            });
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


    });




    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "preventDuplicates": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

<script>

    let event = new Event('Glob_ready');
    let glob = '';

    $(document).ready(function () {

        $('.help_span').each(function () {
            tippy($(this)[0], {
                // content: document.getElementById('content_account_email').innerHTML,
                content: $('#help_messages').find('[data-target="'+ $(this).attr('data-id') +'"]').html(),
                theme:'light',
                placement: 'bottom-start',
                maxWidth:500,
                trigger: 'click',
                interactive: true,
            })
        })

        glob = get_base_data();

        document.dispatchEvent(event);

    });



</script>

<div class="hidden" id="help_messages">


    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/common_assets/config/help_messages.php'?>


</div>

</body>
</html>