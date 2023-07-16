<?php

namespace App\Http\Controllers;

use App\Models\PrintsAndCutsDanaConsumption;
use App\Models\AutoLoadItemStock;
use App\Models\PrintedAndCuttedRollsEntry;
use Illuminate\Http\Request;
use DB;

class PrintsAndCutsDanaConsumptionController extends Controller
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
        //return $request->printCutEntry_id;
        $request->validate([
            "printCutEntry_id"=>"required",
            "godam_id"=>"required",
            "dana_name_id"=>"required",
            "quantity"=>"required",
        ]);
        try{
            DB::beginTransaction();
            //deduct quantity from autoloader  stock
            $autoloaderStock=AutoLoadItemStock::where('from_godam_id',$request->godam_id)
            ->where('dana_name_id',$request->dana_name_id)
            ->first();
            if($autoloaderStock->quantity<$request->quantity){
                return response()->json([
                    'message'=>"you don't have enough",
                ],500);

            }
            $autoloaderStock->quantity -=$request->quantity;
            if($autoloaderStock->quantity <=0){
                $autoloaderStock->delete();
            }else{
                $autoloaderStock->save();
            }
            $printsCutsDanaConsumption=PrintsAndCutsDanaConsumption::where('printCutEntry_id', $request->printCutEntry_id)
            ->where('godam_id',$request->godam_id)
            ->where('dana_name_id',$request->dana_name_id)
            ->first();
            if($printsCutsDanaConsumption){
               $printsCutsDanaConsumption->quantity= $printsCutsDanaConsumption->quantity+$request->quantity;
               $printsCutsDanaConsumption->save();
            }else{
                $printsCutsDanaConsumption=new PrintsAndCutsDanaConsumption();
                $printsCutsDanaConsumption->printCutEntry_id = $request->printCutEntry_id;
                $printsCutsDanaConsumption->godam_id  = $request->godam_id;
                $printsCutsDanaConsumption->dana_name_id = $request->dana_name_id;
                $printsCutsDanaConsumption->quantity = $request->quantity;
                $printsCutsDanaConsumption->save();
        }
        DB::commit();
        return $printsCutsDanaConsumption->load(['godam:id,name','danaName:id,name']);
        }catch(Exception $ex){
            DB::rollback();
            return $ex;
        }

    }
    public function getPrintsAndCutsDanaConsumption(Request $request){
        //return $request->printAndCutEntry_id;
            $printsCutsDanaConsumption=PrintsAndCutsDanaConsumption::where('printCutEntry_id',$request->printAndCutEntry_id)->get();
            return $printsCutsDanaConsumption->load(['godam:id,name','danaName:id,name']);
    }

    // public function deleteConsumedDana(Request $request){
    //     $printsAndCutsEntry_id=PrintedAndCuttedRollsEntry::where('receipt_number',$request->receipt_no)->get('id');
    //     $prints_and_cuts_dana_consumption =PrintsAndCutsDanaConsumption::where($request->prints_and_cuts_dana_consumption_id);
    //     $autoloaderStock=AutoLoadItemStock::where('from_godam_id',$prints_and_cuts_dana_consumption->godam_id)
    //     ->where('dana_group_id',$prints_and_cuts_dana_consumption->dana_group_id)
    //     ->where('dana_name_id',$prints_and_cuts_dana_consumption->dana_name_id)
    //     ->first();
    //     if($autoloaderStock){
    //         $autoloaderStock->quantity+=$prints_and_cuts_dana_consumption->quantity;
    //         $autoloaderStock->save();
    //     }else{
    //         $autoloaderStock= new AutoloaderStock();


    //     }
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PrintsAndCutsDanaConsumption  $printsAndCutsDanaConsumption
     * @return \Illuminate\Http\Response
     */
    public function show(PrintsAndCutsDanaConsumption $printsAndCutsDanaConsumption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrintsAndCutsDanaConsumption  $printsAndCutsDanaConsumption
     * @return \Illuminate\Http\Response
     */
    public function edit(PrintsAndCutsDanaConsumption $printsAndCutsDanaConsumption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrintsAndCutsDanaConsumption  $printsAndCutsDanaConsumption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintsAndCutsDanaConsumption $printsAndCutsDanaConsumption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrintsAndCutsDanaConsumption  $printsAndCutsDanaConsumption
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrintsAndCutsDanaConsumption $printsAndCutsDanaConsumption)
    {
        //
    }
}
