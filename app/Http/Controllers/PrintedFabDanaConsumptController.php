<?php

namespace App\Http\Controllers;

use App\Models\PrintedFabDanaConsumpt;
use Illuminate\Http\Request;

class PrintedFabDanaConsumptController extends Controller
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
            'godam_id'=>'required',
            'dana_name_id'=>'required',
            'quantity'=>'required',
        ]);
        try{
         //deducting from autoloader stock
        $autoloader=AutoLoadItemStock::where('from_godam_id',$request->godam_id)
        ->where('dana_name_id',$request->dana_name_id)
        ->first();
        if($autoloader->quantity >= $request->quantity){
         $autoloader->quantity =  $autoloader->quantity- $request->quantity;
         if($autoloader->quantity <=0){
            $autoloader->delete();
         }else{
            $autoloader->save();
         }
         }else{
           throw new Exception('Not enough quantity available.');
         }
        //adding to db
        $printedFabDanaConsumpt= new PrintedFabDanaConsumpt();
        $printedFabDanaConsumpt->bswfabEntry_id = $request->bswfabEntry_id;
        $printedFabDanaConsumpt->godam_id= $request->godam_id;
        $printedFabDanaConsumpt->dana_name_id = $request->dana_name_id;
        $printedFabDanaConsumpt->quantity = $request->quantity;
        $printedFabDanaConsumpt->save();
        // $printedFabDanaConsumpt=$printedFabDanaConsumpt->load(['godam:id,name','danaName:id,name']);



        return $printedFabDanaConsumpt;
        }catch(Exception $ex){
          return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PrintedFabDanaConsumpt  $printedFabDanaConsumpt
     * @return \Illuminate\Http\Response
     */
    public function show(PrintedFabDanaConsumpt $printedFabDanaConsumpt)
    {
        //
    }

    public function getData( Request $request){
        $printedFabDanaConsumpt =PrintedFabDanaConsumpt::with(['godam:id,name','danaName:id,name'])
        ->where('bswfabEntry_id',$request->bsw_lam_fab_for_printing_entry_id)
        ->get();
        return $printedFabDanaConsumpt;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrintedFabDanaConsumpt  $printedFabDanaConsumpt
     * @return \Illuminate\Http\Response
     */
    public function edit(PrintedFabDanaConsumpt $printedFabDanaConsumpt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrintedFabDanaConsumpt  $printedFabDanaConsumpt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintedFabDanaConsumpt $printedFabDanaConsumpt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrintedFabDanaConsumpt  $printedFabDanaConsumpt
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrintedFabDanaConsumpt $printedFabDanaConsumpt)
    {
        //
    }
}
