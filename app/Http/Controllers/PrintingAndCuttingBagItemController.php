<?php

namespace App\Http\Controllers;

use App\Models\PrintingAndCuttingBagItem;
use App\Models\PrintingAndCuttingBagStock;
use App\Models\BagFabricReceiveItemSentStock;
use App\Models\PrintedAndCuttedRollsEntry;
use Illuminate\Http\Request;
use App\Models\WasteStock;
use Exception;
use Expection;
use Illuminate\Support\Facades\DB;

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
        $stock = BagFabricReceiveItemSentStock::where('fabric_id', $request->fabric_id)->first()->status;
        if ($stock != 'Stock') {
            return response()->json([
                'error' => 'this fabric is not in stock or have already been used'
            ], 404);
        }
        try {

            $request->validate([
                "printAndCutEntry_id" => "required",
                "group_id" => "required",
                "bag_brand_id" => "required",
                "quantity_piece" => "required",
                "average" => "required",
                "wastage" => "required",
                "roll_no" => "required",
                "fabric_id" => "required",
                "net_weight" => "required",
                "cut_length" => "required",

                "gross_weight" => "required",
                "meter" => "required",
                "avg" => "required",
                "req_bag" => "required",
                "godam_id" => 'required',
                "waste_type_id" => 'required',
            ]);


            $stockData = BagFabricReceiveItemSentStock::where('roll_no', $request->roll_no)
                ->where('average', $request->average)
                ->where('gross_wt', $request->gross_weight)
                ->where('net_wt', $request->net_weight)
                ->where('meter', $request->meter)
                ->first();
            //    dd($stockData);
            DB::beginTransaction();
            $printingAndCuttingBagItem = new PrintingAndCuttingBagItem();
            $printingAndCuttingBagItem->printAndCutEntry_id = $request->printAndCutEntry_id;
            $printingAndCuttingBagItem->group_id = $request->group_id;
            $printingAndCuttingBagItem->bag_brand_id = $request->bag_brand_id;
            $printingAndCuttingBagItem->quantity_piece = $request->quantity_piece;
            $printingAndCuttingBagItem->average = $request->average;
            $printingAndCuttingBagItem->wastage = $request->wastage;
            $printingAndCuttingBagItem->roll_no = $request->roll_no;
            $printingAndCuttingBagItem->fabric_id = $request->fabric_id;
            $printingAndCuttingBagItem->net_weight = $request->net_weight;
            $printingAndCuttingBagItem->cut_length = $request->cut_length;
            $printingAndCuttingBagItem->gross_weight = $request->gross_weight;
            $printingAndCuttingBagItem->meter = $request->meter;
            $printingAndCuttingBagItem->avg = $request->avg;
            $printingAndCuttingBagItem->req_bag = $request->req_bag;
            $printingAndCuttingBagItem->godam_id = $request->godam_id;
            $printingAndCuttingBagItem->wastage_id = $request->waste_type_id;

            $printingAndCuttingBagItem->bag_fabric_receive_item_sent_stock_id = $stockData->id;
            $printingAndCuttingBagItem->save();
            //find wastage stock and add up or create new waste stock if no stock exists

            //transfer the stock to Print And Cutting
            $stockData->status = 'Print And Cutting';
            $stockData->save();


            $wastageStock = WasteStock::where('godam_id', $request->godam_id)
                ->where('waste_id', $request->waste_type_id)
                ->first();
            if ($wastageStock) {
                $wastageStock->quantity_in_kg = $wastageStock->quantity_in_kg + $request->wastage;
                $wastageStock->save();
            } else {
                $wastageStock = new WasteStock();
                $wastageStock->godam_id = $request->godam_id;
                $wastageStock->waste_id  = $request->waste_type_id;
                $wastageStock->quantity_in_kg = $request->wastage;
                $wastageStock->save();
            }
            $printingAndCuttingBagItem->load(['group:id,name', 'brandBag:id,name', 'fabric:id,name']);
            DB::commit();
            return response()->json([
                'message' => 'Printing and cutting bag item created successfully ',
                'printingAndCuttingBagItem' => $printingAndCuttingBagItem
            ]);
        } catch (Exception $e) {
            DB::rollback();;
            return response()->json([
                'message' => 'error',

            ]);
        }
    }

    public function getPrintsAndCutsBagItems(Request $request)
    {
        $data = PrintingAndCuttingBagItem::with(['group', 'brandBag', 'fabric'])->where('printAndCutEntry_id', $request->printAndCutEntry_id)->get();
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
    public function itemDelete($printingAndCuttingBagItem_id)
    {
        try {
            DB::beginTransaction();
            $printingAndCuttingBagItem = PrintingAndCuttingBagItem::find($printingAndCuttingBagItem_id);
            $wasteStock = WasteStock::where('godam_id', $printingAndCuttingBagItem->godam_id)
                ->where('waste_id', $printingAndCuttingBagItem->wastage_id)
                ->first();

            $wasteStock->quantity_in_kg = $wasteStock->quantity_in_kg - $printingAndCuttingBagItem->wastage;

            if ($wasteStock->quantity_in_kg <= 0) {
                $wasteStock->delete();
            }
            $wasteStock->save();

            $printingAndCuttingBagItem->delete();

            $bagFabricReceiveItemSentStock = BagFabricReceiveItemSentStock::find($printingAndCuttingBagItem->bag_fabric_receive_item_sent_stock_id);
            $bagFabricReceiveItemSentStock->status = 'Stock';
            $bagFabricReceiveItemSentStock->save();
            DB::commit();
        } catch (Expection $ex) {
            DB::rollback();
        }
    }

    public function updateStock(Request $request)
    {
        try {
            DB::beginTransaction();
            $printingAndCuttingEntryItems = PrintingAndCuttingBagItem::where('printAndCutEntry_id', $request->printAndCutEntry_id)->get();
            foreach ($printingAndCuttingEntryItems as $item) {
                //deleting from BagFabricReceiveItemSentStock
                $item->status = "completed";
                $item->save();
                $bag_fabric_receive_item_sent_stock = BagFabricReceiveItemSentStock::where('fabric_id', $item->fabric_id)->first();
                if ($bag_fabric_receive_item_sent_stock) {
                    $bag_fabric_receive_item_sent_stock->delete();
                } else {
                    DB::rollback();
                    return response()->json([
                        'message' => 'The roll no you entered is not available or has already been used'
                    ], 500);
                }
                //adding to stock
                $printingAndCuttingBagStock = PrintingAndCuttingBagStock::where('group_id', $item->group_id)
                    ->where('bag_brand_id', $item->bag_brand_id)->first();
                if ($printingAndCuttingBagStock) {
                    $printingAndCuttingBagStock->quantity_piece += $item->quantity_piece;
                    $printingAndCuttingBagStock->save();
                } else {
                    $printingAndCuttingBagStock = new PrintingAndCuttingBagStock();
                    $printingAndCuttingBagStock->group_id  = $item->group_id;
                    $printingAndCuttingBagStock->bag_brand_id  = $item->bag_brand_id;
                    $printingAndCuttingBagStock->quantity_piece = $item->quantity_piece;
                    $printingAndCuttingBagStock->save();
                }
            }
            $bagFabricEntry = PrintedAndCuttedRollsEntry::where('id', $request->printAndCutEntry_id)->first();
            $bagFabricEntry->status = "completed";
            $bagFabricEntry->save();
            DB::commit();
            return response()->json([
                'message' => 'printing and cutting done successfully'
            ]);
            // return redirect()->route('prints.and.cuts.index')->withSuccess('Prints and cuts creaed successfully!');
        } catch (Expection $ex) {
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
