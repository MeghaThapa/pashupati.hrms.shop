<?php

namespace App\Http\Controllers;

use App\Models\DanaGroup;
use Illuminate\Http\Request;

class DanaGroupController extends Controller
{
    public function store(Request $request)
    {
        // return $request;
        $validator = $request->validate([
            'dana_group_name'          => 'required|unique:dana_groups,name',
            'status'         => 'required',
        ]);
        $danaGroup = new DanaGroup();
        $danaGroup->name = $request->dana_group_name;
        $danaGroup->status = $request->status;
        $danaGroup->save();
        return response()->json([
            'message' => 'Dana Group created successfully ',
            'danaGroup' => $danaGroup
        ]);
    }
}
