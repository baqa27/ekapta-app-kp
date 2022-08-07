<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Prodi;
use Illuminate\Http\Request;

class BagianController extends Controller
{
    public function index()
    {
        $prodis = Prodi::with(['bagians'])->get();
        return $prodis;
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi_id);
        $validatedData = $request->validate([
            'bagian' => 'required',
        ]);
        $bagian = new Bagian;
        $bagian->bagian = $validatedData['bagian'];
        $prodi->bagians()->save($bagian);
        return $prodi->bagians;
    }

    public function edit(Request $request)
    {
        $bagian = Bagian::findOrFail($request->id);
        return $bagian;
    }

    public function update(Request $request)
    {
        $bagian = Bagian::findOrFail($request->id);
        $validatedData = $request->validate([
            'bagian' => 'required',
        ]);
        $bagian->update($validatedData);
        return $bagian;
    }

    public function delete(Request $request)
    {
    }
}
