<?php

namespace App\Http\Controllers;

use App\Models\BswFabSendcurtxReceivpatchvalveEntry;
use App\Models\ProcessingStep;
use App\Models\Shift;
use App\Models\Godam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BswFabSendcurtxReceivpatchvalveEntryController extends Controller
{
    public function index(){

        return view('admin.bsw.patch_valve.index');
    }

    public function yajraDatatables(){
         $bswCurtexToPatchValveEntryDatas=BswFabSendcurtxReceivpatchvalveEntry::with(['plantType:id,name','plantName:id,name','shift:id,name','godam:id,name'])
        ->get(['id','receipt_no','date','plant_type_id','plant_name_id','shift_id','godam_id','status']);

        return DataTables::of($bswCurtexToPatchValveEntryDatas)
            ->addIndexColumn()
            ->addColumn('statusBtn', function ($bswCurtexToPatchValveEntryData) {
                return '<span class="badge badge-pill badge-success">'.$bswCurtexToPatchValveEntryData->status.'</span>';
            })
            ->addColumn('action', function ($bswCurtexToPatchValveEntryData) {
                $actionBtn='';
                if($bswCurtexToPatchValveEntryData->status == 'running'){
                    $actionBtn .= '
                <a class="btnEdit" href="' . route('fabSendCuetxReceivePatchValveItems.edit', ['id' => $bswCurtexToPatchValveEntryData->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>

                <button class="btn btn-danger" id="dltBagSellingEntry" data-id="'.$bswCurtexToPatchValveEntryData->id.'">
                <i class="fas fa-trash-alt"></i>
            </button>

                ';
                }
                else{
                    $actionBtn .= '
                <button class="btn btn-info" id="dltBagSellingEntry" data-id="'.$bswCurtexToPatchValveEntryData->id.'">
                    <i class="fas fa-eye"></i>
                </button>
                '
                ;
                }


                return $actionBtn;
            })
            ->rawColumns(['action','statusBtn'])
            ->make(true);
    }
    public function create(){
         $id = BswFabSendcurtxReceivpatchvalveEntry::latest()->value('id');
        if($id){
            $receipt_no = "BLFCTOPV"."-".getNepaliDate(date('Y-m-d'))."-".$id+1;
        }else{
            $receipt_no = "BLFCTOPV"."-".getNepaliDate(date('Y-m-d'))."-".'1';
        }
        $nepaliDate = getNepaliDate(date('Y-m-d'));
        $plantTypes = ProcessingStep::where('status','1')->get(['id','name']);

        $shifts=Shift::where('status','active')->get(['id','name']);
        $godams=Godam::where('status','active')->get(['id','name']);

         return view('admin.bsw.patch_valve.create',compact(['receipt_no','nepaliDate','plantTypes','shifts','godams']));
    }

    public function store(Request $request){
        $request->validate([
            'receipt_no'=>'required',
            'date_np'=>'required',
            'plant_type_id'=>'required',
            'plant_name_id'=>'required',
            'shift_id'=>'required',
            'godam_id'=>'required',
        ]);
        $bswFabSendcurtxReceivpatchvalveEntry = new BswFabSendcurtxReceivpatchvalveEntry();
        $bswFabSendcurtxReceivpatchvalveEntry->receipt_no = $request->receipt_no;
        $bswFabSendcurtxReceivpatchvalveEntry->date = $request->date_np;
        $bswFabSendcurtxReceivpatchvalveEntry->plant_type_id  = $request->plant_type_id;
        $bswFabSendcurtxReceivpatchvalveEntry->plant_name_id  = $request->plant_name_id;
        $bswFabSendcurtxReceivpatchvalveEntry->shift_id  = $request->shift_id;
        $bswFabSendcurtxReceivpatchvalveEntry->godam_id = $request->godam_id;
        $bswFabSendcurtxReceivpatchvalveEntry->save();
        $bswFabSendcurtxReceivpatchvalveEntry_id= $bswFabSendcurtxReceivpatchvalveEntry->id;
        return redirect()->route('fabSendCuetxReceivePatchValveItems.createItems',compact('bswFabSendcurtxReceivpatchvalveEntry_id'));
    }
    public function saveEntire(Request $request){
        $bswFabSendcurtxReceivpatchvalveEntry = BswFabSendcurtxReceivpatchvalveEntry::find($request->bswFabSendcurtxReceivpatchvalveEntry_id);
        $bswFabSendcurtxReceivpatchvalveEntry->trem_wastage =$request->trimmimg_wastage;
        $bswFabSendcurtxReceivpatchvalveEntry->fabric_wastage=$request->fabric_waste;
        $bswFabSendcurtxReceivpatchvalveEntry->total_wastage = $request->trimmimg_wastage + $request->fabric_waste;
        $bswFabSendcurtxReceivpatchvalveEntry->status ="completed";
        $bswFabSendcurtxReceivpatchvalveEntry->save();
    }
}
