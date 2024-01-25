<?php

namespace App\Http\Controllers;

use App\Models\BagBrand;
use Illuminate\Http\Request;

class BagBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|unique:bag_brands,name",
            "group_id"=>"required",
            "status" => "required"
            ]);
        $bagBrand=new BagBrand();
        $bagBrand->name=$request->name;
        $bagBrand->group_id = $request->group_id;
        $bagBrand->status = $request->status;
        $bagBrand->save();
         return response()->json([
                'message' =>'Bag Brand Created Successfully',
                'bagBrand' => $bagBrand,
            ],201);
    }

    public function getBagBrandFromGroup($group_id){
        $bagBrands=BagBrand::where('group_id',$group_id)->get(['id','name']);
       // return $bagBrands;
        return response()->json([

                'bagBrands' => $bagBrands,
            ],201);
    }
    public function show(BagBrand $bagBrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BagBrand  $bagBrand
     * @return \Illuminate\Http\Response
     */
    public function edit(BagBrand $bagBrand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BagBrand  $bagBrand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BagBrand $bagBrand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BagBrand  $bagBrand
     * @return \Illuminate\Http\Response
     */
    public function destroy(BagBrand $bagBrand)
    {
        //
    }
}
