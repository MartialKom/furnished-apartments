<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }
    public function apartments(): string
    {
        return view('welcome_message');
    }

}
