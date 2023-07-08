<?php

namespace App\Http\Controllers;

use App\Models\PrintingAndCuttingBagItem;
use App\Models\PrintingAndCuttingBagStock;
use App\Models\BagFabricReceiveItemSentStock;
use Illuminate\Http\Request;
use Expection;
use DB;

class PrintingAndCuttingBagItemController extends Controller
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
        "printAndCutEntry_id" => "required",
        "group_id" => "required",
        "bag_brand_id" => "required",
        "quantity_piece"=>"required",
        "average"=>"required",
        "wastage"=>"required",
        "roll_no"=>"required",
        "fabric_id"=>"required",
        "net_weight"=>"required",
        "cut_length"=>"required",
        "qty_in_kg"=>"required",
        "gross_weight"=>"required",
        "meter"=>"required",
        "avg"=>"required",
        "req_bag"=>"required",
       ]);
       $printingAndCuttingBagItem = new PrintingAndCuttingBagItem();
       $printingAndCuttingBagItem->printAndCutEntry_id= $request->printAndCutEntry_id;
       $printingAndCuttingBagItem->group_id = $request->group_id;
       $printingAndCuttingBagItem->bag_brand_id = $request->bag_brand_id;
       $printingAndCuttingBagItem->quantity_piece = $request->quantity_piece;
       $printingAndCuttingBagItem->average = $request->average;
       $printingAndCuttingBagItem->wastage = $request->wastage;
       $printingAndCuttingBagItem->roll_no = $request->roll_no;
       $printingAndCuttingBagItem->fabric_id = $request->fabric_id;
       $printingAndCuttingBagItem->net_weight = $request->net_weight;
       $printingAndCuttingBagItem->cut_length = $request->cut_length;
       $printingAndCuttingBagItem->qty_in_kg = $request->qty_in_kg;
       $printingAndCuttingBagItem->gross_weight = $request->gross_weight;
       $printingAndCuttingBagItem->meter = $request->meter;
       $printingAndCuttingBagItem->avg = $request->avg;
       $printingAndCuttingBagItem->req_bag = $request->req_bag;
       $printingAndCuttingBagItem->save();

       $printingAndCuttingBagItem->load(['group:id,name','brandBag:id,name','fabric:id,name']);
        return response()->json([
            'message' => 'Printing and cutting bag item created successfully ',
            'printingAndCuttingBagItem' => $printingAndCuttingBagItem
        ]);
    }

    public function getPrintsAndCutsBagItems(Request $request){
       $data=PrintingAndCuttingBagItem::with(['group','brandBag','fabric'])->where('printAndCutEntry_id',$request->printAndCutEntry_id)->get();
        return response()->json([
            'printingAndCuttingBagItem' =>  $data
        ]);
    }
    /**
     *
     * Display the specified resource.
     *
     * @param  \App\Models\PrintingAndCuttingBagItem  $printingAndCuttingBagItem
     * @return \Illuminate\Http\Response
     */
    public function show(PrintingAndCuttingBagItem $printingAndCuttingBagItem)
    {
        //
    }
    public function itemDelete($printingAndCuttingBagItem_id){
         return PrintingAndCuttingBagItem::find($printingAndCuttingBagItem_id)->delete();
    }

    public function updateStock(Request $request){
         try{
            DB::beginTransaction();
            $printingAndCuttingEntryItems= PrintingAndCuttingBagItem::where('printAndCutEntry_id',$request->printAndCutEntry_id)->get();
            foreach ($printingAndCuttingEntryItems as $item) {
                //deleting from BagFabricReceiveItemSentStock
                $bag_fabric_receive_item_sent_stock=BagFabricReceiveItemSentStock::where('roll_no',$item->roll_no)->first();
                if ($bag_fabric_receive_item_sent_stock){
                    $bag_fabric_receive_item_sent_stock->delete();
                }
                else{
                    DB::rollback();
                    return response()->json([
                        'message' => 'The roll no you entered is not available or has already been used'
                    ],500);
                }
                //adding to PrintingAndCuttingBagStock
                $printingAndCuttingBagStock = new PrintingAndCuttingBagStock();
                $printingAndCuttingBagStock->group_id  = $item->group_id;
                $printingAndCuttingBagStock->bag_brand_id  = $item->bag_brand_id ;
                $printingAndCuttingBagStock->quantity_piece = $item->quantity_piece;
                 $printingAndCuttingBagStock->qty_in_kg= $item->qty_in_kg;
                $printingAndCuttingBagStock->cut_length = $item->cut_length;
                $printingAndCuttingBagStock->wastage = $item->wastage;
                $printingAndCuttingBagStock->save();

            }
            $bagFabricEntry=PrintedAndCuttedRollsEntry::where('id',$request->printAndCutEntry_id)->first();
            $bagFabricEntry->status="completed";
            $bagFabricEntry->save();

            DB::commit();
            return redirect()->route('prints.and.cuts.index')->withSuccess('Prints and cuts creaed successfully!');
        }catch(Expection $ex){
            DB::rollback();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrintingAndCuttingBagItem  $printingAndCuttingBagItem
     * @return \Illuminate\Http\Response
     */
    public function edit(PrintingAndCuttingBagItem $printingAndCuttingBagItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrintingAndCuttingBagItem  $printingAndCuttingBagItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintingAndCuttingBagItem $printingAndCuttingBagItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrintingAndCuttingBagItem  $printingAndCuttingBagItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrintingAndCuttingBagItem $printingAndCuttingBagItem)
    {
        //
    }
}
