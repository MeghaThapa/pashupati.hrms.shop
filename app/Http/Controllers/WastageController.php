<?php

namespace App\Http\Controllers;

use App\Models\Wastages;
use Illuminate\Http\Request;

class WastageController extends Controller
{
    public  function index(){
        return view('admin.setup.wastages.index');
    }

    public function create(){
        return view('admin.setup.wastages.create');
    }
    public function store(Request $request){
        $request->validate([
            'wastage_name' => "required",
            'status' => "required"
        ]);
        $create = Wastages::create([
            'name' => $request->wastage_name,
            "status" => $request->status
        ]);
        if($create){
            return back()->with([
                'message' => "Waste type created successfully"
            ]);
        }
    }
}
