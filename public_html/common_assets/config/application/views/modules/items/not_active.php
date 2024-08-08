<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5><?php echo $lang_arr['not_active_modules_heading'] ?></h5>
                </div>
                <div class="card-body table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="col-xs-2"><?php echo $lang_arr['icon']?></th>
                                <th><?php echo $lang_arr['name']?></th>
                                <th class="col-xs-2"><?php echo $lang_arr['category']?></th>
                                <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($templates as $item): ?>
                                <tr>
                                    <td>
                                        <div class="item_image">
                                            <?php if ($item['icon'] != null): ?>
                                                <img class="img-fluid" src="<?php echo $item['icon'] ?>" alt="">
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $item['name'] ?>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control category_select" data-id="<?php echo $item['id']?>">
                                                <option value="<?php echo $top_category?>"><?php echo $lang_arr['no']?></option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="actions_list">
                                            <a class="add_button" data-cat="<?php echo $top_category?>" data-url="<?php echo site_url('modules/not_active_add/') ?>" data-id="<?php echo $item['id']?>" href="<?php echo site_url('modules/not_active_add/' . $item['id'] .'/'. $top_category .'/' . $top_category) ?>">
                                                <?php echo $lang_arr['add']?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<script>

    var top_cat = <?php echo $top_category ?>

    $(document).ready(function () {

        $('.add_button').click(function (e) {
            e.preventDefault();

            if($(this).attr('data-cat') == top_cat){
                if (confirm('<?php echo $lang_arr['no_module_category_warning']?>')) {
                    window.location.href = $(this).attr('href');
                } else {

                }
            } else {
                window.location.href = $(this).attr('href');
            }


        });

        $('.category_select').change(function () {
            var id = $(this).attr("data-id");
            var button = $('.add_button[data-id="'+ id +'"]');

            button.attr('href', button.attr('data-url') + id + '/' + $(this).val() + '/' + top_cat);
            button.attr('data-cat', $(this).val());
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