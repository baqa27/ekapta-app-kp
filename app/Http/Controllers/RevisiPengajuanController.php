<?php

namespace App\Http\Controllers;

use App\Models\RevisiPengajuan;
use App\Http\Requests\StoreRevisiPengajuanRequest;
use App\Http\Requests\UpdateRevisiPengajuanRequest;

class RevisiPengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRevisiPengajuanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRevisiPengajuanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RevisiPengajuan  $revisiPengajuan
     * @return \Illuminate\Http\Response
     */
    public function show(RevisiPengajuan $revisiPengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RevisiPengajuan  $revisiPengajuan
     * @return \Illuminate\Http\Response
     */
    public function edit(RevisiPengajuan $revisiPengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRevisiPengajuanRequest  $request
     * @param  \App\Models\RevisiPengajuan  $revisiPengajuan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRevisiPengajuanRequest $request, RevisiPengajuan $revisiPengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RevisiPengajuan  $revisiPengajuan
     * @return \Illuminate\Http\Response
     */
    public function destroy(RevisiPengajuan $revisiPengajuan)
    {
        //
    }
}
