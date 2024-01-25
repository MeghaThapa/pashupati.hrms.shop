<?php

namespace App\Http\Controllers;

use App\Models\Setupstorein;
use Illuminate\Http\Request;

class SetupstoreinController extends Controller
{
    /**
     * Display a listing of the storein.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storein = Setupstorein::latest()->paginate(15);
        return view('admin.setup.setupstorein.index', compact('storein'));
    }

    /**
     * Show the form for creating a new size.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.setupstorein.create');
    }

    /**
     * Store a newly created size in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:storein',
            'storeinCode' => 'required|string|max:30|unique:storein,code',
            'note' => 'nullable|string|max:255',
        ]);

        // store size
        $size = Setupstorein::create([
            'name' => $request->name,
            'code' => $request->storeinCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->back()->withSuccess('storein added successfully!');
    }

    /**
     * Display the specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('storeinsetup.index');
    }

    /**
     * Show the form for editing the specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $storein = Setupstorein::where('slug', $slug)->first();
        return view('admin.setup.storein.edit', compact('storein'));
    }

    /**
     * Update the specified size in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $storein = Setupstorein::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:storein,name,'.$storein->id,
            'storeinCode' => 'required|string|max:30|unique:storein,code,'.$storein->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update size
        $storein->update([
            'name' => $request->name,
            'code' => $request->storeinCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('storeinsetup.index')->withSuccess('storein updated successfully!');
    }

    /**
     * Remove the specified size from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $size = Setupstorein::where('slug', $slug)->first();

        // delete size
        $size->delete();
        return redirect()->route('storein.index')->withSuccess('storein deleted successfully!');
    }

    /**
     * Change the status of specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $size = Setupstorein::where('slug', $slug)->first();

        // change status
        if($size->status == 1)
        {
            $size->update([
                'status' => 0
            ]);
        }
        else
        {
            $size->update([
                'status' => 1
            ]);
        }
        return redirect()->route('storeinsetup.index')->withSuccess('storein status changed successfully!');
    }
}
