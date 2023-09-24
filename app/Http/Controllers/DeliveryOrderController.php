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
                        return '<span class="badge badge-primary update_status" data-url="'.route("delivery-order.update",$row->id).'" >'.$row->status.'</span>';
                    elseif($row->status == "Approved")
                        return '<span class="badge badge-success update_status" data-url="'.route("delivery-order.update",$row->id).'">'.$row->status.'</span>';
                })
                ->addColumn("action", function ($row) {
                    if ($row->status == "Pending") {
                        return "<div class='btn-group'>
                                        <button class='btn btn-primary edit_item' data-id='{$row->id}'><i class='fa fa-plus' aria-hidden='true'></i></button>
                                        <button class='btn btn-danger delete_item' data-id='{$row->id}'><i class='fa fa-trash' aria-hidden='true'></i></button>
                                    </div>";
                    } else {
                        return "<div class='btn-group'>
                                        <button class='btn btn-secondary view-cc' data-id='{$row->id}'><i class='fa fa-eye' aria-hidden='true'></i></button>
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

    public function store(DeliveryOrderStoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->user()->id;
        DeliveryOrder::create($data);
        return redirect()->route('delivery-order.index');
    }

    public function show(DeliveryOrder $deliveryOrder)
    {
        return view('admin.delivery_order.show',compact('deliveryOrder'));
    }

    public function edit(DeliveryOrder $deliveryOrder)
    {
        return view('admin.delivery_order.edit',compact('deliveryOrder'));
    }

    public function update(DeliveryOrderUpdateRequest $request, DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->update($request->validated());
        return response(['status'=>true,'message'=>'Status updated successfully']);
    }

    public function destroy(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->delete();
        return redirect()->route('delivery-order.index');
    }
}
