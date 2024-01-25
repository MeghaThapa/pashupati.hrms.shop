<?php

namespace App\Http\Controllers;

use App\Models\BswLamPrintedFabricStock;
use Illuminate\Http\Request;

class BswLamPrintedFabricStockController extends Controller
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
            'bswfabEntry_id'=>'required',
            'printed_fabric_id'=>'required',
            'roll_no'=>'required',
            'gross_weight'=>'required',
            'net_weight'=>'required',
            'meter'=>'required',
            'average'=>'required',
            'gram'=>'required',
        ]);
        try{
        $bswLamPrintedFabricStock = new BswLamPrintedFabricStock();
        $bswLamPrintedFabricStock->bswfabEntry_id =$request->bswfabEntry_id;
        $bswLamPrintedFabricStock->printed_fabric_id  =$request->printed_fabric_id;
        $bswLamPrintedFabricStock->roll_no =$request->roll_no;
        $bswLamPrintedFabricStock->gross_weight =$request->gross_weight;
        $bswLamPrintedFabricStock->net_weight =$request->net_weight;
        $bswLamPrintedFabricStock->meter =$request->meter;
        $bswLamPrintedFabricStock->average =$request->average;
        $bswLamPrintedFabricStock->gram_weight =$request->gram;
        $bswLamPrintedFabricStock->save();

        }catch(Exception $e){

        }
    }

    public function printedLamFabData(Request $request){
        $bswLamPrintedFabricStocks=BswLamPrintedFabricStock::with('printedFabric:id,name')
        ->where('bswfabEntry_id',$request->bsw_lam_fab_for_printing_entry_id)
        ->get();
        $totalMeter = $bswLamPrintedFabricStocks->sum('meter');
        $totalNetWt= $bswLamPrintedFabricStocks->sum('net_weight');
        $data = [
        'bswLamPrintedFabricStocks' => $bswLamPrintedFabricStocks,
        'totalMeter' => $totalMeter,
        'totalNetWt' => $totalNetWt,
        ];
        return $data;
        // return $bswLamPrintedFabricStocks;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BswLamPrintedFabricStock  $bswLamPrintedFabricStock
     * @return \Illuminate\Http\Response
     */
    public function show(BswLamPrintedFabricStock $bswLamPrintedFabricStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BswLamPrintedFabricStock  $bswLamPrintedFabricStock
     * @return \Illuminate\Http\Response
     */
    public function edit(BswLamPrintedFabricStock $bswLamPrintedFabricStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BswLamPrintedFabricStock  $bswLamPrintedFabricStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BswLamPrintedFabricStock $bswLamPrintedFabricStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BswLamPrintedFabricStock  $bswLamPrintedFabricStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(BswLamPrintedFabricStock $bswLamPrintedFabricStock)
    {
        //
    }
}
