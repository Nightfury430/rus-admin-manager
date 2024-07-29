<?php

declare(strict_types=1);

namespace App\Libraries;

class Mail
{
    public function sendCreateEmail($data): bool
    {
        $to = $data["login"];
        $subject = "Создан аккаунт";
        $sendSuccess = false;

        $template = $this->createEmailTemplate($data);

        $sendSuccess = $this->sendMail($to, $subject, $template);
        $this->sendMail("info@planplace.ru", $subject, $template);

        app_log(LOG_APP, "Email has been sent: {0}", [log_array([
            "subject" => $subject,
            "to" => $data["login"],
            "success" => $sendSuccess,
        ])]);

        return $sendSuccess;
    }

    public function checkEmail($email): bool
    {
        if (!$email || gettype($email) !== "string" || !str_contains($email, "@")) {
            return false;
        }

        return true;
    }

    private function createEmailTemplate($data): string
    {
        $queryResult = db_admin()->table("tariffs")
            ->select("title")
            ->where("value", $data['tariff'])
            ->limit(1)
            ->get()->getResultArray();

        $tariffTitle = isset($queryResult[0]["title"]) ? $queryResult[0]["title"] : "PRO";

        $msg = '';
        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Добрый день.</span>';
        $msg .= '<br>';
        $msg .= '<br>';
        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Для Вас активирован аккаунт конструктора PlanPlace. ' .  '</span>';
        $msg .= '<br>';
        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Тарифный план: <span style="color: #5d64fe">' . $tariffTitle . '</span>. ' . 'Дата окончания тарифа: <span style="color: #5d64fe">' . $data['date_end'] . '</span></span>';
        $msg .= '<br>';
        $msg .= '<br>';
        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Ссылка на сцену конструктора: <a href="https://planplace.ru/clients/' . $data['folder'] . '"> https://planplace.ru/clients/' . $data['folder'] . '</a>'  . '</span>';
        $msg .= '<br>';
        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Ссылка для входа в ЛК: <a href="' . 'https://planplace.ru/clients/' . $data['folder'] . '/config' . '">https://planplace.ru/clients/' . $data['folder'] . '/config</a>' . '</span>';
        $msg .= '<br>';
        $msg .= '<br>';
        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Логин: ' . $data['login'] . '</span>';
        $msg .= '<br>';
        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Пароль: '  . 'Пароль будет сгенерирован автоматически при первом входе в личный кабинет и отправлен Вам на почту, указанную при регистрации аккаунта' . '</span>';
        $msg .= '<br>';
        $msg .= '<br>';

        if ($data['master']) {
            $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Для работы аккаунта необходимо выгрузить настройки на основном аккаунте' . '</span>';
            $msg .= '<br>';
            $msg .= '<br>';
        }

        $msg .= '<span style="font-family:Helvetica,Arial,sans-serif;">Пароль на скачивание PDF и деталировки в Excel вы можете установить в личном кабинете, в разделе "Настройки конструктора"' . '</span>';
        //		$msg.= '<br>';
        $msg .= '<br>';
        //		$msg.= 'Справочный центр: <a target="_blank" href="https://help.planplace.online">https://help.planplace.online</a>' . '<br>';
        //		$msg.= 'Служба поддержки: <a target="_blank" href="https://help.planplace.online/support_form">https://help.planplace.online/support_form</a>' . '<br>';

        $msg .= '<br>
<table cellspacing="0" cellpadding="0" border="0" style="border-collapse:separate;table-layout:fixed;overflow-wrap:break-word;word-wrap:break-word;word-break:break-word;max-width:310px;" emb-background-style width="310">
    <tbody>
    <tr>
        <td style="color:#555555;font-size:13px;height:75px;font-family:Helvetica,Arial,sans-serif;"><p style="margin:.1px;"><img src="https://planplace.ru/common_assets/images/logo_email.png" width="150" height="75" style="display:block;border:0px;" border="0" nosend="1" alt=""/></p><br></td>
    </tr>
    <tr>
        <td style="color:#555555;font-size:13px;mso-line-height-rule:exactly;line-height:20px;font-family:Helvetica,Arial,sans-serif;"><p style="margin:.1px;"><a href="https://planplace.online/#submenu:more" style="color:#00a8e2;text-decoration:none;" target="_blank">Официальный сайт</a></p></td>
    </tr>
    <tr>
        <td style="color:#555555;font-size:13px;mso-line-height-rule:exactly;line-height:20px;font-family:Helvetica,Arial,sans-serif;"><p style="margin:.1px;"><a href="https://help.planplace.online/" style="color:#00a8e2;text-decoration:none;" target="_blank">Справочный центр</a></p></td>
    </tr>
    <tr>
        <td style="color:#555555;font-size:13px;mso-line-height-rule:exactly;line-height:20px;font-family:Helvetica,Arial,sans-serif;"><p style="margin:.1px;"><a href="https://help.planplace.online/support_form" style="color:#00a8e2;text-decoration:none;" target="_blank">Заявка в службу поддержки</a></p></td>
    </tr>
    <tr>
        <td style="color:#555555;font-size:13px;height:32px;padding-top:10px;font-family:Helvetica,Arial,sans-serif;"><p style="margin:.1px;"><a href="https://vk.com/planplace" style="border:0px;display:inline-block;vertical-align:middle;" target="_blank"><img src="https://planplace.ru/common_assets/images/vk_email.png" width="35" height="32" style="display:inline-block;border:0px;" border="0" nosend="1" alt=""/></a><a href="https://t.me/planplace" target="_blank" style="display:inline-block;padding-right:4px;vertical-align:middle;"><img src="https://planplace.ru/common_assets/images/telegram_email.png" width="32" height="32" style="border:0px;display:inline-block;" border="0" nosend="1" alt="Telegram"/></a></p></td>
    </tr>
    </tbody>
</table>';

        return $msg;
    }

    private function sendMail($to, $subject, $msg): bool
    {
        /** @var \Config\Email */
        $emailConfig = config("Email");

        /** @var \CodeIgniter\Email\Email */
        $emailService = service("email");

        $emailService->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
        $emailService->setTo($to);

        $emailService->setSubject($subject);
        $emailService->setMessage($msg);

        return $emailService->send();
    }
}
