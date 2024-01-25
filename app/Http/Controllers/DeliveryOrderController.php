<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryOrderForItem;
use App\Http\Requests\DeliveryOrder\DeliveryOrderStoreRequest;
use App\Http\Requests\DeliveryOrder\DeliveryOrderUpdateRequest;
use App\Models\FabricSaleEntry;
use Illuminate\Support\Facades\Artisan;

class DeliveryOrderController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = DeliveryOrder::with('supplier','deliveryOrderForItem');

            if ($request->start_date && $request->end_date) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $query->whereBetween('do_date', [$start_date, $end_date]);
            }

            if(!auth()->user()->hasRole('Admin')){
                $query->where('created_by',auth()->user()->id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn("supplier_id",function($row){
                    return $row->supplier->name;
                })
                ->editColumn("delivery_order_for_item_id",function($row){
                    return $row->deliveryOrderForItem->name;
                })
                ->editColumn('status',function($row){
                    if($row->status == "Pending")
                        if(auth()->user()->hasRole('Admin'))
                            return '<span class="badge badge-primary update_status" data-url="'.route("delivery-order.update",$row->id).'" >'.$row->status.'</span>';
                        else
                            return '<span class="badge badge-primary" data-url="'.route("delivery-order.update",$row->id).'" >'.$row->status.'</span>';
                    elseif($row->status == "Approved")
                        return '<span class="badge badge-success">'.$row->status.'</span>';
                    elseif($row->status == "Approved & Delivered")
                        return '<span class="badge badge-delivered">'.$row->status.'</span>';
                    else
                        return '<span class="badge badge-danger">'.$row->status.'</span>';
                })
                ->addColumn("action", function ($row) {
                    if ($row->status == "Pending") {
                        $url = route("delivery-order.update",$row->id);
                        return "<div class='btn-group'>
                                        <button class='btn btn-danger cancel_item' data-url='".$url."' >Cancel DO</button>
                                    </div>";
                    } else if($row->status == "Approved & Delivered") {
                        $url = route('delivery-order.show',$row->id);
                        return "<div class='btn-group'>
                                        <a href='".$url."' class='btn btn-success'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                    </div>";
                    }
                })
                ->rawColumns(['action','status'])
                ->make(true);

        }
        $nextId = DB::table('delivery_orders')->max('id') + 1;
        $suppliers = Supplier::all();
        $deliveryOrderForItems = DeliveryOrderForItem::all();
        return view('admin.delivery_order.index',compact('nextId','suppliers','deliveryOrderForItems'));
    }

    public function filterView()
    {
        return view('admin.delivery_order.datewisefilter');
    }

    public function generateView(Request $request)
    {
        $deliveryOrders = DeliveryOrder::with('supplier','deliveryOrderForItem');
        if($request->start_date && $request->end_date){
            $deliveryOrders = $deliveryOrders->where('do_date','>=',$request->start_date)->where('do_date','<=',$request->end_date);
        }
        if($request->status){
            $deliveryOrders = $deliveryOrders->where('status',$request->status);
        }
        $deliveryOrders = $deliveryOrders->get();

        $deliveryOrders = $deliveryOrders->mapToGroups(function ($item, $key) {
            return [
                $item->do_date => [
                    'do_no' => $item->do_no,
                    'do_date' => $item->do_date,
                    'supplier_name' => $item->supplier->name,
                    'do_for' => $item->deliveryOrderForItem->name,
                    'qty_in_mt' => $item->qty_in_mt,
                    'bundel_pcs' => $item->bundel_pcs,
                    'base_rate_per_kg' => $item->base_rate_per_kg,
                    'overdue_amount' => $item->overdue_amount,
                    'total_due' => $item->total_due,
                ]
            ];
        });

        $deliveryOrdersView = view('admin.delivery_order.ssr.filterview',compact('deliveryOrders'))->render();
        return response(['status'=>true,'data'=>$deliveryOrdersView]);
    }

    public function approvedDeliveryOrder()
    {
        $deliveryOrders = DeliveryOrder::with('supplier','deliveryOrderForItem')->whereStatus('Approved')->get();
        $deliveryOrdersView = view('admin.delivery_order.ssr.approvedlist',compact('deliveryOrders'))->render();
        return response(['status'=>true,'data'=>$deliveryOrdersView]);
    }

    public function store(DeliveryOrderStoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->user()->id;
        DeliveryOrder::create($data);
        return redirect()->route('delivery-order.index');
    }

    public function show(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('supplier','deliveryOrderForItem');
        $fabricSaleEntries = FabricSaleEntry::where('do_no',$deliveryOrder->do_no)->get();
        return view('admin.delivery_order.show',compact('deliveryOrder','fabricSaleEntries'));
    }

    public function edit(DeliveryOrder $deliveryOrder)
    {
        return view('admin.delivery_order.edit',compact('deliveryOrder'));
    }

    public function update(DeliveryOrderUpdateRequest $request, DeliveryOrder $deliveryOrder)
    {
        if(!auth()->user()->hasRole('Admin')){
            return response(['status'=>false,'message'=>'Status Approval Failed']);
        }
        $deliveryOrder->update($request->validated());
        return response(['status'=>true,'message'=>'Status updated successfully']);
    }

    public function destroy(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->delete();
        return redirect()->route('delivery-order.index');
    }
}
