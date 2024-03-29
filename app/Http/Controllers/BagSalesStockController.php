<?php

namespace App\Http\Controllers;

use App\Models\BagSalesStock;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use Yajra\DataTables\Facades\DataTables;

class BagSalesStockController extends Controller
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
        return view('admin.bag.stock.salesIndex',compact('settings'));
    }

    public function bagSalesStockYajraDatatables(){
          $bagSalesStocks=BagSalesStock::with(['group','brandBag'])
            ->get(['group_id','brand_bag_id','bundel_no','pcs','weight','average']);
            return DataTables::of($bagSalesStocks)
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
     * @param  \App\Models\BagSalesStock  $bagSalesStock
     * @return \Illuminate\Http\Response
     */
    public function show(BagSalesStock $bagSalesStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BagSalesStock  $bagSalesStock
     * @return \Illuminate\Http\Response
     */
    public function edit(BagSalesStock $bagSalesStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BagSalesStock  $bagSalesStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BagSalesStock $bagSalesStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BagSalesStock  $bagSalesStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(BagSalesStock $bagSalesStock)
    {
        //
    }
}
