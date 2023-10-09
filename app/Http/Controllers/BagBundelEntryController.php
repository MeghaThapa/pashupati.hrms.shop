<?php

namespace App\Http\Controllers;

use App\Models\BagBundelEntry;
use App\Models\BagBundelItem;
use App\Models\PrintingAndCuttingBagStock;
use App\Models\BagBundelStock;
use Illuminate\Http\Request;
use DB;
use Exception;

class BagBundelEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bagBundelEntries = BagBundelEntry::with(['group', 'bagBrand'])->get(['id', 'nepali_date', 'receipt_no', 'receipt_date', 'total_bundle_quantity', 'status']);
        return view('admin.bag.bagBundelling.index', compact('bagBundelEntries'));
    }

    public function edit($bagBundelEntry_id)
    {
        $bagBundelEntryData = BagBundelEntry::where('id', $bagBundelEntry_id)->get(['id', 'receipt_no', 'receipt_date', 'nepali_date'])->first();
        $groups = PrintingAndCuttingBagStock::with(['group' => function ($query) {
            $query->select('id', 'name');
        }])
            ->distinct('group_id')
            ->get(['group_id']);
        return view('admin.bag.bagBundelling.create', compact('bagBundelEntryData', 'groups'));
    }

    public function view($bagBundelEntry_id)
    {
        $bagBundelEntry = BagBundelEntry::with('bagBundelItems', 'bagBundelItems.group:id,name', 'bagBundelItems.bagBrand:id,name')->find($bagBundelEntry_id);
        // return $bagBundelEntry;
        return view('admin.bag.bagBundelling.view', compact('bagBundelEntry'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createBagBundleEntry()
    {

        $receipt_no = self::getLatestReceiptNo();
        $nepaliDate = getNepaliDate(date('Y-m-d'));
        $date = date('Y-m-d');
        $bagBundelEntryData = null;
        return view('admin.bag.bagBundelling.create', compact('nepaliDate', 'date', 'receipt_no', 'bagBundelEntryData'));
    }
    public function getLatestReceiptNo()
    {
        $id = BagBundelEntry::latest()->value('id');
        return "BB" . "-" . getNepaliDate(date('Y-m-d')) . "-" . $id + 1;
    }
    public function update(Request $request, $bagBundleEntryId)
    {
        $bagBundelEntry = BagBundelEntry::find($bagBundleEntryId);
        $request->validate([
            'receipt_no' => 'required',
            'receipt_date' => 'required',
            'nepali_date' => 'required'
        ]);
        $bagBundelEntry->receipt_date = $request->receipt_date;
        $bagBundelEntry->nepali_date = $request->nepali_date;
        $bagBundelEntry->save();
        return redirect()->route('bagBundelItem.index', ['bagBundelEntryId' => $bagBundelEntry->id]);
    }


    public function getBrandBag(Request $request)
    {
        $brandBags = PrintingAndCuttingBagStock::with(['bagBrand:id,name'])
            ->where('group_id', $request->group_id)
            ->distinct('bag_brand_id')
            ->get(['bag_brand_id']);
        return response()->json([
            'brandBags' => $brandBags
        ]);
    }

    public function saveEntireBagBundelling(Request $request)
    {
        try {
            DB::beginTransaction();
            $bagBundelEntry = BagBundelEntry::withCount('bagBundelItems')->find($request->bagBundellingEntry_id);
            $bagBundelEntry->status = 'completed';
            $bagBundelEntry->total_bundle_quantity = $bagBundelEntry->bag_bundel_items_count;
            $bagBundelEntry->save();

            $bagBundelItems = BagBundelItem::where('bag_bundel_entry_id', $bagBundelEntry->id)->get();
            foreach ($bagBundelItems as $item) {
                //change status to completed
                $item->status = 'completed';
                $item->save();

                $bagBundelStock = new BagBundelStock();
                $bagBundelStock->group_id = $item->group_id;
                $bagBundelStock->bag_brand_id = $item->bag_brand_id;
                $bagBundelStock->bundle_no = $item->bundel_no;
                $bagBundelStock->qty_pcs = $item->qty_pcs;
                $bagBundelStock->qty_in_kg = $item->qty_in_kg;
                $bagBundelStock->average_weight = $item->average_weight;
                $bagBundelStock->save();
            }
            DB::commit();
            return response()->json([
                'message' => 'Bag Bundelling completed successfully'
            ]);
        } catch (Exception $ex) {
        }
        DB::rollback();
    }

    public function calculateBundles($totalBags, $bundleSize)
    {
        $bundles = intdiv($totalBags, $bundleSize);
        $remainingPieces = $totalBags % $bundleSize;

        if ($remainingPieces > 0) {
            throw new Exception('Cannot create complete bundles. Remaining pieces: ' . $remainingPieces);
        }
        return $bundles;
    }

    public function store(Request $request)
    {
        $request->validate([
            'receipt_no' => 'required',
            'receipt_date' => 'required',
            'nepali_date' => 'required'
        ]);
        $bagBundelEntry = new BagBundelEntry();
        $bagBundelEntry->receipt_no = $request->receipt_no;
        $bagBundelEntry->receipt_date = $request->receipt_date;
        $bagBundelEntry->nepali_date = $request->nepali_date;
        $bagBundelEntry->save();
        return redirect()->route('bagBundelItem.index', ['bagBundelEntryId' => $bagBundelEntry->id]);
    }

    // public function store(Request $request){
    //     try{
    //     $request->validate([
    //         'receipt_no'=>'required',
    //         'receipt_date'=>'required',
    //         'nepali_date'=>'required',
    //         'group_id'=>'required',
    //         'brand_bag_id'=>'required',
    //         'quantity_in_kg'=>'required',
    //         'quantity_Pcs'=>'required',
    //         'bundle_pcs'=>'required',
    //         'avg_weight'=>'required',
    //     ]);
    //     DB::beginTransaction();
    //     $bagBundelEntry=new BagBundelEntry();
    //     $bagBundelEntry->group_id=$request->group_id;
    //     $bagBundelEntry->bag_brand_id=$request->brand_bag_id;
    //     $bagBundelEntry->pieces_for_bundeling=$request->quantity_Pcs;
    //     $bagBundelEntry->bundle_pcs = $request->bundle_pcs;
    //     $bagBundelEntry->status="running";
    //     $bagBundelEntry->receipt_no=$request->receipt_no;
    //     $bagBundelEntry->receipt_date=$request->receipt_date;
    //     $bagBundelEntry->nepali_date =$request->nepali_date;
    //     $bagBundelEntry->total_bundel=self::calculateBundles($request->quantity_Pcs,$request->bundle_pcs);
    //     $bagBundelEntry->total_qty_kg=$request->quantity_in_kg;
    //     $bagBundelEntry->average_weight =number_format(($request->quantity_in_kg/ $request->quantity_Pcs)*1000,2);
    //     $bagBundelEntry->save();
    //     $id=BagBundelItem::latest()->value('id');
    //     for($i=0;$i<$bagBundelEntry->total_bundel;$i++){
    //         $id = $id+1;
    //         $bundel_no = "BN" . "-" . getNepaliDate(date('Y-m-d')) . "-" . $id;
    //         $bagBundelItem = new BagBundelItem();
    //         $bagBundelItem->bundel_no=$bundel_no;
    //         $bagBundelItem->bag_bundel_entry_id=$bagBundelEntry->id;
    //         $bagBundelItem->group_id =$bagBundelEntry->group_id;
    //         $bagBundelItem->bag_brand_id  =$bagBundelEntry->bag_brand_id;
    //         $bagBundelItem->average_weight= $bagBundelEntry->average_weight;
    //         $bagBundelItem->qty_pcs =$bagBundelEntry->bundle_pcs;
    //         $bagBundelItem->qty_kg = $bagBundelEntry->total_qty_kg/$bagBundelEntry->total_bundel;
    //         $bagBundelItem->save();
    //     }
    //     $printingAndCuttingBagStock=PrintingAndCuttingBagStock::where('group_id',$request->group_id)
    //     ->where('bag_brand_id',$request->brand_bag_id)->first();
    //      $quantityInkgValue = $printingAndCuttingBagStock->qty_in_kg -$request->quantity_in_kg;
    //      $quantityInPcsValue = $printingAndCuttingBagStock->quantity_piece -$request->quantity_Pcs;
    //      if( !($quantityInkgValue <= 0) && !($quantityInPcsValue <= 0)){
    //             $printingAndCuttingBagStock->qty_in_kg=$quantityInkgValue;
    //             $printingAndCuttingBagStock->quantity_piece=$quantityInPcsValue;
    //      }
    //     if($printingAndCuttingBagStock->qty_in_kg== 0 && $printingAndCuttingBagStock->quantity_piece == 0){
    //         $printingAndCuttingBagStock->delete();
    //     }
    //     else{
    //      $printingAndCuttingBagStock->save();
    //     }


    //     DB::commit();
    //     return BagBundelItem::with(['group:id,name','bagBrand:id,name'])
    //     ->where('bag_bundel_entry_id',$bagBundelEntry->id)
    //     ->get();
    //     }catch(Exception $ex){
    //         DB::rollback();
    //     }

    // }



}
