<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\ProcessingProduct;
use App\Models\Purchase;
use App\Models\SubCategory;
use App\Models\UsedProduct;
use Illuminate\Http\Request;
use PDF;


class FinishedProductController extends Controller
{
    /**
     * Display a listing of the finished product.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = FinishedProduct::query();
        if (request('term')) {
            $term = request('term');
            $query->whereDate('finished_date', $term)
                ->orWhere('finished_code', 'Like', '%' . $term . '%')
                ->orWhereHas('productType', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%');
                });
        }
        $products = $query->with('processingProduct', 'productType')->latest()->paginate(15);
        return view('admin.finished.index', compact('products'));
    }

    /**
     * Show the form for creating a new finished product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subCategories = SubCategory::where('status', 1)->latest()->get();
        $processingProducts = ProcessingProduct::where('status', 1)->latest()->get();
        return view('admin.finished.create', compact('subCategories', 'processingProducts'));
    }

    /**
     * Store a newly created finished product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'processingProduct' => 'required|integer|min:1',
            "productType"    => "required|integer|min:1",
            "quantities"    => "required|array|min:1",
            "quantities.*"  => "required|integer|min:0",
            "usedQuantities"    => "required|array|min:1",
            "usedQuantities.*"  => "required|numeric",
            'finishDate' => 'nullable|date|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // generate finished code
        $latestFinished = FinishedProduct::latest()->first();
        $finishedCode = isset($latestFinished->finished_code) ? $latestFinished->finished_code + 1 :  1;



        // serialize product quantities
        $subCat = SubCategory::findOrfail($request->productType);
        $quantities = implode(", ", $request->quantities);
        $rejectedQuantities = implode(", ", $request->rejectedQuantities);


        // move finished image to the directory
        $finishedImage = '';
        if (isset($request->attchedPic)) {
            $imagePath = 'img/finished';
            $finishedImage = $this->uploadImage($imagePath, $request->attchedPic);
        }

        // store finished product
        $product = FinishedProduct::create([
            'porcessing_pro_id' => $request->processingProduct,
            'sub_cat_id' => $request->productType,
            'finished_code' => $finishedCode,
            'sizes' => $subCat->sizes,
            'quantities' => $quantities,
            'rejected_quantities' => $rejectedQuantities,
            'finished_date' => $request->finishDate,
            'finished_image' => $finishedImage,
            'note' => clean($request->note),
            'status' => $request->status
        ]);

        // update reamining quantities
        $processingProduct = ProcessingProduct::where('id', $request->processingProduct)->first();
        $purchaseProducts = $processingProduct->purchase->purchaseProducts()->get();
        foreach ($purchaseProducts as $key => $purchaseProduct) {
            UsedProduct::create([
                'finished_id' => $product->id,
                'purchase_pro_id' => $purchaseProduct->id,
                'used_quantity' => $request->usedQuantities[$key++]
            ]);
        }
        return redirect()->route('finished.index')->withSuccess('Finished product created successfully!');
    }

    /**
     * Display the specified finished product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = FinishedProduct::where('slug', $slug)->first();
        $sizes = explode(',', $product->sizes);
        $quantities = explode(', ', $product->quantities);
        $rejectedQuantities = '';
        if (!empty($product->quantities)) {
            $rejectedQuantities = explode(', ', $product->rejected_quantities);
        }
        $transferredProducts = $product->transferredProducts()->latest()->paginate(10);
        return view('admin.finished.show', compact('product', 'sizes', 'quantities', 'transferredProducts', 'rejectedQuantities'));
    }

    /**
     * Show the form for editing the specified finished product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $subCategories = SubCategory::where('status', 1)->latest()->get();
        $processingProducts = ProcessingProduct::where('status', 1)->latest()->get();
        $product = FinishedProduct::where('slug', $slug)->first();
        $sizes = explode(',', $product->sizes);
        $quantities = explode(', ', $product->quantities);
        return view('admin.finished.edit', compact('product', 'subCategories', 'processingProducts', 'quantities', 'sizes'));
    }

    /**
     * Update the specified finished product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $product = FinishedProduct::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'processingProduct' => 'required|integer|min:1',
            "productType"    => "required|integer|min:1",
            "quantities"    => "required|array|min:1",
            "quantities.*"  => "required|integer|min:0",
            "usedQuantities"    => "required|array|min:1",
            "usedQuantities.*"  => "required|numeric",
            'finishDate' => 'nullable|date|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $subCat = SubCategory::findOrfail($request->productType);
        $quantities = implode(", ", $request->quantities);;

        // delete finished image and upload selected image

        $finishedImage = $product->finished_image;
        if (isset($request->attchedPic)) {
            if (isset($finishedImage)) {
                $this->deleteImage('img/finished/' . $finishedImage);
            }
            $imagePath = 'img/finished';
            $finishedImage = $this->uploadImage($imagePath, $request->attchedPic);
        }

        // update finished product
        $product->update([
            'porcessing_pro_id' => $request->processingProduct,
            'sub_cat_id' => $request->productType,
            'sizes' => $subCat->sizes,
            'quantities' => $quantities,
            'finished_date' => $request->finishDate,
            'finished_image' => $finishedImage,
            'note' => clean($request->note),
            'status' => $request->status
        ]);

        // update used quantities
        foreach ($product->usedProducts as $key => $product) {
            $product->update([
                'used_quantity' => $request->usedQuantities[$key++]
            ]);
        }

        return redirect()->route('finished.index')->withSuccess('Finished product updated successfully!');
    }

    /**
     * Remove the specified finished product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $finishedProduct = FinishedProduct::where('slug', $slug)->first();


        // remove image from storage
        $finishedImage = $finishedProduct->finished_image;
        if (isset($finishedImage)) {
            $this->deleteImage('img/finished/' . $finishedImage);
        }
        // delete finished product
        $finishedProduct->delete();
        return redirect()->route('finished.index')->withSuccess('Finished product deleted successfully!');
    }

    /**
     * Change the specified finished product status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $product = FinishedProduct::findOrFail($id);

        // change current status of the processing product
        if ($product->isActive()) {
            $product->update([
                'status' => 0
            ]);
        } else {
            $product->update([
                'status' => 1
            ]);
        }
        return redirect()->route('finished.index')->withSuccess('Finished product status changed successfully!');
    }

    /**
     * get the sizes for slected sub category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function productSizes(Request $request)
    {
        $subCategory = SubCategory::where('id', $request->id)->first();
        $sizes = explode(',', $subCategory->sizes);
        return $sizes;
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


    public function finishedPurchaseProducts(Request $request)
    {
        $processingProduct = ProcessingProduct::where('id', $request->id)->first();
        $products = $processingProduct->purchase->purchaseProducts()->get();
        $newProdcuts = array();
        foreach ($products as $key => $product) {
            $newProdcuts[$key]['product_name'] = $product->product_name;
            $newProdcuts[$key]['quantity'] = $product->quantity;
            $newProdcuts[$key]['available_qty'] = $product->availableQuantity();
            $newProdcuts[$key]['used_qty'] = $product->used_quantity;
            $newProdcuts[$key]['unit'] = $product->unit;
            $newProdcuts[$key++]['unit_price'] = $product->unit_price;
        }
        return $newProdcuts;
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = FinishedProduct::with('processingProduct', 'productType')->latest()->get();
        // share data to view
        view()->share('products', $data);
        $pdf = PDF::loadView('admin.pdf.finished', $data->all());
        // download PDF file with download method
        return $pdf->download('finished-products-list.pdf');
    }
}
