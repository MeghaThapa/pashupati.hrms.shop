<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SingleTripalDanaConsumption;
use App\Models\AutoLoadItemStock;
use DB;

class SingleTripalDanaConsumptionController extends Controller
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
            $printsCutsDanaConsumption=SingleTripalDanaConsumption::where('bill_no', $request->bill_no)
            ->where('godam_id',$request->godam_id)
            ->where('dana_name_id',$request->dana_name_id)
            ->first();
            if($printsCutsDanaConsumption){
               $printsCutsDanaConsumption->quantity= $printsCutsDanaConsumption->quantity+$request->quantity;
               $printsCutsDanaConsumption->save();
            }else{
                $printsCutsDanaConsumption=new SingleTripalDanaConsumption();
                $printsCutsDanaConsumption->bill_no = $request->bill_no;
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
    public function getSingleTripalDanaConsumption(Request $request){
        //return $request->printAndCutEntry_id;
        // dd('lol');
            $printsCutsDanaConsumption=SingleTripalDanaConsumption::where('bill_no',$request->bill_no)->get();
            // dd($printsCutsDanaConsumption);
            return $printsCutsDanaConsumption->load(['godam:id,name','danaName:id,name']);
    }
}
