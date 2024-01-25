<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurtexToPatchValFabric;

class CurtexToPatchValFabricController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "name"=>'required|unique:curtex_to_patch_val_fabrics',
            "fabric_type"=>"required",
            "fabric_group_id"=>"required",
            "standard_weight"=>"required",
        ]);
        $curtexToPatchValFabric= new CurtexToPatchValFabric();
        $curtexToPatchValFabric->name =$request->name;
        $curtexToPatchValFabric->fabric_group_id  = $request->fabric_group_id;
        $curtexToPatchValFabric->standard_wt_gm = $request->standard_weight;
        $curtexToPatchValFabric->fabric_type = $request->fabric_type;
        $curtexToPatchValFabric->status = $request->status;
        $curtexToPatchValFabric->save();
        return $curtexToPatchValFabric;
    }
    public function getcrtxToPtchValFabricName(Request $request){
        $curtexToPatchValFabric = CurtexToPatchValFabric::where('fabric_type',$request->fabric_type)->get(['id','name']);
        return $curtexToPatchValFabric;
    }
}
