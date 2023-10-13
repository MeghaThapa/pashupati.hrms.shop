<?php

namespace App\Http\Controllers;

use App\Models\PrintingAndCuttingBagStock;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PrintingAndCuttingBagStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = PrintingAndCuttingBagStock::with('group:id,name', 'bagBrand:id,name')->get();
        return $query;
        // if ($request->ajax()) {
        //     $query = PrintingAndCuttingBagStock::with('group:id,name', 'bagBrand:id,name');
        //     return DataTables::of($query)
        //         ->addIndexColumn()
        //         ->editColumn("group_id", function ($row) {
        //             return $row->group->name;
        //         })
        //         ->editColumn("bag_brand_id", function ($row) {
        //             return $row->bagBrand->name;
        //         })
        //         ->make(true);
        // }
        // return view('admin.bag.printsandcuts.printingAndCuttingBagStock');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PrintingAndCuttingBagStock  $printingAndCuttingBagStock
     * @return \Illuminate\Http\Response
     */
    public function show(PrintingAndCuttingBagStock $printingAndCuttingBagStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrintingAndCuttingBagStock  $printingAndCuttingBagStock
     * @return \Illuminate\Http\Response
     */
    public function edit(PrintingAndCuttingBagStock $printingAndCuttingBagStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrintingAndCuttingBagStock  $printingAndCuttingBagStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrintingAndCuttingBagStock $printingAndCuttingBagStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrintingAndCuttingBagStock  $printingAndCuttingBagStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrintingAndCuttingBagStock $printingAndCuttingBagStock)
    {
        //
    }
}
