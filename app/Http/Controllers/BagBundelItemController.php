<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\BagBundelItem;
use App\Models\BagBundelEntry;
use App\Models\GeneralSetting;
use App\Models\Group;
use App\Models\BagBrand;
use Illuminate\Validation\Rule;
use App\Libraries\Nepali_Calendar;
use App\Models\PrintingAndCuttingBagStock;
use DB;
use Carbon\Carbon;

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
        $groups = Group::where('status', 'active')->get();
        // $groups = PrintingAndCuttingBagStock::with(['group:id,name'])
        //     ->distinct('group_id')
        //     ->get(['group_id']);
        $bundleNo = $this->generateBagBundelNumber();
        $bagBundelItems = BagBundelItem::with('group:id,name', 'bagBrand:id,name')
            ->where('bag_bundel_entry_id', $bagBundelEntry->id)
            ->orderBy('bag_bundel_items.bundel_no', 'desc')
            ->get();
        return view('admin.bag.bagBundelling.createItem', compact('groups', 'bagBundelEntry', 'bundleNo', 'bagBundelItems'));
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

            $bagBundelItem = BagBundelItem::findOrFail($bagBundelItemId);
            $bagBundelEntry = BagBundelEntry::find($bagBundelItem->bag_bundel_entry_id);
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
                $stock->bag_brand_id = $bagBundelItem->bag_brand_id;
                // $stock->qty_in_kg =$bagBundelItem->qty_kg;
                $stock->quantity_piece = $bagBundelItem->qty_pcs;
                $stock->save();
            }
            $bagBundelItem->delete();
            $bagBundelItems = BagBundelItem::with('group:id,name', 'bagBrand:id,name')
                ->where('bag_bundel_entry_id', $bagBundelEntry->id)
                ->orderBy('bundel_suffix', 'desc')
                ->get();

            $view = view('admin.bag.bagBundelling.ssr.tableview', compact('bagBundelItems'))->render();
            DB::commit();
            return response(['status' => true, 'view' => $view]);
        } catch (Exception $ex) {
            DB::rollback();
        }
    }

    //for bundel number

    public function generateBagBundelNumber()
    {
        $setting       = GeneralSetting::where('key', 'bag_bundle_no')->get('value')->first();
        $settingValue  = explode('-', $setting->value);
        $bagBundelitem = BagBundelItem::where('bundel_prefix', $settingValue[0])->orderBy('bundel_suffix', 'DESC')->latest()->first();
        if ($bagBundelitem) {
            $currentNumber = intval($bagBundelitem->bundel_suffix);

            if ($currentNumber >= (int)$settingValue[1]) {
                $newNumber = $currentNumber + 1;
                $newBundelNo = $settingValue[0] . '-' . $newNumber;
                return $newBundelNo;
            }
        }
        $newBundelNo = $settingValue[0] . '-' . $settingValue[1];
        return $newBundelNo;
    }
    public function generateBagBundelNumberOld()
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
                'quantity_in_pcs' => 'required',
                'avg_weight' => 'required',
                'bundel_no' => [
                    'required',
                    Rule::unique('bag_bundel_items', 'bundel_no'),
                    function ($attribute, $value, $fail) {
                        if (substr_count($value, '-') !== 1) {
                            $fail("The $attribute must contain exactly one hyphen.");
                        }
                    },
                ],
            ]);
            $bagBrandGroupCheck = BagBrand::find($request->brand_bag_id)->group_id;
            if ($bagBrandGroupCheck != $request->group_id) {
                return response()->json([
                    'message' => "Bag brand you enetered doesn't belong to that group.",
                ], 404);
            }

            $bunderNumber = explode('-', $request->bundel_no);
            $bagBundelItem = new BagBundelItem();
            $bagBundelItem->bundel_prefix = $bunderNumber[0];
            $bagBundelItem->bundel_suffix = $bunderNumber[1];
            $bagBundelItem->bag_bundel_entry_id = $request->bag_bundel_entry_id;
            $bagBundelItem->group_id = $request->group_id;
            $bagBundelItem->bag_brand_id = $request->brand_bag_id;
            $bagBundelItem->qty_in_kg = $request->quantity_in_kg;
            $bagBundelItem->qty_pcs = $request->quantity_in_pcs;
            $bagBundelItem->average_weight = $request->avg_weight;
            $bagBundelItem->status = 'sent';
            $bagBundelItem->bundel_no = $request->bundel_no;
            $bagBundelItem->save();
            $bagBundelItem->load(['group:id,name', 'bagBrand:id,name'])->first();

            $printingAndCuttingBagStock = PrintingAndCuttingBagStock::where('group_id', $bagBundelItem->group_id)
                ->where('bag_brand_id', $bagBundelItem->bag_brand_id)
                ->first();
            if ($printingAndCuttingBagStock) {
                $printingAndCuttingBagStock->quantity_piece = $printingAndCuttingBagStock->quantity_piece - $bagBundelItem->qty_pcs;
            } else {
                $printingAndCuttingBagStock = new PrintingAndCuttingBagStock();
                $printingAndCuttingBagStock->group_id = $request->group_id;
                $printingAndCuttingBagStock->bag_brand_id = $request->brand_bag_id;
                $printingAndCuttingBagStock->quantity_piece = 0 - $request->quantity_in_pcs;
            }
            $printingAndCuttingBagStock->save();
            DB::commit();
            $newNumber = $this->generateBagBundelNumber();
            $availableStock = PrintingAndCuttingBagStock::where('group_id', $request->group_id)
                ->where('bag_brand_id', $request->brand_bag_id)
                ->get(['quantity_piece'])
                ->first();

            $bagBundelentry_id = BagBundelEntry::find($request->bag_bundel_entry_id)->id;
            //return  $bagBundelentry_id;
            $bagBundelItems = BagBundelItem::with('group:id,name', 'bagBrand:id,name')
                ->where('bag_bundel_entry_id', $bagBundelentry_id)
                ->orderBy('bag_bundel_items.bundel_no', 'desc')
                ->get();

            $view = view('admin.bag.bagBundelling.ssr.tableview', compact('bagBundelItems'))->render();
            return response([
                'status' => true, 'message' => 'Bag Bundel Item Created Successfully',
                'newNumber' => $newNumber, 'available_stock' => $availableStock ? $availableStock->quantity_piece : 0,
                'view' => $view
            ], 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response(['status' => false, 'message' => $ex->getMessage()]);
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
