<?php

namespace App\Http\Controllers;

use App\Models\Godam;
use App\Models\Shift;
use App\Models\DanaName;
use App\Models\Wastages;
use App\Models\WasteStock;
use App\Models\CCPlantEntry;
use App\Models\CCPlantItems;
use Illuminate\Http\Request;
use App\Models\ProcessingStep;
use App\Models\ReprocessWaste;
use Illuminate\Validation\Rule;
use App\Models\CCPlantItemsTemp;
use App\Models\ProcessingSubcat;
use App\Models\RawMaterialStock;
use Yajra\DataTables\DataTables;
use App\Models\ReprocessWasteTemp;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Exceptions\Exception;

class ReprocessWasteController extends Controller
{
    protected $request;
    protected $entry_id;
    public function __construct(Request $request){
        $this->request = $request;
    }

    /************************ Entry ******************************/
    public function entryindex(){
        $godam = Godam::where("status","active")->get();
        $getData = DB::table("ccplantentry")->first();
        if(isset($getData)){
            $entries = DB::table("ccplantentry")->latest()->first()->id;
            $receipt_number = "CC-".getNepaliDate(date("Y-m-d"))."-".$entries + 1;
        }else{
            $receipt_number = "CC-".getNepaliDate(date("Y-m-d"))."-1";
        }
        return view("admin.reprocess_waste.index")->with([
            "receipt_number" => $receipt_number,
            "godam" => $godam
        ]);
    }

    public function entryindexajax(){
        if($this->request->ajax()){
            return DataTables::of(DB::table("reprocess_wastes")->get())
                    ->addIndexColumn()
                    ->editColumn('status',function($row){
                        if($row->status=="Running"){
                            return '<span class="badge badge-primary">Running</span>';
                        }else{
                            return '<span class="badge badge-success">Completed</span>';
                        }
                    })
                    ->addColumn("action",function($row){
                        if($row->status == "Running"){
                            $createUrl = route('reprocess.waste.create',$row->id);
                            return "<div class='btn-group'>
                                        <a href='{$createUrl}' class='btn btn-primary ' data-id='{$row->id}'><i class='fa fa-plus' aria-hidden='true'></i></a>
                                        <button class='btn btn-danger delete-cc-entry' data-id='{$row->id}'><i class='fa fa-trash' aria-hidden='true'></i></button>
                                    </div>";
                        }else{
                            return "<div class='btn-group'>
                                        <button class='btn btn-secondary view-cc' data-id='{$row->id}'><i class='fa fa-eye' aria-hidden='true'></i></button>
                                    </div>";
                        }
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }
    }

    public function entrystore(){
        $this->request->validate([
            "godam_id" => "required",
            "date" => "required",
            "receipt_number" => "required|unique:ccplantentry",
            'status' => [
                'required',
                Rule::in(['Running', 'Completed']),
            ],
        ]);

        ReprocessWaste::create([
            "godam_id" => $this->request->godam_id,
            "receipt_number" => $this->request->receipt_number ,
            "date" => $this->request->date,
            "status" => $this->request->status,
            "remarks" => $this->request->remarks
        ]);

        return back()->with("success","Created Successfully");
    }

    public function create($entry_id){
        $godam = Godam::all();
        $rawmaterials = RawMaterialStock::all();
        $data = ReprocessWaste::where("id",$entry_id)->first();

        $wasteIds = WasteStock::where('godam_id',$data->id)->pluck('waste_id');
        $wastes = Wastages::whereIn('id',$wasteIds)->get();


        return  view("admin.reprocess_waste.create")->with([
            "data" => $data,
            "entry_id" => $entry_id,
            "shift" => Shift::get(),
            "godam" => $godam,
            "rawmaterials" => $rawmaterials,
            "wastes" => $wastes,
        ]);
    }
    /************************ Entry ******************************/

    public function getPlantType(){
        if($this->request->ajax()){
            return response([
                "planttype" => ProcessingStep::where("godam_id",$this->request->godam_id)->get()
            ]);
        }
    }
    public function getPlantName($planttype_id){
        if($this->request->ajax()){
            return response([
                "planttype" => ProcessingSubcat::where("processing_steps_id",$this->request->planttype_id)->get()
            ]);
        }
    }

    public function addWaste(){
        if($this->request->ajax()){
            try{
                ReprocessWasteTemp::create([
                    "reprocess_waste_id" => $this->request->cc_plant_entry_id,
                    "planttype_id" => $this->request->planttype_id,
                    "plantname_id" => $this->request->plantname_id,
                    "dana_id" => $this->request->dana_id,
                    "quantity" => $this->request->quantity,
                    "waste_id" => $this->request->waste_id,
                    "dye_quantity" => $this->request->dye_quantity,
                    "cutter_quantity" => $this->request->cutter_quantity,
                    "melt_quantity" => $this->request->melt_quantity,
                ]);
            }catch(Exception $e){
                return response([
                    "message_err" => $e->getMessage()
                ]);
            }
        }
    }

    public function getWasteRawmaterials(){
        if($this->request->ajax()){
            return DataTables::of(ReprocessWasteTemp::with('dana.danagroup','waste')->where("reprocess_waste_id",$this->request->cc_plant_entry_id)->get())
                        ->addIndexColumn()
                        ->addColumn("dana",function($row){
                            return $row->dana->name;
                        })
                        ->addColumn('dana_group',function($row){
                            if(isset($row->dana->danagroup))
                                return $row->dana->danagroup->name;
                            else
                                return 'Dana Group Not Available';
                        })
                        ->addColumn("waste",function($row){
                            if(isset($row->waste))
                                return $row->waste->name;
                            else 
                                return 'Waste name not available';
                        })
                        ->addColumn("total_quantity",function($row){
                            return $row->dye_quantity + $row->cutter_quantity + $row->melt_quantity;
                        })
                        ->make(true);
        }
    }

    public function getsumquantity($entry_id){
        return response([
            "dana_sum" => ReprocessWasteTemp::where("reprocess_waste_id",$entry_id)->sum("quantity"),
            "total_waste_sum" => ReprocessWasteTemp::where("reprocess_waste_id",$entry_id)->selectRaw('SUM(dye_quantity + cutter_quantity + melt_quantity) as total_sum')->value("total_sum"),
        ]);
    }

    public function finalsubmit(){
        if($this->request->ajax()){
            $data = CCPlantItemsTemp::where("cc_plant_entry_id",$this->request->cc_plant_entry_id)->get();
            DB::beginTransaction();
            foreach($data as $item){
                CCPlantItems::create([
                    "cc_plant_entry_id" => $this->request->cc_plant_entry_id, 
                    'planttype_id' => $item->planttype_id , 
                    "plantname_id" => $item->plantname_id , 
                    "dana_id" => $item->dana_id, 
                    "quantity" => $item->quantity
                ]);

                CCPlantItemsTemp::where("id",$item->id)->delete();
            }
            CCPlantEntry::where("id",$this->request->cc_plant_entry_id)->update([
                "status" => "completed"
            ]);
            DB::commit();
        }
    }
}
