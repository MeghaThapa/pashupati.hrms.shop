<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\DanaName;
use App\Models\AutoLoadItemStock;
use App\Models\Shift;

class TapeEntryController extends Controller
{
    public function index(){
            /************** for all data *********************/ 
        // $department = Department::where('status','active')->get();
        // $planttype =  ProcessingStep::where("status",'1')->get();
        // $plantname = ProcessingSubcat::where('status','active')->get();
        // $dananame = DanaName::where("status",'active')->get();
        
            /************** for stock data *********************/ 
        $department = AutoLoadItemStock::with(['fromGodam'])->get();
        // $planttype = AutoLoadItemStock::with(['plantType'])->get();
        // $plantname = AutoLoadItemStock::with(['plantName'])->get();   
        $shift = AutoLoadItemStock::with(['shift'])->get();    
        // $dananame = AutoLoadItemStock::with(['danaName'])->get();
        
        
        
        // $department = Department::where('id',$departmentid)->get();
        // $planttype = ProcessingStep::where('id',$planttypeid)->get();
        // $plantname = ProcessingSubcat::where('id',$plantnameid)->get();
        // $shift = Shift::where('id',$shift_id)->get();
        // $dananame = DanaName::where('id',$dananame_id)->get();
        
        // return view('admin.TapeEntry.index',compact('department','planttype','plantname','shift','dananame'));
        return view('admin.TapeEntry.index',compact('department','shift'));
    }
    
    public function ajaxrequestplanttype(Request $request){
        if($request->ajax()){
            // return $request->department_id;
            $planttype =  ProcessingStep::where("status",'1')->where('department_id',$request->department_id)->get();
            return response([
                'planttype' => $planttype
            ]);
        }
    }
    
    public function ajaxrequestplantname(Request $request){
        if($request->ajax()){
            $id =  $request->planttype_id;
            $plantname = ProcessingSubcat::where('processing_steps_id',$id)->get();
            return response([
                'plantname' => $plantname
            ],200);
        }
    }
    
    public function ajaxrequestshift(Request $request){
        if($request->ajax()){
            $id =  $request->plantname_id;
            $shift = AutoLoadItemStock::where('plant_name_id',$id)->value('shift_id');
            $sname = Shift::where('id',$shift)->get();
            return response([
                'shift' => $sname,
                'status' => true    
            ]);
        }
    }

    public function ajaxrequestdanainfo(Request $request){
        if($request->ajax()){
            $shift = $request->shift;
            $plantname = $request->plantname;
            $planttype = $request->planttype;
            $department = $request->department;

            $danaid = AutoLoadItemStock::where('from_godam_id',$department)
                    ->where('plant_type_id',$planttype)
                    ->where('plant_name_id',$plantname)
                    ->where('shift_id',$shift)
                    ->with(['danaName'])->get();
            
            $totalqty = 0;
            foreach($danaid as $data){
                $totalqty =  $totalqty+$data->quantity;
            }
            if(count($danaid) > 0){
                return [
                    'data' => $danaid,
                    'total_quantity' => $totalqty
                ];
            }else{
                return[
                    'data_err' => "No data Found"
                ];
            }
        }
    }

    public function tapeentrystockstore(Request $request){
        // return $request;
        //baki xa garna
        $shift = $request->shift;
        $plantname = $request->plantname;
        $planttype = $request->planttype;
        $department = $request->togodam;

        //yo delete hanni stock bata
        $danaid = AutoLoadItemStock::where('from_godam_id',$department)
                ->where('plant_type_id',$planttype)
                ->where('plant_name_id',$plantname)
                ->where('shift_id',$shift)
                ->get();

        //add ganrne tape_entry_stock ma danaid bata ako data
        foreach($danaid as $data){
            //chaiyeko data relation lagau navay pardaina
            return "here";
        }

    }
}
