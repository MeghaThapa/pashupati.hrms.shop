<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinalTripalBill;
use App\Models\Shift;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\FinalTripalStock;
use App\Models\ProcessingSubcat;
use App\Models\TripalEntry;
use Illuminate\Support\Facades\DB;

class FinalTripalBillController extends Controller
{
    public function store(Request $request){
        try{

           DB::beginTransaction();


           $bill =  FinalTripalBill::create([
                'bill_no' => $request['bill_number'],
                'bill_date' => $request['bill_date'],
                'godam_id' =>$request['to_godam_id'],
                'planttype_id' => $request['plant_type_id'],
                'plantname_id' =>  $request['plant_name_id'],
                'shift_id' =>  $request['shift_name_id'],
                'status' =>  'sent',
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

        $datas = FinalTripalBill::where('id', $id)->first();

        $shifts = Shift::where('status','active')->get();
        $godam = Godam::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();

        return view('admin.finaltripal.edit', compact('datas','godam','shifts','planttype','plantname'));
    }

    public function update(Request $request ,$id){

        $datas = FinalTripalBill::where('id', $id)->first();

        $datas->update([
            'godam_id' => $request->to_godam_id,
            'planttype_id' => $request['plant_type_id'],
            'plantname_id' => $request['plant_name_id'],
            'shift_id' => $request['shift_name_id'],
        ]);


        return redirect()->route('finaltripal.index')->withSuccess('Fabric updated successfully!');
    }

    public  function viewBill($id){

        $find_data = FinalTripalBill::find($id);
        $tripal_entries = TripalEntry::where('bill_id',$id)->get();
        $stocks = FinalTripal::where('bill_id',$id)->get();
        // dd($stocks);
          
        return view('admin.finaltripal.viewBill',compact('tripal_entries','stocks','find_data'));

    }
}
