<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinalTripalDanaConsumption;
use App\Models\AutoLoadItemStock;
use DB;

class FinalTripalDanaConsumptionController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        //return $request->printCutEntry_id;
        $request->validate([
            // "printCutEntry_id"=>"required",
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
            $printsCutsDanaConsumption=FinalTripalDanaConsumption::where('bill_no', $request->bill_no)
            ->where('godam_id',$request->godam_id)
            ->where('dana_name_id',$request->dana_name_id)
            ->first();
            if($printsCutsDanaConsumption){
               $printsCutsDanaConsumption->quantity= $printsCutsDanaConsumption->quantity+$request->quantity;
               $printsCutsDanaConsumption->save();
            }else{
                $printsCutsDanaConsumption=new FinalTripalDanaConsumption();
                $printsCutsDanaConsumption->bill_no = $request->bill_no;
                $printsCutsDanaConsumption->bill_id = $request->bill_id;
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
    public function getFinalTripalDanaConsumption(Request $request){
        //return $request->printAndCutEntry_id;
        // dd('lol');
            $printsCutsDanaConsumption=FinalTripalDanaConsumption::where('bill_no',$request->bill_no)->get();
            // dd($printsCutsDanaConsumption);
            return $printsCutsDanaConsumption->load(['godam:id,name','danaName:id,name']);
    }
}
