<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Supplier;
use App\Models\StoreinItem;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\ItemsOfStorein;
use App\Models\StoreinCategory;
use Yajra\DataTables\DataTables;
use App\Models\PurchaseOrderItem;
use App\Models\StoreinDepartment;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $query = PurchaseOrder::with('preparedBy');

            if ($request->start_date && $request->end_date) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $query->whereBetween('date', [$start_date, $end_date]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('prepared_by', function ($row) {
                    return $row->preparedBy->name;
                })
                ->editColumn('status',function($row){
                    if(auth()->user()->hasRole('Admin')){
                        if($row->status=="Complete"){
                            $url = route('purchase-order.unconfirm',$row->id);
                            return '<span style="pointer:cursor;" data-title="Pending" data-url="'.$url.'" class="status_update badge badge-success">'.$row->status.'</span>';
                        }else{
                            $url = route('purchase-order.confirm',$row->id);
                            return '<span style="pointer:cursor;" data-title="Complete" data-url="'.$url.'" class="status_update badge badge-warning">'.$row->status.'</span>';
                        }
                    }else{
                        return '<span class="badge badge-primary">'.$row->status.'</span>';
                    }
                })
                ->addColumn("action", function ($row) {
                    if ($row->status == "Pending") {
                        return "<div class='btn-group'>
                                        <a href='".route('purchase-order.edit',$row->id)."' class='btn btn-primary'><i class='fa fa-edit' aria-hidden='true'></i></a>
                                        <button class='btn btn-danger delete-cc-entry' data-id='{$row->id}'><i class='fa fa-trash' aria-hidden='true'></i></button>
                                    </div>";
                    } else {
                        return "<div class='btn-group'>
                                        <a href='".route('purchase-order.show',$row->id)."' style='color:green;background-color:yellow;border-radius:20px;padding:10px;'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                    </div>";
                    }
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.purchase_order.index');
    }

    public function itemIndex(Request $request,PurchaseOrder $purchaseOrder){
        $query = PurchaseOrderItem::query()
                ->with('itemsOfStoreins.storeinCategory','storeinDepartment')
                ->where('purchase_order_id',$purchaseOrder->id);

        return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('storein_department_id',function($row){
                    return $row->storeinDepartment->name;
                })
                ->editColumn('purchase_rate',function($row){
                    return $row->purchase_rate?$row->purchase_rate:'-';
                })
                ->addColumn('parts_number',function($row){
                    return $row->itemsOfStoreins->pnumber;
                })
                ->addColumn('size',function($row){
                    return $row->itemsOfStoreins->size->name;
                })
                ->addColumn('category',function($row){
                    return $row->itemsOfStoreins->storeinCategory->name;
                })
                ->addColumn("action", function ($row) {
                        if($row->status=="Pending"){
                            $url = route('purchase-order.item.destroy',$row->id);
                            return "<div class='btn-group'>
                                            <button class='btn btn-danger delete_item' data-url='".$url."'><i class='fa fa-trash' aria-hidden='true'></i></button>
                                    </div>";
                        }
                        return "";
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);
        $purchaseOrderExists = PurchaseOrder::latest()->first();
        $purchaseOrder = PurchaseOrder::create([
            'date'=> $request->date,
            'indent_no' => $purchaseOrderExists? $purchaseOrderExists->indent_no + 1: 1,
            'prepared_by' => auth()->user()->id,
            'status'=> 'Pending',
        ]);

        $url = route('purchase-order.edit',$purchaseOrder->id);

        return response()->json([
            'status' => true,
            'url'    => $url,
        ]);
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('preparedBy');
        $purchaseOrder->load('purchaseOrderItems.itemsOfStoreins.storeinCategory');
        $purchaseOrder->load('purchaseOrderItems.itemsOfStoreins.size');
        $purchaseOrder->load('purchaseOrderItems.storeinDepartment');
        return view('admin.purchase_order.show',compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        if($purchaseOrder->status=="Complete"){
            return redirect()->route('purchase-order.index');
        }
        $categories = StoreinCategory::all();
        return view('admin.purchase_order.edit',compact('purchaseOrder','categories'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'storein_department_id'=>'required',
            'store_in_item_id'=>'required',
            'req_quantity'=>'required|numeric',
            'remarks' => 'nullable',
        ]);
        $itemOfStoreIn = ItemsOfStorein::where('id',$request->store_in_item_id)->firstOrFail();
        $latestPurchaseStoreInItem = StoreinItem::with('storeIn.supplier')->where('storein_item_id',$itemOfStoreIn->id)->latest()->first();
        $stock = Stock::where('category_id',$itemOfStoreIn->category_id)->where('department_id',$itemOfStoreIn->department_id)->where('item_id',$itemOfStoreIn->id)->first();
        PurchaseOrderItem::create([
            'purchase_order_id' => $purchaseOrder->id,
            'item_name' => $itemOfStoreIn->name,
            'storein_department_id' => $request->storein_department_id,
            'items_of_storeins_id' => $request->store_in_item_id,
            'stock_quantity' => $stock? $stock->quantity:0,
            'req_quantity' => $request->req_quantity,
            'last_purchase_from' => $latestPurchaseStoreInItem?$latestPurchaseStoreInItem->storeIn->supplier->name:'-',
            'purchase_rate' => $latestPurchaseStoreInItem?$latestPurchaseStoreInItem->price:null,
            'remarks' => $request->remarks,
            'status' =>'Pending',
        ]);
        return response(['status'=> true,'message'=> 'Purchase Order Added']);
    }

    public function itemDestroy(PurchaseOrderItem $purchaseOrderItem)
    {
        if($purchaseOrderItem->status == "Pending"){
            $purchaseOrderItem->delete();
            return response(['status'=> true,'message'=> 'Purchase Order Item Deleted']);
        }else{
            return response(['status'=>false,'message'=>'Purchase Order Item cannot be deleted']);
        }
    }

    public function getStoreInDepartments(Request $request){
        $departments = StoreinDepartment::where('category_id',$request->id)->get();
        return response(['status'=>true,'data'=>$departments]);
    }

    public function getStoreInItems(Request $request){
        $itemOfStoreIns = ItemsOfStorein::with('size')->where('department_id',$request->id)->get();
        return response(['status'=>true,'data'=>$itemOfStoreIns]);
    }

    public function getItemDetails(Request $request){
        $itemOfStoreIn = ItemsOfStorein::with('size')->where('id',$request->id)->firstOrFail();
        $stock = Stock::where('category_id',$itemOfStoreIn->category_id)->where('department_id',$itemOfStoreIn->department_id)->where('item_id',$itemOfStoreIn->id)->first();
        $latestPurchaseStoreInItem = StoreinItem::with('storeIn.supplier')->where('storein_item_id',$itemOfStoreIn->id)->latest()->first();
        return response(['status'=>true,'data'=>$itemOfStoreIn,'lastPurchaseItem'=>$latestPurchaseStoreInItem,'stock'=>$stock]);
    }

    public function confirmPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        if(auth()->user()->hasRole('Admin')){
            $purchaseOrder->status = 'Complete';
            $purchaseOrder->save();

            $purchaseOrderItems = PurchaseOrderItem::where('purchase_order_id',$purchaseOrder->id)->get();
            foreach($purchaseOrderItems as $purchaseOrder){
                $purchaseOrder->status = "Completed";
                $purchaseOrder->save();
            }
        }
        return redirect()->route('purchase-order.index');
    }

    public function unConfirmPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        if(auth()->user()->hasRole('Admin')){
            $purchaseOrder->status = 'Pending';
            $purchaseOrder->save();

            $purchaseOrderItems = PurchaseOrderItem::where('purchase_order_id',$purchaseOrder->id)->get();
            foreach($purchaseOrderItems as $purchaseOrder){
                $purchaseOrder->status = "Pending";
                $purchaseOrder->save();
            }
        }
        return redirect()->route('purchase-order.index');
    }
}
