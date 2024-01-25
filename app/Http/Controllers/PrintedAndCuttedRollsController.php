<?php

namespace App\Http\Controllers;

use App\Models\PrintedAndCuttedRolls;
use App\Models\PrintedAndCuttedRollsEntry;
use App\Models\BagFabricReceiveItemSentStock;
use App\Models\Fabric;
use App\Models\PrintingAndCuttingBagItem;
use App\Models\BagFabricReceiveItemSent;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\Wastages;
use App\Models\BagBrand;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintedAndCuttedRollsController extends Controller

{

    /*************** For Entry *********/

    public function datafixes()
    {

        // try{
        //     DB::beginTransaction();
        // $tests=PrintingAndCuttingBagItem::with('brandBag:id,name')->get();
        //     foreach( $tests as $test ){
        //         // return $test->brandBag->name;

        //         $brandbagid=BagBrand::where('name',$test->brandBag->name)->first()->id;
        //         // return $brandbagid;
        //         $test->update([
        //             'bag_brand_id'=>$brandbagid,
        //         ]);
        //     }
        //     DB::commit();
        //     return 'done';
        //     }catch(Exception $ex){
        //         DB::rollback();
        //     }


        // try{
        //     DB::beginTransaction();
        //     $duplicateNames = BagBrand::groupBy('name')
        //     ->havingRaw('COUNT(*) > 1')
        //     ->get('name');
        //     // return $duplicateNames;
        //     foreach ($duplicateNames as $name) {
        //         $recordsToDelete = BagBrand::where('name', $name->name)->skip(1)->take(PHP_INT_MAX)->get();

        //         foreach ($recordsToDelete as $record) {
        //             $record->delete();
        //         }
        //     }
        //     DB::commit();
        //     return 'done';
        //     }catch(Exception $ex){
        //         DB::rollback();
        //     }

    }
    public function delete($id){
        $printedAndCuttedRollsEntry=PrintedAndCuttedRollsEntry::find($id)->delete();
        return response()->json(['status'=>true,'message'=>'deleted'],200);
    }
    public function index()
    {
 
        $data = PrintedAndCuttedRollsEntry::withCount('printingAndCuttingBagItems')->orderBy('created_at', 'desc')->get();
        // return $data;

        return view("admin.bag.printsandcuts.index", compact("data"));
    }
    private function copyDataToStockSecond()
    {
        // Get items from BagFabricReceiveItemSent that do not exist in BagFabricReceiveItemSentStock
        $items = BagFabricReceiveItemSent::whereNotIn('fabric_id', function ($query) {
            $query->select('fabric_id')
                ->from('bag_fabric_receive_item_sent_stock')
                ->whereColumn('bag_fabric_receive_item_sent_stock.fabric_id', 'bag_fabric_receive_item_sent.fabric_id')
                ->whereColumn('bag_fabric_receive_item_sent_stock.roll_no', 'bag_fabric_receive_item_sent.roll_no');
        })->get();
        // dd($items);
        foreach ($items as $item) {
            $fabric = Fabric::find($item->fabric_id);
            if ($fabric) {
                // Assuming you have already checked if $fabric exists
                $bagFabricReceiveItemSentStock = new BagFabricReceiveItemSentStock();
                $bagFabricReceiveItemSentStock->fabric_bag_entry_id = $item->fabric_bag_entry_id;
                $bagFabricReceiveItemSentStock->fabric_id = $item->fabric_id;
                $bagFabricReceiveItemSentStock->gram = $item->gram;
                $bagFabricReceiveItemSentStock->gross_wt = $item->gross_wt;
                $bagFabricReceiveItemSentStock->net_wt = $item->net_wt;
                $bagFabricReceiveItemSentStock->meter = $item->meter;
                $bagFabricReceiveItemSentStock->roll_no = $item->roll_no;
                $bagFabricReceiveItemSentStock->loom_no = $item->loom_no;
                $bagFabricReceiveItemSentStock->average = $fabric->average_wt;
                $bagFabricReceiveItemSentStock->status = 'Stock';
                $bagFabricReceiveItemSentStock->save();
            } else {
                dd($item);
            }
        }
    }

    private function changeStatusOfStock()
    {
        $items = PrintingAndCuttingBagItem::get();
        foreach ($items as $item) {
            $stockId = BagFabricReceiveItemSentStock::where('fabric_id', $item->fabric_id)->first();
            $stockId->status = "Print And Cutting";
            $stockId->save();
            // $item->bag_fabric_receive_item_sent_stock_id = $stockId;
            // $item->save();
        }
    }

    private function deleteExtraDataFromBagItem()
    {
        $itemFabricIds = PrintingAndCuttingBagItem::pluck('fabric_id');
        $stockFabIds = BagFabricReceiveItemSentStock::pluck('fabric_id');

        $nonStockFabIds = $itemFabricIds->diff($stockFabIds);

        $items = PrintingAndCuttingBagItem::whereIn('fabric_id', $nonStockFabIds)->get();

        foreach ($items as $item) {
            $item->delete();
        }
    }

    private function bagItemDataFixes()
    {
        // $item = PrintingAndCuttingBagItem::get('fabric_id');

        // $duplicateItem = DB::table('bag_fabric_receive_item_sent_stock')
        //     ->whereNotIn('fabric_id', $item)
        //     ->get();
        $itemFabricIds = PrintingAndCuttingBagItem::pluck('fabric_id');
        $stockFabIds = BagFabricReceiveItemSentStock::pluck('fabric_id');

        $nonStockFabIds = $itemFabricIds->diff($stockFabIds);

        $items = PrintingAndCuttingBagItem::whereIn('fabric_id', $nonStockFabIds)->get();

        $newTableItems = $items->map(function ($item) {
            return [
                'printAndCutEntry_id' => $item->printAndCutEntry_id,
                'group_id' => $item->group_id,
                'bag_brand_id' => $item->bag_brand_id,
                'quantity_piece' => $item->quantity_piece,
                'average' => $item->average,
                'wastage' => $item->wastage,
                'roll_no' => $item->roll_no,
                'fabric_id' => $item->fabric_id,
                'net_weight' => $item->net_weight,
                'cut_length' => $item->cut_length,
                'gross_weight' => $item->gross_weight,
                'meter' => $item->meter,
                'avg' => $item->avg,
                'req_bag' => $item->req_bag,
                'godam_id' => $item->godam_id,
                'wastage_id' => $item->wastage_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'bag_fabric_receive_item_sent_stock_id' => $item->bag_fabric_receive_item_sent_stock_id,
            ];
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // Use the DB facade to insert the items into the new table
        DB::table('prints_cutsbag_extra')->insert($newTableItems->toArray());
        // DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return 'done';
    }
    private function fixData()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $items = BagFabricReceiveItemSentStock::orderBy('fabric_id', 'asc')->get();
        $uniqueItems = [];

        foreach ($items as $item) {
            $fabricId = $item->fabric_id;
            if (!isset($uniqueItems[$fabricId])) {
                $uniqueItems[$fabricId] = $item;
            }
        }

        $uniqueItemsCollection = collect($uniqueItems);

        BagFabricReceiveItemSentStock::truncate();

        $itemsToInsert = $uniqueItemsCollection->except('created_at', 'updated_at', 'deleted_at')->toArray();

        // Insert the data into the table
        BagFabricReceiveItemSentStock::insert($itemsToInsert);
        // You can loop through $uniqueItemsCollection to work with the unique items
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
    private function copyDataToStock()
    {
        $items = BagFabricReceiveItemSent::all();
        foreach ($items as $item) {
            $fabric = Fabric::find($item->fabric_id);
            if ($fabric) {
                $bagFabricReceiveItemSentStock = new BagFabricReceiveItemSentStock();
                $bagFabricReceiveItemSentStock->fabric_bag_entry_id = $item->fabric_bag_entry_id;
                $bagFabricReceiveItemSentStock->fabric_id = $item->fabric_id;
                $bagFabricReceiveItemSentStock->gram = $item->gram;
                $bagFabricReceiveItemSentStock->gross_wt = $item->gross_wt;
                $bagFabricReceiveItemSentStock->net_wt = $item->net_wt;
                $bagFabricReceiveItemSentStock->meter = $item->meter;
                $bagFabricReceiveItemSentStock->roll_no = $item->roll_no;
                $bagFabricReceiveItemSentStock->loom_no = $item->loom_no;
                $bagFabricReceiveItemSentStock->average = $fabric->average_wt;
                $bagFabricReceiveItemSentStock->status = 'Stock';
                $bagFabricReceiveItemSentStock->save();
            } else {
                dd($item);
            }
        }
    }
    public function view($id)
    {
        $printedAndCuttedRollsEntryData = PrintedAndCuttedRollsEntry::with(['printingAndCuttingBagItems', 'printingAndCuttingBagItems.fabric:id,name', 'printingAndCuttingBagItems.brandBag:id,name'])
            ->find($id);
        //   return  $printedAndCuttedRollsEntryData->printingAndCuttingBagItems[10];
        return view("admin.bag.printsandcuts.view", compact("printedAndCuttedRollsEntryData"));
    }

    public function createEntry()
    {

        $id = PrintedAndCuttedRollsEntry::latest()->value('id');

        $nepaliDate = getNepaliDate(date('Y-m-d'));

        $date = date('Y-m-d');

        $receipt_no = "PCR" . "-" . getNepaliDate(date('Y-m-d')) . "-" . $id + 1;

        return view("admin.bag.printsandcuts.createEntry", compact('nepaliDate', 'date', 'receipt_no'));
    }



    public function storeEntry(Request $request)
    {

        $request->validate([

            "receipt_number" => "required",

            "date" => "required",

            "date_np" => "required"

        ]);
        PrintedAndCuttedRollsEntry::create([

            "receipt_number" => $request->receipt_number,

            "date" => $request->date,

            "date_np" => $request->date_np

        ]);
        return redirect()->route('prints.and.cuts.index');
    }

    /*************** For Entry *********/

    public function createPrintedRolls($id)
    {

        $data = PrintedAndCuttedRollsEntry::where('id', $id)->first();

        // return $data;
        $fabrics = DB::table('bag_fabric_receive_item_sent_stock as stock')

            ->join('fabrics', 'fabrics.id', '=', 'stock.fabric_id')

            ->select('fabrics.id', 'fabrics.name')

            ->get();

        $groups = Group::where('status', 'active')->get();

        $godams = AutoLoadItemStock::with(['fromGodam' => function ($query) {

            $query->select('id', 'name');
        }])

            ->select('from_godam_id')

            ->distinct()

            ->get();



        $wasteGodams = Godam::where('status', 'active')->get();

        $wastageTypes = Wastages::where('status', 'active')->get();

        return view("admin.bag.printsandcuts.create")->with([

            "data" => $data,

            "fabrics" => $fabrics,

            "groups" => $groups,

            "godams" => $godams,

            "wasteGodams" => $wasteGodams,

            "wastageTypes" => $wastageTypes,

        ]);
    }

    public function getFabric(Request $request)
    {
        $fabric =  BagFabricReceiveItemSentStock::where('roll_no', $request->roll_no)
            ->where('status', 'Stock')
            ->with(['fabric' => function ($query) {

                $query->select('id', 'name');
            }])
            ->select('fabric_id as fabric_id', 'net_wt', 'gross_wt', 'average', 'meter')
            ->first();
        if (!$fabric) {
            return response()->json([
                'error' => 'roll no not in stock or have already been used'
            ], 404);
        } else {
            return  $fabric;
        }
    }

    public function getDanaGroup(Request $request)
    {

        $danaGroups = AutoLoadItemStock::with(['danaGroup' => function ($query) {

            $query->select('id', 'name');
        }])

            ->select('dana_group_id')

            ->where('from_godam_id', $request->godam_id)

            ->distinct()

            ->get();



        return response()->json([

            'danaGroups' => $danaGroups

        ]);
    }

    public function getDanaName(Request $request)
    {

        $danaNames = AutoLoadItemStock::with(['danaName' => function ($query) {

            $query->select('id', 'name');
        }])
            ->select('dana_name_id')
            //->where('dana_group_id',$request->dana_group_id)
            ->where('from_godam_id', $request->godam_id)
            ->distinct()
            ->get();

        return $danaNames;
    }

    public function getStockQuantity(Request $request)
    {

        $stockQty = AutoLoadItemStock::select('quantity')

            ->where('from_godam_id', $request->godam_id)

            ->where('dana_name_id', $request->dana_name_id)

            ->first();

        return $stockQty;
    }
}
