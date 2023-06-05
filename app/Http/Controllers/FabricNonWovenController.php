<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricGroup;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FabricImport;

class FabricNonWovenController extends Controller
{
    public function index(Request $request)
    {
        $query = Fabric::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $fabrics = $query->orderBy('id', 'DESC')->paginate(15);

        return view('admin.fabricnonwoven.index', compact('fabrics'));
    }

    public function create()
    {
        $fabricgroups = FabricGroup::get();
        return view('admin.fabricnonwoven.create',compact('fabricgroups'));
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
}
