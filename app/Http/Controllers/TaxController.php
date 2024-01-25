<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    /**
     * Display a listing of the units.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Tax::latest()->paginate(15);
        return view('admin.setup.tax.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPercentageBySlug($slug)
    {
        return Tax::where('slug', $slug)->get('percentage')->first();
    }

    public function create()
    {
        return view('admin.setup.tax.create');
    }

    /**
     * Store a newly created unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        // $validator = $request->validate([
        //     'name' => 'required|string|max:30|unique:units',
        //     'note' => 'nullable|string|max:255',
        // ]);

        // store unit
        $unit = Tax::create([
            'tax_type' => $request->name,
            'percentage' => $request->tax,
            'status' => $request->status

        ]);
        // dd($unit);

        return redirect()->back()->withSuccess('Tax added successfully!');
    }

    /**
     * Display the specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('tax.index');
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $unit = Tax::where('slug', $slug)->first();
        return view('admin.setup.tax.edit', compact('unit'));
    }

    /**
     * Update the specified unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $unit = Tax::where('slug', $slug)->first();

        // validate form
        // $validator = $request->validate([
        //     'name' => 'required|string|max:30|unique:units,name,'.$unit->id,
        //     'unitCode' => 'required|string|max:30|unique:units,code,'.$unit->id,
        // ]);

        // update unit
        $unit->update([
            'tax_type' => $request->name,
            'percentage' => $request->tax,
            'status' => $request->status
        ]);
        // dd($unit);
        return redirect()->route('tax.index')->withSuccess('Tax updated successfully!');
    }

    /**
     * Remove the specified unit from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $unit = Tax::where('slug', $slug)->first();

        // delete unit
        $unit->delete();
        return redirect()->route('tax.index')->withSuccess('Tax deleted successfully!');
    }


    /**
     * Change the status of specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $unit = Tax::where('slug', $slug)->first();

        // change unit status
        if ($unit->status == 1) {
            $unit->update([
                'status' => 0
            ]);
        } else {
            $unit->update([
                'status' => 1
            ]);
        }
        return redirect()->route('tax.index')->withSuccess('Tax status changed successfully!');
    }
}
