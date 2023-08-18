<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SingleTripalBill;
use App\Models\Shift;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use Illuminate\Support\Facades\DB;

class SingleTripalBillController extends Controller
{
    public function store(Request $request){
        try{

           DB::beginTransaction();


           $bill =  SingleTripalBill::create([
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

    public function edit($id){

        $datas = SingleTripalBill::where('id', $id)->first();

        $shifts = Shift::where('status','active')->get();
        $godam = Godam::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();

        return view('admin.tripal.edit', compact('datas','godam','shifts','planttype','plantname'));
    }

    public function update(Request $request ,$id){

        $datas = SingleTripalBill::where('id', $id)->first();

        $datas->update([
            'godam_id' => $request->to_godam_id,
            'planttype_id' => $request['plant_type_id'],
            'plantname_id' => $request['plant_name_id'],
            'shift_id' => $request['shift_name_id'],
        ]);


        return redirect()->route('tripal.index')->withSuccess('Fabric updated successfully!');
    }


}
