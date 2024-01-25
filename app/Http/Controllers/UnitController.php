<?php

namespace App\Http\Controllers;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::latest()->paginate(15);
        return view('admin.setup.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.units.create');
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
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:units',
            'code' => 'required|string|max:30|unique:units,code',
            'status' =>'required',
            'note' => 'nullable|string|max:255',
        ]);

        // store unit
        try{
        $unit = Unit::create([
            'name' => $request->name,
            'code' => $request->code,
            'slug' =>Str::slug($request->name),
            'note' => clean($request->note),
            'status' => $request->status
        ]);
         return response()->json([
                'message' => 'Unit added successfully!',
                'unit'=>$unit,
            ], 200);
        }catch(Exception $e){
            return "something went wrong while creating unit";
        }
       // return redirect()->back()->withSuccess('Unit added successfully!');
    }

    /**
     * Display the specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('units.index');
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $unit = Unit::where('slug', $slug)->first();
        return view('admin.setup.units.edit', compact('unit'));
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
        $unit = Unit::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:units,name,'.$unit->id,
            'unitCode' => 'required|string|max:30|unique:units,code,'.$unit->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update unit
        $unit->update([
            'name' => $request->name,
            'code' => $request->unitCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('units.index')->withSuccess('Unit updated successfully!');
    }

    /**
     * Remove the specified unit from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $unit = Unit::where('slug', $slug)->first();

        // delete unit
        $unit->delete();
        return redirect()->route('units.index')->withSuccess('Unit deleted successfully!');
    }


    /**
     * Change the status of specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $unit = Unit::where('slug', $slug)->first();

        // change unit status
        if($unit->status == 1)
        {
            $unit->update([
                'status' => 0
            ]);
        }
        else
        {
            $unit->update([
                'status' => 1
            ]);
        }
        return redirect()->route('units.index')->withSuccess('Unit status changed successfully!');

    }
}
