<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricStock;
use App\Models\FabricGroup;
use App\Models\NonWovenFabric;
use App\Models\FabricNonWovenReciveEntry;
use App\Models\FabricNonWovenReceiveEntryStock;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


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

    public function test()
    {
        // dd('lol');
        $fabric_opening = Fabric::where('bill_no','opening')->count('net_wt');
        $fabric_entry = Fabric::where('bill_no','!=','Opening')->count('net_wt');

        $fabric_op_en = $fabric_opening + $fabric_entry;
        $current_stock = FabricStock::count('net_wt');
        $current_fabric_entry = $current_stock - $fabric_entry;

        dd($fabric_op_en,$current_stock,$current_fabric_entry);
        return view('admin.nonwovenfabric.create');
    }

    public function store(Request $request)
    {


        $validator = $request->validate([
            'name' => 'required|string|max:60',
            'gsm' => 'required|numeric',
            'color' => 'required',
        ]);

        $slug = Str::slug($request['name']);
        $color = str_replace(' ', '', $request['color']);

        $count =  NonWovenFabric::where('slug',$slug)->where('gsm',$request->gsm)->where('color',$request->color)->count();

        if($count == 0){
            $fabric = NonWovenFabric::create([
                'name' => $request['name'],
                'slug' => Str::slug($request['name']),
                'gsm' => $request['gsm'],
                'color' => $color,
            ]);

        }

        return redirect()->back()->withSuccess('NonWoven created successfully!');
    }

    public function edit($id)
    {
        $nonwovenfabrics = NonWovenFabric::where('id', $id)->first();
        return view('admin.nonwovenfabric.edit', compact('nonwovenfabrics'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fabric = NonWovenFabric::where('id', $id)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50',
            'gsm' => 'required|numeric',
            'color' => 'required',
        ]);
        $color = str_replace(' ', '', $request['color']);


        // update fabric
        $fabric->update([
            'name' => $request['name'],
            'slug' => Str::slug($request['name']),
            'gsm' => $request['gsm'],
            'color' => $color,
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
        $category = NonWovenFabric::find($slug);
        // dd($category);
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
