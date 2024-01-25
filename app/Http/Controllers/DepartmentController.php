<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the units.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Department::latest()->paginate(15);
        return view('admin.setup.department.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.department.create');
    }

    /**
     * Store a newly created unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        // $validator = $request->validate([
        //     'name' => 'required|string|max:30|unique:units',
        //     'note' => 'nullable|string|max:255',
        // ]);

        // store unit
        $unit = Department::create([
            'department' => $request->name,
            'status' => $request->status

        ]);
        // dd($unit);

        return redirect()->route('department.index')->withSuccess('Department added successfully!');
    }

    public function storeDepartmentFromModel(Request $request)
    {
        $validator = $request->validate([
            'department' => 'required|string|max:50|unique:department,department',
            'status' => 'required'
        ]);
        $department = new Department();
        $department->department = $request->department;
        $department->status = $request->status;
        $department->slug = Str::slug($request->department);
        $department->save();
        $message = 'Department added successfully!';
        return response()->json(['message' => $message, 'department' => $department]);
        //return $department->withSuccess('Department added successfully!');

    }
    /**
     * Display the specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return redirect()->route('department.index');
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $unit = Department::where('slug', $slug)->first();
        return view('admin.setup.department.edit', compact('unit'));
    }

    /**
     * Update the specified unit in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $unit = Department::where('slug', $slug)->first();

        // validate form
        // $validator = $request->validate([
        //     'name' => 'required|string|max:30|unique:units,name,'.$unit->id,
        //     'unitCode' => 'required|string|max:30|unique:units,code,'.$unit->id,
        // ]);

        // update unit
        $unit->update([
            'department' => $request->name,
            'status' => $request->status
        ]);
        // dd($unit);
        return redirect()->route('department.index')->withSuccess('Department updated successfully!');
    }

    /**
     * Remove the specified unit from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $unit = Department::where('slug', $slug)->first();

        // delete unit
        $unit->delete();
        return redirect()->route('department.index')->withSuccess('Department deleted successfully!');
    }


    /**
     * Change the status of specified unit.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $unit = Department::where('slug', $slug)->first();

        // change unit status
        if ($unit->status == 1) {
            $unit->update([
                'status' => 0
            ]);
        } else {
            $unit->update([
                'status' => 1
            ]);
        }
        return redirect()->route('department.index')->withSuccess('Department status changed successfully!');
    }
}
