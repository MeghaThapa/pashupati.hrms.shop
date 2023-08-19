<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FinalTripalStock;

class FinalTripalStockController extends Controller
{
    public function index()
    {
       $helper = new AppHelper();
       $settings = $helper->getGeneralSettigns();
       $finaltripal = FinalTripalStock::paginate(50);
       $finaltripalname = FinalTripalStock::get()->unique('name')->values()->all();
       $sum = 0 ;

        return view('admin.tripal_stock.finaltripalstock.index',
        compact('settings','finaltripal','finaltripalname','sum'));
    }

      public function filterStock(Request $request){

        // dd('hey');
         // dd($request);
        
       

        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();
        $name = $request->name ?? null ;

        $finaltripalname = FinalTripalStock::get()->unique('name')->values()->all();

        $sum = 0;
        // dd($request);


            if($name || $name !=null){
                $finaltripal = FinalTripalStock::where('name', 'LIKE', '%'.$request->name.'%')->paginate(35);
                $sum = $finaltripal->sum('net_wt');
            }

           

            // $fabric_stock= $finaltripal->orderBy('roll_no')->paginate(35);


        return view('admin.tripal_stock.finaltripalstock.index-ajax',
        compact('settings','finaltripal','finaltripalname','sum'));
    }
}
