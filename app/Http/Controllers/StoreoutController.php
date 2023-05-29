<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\AdminStoreOutItem;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Department;
use App\Models\SubCategory;
use App\Models\Setupstorein;
use App\Models\Setupstoreout;
use App\Models\Storeout;
use App\Models\Items;
use App\Models\Placement;
use App\Models\Size;
use App\Models\Stock;
use Exception;
//use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class StoreoutController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $storeOutDatas = Storeout::all();
        // return $storeOutDatas;
        return view('admin.storeout.index', compact('storeOutDatas'));
    }
    public function storeoutItemDelete($storeout_item_id)
    {
        $storeOutItem = AdminStoreOutItem::find($storeout_item_id)->first();
        $stock = Stock::where('item_id', $storeOutItem->item_id)->first();

        $presentStockQuantity = $stock->quantity;
        $oldStockQuantity = $presentStockQuantity + $storeOutItem->quantity;
        $stock->quantity = $oldStockQuantity;
        $stock->save();
        $storeOutItem->delete();

        return true;
        // return response()->json([
        //     'message' => 'Storeout Item deleted Successfully.',
        //     'storeOutItem' => $storeOutItem
        // ]);
    }

    /**
     * Show the form for creating a new purchase.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $receipt_no =  AppHelper::getStoreOutReceiptNo();
        $storeOut = null;
        return view('admin.storeout.create', compact('receipt_no', 'storeOut'));
    }

    public function getEditItemData($storeoutItem_id)
    {
        $storeOutItemData = AdminStoreOutItem::with('department', 'placement', 'item')->find($storeoutItem_id);
        return $storeOutItemData;
    }
    /**
     * Store a newly created purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }


    public function edit($storeout_id)
    {
        $receipt_no = null;
        $storeOut = StoreOut::find($storeout_id);
        return view('admin.storeout.create', compact('receipt_no', 'storeOut'));
    }

    /**
     * Update the specified purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
    }

    public function saveStoreout(Request $request)
    {

        $validator = $request->validate([
            'receipt_date'          => 'required|string|max:50',
            'receipt_no'         => 'required|string|unique:admin_storeout,receipt_no',
            'for_id'         => 'required',
        ]);
        $storeout = new Storeout;
        $storeout->receipt_date = $request->receipt_date;
        $storeout->receipt_no = $request->receipt_no;
        $storeout->for = $request->for_id;
        $storeout->status = 'pending';
        $storeout->save();

        return redirect()->route('storeout.storeOutItems', ['store_out_id' => $storeout->id]);
    }
    public function storeOutItems($store_out_id)
    {
        $storeOut = Storeout::find($store_out_id);
        $departments = Department::get();
        $items = Stock::with('item')->get();

        //return $items;
        return view('admin.storeout.createStoreoutItems', compact('departments', 'items', 'storeOut'));
    }
    public function getDepartmentPlacements($dept_id)
    {
        $placement = Placement::where('department_id', $dept_id)->get();
        return $placement;
    }
    public function updateStoreOut(Request $request, $storeout_id)
    {
        $validator = $request->validate([
            'receipt_date'          => 'required|string|max:50',
            'receipt_no'         => 'required|string',
            'for_id'         => 'required',
        ]);
        $storeOut = Storeout::find($storeout_id);
        $storeOut->receipt_date = $request->receipt_date;
        $storeOut->receipt_no = $request->receipt_no;
        $storeOut->for = $request->for_id;
        $storeOut->save();
        return redirect()->route('storeout.storeOutItems', ['store_out_id' => $storeOut->id]);
    }

    public function saveStoreoutItems(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = $request->validate([
                'storeout_id' => 'required',
                'item_id' => 'required',
                'size' => 'required',
                'unit' => 'required',
                'rate' => 'required',
                'quantity' => 'required',
                'department_id' => 'required',
                'placement_id' => 'required',
                'through' => 'required',
                //'remark' => 'required',
            ]);

            $storeOutItem = new AdminStoreOutItem();
            $storeOutItem->item_id = $request->item_id;
            $storeOutItem->storeout_id = $request->storeout_id;
            $storeOutItem->department_id = $request->department_id;
            $storeOutItem->placement_id = $request->placement_id;
            $storeOutItem->unit = $request->unit;
            $storeOutItem->size = $request->size;
            $storeOutItem->quantity = $request->quantity;
            $storeOutItem->rate = $request->rate;
            $storeOutItem->through = $request->through;
            $storeOutItem->total = $storeOutItem->quantity * $storeOutItem->rate;
            $storeOutItem->save();

            $stock = Stock::where('item_id', $storeOutItem->item_id)->first();
            if ($stock) {
                if ($stock->quantity < $request->quantity) {
                    return response()->json([
                        'message' => "you don't have enough stock available.",
                    ], 400);
                } else {
                    $stock->quantity = $stock->quantity - $request->quantity;
                    $stock->save();
                    if ($stock->quantity <= 0) {
                        $stock->delete();
                    }
                }
            } else {
                return response()->json([
                    'message' => 'Something went wrong.',
                ], 400);
                // throw new Exception("Stock not found");
            }

            DB::commit();
            return response()->json([
                'message' => 'Storeout Item Created Successfully.',
                'storeOutItem' =>  Self::getStoreinItemData($storeOutItem->id),
                'stock' => $stock
            ]);
            // Both operations succeeded
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($e);
        }
    }

    public function getStoreinItemData($storeOutItem_id)
    {
        return  AdminStoreOutItem::with(['item', 'placement', 'department'])->find($storeOutItem_id);
    }
    public function getStoreOutItemData($storeout_id)
    {
        $storeout_items = AdminStoreOutItem::with(['placement', 'item', 'department'])->where('storeout_id', $storeout_id)->get();
        // return $storeout_items;

        return response()->json([
            'storeOutItem' =>  $storeout_items
        ]);
    }
    public function saveEntireStoreOut(Request $request, $storeout_id)
    {
        try {
            $storeout = Storeout::find($storeout_id);
            $storeout->total_amount = $request->storeOutTotal;
            $storeout->remark = $request->store_out_remark;
            $storeout->save();
            return redirect()->route('storeout.index')->withSuccess('Store Out Created Successfully!');
        } catch (Exception $e) {
            return back()->withErrors('Something went Wrong!');
        }
    }
    public function updateStoreOutItems(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = $request->validate([
                'quantity'          => 'required|min:1',
                'item_id'         => 'required',
                'department_id'   => 'required',
                'placement_id'    => 'required',
                'through'   => 'required',

            ]);
            $storeOutItem = AdminStoreOutItem::find($request->storeout_item_id);
            // old storeout quantity
            $oldStoreOutQuantity = $storeOutItem->quantity;
            $storeOutItem->item_id = $request->item_id;
            $storeOutItem->quantity = $request->quantity;
            $storeOutItem->department_id = $request->department_id;
            $storeOutItem->placement_id = $request->placement_id;
            $storeOutItem->through = $request->through;
            $storeOutItem->total = $storeOutItem->quantity * $storeOutItem->rate;
            $storeOutItem->save();

            $stock = Stock::where('item_id', $request->item_id)->first();
            if ($stock) {
                if ($stock->quantity < $request->quantity) {
                    return response()->json([
                        'message' => "you don't have enough stock available.",
                    ], 400);
                } else {
                    $stock->quantity = ($stock->quantity +  $oldStoreOutQuantity) - $request->quantity;
                    $stock->save();
                    if ($stock->quantity <= 0) {
                        $stock->delete();
                    }
                }
            }
            DB::commit();

            return response()->json([
                'message' => 'Store out updated successfully',
                'storeOutItem' =>  self::getStoreinItemData($storeOutItem->id)
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json('Something is wrong', 400);
        }
    }
}
