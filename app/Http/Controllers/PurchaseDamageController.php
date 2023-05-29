<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDamage;
use App\Models\PurchaseProduct;
use Illuminate\Http\Request;
use PDF;


class PurchaseDamageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PurchaseDamage::query();
        if (request('term')) {
            $term = request('term');
            $query->where('damage_reason', 'Like', '%' . $term . '%');
        }
        $damagePruchases = $query->with('purchase')->orderBy('id', 'DESC')->paginate(15);
        return view('admin.damage-purchases.index', compact('damagePruchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purchases = Purchase::where('status', 1)->latest()->get();
        return view('admin.damage-purchases.create', compact('purchases'));
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
            'damageReason' => 'required|string|max:255|min:3',
            'damageDate' => "required|date|after_or_equal:$purchase->purchase_date",
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // store purchase return image
        $attchedPic = '';
        if (isset($request->attchedPic)) {
            $imagePath = 'img/damage-purchases/';
            $attchedPic = $this->uploadImage($imagePath, $request->attchedPic);
        }

        $purchaseReturn = PurchaseDamage::create([
            'purchase_id' => $purchase->id,
            'damage_reason' => $request->damageReason,
            'damage_date' => $request->damageDate,
            'damage_image' => $attchedPic,
            'note' => $request->note,
            'status' => $request->status
        ]);

        // update purchase product quanity
        $purchaseProducts = PurchaseProduct::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseProducts as $key => $product) {
            $product->update([
                'damage_quantity' => $request->damageQuantities[$key],
            ]);
        }
        return redirect()->route('purchaseDamage.index')->withSuccess('Damage purchase added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $damagePurchase = PurchaseDamage::with('purchase')->where('slug', $slug)->first();
        return view('admin.damage-purchases.show', compact('damagePurchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $damagePurchase = PurchaseDamage::where('slug', $slug)->first();
        $purchases = Purchase::where('status', 1)->latest()->get();
        return view('admin.damage-purchases.edit', compact('damagePurchase', 'purchases'));
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
        $damagePurchase = PurchaseDamage::where('slug', $slug)->first();
        $purchase = Purchase::findOrFail($request->purchaseProduct);
        //validate form
        $validator = $request->validate([
            'damageReason' => 'required|string|max:255|min:3',
            'damageDate' => "required|date|after_or_equal:$purchase->purchase_date",
            'note' => 'nullable|string|max:255',
            'attchedPic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // store purchase return image
        $attchedPic = $damagePurchase->damage_image;
        if (isset($request->attchedPic)) {
            $imagePath = 'img/damage-purchases/';
            if (isset($attchedPic)) {
                $this->deleteImage($imagePath . $attchedPic);
            }
            $attchedPic = $this->uploadImage($imagePath, $request->attchedPic);
        }

        $damagePurchase->update([
            'purchase_id' => $purchase->id,
            'damage_reason' => $request->damageReason,
            'damage_date' => $request->damageDate,
            'damage_image' => $attchedPic,
            'note' => $request->note,
            'status' => $request->status
        ]);

        // update purchase product quanity
        $purchaseProducts = PurchaseProduct::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseProducts as $key => $product) {
            $product->update([
                'damage_quantity' => $request->damageQuantities[$key],
            ]);
        }
        return redirect()->route('purchaseDamage.index')->withSuccess('Damage purchase updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $damagePurchase = PurchaseDamage::where('slug', $slug)->first();
        // delete purchase damage image
        if (isset($damagePurchase->damage_image)) {
            $this->deleteImage('img/damage-purchases/' . $damagePurchase->damage_image);
        }

        // update purchase product quanity
        $purchaseProducts = PurchaseProduct::where('purchase_id', $damagePurchase->purchase->id)->get();
        foreach ($purchaseProducts as $key => $product) {
            $product->update([
                'damage_quantity' => 0,
            ]);
        }
        $damagePurchase->delete();
        return redirect()->route('purchaseDamage.index')->withSuccess('Purchase return deleted successfully!');
    }


    /**
     * Change the status of specified expense.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $damagePurchase = PurchaseDamage::where('slug', $slug)->first();

        // change category status
        if ($damagePurchase->status == 1) {
            $damagePurchase->update([
                'status' => 0
            ]);
        } else {
            $damagePurchase->update([
                'status' => 1
            ]);
        }
        return redirect()->route('purchaseDamage.index')->withSuccess('Purchase damage status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = PurchaseDamage::with('purchase')->latest()->get();
        // share data to view
        view()->share('damagePruchases', $data);
        $pdf = PDF::loadView('admin.pdf.damage-purchases', $data->all());
        // download PDF file with download method
        return $pdf->download('damage-purchases-list.pdf');
    }
}
