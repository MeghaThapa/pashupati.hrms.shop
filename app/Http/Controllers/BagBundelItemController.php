<?php

namespace App\Http\Controllers;

use App\Models\BagBundelItem;
use App\Models\BagBundelEntry;
use App\Libraries\Nepali_Calendar;
use Carbon\Carbon;
use App\Models\PrintingAndCuttingBagStock;
use Illuminate\Http\Request;
use DB;

class BagBundelItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($bagBundelEntryId)
    {
        $bagBundelEntry = BagBundelEntry::find($bagBundelEntryId);
        $groups = PrintingAndCuttingBagStock::with(['group:id,name'])
            ->distinct('group_id')
            ->get(['group_id']);
        $bundleNo = self::generateBagBundelNumber();
        return view('admin.bag.bagBundelling.createItem', compact('groups', 'bagBundelEntry', 'bundleNo'));
    }

    public function getBagBundelItemData(Request $request)
    {
        $bagBundelentry_id = BagBundelEntry::find($request->bagBundelEntry_id)->id;
        //return  $bagBundelentry_id;
        $bagBundelItems = BagBundelItem::with('group:id,name', 'bagBrand:id,name')
            ->where('bag_bundel_entry_id', $bagBundelentry_id)
            ->get();
        return $bagBundelItems;
    }

    public function getAvailableStock(Request $request)
    {
        $availableStock = PrintingAndCuttingBagStock::where('group_id', $request->group_id)
            ->where('bag_brand_id', $request->brand_bag_id)
            ->get(['quantity_piece'])
            ->first();
        return response()->json([
            'availableStock' => $availableStock
        ]);
        // return $availableStock;
    }

    public function deleteBagBundelItem($bagBundelItemId)
    {
        try {
            DB::beginTransaction();

            $bagBundelItem = BagBundelItem::find($bagBundelItemId);
            // return $bagBundelItem->qty_pcs;
            $printingAndCuttingBagStock = PrintingAndCuttingBagStock::where('group_id', $bagBundelItem->group_id)
                ->where('bag_brand_id', $bagBundelItem->bag_brand_id)
                ->first();
            if ($printingAndCuttingBagStock) {
                $printingAndCuttingBagStock->quantity_piece = $printingAndCuttingBagStock->quantity_piece + $bagBundelItem->qty_pcs;
                // return([$printingAndCuttingBagStock->quantity_piece, $bagBundelItem->quantity_Pcs]);
                $printingAndCuttingBagStock->save();
            } else {
                $stock = new PrintingAndCuttingBagStock();
                $stock->group_id = $bagBundelItem->group_id;
                $stock->bag_brand_id = $bagBundelItem->brand_bag_id;
                // $stock->qty_in_kg =$bagBundelItem->qty_kg;
                $stock->quantity_piece = $bagBundelItem->qty_pcs;
                $stock->save();
            }
            $bagBundelItem->delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
        }
    }

    //for bundel number
    public function generateBagBundelNumber()
    {
        $nepaliDate = getTodayNepaliDate();
        // Extract the year, month, and day from the Nepali date
        // 2080-03-29-123
        $year = substr($nepaliDate, 0, 4);
        $month = substr($nepaliDate, 5, 2);
        $day = substr($nepaliDate, 8, 2);
        // Get the last invoice number from the database based on the current year and month
        $bundleItem = BagBundelItem::latest()->first();
        if ($bundleItem) {
            $bundleNo = substr($bundleItem->bundel_no, strrpos($bundleItem->bundel_no, '-') + 1);
            $lastNumber = $bundleNo ? $bundleNo + 1 : 1;
        } else {
            $lastNumber = 1;
        }
        // If the month has changed, reset the last number to 1
        if ($bundleItem && $month != substr($bundleItem->bundel_no, 8, 2)) {
            $lastNumber = 1;
        }
        // Format the bundel number
        return "BB-$year-$month-$day-$lastNumber";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'bag_bundel_entry_id' => 'required',
                'group_id' => 'required',
                'brand_bag_id' => 'required',
                'quantity_in_kg' => 'required',
                'quantity_Pcs' => 'required',
                'avg_weight' => 'required',
                'bundel_no' => 'required'
            ]);
            $bagBundelItem = new BagBundelItem();
            $bagBundelItem->bag_bundel_entry_id = $request->bag_bundel_entry_id;
            $bagBundelItem->group_id = $request->group_id;
            $bagBundelItem->bag_brand_id = $request->brand_bag_id;
            $bagBundelItem->qty_in_kg = $request->quantity_in_kg;
            $bagBundelItem->qty_pcs = $request->quantity_Pcs;
            $bagBundelItem->average_weight = $request->avg_weight;
            $bagBundelItem->bundel_no = $request->bundel_no;
            $bagBundelItem->save();
            $bagBundelItem->load(['group:id,name', 'bagBrand:id,name'])->first();

            $printingAndCuttingBagStock = PrintingAndCuttingBagStock::where('group_id', $bagBundelItem->group_id)
                ->where('bag_brand_id', $bagBundelItem->bag_brand_id)
                ->first();
            // return $printingAndCuttingBagStock;
            $printingAndCuttingBagStock->quantity_piece = $printingAndCuttingBagStock->quantity_piece - $bagBundelItem->qty_pcs;
            if ($printingAndCuttingBagStock->quantity_piece <= 0) {
                $printingAndCuttingBagStock->delete();
            } else {
                $printingAndCuttingBagStock->save();
            }
            DB::commit();
            return $bagBundelItem;
        } catch (Exception $ex) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BagBundelItem  $bagBundelItem
     * @return \Illuminate\Http\Response
     */
    public function show(BagBundelItem $bagBundelItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BagBundelItem  $bagBundelItem
     * @return \Illuminate\Http\Response
     */
    public function edit(BagBundelItem $bagBundelItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BagBundelItem  $bagBundelItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BagBundelItem $bagBundelItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BagBundelItem  $bagBundelItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(BagBundelItem $bagBundelItem)
    {
        //
    }
}
