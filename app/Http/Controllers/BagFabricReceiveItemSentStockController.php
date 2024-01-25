<?php

namespace App\Http\Controllers;

use App\Models\BagFabricReceiveItemSentStock;
use App\Models\PrintingAndCuttingBagItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class BagFabricReceiveItemSentStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    
        if ($request->ajax()) {
            $query = BagFabricReceiveItemSentStock::with('fabric:id,name');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn("fabric_id", function ($row) {
                    return $row->fabric->name;
                })
                ->make(true);
        }
        return view('admin.bag.printsandcuts.bagFabricItemSentReceiveStock');
    }

    private function fixprintingxCuttingBagitems(){
 
        $unmatchedItems = PrintingAndCuttingBagItem::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('bag_fabric_receive_item_sent_stock')
                ->whereRaw('bag_fabric_receive_item_sent_stock.id = printing_and_cutting_bag_items.bag_fabric_receive_item_sent_stock_id');
        })
        ->get();
     
        foreach ($unmatchedItems as $item) {
            $rollNo = $item->roll_no;
            $netWeight = $item->net_weight;
        
            // Find the corresponding record in BagFabricReceiveItemSentStock based on roll_no and net_wt
            $stock = DB::table('bag_fabric_receive_item_sent_stock')
            ->where('roll_no', $rollNo)
            ->where('net_wt', $netWeight)
            ->first();        
                if ($stock === null) {
                    // Dump and die if no data is found
                    return("No data found for roll_no: $rollNo and net_wt: $netWeight");
                }
        
            if ($stock) {
                // Update the PrintingAndCuttingBagItem record with the found stock_id
                DB::table('bag_fabric_receive_item_sent_stock')
                ->where('id', $stock->id)
                ->update([
                    'status' => 'Print And Cutting',
                    'deleted_at' => now(),
                ]);
        
            // Update the PrintingAndCuttingBagItem record with the found stock_id
            DB::table('printing_and_cutting_bag_items')
                ->where('id', $item->id)
                ->update(['bag_fabric_receive_item_sent_stock_id' => $stock->id]);
            }   
         
        }
        return 'done';
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
