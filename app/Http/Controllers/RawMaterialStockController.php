<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\RawMaterialStock;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\Department;
use App\Models\Godam;
use DB;
class RawMaterialStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();

       $rawMaterialStocks= DB::table('raw_material_stocks AS stock')
        ->join('dana_names AS danaName', 'stock.dana_name_id', '=', 'danaName.id')
        ->join('dana_groups AS danaGroup', 'stock.dana_group_id', '=', 'danaGroup.id')
        ->select(
            'danaGroup.name as danaGroup',
            'danaName.name as danaName',
            DB::raw('SUM(stock.quantity) as quantity')
        )
        ->groupBy('danaGroup.name', 'danaName.name')
        ->paginate(35);

        $godams=Godam::where('status','active')->get(['id','name']);
        $danaGroups=DanaGroup::where('status','active')->get(['id','name']);
        $danaNames= DanaName::where('status','active')->get(['id','name']);

        return view('admin.rawMaterialStock.rawMaterialStockIndex',
        compact('settings','rawMaterialStocks','godams','danaGroups','danaNames'));
    }
    public function filterStocks(Request $request){

        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();
        $godams=Godam::where('status','active')->get(['id','name']);
        $danaGroups=DanaGroup::where('status','active')->get(['id','name']);
        $danaNames= DanaName::where('status','active')->get(['id','name']);
        $godam_id = $request->godam_id ?? null ;
        $dana_group_id = $request->danaGroup_id ?? null;
        $dana_name_id = $request->danaName_id??null;

          $rawMaterialStocks= DB::table('raw_material_stocks AS stock')
           ->join('dana_names AS danaName', 'stock.dana_name_id', '=', 'danaName.id')
           ->join('dana_groups AS danaGroup', 'stock.dana_group_id', '=', 'danaGroup.id')
           ->select(
               'danaGroup.name as danaGroup',
               'danaName.name as danaName',
               DB::raw('SUM(stock.quantity) as quantity')
           )
           ->groupBy('danaGroup.name', 'danaName.name');
            if ($godam_id  || $godam_id  != null) {
                $rawMaterialStocks->where('stock.godam_id',$godam_id);
            }
            if($dana_group_id || $dana_group_id !=null){
                $rawMaterialStocks->where('stock.dana_group_id', $dana_group_id);
            }
            if($dana_name_id || $dana_name_id !=null){
                $rawMaterialStocks->where('stock.dana_name_id', $dana_name_id);
            }

            $rawMaterialStocks= $rawMaterialStocks->paginate(35);


        return view('admin.rawMaterialStock.rawMaterialStockIndex',
        compact('settings','rawMaterialStocks','godams','danaGroups','danaNames'));
    }


    Public function danaGroupFilter(){
        $danaGroups=DanaGroup::all();
        return view('admin.rawMaterialStock.danaGroupFilter',compact('danaGroups'));
    }

    public function danaNameFilter(){
        $danaNames=DanaName::all();

        return view('admin.rawMaterialStock.danaNameFilter',compact('danaNames'));
    }
   public function filterAccDanaName($danaName_id)
    {
        $rawMaterialItemStock = RawMaterialStock::with('danaName', 'danaGroup')
            ->where('dana_name_id', $danaName_id)
            ->groupBy('dana_group_id', 'dana_name_id')
            ->select('dana_group_id', 'dana_name_id', DB::raw('SUM(quantity) as quantity'))
            ->get();

        if ($rawMaterialItemStock) {
            return $rawMaterialItemStock;
        } else {
            return false;
        }
    }


    public function filterAccDanaGroup($danaGroup_id){

       // return ($danaGroup_id);
        $rawMaterialItemStocks =RawMaterialStock::with('danaName','danaGroup')
        ->where('dana_group_id',$danaGroup_id)
        ->groupBy('dana_group_id', 'dana_name_id')
        ->select('dana_group_id', 'dana_name_id', DB::raw('SUM(quantity) as quantity'))
        ->get();
        if ($rawMaterialItemStocks) {
            return $rawMaterialItemStocks;
        } else {

            return false;
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
