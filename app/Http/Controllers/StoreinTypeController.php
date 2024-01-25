<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\StoreinType;
class StoreinTypeController extends Controller
{
    public function store(Request $request){
       // return $request;
         $request->validate([
            'name'          => 'required|string|unique:storein_types',
            'code'          =>  'required|unique:storein_types,code',
            'note'          => 'required',
            'status'        => 'required',

        ]);
         try{
        $storeinType= new StoreinType();
        $storeinType->name= strtolower($request->name);
        $storeinType->slug=Str::slug(strtolower($request->name));
     // $storeinType->slug=$request->name;
        $storeinType->note = $request->note;
        $storeinType->code= $request->code;
        $storeinType->status = $request->status;
        $storeinType->save();
         return response()->json([
                'message' =>'storeinType Created Successfully',
                'storeinType' => $storeinType,
            ],201);
             }
         catch (Exception $ex){
            return 'something went wrong';
        }
    }
}
