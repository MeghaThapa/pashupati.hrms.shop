<?php

namespace App\Http\Controllers\Nonwoven;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NonwovenBill;
// use App\Models\Shift;
// use App\Models\Godam;
// use App\Models\ProcessingStep;
// use App\Models\ProcessingSubcat;
use Illuminate\Support\Facades\DB;

class NonwovenBillController extends Controller
{
    public function store(Request $request){
        try{

           DB::beginTransaction();


           $bill =  NonwovenBill::create([
                'bill_no' => $request['bill_number'],
                'bill_date' => $request['bill_date'],
                'godam_id' =>$request['to_godam_id'],
                'planttype_id' => $request['plant_type_id'],
                'plantname_id' =>  $request['plant_name_id'],
                'shift_id' =>  $request['shift_name_id'],
            ]);
                
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
