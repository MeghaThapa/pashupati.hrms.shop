<?php

namespace App\Http\Controllers;

use App\Models\SaudaItem;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\DispatchSaudaItemToParty;
use App\Http\Requests\DispatchSauda\DispatchSaudaStoreRequest;
use App\Http\Requests\DispatchSauda\DispatchSaudaUpdateRequest;

class DispatchSaudaItemToPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = DispatchSaudaItemToParty::with('saudaItem','supplier','deliveryOrderForItem');

            if ($request->start_date && $request->end_date) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $query->whereBetween('dispatch_date', [$start_date, $end_date]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn("sauda_no",function($row){
                    return $row->saudaItem->sauda_no;
                })
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
                        $url = route("dispatch-sauda-item.update",$row->id);
                        return "<div class='btn-group'>
                                        <button class='btn btn-danger cancel_item' data-url='".$url."' >Cancel DO</button>
                                    </div>";
                    } else if($row->status == "Approved & Delivered") {
                        $url = route('dispatch-sauda-item.show',$row->id);
                        return "<div class='btn-group'>
                                        <a href='".$url."' class='btn btn-success'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                    </div>";
                    }
                })
                ->rawColumns(['action','status'])
                ->make(true);

        }

        $saudaItems = SaudaItem::select('id','sauda_no')->where('qty','>',0)->get();
        return view('admin.dispatch_sauda.index',compact('saudaItems'));
    }

    public function filterView()
    {
        return view('admin.dispatch_sauda.datewisefilter');
    }

    public function generateView(Request $request){
        $dispatchItems = DispatchSaudaItemToParty::query();

        if($request->start_date && $request->end_date){
            $dispatchItems->where('dispatch_date','>=',$request->start_date)->where('dispatch_date','<=',$request->end_date);
        }

        $dispatchItems = $dispatchItems->with('saudaItem','supplier','deliveryOrderForItem')->get();

        $formattedData = [];

        foreach ($dispatchItems as $item) {
            $dispatchDate = $item->dispatch_date;
            $deliveryItemId = $item->delivery_item_for_item_id;

            if (!isset($formattedData[$dispatchDate])) {
                $formattedData[$dispatchDate] = [];
            }

            if (!isset($formattedData[$dispatchDate][$deliveryItemId])) {
                $formattedData[$dispatchDate][$deliveryItemId] = [];
            }

            $formattedData[$dispatchDate][$deliveryItemId][] = [
                'id'            => $item->id,
                'for'           => $item->for,
                'receipt_no'    => $item->saudaItem->sauda_no,
                'supplier_name' => $item->supplier->name,
                'party_acc'     => $item->party_acc,
                'dispatch_for'  => $item->deliveryOrderForItem->name,
                'dispatch_qty'  => $item->dispatch_qty,
                'rate'          => $item->rate,
                'remarks'       => $item->remarks,
            ];
        }

        $updatedFormattedData = [];

        foreach ($formattedData as $dispatchDate => $dateData) {
            foreach ($dateData as $deliveryItemId => $items) {
                foreach ($items as $item) {
                    $dispatchFor = $item['dispatch_for'];
                    $dispatchQty = $item['dispatch_qty'];

                    // Check if the dispatch_for key exists, and create it if not
                    if (!isset($updatedFormattedData[$dispatchFor])) {
                        $updatedFormattedData[$dispatchFor] = [];
                        $updatedFormattedData[$dispatchFor]['dispatch_for'] = $dispatchFor;
                        $updatedFormattedData[$dispatchFor]['total_dispatch_qty'] = 0;
                    }

                    // Add the dispatch_qty to the total_dispatch_qty
                    $updatedFormattedData[$dispatchFor]['total_dispatch_qty'] += $dispatchQty;
                }
            }
        }

        $dispatchOrdersView = view('admin.dispatch_sauda.ssr.filterview',compact('formattedData'))->render();

        $summaryView = view('admin.dispatch_sauda.ssr.summaryview',compact('updatedFormattedData'))->render();

        return response(['status'=>true,'data'=> $dispatchOrdersView,'summary'=>$summaryView]);
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
    public function store(DispatchSaudaStoreRequest $request)
    {
        try{

            DB::beginTransaction();
            $data = $request->validated();
            $saudaItem = SaudaItem::where('id',$data['sauda_item_id'])->firstOrFail();
            $data['for'] = $saudaItem->sauda_for;
            $data['supplier_id'] = $saudaItem->supplier_id;
            $data['delivery_order_for_item_id'] = $saudaItem->delivery_order_for_item_id;
            $data['fabric_name'] = $saudaItem->fabric_name;
            $data['unit_name'] = $saudaItem->unit_name;
            $data['rate']  = $saudaItem->rate;
            DispatchSaudaItemToParty::create($data);

            $saudaItem->qty = $saudaItem->qty - $data['dispatch_qty'];
            $saudaItem->status = 'Dispatched';
            $saudaItem->save();

            DB::commit();
            return redirect()->back()->with('message','Dispatch Sauda Item to party created successfully');
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DispatchSaudaItemToParty  $dispatchSaudaItemToParty
     * @return \Illuminate\Http\Response
     */
    public function show(DispatchSaudaItemToParty $dispatchSaudaItemToParty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DispatchSaudaItemToParty  $dispatchSaudaItemToParty
     * @return \Illuminate\Http\Response
     */
    public function edit(DispatchSaudaItemToParty $dispatchSaudaItemToParty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DispatchSaudaItemToParty  $dispatchSaudaItemToParty
     * @return \Illuminate\Http\Response
     */
    public function update(DispatchSaudaUpdateRequest $request, DispatchSaudaItemToParty $dispatchSaudaItemToParty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DispatchSaudaItemToParty  $dispatchSaudaItemToParty
     * @return \Illuminate\Http\Response
     */
    public function destroy(DispatchSaudaItemToParty $dispatchSaudaItemToParty)
    {
        $dispatchSaudaItemToParty->delete();
    }
}
