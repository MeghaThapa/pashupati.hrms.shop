<?php

namespace App\Http\Controllers\Tripal\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Godam;
use App\Models\Unlaminatedfabrictripal;
use Carbon\Carbon;

use Carbon\CarbonPeriod;
use Yajra\DataTables\DataTables;
use App\Services\NepaliConverter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SingleTripalReportController extends Controller
{

    protected $neDate;

    public function __construct(NepaliConverter $neDate)
    {
        $this->neDate = $neDate;
    }

    public function singletripalReport()
    {
        $godams = Godam::all();
        return view('admin.report.singletripalreport.index', compact('godams'));
    }

    public function generateSingleTripalView(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date']);
        }
        $reportData = [];
        $nepaliDates = [];
        $nepaliDates = $this->getDateRangeNepali($request->start_date, $request->end_date);

        foreach ($nepaliDates as $nepaliDate) {

            $fabricGodams = Unlaminatedfabrictripal::where('bill_date', $nepaliDate);

            if ($request->godam_id) {
                $fabricGodams = $fabricGodams->where('department_id', $request->godam_id);
            }
            $fabricGodams = $fabricGodams->get();

            if (!$fabricGodams->isEmpty()) {
                $fabricView = view('admin.report.singletripalreport.singletripal_transfer_datewise', compact('fabricGodams', 'nepaliDate'))->render();
                array_push($reportData, $fabricView);
            }
        }

       

        // Summary Part Code

        $nepaliStartDate = $nepaliDates[0];
        $nepaliEndDate = end($nepaliDates);

        // dd($nepaliStartDate,$nepaliEndDate);

        $summaryFabrics = Unlaminatedfabrictripal::select(
            'fabrics.name',
            DB::raw('COUNT(fabrics.name) as roll_count'),
            DB::raw('SUM(fabrics.gross_wt) as gross_wt'),
            DB::raw('SUM(fabrics.net_wt) as net_wt'),
            DB::raw('SUM(fabrics.meter) as meter')
        )
            ->join('fabrics', 'unlaminatedfabrictripals.fabric_id', '=', 'fabrics.id')
            ->groupBy('fabrics.name');
            // dd($summaryFabrics->get());

        if ($request->start_date && $request->end_date) {
            $summaryFabrics = $summaryFabrics->where('unlaminatedfabrictripals.bill_date', '>=', $request->start_date)->where('unlaminatedfabrictripals.bill_date', '<=', $request->end_date);
        }

        if ($request->godam_id) {
            $summaryFabrics = $summaryFabrics->where('unlaminatedfabrictripals.department_id', $request->godam_id);
        }

        $summaryFabrics = $summaryFabrics->get();
        $summaryView = view('admin.report.singletripalreport.summary', compact('summaryFabrics', 'nepaliStartDate', 'nepaliEndDate'))->render();
        array_push($reportData, $summaryView);

        return response(['status' => true, 'data' => $reportData]);
    }

    private function getDateRangeNepali($npStartDate, $npEndDate)
    {
        $startEngDate = $this->getEngDate($npStartDate);
        $endEngDate = $this->getEngDate($npEndDate);

        $npDates = [];

        $engDates = $this->getEngDateRange($startEngDate, $endEngDate);
        foreach ($engDates as $engDate) {
            array_push($npDates, $this->getNpDate($engDate));
        }

        return $npDates;
    }

    private function getEngDateRange($startDate, $endDate)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

        $dateRange = CarbonPeriod::create($startDate, $endDate);

        $dates = array_map(fn ($date) => $date->format('Y-m-d'), iterator_to_array($dateRange));

        return $dates;
    }

    private function getEngDate($npDate)
    {
        $explodedStartDate = explode('-', $npDate);
        $date = $this->neDate->nep_to_eng($explodedStartDate[0], $explodedStartDate[1], $explodedStartDate[2]);

        if ($date['month'] < 10) {
            $month = '0' . $date['month'];
        } else {
            $month = $date['month'];
        }

        return $date['year'] . '-' . $month . '-' . $date['date'];
    }

    private function getNpDate($engDate)
    {
        $explodedStartDate = explode('-', $engDate);
        return $this->neDate->eng_to_nep($explodedStartDate[0], $explodedStartDate[1], $explodedStartDate[2]);
    }

}
