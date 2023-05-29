<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\PurchaseReturn;
use Illuminate\Http\Request;
use PDF;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PurchaseReturn::query();
        if (request('term')) {
            $term = request('term');
            $query->where('return_reason', 'Like', '%' . $term . '%')
                ->orWhere('refund_amount', 'Like', '%' . $term . '%');
        }
        $purchaseReturns = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.return-purchases.index', compact('purchaseReturns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchases = Purchase::where('status', 1)->latest()->get();
        return view('admin.return-purchases.create', compact('purchases'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchase = Purchase::findOrFail($request->purchaseProduct);

        //validate form
        $validator = $request->validate([
            'returnReason' => 'required|string|max:255|min:3',
            'returnDate' => "required|date|after_or_equal:$purchase->purchase_date",
            'refundAmount' => "numeric|max:$purchase->total",
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // store purchase return image
        $attchedPic = '';
        if (isset($request->attchedPic)) {
            $imagePath = 'img/return-purchases/';
            $attchedPic = $this->uploadImage($imagePath, $request->attchedPic);
        }

        $purchaseReturn = PurchaseReturn::create([
            'purchase_id' => $purchase->id,
            'return_reason' => $request->returnReason,
            'return_date' => $request->returnDate,
            'refund_amount' => $request->refundAmount,
            'return_image' => $attchedPic,
            'note' => $request->note,
            'status' => $request->status
        ]);

        // update purchase product quanity
        $purchaseProducts = PurchaseProduct::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseProducts as $key => $product) {
            $product->update([
                'return_quantity' => $request->returnQuantities[$key],
            ]);
        }
        return redirect()->route('purchaseReturn.index')->withSuccess('Purchase return added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $purchaseReturn = PurchaseReturn::where('slug', $slug)->first();
        return view('admin.return-purchases.show', compact('purchaseReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $purchaseReturn = PurchaseReturn::where('slug', $slug)->first();
        $purchases = Purchase::where('status', 1)->latest()->get();
        return view('admin.return-purchases.edit', compact('purchaseReturn', 'purchases'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $purchaseReturn = PurchaseReturn::where('slug', $slug)->first();
        $purchase = Purchase::findOrFail($request->purchaseProduct);
        //validate form
        $validator = $request->validate([
            'returnReason' => 'required|string|max:255|min:3',
            'returnDate' => "required|date|after_or_equal:$purchase->purchase_date",
            'refundAmount' => "numeric|max:$purchase->total",
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);


        // store purchase return image
        $attchedPic = $purchaseReturn->return_image;
        if (isset($request->attchedPic)) {
            $imagePath = 'img/return-purchases/';
            if (isset($attchedPic)) {
                $this->deleteImage($imagePath . $attchedPic);
            }
            $attchedPic = $this->uploadImage($imagePath, $request->attchedPic);
        }

        $purchaseReturn->update([
            'purchase_id' => $purchase->id,
            'return_reason' => $request->returnReason,
            'return_date' => $request->returnDate,
            'refund_amount' => $request->refundAmount,
            'return_image' => $attchedPic,
            'note' => $request->note,
            'status' => $request->status
        ]);

        // update purchase product quanity
        $purchaseProducts = PurchaseProduct::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseProducts as $key => $product) {
            $product->update([
                'return_quantity' => $request->returnQuantities[$key],
            ]);
        }
        return redirect()->route('purchaseReturn.index')->withSuccess('Purchase return updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $purchaseReturn = PurchaseReturn::where('slug', $slug)->first();
        // delete purchase return image
        if (isset($purchaseReturn->return_image)) {
            $this->deleteImage('img/return-purchases/' . $purchaseReturn->return_image);
        }

        // update purchase product quanity
        $purchaseProducts = PurchaseProduct::where('purchase_id', $purchaseReturn->purchase->id)->get();
        foreach ($purchaseProducts as $key => $product) {
            $product->update([
                'return_quantity' => 0,
            ]);
        }
        $purchaseReturn->delete();
        return redirect()->route('purchaseReturn.index')->withSuccess('Purchase return deleted successfully!');
    }


    /**
     * Change the status of specified expense.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $purchaseReturn = PurchaseReturn::where('slug', $slug)->first();

        // change category status
        if ($purchaseReturn->status == 1) {
            $purchaseReturn->update([
                'status' => 0
            ]);
        } else {
            $purchaseReturn->update([
                'status' => 1
            ]);
        }
        return redirect()->route('purchaseReturn.index')->withSuccess('Purchase return status changed successfully!');
    }


    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = PurchaseReturn::latest()->get();
        // share data to view
        view()->share('purchaseReturns', $data);
        $pdf = PDF::loadView('admin.pdf.return-purchases', $data->all());
        // download PDF file with download method
        return $pdf->download('return-purchases-list.pdf');
    }
}
