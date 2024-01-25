<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreinCategory;
use Illuminate\Support\Str;

class StoreinCategoryController extends Controller
{
    //
     public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:storein_categories',
            'note' => 'nullable|string|max:255',
            'status' => 'required'
        ]);
        try{
        // store category
        $category = StoreinCategory::create([
            'name' => clean(strtolower($request->name)),
            'note' => clean(strtolower($request->note)),
            'slug' => clean(Str::slug(strtolower($request->name))),
            'status' => clean($request->status)
        ]);
        return response()->json([
            'message' =>'Category Created Successfully',
            'category' => $category,
        ],201);
        }
        catch (Exception $ex){
            return $ex;
        }
    }
}
