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
            'name' => 'required|string|max:60|unique:items_of_storeins',
            'pnumber' => 'required|unique:items_of_storeins,pnumber',
            'category_id' => 'required|integer',
            'department_id' => 'required',

        ]);

      try{

        $items = new ItemsOfStorein();
        $items->name = $request->name;
        $items->pnumber = $request->pnumber;
        $items->category_id = $request->category_id;
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
