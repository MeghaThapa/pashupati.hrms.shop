<?php

namespace App\Http\Controllers;
use App\Models\BswFabSendcurtxReceivpatchvalveEntry;
use App\Models\PatchValDanaConsumpt;
use App\Models\AutoLoadItemStock;
use App\Models\DanaName;
use Illuminate\Http\Request;

use DB;

class PatchValDanaConsumptController extends Controller
{

    public function store(Request $request){
        try{
       //adding to consumpt table
        DB::beginTransaction();
        $entryData=BswFabSendcurtxReceivpatchvalveEntry::find($request->bswFabSendcurtxReceivpatchvalveEntry_id);
        $patchValDanaConsumpt = new PatchValDanaConsumpt();
        $patchValDanaConsumpt->curtexToPatchValEntry_id = $request->bswFabSendcurtxReceivpatchvalveEntry_id;
        $patchValDanaConsumpt->godam_id =$entryData->godam_id;
        $patchValDanaConsumpt->plantType_id =$entryData->plant_type_id ;
        $patchValDanaConsumpt->plantName_id =$entryData->plant_name_id;
        $patchValDanaConsumpt->shift_id =$entryData-> shift_id;
        $patchValDanaConsumpt->dana_name_id =$request->dana_name_id;
        $patchValDanaConsumpt->quantity =$request->quantity;
        $patchValDanaConsumpt->save();

        $autoloader =AutoLoadItemStock::where('from_godam_id',$entryData->godam_id)
        ->where('plant_type_id',$entryData->plant_type_id)
        ->where('plant_name_id',$entryData-> plant_name_id)
        ->where('shift_id',$entryData-> shift_id)
        ->where('dana_name_id',$request->dana_name_id)
        ->first();
         $autoloader->quantity = $autoloader->quantity - $request->quantity;

         if($autoloader->quantity <=0){
             $autoloader->delete();
         }else{
             $autoloader->save();
         }
        DB::commit();
           return response()->json(['successMessage' => 'Data saved successfully'], 200);
        }catch(Exception $ex){
            DB::rollback();
            return response()->with('error', 'An error occurred while saving data');
        }
    }

    public function getDanaConsumptData(Request $request){
        $danaConsumptData = PatchValDanaConsumpt::with(['godam:id,name','danaName:id,name'])
        ->where('curtexToPatchValEntry_id',$request->bsw_lam_fabcurtexToPatchVal_entry_id)
        ->get();
        return $danaConsumptData;

    }
    public function delete($id){
        try{
        DB::beginTransaction();
        $danaConsumptData=PatchValDanaConsumpt::find($id);
        $danaName =DanaName::find($danaConsumptData->dana_name_id);
        $autoloaderStock = AutoLoadItemStock::where('from_godam_id',$danaConsumptData->godam_id)
        ->where('plant_type_id',$danaConsumptData->plantType_id)
        ->where('plant_name_id',$danaConsumptData->plantName_id)
        ->where('shift_id',$danaConsumptData->shift_id)
        ->where('dana_name_id',$danaConsumptData->dana_name_id)
        ->first();
        if($autoloaderStock && $autoloaderStock !=null){
            $autoloaderStock->quantity = $danaConsumptData->quantity;
            $autoloaderStock->save();
        }else{
            $autoloaderStock = new AutoLoadItemStock();
            $autoloaderStock->from_godam_id = $danaConsumptData->godam_id;
            $autoloaderStock->plant_type_id = $danaConsumptData->plantType_id;
            $autoloaderStock->plant_name_id = $danaConsumptData->plantName_id ;
            $autoloaderStock->shift_id  = $danaConsumptData->shift_id;
            $autoloaderStock->dana_group_id =$danaName->dana_group_id;
            $autoloaderStock->dana_name_id = $danaConsumptData->dana_name_id;
            $autoloaderStock->quantity = $danaConsumptData->quantity;
            $autoloaderStock->save();
        }

         $danaConsumptData->delete();
        DB::commit();
        }catch(Exception $ex){
        DB::rollback();
        }
    }
}
