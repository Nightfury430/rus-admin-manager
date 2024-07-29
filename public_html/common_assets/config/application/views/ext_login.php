<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в личный кабинет PlanPlace</title>
    <link rel="shortcut icon" href="/common_assets/images/favicon_scene.ico" type="image/x-icon">
    <style>

        @font-face {
            font-family: 'Roboto';
            src: url('/common_assets/fonts/roboto/Roboto-Regular.ttf');
        }

        @font-face {
            font-family: 'Roboto';
            src: url('/common_assets/fonts/roboto/Roboto-Thin.ttf');
            font-weight: 200;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('/common_assets/fonts/roboto/Roboto-Light.ttf');
            font-weight: 300;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('/common_assets/fonts/roboto/Roboto-Medium.ttf');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Roboto';
            src: url('/common_assets/fonts/roboto/Roboto-Bold.ttf');
            font-weight: 600;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 14px;
            margin: 0;
            font-family: 'Roboto', Arial, sans-serif;
        }
        form {
            max-width: 350px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        input {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            box-sizing: border-box;
            color: rgb(0, 0, 0);
            border: 1px solid rgb(206, 206, 206);
            background-color: rgb(255, 255, 255);
            border-radius: 4px;
            font-weight: 400;
            height: 30px;

        }
        button {
            width: 100%;
            padding: 8px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-image: linear-gradient(0.403turn, rgba(94, 101, 254, 1) 0%, rgba(0, 162, 255, 1) 100%);
        }
        p {
            font-size: 11px;
            color: #888888;
        }

        h1{
            text-align: center;
            margin-bottom: 5px;
        }

        .logo {
            margin: 0 auto 20px auto;
        }

    </style>
</head>
<body>
<form>
    <h1>Вход в личный кабинет</h1>
    <div>
        <img class="logo" src="https://planplace.ru/common_assets/images/logo_email.png" width="150" height="75" style="display:block;border:0;" alt=""/>
    </div>
    <input id="login" type="text" placeholder="Логин">
    <input id="pass" type="password" placeholder="Пароль">
    <button id="send_form" type="submit">Войти</button>
    <p>Доступ в ЛК конструктора не ограничен одним пользователем (администратором). Можно осуществлять настройки параллельно с коллегами.</p>
    <div>
        <table  style="border-collapse:separate;table-layout:fixed;overflow-wrap:break-word;word-wrap:break-word;word-break:break-word;max-width:310px;" >
            <tbody>
            <tr>
                <td style="color:#555555;font-size:13px;mso-line-height-rule:exactly;line-height:20px;font-family:Helvetica,Arial,sans-serif;"><p style="margin:0;"><a href="https://planplace.online/" style="color:#00a8e2;text-decoration:none;" target="_blank">Официальный сайт</a></p></td>
            </tr>
            <tr>
                <td style="color:#555555;font-size:13px;mso-line-height-rule:exactly;line-height:20px;font-family:Helvetica,Arial,sans-serif;"><p style="margin: 0;"><a href="https://help.planplace.online/" style="color:#00a8e2;text-decoration:none;" target="_blank">Справочный центр</a></p></td>
            </tr>
            <tr>
                <td style="color:#555555;font-size:13px;mso-line-height-rule:exactly;line-height:20px;font-family:Helvetica,Arial,sans-serif;"><p style="margin: 0;"><a href="https://help.planplace.online/support_form" style="color:#00a8e2;text-decoration:none;" target="_blank">Заявка в службу поддержки</a></p></td>
            </tr>
            <tr>
                <td style="color:#555555;font-size:13px;height:32px;padding-top:10px;font-family:Helvetica,Arial,sans-serif;">
                    <p style="margin: 0;">
                        <a href="https://vk.com/planplace" style="border:0;display:inline-block;vertical-align:middle;" target="_blank">
                            <img src="https://planplace.ru/common_assets/images/vk_email.png" width="35" height="32" style="display:inline-block;border:0;"   alt=""/>
                        </a>
                        <a href="https://t.me/planplace" target="_blank" style="display:inline-block;padding-right:4px;vertical-align:middle;">
                            <img src="https://planplace.ru/common_assets/images/telegram_email.png" width="32" height="32" style="border:0;display:inline-block;"   alt="Telegram"/>
                        </a>
                        <a href="https://tenchat.ru/planplace?utm_source=14515359-3d00-4e21-aa8b-e6cf238437ec" target="_blank" style="display:inline-block;padding-right:4px;vertical-align:middle;">
                            <img src="https://planplace.ru/common_assets/images/ten_chat.png" width="32" height="32" style="border:0;display:inline-block;"   alt="Telegram"/>
                        </a>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</form>

<script>
    let btn = document.getElementById('send_form');
    btn.addEventListener('click', async (e)=>{
        e.preventDefault();

        let data = new FormData();
        data.append('login', document.getElementById('login').value)
        data.append('pass', document.getElementById('pass').value)
        let res = await promise_request_post_raw('/login/index.php', data)
        console.log(res)
        console.log(res.responseText)
        if(res.responseText == 'error'){

        } else {
            console.log(res.responseText)
            // location.href= res.responseText;
        }
        return false;
    })

    function promise_request_post_raw(url, data) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", url);
        xhr.send(data);

        return new Promise(function (resolve, reject) {
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    try {
                        resolve(xhr);
                    } catch (e) {
                        console.log(e);
                    }
                } else if (xhr.readyState == 4) {
                    reject();
                }
            };
        });
    }
</script>

</body>
</html>