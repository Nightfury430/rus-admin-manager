<div class="col-sm-12 col-md-12" id="content">
    <h3>Список моделей фурнитуры</h3>


    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-default" href="<?php echo site_url('furniture/items_add/') ?>" role="button"><?php echo $lang_arr['add_model']?></a>
        </div>
    </div>

    <table class="table table-hover items_table">
        <thead>
        <tr>
            <th><?php echo $lang_arr['icon']?></th>
            <th><?php echo $lang_arr['name']?></th>
            <th><?php echo $lang_arr['code']?></th>
            <th><?php echo $lang_arr['category']?></th>
            <th><?php echo $lang_arr['price']?></th>
            <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($items as $item):?>
            <tr>
                <td>
                    <div class="item_image">
                        <?php if($item['icon'] != null): ?>
                            <img class="img-fluid" src="<?php echo $this->config->item('const_path') . $item['icon']?>" alt="">
                        <?php endif;?>
                    </div>
                </td>
                <td>
                    <?php echo $item['name']?>
                </td>
                <td>
                    <?php echo $item['code']?>
                </td>
                <td>
                    <?php echo $item['category']?>
                </td>
                <td>
                    <?php echo $item['price']?>
                </td>
                <td>
                    <div class="actions_list">
                        <a href="<?php echo site_url('furniture/items_edit/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                        <a class="delete_button" href="<?php echo site_url('furniture/items_remove/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
                    </div>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>



</div>

<script>
    $(document).ready(function () {
        $('.delete_button').click(function (e) {
            e.preventDefault();
            if (confirm('<?php echo $lang_arr['delete_confirm_message']?>')) {
                window.location.href = $(this).attr('href');
            } else {

            }
        })


    })
</script>

<style>
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