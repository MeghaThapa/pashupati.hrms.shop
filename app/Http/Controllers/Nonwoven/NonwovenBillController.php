<?php

namespace App\Http\Controllers\Nonwoven;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NonwovenBill;
use App\Helpers\AppHelper;

use Illuminate\Support\Facades\DB;

class NonWovenBillController extends Controller
{
    public function store(Request $request){
        try{

           DB::beginTransaction();
           $bill_date_en = AppHelper::convertNepaliToEnglishDate($request->bill_date);

           $bill =  NonwovenBill::create([
                'bill_no' => $request['bill_number'],
                'bill_date' => $request['bill_date'],
                'bill_date_en' => $bill_date_en,
                'godam_id' =>$request['to_godam_id'],
                'planttype_id' => $request['plant_type_id'],
                'plantname_id' =>  $request['plant_name_id'],
                'shift_id' =>  $request['shift_name_id'],
                'status' =>  'pending',
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
