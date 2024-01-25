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
            $autoloaderStock = AutoLoadItemStock::find($request->autoloader_id);

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
                $find_bill = FinalTripalBill::find($request->bill_id);

                $printsCutsDanaConsumption=new FinalTripalDanaConsumption();
                $printsCutsDanaConsumption->bill_no = $find_bill->bill_no;
                $printsCutsDanaConsumption->bill_id = $request->bill_id;
                $printsCutsDanaConsumption->autoloader_id  = $request->autoloader_id;
                $printsCutsDanaConsumption->quantity = $request->quantity;
                $printsCutsDanaConsumption->dana_name_id = $autoloaderStock->dana_name_id;
                $printsCutsDanaConsumption->from_godam_id = $autoloaderStock->from_godam_id;
                $printsCutsDanaConsumption->plant_type_id = $autoloaderStock->plant_type_id;
                $printsCutsDanaConsumption->plant_name_id = $autoloaderStock->plant_name_id;
                $printsCutsDanaConsumption->shift_id = $autoloaderStock->shift_id;
                $printsCutsDanaConsumption->dana_group_id = $autoloaderStock->dana_group_id;
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

        try{

           DB::beginTransaction();

           $find_data = FinalTripalDanaConsumption::find($id);
           $find_bill = FinalTripalBill::find($find_data->bill_id);

           $autoloaderStock=AutoLoadItemStock::where('id',$find_data->autoloader_id)
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
