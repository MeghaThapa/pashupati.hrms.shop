<?php

namespace App\Http\Controllers;

use App\Models\BagSellingItem;
use App\Models\BagSellingEntry;
use App\Models\BagBundelStock;
use App\Models\BagSalesStock;
use Illuminate\Http\Request;
use DB;

class BagSellingItemController extends Controller
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
       try{
        DB::beginTransaction();
        $request->validate([
            'bag_selling_entry_id'=>'required',
            'group_id'=>'required',
            'brand_bag_id'=>'required',
            'bundel_no'=>'required',
            'pcs'=>'required',
            'weight'=>'required',
            'average'=>'required',
        ]);
         $bagBundelStock=BagBundelStock::where('bundle_no',$request->bundel_no)->first();
        if(!$bagBundelStock){
          return response()->json([
                        'message' => "you don't have stock available.",
                    ], 500);
        }elseif($bagBundelStock->qty_pcs < $request->pcs){
            return response()->json([
                        'message' => "you don't have enough stock available.",
                    ], 500);
        }

        $bagSellingItem =new BagSellingItem();
        $bagSellingItem->bag_selling_entry_id= $request->bag_selling_entry_id;
        $bagSellingItem->group_id = $request->group_id;
        $bagSellingItem->brand_bag_id  = $request->brand_bag_id;
        $bagSellingItem->bundel_no = $request->bundel_no;
        $bagSellingItem->pcs = $request->pcs;
        $bagSellingItem->weight = $request->weight;
        $bagSellingItem->average =$request->average;
        $bagSellingItem->save();

        $bagBundelStock->delete();
        DB::commit();
        }catch(Exception $ex){
            DB::rollback();
       }
        return $bagSellingItem->with('group:id,name','brandBag:id,name')->first();

    }
    public function saveEntireBagSellingEntry(Request $request){
        try{
        DB::beginTransaction();
        $bagSellingEntry=BagSellingEntry::find($request->bagSellingEntry_id);
        $bagSellingEntry->status= 'completed';
        $bagSellingEntry->save();
        $bagSellingItems=BagSellingItem::where('bag_selling_entry_id',$request->bagSellingEntry_id)->get();
        foreach($bagSellingItems as $bagSellingItem){
            $bagSalesStock= new BagSalesStock();
            $bagSalesStock->group_id = $bagSellingItem->group_id;
            $bagSalesStock->brand_bag_id  = $bagSellingItem->brand_bag_id;
            $bagSalesStock->bundel_no = $bagSellingItem->bundel_no;
            $bagSalesStock->pcs = $bagSellingItem->pcs;
            $bagSalesStock->weight = $bagSellingItem->weight;
            $bagSalesStock->average = $bagSellingItem->average;
            $bagSalesStock->save();
        }
        DB::commit();
        }catch(Exception $ex){
        DB::rollback();
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\bagSellingItem  $bagSellingItem
     * @return \Illuminate\Http\Response
     */
    public function show(bagSellingItem $bagSellingItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\bagSellingItem  $bagSellingItem
     * @return \Illuminate\Http\Response
     */
    public function edit($bagSellingEntry_id)
    {
       $bagSellingEntry=BagSellingEntry::with('supplier:id,name')
       ->find($bagSellingEntry_id);
        return view('admin.bag.bagSelling.createSalesItem',compact('bagSellingEntry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\bagSellingItem  $bagSellingItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bagSellingEntry_id)
    {
       $bagSellingEntry=BagSellingEntry::with('supplier:id,name')
       ->find($bagSellingEntry_id);
       //return  $bagSellingEntry->id;
       $groups=BagBundelStock::with('group')
        ->select('group_id')
        ->distinct('group_id')
        ->get()
        ;
     return view('admin.bag.bagSelling.createSalesItem',compact('bagSellingEntry','groups'));
    }

    public function getBagSellingItemData(Request $request){
        $bagSellingItems=BagSellingItem::with('group:id,name','brandBag:id,name')
        ->where('bag_selling_entry_id',$request->bagSellingEntry_id)
        ->get();
        return $bagSellingItems;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\bagSellingItem  $bagSellingItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(bagSellingItem $bagSellingItem)
    {
        //
    }
}
