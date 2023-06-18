<?php

namespace App\Http\Controllers;

use App\Models\TapeEntryStockModel;
use App\Models\Wastages;
use App\Models\WasteStock;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\DanaName;
use App\Models\AutoLoadItemStock;
use App\Models\Shift;
use App\Models\TapeEntry;

class TapeEntryController extends Controller
{
    // public function index(){
    //         /************** for all data *********************/
    //     // $department = Department::where('status','active')->get();
    //     // $planttype =  ProcessingStep::where("status",'1')->get();
    //     // $plantname = ProcessingSubcat::where('status','active')->get();
    //     // $dananame = DanaName::where("status",'active')->get();

    //         /************** for stock data *********************/
    //     $department = AutoLoadItemStock::with(['fromGodam'])->get();
    //     // $planttype = AutoLoadItemStock::with(['plantType'])->get();
    //     // $plantname = AutoLoadItemStock::with(['plantName'])->get();
    //     $shift = AutoLoadItemStock::with(['shift'])->get();
    //     // $dananame = AutoLoadItemStock::with(['danaName'])->get();



    //     // $department = Department::where('id',$departmentid)->get();
    //     // $planttype = ProcessingStep::where('id',$planttypeid)->get();
    //     // $plantname = ProcessingSubcat::where('id',$plantnameid)->get();
    //     // $shift = Shift::where('id',$shift_id)->get();
    //     // $dananame = DanaName::where('id',$dananame_id)->get();

    //     // return view('admin.TapeEntry.index',compact('department','planttype','plantname','shift','dananame'));
    //     return view('admin.TapeEntry.index',compact('department','shift'));
    // }

    public function index(){
        $tapeentries = TapeEntry::orderBy('created_at','DESC')->get();
        return view('admin.TapeEntry.index',compact('tapeentries'));
    }

    public function tapeentrystore(Request $request){
        $receipt_number = $request->receipt_number;
        $tape_receive_date = $request->tape_receive_date;
        $tapeentry = TapeEntry::create([
            'receipt_number' => $receipt_number,
            'tape_entry_date' => $tape_receive_date
        ]);
        if($tapeentry){
            return back()->with(['message'=>'Tape Receive Created Successfully']);
        }else{
            return back()->with(['message_err'=>'Tape Receive Creation Unsuccessful']);
        }
    }

    public function create($id){
        // $department = AutoLoadItemStock::with('fromGodam')->get();
        // $department = Department::where("name","like","tape"."%")->get();   

        // return $department = AutoLoadItemStock::with('fromGodam')->distinct('from_godam_id')->get();

        $departments = AutoLoadItemStock::with('fromGodam')
            ->whereHas('fromGodam', function ($query) {
                $query->where('slug', '<>', 'bsw');
            })
            ->distinct('from_godam_id')
            ->get(['from_godam_id']);

        $departmentIds = $departments->pluck('from_godam_id')->toArray();

        $department = Department::whereIn('id', $departmentIds)->get();


        $shift = AutoLoadItemStock::with('shift')->get();
        $tapeentries = TapeEntry::where('id', $id)->get();
        $wastage = Wastages::all();

        return view('admin.TapeEntry.create', compact('department', 'shift', 'tapeentries','wastage'));

    }

    public function view($id){
        return TapeEntryStockModel::where('tape_entry_id',$id)->get();
        
    }

    public function deleteTape(Request $request){
        if($request->ajax()){
            TapeEntry::where('id',$request->id)->delete();
            return redirect()->back();
        }
    }


    public function ajaxrequestplanttype(Request $request){
        if($request->ajax()){
            // return $request->department_id;
            $planttype =  ProcessingStep::where("status",'1')->where('department_id',$request->department_id)->where("name","like","tape"."%")->get();
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

    // public function getajaxwastage(){
    //     $wastage = Wastages::all();
    //     return response([
    //         'data' => $wastage
    //     ]);
    // }

    public function tapeentrystockstore(Request $request){
        // return $request;
        $tape_entry_id = $request->tape_entry_id;
        $shift = $request->shift;
        $plantname = $request->plantname;
        $planttype = $request->planttype;
        $department = $request->togodam;
        $tapetype = $request->tapetype;
        $tape_qty_in_kg = $request->tape_qty_in_kg;
        $total_in_kg = $request->total_in_kg;
        $loading = $request->loading;
        $running = $request->running;
        $bypass_wast = $request->bypass_wast;
        $dana_in_kg = $request->dana_in_kg;
        $wastetype = $request->wastetype ;

        $totalwaste = $dana_in_kg - $total_in_kg;
        if($totalwaste > 0){
            $this->wastemgmt($totalwaste,$department,$wastetype);
        }

        $tesm = TapeEntryStockModel::create([
            'tape_entry_id'=>$tape_entry_id,
            'togodam_id'=>$department,
            'planttype_id'=>$planttype,
            'plantname_id'=>$plantname,
            'shift_id'=>$shift,
            'tape_type'=>$tapetype,
            'tape_qty_in_kg'=>$tape_qty_in_kg,
            'total_in_kg'=>$total_in_kg,
            'loading'=>$loading,
            'running'=>$running,
            'bypass_wast'=>$bypass_wast,
            'dana_in_kg'=>$dana_in_kg,
        ]);

        if($tesm){
            TapeEntry::where('id',$tape_entry_id)->update([
                'status' => "created",
            ]);
            $danaid = AutoLoadItemStock::where('from_godam_id',$department)
                ->where('plant_type_id',$planttype)
                ->where('plant_name_id',$plantname)
                ->where('shift_id',$shift)
                ->get();

        foreach($danaid as $data){
            AutoLoadItemStock::where('id',$data->id)->delete();
        }

        return $this->index()->with(['message'=>"Tape Receive Entry Successful"]);
        }
    }
    function wastemgmt($totalwaste,$department,$wastetype){
        WasteStock::create([
            'department_id' => $department,
            'quantity_in_kg' => $totalwaste,
            'waste_id' => '1'
        ]);
    }
}
