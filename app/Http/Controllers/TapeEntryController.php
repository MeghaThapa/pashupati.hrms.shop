<?php

namespace App\Http\Controllers;

use App\Models\TapeEntryItemModel;
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
use App\Models\TapeEntryOpening;
use Yajra\DataTables\DataTables;

class TapeEntryController extends Controller
{
    public function openingcreate(){
        $godam = Godam::where("status","active")->get(); 
        return view("admin.TapeEntry.opening")->with([
            "godam" => $godam,
        ]);
    }
    public function openingstore(Request $request){
        $request->validate([
            "tape_quantity" => "numeric|required",
            "opening_date" => "required",
            "to_godam" => "required",
            "receipt_number" => "required"
        ]);
        // dd($request);

        $opening = TapeEntryOpening::create([
            "godam_id" => $request->to_godam,
            "qty" => $request->tape_quantity,
            "date" => $request->opening_date,
        ]);

        // dd($request->to_godam);

        $stock = TapeEntryStockModel::where('toGodam_id', $request->to_godam)->count();

        $getStock = TapeEntryStockModel::where('toGodam_id', $request->to_godam)
          ->first();
          // dd($getStock);
        

        if ($stock == 1) {
            $getStock->tape_qty_in_kg += $request->tape_quantity;
            $getStock->save();
        } else {
           TapeEntryStockModel::create([
               "toGodam_id" => $request->to_godam,
               "tape_type" => "tape1",
               "tape_qty_in_kg" => $request->tape_quantity,
               "total_in_kg" => $request->tape_quantity,
               'loading'=> "0",
               'running' => '0',
               'bypass_wast' => "0",
               "cause" => "opening"
           ]);
        }
        
       
        return  back()->with([
            "message" => "Tape Opening added successfully"
        ]);
    }

    public function index(){
        $latest = TapeEntry::latest("id")->value("id");
        $receipt_number = 'TR'.'-'.getNepaliDate(date('Y-m-d')).'-'.$latest+1;
        $tapeentries = TapeEntry::orderBy('updated_at','DESC')->get();
        return view('admin.TapeEntry.index',compact('tapeentries',"receipt_number"));
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
        $tapeentries = TapeEntry::where('id', $tapeReceive_id)->get();
        $wastage = Wastages::all();

        return view('admin.TapeEntry.create', compact('department', 'shift', 'tapeentries','wastage',"tapeReceive_id"));

    }

