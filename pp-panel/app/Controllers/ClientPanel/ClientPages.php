<?php

declare(strict_types=1);

namespace App\Controllers\ClientPanel;

class ClientPages extends BaseController
{
    public function index(): string
    {
        return view("client-panel/home", ["page_title" => "Главная"]);
    }

    public function login()
    {
        if (auth()->loggedIn()) {
            return redirect()->to("client-panel");
        }

        return view("client-panel/login", ["page_title" => lang("AppClientPanel.pages.login")]);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->to("/");
    }
}
