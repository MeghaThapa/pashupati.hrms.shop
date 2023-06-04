<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricGroup;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FabricImport;


class FabricController extends Controller
{
    public function index(Request $request)
    {
        $query = Fabric::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $fabrics = $query->orderBy('id', 'DESC')->paginate(15);

        return view('admin.fabric.index', compact('fabrics'));
    }

   
     public function create()
    {
        $fabricgroups = FabricGroup::get();
        return view('admin.fabric.create',compact('fabricgroups'));
    }

    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:60|unique:fabrics',
            'fabricgroup_id' => 'required|integer',
            'roll_no' => 'required|integer',
            'loom_no' => 'required|integer',
            'gross_wt' => 'required|integer',
            'net_wt' => 'required|integer',
        ]);


        // store subcategory
        $fabric = Fabric::create([
            'name' => $request['name'],
            'roll_no' => $request['roll_no'],
            'loom_no' => $request['loom_no'],
            'fabricgroup_id' => $request['fabricgroup_id'],
            'gross_wt' => $request['gross_wt'],
            'net_wt' => $request['net_wt'],
            'meter' => $request['meter'],
            'gram' => '00',
        ]);
        return redirect()->back()->withSuccess('Sub category created successfully!');
    }

    public function import(Request $request){
        // dd('lol');
        $request->validate([
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm",
        ]);
        $file = $request->file('file');
        $import = Excel::import(new FabricImport, $file );
        if($import){
            return back()->with(["message"=>"Data imported successfully!"]);
        }else{
            return "Unsuccessful";
        }
    }

    public function edit($slug)
    {
        $fabrics = Fabric::where('slug', $slug)->first();
        $fabricgroups = FabricGroup::get();
        return view('admin.fabric.edit', compact('fabrics','fabricgroups'));
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
        $fabric = Fabric::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:fabrics,name,' . $fabric->id,
            'fabricgroup_id' => 'required|integer',
            'roll_no' => 'required|integer',
            'loom_no' => 'required|integer',
            'gross_wt' => 'required|integer',
            'net_wt' => 'required|integer',
        ]);

        // update fabric
        $fabric->update([
            'name' => $request->name,
            'roll_no' => $request['roll_no'],
            'loom_no' => $request['loom_no'],
            'fabricgroup_id' => $request['fabricgroup_id'],
            'gross_wt' => $request['gross_wt'],
            'net_wt' => $request['net_wt'],
            'meter' => $request['meter'],
        ]);


        return redirect()->route('fabrics.index')->withSuccess('Fabric updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = Fabric::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('fabrics.index')->withSuccess('Fabric deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = Fabric::where('slug', $slug)->first();

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
        return redirect()->route('fabrics.index')->withSuccess('Fabric status changed successfully!');
    }
}
