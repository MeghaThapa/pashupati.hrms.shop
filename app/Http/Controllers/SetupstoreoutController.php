<?php

namespace App\Http\Controllers;

use App\Models\Setupstoreout;
use Illuminate\Http\Request;

class SetupstoreoutController extends Controller
{
    /**
     * Display a listing of the storeout.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storein = Setupstoreout::latest()->paginate(15);
        return view('admin.setup.setupstoreout.index', compact('storein'));
    }

    /**
     * Show the form for creating a new size.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.setupstoreout.create');
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
            'name' => 'required|string|max:30|unique:storeout',
            'storeoutCode' => 'required|string|max:30|unique:storeout,code',
            'note' => 'nullable|string|max:255',
        ]);

        // store size
        $size = Setupstoreout::create([
            'name' => $request->name,
            'code' => $request->storeoutCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('storeoutsetup.index')->withSuccess('storeout added successfully!');
    }

    /**
     * Display the specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('storeoutsetup.index');
    }

    /**
     * Show the form for editing the specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $storein = Setupstoreout::where('slug', $slug)->first();
        return view('admin.setup.storeout.edit', compact('storein'));
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
        $storein = Setupstoreout::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:storeout,name,'.$storein->id,
            'storeoutCode' => 'required|string|max:30|unique:storeout,code,'.$storein->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update size
        $storein->update([
            'name' => $request->name,
            'code' => $request->storeoutCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('storeoutsetup.index')->withSuccess('storeout updated successfully!');
    }

    /**
     * Remove the specified size from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $size = Setupstoreout::where('slug', $slug)->first();

        // delete size
        $size->delete();
        return redirect()->route('storeout.index')->withSuccess('storeout deleted successfully!');
    }

    /**
     * Change the status of specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $size = Setupstoreout::where('slug', $slug)->first();

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
        return redirect()->route('storeoutsetup.index')->withSuccess('storeout status changed successfully!');
    }
}
