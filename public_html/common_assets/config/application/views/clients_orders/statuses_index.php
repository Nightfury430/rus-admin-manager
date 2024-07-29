<div class="col-sm-12 col-md-12" id="content">
    <h3>Список статусов заявок</h3>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Добавить статус
    </button>

    <ul class="cat_list">
        <?php foreach ($items as $cat): ?>
            <li>
                <div class="list_div">
                    <?php echo $cat['name']?>
                    <div class="actions_list">
                        <a href="<?php echo site_url('materials/categories_edit/' . $cat['id']) ?>"><span class="glyphicon glyphicon-edit"></span></a>
                        <?php if(!isset($cat['children'])):?>
                            <a class="delete_button" href="<?php echo site_url('materials/categories_remove/'. $cat['id']) ?>"><span class="glyphicon glyphicon-trash"></span></a>
                        <?php else:?>
                            <a class="delete_button disabled" href="#"><span class="glyphicon glyphicon-trash"></span></a>
                        <?php endif;?>
                    </div>
                </div>
            </li>

        <?php endforeach; ?>
    </ul>




    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Добавить статус</h4>
                </div>
                <div class="modal-body">
                    <label for="color"><?php echo $lang_arr['color']?></label>
                    <input type="text" class="form-control" name="color" id="color" value="#ffffff">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                    <button type="button" class="btn btn-success">Применить</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="/common_assets/libs/spectrum/spectrum.js" type="text/javascript"></script>

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

        $("#color").spectrum({
            color: "#ffffff",
            preferredFormat: "hex",
            cancelText: '<?php echo $lang_arr['cancel']?>',
            chooseText: '<?php echo $lang_arr['pick']?>',
            showInput: true,
            move: function(color) {
            },
            change: function(color) {
            }
        });
    })
</script>
