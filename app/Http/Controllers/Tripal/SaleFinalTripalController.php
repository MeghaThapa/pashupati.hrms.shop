<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\Shift;
use App\Models\Supplier;
use App\Models\FinalTripalStock;
use App\Helpers\AppHelper;
use Carbon\Carbon;

class SaleFinalTripalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bill_no = AppHelper::getFinalTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $partyname = Supplier::where('status',1)->get();
        return view('admin.tripal.salefinaltripal.index',compact('bill_no','bill_date','fabrics','partyname'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSaleFinalTripalStockList(Request $request)
    {
        $fabric_id = $request->fabric_id;
        $getName = FinalTripalStock::where('id',$fabric_id)->value('name');
        $fabrics = FinalTripalStock::where('name',$getName)->get();

        return response(['response'=>$fabrics]);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
