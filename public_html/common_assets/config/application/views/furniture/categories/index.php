<div class="col-sm-12 col-md-12" id="content">
    <h3>Список категорий фурнитуры</h3>



    <a class="btn btn-default" href="<?php echo site_url('furniture/categories_add/') ?>" role="button">Добавить категорию</a>


    <table class="table table-hover items_table">
        <thead>
        <tr>
            <th><?php echo $lang_arr['name']?></th>
            <th><?php echo $lang_arr['code']?></th>
            <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($items as $item):?>
            <tr>
                <td>
                    <?php echo $item['name']?>
                </td>
                <td>
                    <?php echo $item['code']?>
                </td>
                <td>
                    <div class="actions_list">
                        <a href="<?php echo site_url('furniture/categories_edit/' . $item['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                        <a class="delete_button" href="<?php echo site_url('furniture/categories_remove/'. $item['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
                    </div>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<style>

    .cat_list{
        margin-top: 20px;
        padding: 0;
        border-bottom: 1px solid #ddd;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }

    .cat_list li{
        display: block;
        position: relative;
        line-height: 40px;
    }

    .list_div:hover{
        background: #f5f5f5;
    }

    .cat_list > li .list_div{
        padding-left: 15px;
        font-weight: bold;
        position: relative;
    }

    .cat_list > li:after {
        content: "\00A0";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        border-top: 1px solid #ddd;
        z-index: 1;
        pointer-events: none;
    }

    .cat_list li > ul{
        padding: 0;
    }

    .cat_list li > ul > li .list_div{
        padding-left: 45px;
        font-weight: normal;
        position: relative;
    }

    .cat_list li > ul > li:after {
        content: "\00A0";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        border-top: 1px solid #ddd;
        z-index: 1;
        pointer-events: none;
    }

    .actions_list{
        position: absolute;
        right: 20px;
        top:0;
        bottom:0;
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

</style>

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