<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\RawMaterialStock;
use App\Models\DanaName;
use App\Models\DanaGroup;
class RawMaterialStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();
       $rawMaterialStocks= RawMaterialStock::with('danaName','danaGroup')->get();
      // return $rawMaterialStocks[0]->danaGroup;
        //


        return view('admin.rawMaterialStock.rawMaterialStockIndex',compact('settings','rawMaterialStocks'));

    }
    Public function danaGroupFilter(){
        $danaGroups=DanaGroup::all();
        return view('admin.rawMaterialStock.danaGroupFilter',compact('danaGroups'));
    }

    public function danaNameFilter(){
        $danaNames=DanaName::all();

        return view('admin.rawMaterialStock.danaNameFilter',compact('danaNames'));
    }
    public function filterAccDanaName($danaName_id){
        $rawMaterialItemStock=RawMaterialStock::with('danaName','danaGroup')->where('dana_name_id',$danaName_id)->get();
        if ($rawMaterialItemStock) {
            return $rawMaterialItemStock;
        } else {

            return false;
        }
    }


    public function filterAccDanaGroup($danaGroup_id){

       // return ($danaGroup_id);
        $rawMaterialItemStocks =RawMaterialStock::with('danaName','danaGroup')->where('dana_group_id',$danaGroup_id)->get();
        //return $rawMaterialItemStocks;
        if ($rawMaterialItemStocks) {
            return $rawMaterialItemStocks;
        } else {

            return false;
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
