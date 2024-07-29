<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['rendering']?></h2>
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


<style>

</style>

<div class="wrapper wrapper-content  animated fadeInRight">


        <div class="row">
            <div class="col-lg-12">

                <div class="ibox ">
                    <div class="ibox-content">

                        <div class="form-group row">
                            <label class="col-sm-1 col-form-label"><?php echo $lang_arr['password']?></label>
                            <div class="col-sm-2">
                                <input id="pwd" class="form-control" placeholder="********" type="password">
                            </div>
                            <div class="col-sm-4 col-sm-offset-2"><button id="save_password" type="button" class="btn btn-primary btn-sm"><?php echo $lang_arr['save']?></button></div>
                        </div>

                    </div>
                </div>


                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?php echo $lang_arr['rendering_history'] ?></h5>
                    </div>
                    <div class="ibox-content">

                        <div class="pagination">
                            <?php echo $pagination?>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th><?php echo $lang_arr['result'] ?></th>
                                    <th><?php echo $lang_arr['date'] ?></th>
                                    <th><?php echo $lang_arr['time'] ?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php foreach ( $items as $item ): ?>
                                    <tr>
                                        <td>
                                            <a title="<?php echo $lang_arr['open_project_in_new_tab'] ?>"
                                               target="_blank"
                                               href="<?php echo $item['result'] ?>">
                                                <img style="max-width: 600px; height: auto" src="<?php echo $item['result'] ?>" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo date("d.m.Y H:i:s", $item['date']);  ?>
                                        </td>
                                        <td>
                                            <?php echo gmdate("H:i:s", $item['time']) ?>
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
        $('#save_password').click(function () {
            if($('#pwd').val() == '********' || $('#pwd').val().length < 5){
                toastr.error('<?php echo $lang_arr['rendering_wrong_password']?>');
                return;
            }

            $.ajax({
                url: "<?php echo base_url('index.php/rendering/set_password')?>",
                data: {password: $('#pwd').val()},
                type: 'post'
            }).done(function () {
                toastr.success('<?php echo $lang_arr['success']?>');
            });
        });
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



