<?php

namespace App\Http\Controllers;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(15);
        return view('admin.setup.payments.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.payments.create');
    }

    /**
     * Store a newly created payment method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:payment_methods',
            'paymentMethodCode' => 'required|string|max:30|unique:payment_methods,code',
            'note' => 'nullable|string|max:255',
        ]);

        // store paymetn method
        $method = PaymentMethod::create([
            'name' => $request->name,
            'code' => $request->paymentMethodCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('payments.index')->withSuccess('Payment method added successfully!');
    }

    /**
     * Display the specified payment method.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('payments.index');
    }

    /**
     * Show the form for editing the specified payment method.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $method = PaymentMethod::where('slug', $slug)->first();
        return view('admin.setup.payments.edit', compact('method'));
    }

    /**
     * Update the specified payment method in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $method = PaymentMethod::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:payment_methods,name,'.$method->id,
            'paymentMethodCode' => 'required|string|max:30|unique:payment_methods,code,'.$method->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update payment method
        $method->update([
            'name' => $request->name,
            'code' => $request->paymentMethodCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('payments.index')->withSuccess('Payment method updated successfully!');
    }

    /**
     * Remove the specified payment method from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $method = PaymentMethod::where('slug', $slug)->first();

        // delete payment method
        $method->delete();
        return redirect()->route('payments.index')->withSuccess('Payment method deleted successfully!');
    }

    /**
     * Change the status of specified payment method.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $method = PaymentMethod::where('slug', $slug)->first();

        // change payment method status
        if($method->status == 1)
        {
            $method->update([
                'status' => 0
            ]);
        }
        else
        {
            $method->update([
                'status' => 1
            ]);
        }
        return redirect()->route('payments.index')->withSuccess('Payment method status changed successfully!');

    }
}
