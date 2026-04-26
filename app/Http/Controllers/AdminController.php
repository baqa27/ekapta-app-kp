<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function account(){
        $admin = Auth::guard('admin')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'partials.sidebarAdmin',
            'admin' => $admin,
        ];

        return view('pages.admin.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $admin = Auth::guard('admin')->user();

        abort_unless($admin && (int) $admin->id === (int) $id, 403);

        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $updateData = [
            'nama' => $validatedData['nama'],
        ];

        if (!empty($validatedData['password'])) {
            $updateData['password'] = Hash::make($validatedData['password']);
        }

        $admin->forceFill($updateData)->save();
        Auth::guard('admin')->setUser($admin->fresh());

        return back()->with('success', 'Akun berhasil diubah');
    }
}
