<div class="col-sm-12 col-md-12" id="content">
    <h3><?php echo $lang_arr['comms_items_list']?></h3>


    <div class="row">
        <div class="col-xs-12">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
<!--            --><?php //echo $pagination?>
        </div>

    </div>
    <table class="table table-hover items_table">
        <thead>
        <tr>
            <th><?php echo $lang_arr['icon']?></th>
            <th><?php echo $lang_arr['name']?></th>
            <th><?php echo $lang_arr['category']?></th>
            <th class="col-xs-2"><?php echo $lang_arr['actions']?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($items as $item):?>
            <tr>
                <td>
                    <div class="item_image">
                        <?php if($item['icon'] != null): ?>
                            <img class="img-fluid" src="<?php echo $item['icon']?>" alt="">
                        <?php endif;?>
                    </div>
                </td>
                <td>
                    <?php echo $item['name']?>
                </td>
                <td>
                    <?php

                    $key = array_search($item['category'], array_column($categories, 'id'));

                    if($key === null){
                        echo $lang_arr['no'];
                    } else {
                        $cat = $categories[$key]['parent'];
                        if($cat == 0){
                            echo $categories[$key]['name'];
                        } else {
                            $key2 = array_search($cat, array_column($materials_categories, 'id'));
                            echo $materials_categories[$key2]['name'];
                            echo '/';
                            echo $categories[$key]['name'];
                        }
//                                print_r();

                    }
                    ?>
                </td>

                <td>
                    <div class="actions_list">
                        <a title="<?php echo $lang_arr['add_model_to_your_catalog']?>" class="add_item" href="<?php echo site_url('comms/add_item_from_catalog/' . $item['id']) ?>"><span class="glyphicon glyphicon-copy"></span></a>
                    </div>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
<!--        --><?php //echo $pagination?>
    </div>



</div>


<div class="modal_wrapper">
    <div class="modal_content">
        <span class="modal_close glyphicon glyphicon-remove"></span>
        <div class="col-xs-12">

            <div class="form-group">
                <label for="selected_material_facade"><?php echo $lang_arr['choose_category']?></label>
                <select class="form-control" >
                    <option value="0">--- <?php echo $lang_arr['choose_category']?> ---</option>
                    <?php foreach ($acc_categories as $val): ?>
                        <option  <?php if($val['parent'] == null || $val['parent'] == 0) echo 'style="font-weight:bold;"' ?> value="<?php echo $val['id']?>"><?php echo $val['name']?></option>
                    <?php endforeach;?>
                </select>

                <div class="copy_confirm btn btn-success"><?php echo $lang_arr['add']?></div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.delete_button').click(function (e) {
            e.preventDefault();
            if (confirm('<?php echo $lang_arr['delete_confirm_message']?>')) {
                window.location.href = $(this).attr('href');
            } else {

            }
        });

        $('.modal_content .modal_close').click(function () {
            $('.modal_wrapper').fadeOut();
            $('.copy_confirm').attr('data-href', '');
            $('.modal_wrapper').find('select').val(0)
        });

        $('.add_item').click(function (e) {
            e.preventDefault();
            $('.modal_wrapper').fadeIn();
            $('.copy_confirm').attr('data-href', $(this).attr('href'));

        });

        $('.copy_confirm').click(function () {

            if($('.modal_wrapper').find('select').val() != 0){
                $.ajax({
                    url: $(this).attr('data-href') + '/' + $('.modal_wrapper').find('select').val(),
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'post'
                }).done(function (msg) {
                    console.log(msg)

                    $('.modal_wrapper').fadeOut();
                    $('.copy_confirm').attr('data-href', '');
                    $('.modal_wrapper').find('select').val(0)
                    alert('<?php echo $lang_arr['add_model_success']?>')
                });
            } else {
                alert('<?php echo $lang_arr['choose_category']?>')
            }


        })

        // $('#category').selectize({
        //     create: false,
        //     render: {
        //         option: function (data, escape) {
        //             return "<div class='option " + data.class + "'>" + data.text + "</div>"
        //         }
        //     }
        // });
        //
        // $('#category').change(function () {
        //     var action = $('#filter_form').attr('action');
        //     action += $('#category').val() + '/' + $('#per_page').val();
        //     window.location.href = action;
        // });

        // $('#per_page').change(function () {
        //     var action = $('#filter_form').attr('action');
        //     action += $('#category').val() + '/' + $('#per_page').val()
        //     window.location.href = action;
        // })
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

    .copy_confirm{
        text-align: center;
        margin-top: 10px;
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