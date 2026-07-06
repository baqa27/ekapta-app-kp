<?php

namespace App\Http\Controllers\KP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('kp.pages.home.home', [
            'title' => 'Ekapta',
        ]);
    }

    public function login()
    {
        return view('kp.pages.home.login', [
            'title' => 'Login',
        ]);
    }
}

