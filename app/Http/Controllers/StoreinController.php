<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\StoreinCategory;
use App\Models\Charges;
use App\Models\StoreinDepartment;
use App\Models\SubCategory;
 use App\Models\StoreinType;
// use DBApp\Models\Setupstoreout;
use App\Models\Storein;
use App\Models\ItemsOfStorein;
use App\Models\Size;
use App\Models\Stock;
use App\Models\StoreinItem;
use App\Models\Tax;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;



class StoreinController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $storeinDatas = Storein::with('supplier')->orderBy('created_at', 'DESC')->get();

        return view('admin.storein.index', compact('storeinDatas'));
    }

    /**
     * Show the form for creating a new purchase.
     *
     * @return \Illuminate\Http\Response
     */
    //  m

    public function categoryNameForTbl($id)
    {
        $categoryName = StoreinCategory::find($id)->get('name');
        return $categoryName;
    }

    public function createStorein()
    {
        $storeintype = StoreinType::all();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $categories = StoreinCategory::where('status', 1)->get();
        $storeinData = null;
        return view('admin.storein.storeinCreate', compact('storeintype', 'suppliers', 'categories', 'storeinData'));
    }

    public function editStorein($storein_id)
    {
        // return $storein_id;
        $storeintype = StoreinType::all();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $storeinData = Storein::with('storeinType', 'supplier')->find($storein_id);
        // return $storeinData;
        return view('admin.storein.storeinCreate', compact('storeintype', 'suppliers', 'storeinData'));
    }

    public function storinYajraDatabales()
    {
        $storein = DB::table('storein')
        ->join('suppliers','suppliers.id','=','storein.supplier_id')
        ->join('storein_types','storein_types.id','=','storein.storein_type_id')
        ->select(
            'storein.id',
            'suppliers.name as supplier_name',
            'storein_types.name as storein_type_name',
            'storein.sr_no',
            'storein.bill_no',
            'storein.pp_no',
            'storein.purchase_date',
            'storein.total_discount',
            'storein.grand_total'
            )
        ->get();


        return DataTables::of($storein)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '
                <a class="btnEdit" href="' . route('storein.editStorein', ["storein_id" => $row->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>

                <button class="btn btn-danger" id="dltstorein" data-id="'.$row->id.'">
                    <i class="fas fa-trash-alt"></i>
                </button>

                <a class="btnEdit" href="' . route('storein.invoiceView', ["storein_id" => $row->id]) . '" >
                    <button class="btn btn-info">
                        <i class="fas fa-file-invoice"></i>
                    </button>
                </a>



                '
                ;

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function updateStorein(Request $request, $storein_id)
    {

            $validator = $request->validate([
                'type' => 'required',
                'ppno' => [
                    Rule::requiredIf(function () use ($request) {
                         return in_array(StoreinType::find($request->type)->name, ['import']);
                    }),
                ],
                'srno' => [
                    Rule::requiredIf(function () use ($request) {
                        return in_array(StoreinType::find($request->type)->name, ['import','local']);
                   }),
                ],
                'billno' => [
                    Rule::requiredIf(function () use ($request) {
                        return in_array(StoreinType::find($request->type)->name, ['sapat','local']);
                   }),
                ],
                'purchaseDate' => 'required|date',
                'supplier' => 'required',
            ]);

        $storein = Storein::find($storein_id);
        $storein->sr_no = $request->srno;
        $storein->bill_no = $request->billno;
        $storein->pp_no = $request->ppno;
        $storein->purchase_date = $request->purchaseDate;
        $storein->storein_type_id = $request->type;
        $storein->supplier_id = $request->supplier;
        $storein->save();
        // return $storein;
        return redirect()->route('storein.storeinItemCreate', ['id' => $storein->id]);
    }

    public function saveStorein(Request $request)
    {
        $validator = $request->validate([
            'type' => 'required',
            'ppno' => [
                Rule::requiredIf(function () use ($request) {
                     return in_array(StoreinType::find($request->type)->name, ['import']);
                }),
            ],
            'srno' => [
                Rule::requiredIf(function () use ($request) {
                    return in_array(StoreinType::find($request->type)->name, ['import','local']);
               }),
            ],
            'billno' => [
                Rule::requiredIf(function () use ($request) {
                    return in_array(StoreinType::find($request->type)->name, ['sapat','local']);
               }),
            ],
            'purchaseDate' => 'required|date',
            'supplier' => 'required',
        ]);

        $storein = new Storein();
        $storein->sr_no = $request->srno ? $request->srno : null;
        $storein->bill_no = $request->billno ? $request->billno : null;
        $storein->pp_no = $request->ppno ? $request->ppno : null;
        $storein->purchase_date = $request->purchaseDate;
        $storein->storein_type_id = $request->type;
        $storein->supplier_id = $request->supplier;
        $storein->save();
        return redirect()->route('storein.storeinItemCreate', ['id' => $storein->id]);
    }
    public function saveImg($request)
    {
        $image = $request->file('purchaseImage');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/storeinImages', $imageName);
        $imagePath = 'storage/storeinImages/' . $imageName;
        return $imagePath;
    }
    public function saveEntireStorein(Request $request, $storein_id)
    {
        try{
            DB::beginTransaction();
        $storein = Storein::with('storeinItems')
            ->withSum('storeinItems', 'total_amount')
            ->find($storein_id);

        $storein->sub_total = $storein->storein_items_sum_total_amount;
        $grandTotal = $storein->sub_total;
        //checking weather discount percentage has came or amount
        if ($request->discount_percent && $request->discount_percent != null && $request->discount_percent != 0) {
            $storein->discount_percent = $request->discount_percent;
            $storein->total_discount =  ($request->discount_percent * $storein->sub_total) / 100;
            $grandTotal -= $storein->total_discount;
        } else {
            $storein->total_discount =  $request->discount_amount ? $request->discount_amount : 0;
            $grandTotal -= $storein->total_discount;
        }
        $chargeArray = [];
        if ($request->chargeName && count($request->chargeName) > 0) {
            for ($i = 0; $i < count($request->chargeName); $i++) {
                $chargeArr = explode('-', $request->chargeName[$i]);
                $charge_id = $chargeArr[0];
                $charge_name = $chargeArr[1];
                $charge_amount = $request->chargeAmount[$i] ? $request->chargeAmount[$i] : '0';
                $charge_operator = $request->chargeOperator[$i];
                $charge_total = $request->chargeTotal[$i] ? $request->chargeTotal[$i] : '0';

                $chargeArray[] = [
                    'charge_id' => $charge_id,
                    'charge_name' => $charge_name,
                    'charge_amount' => $charge_amount,
                    'charge_operator' => $charge_operator,
                    'charge_total' => $charge_total
                ];
                $grandTotal += $charge_total;
            }
        }
        $storein->grand_total = $grandTotal;
        $storein->extra_charges = json_encode($chargeArray);
        // save image
        if ($request->hasFile('purchaseImage')) {
            $imgPath = self::saveImg($request);
            $storein->image_path = $imgPath;
        }
        $storein->note = $request->note;
        $storein->status = $request->status;
        // storein status change
        $storein->save();
        DB::commit();
        return redirect()->route('storein.index')->withSuccess('Storein entirely created successfully!');
        }catch(Exception $e){
            DB::rollback();
            return 'something went wrong on saving storein entirely';

        }
    }


    public function getTaxPercentage($tax_slug)
    {
        // return $tax_id;
        return Tax::where('slug', $tax_slug)->first();
    }
    public function invoiceView($storein_id)
    {

        $storein = Storein::with(['supplier', 'storeinType', 'storeinItems', 'storeinItems.itemsOfStorein'])->find($storein_id);
//return $storein;
        $charges = $storein->extra_charges ? json_decode($storein->extra_charges) : [];
        // return $charges;
        return view('admin.storein.viewStorein', compact('storein', 'charges'));
    }
    // not in use
    public function getcategoryItems($category_id)
    {
        return ItemsOfStorein::with('storeinCategory')->where('category_id', $category_id)->get();
    }
    public function EditItemStoreData(Request $request)
    {
        try{
            return $request;
            DB::beginTransaction();
            $id = $request->storein_item_id;
            $storeinItem = StoreinItem::find($id);
            $old_item_quantity = $storeinItem->quantity;
            $old_item_total_amount = $storeinItem->total_amount;
            $old_item_id = $storeinItem->storein_item_id;

            $new_total_amount = $request->quantity * $request->unit_price;

            $storeinItem->storein_category_id = $request->category_id;
            $storeinItem->storein_item_id = $request->product_id;
            $storeinItem->quantity = $request->quantity;
            $storeinItem->price = $request->unit_price;
            $storeinItem->unit_id = $request->unit_id;

            // if ($request->discount_percentage == 0) {
            //     $storeinItem->discount_percentage = 0;

            //     $storeinItem->discount_amount = 0;
            //     $storeinItem->total_amount = $storeinItem->price * $storeinItem->quantity;
            // } else {
                // $storeinItem->discount_percentage = $request->discount_percentage;
                // $netTotal = $storeinItem->quantity * $storeinItem->price;
                // $storeinItem->discount_amount = ($netTotal *  $storeinItem->discount_percentage) / 100;
                $storeinItem->total_amount = $storeinItem->quantity * $storeinItem->price;
            // }
            $storeinItem->save();
            // return $storeinItem;
            Stock::updateStock($old_item_id, $old_item_total_amount, $old_item_quantity, $request, $new_total_amount);
            DB::commit();
            return self::getStoreInById($storeinItem->id);
        }catch(Exception $ex){
            DB::rollBack();
            return 'something went wrong';
        }

    }

    public function getEditItemData($storeinItem_id)
    {
        // return $storeinItem_id;
        $storeinItem = StoreinItem::with(['storeinCategory', 'itemsOfStorein', 'unit'])->where('id', $storeinItem_id)->first();
        return $storeinItem;
    }

    public function storeinItemDelete($id)
    {
        try{

        DB::beginTransaction();
        $storeinItem = StoreinItem::find($id);
        Stock::deleteStock($storeinItem->storein_item_id, $storeinItem->total_amount, $storeinItem->quantity);
        $storeinItem->delete();

        // checking if items is empty then set grand total to zero
        $storeIn = Storein::with('storeinItems')->find($storeinItem->storein_id);
        if (!$storeIn->storeinItems || count($storeIn->storeinItems) <= 0) {
            $storeIn->grand_total = 0;
            $storeIn->sub_total = 0;
            $storeIn->total_discount = 0;
            $storeIn->save();
        }
        DB::commit();
        return true;
    }catch(Exception $ex){
        DB::rollback();
        return'Something went wrong in deleting item';
    }
    }

    public function storeinDelete($id)
    {
        try{
         DB::beginTransaction();
            $storein= Storein::find($id);
            $storeinItems = $storein->storeinItems;
            if( $storeinItems && count($storeinItems) != 0){

                foreach($storeinItems as $storein_item){
                    $stock=Stock::where('item_id',$storein_item->storein_item_id)->first();
                    $stock->quantity -=$storein_item->quantity;
                    if($stock->quantity <= 0){
                        $stock->delete();
                    }
                    else{
                        $stock->save();
                    }
                }
            }
           $storein->delete();
            DB::commit();
        }catch(Exception $e){
                DB::rollBack();
                return $e;
        }

    }
    // m
    public function saveStoreinItems(Request $request, $id)
    {
try{
        DB::beginTransaction();
        $validator = $request->validate([
            'category_id'  => 'required',
            'item_id'   => 'required',
            'units'         => 'required',
            'size_id'       => 'required',
            'quantity'      => 'required|numeric',
            'unit_price'   => 'required|numeric',
        ]);
        $totalAmt = ($request->quantity * $request->unit_price);
        // return $request;
        $storeinItem = new StoreinItem();
        $storeinItem->storein_category_id = $request->category_id;
        $storeinItem->storein_item_id = $request->item_id;
        $storeinItem->quantity = $request->quantity;
        $storeinItem->unit_id = $request->units;
        // recent added
        $storeinItem->size_id = $request->size_id;
        $storeinItem->storein_id = $id;
        $storeinItem->price = $request->unit_price;
        $storeinItem->total_amount = $totalAmt;
        $storeinItem->save();

        Stock::createStock($storeinItem);
        DB::commit();
        return self::getStoreInById($storeinItem->id);

        }catch(Exception $ex)
        {
            DB::rollback();
            return 'some thing went wrong in store in item';
        }
    }
    // getItems department name
    public function getItemsDepartment($product_id)
    {
        return ItemsOfStorein::with('storeinDepartment')->find($product_id);
    }
    // layout
    public function storeinItemCreate($id)
    {
        $storein = Storein::with('tax')->find($id);
        $categories = StoreinCategory::where('status', 1)->get();
        $items = ItemsOfStorein::with('storeinCategory')->get();
        $sizes = Size::where('status', 1)->get();
        $taxes = Tax::all();
        $units = Unit::where('status', 1)->get();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $charges = Charges::all();
        $departments = StoreinDepartment::where('status', 'active')->get();
        $addedCharges = $storein->extra_charges ? json_decode($storein->extra_charges) : [];
        $storeinItems = StoreinItem::where('storein_id', $storein->id)->with(['storeinCategory', 'itemsOfStorein', 'unit'])->get();
        return view('admin.storein.createItems', compact('storein', 'addedCharges', 'departments', 'categories', 'suppliers', 'items', 'units', 'charges', 'sizes', 'storeinItems', 'taxes'));
    }
    public function storeInItemsRetrive($storein_id)
    {
        return Storein::with(['storeinItems.storeinCategory', 'storeinItems.itemsOfStorein', 'storeinItems.unit', 'storeinItems.size'])->find($storein_id);
    }
    public function getStoreInById($id)
    {
        return StoreinItem::with(['storeinCategory', 'itemsOfStorein', 'unit', 'size'])->find($id);
    }



    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = Storein::latest()->get();
        // share data to view
        view()->share('purchases', $data);
        $pdf = PDF::loadView('admin.pdf.purchases', $data->all());
        // download PDF file with download method
        return $pdf->download('purchases-list.pdf');
    }
}
