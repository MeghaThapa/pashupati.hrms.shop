<?php

namespace App\Http\Controllers;
use App\Models\PatchStock;
use App\Models\ValveStock;
use App\Models\FabricStock;
use App\Models\CurtexToPatchValFabric;
use App\Models\CommonStockOfThreeStock;
use App\Models\BswFabSendcurtxReceivpatchvalveEntry;
use Illuminate\Http\Request;
use DB;
class ToPatchValveUnlamFabricStockController extends Controller
{
    public function store(Request $request){

        try{
        DB::beginTransaction();
        //save in all common stock of all three
        $commonStockOfThreeStock = new CommonStockOfThreeStock();
        $commonStockOfThreeStock->curtexToPatchValEntry_id = $request->fabCurtxToPatchValEntry_id;
        $commonStockOfThreeStock->curtexToPatchValFabric_id =$request->curtex_to_patch_val_fabric_id;
        $commonStockOfThreeStock->roll_no = $request->roll_no;
        $commonStockOfThreeStock->gross_weight = $request->gross_weight;
        $commonStockOfThreeStock->net_weight = $request->net_weight;
        $commonStockOfThreeStock->meter = $request->meter;
        $commonStockOfThreeStock->avg = $request->average;
        $commonStockOfThreeStock->gram_weight= $request->gram??null;
        $commonStockOfThreeStock->status ="completed";
        $commonStockOfThreeStock->save();


        if($request->fabric_type =="roll"){
            $godam_id =BswFabSendcurtxReceivpatchvalveEntry::find($request->fabCurtxToPatchValEntry_id)->godam_id;
            $fabricgroupdetail=CurtexToPatchValFabric::find($request->curtex_to_patch_val_fabric_id);
            // return $fabricgroupdetail;

            $fabricStock =new FabricStock();
            $fabricStock->name=$fabricgroupdetail->name;
            $fabricStock->slug = $fabricgroupdetail->name;
            $fabricStock->fabricgroup_id= $fabricgroupdetail->fabric_group_id;
            $fabricStock->godam_id = $godam_id;
            $fabricStock->average_wt =$request->average;
            $fabricStock->gram_wt = $request->gram;
            $fabricStock->gross_wt = $request->gross_weight;
            $fabricStock->net_wt = $request->net_weight;
            $fabricStock->meter = $request->meter;
            $fabricStock->roll_no = $request->roll_no;
            $fabricStock->is_laminated="false";
            $fabricStock->curtexToPatchValFabric_id = $request->curtex_to_patch_val_fabric_id;
            $fabricStock->save();

        }else if($request->fabric_type =="patch"){
            $patchStock =new PatchStock();
            $patchStock->curtexToPatchValFabric_id = $request->curtex_to_patch_val_fabric_id;
            $patchStock->roll_no =$request->roll_no;
            $patchStock->gross_weight = $request->gross_weight;
            $patchStock->net_weight = $request->net_weight;
            $patchStock->meter = $request->meter;
            $patchStock->avg = $request->average;
            $patchStock->gram_weight = $request->gram ?? null;
            $patchStock->status= "completed";
            $patchStock->save();

        }else if($request->fabric_type =="valve"){
            $valveStock =new ValveStock();
            $valveStock->curtexToPatchValFabric_id =$request->curtex_to_patch_val_fabric_id;
            $valveStock->roll_no =$request->roll_no;
            $valveStock->gross_weight = $request->gross_weight;
            $valveStock->net_weight = $request->net_weight;
            $valveStock->meter = $request->meter;
            $valveStock->avg = $request->average;
            $valveStock->gram_weight = $request->gram ?? null;
            $valveStock->status= "completed";
            $valveStock->save();
        }
        DB::commit();
        }catch(Exception $ex){
            DB::rollback();
        }
    }

    public function threeDiffStockData(Request $request){
         $commonStockOfThreeStock =CommonStockOfThreeStock::with('curtexToPatchValFabric:id,name,fabric_type')->where('curtexToPatchValEntry_id',$request->bsw_lam_fabcurtexToPatchVal_entry_id)
        ->get();
        $fabricType=$commonStockOfThreeStock[0]->curtexToPatchValFabric->fabric_type;
        $totalmeter = $commonStockOfThreeStock->sum('meter');
        $totalNetWeight = $commonStockOfThreeStock->sum('net_weight');
        $data=[
           'commonStockOfThreeStock'=>$commonStockOfThreeStock,
           'totalmeter'=>$totalmeter,
           'netWeight'=>$totalNetWeight,
           'fabricType'=>$fabricType
        ];
        return $data;
    }
}
