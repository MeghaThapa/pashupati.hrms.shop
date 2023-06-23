<?php

namespace App\Http\Controllers;

use App\Models\RawMaterialItem;
use App\Models\RawMaterialStock;
use App\Models\RawMaterial;
use App\Models\Setupstorein;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RawMaterialItemController extends Controller
{
    public function store(Request $request)
    {
            $request->validate([
            'rawMaterial_id' => 'required',
            'lorry_no' => 'required',
            'dana_group_id' => 'required',
            'dana_name_id' => 'required',
            'quantity_in_kg' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $rawMaterialItem = new RawMaterialItem();
            $rawMaterialItem->raw_material_id = $request->rawMaterial_id;
            $rawMaterialItem->lorry_no = $request->lorry_no;
            $rawMaterialItem->dana_group_id = $request->dana_group_id;
            $rawMaterialItem->dana_name_id = $request->dana_name_id;
            $rawMaterialItem->quantity = $request->quantity_in_kg;
            $rawMaterialItem->save();


            $rawMaterial =RawMaterial::find($request->rawMaterial_id);

            if($request && json_decode($request->fromStockBool) === true){
                $rawMaterialStock= RawMaterialStock::where('godam_id', $rawMaterial->from_godam_id)
                ->where('dana_name_id', $rawMaterialItem->dana_name_id)
                ->where('dana_group_id',$rawMaterialItem->dana_group_id)
                ->first();
                if($rawMaterialStock->quantity- $request->quantity_in_kg !=0){
                     return response()->json([
                        'message'=>'you should transfer entire raw material items to anothe godam'
                    ],500);
                }
                $rawMaterialStock->quantity =   $rawMaterialStock->quantity - $rawMaterialItem->quantity;
                if($rawMaterialStock->quantity <=0){
                    $rawMaterialStock->delete();
                }
                else{
                    $rawMaterialStock->save();
                }
            }
            RawMaterialStock::createRawMaterialStock($rawMaterialItem,$rawMaterial->to_godam_id);
            $rawMaterialItem = RawMaterialItem::with('danaName', 'danaGroup')->find($rawMaterialItem->id);
            DB::commit();
            return response()->json([
                'message' => 'RawMaterial created successfully ',
                'rawMaterialItem' => $rawMaterialItem
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($e, 400);
        }
    }
    public function delete(Request $request,$rawMaterialItem_id)
    {
        if (!$rawMaterialItem_id) {
            return response()->json(
                [
                    'message' => 'no item found'
                ],
                400
            );
        }
        try {
            DB::beginTransaction();

        $rawMaterialItem = RawMaterialItem::find($rawMaterialItem_id);
        $rawMaterial=RawMaterial::find($request->rawMaterial_id);

        $rawMaterialStock = RawMaterialStock::where('godam_id', $rawMaterial->to_godam_id)
        ->where('dana_group_id',$rawMaterialItem->dana_group_id)
        ->where('dana_name_id', $rawMaterialItem->dana_name_id)
        ->first();
        $rawMaterialStock->quantity -= $rawMaterialItem->quantity;
        if ($rawMaterialStock->quantity == 0) {
            $rawMaterialStock->delete();
        } else {
            $rawMaterialStock->save();
        }

        if(json_decode($request->fromRawMaterialStock)===true){
            $rawMaterialStockFrom =RawMaterialStock::where('godam_id', $rawMaterial->from_godam_id)
            ->where('dana_group_id',$rawMaterialItem->dana_group_id)
            ->where('dana_name_id', $rawMaterialItem->dana_name_id)
            ->first();
            if($rawMaterialStockFrom &&$rawMaterialStockFrom !=null){
                $rawMaterialStockFrom->quantity += $rawMaterialItem->quantity;
                $rawMaterialStockFrom->save();
            }
            else{
                $newRawMaterialStock = new RawMaterialStock();
                $newRawMaterialStock->godam_id = $rawMaterial->from_godam_id;
                $newRawMaterialStock->dana_group_id =$rawMaterialItem->dana_group_id;
                $newRawMaterialStock->dana_name_id = $rawMaterialItem->dana_name_id;
                $newRawMaterialStock->quantity =$rawMaterialItem->quantity;
                $newRawMaterialStock->save();
            }
        }
        $rawMaterialItem->delete();
        DB::commit();
        return response()->json(
            [
                'message' => 'Raw Material Item and its stock deleted successfully'
            ],
            200
        );
    } catch (Exception $e) {
        DB::rollback();
        return response()->json($e, 400);
    }
    }
    public function update(Request $request)
    {
        $validator = $request->validate([
            'raw_material_item_id' => 'required',
            'lorryNumber' => 'required',
            'danaGroup_Name_id' => 'required',
            'dana_name_id' => 'required',
            'quantity' => 'required',
        ]);

        $rawMaterialItem = RawMaterialItem::where('id', $request->raw_material_item_id)->first();

        $department_id =RawMaterial::find($rawMaterialItem->raw_material_id)->department_id;

        $RawMaterialStock = RawMaterialStock::where('department_id', $department_id)
        ->where('dana_name_id', $rawMaterialItem->dana_name_id)->first();

        $RawMaterialStock->quantity -= $rawMaterialItem->quantity;
        if ($RawMaterialStock->quantity == 0) {
            $RawMaterialStock->delete();
        }
        // if doesn't match with old raw item
        if ($request->dana_name_id != $rawMaterialItem->dana_name_id) {
            $stock = RawMaterialStock::where('department_id', $department_id)
            ->where('dana_name_id', $rawMaterialItem->dana_name_id)->first();
            //if this old stock is present of that dane name id
            if ($stock) {
                $stock->quantity += $request->quantity;
                $stock->save();
            } else {
                $rawMaterialStock = new RawMaterialStock();
                $rawMaterialStock->dana_group_id = $request->danaGroup_Name_id;
                $rawMaterialStock->dana_name_id = $request->dana_name_id;
                $rawMaterialStock->quantity = $request->quantity;
                $$rawMaterialStock->department_id = $department_id;
                $rawMaterialStock->save();
            }
        } else {
            $RawMaterialStock->quantity += $request->quantity;
            $RawMaterialStock->save();
        }
        $rawMaterialItem->dana_group_id = $request->danaGroup_Name_id;
        $rawMaterialItem->dana_name_id = $request->dana_name_id;
        $rawMaterialItem->quantity = $request->quantity;
        $rawMaterialItem->lorry_no = $request->lorryNumber;

        $rawMaterialItem->save();


        $rawMaterialItemData = RawMaterialItem::where('id', $rawMaterialItem->id)->with('danaName', 'danaGroup')->first();
        return $rawMaterialItemData;
    }



    public function getRawMaterialItemsData($rawMaterial_id)
    {
        // return $rawMaterial_id;
        $rawMaterialItemDatas = RawMaterialItem::with('danaName', 'danaGroup')->where('raw_material_id', $rawMaterial_id)->get();
        if ($rawMaterialItemDatas) {
            return response()->json([
                'rawMaterialItemDatas' =>  $rawMaterialItemDatas
            ]);
        } else {
            return "false";
        }
    }
    public function getEditRawMaterialItemData($rawMaterialItem_id)
    {
        $rawMaterialItemData = RawMaterialItem::with('danaName', 'danaGroup')->find($rawMaterialItem_id);
        if ($rawMaterialItemData) {
            return response()->json([
                'rawMaterialItemData' =>  $rawMaterialItemData
            ]);
        } else {
            return "false";
        }
    }
}
