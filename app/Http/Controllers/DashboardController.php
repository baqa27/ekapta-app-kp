<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboardMahasiswa()
    {
        return view('pages.mahasiswa.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard'
        ]);
    }

    public function dashboardProdi()
    {
        return view('pages.prodi.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarProdi',
        ]);
    }

    public function dashboardAdmin()
    {
        return view('pages.admin.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarAdmin',
        ]);
    }
}
