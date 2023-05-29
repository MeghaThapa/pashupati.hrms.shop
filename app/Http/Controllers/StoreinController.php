<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Charges;
use App\Models\Department;
use App\Models\SubCategory;
use App\Models\Setupstorein;
use App\Models\Setupstoreout;
use App\Models\Storein;
use App\Models\Items;
use App\Models\Size;
use App\Models\Stock;
use App\Models\StoreinItem;
use App\Models\Tax;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Arr;


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
        // return $storeinDatas;
        // if (request('term')) {
        //     $term = request('term');
        //     $query->whereDate('purchase_date', $term)
        //         ->orWhere('purchase_code', 'Like', '%' . $term . '%')
        //         ->orWhereHas('supplier', function ($newQuery) use ($term) {
        //             $newQuery->where('name', 'LIKE', '%' . $term . '%');
        //         });
        //     $expenses = $query->orderBy('id', 'DESC')->paginate(15);
        // }
        // $storein = $query->with('supplier')->orderBy('id', 'DESC')->paginate(15);
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
        $categoryName = Category::find($id)->get('name');
        return $categoryName;
    }
    public function createStorein()
    {
        $storeintype = Setupstorein::all();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $categories = Category::where('status', 1)->get();
        $storeinData = null;
        return view('admin.storein.storeinCreate', compact('storeintype', 'suppliers', 'categories', 'storeinData'));
    }
    public function editStorein($storein_id)
    {
        // return $storein_id;
        $storeintype = Setupstorein::all();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $storeinData = Storein::with('storeintype', 'supplier')->find($storein_id);
        // return $storeinData;
        return view('admin.storein.storeinCreate', compact('storeintype', 'suppliers', 'storeinData'));
    }

    public function editStoreinItems()
    {
    }
    public function updateStorein(Request $request, $storein_id)
    {
        // return $request;
        $validator = $request->validate([
            'type' => 'required',
            'ppno' => 'required_if:type,1',
            'srno' => 'required_if:type,1,type,2',
            'billno' => 'required_if:type,2,type,3',
            'purchaseDate' => 'required|date',
            'supplier' => 'required',
        ]);
        // , [
        //     'srno.required' => 'The sr no field is required.',
        //     'billno.required' => 'The bill no field is required.',
        //     'ppno.required' => 'The ppno field is required.',
        //     'purchaseDate.required' => 'The purchaseDate field is required.',
        //     'purchaseDate.date' => 'The purchaseDateh must consists of date.',
        //     'type.required' => 'The storein type field is required.',
        //     'supplier.required' => 'The supplier field is required.',

        // ]);
        $storein = Storein::find($storein_id);
        $storein->sr_no = $request->srno;
        $storein->bill_no = $request->billno;
        $storein->pp_no = $request->ppno;
        $storein->purchase_date = $request->purchaseDate;
        $storein->storein_id = $request->type;
        $storein->supplier_id = $request->supplier;
        $storein->save();
        // return $storein;
        return redirect()->route('storein.storeinItemCreate', ['id' => $storein->id]);
    }

    public function saveStorein(Request $request)
    {
        // return $request;
        $validator = $request->validate([
            'type' => 'required',
            'ppno' => 'required_if:type,1',
            'srno' => 'required_if:type,1,type,2',
            'billno' => 'required_if:type,2,type,3',
            'purchaseDate' => 'required|date',
            'supplier' => 'required',
        ]);

        $storein = new Storein();
        $storein->sr_no = $request->srno ? $request->srno : null;
        $storein->bill_no = $request->billno ? $request->billno : null;
        $storein->pp_no = $request->ppno ? $request->ppno : null;
        $storein->purchase_date = $request->purchaseDate;
        $storein->storein_id = $request->type;
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
        // return $request;

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
        $storein->storein_status = 'complete';
        $storein->save();
        // megha
        $storeinItems = $storein->storeinItems;
        // return $storeinItems;
        return redirect()->route('storein.index')->withSuccess('Storein entirely created successfully!');
    }


    public function getTaxPercentage($tax_slug)
    {
        // return $tax_id;
        return Tax::where('slug', $tax_slug)->first();
    }
    public function invoiceView($storein_id)
    {

        $storein = Storein::with(['supplier', 'storeintype', 'storeinItems', 'storeinItems.item', 'storeintype'])->find($storein_id);
        $charges = $storein->extra_charges ? json_decode($storein->extra_charges) : [];
        // return $charges;
        return view('admin.storein.viewStorein', compact('storein', 'charges'));
    }
    // not in use
    public function getcategoryItems($category_id)
    {
        $items = Items::with('category')->where('category_id', $category_id)->get();
        return $items;
    }
    public function EditItemStoreData(Request $request)
    {
        $id = $request->storein_item_id;
        $storeinItem = StoreinItem::find($id);
        // return $request;
        $old_item_quantity = $storeinItem->quantity;
        $old_item_total_amount = $storeinItem->total_amount;
        $old_item_id = $storeinItem->item_id;
        $new_total_amount = $request->quantity * $request->unit_price;


        $storeinItem->category_id = $request->category_id;
        $storeinItem->item_id = $request->product_id;
        $storeinItem->quantity = $request->quantity;
        $storeinItem->price = $request->unit_price;
        $storeinItem->unit_id = $request->unit_id;

        // $storeinItem->discount_percentage = $request->discount_percent;
        if ($request->discount_percentage == 0) {
            // return $request->discount_percentage;
            $storeinItem->discount_percentage = 0;

            $storeinItem->discount_amount = 0;
            $storeinItem->total_amount = $storeinItem->price * $storeinItem->quantity;
        } else {
            $storeinItem->discount_percentage = $request->discount_percentage;
            $netTotal = $storeinItem->quantity * $storeinItem->price;
            $storeinItem->discount_amount = ($netTotal *  $storeinItem->discount_percentage) / 100;
            $storeinItem->total_amount = $netTotal - $storeinItem->discount_amount;
        }
        $storeinItem->save();
        // return $storeinItem;
        Stock::updateStock($old_item_id, $old_item_total_amount, $old_item_quantity, $request, $new_total_amount);

        return self::getStoreInById($storeinItem->id);
    }

    public function getEditItemData($storeinItem_id)
    {
        // return $storeinItem_id;
        $storeinItem = StoreinItem::with(['category', 'item', 'unit'])->where('id', $storeinItem_id)->first();
        return $storeinItem;
    }

    public function storeinItemDelete($id)
    {
        $storeinItem = StoreinItem::find($id);
        Stock::deleteStock($storeinItem->item_id, $storeinItem->total_amount, $storeinItem->quantity);
        $storeinItem->delete();

        // checking if items is empty then set grand total to zero
        $storeIn = Storein::with('storeinItems')->find($storeinItem->storein_id);
        if (!$storeIn->storeinItems || count($storeIn->storeinItems) <= 0) {
            $storeIn->grand_total = 0;
            $storeIn->sub_total = 0;
            $storeIn->total_discount = 0;
            $storeIn->save();
            return true;
        }
        return true;
    }

    public function storeinDelete($id)
    {
        // return Storein::find($id);
        return Storein::find($id)->delete();
    }
    // m
    public function saveStoreinItems(Request $request, $id)
    {
        // $discount_per = ($request->discount) ? $request->discount : 0;
        // $discountAmt = ($request->quantity * $request->unit_price * $discount_per) / 100;
        $validator = $request->validate([
            'categoryName'  => 'required',
            'productName'   => 'required',
            'units'         => 'required',
            'size_id'       => 'required',
            'quantity'      => 'required|numeric',
            'unit_price'   => 'required|numeric',
        ]);
        $totalAmt = ($request->quantity * $request->unit_price);
        // return $request;
        $storeinItem = new StoreinItem();
        $storeinItem->category_id = $request->categoryName;
        $storeinItem->item_id = $request->productName;
        $storeinItem->quantity = $request->quantity;
        $storeinItem->unit_id = $request->units;
        // recent added
        $storeinItem->size_id = $request->size_id;
        $storeinItem->storein_id = $id;
        $storeinItem->price = $request->unit_price;
        // $storeinItem->discount_percentage = $discount_per;
        // $storeinItem->discount_amount = $discountAmt;
        $storeinItem->total_amount = $totalAmt;
        $storeinItem->save();

        Stock::createStock($storeinItem->id);

        return self::getStoreInById($storeinItem->id);
    }
    // getItems department name
    public function getItemsDepartment($product_id)
    {
        $department = Items::with('department')->find($product_id);
        return $department;
    }
    // layout
    public function storeinItemCreate($id)
    {

        $storein = Storein::with(['tax'])->find($id);

        $categories = Category::where('status', 1)->get();
        $items = Items::with('category')->get();
        $sizes = Size::where('status', 1)->get();
        $taxes = Tax::all();
        $units = Unit::where('status', 1)->get();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $charges = Charges::all();
        $departments = Department::where('status', 'active')->get();
        // return $departments;
        $addedCharges = $storein->extra_charges ? json_decode($storein->extra_charges) : [];
        //  return $addedCharges;
        // For Table items
        $storeinItems = StoreinItem::where('storein_id', $storein->id)->with(['category', 'item', 'unit'])->get();

        return view('admin.storein.createItems', compact('storein', 'addedCharges', 'departments', 'categories', 'suppliers', 'items', 'units', 'charges', 'sizes', 'storeinItems', 'taxes'));
    }
    public function storeInItemsRetrive($storein_id)
    {
        $storeinDatas = Storein::with(['storeinItems.category', 'storeinItems.item', 'storeinItems.unit', 'storeinItems.size'])->find($storein_id);
        return $storeinDatas;
    }
    public function getStoreInById($id)
    {
        return StoreinItem::with(['category', 'item', 'unit', 'size'])->find($id);
    }



    /**
     * Show the form for editing the specified purchase.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {

        $purchase = Storein::where('purchase_code', $code)->first();

        // validate form
        // $validator = $request->validate([
        //     'purchaseDate' => 'required|date|date_format:Y-m-d',
        //     'supplier' => 'required|integer',
        //     "products"    => "required|array|min:'count($purchase->purchaseProducts)'",
        //     "products.*"  => "required|string|distinct|min:3|max:60",
        //     "quantities"    => "required|array|min:'count($purchase->purchaseProducts)'",
        //     "quantities.*"  => "required|numeric|min:1",
        //     "units"    => "required|array|min:1",
        //     "units.*"  => "required|string|min:'count($purchase->purchaseProducts)'",
        //     "unitPrices"    => "required|array|min:1",
        //     "unitPrices.*"  => "required|numeric|min:1",
        //     "discount"  => "nullable|numeric|min:1",
        //     "transportCost"  => "nullable|numeric|min:1",
        //     "totalPayment"  => 'required|numeric|min:1|max:{$request->total}',
        //     "totalDue"  => 'nullable|numeric|min:0|max:{$request->total}',
        //     'note' => 'nullable|string|max:255',
        //     'purchaseImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        // ]);

        // delete purchase image and upload new image
        $purchaseImage = $purchase->purchase_image;
        if (isset($request->purchaseImage)) {
            $imagePath = 'img/purchases';
            if (isset($purchaseImage)) {
                $this->deleteImage('img/purchases/' . $purchaseImage);
            }
            $purchaseImage = $this->uploadImage($imagePath, $request->purchaseImage);
        }

        // calculate due
        $due = $request->total - $request->totalPayment;

        // update purchase
        $purchaseUpdate = $purchase->update([
            'purchase_date' => $request->purchaseDate,
            'supplier_id' => $request->supplier,
            'sr_no' => $request->srno,
            'bill_no' => $request->billno,
            'pp_no' => $request->ppno,
            'size' => $request->size,
            'tax' => $request->tax,
            // 'infrom' => $request->infrom,
            'purchase_code' => $purchase->purchase_code,
            'sub_total' => $request->subTotal,
            'category_id' => $request->categoryName,
            // 'subcategory_id' => $request->subcategoryName,
            'type' => $request->type,
            'item' => $request->ProductName,
            'discount' => $request->totalDiscount,
            'trasnport' => $request->transportCost,
            'total' => $request->total,
            'total_paid' => $request->totalPayment,
            'total_due' => $due,
            'payment_type' => $request->paymentMethod,
            'purchase_image' => $purchaseImage,
            'note' => clean($request->note),
            'status' => $request->status
        ]);

        // update purchase products
        $pro = PurchaseProduct::where('purchase_id', $purchase->id)->get();
        // for ($i = 0; $i < count($request->products); $i++) {
        //     if ($i < $pro->count()) {
        //         // udpate current product
        //         $pro->values()->get($i)->update([
        //             'product_name' => $request->products[$i],
        //             'quantity' => $request->quantities[$i],
        //             'unit' => $request->units[$i],
        //             'unit_price' => $request->unitPrices[$i],
        //             'discount' => $request->discounts[$i],
        //             'total' => $request->singleTotal[$i]
        //         ]);
        //     } else {
        //         // store new product
        //         PurchaseProduct::create([
        //             'purchase_id' => $purchase->id,
        //             'product_name' => $request->products[$i],
        //             'quantity' => $request->quantities[$i],
        //             'unit' => $request->units[$i],
        //             'unit_price' => $request->unitPrices[$i],
        //             'discount' => $request->discounts[$i],
        //             'total' => $request->singleTotal[$i]
        //         ]);
        //     }
        // }
        return redirect()->route('storein.index')->withSuccess('Storein updated successfully!');
    }

    /**
     * Remove the specified purchase from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $purchase = Storein::where('purchase_code', $code)->first();

        // delete purchase image from storage
        $purchaseImage = $purchase->purchase_image;
        if (isset($purchaseImage)) {
            $this->deleteImage('img/purchases/' . $purchaseImage);
        }

        // delete purchase
        $purchase->delete();
        return redirect()->route('storein.index')->withSuccess('Storein deleted successfully!');
    }


    // activate purcahse
    public function changeStatus($code)
    {
        $product = Storein::where('purchase_code', $code)->first();
        if ($product->status == 1) {
            $product->update([
                'status' => 0
            ]);
        } else {
            $product->update([
                'status' => 1
            ]);
        }
        return redirect()->route('storein.index')->withSuccess('Purchase status changed successfully!');
    }


    /**
     * Display invoice of the specified pruchase.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function getInvoice($code)
    {
        $purchase = Purchase::where('purchase_code', $code)->first();
        return view('admin.storein.invoice', compact('purchase'));
    }


    /**
     * get the products for specified purchase
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function purchaseProducts(Request $request)
    {
        $purchase = Purchase::where('id', $request->id)->first();
        $products = $purchase->purchaseProducts()->get();
        $newProdcuts = array();
        foreach ($products as $key => $product) {
            $newProdcuts[$key]['product_name'] = $product->product_name;
            $newProdcuts[$key]['quantity'] = $product->quantity;
            $newProdcuts[$key]['available_qty'] = $product->availableQuantity();
            $newProdcuts[$key]['unit'] = $product->unit;
            $newProdcuts[$key++]['unit_price'] = $product->unit_price;
        }
        return $newProdcuts;
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
