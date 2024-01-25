<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shift.index', compact('shifts'));
    }
    public function create()
    {
        return view('admin.shift.create');
    }
    public function store(Request $request)
    {
        //return $request;
        $validator = $request->validate([
            'name'          => 'required|unique:shifts,name',
            'start_time'         => 'required',
            'end_time'         => 'required',
        ]);
        $shift = new Shift();
        $shift->name = $request->name;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->save();
        return redirect()->route('shift.index')->withSuccess('Shift created successfully!');
    }
    public function update(Request $request)
    {
        //return $request;

        $validator = $request->validate([
            'name'          => 'required',
            'start_time'         => 'required',
            'end_time'         => 'required',
        ]);
        $shift = Shift::find($request->shift_id);
        $shift->name = $request->name;
        $shift->start_time = $request->start_time;
        $shift->end_time = $request->end_time;
        $shift->status = $request->status;
        $shift->save();
        return back()->withSuccess('Shift updated successfully!');
    }
    public function getShiftData($id)
    {
        return Shift::find($id);
    }
    public function delete($id)
    {
        Shift::find($id)->delete();
    }
}
