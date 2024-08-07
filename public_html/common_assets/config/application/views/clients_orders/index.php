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
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                <div class="card-header">
                    <h5><?php echo $lang_arr['clients_orders_heading']?></h5>
                </div>
                <div class="card-body">
                    <div class="pagination">
		                <?php echo $pagination?>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
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
                            <tbody class="table-border-bottom-0">

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
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" title="<?php echo $lang_arr['download_project_file'] ?>"
                                                href="<?php echo $this->config->item( 'const_path' ) . 'clients_orders/' . $item['project_file'] ?>"
                                                download>
                                                    <i class="fa fa-cloud-download text-navy"></i> Download
                                                </a>
                                                <a class="dropdown-item" title="<?php echo $lang_arr['open_project_in_new_tab'] ?>"
                                                target="_blank"
                                                href="<?php echo $this->config->item( 'const_path' ) . '?co=' . $item['project_file'] ?>">
                                                    <i class="fa fa-folder-open text-navy"></i>Open
                                                </a>
                                                <?php if ($this->config->item('akst')): ?>

                                                    <a class="dropdown-item" class="get_bazis_proj" title="Получить проект Базис"
                                                    data-account="<?php echo basename($this->config->item( 'const_path' )) ?>"
                                                    data-name="<?php echo $item['project_file']?>"
                                                    href="<?php echo $this->config->item( 'const_path' ) . 'clients_orders/' . $item['project_file'] ?>">
                                                        <i class="fa fa-cubes"></i>Basis Download
                                                    </a>

                                                <?php endif;?>
                                                <!--                                            <a title="--><?php //echo $lang_arr['export_to_obj'] ?><!--" target="_blank"-->
                                                <!--                                               href="--><?php //echo $this->config->item( 'const_path' ) . '?co=' . $item['project_file'] . '&save_to_obj=1' ?><!--">-->
                                                <!--                                                <i class="fa fa-cubes text-navy"></i>-->
                                                <!--                                            </a>-->
                                                <a class="dropdown-item" title="<?php echo $lang_arr['delete'] ?>" class="delete_button"
                                                href="<?php echo site_url( 'clients_orders/remove/' . $item['id'] ) ?>">
                                                    <i class="fa fa-trash"></i>Delete
                                                </a>
                                            </div>
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