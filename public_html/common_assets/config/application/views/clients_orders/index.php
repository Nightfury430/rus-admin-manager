<div id="blocker" style="
    position: fixed;
    z-index: 9999;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(52,52,52,0.42);
    display: none;
    align-items: center;
    justify-content: center;
">
    <div style="padding: 20px; font-size: 20px; background: #ffffff">
        Идет конвертация, пожалуйста подождите
    </div>

</div>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['clients_orders_heading']?></h2>
<!--        <a download href="http://194.87.99.226:88/projects/ak-stimul.ru/20.11.2022_21.25_dmazygula@mail.ru_79998154434.b3d">123</a>-->
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

<div class="wrapper wrapper-content  animated fadeInRight">


    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><?php echo $lang_arr['clients_orders_heading']?></h5>
                </div>
                <div class="ibox-content">
                    <div class="pagination">
		                <?php echo $pagination?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th><?php echo $lang_arr['order_number'] ?></th>
                                <th><?php echo $lang_arr['client_name'] ?></th>
                                <th><?php echo $lang_arr['email'] ?></th>
                                <th><?php echo $lang_arr['phone'] ?></th>
                                <th><?php echo $lang_arr['comments'] ?></th>
                                <th><?php echo $lang_arr['date'] ?></th>
					            <?php if ( $settings['price_enabled'] == 1 || $settings['shop_mode'] == 1 ): ?>
                                    <th><?php echo $lang_arr['price'] ?></th>
					            <?php endif; ?>

                                <th><?php echo $lang_arr['actions'] ?></th>
                            </tr>
                            </thead>
                            <tbody>

				            <?php foreach ( $items as $item ): ?>
                                <tr>
                                    <td>
							            <?php echo $item['number'] ?>
                                    </td>
                                    <td>
							            <?php echo $item['name'] ?>
                                    </td>
                                    <td>
							            <?php echo $item['email'] ?>
                                    </td>
                                    <td>
							            <?php echo $item['phone'] ?>
                                    </td>
                                    <td>
							            <?php echo $item['comments'] ?>
                                    </td>
                                    <td>
							            <?php echo $item['date'] ?>
                                    </td>
						            <?php if ( $settings['price_enabled'] == 1 || $settings['shop_mode'] == 1 ): ?>
                                        <td><?php echo $item['price'] ?></td>
						            <?php endif; ?>
                                    <!--                <td>-->
                                    <!--                    --><?php //echo $item['status']?>
                                    <!--                </td>-->
                                    <td>
                                        <div class="actions_list">
                                            <a title="<?php echo $lang_arr['download_project_file'] ?>"
                                               href="<?php echo $this->config->item( 'const_path' ) . 'clients_orders/' . $item['project_file'] ?>"
                                               download>
                                                <i class="fa fa-cloud-download text-navy"></i>
                                            </a>
                                            <a title="<?php echo $lang_arr['open_project_in_new_tab'] ?>"
                                               target="_blank"
                                               href="<?php echo $this->config->item( 'const_path' ) . '?co=' . $item['project_file'] ?>">
                                                <i class="fa fa-folder-open text-navy"></i>
                                            </a>
                                            <?php if ($this->config->item('akst')): ?>

                                                <a class="get_bazis_proj" title="Получить проект Базис"
                                                   data-account="<?php echo basename($this->config->item( 'const_path' )) ?>"
                                                   data-name="<?php echo $item['project_file']?>"
                                                   href="<?php echo $this->config->item( 'const_path' ) . 'clients_orders/' . $item['project_file'] ?>">
                                                    <i class="fa fa-cubes"></i>
                                                </a>

                                            <?php endif;?>
                                            <!--                                            <a title="--><?php //echo $lang_arr['export_to_obj'] ?><!--" target="_blank"-->
                                            <!--                                               href="--><?php //echo $this->config->item( 'const_path' ) . '?co=' . $item['project_file'] . '&save_to_obj=1' ?><!--">-->
                                            <!--                                                <i class="fa fa-cubes text-navy"></i>-->
                                            <!--                                            </a>-->
                                            <a title="<?php echo $lang_arr['delete'] ?>" class="delete_button"
                                               href="<?php echo site_url( 'clients_orders/remove/' . $item['id'] ) ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

				            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
		                <?php echo $pagination?>
                    </div>
                </div>
            </div>
        </div>
    </div>











</div>

<script>
    $(document).ready(function () {

        $('.get_bazis_proj').click(async function (e) {

            $('#blocker').css('display', 'flex');

            e.preventDefault();
            let url = $(this).attr('href');
            let name = $(this).attr('data-name');
            let account = $(this).attr('data-account');
            let json = await promise_request(url)
            console.log(json);
            let data = new FormData();
            data.append('data', JSON.stringify(json))
            data.append('key', 'as239ks')
            data.append('name', name)
            data.append('account', account)

            let p_url = $('#ajax_base_url').val() + '/clients_orders/get_bazis';

            let res = await promise_request_post(p_url, data);
            console.log(res)

            $('#blocker').css('display', 'none');
            var link = document.createElement('a');
            link.style.display = 'none';
            document.body.appendChild(link);
            link.href = res.url;
            link.download = res.filename;
            link.click();
            link.remove();

        })


        function save(blob, filename) {
            var link = document.createElement('a');
            link.style.display = 'none';
            document.body.appendChild(link);
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
            link.remove();
        }

        function saveArrayBuffer(buffer, filename) {
            save(new Blob([buffer], {type: 'application/octet-stream'}), filename);
        }

        $('.delete_button').click(function (e) {
            e.preventDefault();

            let scope = $(this);

            swal({
                title: "<?php echo $lang_arr['are_u_sure']?>",
                text: $('#delete_confirm_message').html(),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: false
            }, function () {

                window.location.href = scope.attr('href');
            });
        })
    })
</script>

<style>




    .actions_list a{
        margin-right: 10px;
        margin-top: 0;
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
</style>