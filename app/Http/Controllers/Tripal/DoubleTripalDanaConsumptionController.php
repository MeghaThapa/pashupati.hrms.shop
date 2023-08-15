<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoubleTripalDanaConsumption;
use App\Models\AutoLoadItemStock;
use App\Models\DoubleTripalBill;
use DB;

class DoubleTripalDanaConsumptionController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        //return $request->printCutEntry_id;
        $request->validate([
            // "printCutEntry_id"=>"required",
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
            $printsCutsDanaConsumption=DoubleTripalDanaConsumption::where('bill_id', $request->bill_id)
            ->where('id',$request->autoloader_id)
            ->first();
            if($printsCutsDanaConsumption){
               $printsCutsDanaConsumption->quantity= $printsCutsDanaConsumption->quantity+$request->quantity;
               $printsCutsDanaConsumption->save();
            }else{
                $find_data = AutoLoadItemStock::find($request->autoloader_id);
                $find_bill = DoubleTripalBill::find($request->bill_id);

                $printsCutsDanaConsumption=new DoubleTripalDanaConsumption();
                $printsCutsDanaConsumption->bill_no = $find_bill->bill_no;
                $printsCutsDanaConsumption->bill_id = $request->bill_id;
                $printsCutsDanaConsumption->autoloader_id  = $request->autoloader_id;
                $printsCutsDanaConsumption->dana_name_id = $find_data->dana_name_id;
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
    public function getDoubleTripalDanaConsumption(Request $request){
        //return $request->printAndCutEntry_id;
        // dd('lol');
            $printsCutsDanaConsumption=DoubleTripalDanaConsumption::where('bill_no',$request->bill_no)->get();
            // dd($printsCutsDanaConsumption);
            return $printsCutsDanaConsumption->load(['godam:id,name','danaName:id,name']);
    }

    public function delDoubleDanaConsumption($id){

        $find_data = DoubleTripalDanaConsumption::find($id);
        $find_bill = DoubleTripalBill::find($find_data->bill_id);

        $autoloaderStock=AutoLoadItemStock::where('id',$find_data->autoloader_id)
        ->value('id');

        $find_autoloader = AutoLoadItemStock::find($autoloaderStock);

        $find_autoloader->quantity= $find_autoloader->quantity + $find_data->quantity;
        $find_autoloader->update();
        $find_data->delete();
        return back();

    }

}
