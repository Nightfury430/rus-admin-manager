<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="sk-spinner sk-spinner-wave">
                        <div class="sk-rect1"></div>
                        <div class="sk-rect2"></div>
                        <div class="sk-rect3"></div>
                        <div class="sk-rect4"></div>
                        <div class="sk-rect5"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12">
                            <a class="btn btn-w-m btn-primary" href="<?php echo site_url('module_sets/sets_add/') ?>" role="button"><?php echo $lang_arr['add_module_set']?></a>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered items_table">
                        <thead>
                        <tr>
                            <th><?php echo $lang_arr['name']?></th>
                            <th><?php echo $lang_arr['categories']?></th>
                            <th><?php echo $lang_arr['kitchen_modules_label']?></th>
                            <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                        </tr>
                        </thead>
                        <tbody>
	                    <?php foreach ($items as $item):?>
                            <tr>
                                <input class="set_id" type="hidden" value="<?php echo $item['id']?>">
                                <input class="set_empty" type="hidden" value="<?php echo $item['is_empty']?>">
                                <td class="set_name">
				                    <?php echo $item['name']?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('catalog/categories/module_sets/'.$item['id']) ?>"><?php echo $lang_arr['edit_categories']?></a>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('catalog/items/module_sets/'.$item['id']) ?>"><?php echo $lang_arr['edit_modules']?></a>
                                </td>
                                <td>
                                    <div class="actions_list">
                                        <a href="<?php echo site_url('module_sets/sets_edit/') . $item['id']?>" class="btn rounded-pill btn-icon btn-label-primary waves-effect">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        <a target="_blank" title="<?php echo $lang_arr['export_module_set_csv']?>" class="btn rounded-pill btn-icon btn-label-secondary waves-effect" href="<?php echo site_url('module_sets/ajax_export/') . $item['id']?>">
                                            <span class="glyphicon glyphicon-download"></span>
                                        </a>
                                        <a class="csv_import btn rounded-pill btn-icon btn-label-primary waves-effect" data-id="<?php echo $item['id']?>" title="<?php echo $lang_arr['import_module_set_csv']?>" href="<?php echo site_url('module_sets/ajax_import/') . $item['id']?>">
                                            <span class="glyphicon glyphicon-upload"></span>
                                        </a>

					                    <?php if( $item['is_empty'] == 1 ): ?>
                                            <a class="copy_from_another_set btn btn-icon rounded-pill btn-label-pinterest waves-effect" data-id="<?php echo $item['id']?>" title="<?php echo $lang_arr['copy_from_another_set_modules']?>" href="<?php echo site_url('module_sets/copy_set/')?>">
                                                <span class="glyphicon glyphicon-duplicate"></span>
                                            </a>
					                    <?php endif;?>

                                        <a class="delete_button btn rounded-pill btn-icon btn-label-secondary waves-effect" href="<?php echo site_url('module_sets/sets_remove/' . $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
                                    </div>
                                </td>
                            </tr>

	                    <?php endforeach; ?>
                        </tbody>
                    </table>
                    <input style="display: none" id="csv_file" type="file" accept=".csv"/>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal_wrapper">
    <div class="modal_content">
        <span class="modal_close glyphicon glyphicon-remove"></span>
        <div class="col-xs-12">
            <div class="form-group">
                <label for="selected_material_facade"><?php echo $lang_arr['copy_select_from_set']?></label>
                <select class="form-control" >
                    <option value="0">--- <?php echo $lang_arr['choose_set']?> ---</option>
                </select>

                <div class="copy_confirm btn btn-success"><?php echo $lang_arr['copy']?></div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        $('.delete_button').click(function (e) {
            e.preventDefault();

            let scope = $(this);

            Swal.fire({
                title: '<?php echo $lang_arr['are_u_sure']?>',
                text: $('#delete_confirm_message').html(),
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: false,
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                },
                buttonsStyling: false
                }).then(function (result) {
                if (result.value) {
                    window.location.href = scope.attr('href');
                }
            });
        });

        var href = 0;

        $('.csv_import').click(function (e) {
            e.preventDefault();
            href = $(this).attr('href');
            $('#csv_file').val('');
            $('#csv_file').click();
        });

        $('#csv_file').change(function () {
            $('.loader').show();
            console.log($(this).prop("files")[0]);
            if(confirm('<?php echo $lang_arr['upload_csv_data']?>')){
                var form_data = new FormData();
                form_data.append("file", $(this).prop("files")[0]);
                $.ajax({
                    url: href,
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function(data){
                        console.log(data)
                        $('.loader').hide();
                    }
                });
            }
        });


        $('.copy_from_another_set').click(function (e) {
            e.preventDefault();
            var modal = $('.modal_wrapper');
            modal.fadeIn();

            var scope = $(this);

            var select = modal.find('select');
            $('.copy_confirm').attr('data-id', $(this).attr('data-id'));
            $('.copy_confirm').attr('data-href', $(this).attr('href'));

            $('body').css('overflow', 'hidden');

            select.html('');
            select.append('<option value="0">--- <?php echo $lang_arr['choose_set']?> ---</option>');

            $('.items_table tbody tr').each(function () {
                var set_id = $(this).find('.set_id').val();
                var set_name = $(this).find('.set_name').html();
                var set_empty = $(this).find('.set_empty').val();
                if(set_id !== scope.attr('data-id') ){
                    if( parseInt(set_empty) !== 1 ){
                        select.append('<option value="'+set_id+'">'+ set_name +'</option>')
                    }
                }
            })
        });

        $('.copy_confirm').click(function () {
            var modal = $('.modal_wrapper');
            var select = modal.find('select');

            if( select.val() !== "0" ){
                var href = $(this).attr('data-href') + select.val() + '/' + $(this).attr('data-id');
                $(window).scrollTop(0);
                modal.hide();
                $('.ibox-content').toggleClass('sk-loading');
                $.ajax({
                    url: href,
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post'
                }).done(function (msg) {
                    location.reload();
                });
            }
        });

        $('.modal_content .modal_close').click(function () {
            $('.modal_wrapper').fadeOut();
        })

    })
