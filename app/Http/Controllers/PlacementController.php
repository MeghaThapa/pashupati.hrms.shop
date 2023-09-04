<?php

namespace App\Http\Controllers;

use App\Models\Placement;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    public function save(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:placements,name,NULL,id,godam_id,' . $request->input('godam_id'),
            'godam_id' =>'required',
            'storeoutdpt_id' => 'required',
            'status' => 'required',
        ]);
        $placement = new Placement();
        $placement->name = $request->name;
        $placement->storeout_dpt_id  = $request->storeoutdpt_id;
        $placement->godam_id = $request->godam_id;
        $placement->status = $request->status;
        $placement->save();
        return response()->json([
            'message' => 'placement created successfully ',
            'placement' => $placement
        ]);
    }
}
