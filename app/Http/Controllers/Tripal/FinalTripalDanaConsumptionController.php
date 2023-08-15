<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinalTripalDanaConsumption;
use App\Models\AutoLoadItemStock;
use App\Models\FinalTripalBill;
use DB;

class FinalTripalDanaConsumptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "autoloader_id"=>"required",
            "quantity"=>"required",
        ]);
        try{
            DB::beginTransaction();
            //deduct quantity from autoloader  stock
            $autoloaderStock=AutoLoadItemStock::where('id',$request->autoloader_id)
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
            $printsCutsDanaConsumption=FinalTripalDanaConsumption::where('bill_id', $request->bill_id)
            ->where('autoloader_id',$request->autoloader_id)
            ->first();
            if($printsCutsDanaConsumption){
               $printsCutsDanaConsumption->quantity= $printsCutsDanaConsumption->quantity+$request->quantity;
               $printsCutsDanaConsumption->save();
            }else{
                $find_data = AutoLoadItemStock::find($request->autoloader_id);
                $find_bill = FinalTripalBill::find($request->bill_id);

                $printsCutsDanaConsumption=new FinalTripalDanaConsumption();
                $printsCutsDanaConsumption->bill_no = $find_bill->bill_no;
                $printsCutsDanaConsumption->bill_id = $request->bill_id;
                $printsCutsDanaConsumption->dana_name_id = $find_data->dana_name_id;
                $printsCutsDanaConsumption->autoloader_id  = $request->autoloader_id;
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

    public function delFinalDanaConsumption($id){

        $find_data = FinalTripalDanaConsumption::find($id);
        $find_bill = FinalTripalBill::find($find_data->bill_id);

        $autoloaderStock=AutoLoadItemStock::where('id',$find_data->autoloader_id)
        ->value('id');

        $find_autoloader = AutoLoadItemStock::find($autoloaderStock);

        // dd($find_data,$find_autoloader);

        $find_autoloader->quantity= $find_autoloader->quantity + $find_data->quantity;
        $find_autoloader->update();
        $find_data->delete();
        return back();

    }


}
