<?php

namespace App\Http\Controllers;

use App\Models\BswLamFabForPrintingEntry;
use App\Models\BswLamPrintedFabricStock;
use App\Models\BswSentLamFab;
use App\Models\Wastages;
use App\Models\WasteStock;
use Illuminate\Http\Request;
use DB;

class BswLamFabForPrintingEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveEntire(Request $request)
    {
        try{
        DB::beginTransaction();
        $bswLamFabForPrintingEntry =BswLamFabForPrintingEntry::find($request->bswfabEntry_id);
        $bswLamFabForPrintingEntry->trimming_wst = $request->trimmimg_wastage;
        $bswLamFabForPrintingEntry->fabric_wst = $request->fabric_waste;
        $bswLamFabForPrintingEntry->status= 'completed';
        $bswLamFabForPrintingEntry->save();

        //saving waste
        $wastage_id=Wastages::where('name','rafia')->first()->id;
        $wasteStock = WasteStock::where('godam_id',$request->wastage_godam_id)->where('waste_id', $wastage_id)->first();
        if($wasteStock!== null){
            $wasteStock->quantity_in_kg = floatval($request->trimmimg_wastage) + floatval($request->fabric_waste);
            $wasteStock->save();
        }else{
          $wasteStock= new WasteStock();
          $wasteStock->godam_id =$request->wastage_godam_id;
          $wasteStock->waste_id = $wastage_id;
          $wasteStock->quantity_in_kg = floatval($request->trimmimg_wastage) + floatval($request->fabric_waste);
          $wasteStock->save();
        }


        $bswLamPrintedFabricStocks= BswLamPrintedFabricStock::where('bswfabEntry_id',$request->bswfabEntry_id)->get();
        if ($bswLamPrintedFabricStocks->isNotEmpty()) {
        foreach ($bswLamPrintedFabricStocks as $stock) {
            $stock->status = 'completed';
            $stock->save();
        }
        }


        $bswSentLamFabs=BswSentLamFab::where('bsw_lam_fab_for_printing_entry_id',$request->bswfabEntry_id)->get();
        if ($bswSentLamFabs->isNotEmpty()) {
        foreach ($bswSentLamFabs as $bswSentLamFab) {
            $bswSentLamFab->status = 'completed';
            $bswSentLamFab->save();
        }
        }
        // return redirect()->route('BswLamFabSendForPrinting.index');
        DB::commit();
        return true;

        }catch(Exception $ex){
             $errorMessage = 'An error occurred: ' . $ex->getMessage();
             return response()->json(['error' => $errorMessage]);
            DB::rollback();
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


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
     * @param  \App\Models\BswLamFabForPrintingEntry  $bswLamFabForPrintingEntry
     * @return \Illuminate\Http\Response
     */
    public function show(BswLamFabForPrintingEntry $bswLamFabForPrintingEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BswLamFabForPrintingEntry  $bswLamFabForPrintingEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(BswLamFabForPrintingEntry $bswLamFabForPrintingEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BswLamFabForPrintingEntry  $bswLamFabForPrintingEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BswLamFabForPrintingEntry $bswLamFabForPrintingEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BswLamFabForPrintingEntry  $bswLamFabForPrintingEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(BswLamFabForPrintingEntry $bswLamFabForPrintingEntry)
    {
        //
    }
}
