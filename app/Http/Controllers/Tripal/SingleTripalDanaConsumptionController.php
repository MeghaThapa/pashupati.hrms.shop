<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SingleTripalDanaConsumption;
use App\Models\AutoLoadItemStock;
use App\Models\SingleTripalBill;
use DB;

class SingleTripalDanaConsumptionController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        //return $request->printCutEntry_id;
        $request->validate([
            // "printCutEntry_id"=>"required",
            // "godam_id"=>"required",
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
            $printsCutsDanaConsumption=SingleTripalDanaConsumption::where('bill_id', $request->bill_id)
            ->where('autoloader_id',$request->autoloader_id)
            ->first();

            if($printsCutsDanaConsumption){
               $printsCutsDanaConsumption->quantity= $printsCutsDanaConsumption->quantity+$request->quantity;
               $printsCutsDanaConsumption->save();
            }else{
                $find_data = AutoLoadItemStock::find($request->autoloader_id);
                $find_bill = SingleTripalBill::find($request->bill_id);

                $printsCutsDanaConsumption=new SingleTripalDanaConsumption();
                $printsCutsDanaConsumption->bill_no = $find_bill->bill_no;
                $printsCutsDanaConsumption->autoloader_id  = $request->autoloader_id;
                $printsCutsDanaConsumption->dana_name_id = $find_data->dana_name_id;
                $printsCutsDanaConsumption->quantity = $request->quantity;
                $printsCutsDanaConsumption->bill_id = $request->bill_id;
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
        // dd($request);
            $printsCutsDanaConsumption=SingleTripalDanaConsumption::where('bill_id',$request->bill_id)->get();
            // dd($printsCutsDanaConsumption);
            return $printsCutsDanaConsumption->load(['godam:id,name','danaName:id,name']);
    }

    public function delSingleDanaConsumption($id){

        $find_data = SingleTripalDanaConsumption::find($id);
        $find_bill = SingleTripalBill::find($find_data->bill_id);

        $autoloaderStock = AutoLoadItemStock::where('id',$find_data->autoloader_id)
                        ->value('id');

        $find_autoloader = AutoLoadItemStock::find($autoloaderStock);

        $find_autoloader->quantity= $find_autoloader->quantity + $find_data->quantity;
        $find_autoloader->update();
        $find_data->delete();
        return back();

    }
}
