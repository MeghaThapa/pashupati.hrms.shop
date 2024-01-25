<?php

namespace App\Http\Controllers;

use App\Models\RawmaterialOpeningItem;

use Illuminate\Http\Request;

class RawmaterialOpeningItemController extends Controller
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
        $openingRawmaterialItem= new RawmaterialOpeningItem();
        $openingRawmaterialItem->rawmaterial_opening_entry_id= $request->rawmaterial_opening_entry_id;
        $openingRawmaterialItem->dana_group_id = $request->dana_group_id;
        $openingRawmaterialItem->dana_name_id = $request->dana_name_id;
        $openingRawmaterialItem->qty_in_kg = $request->qty_in_kg;
        $openingRawmaterialItem->save();
        return $openingRawmaterialItem->load('danaGroup:id,name','danaName:id,name');
    }

    public function getRawmaterialItem(Request $request){
        $openingRawmaterialItems=RawmaterialOpeningItem::with(['danaGroup:id,name','danaName:id,name'])
        ->where('rawmaterial_opening_entry_id',$request->rawmaterial_opening_entry_id)
        ->get();
        return $openingRawmaterialItems;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\rawmaterialOpeningItem  $rawmaterialOpeningItem
     * @return \Illuminate\Http\Response
     */
    public function show(rawmaterialOpeningItem $rawmaterialOpeningItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\rawmaterialOpeningItem  $rawmaterialOpeningItem
     * @return \Illuminate\Http\Response
     */
    public function edit(rawmaterialOpeningItem $rawmaterialOpeningItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\rawmaterialOpeningItem  $rawmaterialOpeningItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rawmaterialOpeningItem $rawmaterialOpeningItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\rawmaterialOpeningItem  $rawmaterialOpeningItem
     * @return \Illuminate\Http\Response
     */
    public function delete($id){
        $openingRawmaterialItem=RawmaterialOpeningItem::find($id);
        $openingRawmaterialItem->delete();
    }
    public function destroy(rawmaterialOpeningItem $rawmaterialOpeningItem)
    {
        //
    }
}
