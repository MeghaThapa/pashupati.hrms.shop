<?php

namespace App\Http\Controllers;

use App\Models\PrintedFabric;
use Illuminate\Http\Request;

class PrintedFabricController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
        'name' =>'required|unique:printed_fabrics,name',
       ]);
       $printedFabric= new PrintedFabric();
       $printedFabric->name = $request->name;
       $printedFabric->status = $request->status;
       $printedFabric->save();
       return $printedFabric;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\printedFabric  $printedFabric
     * @return \Illuminate\Http\Response
     */
    public function show(printedFabric $printedFabric)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\printedFabric  $printedFabric
     * @return \Illuminate\Http\Response
     */
    public function edit(printedFabric $printedFabric)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\printedFabric  $printedFabric
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, printedFabric $printedFabric)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\printedFabric  $printedFabric
     * @return \Illuminate\Http\Response
     */
    public function destroy(printedFabric $printedFabric)
    {
        //
    }
}
