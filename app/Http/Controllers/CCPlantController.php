<?php

namespace App\Http\Controllers;

use App\Models\CCPlantDanaCreation;
use App\Models\CCPlantDanaCreationTemp;
use App\Models\CCPlantEntry;
use App\Models\CCPlantItems;
use App\Models\CCPlantItemsTemp;
use App\Models\DanaName;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\RawMaterialStock;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Exceptions\Exception;

class CCPlantController extends Controller
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
        return view("admin.cc_plant.index")->with([
            "receipt_number" => $receipt_number,
            "godam" => $godam
        ]);
    }

    public function entryindexajax(){
        if($this->request->ajax()){
            return DataTables::of(DB::table("ccplantentry")->get())
                    ->addIndexColumn()
                    ->addColumn("action",function($row){
                        if($row->status == "pending"){
                            return "<div class='btn-group'>
                                        <button class='btn btn-primary create-cc' data-id='{$row->id}'><i class='fa fa-plus' aria-hidden='true'></i></button>
                                        <button class='btn btn-danger delete-cc-entry' data-id='{$row->id}'><i class='fa fa-trash' aria-hidden='true'></i></button>
                                    </div>";
                        }else{
                            return "<div class='btn-group'>
                                        <button class='btn btn-secondary view-cc' data-id='{$row->id}'><i class='fa fa-eye' aria-hidden='true'></i></button>
                                    </div>";
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function entrystore(){
        $this->request->validate([
            "godam_id" => "required",
            "date" => "required",
            "receipt_number" => "required|unique:ccplantentry",
            "date_np" => "required",
        ]);

        CCPlantEntry::create([
            "godam_id" => $this->request->godam_id,
            "date" => $this->request->date,
            "date_np" => $this->request->date_np,
            "receipt_number" => $this->request->receipt_number ,
            "remarks" => $this->request->remarks
        ]);

        return back()->with("success","Created Successfully");
    }

    public function create($entry_id){
        $godam = Godam::all();
        $rawmaterials = RawMaterialStock::all();
        $data = CCPlantEntry::where("id",$entry_id)->first();
        return  view("admin.cc_plant.create")->with([
            "data" => $data,
            "entry_id" => $entry_id,
            "shift" => Shift::get(),
            "godam" => $godam,
            "rawmaterials" => $rawmaterials
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

    public function addDana(){
        if($this->request->ajax()){
            try{
                CCPlantItemsTemp::create([
                    "cc_plant_entry_id" => $this->request->cc_plant_entry_id,
                    "planttype_id" => $this->request->planttype_id,
                    "plantname_id" => $this->request->plantname_id,
                    "dana_id" => $this->request->dana_id,
                    "quantity" => $this->request->quantity,
                ]);
            }catch(Exception $e){
                return response([
                    "message_err" => $e->getMessage()
                ]);
            }
        }
    }

    public function getccrawmaterials(){
        if($this->request->ajax()){
            return DataTables::of(CCPlantItemsTemp::where("cc_plant_entry_id",$this->request->cc_plant_entry_id)->get())
                        ->addIndexColumn()
                        ->addColumn("dana",function($row){
                            return $row->dananame->name;
                        })
                        ->make(true);
        }
    }

    public function getsumquantity($entry_id){
        return response([
            "sum" => CCPlantItemsTemp::where("cc_plant_entry_id",$entry_id)->sum("quantity")
        ]);
    }

    public function finalsubmit(){
        if($this->request->ajax()){
            $data = CCPlantItemsTemp::where("cc_plant_entry_id",$this->request->cc_plant_entry_id);
            $godam = $data->first()->entry->godam_id;
            DB::beginTransaction();
            foreach($data->get() as $item){

                $dana = DanaName::where("id",$item->dana_id)->first();

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

    public function danacreation(){
        if($this->request->ajax()){
            CCPlantDanaCreationTemp::create([
                "dananame" => $this->request->dana_name,
                "danagroup_id" => $this->request->dana_group,
                "entry_id" => $this->request->cc_plant_entry_id,
                "quantity" => $this->request->quantity,
                "planttype_id" => $this->request->planttype_id,
                "plantname_id" => $this->request->plantname_id
            ]);
        }
    }
    public function createdDana($entry_id){
        if($this->request->ajax()){
            return $entry_id;
        }
    }
}
