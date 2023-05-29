<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PDF;

class UserController extends Controller
{
    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%')
                ->orWhere('email', 'Like', '%' . $term . '%');
        }
        $users = $query->orderBy('id', 'DESC')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:80|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profilePic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        //upload selected image
        $imageName = '';
        if (isset($request->profilePic)) {
            $imagePath = 'img/profile';
            $imageName = $this->uploadImage($imagePath, $request->profilePic);
        }

        // store staff
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_picture' => $imageName,
            'role' => $request->accountType,
            'status' => $request->status,
        ]);

        return redirect()->route('users.index')->withSuccess('User added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50',
            'password' => $request->password ? 'nullable|string|min:8|max:255|confirmed' : '',
            'profilePic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageName = $user->profile_picture;

        // remove current image and upload new image
        if (isset($request->profilePic)) {
            $this->deleteImage('img/profile/' . $user->profile_picture);
            $imagePath = 'img/profile';
            $imageName = $this->uploadImage($imagePath, $request->profilePic);
        }

        $request->password ? $password = Hash::make($request->password) : $password = $user->password;

        // update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'profile_picture' => $imageName,
            'role' => $request->accountType,
            'status' => $request->status,
        ]);

        return redirect()->route('users.index')->withSuccess('User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // delete user image from storage
        $this->deleteImage('img/profile/' . $user->profile_picture);

        // delete user
        $user->delete();
        return redirect()->route('users.index')->withSuccess('User deleted successfully!');
    }


    /**
     * Change the status of specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $user = User::findOrFail($id);

        // change staff status
        if ($user->status == 1) {
            $user->update([
                'status' => 0
            ]);
        } else {
            $user->update([
                'status' => 1
            ]);
        }
        return redirect()->route('users.index')->withSuccess('User status changed successfully!');
    }

    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = User::latest()->get();
        // share data to view
        view()->share('users', $data);
        $pdf = PDF::loadView('admin.pdf.users', $data->all());
        // download PDF file with download method
        return $pdf->download('user-list.pdf');
    }
}
