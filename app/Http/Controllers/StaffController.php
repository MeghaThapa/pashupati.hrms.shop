<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use PDF;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Staff::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%')
                ->orWhere('email', 'Like', '%' . $term . '%')
                ->orWhere('phone_number', 'Like', '%' . $term . '%')
                ->orWhere('department', 'Like', '%' . $term . '%')
                ->orWhere('designation', 'Like', '%' . $term . '%');
        }
        $allStaff = $query->orderBy('id', 'DESC')->paginate(10);
        return view('admin.staff.index', compact('allStaff'));
    }

    /**
     * Show the form for creating a new staff.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created staff in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name'          => 'required|string|max:50',
            'email'         => 'nullable|string|email|max:80|unique:staff',
            'phone'         => 'nullable|max:20',
            'department'    => 'nullable|string|max:50',
            'designation'   => 'nullable|string|max:50',
            'profilePic'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        //upload selected image
        $imageName = '';
        if (isset($request->profilePic)) {
            $imagePath = 'img/staff';
            $imageName = $this->uploadImage($imagePath, $request->profilePic);
        }

        // store staff
        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'department' => $request->department,
            'designation' => $request->designation,
            'address' => clean($request->address),
            'profile_picture' => $imageName,
            'status' => $request->status
        ]);

        return redirect()->route('staff.index')->withSuccess('Staff added successfully!');
    }

    /**
     * Display the specified staff.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        // validate staff
        $validator = $request->validate([
            'name'              => 'required|string|max:50',
            'email'             => 'nullable|string|email|max:80|unique:staff,email,' . $staff->id,
            'phone'             => 'nullable|',
            'department'        => 'required|string|max:50',
            'designation'       => 'required|string|max:50',
            'profilePic'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageName = $staff->profile_picture;

        // remove current image and upload new image
        if (isset($request->profilePic)) {
            $this->deleteImage('img/staff/' . $staff->profile_picture);
            $imagePath = 'img/staff';
            $imageName = $this->uploadImage($imagePath, $request->profilePic);
        }

        // update staff
        $staff->update([
            'name'              => $request->name,
            'email'             => $request->email,
            'phone_number'      => $request->phone,
            'department'        => $request->department,
            'designation'       => $request->designation,
            'address'           => clean($request->address),
            'profile_picture'   => $imageName,
            'status'            => $request->status
        ]);

        return redirect()->route('staff.index')->withSuccess('Staff updated successfully!');
    }

    /**
     * Remove the specified staff from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        // delete staff image from storage
        $this->deleteImage('img/staff/' . $staff->profile_picture);
        // delete staff
        $staff->delete();
        return redirect()->route('staff.index')->withSuccess('Staff deleted successfully!');
    }


    /**
     * Change the status of specified staff.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $staff = Staff::findOrFail($id);

        // change staff status
        if ($staff->status == 1) {
            $staff->update([
                'status' => 0
            ]);
        } else {
            $staff->update([
                'status' => 1
            ]);
        }
        return redirect()->route('staff.index')->withSuccess('Staff status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = Staff::latest()->get();
        // share data to view
        view()->share('allStaff', $data);
        $pdf = PDF::loadView('admin.pdf.staff', $data->all());
        // download PDF file with download method
        return $pdf->download('staff-list.pdf');
    }
}