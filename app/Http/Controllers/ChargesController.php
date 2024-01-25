<?php

namespace App\Http\Controllers;

use App\Models\Charges;
use Exception;
use Illuminate\Http\Request;

class ChargesController extends Controller
{
    public function store(Request $request)
    {
        // return $request;
        $validator = $request->validate([
            'charge' => 'required|string|max:50|unique:charges,name',
        ]);
        $charges = new Charges();
        $charges->name = $request->charge;
        $charges->save();
        return back()->withSuccess('Charges creates successfully');
    }
}
