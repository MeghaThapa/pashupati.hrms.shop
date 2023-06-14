<?php

namespace App\Http\Controllers;
use App\Models\ItemsOfStorein;
use Illuminate\Http\Request;

class ItemsOfStoreinController extends Controller
{
        public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:60',
            'pnumber' => 'required|unique:items_of_storeins,pnumber',
            'category_id' => 'required|integer',
            'department_id' => 'required',
            'unit_id' =>'required',
            'size_id' =>'required'

        ]);
        //as item belongs to only one department of one category
        $itemOfStoreins =ItemsOfStorein::where('name',$request->name)
        ->where('category_id',$request->category_id)
        ->first();
        if($itemOfStoreins->department_id){
            return response()->json([
                'message' =>'Item can belong to only one department of particular category',
            ],500);
        }

      try{
        $items = new ItemsOfStorein();
        $items->name = trim(strtolower($request->name));
        $items->pnumber = trim(strtolower($request->pnumber));
        $items->category_id = $request->category_id;
        $items->unit_id = $request->unit_id;
        $items->size_id = $request->size_id;
        $items->department_id = $request->department_id;
        $items->status = $request->status;
        $items->save();
        return response()->json([
            'message' =>'Item Created Successfully',
            'item' => $items,
        ],201);
        }
        catch (Exception $ex){
            return $ex;
        }
    }
}
