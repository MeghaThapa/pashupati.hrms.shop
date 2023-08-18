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
            $autoloaderStock = AutoLoadItemStock::find($request->autoloader_id);

            $autoloaderStock->quantity -=$request->quantity;
            if($autoloaderStock->quantity <=0){
                $autoloaderStock->delete();
            }else{
                $autoloaderStock->save();
            }
            // dd($request->quantity,$autoloaderStock->quantity);
            $printsCutsDanaConsumption=DoubleTripalDanaConsumption::where('bill_id', $request->bill_id)
            ->where('id',$request->autoloader_id)
            ->first();
            if($printsCutsDanaConsumption){
               $printsCutsDanaConsumption->quantity= $printsCutsDanaConsumption->quantity+$request->quantity;
               $printsCutsDanaConsumption->save();
            }else{
                $find_bill = DoubleTripalBill::find($request->bill_id);

                $printsCutsDanaConsumption=new DoubleTripalDanaConsumption();
                $printsCutsDanaConsumption->bill_no = $find_bill->bill_no;
                $printsCutsDanaConsumption->bill_id = $request->bill_id;
                $printsCutsDanaConsumption->autoloader_id  = $request->autoloader_id;
                $printsCutsDanaConsumption->dana_name_id = $autoloaderStock->dana_name_id;
                $printsCutsDanaConsumption->from_godam_id = $autoloaderStock->from_godam_id;
                $printsCutsDanaConsumption->plant_type_id = $autoloaderStock->plant_type_id;
                $printsCutsDanaConsumption->plant_name_id = $autoloaderStock->plant_name_id;
                $printsCutsDanaConsumption->shift_id = $autoloaderStock->shift_id;
                $printsCutsDanaConsumption->dana_group_id = $autoloaderStock->dana_group_id;
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

        try{

           DB::beginTransaction();

           $find_data = DoubleTripalDanaConsumption::find($id);
           $find_bill = DoubleTripalBill::find($find_data->bill_id);

           $autoloaderStock = AutoLoadItemStock::where('id',$find_data->autoloader_id)
           ->value('id');

           $find_autoloader = AutoLoadItemStock::find($autoloaderStock);

           if($find_autoloader != null){

               $find_autoloader->quantity= $find_autoloader->quantity + $find_data->quantity;
               $find_autoloader->update();
               $find_data->delete();

           }else{

               $autoloaderStock = new AutoLoadItemStock();
               $autoloaderStock->from_godam_id = $find_data->from_godam_id;
               $autoloaderStock->plant_type_id = $find_data->plant_type_id;
               $autoloaderStock->plant_name_id = $find_data->plant_name_id;
               $autoloaderStock->shift_id = $find_data->shift_id;
               $autoloaderStock->dana_name_id = $find_data->dana_name_id;
               $autoloaderStock->dana_group_id = $find_data->dana_group_id;
               $autoloaderStock->quantity = $find_data->quantity;
               $autoloaderStock->save();
               $find_data->delete();
           }
 
           DB::commit();
            return back();

        }
        catch(Exception $e){
            DB::rollback();
            dd($e);
            return "exception".$e->getMessage();
        }


    }

}
