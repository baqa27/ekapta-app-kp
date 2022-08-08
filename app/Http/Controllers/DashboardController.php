<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
