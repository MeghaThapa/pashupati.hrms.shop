<?php

namespace App\Http\Controllers;

use App\Models\Godam;
use App\Models\Shift;
use App\Models\DanaName;
use App\Models\Wastages;
use App\Models\WasteStock;
use App\Models\RawMaterial;
use App\Models\CCPlantEntry;
use App\Models\CCPlantItems;
use Illuminate\Http\Request;
use App\Models\ProcessingStep;
use App\Models\CCPlantItemsTemp;
use App\Models\ProcessingSubcat;
use App\Models\RawMaterialStock;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\CCPlantDanaCreation;
use App\Models\CCPlantDanaCreationTemp;
use App\Models\CcPlantWastage;
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
            return DataTables::of(CCPlantEntry::with('godam','danaName.danagroup')->get())
                    ->addIndexColumn()
                    ->addColumn("godam_name",function($row){
                        return $row->godam->name;
                    })
                    ->addColumn("dana_name",function($row){
                        return $row->danaName->name;
                    })
                    ->addColumn('dana_group',function($row){
                        return $row->danaName->danagroup->name;
                    })
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
        $request = $this->request;
        $this->request->validate([
            "godam_id" => "required",
            "date" => "required",
            "receipt_number" => "required|unique:ccplantentry",
            "date_np" => "required",
            "dana_name" => "required",
            "available_quantity" => "required",
            "dana_quantity" => function ($attribute, $value, $fail) use ($request) {
                $availableQuantity = $request->input('available_quantity');
                if (floatval($value) >= floatval($availableQuantity)) {
                    $fail("The $attribute must be less than the available quantity.");
                }
            },
        ]);


        $rawMaterial = RawMaterialStock::where('godam_id',$request->godam_id)->where('dana_name_id',$request->dana_name)->firstOrFail();
        if($rawMaterial->quantity >= $request->dana_quantity){
            $rawMaterial->quantity = $rawMaterial->quantity - $request->dana_quantity;
            $rawMaterial->save();
        }else{
            return back()->with('error','Raw material is out of Stock');
        }

        CCPlantEntry::create([
            "godam_id" => $this->request->godam_id,
            "date" => $this->request->date,
            "date_np" => $this->request->date_np,
            "receipt_number" => $this->request->receipt_number ,
            "dana_name_id" => $this->request->dana_name ,
            "dana_quantity" => $this->request->dana_quantity,
            "remarks" => $this->request->remarks
        ]);

        return back()->with("success","Created Successfully");
    }

    public function create($entry_id){
        $ccPlantEntry = CCPlantEntry::with('godam')->where("id",$entry_id)->firstOrFail();
        $danaNameIds = RawMaterialStock::where('godam_id',$ccPlantEntry->godam_id)->pluck('dana_name_id');
        $danaNames = DanaName::with('danagroup','rawMaterialStock')->whereIn('id',$danaNameIds)->get();
        $wastages = Wastages::all();
        return  view("admin.cc_plant.create")->with([
            "ccPlantEntry" => $ccPlantEntry,
            "danaNames" => $danaNames,
            "entry_id" => $entry_id,
            "shift" => Shift::get(),
            'wastages' => $wastages,
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

    public function danaNameFromStock($godam_id){
        $rawMaterials = RawMaterialStock::with('danaName','danaGroup')->where('godam_id',$godam_id)->get();
        return response(['status'=>true,'data'=>$rawMaterials]);
    }

    public function danaFromDanaGroupGodam(Request $request){
        $danaIds = RawMaterialStock::with('danaName','danaGroup')->where('godam_id',$request->godam_id)->pluck('dana_name_id');
        $danaNames = DanaName::where('dana_group_id',$request->dana_group_id)->whereIn('id',$danaIds)->get();
        return response(['status'=>true,'data'=>$danaNames]);
    }

    public function addDana(){
        if($this->request->ajax()){
            try{
                $ccPlantEntry = CCPlantEntry::findOrFail($this->request->cc_plant_entry_id);

                DB::beginTransaction();

                RawMaterialStock::where('godam_id',$ccPlantEntry->godam_id)
                                        ->where('dana_name_id',$this->request->dana_id)
                                        ->decrement('quantity',$this->request->quantity);
                $rawMaterialStock =  RawMaterialStock::where('godam_id',$ccPlantEntry->godam_id)
                ->where('dana_name_id',$this->request->dana_id)->first();

                CCPlantItemsTemp::create([
                    "cc_plant_entry_id" => $this->request->cc_plant_entry_id,
                    "planttype_id" => $this->request->planttype_id,
                    "plantname_id" => $this->request->plantname_id,
                    "dana_id" => $this->request->dana_id,
                    "quantity" => $this->request->quantity,
                ]);

                DB::commit();
                return response(['status'=>true,'data'=>$rawMaterialStock]);

            }catch(Exception $e){
                return response([
                    "message_err" => $e->getMessage()
                ]);
            }
        }
    }

    public function removeDana()
    {
        if($this->request->ajax()){
            try{
                $ccPlantEntry = CCPlantEntry::findOrFail($this->request->cc_plant_entry_id);
                $ccPlantTemp = CCPlantItemsTemp::whereId($this->request->restore_id)->firstOrFail();

                DB::beginTransaction();

                RawMaterialStock::where('godam_id',$ccPlantEntry->godam_id)
                                        ->where('dana_name_id',$ccPlantTemp->dana_id)
                                        ->increment('quantity',$ccPlantTemp->quantity);

                $ccPlantTemp->delete();

                $rawMaterialStock =  RawMaterialStock::where('godam_id',$ccPlantEntry->godam_id)
                ->where('dana_name_id',$this->request->dana_id)->first();

                DB::commit();
                return response(['status'=>true,'data'=>$rawMaterialStock]);

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
                        ->addColumn('action',function($row){
                            return "<button data-id='{$row->id}' class='btn btn-sm btn-danger item_recycle'>
                                        <i class='fa fa-recycle'></i>
                                    </button>";
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

    public function updateDana(Request $request){

        try{

            DB::beginTransaction();

            CCPlantDanaCreationTemp::create([
                "dana_name_id" => $this->request->dana_name_id,
                "dana_group_id" => $this->request->dana_group_id,
                "cc_plant_entry_id" => $this->request->cc_plant_entry_id,
                "quantity" => $this->request->quantity,
                "plant_type_id" => $this->request->plant_type_id,
                "plant_name_id" => $this->request->plant_name_id,
            ]);

            RawMaterialStock::where('godam_id',$request->godam_id)->where('dana_name_id',$request->dana_name_id)->increment('quantity',$request->quantity);

            $rawMaterialStock =  RawMaterialStock::where('godam_id',$request->godam_id)
                ->where('dana_name_id',$request->dana_name_id)->first();

            DB::commit();
            return response(['status'=>true,'data'=>$rawMaterialStock]);

        }catch(\Exception $e){
            dd($e->getMessage());
        }

    }

    public function removeRecycleDana(Request $request){

        try{

            DB::beginTransaction();

            $ccPlantDanaCreation =  CCPlantDanaCreationTemp::findOrFail($request->restore_recycle_id);

            RawMaterialStock::where('godam_id',$request->godam_id)->where('dana_name_id',$ccPlantDanaCreation->dana_name_id)->decrement('quantity',$ccPlantDanaCreation->quantity);

            $rawMaterialStock =  RawMaterialStock::where('godam_id',$request->godam_id)
                ->where('dana_name_id',$request->dana_name_id)->first();

            $ccPlantDanaCreation->delete();

            DB::commit();
            return response(['status'=>true,'data'=>$rawMaterialStock]);

        }catch(\Exception $e){
            dd($e->getMessage());
        }

    }

    public function createdDana($entry_id){
        if($this->request->ajax()){
            return DataTables::of(CCPlantDanaCreationTemp::with('danaName','danaGroup')->where("cc_plant_entry_id",$entry_id)->get())
                        ->addIndexColumn()
                        ->addColumn("dana_group",function($row){
                            return $row->danaGroup->name;
                        })
                        ->addColumn("dana_name",function($row){
                            return $row->danaName->name;
                        })
                        ->addColumn('action',function($row){
                            return "<button data-id='{$row->id}' class='btn btn-sm btn-danger item_recycle_remove'>
                                        <i class='fa fa-recycle'></i>
                                    </button>";
                        })
                        ->make(true);
        }
    }
    
    public function createdWastage($entry_id){
        if($this->request->ajax()){
            return DataTables::of(CcPlantWastage::with('wastage')->where("ccplantentry_id",$entry_id)->get())
                        ->addIndexColumn()
                        ->addColumn("wastage",function($row){
                            return $row->wastage->name;
                        })
                        ->addColumn('action',function($row){
                            return "<button data-id='{$row->id}' class='btn btn-sm btn-danger wastage_recycle_remove'>
                                        <i class='fa fa-recycle'></i>
                                    </button>";
                        })
                        ->make(true);
        }
    }

    public function updateWastageStock(Request $request){

        try{
            DB::beginTransaction();

            $ccPlantEntry = CCPlantEntry::findOrFail($request->cc_plant_entry_id);
            
            CcPlantWastage::create([
                'ccplantentry_id' => $ccPlantEntry->id,
                'godam_id' => $ccPlantEntry->godam_id,
                'wastage_id' => $request->wastage_id,
                'quantity' => $request->quantity,
            ]);

            WasteStock::where('godam_id',$ccPlantEntry->godam_id)->where('waste_id',$request->wastage_id)->increment('quantity_in_kg',$request->quantity);

            DB::commit();

            return response(['status'=>true,'message'=>'WastageStock Updated']);

        }catch(\Exception $e){
            dd($e->getMessage());
        }

    }
    
    public function removeRecycleWastage(Request $request){

        try{
            DB::beginTransaction();

            $ccPlantEntry = CCPlantEntry::findOrFail($request->cc_plant_entry_id);
            
            $ccPlantWastage = CcPlantWastage::findOrFail($request->restore_wastage_id);

            WasteStock::where('godam_id',$ccPlantEntry->godam_id)->where('waste_id',$ccPlantWastage->wastage_id)->decrement('quantity_in_kg',$ccPlantWastage->quantity);

            $ccPlantWastage->delete();

            DB::commit();

            return response(['status'=>true,'message'=>'WastageStock Updated']);

        }catch(\Exception $e){
            dd($e->getMessage());
        }

    }

}
