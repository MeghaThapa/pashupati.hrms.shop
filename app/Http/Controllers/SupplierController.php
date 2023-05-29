<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use DB;
use Illuminate\Http\Request;
use PDF;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%')
                ->orWhere('email', 'Like', '%' . $term . '%')
                ->orWhere('phone_number', 'Like', '%' . $term . '%')
                ->orWhere('company_name', 'Like', '%' . $term . '%')
                ->orWhere('designation', 'Like', '%' . $term . '%');
        }
        $suppliers = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name'          => 'required|string|max:50',
            'email'         => 'nullable|string|email|max:80|unique:suppliers',
            'phone'         => 'sometimes|nullable',
            'company'       => 'required|string|max:50',
            'designation'   => 'nullable|string|max:50',
            'profilePic'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);


        //upload selected image
        $imageName = '';
        if (isset($request->profilePic)) {
            $imagePath = 'img/suppliers';
            $imageName = $this->uploadImage($imagePath, $request->profilePic);
        }

        // store supplier
        Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'company_name' => $request->company,
            'designation' => $request->designation,
            'address' => clean($request->address),
            'profile_picture' => $imageName,
            'status' => $request->status
        ]);

        return redirect()->back()->withSuccess('Supplier added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        $purchases = Purchase::where('supplier_id', $supplier->id)->with('supplier', 'purchaseProducts')->latest()->paginate(15);
        $totalDiscount = DB::table('purchases')->selectRaw('((discount / 100) * sub_total) AS new_dis')->where('supplier_id', $supplier->id)->get()->sum('new_dis');
        return view('admin.suppliers.show', compact('supplier', 'purchases', 'totalDiscount'));
    }

    /**
     * Show the form for editing the supplier resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'nullable|string|email|max:80|unique:suppliers,email,' . $supplier->id,
            'phone' => 'sometimes|nullable|numeric',
            'company' => 'required|string|max:50',
            'designation' => 'nullable|string|max:50',
            'profilePic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageName = $supplier->profile_picture;

        // delete current image and uplad new image
        if (isset($request->profilePic)) {
            $this->deleteImage('img/suppliers/' . $supplier->profile_picture);
            $imagePath = 'img/suppliers';
            $imageName = $this->uploadImage($imagePath, $request->profilePic);
        }

        // update supplier
        $supplier->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'company_name' => $request->company,
            'designation' => $request->designation,
            'address' => clean($request->address),
            'profile_picture' => $imageName,
            'status' => $request->status
        ]);

        return redirect()->route('suppliers.index')->withSuccess('Supplier updated successfully!');
    }

    /**
     * Remove the specified supplier from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        //delete supplier image from storage
        $this->deleteImage('img/suppliers/' . $supplier->profile_picture);

        // delete supplier
        $supplier->delete();
        return redirect()->route('suppliers.index')->withSuccess('Supplier deleted successfully!');
    }

    /**
     * Change the status of specified suppler.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $supplier = Supplier::findOrFail($id);

        // change supplier status
        if ($supplier->status == 1) {
            $supplier->update([
                'status' => 0
            ]);
        } else {
            $supplier->update([
                'status' => 1
            ]);
        }
        return redirect()->route('suppliers.index')->withSuccess('Supplier status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = Supplier::latest()->get();
        // share data to view
        view()->share('suppliers', $data);
        $pdf = PDF::loadView('admin.pdf.suppliers', $data->all());
        // download PDF file with download method
        return $pdf->download('supplier-list.pdf');
    }
}