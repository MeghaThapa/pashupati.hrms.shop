<?php

namespace App\Http\Controllers;


use App\Models\FinishedProduct;
use App\Models\Purchase;
use App\Models\Showroom;
use App\Models\TransferredProduct;
use Illuminate\Http\Request;
use PDF;

class TransferredProductController extends Controller
{
    /**
     * Display a listing of the transferred products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = TransferredProduct::query();
        if (request('term')) {
            $term = request('term');
            $query->whereDate('transferred_date', $term)
                ->orWhere('transferred_code', 'Like', '%' . $term . '%')
                ->orWhereHas('finishedProduct', function ($newQuery) use ($term) {
                    $newQuery->where('finished_code', 'LIKE', '%' . $term . '%');
                })
                ->orWhereHas('showroom', function ($newQuery) use ($term) {
                    $newQuery->where('name', 'LIKE', '%' . $term . '%');
                });
        }
        $products = $query->with('finishedProduct', 'showroom')->paginate(15);
        return view('admin.transferred.index', compact('products'));
    }

    /**
     * Show the form for creating a new transferred product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $finisedProducts = FinishedProduct::where('status', 1)->latest()->get();
        $showrooms = Showroom::where('status', 1)->latest()->get();
        return view('admin.transferred.create', compact('finisedProducts', 'showrooms'));
    }

    /**
     * Store a newly created transferred product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'finishedProduct' => 'required|integer',
            "transferredQuantities"    => "required|array|min:1",
            "transferredQuantities.*"  => "required|integer|min:0",
            "showroom"    => "required|integer",
            "cartoonNumber"    => "required|string|max:60",
            'transferredDate' => 'required|date|date_format:Y-m-d',
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);


        // generate processing code
        $latestTransferred = TransferredProduct::latest()->first();
        $transferredCode = isset($latestTransferred->transferred_code) ? $latestTransferred->transferred_code + 1 :  1;

        // serialize product quantities
        $quantities = implode(", ", $request->transferredQuantities);

        //move transferred image in storage
        $transferredImage = '';
        if (isset($request->attchedPic)) {
            $imagePath = 'img/transferred';
            $transferredImage = $this->uploadImage($imagePath, $request->attchedPic);
        }

        // store transferred product
        $product = TransferredProduct::create([
            'finished_id' => $request->finishedProduct,
            'showroom_id' => $request->showroom,
            'transferred_code' => $transferredCode,
            'transferred_date' => $request->transferredDate,
            'cartoon_number' => $request->cartoonNumber,
            'transferred_quantities' => $quantities,
            'transferred_image' => $transferredImage,
            'note' => clean($request->note),
            'status' => $request->status,
        ]);
        return redirect()->route('transferred.index')->withSuccess('Transferred product created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = TransferredProduct::findOrFail($id);
        $sizes = explode(', ',  $product->finishedProduct->sizes);
        $quantities = explode(', ',  $product->finishedProduct->quantities);
        $transQuantities = explode(', ',  $product->transferred_quantities);
        $rmQuantites = explode(', ',  $this->transferredSizes($product->finishedProduct));
        return view('admin.transferred.show', compact('product', 'sizes', 'quantities', 'transQuantities', 'rmQuantites'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchases = Purchase::where('status', 1)->latest()->get();
        $product = TransferredProduct::findOrFail($id);
        $showrooms = Showroom::where('status', 1)->latest()->get();
        $transQuantities = explode(', ', $this->transferredSizes(FinishedProduct::findOrFail($product->finished_id)));

        //dd($product);

        return view('admin.transferred.edit', compact('product', 'purchases', 'showrooms', 'transQuantities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = TransferredProduct::findOrFail($id);

        // validate form
        $validator = $request->validate([
            "transferredQuantities"     => "required|array|min:1",
            "transferredQuantities.*"   => "required|integer|min:0",
            "showroom"                  => "required|integer",
            "cartoonNumber"             => "required|string|max:60",
            'transferredDate'           => 'required|date|date_format:Y-m-d',
            'note'                      => 'nullable|string|max:255',
            'attchedPic'                => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // serialize product quantities
        $quantities = implode(", ", $request->transferredQuantities);

        // remove current image and upload new image
        $transferredImage = $product->transferred_image;
        if (isset($request->attchedPic)) {
            if (isset($transferredImage)) {
                $this->deleteImage('img/transferred/' . $transferredImage);
            }
            $imagePath = 'img/transferred';
            $transferredImage = $this->uploadImage($imagePath, $request->attchedPic);
        }

        $product->update([
            'showroom_id'               => $request->showroom,
            'transferred_date'          => $request->transferredDate,
            'cartoon_number'            => $request->cartoonNumber,
            'transferred_quantities'    => $quantities,
            'transferred_image'         => $transferredImage,
            'note'                      => clean($request->note),
            'status'                    => $request->status,
        ]);

        return redirect()->route('transferred.index')->withSuccess('Transferred product updated successfully!');
    }

    /**
     * Remove the specified transferred product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = TransferredProduct::findOrFail($id);

        // remove image from storage
        $transferredImage = $product->transferred_image;
        if (isset($transferredImage)) {
            $this->deleteImage('img/transferred/' . $transferredImage);
        }

        // delete transferred product
        $product->delete();
        return redirect()->route('transferred.index')->withSuccess('Transferred product deleted successfully!');
    }


    /**
     * Change the specified transferred product status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $product = TransferredProduct::findOrFail($id);

        // change current status of the transferred product
        if ($product->isActive()) {
            $product->update([
                'status' => 0
            ]);
        } else {
            $product->update([
                'status' => 1
            ]);
        }
        return redirect()->route('transferred.index')->withSuccess('Transferred product status changed successfully!');
    }


    /**
     * get the finished product sizes and quantities for slected product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finishedProductSizes(Request $request)
    {
        $finishedProduct = FinishedProduct::findOrFail($request->id);
        // calculate total transferred quantities
        $transQty = $this->transferredSizes($finishedProduct);
        return [
            'finishedProduct' => $finishedProduct,
            'transferredQty' => $transQty
        ];
    }


    // get all the transferred quantity
    public function transferredSizes($product)
    {
        // calculate total transferred quantities
        $transferredSizes = $product->transferredProducts()->select('transferred_quantities')->get();
        $newArray = array(array());
        for ($i = 0; $i < count($transferredSizes); $i++) {
            array_push($newArray, explode(', ', $transferredSizes[$i]->transferred_quantities));
        }
        $newArray = array_filter(array_map('array_filter', $newArray));
        $newStr = '';

        $rows = count($newArray);
        if ($rows > 0) {
            $cols = count($newArray[1]);
            for ($i = 0; $i < $cols; $i++) {
                $sumQty = 0;
                for ($j = 0; $j < $rows; $j++) {
                    if (isset($newArray[$j + 1][$i])) {
                        $sumQty = $sumQty + $newArray[$j + 1][$i];
                    }
                }
                if ($i == $cols - 1) {
                    $newStr .= $sumQty;
                } else {
                    $newStr .= $sumQty . ', ';
                }
            }
        }
        return $newStr;
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = TransferredProduct::with('finishedProduct', 'showroom')->latest()->get();
        // share data to view
        view()->share('products', $data);
        $pdf = PDF::loadView('admin.pdf.transferred', $data->all());
        // download PDF file with download method
        return $pdf->download('transferred-products-list.pdf');
    }
}