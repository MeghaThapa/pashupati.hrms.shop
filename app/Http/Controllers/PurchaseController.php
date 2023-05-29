<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use App\Models\Unit;
use DB;
use Illuminate\Http\Request;
use PDF;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Purchase::query();
        if (request('term')) {
            $term = request('term');
            $query->whereDate('purchase_date', $term)
                ->orWhere('purchase_code', 'Like', '%' . $term . '%')
                ->orWhereHas('supplier', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%');
                });
            $expenses = $query->orderBy('id', 'DESC')->paginate(15);
        }
        $purchases = $query->with('supplier')->orderBy('id', 'DESC')->paginate(15);
        return view('admin.purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new purchase.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // generate unit name and code in string format
        $units = Unit::where('status', 1)->get();
        $unitCodes = '';
        $unitNames = '';
        foreach ($units as $key => $unit) {
            if (++$key == count($units)) {
                $unitCodes .= $unit->code;
                $unitNames .= $unit->name;
            } else {
                $unitCodes .= $unit->code . ',';
                $unitNames .= $unit->name . ',';
            }
        }
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $methods = PaymentMethod::where('status', 1)->latest()->get();
        return view('admin.purchases.create', compact('suppliers', 'methods', 'units', 'unitNames', 'unitCodes'));
    }

    /**
     * Store a newly created purchase in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'purchaseDate' => 'required|date|date_format:Y-m-d',
            'supplier' => 'required|integer',
            "products"    => "required|array|min:1",
            "products.*"  => "required|string|distinct|min:3|max:50",
            "quantities"    => "required|array|min:1",
            "quantities.*"  => "required|numeric|min:1",
            "units"    => "required|array|min:1",
            "units.*"  => "required|string|min:1",
            "unitPrices"    => "required|array|min:1",
            "unitPrices.*"  => "required|numeric|min:1",
            "discount"  => "nullable|numeric|min:1",
            "transportCost"  => "nullable|numeric|min:1",
            "totalPayment"  => 'required|numeric|min:1|max:{$request->total}',
            "totalDue"  => 'nullable|numeric|min:1|max:{$request->total}',
            'note' => 'nullable|string|max:255',
            'purchaseImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // generate purcahse code
        $latestPurchase = Purchase::latest()->first();
        $purchaseCode = isset($latestPurchase->purchase_code) ? $latestPurchase->purchase_code + 1 :  1;


        // calculate due
        $due = $request->total - $request->totalPayment;
        // store purchase image
        $purchaseImage = '';
        if (isset($request->purchaseImage)) {
            $imagePath = 'img/purchases';
            $purchaseImage = $this->uploadImage($imagePath, $request->purchaseImage);
        }

        // store purchase
        $purchase = Purchase::create([
            'purchase_date' => $request->purchaseDate,
            'supplier_id' => $request->supplier,
            'purchase_code' => $purchaseCode,
            'sub_total' => $request->subTotal,
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

        // store purchase products
        for ($i = 0; $i < count($request->products); $i++) {
            PurchaseProduct::create([
                'purchase_id' => $purchase->id,
                'product_name' => $request->products[$i],
                'quantity' => $request->quantities[$i],
                'unit' => $request->units[$i],
                'unit_price' => $request->unitPrices[$i],
                'discount' => $request->discounts[$i],
                'total' => $request->singleTotal[$i]
            ]);
        }
        return redirect()->route('purchases.index')->withSuccess('Purchase created successfully!');
    }

    /**
     * Display the specified pruchase.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $purchase = Purchase::where('purchase_code', $code)->with('supplier', 'purchaseReturn', 'purchaseDamage', 'processingProducts')->first();
        $steps = DB::table('processing_product_staff')->select('processing_step_id')->distinct()->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')->select('processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id')->select('processing_steps.name as step_name', 'processing_steps.id as step_id')->get();
        $processingProducts = $purchase->processingProducts()->latest()->paginate(10);
        // $transferredProducts = $purchase->transferredProducts()->latest()->paginate(10);
        return view('admin.purchases.show', compact('purchase', 'steps', 'processingProducts'));
    }

    /**
     * Show the form for editing the specified purchase.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        // generate unit name and code in string format
        $units = Unit::where('status', 1)->get();
        $unitCodes = '';
        $unitNames = '';
        foreach ($units as $key => $unit) {
            if (++$key == count($units)) {
                $unitCodes .= $unit->code;
                $unitNames .= $unit->name;
            } else {
                $unitCodes .= $unit->code . ',';
                $unitNames .= $unit->name . ',';
            }
        }
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $purchase = Purchase::where('purchase_code', $code)->first();
        $methods = PaymentMethod::where('status', 1)->latest()->get();
        $units = Unit::where('status', 1)->get();
        return view('admin.purchases.edit', compact('suppliers', 'purchase', 'methods', 'units', 'unitNames', 'unitCodes'));
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

        $purchase = Purchase::where('purchase_code', $code)->first();

        // validate form
        $validator = $request->validate([
            'purchaseDate' => 'required|date|date_format:Y-m-d',
            'supplier' => 'required|integer',
            "products"    => "required|array|min:'count($purchase->purchaseProducts)'",
            "products.*"  => "required|string|distinct|min:3|max:60",
            "quantities"    => "required|array|min:'count($purchase->purchaseProducts)'",
            "quantities.*"  => "required|numeric|min:1",
            "units"    => "required|array|min:1",
            "units.*"  => "required|string|min:'count($purchase->purchaseProducts)'",
            "unitPrices"    => "required|array|min:1",
            "unitPrices.*"  => "required|numeric|min:1",
            "discount"  => "nullable|numeric|min:1",
            "transportCost"  => "nullable|numeric|min:1",
            "totalPayment"  => 'required|numeric|min:1|max:{$request->total}',
            "totalDue"  => 'nullable|numeric|min:0|max:{$request->total}',
            'note' => 'nullable|string|max:255',
            'purchaseImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

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
            'purchase_code' => $purchase->purchase_code,
            'sub_total' => $request->subTotal,
            'discount' => $request->totalDiscount,
            'trasnport' => $request->transportCost,
            'total' => $request->total,
            'total_paid' => $request->totalPayment,
            'total_due' => $due,
            'payment_type' => $request->paymentType,
            'purchase_image' => $purchaseImage,
            'note' => clean($request->note),
            'status' => $request->status
        ]);

        // update purchase products
        $pro = PurchaseProduct::where('purchase_id', $purchase->id)->get();
        for ($i = 0; $i < count($request->products); $i++) {
            if ($i < $pro->count()) {
                // udpate current product
                $pro->values()->get($i)->update([
                    'product_name' => $request->products[$i],
                    'quantity' => $request->quantities[$i],
                    'unit' => $request->units[$i],
                    'unit_price' => $request->unitPrices[$i],
                    'discount' => $request->discounts[$i],
                    'total' => $request->singleTotal[$i]
                ]);
            } else {
                // store new product
                PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_name' => $request->products[$i],
                    'quantity' => $request->quantities[$i],
                    'unit' => $request->units[$i],
                    'unit_price' => $request->unitPrices[$i],
                    'discount' => $request->discounts[$i],
                    'total' => $request->singleTotal[$i]
                ]);
            }
        }
        return redirect()->route('purchases.index')->withSuccess('Purchase updated successfully!');
    }

    /**
     * Remove the specified purchase from storage.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $purchase = Purchase::where('purchase_code', $code)->first();

        // delete purchase image from storage
        $purchaseImage = $purchase->purchase_image;
        if (isset($purchaseImage)) {
            $this->deleteImage('img/purchases/' . $purchaseImage);
        }

        // delete purchase
        $purchase->delete();
        return redirect()->route('purchases.index')->withSuccess('Purchase deleted successfully!');
    }


    // activate purcahse
    public function changeStatus($code)
    {
        $product = Purchase::where('purchase_code', $code)->first();
        if ($product->status == 1) {
            $product->update([
                'status' => 0
            ]);
        } else {
            $product->update([
                'status' => 1
            ]);
        }
        return redirect()->route('purchases.index')->withSuccess('Purchase status changed successfully!');
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
        return view('admin.purchases.invoice', compact('purchase'));
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
        $data = Purchase::latest()->get();
        // share data to view
        view()->share('purchases', $data);
        $pdf = PDF::loadView('admin.pdf.purchases', $data->all());
        // download PDF file with download method
        return $pdf->download('purchases-list.pdf');
    }
}
