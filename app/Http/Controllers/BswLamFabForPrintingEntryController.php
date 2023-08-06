<?php

namespace App\Http\Controllers;

use App\Models\BswLamFabForPrintingEntry;
use App\Models\BswLamPrintedFabricStock;
use App\Models\BswSentLamFab;
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
        $bswLamFabForPrintingEntry->status= 'completed';
        $bswLamFabForPrintingEntry->save();

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
