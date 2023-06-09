<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricGroup;
use App\Models\NonWovenFabric;
use Maatwebsite\Excel\Facades\Excel;

class FabricNonWovenController extends Controller
{
    public function index(Request $request)
    {
        $query = NonWovenFabric::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $nonwovenfabrics = $query->orderBy('id', 'DESC')->paginate(15);

        return view('admin.nonwovenfabric.index', compact('nonwovenfabrics'));
    }

    public function create()
    {
        return view('admin.nonwovenfabric.create');
    }

    public function store(Request $request)
    {
        // dd('hello');
        //validate form


        $validator = $request->validate([
            'name' => 'required|string|max:60|unique:non_woven_fabrics',
            'gsm' => 'required|numeric|unique:non_woven_fabrics',
            'color' => 'required',
        ]);

        $fabric = NonWovenFabric::create([
            'name' => $request['name'],
            'gsm' => $request['gsm'],
            'color' => $request['color'],
        ]);


        // store subcategory
        // $fabric = Fabric::create([
        //     'name' => $request['name'],
        //     'roll_no' => $request['roll_no'],
        //     'loom_no' => $request['loom_no'],
        //     'fabricgroup_id' => $request['fabricgroup_id'],
        //     'gross_wt' => $request['gross_wt'],
        //     'net_wt' => $request['net_wt'],
        //     'meter' => $request['meter'],
        //     'gram' => '00',
        // ]);
        return redirect()->back()->withSuccess('NonWoven created successfully!');
    }

    public function edit($slug)
    {
        $nonwovenfabrics = NonWovenFabric::where('slug', $slug)->first();
        return view('admin.nonwovenfabric.edit', compact('nonwovenfabrics'));
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
        $fabric = NonWovenFabric::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:non_woven_fabrics,name,' . $fabric->id,
            'gsm' => 'required|numeric|unique:non_woven_fabrics',
            'color' => 'required',
        ]);

        // update fabric
        $fabric->update([
            'name' => $request->name,
            'gsm' => $request['gsm'],
            'color' => $request['color'],
        ]);


        return redirect()->route('nonwovenfabrics.index')->withSuccess('Fabric updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = NonWovenFabric::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('nonwovenfabrics.index')->withSuccess('Fabric deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = NonWovenFabric::where('slug', $slug)->first();

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
        return redirect()->route('nonwovenfabrics.index')->withSuccess('Fabric status changed successfully!');
    }

   
}
