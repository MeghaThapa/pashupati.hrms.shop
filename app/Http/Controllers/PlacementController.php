<?php

namespace App\Http\Controllers;

use App\Models\Placement;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    public function save(Request $request)
    {
        $validator = $request->validate([
            'placement' => 'required|string|max:50|unique:placements,name',
            'department_id' => 'required',
            'status' => 'required',
        ]);
        $placement = new Placement();
        $placement->name = $request->placement;
        $placement->department_id = $request->department_id;
        $placement->status = $request->status;
        $placement->save();
        return response()->json([
            'message' => 'placement created successfully ',
            'placement' => $placement
        ]);
    }
}
