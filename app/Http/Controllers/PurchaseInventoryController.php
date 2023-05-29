<?php

namespace App\Http\Controllers;

use App\Models\PurchaseProduct;
use Illuminate\Http\Request;
use PDF;

class PurchaseInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = PurchaseProduct::query();
        if (request('term')) {
            $term = request('term');
            $query->where('product_name', 'Like', '%' . $term . '%')
                ->orWhere('quantity', 'Like', '%' . $term . '%')
                ->orWhere('used_quantity', 'Like', '%' . $term . '%')
                ->orWhere('return_quantity', 'Like', '%' . $term . '%')
                ->orWhere('damage_quantity', 'Like', '%' . $term . '%')
                ->orWhere('unit_price', 'Like', '%' . $term . '%');
        }
        $products = $query->with('purchase')->orderBy('id', 'DESC')->paginate(15);

        return view('admin.purchase-inventory.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = PurchaseProduct::with('purchase')->latest()->get();
        // share data to view
        view()->share('products', $data);
        $pdf = PDF::loadView('admin.pdf.purchase-inventory', $data->all());
        // download PDF file with download method
        return $pdf->download('purchase-inventory-list.pdf');
    }
}
