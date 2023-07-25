<?php

namespace App\Http\Controllers;

use App\Models\BagBundelStock;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Yajra\DataTables\Facades\DataTables;

class BagBundelStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();
        return view('admin.bag.stock.bagBundelIndex',compact('settings'));
    }

    public function bagBundellingYajraDatatables(){

        $bagBundelStocks=BagBundelStock::with(['group','bagBrand'])
        ->get(['group_id','bag_brand_id','bundle_no','qty_pcs','qty_in_kg','average_weight']);
         return DataTables::of($bagBundelStocks)
          ->addIndexColumn()
            ->make(true);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BagBundelStock  $bagBundelStock
     * @return \Illuminate\Http\Response
     */
    public function show(BagBundelStock $bagBundelStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BagBundelStock  $bagBundelStock
     * @return \Illuminate\Http\Response
     */
    public function edit(BagBundelStock $bagBundelStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BagBundelStock  $bagBundelStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BagBundelStock $bagBundelStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BagBundelStock  $bagBundelStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(BagBundelStock $bagBundelStock)
    {
        //
    }
}
