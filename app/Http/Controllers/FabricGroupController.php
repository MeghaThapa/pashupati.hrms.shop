<?php

namespace App\Http\Controllers;

use App\Models\FabricGroup;
use Illuminate\Http\Request;
use PDF;

class FabricGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = FabricGroup::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $fabricgroups = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.fabric-group.index', compact('fabricgroups'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.fabric-group.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:fabric_groups',
        ]);

        // store category
        $category = FabricGroup::create([
            'name' => $request->name,
            'status' => $request->status
        ]);
        return redirect()->back()->withSuccess('FabricGroup added successfully!');
    }

    /**
     * Display the specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('fabric-groups.index');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $fabricgroups = FabricGroup::where('slug', $slug)->first();
        return view('admin.fabric-group.edit', compact('fabricgroups'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $category = FabricGroup::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:fabric_groups,name,' . $category->id,
            'note' => 'nullable|string|max:255',
        ]);

        // update category
        $category->update([
            'name' => $request->name,
            'status' => $request->status
        ]);
        return redirect()->route('fabric-groups.index')->withSuccess('FabricGroup updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = FabricGroup::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('fabric-groups.index')->withSuccess('FabricGroup deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = FabricGroup::where('slug', $slug)->first();

        // change category status
        if ($category->status == 1) {
            $category->update([
                'status' => 0
            ]);
        } else {
            $category->update([
                'status' => 1
            ]);
        }
        return redirect()->route('fabric-groups.index')->withSuccess('FabricGroup status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = FabricGroup::latest()->get();
        // share data to view
        view()->share('fabric_groups', $data);
        $pdf = PDF::loadView('admin.pdf.fabricgroup', $data->all());
        // download PDF file with download method
        return $pdf->download('fabric_group-list.pdf');
    }
}
