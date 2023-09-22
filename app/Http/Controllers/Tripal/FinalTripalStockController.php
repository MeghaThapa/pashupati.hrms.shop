<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FinalTripalStock;
use App\Models\Godam;
use Yajra\DataTables\DataTables;

class FinalTripalStockController extends Controller
{
    public function index()
    {
       $helper = new AppHelper();
       $settings = $helper->getGeneralSettigns();
       $finaltripal = FinalTripalStock::paginate(50);
       $finaltripalname = FinalTripalStock::get()->unique('name')->values()->all();
       $sum = FinalTripalStock::sum('net_wt');
       $godams = Godam::get();

        return view('admin.tripal_stock.finaltripalstock.index',
        compact('settings','finaltripal','finaltripalname','sum','godams'));
    }

    public function getFilterList(Request $request)
    {
        if ($request->ajax()) {
            // dd($request);
            $query = FinalTripalStock::where('status_type','active');

            if ($request->tripalName) {
                $getname = FinalTripalStock::where('id',$request->tripalName)->value('name');
                // dd($getname);
                // $end_date = $request->input('end_date');
                $query->where('name', $getname);
            }

            if ($request->godam_id) {
                $query->where('department_id', (int)$request->godam_id);
            }

            $totalNetweightSum = $query->sum('net_wt');

            $data = DataTables::of($query)
                ->addIndexColumn()

                ->toArray();

            $data['total_netweight_sum'] = $totalNetweightSum;

            return response()->json($data);
        }

        return view('admin.tripal_stock.finaltripalstock.index');
    }

      public function filterStock(Request $request){

        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();
        $name = $request->name ?? null ;

        $finaltripalname = FinalTripalStock::get()->unique('name')->values()->all();

        $sum = 0;

        if($name || $name !=null){
                $finaltripal = FinalTripalStock::where('name', 'LIKE', '%'.$request->name.'%')->paginate(35);
                $sum = $finaltripal->sum('net_wt');
        }

        return view('admin.tripal_stock.finaltripalstock.index-ajax',
        compact('settings','finaltripal','finaltripalname','sum'));
    }
}
