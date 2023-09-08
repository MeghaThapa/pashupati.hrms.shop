<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Godam;
use App\Models\Shift;
use App\Models\DanaName;
use App\Models\Wastages;
use App\Models\WasteStock;
use App\Models\WastageDana;
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
use App\Services\NepaliConverter;
use App\Models\ReprocessWasteTemp;
use Illuminate\Support\Facades\DB;
use App\Models\ReprocessWastageDetail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Exceptions\Exception;

class ReprocessWasteController extends Controller
{

    protected $neDate;
    protected $request;
    protected $entry_id;

    public function __construct(NepaliConverter $neDate,Request $request){
        $this->neDate = $neDate;
        $this->request = $request;
    }

    /************************ Entry ******************************/
    public function entryindex()
    {
        $godams = Godam::where("status", "active")->get();
        $getData = DB::table("reprocess_wastes")->first();
        if (isset($getData)) {
            $entries = DB::table("reprocess_wastes")->latest()->first()->id;
            $receipt_number = "CC-" . getNepaliDate(date("Y-m-d")) . "-" . $entries + 1;
        } else {
            $receipt_number = "CC-" . getNepaliDate(date("Y-m-d")) . "-1";
        }
        return view("admin.reprocess_waste.index")->with([
            "receipt_number" => $receipt_number,
            "godams" => $godams
        ]);
    }

