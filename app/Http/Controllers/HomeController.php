<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home.home', [
            'title' => 'Ekapta',
        ]);
    }

    public function login()
    {
        return view('pages.home.login', [
            'title' => 'Login',
        ]);
    }
}
