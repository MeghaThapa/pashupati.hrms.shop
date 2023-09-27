<?php

namespace App\Http\Controllers;

use App\Models\Fabric;
use App\Models\Supplier;
use App\Models\SaudaItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryOrderForItem;
use App\Http\Requests\SaudaItem\SaudaItemStoreRequest;
use App\Http\Requests\SaudaItem\SaudaItemUpdateRequest;

class SaudaItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = SaudaItem::with('supplier','deliveryOrderForItem');

            if ($request->start_date && $request->end_date) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $query->whereBetween('do_date', [$start_date, $end_date]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn("supplier_id",function($row){
                    return $row->supplier->name;
                })
                ->editColumn("delivery_order_for_item_id",function($row){
                    return $row->deliveryOrderForItem->name;
                })
                ->editColumn('fabric_name',function($row){
                    $fabric_names = json_decode($row->fabric_name);
                    return implode(', ', $fabric_names);
                })
                ->addColumn("action", function ($row) {
                    if ($row->status == "Pending") {
                        $url = route("sauda-item.update",$row->id);
                        return "<div class='btn-group'>
                                        <button class='btn btn-danger cancel_item' data-url='".$url."' >Cancel DO</button>
                                    </div>";
                    } else if($row->status == "Approved & Delivered") {
                        $url = route('sauda-item.show',$row->id);
                        return "<div class='btn-group'>
                                        <a href='".$url."' class='btn btn-success'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                    </div>";
                    }
                })
                ->rawColumns(['action','status'])
                ->make(true);


        }

        $suppliers = Supplier::select('name','id')->get();
        $fabrics = Fabric::distinct()->pluck('name')->toArray();
        $nextId = DB::table('sauda_items')->max('id') + 1;
        $deliveryOrderForItems = DeliveryOrderForItem::select('id','name')->get();
        return view('admin.sauda_item.index',compact('suppliers','fabrics','nextId','deliveryOrderForItems'));
    }

    public function filterView(){
        return view('admin.sauda_item.datewisefilter');
    }

    public function entryFilterView(){
        return view('admin.sauda_item.datewise_entry_filter');
    }

    public function generateView(Request $request)
    {
        $saudaItems = SaudaItem::query();

        if($request->start_date && $request->end_date){
            $saudaItems->where('sauda_date','>=',$request->start_date)->where('sauda_date','<=',$request->end_date);
        }

        $saudaItems = $saudaItems->with('supplier','deliveryOrderForItem')->withSum('dispatchSaudaItemToParty', 'dispatch_qty')->get();

        $formattedData = [];

        $saudaItems->each(function ($item) use (&$formattedData) {
            $date = $item->sauda_date;
            $itemId = $item->delivery_order_for_item_id;

            if (!isset($formattedData[$date])) {
                $formattedData[$date] = [];
            }

            if (!isset($formattedData[$date][$itemId])) {
                $formattedData[$date][$itemId] = [];
            }

            $formattedData[$date][$itemId][] = [
                'sauda_no' => $item->sauda_no,
                'supplier_name' => $item->supplier->name,
                'sauda_for' => $item->sauda_for,
                'acc_name'  => $item->acc_name,
                'order_for' => $item->deliveryOrderForItem->name,
                'qty' => $item->order_qty,
                'dispatch_qty'  => $item->dispatch_sauda_item_to_party_sum_dispatch_qty?$item->dispatch_sauda_item_to_party_sum_dispatch_qty:0,
                'pending_qty'  => $item->qty,
                'rate' => $item->rate,
            ];
        });

        $saudaItemsView = view('admin.sauda_item.ssr.filterview',compact('formattedData'))->render();

        $updatedFormattedData = [];

        foreach ($formattedData as $doDate => $saudaItem) {
            foreach ($saudaItem as $itemId => $items) {
                $order_qty = 0;
                $dispatch_qty = 0;
                $pending_qty = 0;
                $orderFor = null;

                foreach ($items as $item) {
                    $orderFor = $item['order_for'];
                    $order_qty += $item['qty'];
                    $dispatch_qty += $item['dispatch_qty'];
                    $pending_qty += $item['pending_qty'];
                }

                if (!isset($updatedFormattedData[$itemId])) {
                    $updatedFormattedData[$itemId] = [];
                }

                $updatedFormattedData[$itemId][] = [
                    'order_for' => $orderFor,
                    'order_qty' => $order_qty,
                    'dispatch_qty' => $dispatch_qty,
                    'pending_qty'  => $pending_qty,
                ];
            }
        }


        $saudaItemsSummaryView = view('admin.sauda_item.ssr.summaryview',compact('updatedFormattedData'))->render();

        return response(['status'=>true,'data'=>$saudaItemsView,'summary'=> $saudaItemsSummaryView]);

    }

    public function generateEntryView(Request $request)
    {
        $saudaItems = SaudaItem::query();

        if($request->start_date && $request->end_date){
            $saudaItems->where('sauda_date','>=',$request->start_date)->where('sauda_date','<=',$request->end_date);
        }

        $saudaItems = $saudaItems->with('supplier','deliveryOrderForItem')->get();

        $formattedData = [];

        $saudaItems->each(function ($item) use (&$formattedData) {
            $date = $item->sauda_date;
            $itemId = $item->delivery_order_for_item_id;

            if (!isset($formattedData[$date])) {
                $formattedData[$date] = [];
            }

            if (!isset($formattedData[$date][$itemId])) {
                $formattedData[$date][$itemId] = [];
            }

            $formattedData[$date][$itemId][] = [
                'sauda_for' => $item->sauda_for,
                'sauda_no' => $item->sauda_no,
                'supplier_name' => $item->supplier->name,
                'acc_name'  => $item->acc_name,
                'order_for' => $item->deliveryOrderForItem->name,
                'qty' => $item->order_qty,
                'rate' => $item->rate,
                'remarks' => $item->remarks,
            ];
        });

        $saudaItemsView = view('admin.sauda_item.ssr.entry_filterview',compact('formattedData'))->render();

        $updatedFormattedData = [];

        foreach ($formattedData as $doDate => $saudaItem) {
            foreach ($saudaItem as $itemId => $items) {
                $order_qty = 0;
                $orderFor = null;

                foreach ($items as $item) {
                    $orderFor = $item['order_for'];
                    $order_qty += $item['qty'];
                }

                if (!isset($updatedFormattedData[$itemId])) {
                    $updatedFormattedData[$itemId] = [];
                }

                $updatedFormattedData[$itemId][] = [
                    'order_for' => $orderFor,
                    'order_qty' => $order_qty,
                ];
            }
        }

        $totalOrderQty = collect($updatedFormattedData)->sum('order_qty');
        $totalDispatchQty = collect($updatedFormattedData)->sum('dispatch_qty');

        $saudaItemsSummaryView = view('admin.sauda_item.ssr.entry_summaryview',compact('updatedFormattedData','totalOrderQty','totalDispatchQty'))->render();

        return response(['status'=>true,'data'=>$saudaItemsView,'summary'=> $saudaItemsSummaryView]);

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
    public function store(SaudaItemStoreRequest $request)
    {
        $data = $request->validated();
        $data['fabric_name'] = json_encode($request->fabric_name);
        $data['order_qty'] = $data['qty'];
        SaudaItem::create($data);
        return redirect()->back()->with('message','Sauda Item Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaudaItem  $saudaItem
     * @return \Illuminate\Http\Response
     */
    public function show(SaudaItem $saudaItem)
    {
        $saudaItem->load('supplier','deliveryOrderForItem');
        $fabric_names = json_decode($saudaItem->fabric_name);
        $saudaItem->fabric_name = implode(', ', $fabric_names);
        return response(['status'=>true,'data'=>$saudaItem]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaudaItem  $saudaItem
     * @return \Illuminate\Http\Response
     */
    public function edit(SaudaItem $saudaItem)
    {
        // return view('admin.sauda_item.edit',compact('saudaItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaudaItem  $saudaItem
     * @return \Illuminate\Http\Response
     */
    public function update(SaudaItemUpdateRequest $request, SaudaItem $saudaItem)
    {
        // $saudaItem->update($request->validated());
        // return response(['status'=>true,'message'=>'Sauda Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaudaItem  $saudaItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaudaItem $saudaItem)
    {
        // $saudaItem->delete();
        // return response(['status'=>true,'message'=>'Sauda Deleted Successfully']);
    }
}
