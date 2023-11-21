<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TapeEntryItemModel;
use App\Models\ProcessingSubcat;
use App\Models\SingleTripalBill;

class TripalProductionReportController extends Controller
{
    public function __invoke(Request $request)
    {
         $nepaliDate = getNepaliDate(date('Y-m-d'));

        if ($request->ajax()) {

            $plantArray = $this->data($request);
            // dd($plantArray);

            $view = view('admin.tripalProductionReport.ssr.reportview', compact('plantArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.tripalProductionReport.report',compact('nepaliDate'));
    }

    private function data($request)
    {
        $singleSideProd = DB::table('single_tripal_bills')
        ->leftJoin('unlaminatedfabrictripals', 'single_tripal_bills.bill_date', '=', 'unlaminatedfabrictripals.bill_date')
        ->leftJoin('singlesidelaminatedfabrics', 'single_tripal_bills.bill_date', '=', 'singlesidelaminatedfabrics.bill_date')
        ->whereBetween('single_tripal_bills.bill_date', [$request->start_date, $request->end_date])
        ->select('single_tripal_bills.bill_date',
            DB::raw('SUM(single_tripal_bills.total_waste) as total_waste_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM unlaminatedfabrictripals WHERE bill_date = single_tripal_bills.bill_date) as unlam_net_wt_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM singlesidelaminatedfabrics WHERE bill_date = single_tripal_bills.bill_date) as singleLam_net_wt_sum_singleside'),
            DB::raw('(SELECT SUM(meter) FROM singlesidelaminatedfabrics WHERE bill_date = single_tripal_bills.bill_date) as singleLam_meter_sum')
        )
        ->groupBy('single_tripal_bills.bill_date')
        ->whereNotNull('single_tripal_bills.total_waste')
        ->orWhereNotNull('unlaminatedfabrictripals.net_wt')
        ->orWhereNotNull('singlesidelaminatedfabrics.net_wt')
        ->orWhereNotNull('singlesidelaminatedfabrics.meter')
        ->get();
        dd($singleSideProd);

        return $tapEntryItems;
    }
}
        // $singleSideProd = DB::table('single_tripal_bills')
        // ->leftJoin('unlaminatedfabrictripals', 'single_tripal_bills.bill_date', '=', 'unlaminatedfabrictripals.bill_date')
        // ->leftJoin('singlesidelaminatedfabrics', 'single_tripal_bills.bill_date', '=', 'singlesidelaminatedfabrics.bill_date')
        // ->whereBetween('single_tripal_bills.bill_date', [$request->start_date, $request->end_date])
        // ->select('single_tripal_bills.bill_date',
        //     DB::raw('SUM(single_tripal_bills.total_waste) as total_waste_sum'),
        //     DB::raw('SUM(unlaminatedfabrictripals.net_wt) as unlam_net_wt_sum'),
        //     DB::raw('SUM(singlesidelaminatedfabrics.net_wt) as singleLam_net_wt_sum_singleside'),
        //     DB::raw('SUM(singlesidelaminatedfabrics.meter) as singleLam_meter_sum')
        // )
        // ->groupBy('single_tripal_bills.bill_date')
        // ->whereNotNull('single_tripal_bills.total_waste')
        // ->orWhereNotNull('unlaminatedfabrictripals.net_wt')
        // ->orWhereNotNull('singlesidelaminatedfabrics.net_wt')
        // ->orWhereNotNull('singlesidelaminatedfabrics.meter')
        // ->get();