    public function view($id){
        return TapeEntryItemModel::where('tape_entry_id',$id)->get();

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

            TapeEntryItemModel::create([
                'tape_entry_id'=>$tape_entry_id,
                'toGodam_id'=>$department,
                'plantType_id'=>$planttype,
                'plantName_id'=>$plantname,
                'shift_id'=>$shift,
                'tape_type'=>$tapetype,
                'tape_qty_in_kg'=>$tape_qty_in_kg,
                'total_in_kg'=>$total_in_kg,
                'loading'=>$loading,
                'running'=>$running,
                'bypass_wast'=>$bypass_wast,
                'dana_in_kg'=>$dana_in_kg,
            ]);

            $getEarlierData = TapeEntryStockModel::where("toGodam_id",$department)
                                // ->where("planttype_id",$planttype)
                                // ->where("plantname_id",$plantname)
                                // ->where("shift_id",$shift)
                                ->first();
            if($getEarlierData == null){
                $tesm = TapeEntryStockModel::create([
                    // 'tape_entry_id'=>$tape_entry_id,
                    'toGodam_id'=>$department,
                    // 'plantType_id'=>$planttype,
                    // 'plantName_id'=>$plantname,
                    // 'shift_id'=>$shift,
                    'tape_type'=>$tapetype,
                    'tape_qty_in_kg'=>$tape_qty_in_kg,
                    'total_in_kg'=>$total_in_kg,
                    'loading'=>$loading,
                    'running'=>$running,
                    'bypass_wast'=>$bypass_wast,
                    // 'dana_in_kg'=>$dana_in_kg,
                ]);
            }else{
                $new_tape_qty_in_kg = $getEarlierData->tape_qty_in_kg + $tape_qty_in_kg;
                $new_total_in_kg = $getEarlierData->total_in_kg + $total_in_kg;
                $new_loading = $getEarlierData->loading + $loading;
                $new_running = $getEarlierData->running + $running;
                $new_bypass_wast = $getEarlierData->bypass_wast + $bypass_wast;
                $new_dana_in_kg = $getEarlierData->dana_in_kg + $dana_in_kg;

                $tesm =  $getEarlierData->update([
                    // 'tape_entry_id'=>$tape_entry_id,
                    'toGodam_id'=>$department,
                    // 'plantType_id'=>$planttype,
                    // 'plantName_id'=>$plantname,
                    // 'shift_id'=>$shift,
                    'tape_type'=>$tapetype,
                    'tape_qty_in_kg'=>$new_tape_qty_in_kg,
                    'total_in_kg'=>$new_total_in_kg,
                    'loading'=>$new_loading,
                    'running'=>$new_running,
                    'bypass_wast'=>$new_bypass_wast,
                    // 'dana_in_kg'=>$new_dana_in_kg,
                ]);
            }

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
        return redirect()->route("tape.entry")->with(['message'=>"Tape Receive Entry Successful"]);

        }
        catch(Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return "exception :".$e->getMessage();
        }
    }

    function wastemgmt($totalwaste,$department,$wastetype){
        
        $wastage_type = Wastages::where("name" ,"like" , "raffia"."%")->value("id");
        
        WasteStock::create([
            'godam_id' => $department,
            'quantity_in_kg' => $totalwaste,
            'waste_id' => $wastage_type
        ]);
    }

    public function tape_report(){
        $godams = Godam::where("status","active")->get();
        $tape_entries= TapeEntryItemModel::get();
        $dana_in_kg = 0;
        $wastage = 0 ;
        $tape_qty = 0;
        foreach($tape_entries as $data){
            $dana_in_kg += $data->dana_in_kg;

            $loading = $data->loading;
            $bypass_wast = $data->bypass_wast;
            $running = $data->running;
            $total = $loading + $bypass_wast + $running;

            $wastage += $total;

            $tape_qty += $data->tape_qty_in_kg;

        }
        return view("admin.TapeEntry.view")->with([
            "wastage" => $wastage,
            "dana_consumption" => $dana_in_kg,
            "tape_quantity" => $tape_qty,
            "godams" => $godams
        ]);
    }
    public function tape_report_ajax(Request $request){
        if($request->ajax()){
            $tape_entries= TapeEntryItemModel::query();
            if($request->godam){
                $tape_entries->with(["tapeentry","godam"])->where("toGodam_id",$request->godam);
            }
            $tape_entries->with(["tapeentry","godam"])->get();

            return DataTables::of($tape_entries)
                ->addIndexColumn()
                ->addColumn("receipt_entry_date",function($row){
                    return $row->tapeentry->tape_entry_date;
                })
                ->addColumn("receipt_number",function($row){
                    return $row->tapeentry->receipt_number;
                })
                ->addColumn("godam",function($row){
                    return $row->godam->name;
                })
                ->addColumn("wastage",function($row){
                    $loading = $row->loading;
                    $bypass_wast = $row->bypass_wast;
                    $running = $row->running;
                    return $total = $loading + $bypass_wast + $running;
                })
                ->rawColumns(["receipt_entry_date","receipt_number","wastage","godam"])
                ->make(true);
        }
    }   

    public function tape_report_amounts_ajax($godam){
        $tape_entries= TapeEntryItemModel::where("toGodam_id",$godam)->get();
        $dana_in_kg = 0;
        $wastage = 0 ;
        $tape_qty = 0;
        foreach($tape_entries as $data){
            $dana_in_kg += $data->dana_in_kg;

            $loading = $data->loading;
            $bypass_wast = $data->bypass_wast;
            $running = $data->running;
            $total = $loading + $bypass_wast + $running;

            $wastage += $total;

            $tape_qty += $data->tape_qty_in_kg;
        }
        return response([
            "wastage" => $wastage,
            "dana_consumption" => $dana_in_kg,
            "tape_quantity" => $tape_qty,
        ],200);
    }
}