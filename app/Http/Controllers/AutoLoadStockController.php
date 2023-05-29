<?php

namespace App\Http\Controllers;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\AutoLoadItemStock;
use App\Models\Department;
use DB;
class AutoLoadStockController extends Controller
{
    public function index(){
         $helper= new AppHelper();
         $settings= $helper->getGeneralSettigns();
          $autoloadStocks= DB::table('auto_load_item_stocks AS stock')
          ->join('processing_steps AS plantType', 'stock.plant_type_id', '=', 'plantType.id')
          ->join('processing_subcats AS plantName', 'stock.plant_name_id', '=', 'plantName.id')
          ->join('dana_names AS danaName', 'stock.dana_name_id', '=', 'danaName.id')
          ->join('shifts AS shift', 'stock.shift_id', '=', 'shift.id')
          ->select(
              'plantType.name as plantTypeName',
              'plantName.name as plantName',
              'danaName.name as danaName',
              'shift.name as shiftName',
              'stock.quantity',
          )
          ->paginate(35);

        // $autoloadStocksData=AutoLoadItemStock::with('plantType','plantName','danaName','shift')->paginate(15);

         $godams=Department::where('status','active')->get(['id','department']);
         //return $godams;
         return view('admin.autoloadStock.index',compact('settings','autoloadStocks','godams'));
    }
    public function filterAccGodam(Request $request){
            $helper= new AppHelper();
            $settings= $helper->getGeneralSettigns();
            $autoloadStocks= DB::table('auto_load_item_stocks AS stock')
            ->when($request->godam_id != 'all', function ($query) use ($request) {
                return $query->where('stock.from_godam_id', $request->godam_id);
            })
          ->join('processing_steps AS plantType', 'stock.plant_type_id', '=', 'plantType.id')
          ->join('processing_subcats AS plantName', 'stock.plant_name_id', '=', 'plantName.id')
          ->join('dana_names AS danaName', 'stock.dana_name_id', '=', 'danaName.id')
          ->join('shifts AS shift', 'stock.shift_id', '=', 'shift.id')
          ->select(
              'plantType.name as plantTypeName',
              'plantName.name as plantName',
              'danaName.name as danaName',
              'shift.name as shiftName',
              'stock.quantity',
          )
          ->paginate(35);
         $godams=Department::where('status','active')->get(['id','department']);
       // return $autoloadStockDatas;
            return view('admin.autoloadStock.index',compact('settings','autoloadStocks','godams'));
    }
}
