<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="/common_assets/libs/bootstrap/bootstrap_w_theme.min.css">


    <script src="/common_assets/libs/jquery_1.12.5.js" type="text/javascript"></script>
    <script src="/common_assets/libs/bootstrap/bootstrap.min.js" type="text/javascript"></script>


</head>
<body>



<div class="container">


	<?php if($reset_pwd == 1 ): ?>
        <form class="form-signin" method="post" accept-charset="UTF-8" action="<?php echo site_url('login/generate_pwd') ?>">
            <h2 class="form-signin-heading">Вход</h2>

            <div class="alert alert-info" role="alert">
                <p>
                    <?php echo $lang_arr['login_password_generate_notice']?> <?php echo $email?>
                </p>
            </div>

            <div class="form-group hidden">
                <label for="email_type">Выберите почту, на которую будет отправлен новый пароль</label>
                <select class="form-control" name="email_type" id="email_type">
                    <option selected value="1">Email указанный при регистрации</option>
                    <!--                    <option value="2">Email для заявок, указанный в личном кабинете</option>-->
                </select>
            </div>


            <div class="form-group text-right">
                <button class="btn btn-md btn-primary login_btn" type="submit"><?php echo $lang_arr['login_password_generate_send_password']?></button>
            </div>
        </form>



	<?php else:?>
        <?php
        $action = site_url('login');

        if($get_through == 1) $action = site_url('login') . '?npr=a74lmFhy'

        ?>




        <form class="form-signin" method="post" accept-charset="UTF-8" action="<?php echo $action ?>">



            <h2 class="form-signin-heading"><?php echo $lang_arr['login_header']?></h2>

			<?php if(isset($login_message)):?>
                <div class="alert alert-success" role="alert">
                    <p><?php echo $login_message?></p>
                </div>
			<?php endif;?>

            <div class="form-group">
                <label for="login" class="sr-only"><?php echo $lang_arr['login']?></label>
                <input  type="text" name="login" id="login" class="form-control" <?php if ($this->config->item('const_path') == 'https://broskokitchenplanner.com/clients/test/') echo 'value ="bpadmin"' ?> placeholder="<?php echo $lang_arr['login']?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="sr-only"><?php echo $lang_arr['password']?></label>
                <input type="password" <?php if ($this->config->item('const_path') == 'https://broskokitchenplanner.com/clients/test/') echo 'value ="bpadmin"' ?> name="password" id="inputPassword" class="form-control" placeholder="<?php echo $lang_arr['password']?>" required>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-md btn-primary login_btn" type="submit"><?php echo $lang_arr['login_button']?></button>
            </div>
        </form>
	<?php endif;?>


</div>

<style>
    .form-signin{
        max-width: 400px;
        margin: 0 auto;
    }



</style>
</body>
</html>