</script>

<style>
    .modal_wrapper{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 220px;
        right: 0;
        background: rgba(0, 0, 0, 0.36);
        z-index: 10;
        display: none;
    }

    .modal_content{
        position: absolute;
        background: #ffffff;
        width: 450px;
        height: 200px;
        left: 0;
        right: 0;
        margin: 0 auto;
        top: 200px;
        padding: 50px;
    }

    .modal_content .modal_close{
        position: absolute;
        right: 5px;
        top: 5px;
        cursor: pointer;
        font-size: 20px;
    }

    .loader{
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 999;
        background: #ffffff;
        text-align: center;
        font-weight: bold;
        font-size: 30px;
    }

    .copy_confirm{
        margin-top: 20px;
        float: right;
    }

    .table.items_table>tbody>tr>td {
        vertical-align: middle;
    }

    .actions_list a{
        margin-right: 10px;
        margin-top: 12px;
        display: inline-block;
        font-size: 16px;
    }

    .actions_list a.delete_button{
        color: #ff0000;
    }

    .actions_list a.delete_button.disabled{
        color: #9d9d9d;
        pointer-events: none;
    }

    .actions_list a.is_visible{
        color: #0e7b0d;
        pointer-events: none;
    }

    .actions_list a.is_visible.disabled{
        color: #9d9d9d;
    }

    .item_image{
        max-width: 100px;
        height: auto;
        border: 1px solid;
    }

    .item_image > div{
        width: 100%;
        height: 100%;
    }

    .option.top_cat{
        font-weight: bold;
    }

    .option.sub_cat{
        padding-left: 40px;
    }

    .sp-input{
        background: #ffffff;
    }
</style>