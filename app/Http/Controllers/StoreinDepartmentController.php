<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\StoreinDepartment;
use Illuminate\Support\Str;
class StoreinDepartmentController extends Controller
{
    public function store(Request $request){
         $validator = $request->validate([
            'name'          => 'required|string|unique:storein_departments',
            'status'         => 'required',
            'category_id'   =>'required'
        ]);
         try{
            $storeinDepartment = new StoreinDepartment;

            $storeinDepartment->name= strtolower($request->name);
            $storeinDepartment->category_id= $request->category_id;
            $storeinDepartment->slug=Str::slug(strtolower($request->name));
            $storeinDepartment->status= $request->status;
            $storeinDepartment->save();
             return response()->json([
                'message' =>'Department Created Successfully',
                'storeinDepartment' => $storeinDepartment,
            ],201);
         }
         catch (Exception $ex){
            return 'something went wrong';
        }
    }
}
