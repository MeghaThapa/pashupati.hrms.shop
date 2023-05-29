<?php

namespace App\Http\Controllers;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\AutoLoadItemStock;
use App\Models\Department;
class AutoLoadStockController extends Controller
{
    public function index(){
         $helper= new AppHelper();
         $settings= $helper->getGeneralSettigns();
         $autoloadStockDatas=null;
         $autoloadStocks=AutoLoadItemStock::with('plantType','plantName','danaName','shift')->get();
         $godams=Department::where('status','active')->get();
         //return $godams;
         return view('admin.autoloadStock.index',compact('settings','autoloadStocks','godams','autoloadStockDatas'));
    }
    public function filterAccGodam(Request $request){
            $helper= new AppHelper();
            $settings= $helper->getGeneralSettigns();
            $autoloadStockDatas=AutoLoadItemStock::where('from_godam_id',$request->godam_id)->get();
            $autoloadStocks=null;
            $godams=Department::where('status','active')->get();
       // return $autoloadStockDatas;
            return view('admin.autoloadStock.index',compact('settings','autoloadStockDatas','godams','autoloadStocks'));
    }
}
