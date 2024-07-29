<?php

declare(strict_types=1);

namespace App\Controllers\ClientPanel;

class HomeController extends BaseController
{
    public function index(): string
    {
        return view("home", ["page_title" => "Главная"]);
    }
}
