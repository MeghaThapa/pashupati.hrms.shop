<?php

namespace App\Http\Controllers;

use App\Models\BswSentLamFab;
use Illuminate\Http\Request;
use App\Models\FabricStock;
class BswSentLamFabController extends Controller
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
        $request->validate([
            "gross_wt"=>'required',
            'net_wt'=>'required',
            'roll_no'=>'required',
            'meter'=>'required',
            'gram_wt'=>'required',
            'fabric_id' =>'required',
            'bsw_lam_fab_for_printing_entry_id'=>'required',
        ]);
        try{
        // delete
        $bswSentLamFab = new BswSentLamFab();
        $bswSentLamFab->bsw_lam_fab_for_printing_entry_id =$request->bsw_lam_fab_for_printing_entry_id;
        $bswSentLamFab->fabric_id =$request->fabric_id;
        $bswSentLamFab->roll_no = $request->roll_no;
        $bswSentLamFab->gross_wt = $request->gross_wt;
        $bswSentLamFab->net_wt = $request->net_wt;
        $bswSentLamFab->gram_wt = $request->gram_wt;
        $bswSentLamFab->meter = $request->meter;
        $bswSentLamFab->average = ($request->average)? $request->average:'null';
        $bswSentLamFab->save();

        FabricStock::where('roll_no',$request->roll_no)->delete();

        $bswSentLamFab->load(['fabric']);
        return $bswSentLamFab;
        }catch(Exception $e){};
    }

    public function lamFabData(Request $request){
        $bswSentLamFab=BswSentLamFab::with('fabric:id,name')->where('bsw_lam_fab_for_printing_entry_id',$request->bsw_lam_fab_for_printing_entry_id)->get();

        $totalMeter = $bswSentLamFab->sum('meter');
        $totalNetWt= $bswSentLamFab->sum('net_wt');
       $data = [
        'bswSentLamFab' => $bswSentLamFab,
        'totalMeter' => $totalMeter,
        'totalNetWt' => $totalNetWt,
    ];
    return $data;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BswSentLamFab  $bswSentLamFab
     * @return \Illuminate\Http\Response
     */
    public function show(BswSentLamFab $bswSentLamFab)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BswSentLamFab  $bswSentLamFab
     * @return \Illuminate\Http\Response
     */
    public function edit(BswSentLamFab $bswSentLamFab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BswSentLamFab  $bswSentLamFab
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BswSentLamFab $bswSentLamFab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BswSentLamFab  $bswSentLamFab
     * @return \Illuminate\Http\Response
     */
    public function destroy(BswSentLamFab $bswSentLamFab)
    {
        //
    }
}
