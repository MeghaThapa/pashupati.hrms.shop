<?php

namespace App\Http\Controllers;

use App\Models\StoreoutDepartment;
use Illuminate\Http\Request;

class StoreoutDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeoutDepartment= new StoreoutDepartment();
        $storeoutDepartment->name= $request->name;
        $storeoutDepartment->created_by = auth()->user()->id;
        $storeoutDepartment->status= $request->status;
         $storeoutDepartment->save();
          return response()->json([
                'message' =>'storeout department Created Successfully',
                'storeoutDepartment' => $storeoutDepartment,
            ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StoreoutDepartment  $storeoutDepartment
     * @return \Illuminate\Http\Response
     */
    public function show(StoreoutDepartment $storeoutDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StoreoutDepartment  $storeoutDepartment
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreoutDepartment $storeoutDepartment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StoreoutDepartment  $storeoutDepartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreoutDepartment $storeoutDepartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StoreoutDepartment  $storeoutDepartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreoutDepartment $storeoutDepartment)
    {
        //
    }
}
