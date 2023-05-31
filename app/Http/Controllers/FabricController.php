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
        $categories = $query->orderBy('id', 'DESC')->paginate(15);

        return view('admin.fabric.index', compact('categories'));
    }

     public function create()
    {
        $fabric_groups = FabricGroup::get();
        return view('admin.fabric.create',compact('fabric_groups'));
    }

    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:60|unique:sub_categories',
            'fabricgroup_id' => 'required|integer',
        ]);

        // convert sizes into string
        $sizes = implode(', ', $request->sizes);

        // store subcategory
        $subCategory = Fabric::create([
            'name' => $request->name,
            'category_id' => $request->categoryName,
            'sizes' => $sizes,
            'note' => clean($request->note),
            'status' => $request->status
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
}
