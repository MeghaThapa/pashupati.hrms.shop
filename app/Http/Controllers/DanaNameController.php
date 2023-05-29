<?php

namespace App\Http\Controllers;

use App\Models\DanaName;
use Illuminate\Http\Request;

class DanaNameController extends Controller
{
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name'          => 'required|unique:dana_names,name',
            'dana_group_id' => 'required',
            'status'         => 'required',
        ]);
        $danaName = new DanaName();
        $danaName->name = $request->name;
        $danaName->dana_group_id  = $request->dana_group_id;
        $danaName->status = $request->status;
        $danaName->save();
        return response()->json([
            'message' => 'Dana Name created successfully ',
            'danaName' => $danaName
        ]);
    }
}
