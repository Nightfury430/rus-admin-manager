<?php

declare(strict_types=1);

namespace App\Controllers\AdminPanel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AdminPages extends BaseController
{
    public function clients(): string
    {
        return view("admin-panel/tables/clients");
    }

    public function users(): string
    {
        return view("admin-panel/tables/users");
    }

    public function partners(): string
    {
        return view("admin-panel/tables/partners");
    }

    public function tariffs(): string
    {
        return view("admin-panel/tables/tariffs");
    }

    public function services(): string
    {
        return view("admin-panel/tables/services");
    }

    public function info(): string
    {
        return view("admin-panel/info");
    }

    public function init(): string
    {
        if (file_exists(WRITEPATH . "app/su_created")) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view("admin-panel/init");
    }

    public function login()
    {
        if (auth()->loggedIn()) {
            return redirect()->route('admin-panel');
        }

        return view("admin-panel/login");
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('/');
    }
}