    public function entryindexajax(Request $request)
    {
        if ($request->ajax()) {
            $query = ReprocessWaste::query();

            if ($request->start_date && $request->end_date) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $query->whereBetween('date', [$start_date, $end_date]);
            }

            if($request->godam_id){
                $query->where('godam_id',(int)$request->godam_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == "Running") {
                        return '<span class="badge badge-primary">Running</span>';
                    } else {
                        return '<span class="badge badge-success">Completed</span>';
                    }
                })
                ->addColumn("action", function ($row) {
                    if ($row->status == "Running") {
                        $createUrl = route('reprocess.wastage.create', $row->id);
                        return "<div class='btn-group'>
                                        <a href='{$createUrl}' class='btn btn-primary ' data-id='{$row->id}'><i class='fa fa-plus' aria-hidden='true'></i></a>
                                        <button class='btn btn-danger delete-cc-entry' data-id='{$row->id}'><i class='fa fa-trash' aria-hidden='true'></i></button>
                                    </div>";
                    } else {
                        return "<div class='btn-group'>
                                        <button class='btn btn-secondary view-cc' data-id='{$row->id}'><i class='fa fa-eye' aria-hidden='true'></i></button>
                                    </div>";
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function entrystore(Request $request)
    {
        $request->validate([
            "receipt_number" => "required|unique:ccplantentry,receipt_number",
            "godam_id" => "required",
            "date" => "required|date_format:Y-m-d",
            "remarks" => 'nullable|max:255',
        ]);

        ReprocessWaste::create($request->only(['receipt_number', 'godam_id', 'date', 'remarks']));

        return back()->with("success", "Created Successfully");
    }

    public function create($entry_id)
    {
        $reprocessWaste = ReprocessWaste::with('godam')->where("id", $entry_id)->first();
        $wasteIds = WasteStock::where('godam_id', $reprocessWaste->godam_id)->pluck('waste_id');
        $wastages = Wastages::with('wastageStock')->whereIn('id', $wasteIds)->get();

        return  view("admin.reprocess_waste.create")->with([
            "reprocessWaste" => $reprocessWaste,
            "shifts" => Shift::get(),
            "godam_id" => $reprocessWaste->godam_id,
            "wastages" => $wastages,
        ]);
    }
    /************************ Entry ******************************/

    public function getPlantType(Request $request)
    {
        if ($request->ajax()) {
            return response([
                "planttype" => ProcessingStep::where("godam_id", $request->godam_id)->get()
            ]);
        }
    }
    public function getPlantName($planttype_id)
    {
        if ($this->request->ajax()) {
            return response([
                "planttype" => ProcessingSubcat::where("processing_steps_id", $this->request->planttype_id)->get()
            ]);
        }
    }

    public function addWaste()
    {
        $reprocessWaste = ReprocessWaste::where('id',$this->request->cc_plant_entry_id)->firstOrFail();
        if ($this->request->ajax()) {
            try {

                DB::beginTransaction();
                WasteStock::where('godam_id',$reprocessWaste->godam_id)->where('waste_id',$this->request->wastage_id)->decrement('quantity_in_kg',$this->request->quantity);

                $wasteStock = WasteStock::where('godam_id',$reprocessWaste->godam_id)->where('waste_id',$this->request->wastage_id)->first();

                ReprocessWasteTemp::create([
                    "reprocess_waste_id" => $this->request->cc_plant_entry_id,
                    "planttype_id" => $this->request->planttype_id,
                    "plantname_id" => $this->request->plantname_id,
                    "wastage_id" => $this->request->wastage_id,
                    "quantity" => $this->request->quantity,
                ]);


                DB::commit();

                return response(['status'=>true,'data'=>$wasteStock]);

            } catch (Exception $e) {
                return response([
                    "message_err" => $e->getMessage()
                ]);
            }
        }
    }

    public function restoreWastage()
    {
        if($this->request->ajax()){
            try{
                $reprocessWaste = ReprocessWaste::findOrFail($this->request->cc_plant_entry_id);
                $reprocessWasteTemp = ReprocessWasteTemp::whereId($this->request->restore_id)->firstOrFail();

                DB::beginTransaction();

                WasteStock::where('godam_id',$reprocessWaste->godam_id)
                                        ->where('waste_id',$reprocessWasteTemp->wastage_id)
                                        ->increment('quantity_in_kg',$reprocessWasteTemp->quantity);

                $reprocessWasteTemp->delete();

                $wastageStock =  WasteStock::where('godam_id',$reprocessWaste->godam_id)
                ->where('waste_id',$reprocessWasteTemp->wastage_id)->first();

                DB::commit();
                return response(['status'=>true,'data'=>$wastageStock]);

            }catch(Exception $e){
                return response([
                    "message_err" => $e->getMessage()
                ]);
            }
        }

    }

    public function wastageStockEntry(Request $request){

        try{

            DB::beginTransaction();


            $reprocessWaste = ReprocessWaste::findOrFail($request->cc_plant_entry_id);

            ReprocessWastageDetail::create([
                'reprocess_waste_id' => $reprocessWaste->id,
                'dye_quantity' => $request->dye_quantity,
                'cutter_quantity' => $request->cutter_quantity,
                'melt_quantity' => $request->melt_quantity,
            ]);

            $total_quantity = (int)$request->dye_quantity + (int)$request->cutter_quantity + (int)$request->melt_quantity;

            $wastage = Wastages::where('name','erema lumps')->first();

            WasteStock::where('godam_id',$reprocessWaste->godam_id)->where('waste_id',$wastage->id)->increment('quantity_in_kg',$total_quantity);

            $wastageStock = WasteStock::where('godam_id',$reprocessWaste->godam_id)->where('waste_id',$wastage->id)->first();

            DB::commit();

            return response(['status'=>true,'data'=>$wastageStock]);

        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function createdWastage($entry_id){
        if($this->request->ajax()){
            return DataTables::of(ReprocessWastageDetail::where("reprocess_waste_id",$entry_id)->get())
                        ->addIndexColumn()
                        ->addColumn("total_quantity",function($row){
                            return $row->dye_quantity + $row->cutter_quantity + $row->melt_quantity;
                        })
                        ->addColumn('action',function($row){
                            return "<button data-id='{$row->id}' class='btn btn-sm btn-danger wastage_recycle_remove'>
                                        <i class='fa fa-recycle'></i>
                                    </button>";
                        })
                        ->make(true);
        }
    }

    public function getWasteRawmaterials()
    {
        if ($this->request->ajax()) {
            return DataTables::of(ReprocessWasteTemp::with('wastage')->where("reprocess_waste_id", $this->request->cc_plant_entry_id)->get())
                ->addIndexColumn()
                ->addColumn("wastage", function ($row) {
                    if (isset($row->wastage))
                        return $row->wastage->name;
                    else
                        return 'Waste name not available';
                })
                ->addColumn("quantity", function ($row) {
                    return $row->quantity;
                })
                ->addColumn("action",function($row){
                    return "<button data-id='{$row->id}' class='btn btn-sm btn-danger waste_recycle'>
                                <i class='fa fa-recycle'></i>
                            </button>";
                })
                ->make(true);
        }
    }

    public function getsumquantity($entry_id)
    {
        return response([
            "dana_sum" => ReprocessWasteTemp::where("reprocess_waste_id", $entry_id)->sum("quantity"),
        ]);
    }

    public function finalsubmit()
    {
        if ($this->request->ajax()) {
            $data = CCPlantItemsTemp::where("cc_plant_entry_id", $this->request->cc_plant_entry_id)->get();
            DB::beginTransaction();
            foreach ($data as $item) {
                CCPlantItems::create([
                    "cc_plant_entry_id" => $this->request->cc_plant_entry_id,
                    'planttype_id' => $item->planttype_id,
                    "plantname_id" => $item->plantname_id,
                    "dana_id" => $item->dana_id,
                    "quantity" => $item->quantity
                ]);

                CCPlantItemsTemp::where("id", $item->id)->delete();
            }
            CCPlantEntry::where("id", $this->request->cc_plant_entry_id)->update([
                "status" => "completed"
            ]);
            DB::commit();
        }
    }

    public function removeRecycleWastage(Request $request){

        try{
            DB::beginTransaction();

            $reprocessWaste = ReprocessWaste::findOrFail($request->cc_plant_entry_id);

            $reprocessWasteDetail = ReprocessWastageDetail::findOrFail($request->restore_wastage_id);

            $wastage = Wastages::where('name','erema lumps')->first();

            $total_quantity = $reprocessWasteDetail->dye_quantity + $reprocessWasteDetail->cutter_quantity + $reprocessWasteDetail->melt_quantity;

            WasteStock::where('godam_id',$reprocessWaste->godam_id)->where('waste_id',$wastage->id)->decrement('quantity_in_kg',$total_quantity);

            $reprocessWasteDetail->delete();

            DB::commit();

            $wasteStock = WasteStock::where('godam_id',$reprocessWaste->godam_id)->where('waste_id',$wastage->id)->first();

            return response(['status'=>true,'data'=>$wasteStock]);

        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function updateDana(Request $request){

        try{

            DB::beginTransaction();

            $reprocessWastage = ReprocessWaste::findOrFail($this->request->cc_plant_entry_id);

            WastageDana::create([
                "dana_id" => $this->request->dana_name_id,
                "dana_group_id" => $this->request->dana_group_id,
                "reprocess_wastage_id" => $reprocessWastage->id,
                "quantity" => $this->request->quantity,
                "planttype_id" => $this->request->plant_type_id,
                "plantname_id" => $this->request->plant_name_id,
            ]);

            RawMaterialStock::where('godam_id',$reprocessWastage->godam_id)->where('dana_name_id',$request->dana_name_id)->increment('quantity',$request->quantity);

            $rawMaterialStock =  RawMaterialStock::where('godam_id',$request->godam_id)
                ->where('dana_name_id',$request->dana_name_id)->first();

            DB::commit();
            return response(['status'=>true,'data'=>$rawMaterialStock]);

        }catch(\Exception $e){
            dd($e->getMessage());
        }

    }

    public function createdDana($entry_id){
        if($this->request->ajax()){
            return DataTables::of(WastageDana::with('danaName','danaGroup')->where("reprocess_wastage_id",$entry_id)->get())
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

    public function removeRecycleDana(Request $request){
        try{
            DB::beginTransaction();
            $ccPlantDanaCreation =  WastageDana::findOrFail($request->restore_recycle_id);

            $rawMaterialStock =  RawMaterialStock::where('godam_id',$request->godam_id)
                ->where('dana_name_id',$ccPlantDanaCreation->dana_id)->first();

            if($rawMaterialStock->quantity > $ccPlantDanaCreation->quantity){
                RawMaterialStock::where('godam_id',$request->godam_id)->where('dana_name_id',$ccPlantDanaCreation->dana_id)->decrement('quantity',$ccPlantDanaCreation->quantity);
            }else{
                RawMaterialStock::where('godam_id',$request->godam_id)->where('dana_name_id',$ccPlantDanaCreation->dana_id)->update(['quantity'=>0]);
            }

            $rawMaterialStock =  RawMaterialStock::where('godam_id',$request->godam_id)
                ->where('dana_name_id',$ccPlantDanaCreation->dana_id)->first();

            $ccPlantDanaCreation->delete();

            DB::commit();
            return response(['status'=>true,'data'=>$rawMaterialStock]);

        }catch(\Exception $e){
            dd($e->getMessage());
        }

    }

    public function entryDestroy(Request $request){
        $reprocessWastage = ReprocessWaste::findOrFail($request->id);
        try{

            // since we add dana in wastage view page so we need to decrement wastage stock
            $wastageDanas = WastageDana::where('reprocess_wastage_id',$reprocessWastage->id)->get();
            foreach($wastageDanas as $dana){
                RawMaterialStock::where('godam_id',$reprocessWastage->godam_id)->where('dana_name_id',$dana->dana_id)->decrement('quantity',$dana->quantity);
                $dana->delete();
            }

            // Since wastage consumption we need to increment wastage stock while reversing
            $reprocessWasteTemps = ReprocessWasteTemp::where('reprocess_waste_id',$reprocessWastage->id)->get();
            foreach($reprocessWasteTemps as $reprocessTemp){
                WasteStock::where('godam_id',$reprocessWastage->godam_id)->where('waste_id',$reprocessTemp->wastage_id)->increment('quantity_in_kg',$reprocessTemp->quantity);
                $reprocessTemp->delete();
            }

            // Now since we have added stocks to erema lumps we need to reverse it
            $reprocessWastageDetails = ReprocessWastageDetail::where('reprocess_waste_id',$reprocessWastage->id)->get();
            $wastage = Wastages::where('name','erema lumps')->first();
            foreach($reprocessWastageDetails as $detail){
                $total_quantity =  (int)$detail->dye_quantity + (int)$detail->cutter_quantity + (int)$detail->melt_quantity;
                WasteStock::where('godam_id',$reprocessWastage->godam_id)->where('waste_id',$wastage->id)->decrement('quantity_in_kg',$total_quantity);
                $detail->delete();
            }

            $reprocessWastage->delete();

            DB::commit();
            return response(['status'=>true,'message'=> 'Reprocess Wastage removed successfully']);

        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

}
