<?php

namespace App\Http\Controllers;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the sizes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::latest()->paginate(15);
        return view('admin.setup.sizes.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new size.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.sizes.create');
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
            'name' => 'required|string|max:30|unique:sizes',
            'code' => 'required|string|max:30|unique:sizes,code',
            'note' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        // store size
        try{
            $size = Size::create([
                'name' =>strtolower($request->name),
                'code' => $request->code,
                'note' => clean($request->note),
                'status' => $request->status
            ]);
            return response()->json([
                    'message' => 'Size added successfully!',
                    'size'=>$size,
                ], 200);
        }catch(Exception $e){
            return "something went wrong while creating size";
        }
       // return redirect()->back()->withSuccess('Size added successfully!');
    }

    /**
     * Display the specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('sizes.index');
    }

    /**
     * Show the form for editing the specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $size = Size::where('slug', $slug)->first();
        return view('admin.setup.sizes.edit', compact('size'));
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
        $size = Size::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:sizes,name,'.$size->id,
            'sizeCode' => 'required|string|max:30|unique:sizes,code,'.$size->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update size
        $size->update([
            'name' => $request->name,
            'code' => $request->sizeCode,
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('sizes.index')->withSuccess('Size updated successfully!');
    }

    /**
     * Remove the specified size from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $size = Size::where('slug', $slug)->first();

        // delete size
        $size->delete();
        return redirect()->route('sizes.index')->withSuccess('Size deleted successfully!');
    }

    /**
     * Change the status of specified size.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $size = Size::where('slug', $slug)->first();

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
        return redirect()->route('sizes.index')->withSuccess('Size status changed successfully!');
    }
}
