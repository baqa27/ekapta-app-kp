<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{
    public function index()
    {
        $seminars = Seminar::all();
        return $seminars;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_pembuatan_ta' => 'required',
            'tanggal_acc_pembimbing_utama'  => 'required',
            'tanggal_acc_pembimbing_pendamping'  => 'required',
            'lampiran_1'  => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg'],
            'link_video' => 'required',
        ]);
        $validatedData['nim'] = Auth::guard('mahasiswa')->user()->nim;
        Seminar::create($validatedData);
        return $validatedData;
    }

    public function edit($seminar)
    {
        $seminar = Seminar::findOrFail($seminar);
        return $seminar;
    }

    public function update(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $validatedData = $request->validate([
            'tanggal_pembuatan_ta' => 'required',
            'tanggal_acc_pembimbing_utama'  => 'required',
            'tanggal_acc_pembimbing_pendamping'  => 'required',
            'lampiran_1'  => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg'],
            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg'],
            'link_video' => 'required',
        ]);
        $seminar->update($validatedData);
        return $seminar;
    }

    public function delete(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->delete();
        return 'Seminar has been deleted';
    }

    public function accSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->update([]);
        return $seminar;
    }
}