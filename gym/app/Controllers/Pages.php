<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
}
