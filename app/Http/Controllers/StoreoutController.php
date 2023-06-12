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
use App\Models\Godam;
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
        ->join('godam','godam.id','=','storeout.godam_id')
        ->select(
            'storeout.id',
            'godam.name as storein_department_name',
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
        ->where('unit',$storeOutItem->unit_id )
        ->where('size',$storeOutItem->size_id )
        ->first();
        $stock->quantity += $storeOutItem->quantity;
        $stock->total_amount = $stock->quantity*$stock->avg_price;
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
        $godams =Godam::get(['id','name']);
        $storeOut = null;
        return view('admin.storeout.create', compact('receipt_no', 'storeOut','godams'));
    }

    public function getEditItemData($storeoutItem_id)
    {
        $storeOutItemData = StoreOutItem::with('department', 'placement', 'itemsOfStorein')->find($storeoutItem_id);
        return $storeOutItemData;
    }

    public function edit($storeout_id)
    {
        $receipt_no = null;
       $godams =Godam::get(['id','name']);
        $storeOut = StoreOut::find($storeout_id);
        return view('admin.storeout.create', compact('receipt_no', 'storeOut','godams'));
    }

    public function saveStoreout(Request $request)
    {
       $request->validate([
            'receipt_date'          => 'required|string|max:50',
            'receipt_no'         => 'required|string|unique:storeout,receipt_no',
            'for_id'         => 'required',
        ]);
        try{
            $storeout = new Storeout;
            $storeout->receipt_date = $request->receipt_date;
            $storeout->receipt_no = $request->receipt_no;
            $storeout->godam_id = $request->for_id;
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
        $stockCategory = DB::table('stocks')
        ->join('storein_categories','storein_categories.id','=','stocks.category_id')
        ->select('storein_categories.id as category_id','storein_categories.name as category_name')
        ->distinct('storein_categories.name')
        ->get();
        //return $stockCategory;
        return view('admin.storeout.createStoreoutItems', compact('storeOut','stockCategory','storeinDepartment'));
    }
    public function getDepartmentPlacements($dept_id)
    {
        return Placement::where('department_id', $dept_id)->get(['id','name']);
    }
    public function getStoreinItemAccCat($category_id){
        return DB::table('stocks')
        ->where('stocks.category_id', $category_id)
        ->join('items_of_storeins','items_of_storeins.id','stocks.item_id')
        ->select(
        'items_of_storeins.id as item_id',
        'items_of_storeins.name as item_name',
        'items_of_storeins.pnumber as item_code',

        )
        ->distinct('storein_categories.name')
        ->get();
    }
    public function getDepartmentSizeUnit($items_of_storein_name, $category_id){
        $stocks =Stock::with('department:id,name','units:id,name','sizes:id,name')
        ->whereIn('item_id', function ($query) use ($items_of_storein_name) {
            $query->select('id')
                ->from('items_of_storeins')
                ->where('name', $items_of_storein_name);
        })
        ->where('category_id',$category_id)
        ->groupBy(['size','unit','department_id'])
        ->get(['size','unit','department_id']);
        // return $stocks;


        $ArrayStock =$stocks->toArray();

        $unitArray = [];
        $sizeArray = [];
        $departmentArray = [];

        foreach ($stocks as $stock) {
            $unit = $stock->units;
            $size = $stock->sizes;
            $department = $stock->department;

            if (!in_array($unit, $unitArray, true)) {
                $unitArray[] = $unit;
            }

            if (!in_array($size, $sizeArray, true)) {
                $sizeArray[] = $size;
            }

            if (!in_array($department, $departmentArray, true)) {
                $departmentArray[] = $department;
            }
        }


        return response()->json(
            [
                'units' => $unitArray,
                'size' => $sizeArray,
                'department' => $departmentArray
            ]
            );

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
        $storeOut->godam_id  = $request->for_id;
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
            'item_name' =>'required',
            'size' => 'required',
            'unit' => 'required',
            'quantity' => 'required',
            'department_id' => 'required',
            'placement_id' => 'required',
            'through' => 'required',
        ]);

        $stock = Stock::where('item_id', function ($query) use ($request) {
            $query->select('id')
            ->from('items_of_storeins')
            ->where('department_id',$request->department_id)
            ->where('unit_id',$request->unit)
            ->where('size_id',$request->size)
            ->where('name', $request->item_name);
        })
        ->where('department_id',$request->department_id)
        ->where('size',$request->size)
        ->where('unit',$request->unit)
        ->first();
    //    return $stock;
        try {
            DB::beginTransaction();

            $storeOutItem = new StoreOutItem();
            $storeOutItem->item_of_storein_id = $stock->item_id;
            $storeOutItem->storeout_id = $request->storeout_id;
            $storeOutItem->storeinDepartment_id = $request->department_id;
            $storeOutItem->placement_id = $request->placement_id;
            $storeOutItem->unit_id = $request->unit;
            $storeOutItem->size_id = $request->size;
            $storeOutItem->quantity = $request->quantity;
            $storeOutItem->rate = $stock->avg_price;
            $storeOutItem->through = $request->through;
            $storeOutItem->total = $storeOutItem->quantity * $stock->avg_price;
            $storeOutItem->save();

            if ($stock) {
                if ($stock->quantity < $request->quantity) {
                    return response()->json([
                        'message' => "you don't have enough stock available.",
                    ], 500);
                } else {
                    $stock->quantity = $stock->quantity - $request->quantity;
                    $stock->total_amount = $stock->quantity * $stock->avg_price;
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
                'storeOutItem' =>  Self::getSingleStoreOutItemData($storeOutItem->id),
                'stock' => $stock
            ]);
            // Both operations succeeded
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($e);
        }
    }

    public function getSingleStoreOutItemData($storeOutItem_id)
    {
        return  StoreOutItem::with(['itemsOfStorein','size','unit', 'placement', 'department'])->find($storeOutItem_id);
    }
    public function getStoreOutItemData($storeout_id)
    {
        $storeout_items = StoreOutItem::with(['placement', 'size','unit','itemsOfStorein', 'department'])->where('storeout_id', $storeout_id)->get();
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
