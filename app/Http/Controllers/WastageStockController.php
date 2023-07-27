<?php

namespace App\Http\Controllers;
use App\Helpers\AppHelper;
use App\Models\WasteStock;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WastageStockController extends Controller
{
    public function index(){
        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();
        return view('admin.wastageStock.wastageStockIndex',compact('settings'));
    }
    public function yajraDatatables(){
         $wastageStocks=WasteStock::with(['godam:id,name','wastage:id,name'])
        ->get(['godam_id','waste_id','quantity_in_kg']);
         return DataTables::of($wastageStocks)
          ->addIndexColumn()
            ->make(true);
    }
}
