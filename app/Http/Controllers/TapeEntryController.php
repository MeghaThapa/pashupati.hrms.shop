<?php

namespace App\Http\Controllers;

use App\Models\TapeEntryStockModel;
use App\Models\Wastages;
use App\Models\WasteStock;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\DanaName;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
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

    public function create($tapeReceive_id){
        // $department = AutoLoadItemStock::with('fromGodam')->get();
        // $department = Department::where("name","like","tape"."%")->get();

        // return $bhabishid;

        $departments = [];

        $getdepartment = AutoLoadItemStock::with('fromGodam')->distinct('from_godam_id')->get();
        foreach($getdepartment as $data){
            $id = $data->from_godam_id;
            if(!in_array($id,$departments)){
                $departments[] = $id;
            }
        }


       $department = Godam::whereIn("id",$departments)->get();

        // $departments = AutoLoadItemStock::with('fromGodam')
        //     ->whereHas('fromGodam', function ($query) {
        //         $query->where('slug', '<>', 'bsw');
        //     })
        //     ->distinct('from_godam_id')
        //     ->get(['from_godam_id']);

        // $departmentIds = $departments->pluck('from_godam_id')->toArray();

        // $department = Department::whereIn('id', $departmentIds)->get();



        $shift = AutoLoadItemStock::with('shift')->get();
        $tapeentries = TapeEntry::where('id', $id)->get();
        $wastage = Wastages::all();

        return view('admin.TapeEntry.create', compact('department', 'shift', 'tapeentries','wastage',"tapeReceive_id"));

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
        $planttype = DB::table('autoload_items_stock')
            ->join('processing_steps', 'processing_steps.id', '=', 'autoload_items_stock.plant_type_id')
            ->where('from_godam_id', $request->godam_id)
            ->where('processing_steps.name', 'like', 'tape' . '%')
            ->select('processing_steps.id','processing_steps.name')
            ->groupBy('processing_steps.id', 'processing_steps.name')
            ->distinct()
            ->get();
             return response([
                'planttype' => $planttype
            ],200);
        }
    }

    public function ajaxrequestplantname($planttype_id,$godam_id){
       // return $request;
            $plantnames=DB::table('autoload_items_stock')
            ->join('processing_subcats','processing_subcats.id','=','autoload_items_stock.plant_name_id')
            ->where('plant_type_id',$planttype_id)
            ->where('from_godam_id',$godam_id)
            ->select('processing_subcats.id','processing_subcats.name')
            ->groupBy('processing_subcats.id','processing_subcats.name')
            ->distinct()
            ->get();
           //  $plantnames = AutoLoadItemStock::with('plantName')->where('from_godam_id',$godam_id)->where('plant_type_id',$planttype_id)->get();
            return response([
                'plantname'=>$plantnames
               // 'plantname' => $plantname
            ],200);

    }

    public function ajaxrequestshift($plantname_id,$godam_id,$plantType_id){
        $shifts=DB::table('autoload_items_stock')
        ->join('shifts','shifts.id','=','autoload_items_stock.shift_id')
        ->where('from_godam_id',$godam_id)
        ->where('plant_type_id',$plantType_id)
        ->where('plant_name_id',$plantname_id)
        ->select('shifts.id','shifts.name')
        ->groupBy('shifts.id','shifts.name')
        ->distinct()
        ->get();

            //$shifts=AutoLoadItemStock::with('shift')->where('from_godam_id',$godam_id)->where('plant_type_id',$plantType_id)->where('plant_name_id',$plantname_id)->get();
            return response([
                'shifts'=>$shifts
            ]);
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
        //return $request;
        try{

            DB::beginTransaction();

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
                'toGodam_id'=>$department,
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

            DB::commit();
        return $this->index()->with(['message'=>"Tape Receive Entry Successful"]);

        }
        catch(Exception $e){
            DB::rollBack();
            return "exception :".$e->getMessage();
        }
    }

    function wastemgmt($totalwaste,$department,$wastetype){
        WasteStock::create([
            'godam_id' => $department,
            'quantity_in_kg' => $totalwaste,
            'waste_id' => '1'
        ]);
    }
}
