<?php

namespace App\Http\Controllers;

use App\Models\Showroom;
use Illuminate\Http\Request;

class ShowroomController extends Controller
{
    /**
     * Display a listing of the showrooms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $showrooms = Showroom::latest()->paginate(15);
        return view('admin.setup.showrooms.index', compact('showrooms'));
    }

    /**
     * Show the form for creating a new showroom.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setup.showrooms.create');
    }

    /**
     * Store a newly created showroom in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:showrooms',
            'showroomCode' => 'required|string|max:30|unique:showrooms,code',
            'showroomManager' => 'required|string|max:50',
            'emailAddress' => 'nullable|string|max:80',
            'phoneNumber' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);

        // store showroom
        $showroom = Showroom::create([
            'name' => $request->name,
            'code' => $request->showroomCode,
            'manager' => $request->showroomManager,
            'email' => $request->emailAddress,
            'phone_number' => $request->phoneNumber,
            'address' => clean($request->address),
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('showrooms.index')->withSuccess('Showroom added successfully!');
    }

    /**
     * Display the specified showroom.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $showroom = Showroom::where('slug', $slug)->first();
        return view('admin.setup.showrooms.show', compact('showroom'));
    }

    /**
     * Show the form for editing the specified showroom.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $showroom = Showroom::where('slug', $slug)->first();
        return view('admin.setup.showrooms.edit', compact('showroom'));
    }

    /**
     * Update the specified showroom in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $showroom = Showroom::where('slug', $slug)->first();

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:30|unique:showrooms,name,' . $showroom->id,
            'showroomCode' => 'required|string|max:30|unique:showrooms,code,' . $showroom->id,
            'showroomManager' => 'required|string|max:50',
            'emailAddress' => 'nullable|string|max:80',
            'phoneNumber' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ]);

        // update showroom
        $showroom->update([
            'name' => $request->name,
            'code' => $request->showroomCode,
            'manager' => $request->showroomManager,
            'email' => $request->emailAddress,
            'phone_number' => $request->phoneNumber,
            'address' => clean($request->address),
            'note' => clean($request->note),
            'status' => $request->status
        ]);
        return redirect()->route('showrooms.index')->withSuccess('Showroom updated successfully!');
    }

    /**
     * Remove the specified showroom from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $showroom = Showroom::where('slug', $slug)->first();

        // delete showroom
        $showroom->delete();
        return redirect()->route('showrooms.index')->withSuccess('Showroom deleted successfully!');
    }

    /**
     * Change the status of specified showroom.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $showroom = Showroom::where('slug', $slug)->first();

        // change status
        if ($showroom->status == 1) {
            $showroom->update([
                'status' => 0
            ]);
        } else {
            $showroom->update([
                'status' => 1
            ]);
        }
        return redirect()->route('showrooms.index')->withSuccess('Showroom status changed successfully!');
    }
}