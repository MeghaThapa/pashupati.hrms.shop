<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\StoreOutItem;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Department;
use App\Models\Storeout;
use App\Models\ItemsOfStorein;
use App\Models\Placement;
use App\Models\Size;
use App\Models\Stock;
use App\Models\StoreinDepartment;
use Exception;
//use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


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
    public function storoutYajraDatabales()
    {
        $storeout = DB::table('storeout')
        ->join('storein_departments','storein_departments.id','=','storeout.for')
        ->select(
            'storeout.id',
            'storein_departments.name as storein_department_name',
            'storeout.receipt_date',
            'storeout.receipt_no',
            'storeout.total_amount',
            )
        ->get();
        return DataTables::of($storeout)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '
                <a class="btnEdit" href="' . route('storeout.edit', ["storeout_id" => $row->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>

                <button class="btn btn-danger" id="dltStoreout" data-id="'.$row->id.'">
                    <i class="fas fa-trash-alt"></i>
                </button>
                '
                ;
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function deleteStoreout($storeout_id){
    try{
         DB::beginTransaction();
            $storeout= Storeout::find($storeout_id);
            $storeoutItems = $storeout->storeoutItems;

            if( $storeoutItems && count($storeoutItems) != 0){

                foreach($storeoutItems as $storeOutItem){
                    $stock= Stock::where('item_id', $storeOutItem->item_of_storein_id)
                    ->where('department_id', $storeOutItem->storeinDepartment_id)
                    ->where('size',$storeOutItem->size)
                    ->first();
                    $cat_id=ItemsOfStorein::find($storeOutItem->item_of_storein_id)->category_id;
                    if(!$stock){
                        $stock=new Stock();
                        $stock->department_id = $storeOutItem->storeinDepartment_id;
                        $stock->category_id= $cat_id;
                        $stock->item_id =$storeOutItem->item_of_storein_id;
                        $stock->size= $storeOutItem->size;
                        $stock->quantity= $storeOutItem->quantity;
                        $stock->unit=$storeOutItem->unit;
                        $stock->avg_price = $storeOutItem->rate;
                        $stock->total_amount = $storeOutItem->total;
                        $stock->save();
                    }
                    else{
                        $stock->quantity += $storeOutItem->quantity;
                        $total = $stock->total_amount + $storeOutItem->total_amount;
                        $stock->avg_price = $total / $stock->quantity;
                        $stock->total_amount  =  $stock->quantity * $stock->avg_price;
                        $stock->save();
                    }
                }
            }
           $storeout->delete();
            DB::commit();
        }catch(Exception $e){
                DB::rollBack();
                return $e;
        }
    }
    public function storeoutItemDelete($storeout_item_id)
    {
        try{
        DB::beginTransaction();
        $storeOutItem =StoreOutItem::find($storeout_item_id);
        $stock =Stock::where('item_id', $storeOutItem->item_of_storein_id)
        ->where('department_id', $storeOutItem->storeinDepartment_id)
        ->where('size',$storeOutItem->size)
        ->first();
        $stock->quantity += $storeOutItem->quantity;
        $stock->save();
        $storeOutItem->delete();
        DB::commit();
        return true;
        }
        catch(Expection $e){
            DB::rollback();
            return 'something went wrong while deleting item';
        }
    }


    public function create()
    {
        $receipt_no =  AppHelper::getStoreOutReceiptNo();
        $storeinDepartment =StoreinDepartment::get(['id','name']);
        $storeOut = null;
        return view('admin.storeout.create', compact('receipt_no', 'storeOut','storeinDepartment'));
    }

    public function getEditItemData($storeoutItem_id)
    {
        $storeOutItemData = StoreOutItem::with('department', 'placement', 'itemsOfStorein')->find($storeoutItem_id);
        return $storeOutItemData;
    }

    public function edit($storeout_id)
    {
        $receipt_no = null;
        $storeinDepartment =StoreinDepartment::get(['id','name']);
        $storeOut = StoreOut::find($storeout_id);
        return view('admin.storeout.create', compact('receipt_no', 'storeOut','storeinDepartment'));
    }

    public function saveStoreout(Request $request)
    {
       $request->validate([
            'receipt_date'          => 'required|string|max:50',
            'receipt_no'         => 'required|string|unique:admin_storeout,receipt_no',
            'for_id'         => 'required',
        ]);
        try{
            $storeout = new Storeout;
            $storeout->receipt_date = $request->receipt_date;
            $storeout->receipt_no = $request->receipt_no;
            $storeout->for = $request->for_id;
            $storeout->save();
            return redirect()->route('storeout.storeOutItems', ['store_out_id' => $storeout->id]);
        }catch (Exception $ex){
            return 'something went wrong while storing storeout';
        }
    }
    public function storeOutItems($store_out_id)
    {
        $storeOut = Storeout::find($store_out_id);
        $storeinDepartment =StoreinDepartment::get(['id','name']);
        $items = Stock::with('item','sizes')->get();


        return view('admin.storeout.createStoreoutItems', compact('storeinDepartment', 'items', 'storeOut'));
    }
    public function getDepartmentPlacements($dept_id)
    {
        return Placement::where('department_id', $dept_id)->get(['id','name']);
    }
    public function updateStoreOut(Request $request, $storeout_id)
    {
       $request->validate([
            'receipt_date'          => 'required|string|max:50',
            'receipt_no'         => 'required|string',
            'for_id'         => 'required',
        ]);
        try{
        $storeOut = Storeout::find($storeout_id);
        $storeOut->receipt_date = $request->receipt_date;
        $storeOut->receipt_no = $request->receipt_no;
        $storeOut->for = $request->for_id;
        $storeOut->save();
        return redirect()->route('storeout.storeOutItems', ['store_out_id' => $storeOut->id]);
        }catch (Exception $ex){
            return 'something went wrong while storing storeout';
        }
    }

    public function saveStoreoutItems(Request $request)
    {
        $request->validate([
            'storeout_id' => 'required',
            'item_id' => 'required',
            'size' => 'required',
            'unit' => 'required',
            'rate' => 'required',
            'quantity' => 'required',
            'department_id' => 'required',
            'placement_id' => 'required',
            'through' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $storeOutItem = new StoreOutItem();
            $storeOutItem->item_of_storein_id = $request->item_id;
            $storeOutItem->storeout_id = $request->storeout_id;
            $storeOutItem->storeinDepartment_id = $request->department_id;
            $storeOutItem->placement_id = $request->placement_id;
            $storeOutItem->unit = $request->unit;
            $storeOutItem->size = $request->size;
            $storeOutItem->quantity = $request->quantity;
            $storeOutItem->rate = $request->rate;
            $storeOutItem->through = $request->through;
            $storeOutItem->total = $storeOutItem->quantity * $storeOutItem->rate;
            $storeOutItem->save();

            $stock = Stock::where('item_id', $storeOutItem->item_of_storein_id)
            ->where('department_id', $storeOutItem->storeinDepartment_id)
            ->where('size',$storeOutItem->size)
            ->first();

            if ($stock) {
                if ($stock->quantity < $request->quantity) {
                    return response()->json([
                        'message' => "you don't have enough stock available.",
                    ], 500);
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
                ], 500);
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
        return  StoreOutItem::with(['itemsOfStorein', 'placement', 'department'])->find($storeOutItem_id);
    }
    public function getStoreOutItemData($storeout_id)
    {
        $storeout_items = StoreOutItem::with(['placement', 'itemsOfStorein', 'department'])->where('storeout_id', $storeout_id)->get();
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
            $storeOutItem =  Stock::where('item_id', $storeOutItem->item_of_storein_id)
            ->where('department_id', $storeOutItem->storeinDepartment_id)
            ->where('size',$storeOutItem->size)
            ->first();
            // old storeout quantity
            $oldStoreOutQuantity = $storeOutItem->quantity;
            $storeOutItem->item_of_storein_id = $request->item_id;
            $storeOutItem->quantity = $request->quantity;
            $storeOutItem->storeinDepartment_id = $request->department_id;
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
