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

            $dataArray = $this->data($request);
            // dd($plantArray);

            $view = view('admin.tripalProductionReport.ssr.reportview', compact('dataArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.tripalProductionReport.report',compact('nepaliDate'));
    }

    public function data($request)
    {
        $singleSideProd = DB::table('single_tripal_bills')
        ->leftJoin('unlaminatedfabrictripals', 'single_tripal_bills.bill_date', '=', 'unlaminatedfabrictripals.bill_date')
        ->leftJoin('singlesidelaminatedfabrics', 'single_tripal_bills.bill_date', '=', 'singlesidelaminatedfabrics.bill_date')
        ->whereBetween('single_tripal_bills.bill_date', [$request->start_date, $request->end_date])
        ->select('single_tripal_bills.bill_date',
            DB::raw('SUM(single_tripal_bills.total_waste) as singleSide_total_waste_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM unlaminatedfabrictripals WHERE bill_date = single_tripal_bills.bill_date) as single_unlam_net_wt_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM singlesidelaminatedfabrics WHERE bill_date = single_tripal_bills.bill_date) as singleLam_net_wt_sum_singleside'),
            DB::raw('(SELECT SUM(meter) FROM singlesidelaminatedfabrics WHERE bill_date = single_tripal_bills.bill_date) as singleLam_meter_sum')
        )
        ->groupBy('single_tripal_bills.bill_date')
        ->whereNotNull('single_tripal_bills.total_waste')
        ->orWhereNotNull('unlaminatedfabrictripals.net_wt')
        ->orWhereNotNull('singlesidelaminatedfabrics.net_wt')
        ->orWhereNotNull('singlesidelaminatedfabrics.meter')
        ->get()
        ->toArray();
        // dd($singleSideProd);

        $doubleSideProd = DB::table('double_tripal_bills')
        ->leftJoin('single_sideunlaminated_fabrics', 'double_tripal_bills.bill_date', '=', 'single_sideunlaminated_fabrics.bill_date')
        ->leftJoin('double_side_laminated_fabrics', 'double_tripal_bills.bill_date', '=', 'double_side_laminated_fabrics.bill_date')
        ->whereBetween('double_tripal_bills.bill_date', [$request->start_date, $request->end_date])
        ->select('double_tripal_bills.bill_date',
            DB::raw('SUM(double_tripal_bills.total_waste) as doubleSide_total_waste_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM single_sideunlaminated_fabrics WHERE bill_date = double_tripal_bills.bill_date) as double_unlam_net_wt_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM double_side_laminated_fabrics WHERE bill_date = double_tripal_bills.bill_date) as doubleLam_net_wt_sum'),
            DB::raw('(SELECT SUM(meter) FROM double_side_laminated_fabrics WHERE bill_date = double_tripal_bills.bill_date) as doubleLam_meter_sum')
        )
        ->groupBy('double_tripal_bills.bill_date')
        ->whereNotNull('double_tripal_bills.total_waste')
        ->orWhereNotNull('single_sideunlaminated_fabrics.net_wt')
        ->orWhereNotNull('double_side_laminated_fabrics.net_wt')
        ->orWhereNotNull('double_side_laminated_fabrics.meter')
        ->get()
        ->toArray();

        $mergedArray = self::mergeArraysByBillDate1($singleSideProd, $doubleSideProd);
        // dd($mergedArray);

        $tripalProd = DB::table('final_tripal_bills')
        ->leftJoin('tripal_entries', 'final_tripal_bills.bill_date', '=', 'tripal_entries.bill_date')
        ->leftJoin('final_tripals', 'final_tripal_bills.bill_date', '=', 'final_tripals.bill_date')
        ->whereBetween('final_tripal_bills.bill_date', [$request->start_date, $request->end_date])
        ->select('final_tripal_bills.bill_date',
            DB::raw('SUM(final_tripal_bills.total_waste) as triple_total_waste_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM tripal_entries WHERE bill_date = final_tripal_bills.bill_date) as tripal_net_wt_sum'),
            DB::raw('(SELECT SUM(net_wt) FROM final_tripals WHERE bill_date = final_tripal_bills.bill_date) as finalTripal_net_wt_sum'),
            DB::raw('(SELECT SUM(meter) FROM final_tripals WHERE bill_date = final_tripal_bills.bill_date) as finalTripal_meter_sum')
        )
        ->groupBy('final_tripal_bills.bill_date')
        ->whereNotNull('final_tripal_bills.total_waste')
        ->orWhereNotNull('tripal_entries.net_wt')
        ->orWhereNotNull('final_tripals.net_wt')
        ->orWhereNotNull('final_tripals.meter')
        ->get()
        ->toArray();
        $array = self::mergeArraysByBillDate($mergedArray, $tripalProd);

          $resultArray = [];
        foreach ($array as $item) {
            $bill_date = $item['bill_date'];
            $resultArray[$bill_date][] = [
                'bill_date' => $item['bill_date'],
                'singleSide_total_waste_sum' => $item['singleSide_total_waste_sum'],
                'single_unlam_net_wt_sum' => $item['single_unlam_net_wt_sum'],
                'singleLam_net_wt_sum_singleside' => $item['singleLam_net_wt_sum_singleside'],
                'singleLam_meter_sum' => $item['singleLam_meter_sum'],
                'double_unlam_net_wt_sum' => $item['double_unlam_net_wt_sum'],
                'doubleSide_total_waste_sum' => $item['doubleSide_total_waste_sum'],
                'doubleLam_net_wt_sum' => $item['doubleLam_net_wt_sum'],
                'doubleLam_meter_sum' => $item['doubleLam_meter_sum'],
                'triple_total_waste_sum' => $item['triple_total_waste_sum'],
                'tripal_net_wt_sum' => $item['tripal_net_wt_sum'],
                'finalTripal_net_wt_sum' => $item['finalTripal_net_wt_sum'],
                'finalTripal_meter_sum' => $item['finalTripal_meter_sum'],
            ];
        }
    $rowData = [];
        foreach ($resultArray as $date => $data) {
            $rowData[$date]['bill_date'] = $date;
            $rowData[$date]['total_singleSide_total_waste_sum'] = 0;
            $rowData[$date]['total_single_unlam_net_wt_sum'] = 0;
            $rowData[$date]['total_singleLam_net_wt_sum_singleside'] = 0;
            $rowData[$date]['total_singleLam_meter_sum'] = 0;
            $rowData[$date]['total_doubleSide_total_waste_sum'] = 0;
            $rowData[$date]['total_double_unlam_net_wt_sum'] = 0;
            $rowData[$date]['total_doubleLam_net_wt_sum'] = 0;
            $rowData[$date]['total_doubleLam_meter_sum'] = 0;
            $rowData[$date]['total_triple_total_waste_sum'] = 0;
            $rowData[$date]['total_tripal_net_wt_sum'] = 0;
            $rowData[$date]['total_finalTripal_net_wt_sum'] = 0;
            $rowData[$date]['total_finalTripal_meter_sum'] = 0;

            foreach ($data as $item) {

                $rowData[$date]['total_single_unlam_net_wt_sum'] += $item['single_unlam_net_wt_sum'];
                $rowData[$date]['total_singleLam_net_wt_sum_singleside'] += $item['singleLam_net_wt_sum_singleside'];
                $rowData[$date]['total_singleLam_meter_sum'] += $item['singleLam_meter_sum'];
                 $rowData[$date]['total_singleSide_total_waste_sum'] += $item['singleSide_total_waste_sum'];

                $rowData[$date]['total_double_unlam_net_wt_sum'] += $item['double_unlam_net_wt_sum'];
                $rowData[$date]['total_doubleLam_net_wt_sum'] += $item['doubleLam_net_wt_sum'];
                $rowData[$date]['total_doubleLam_meter_sum'] += $item['doubleLam_meter_sum'];
                $rowData[$date]['total_doubleSide_total_waste_sum'] += $item['doubleSide_total_waste_sum'];

                $rowData[$date]['total_tripal_net_wt_sum'] += $item['tripal_net_wt_sum'];
                $rowData[$date]['total_finalTripal_net_wt_sum'] += $item['finalTripal_net_wt_sum'];
                $rowData[$date]['total_finalTripal_meter_sum'] += $item['finalTripal_meter_sum'];
                $rowData[$date]['total_triple_total_waste_sum'] += $item['triple_total_waste_sum'];

            }


        }
//  dd($rowData);
        return $rowData;
    }


public function mergeArraysByBillDate1($arrayOne, $arrayTwo)
{
    $datesArrayOne = array_column($arrayOne, 'bill_date');
    $datesArrayTwo = array_column($arrayTwo, 'bill_date');

    $uniqueDates = array_unique(array_merge($datesArrayOne, $datesArrayTwo));
    $mergedArray = [];

    // Loop through each unique date
    foreach ($uniqueDates as $date) {
        // Find the corresponding data from $arrayOne (or set null if not found)
        $dataOne = array_values(array_filter($arrayOne, function ($item) use ($date) {
            $item = (array) $item;
            return $item['bill_date'] === $date;
        }));

        // Find the corresponding data from $arrayTwo (or set null if not found)
        $dataTwo = array_values(array_filter($arrayTwo, function ($item) use ($date) {
            $item = (array) $item;
            return $item['bill_date'] === $date;
        }));

        $dataOne = (array) ($dataOne[0] ?? ['bill_date' => $date]);
        $dataTwo = (array) ($dataTwo[0] ?? ['bill_date' => $date]);

        // Ensure all keys are present in the result array with null values if not found
        $mergedData = [
            'bill_date' => $date,
            'singleSide_total_waste_sum' => $dataOne['singleSide_total_waste_sum'] ?? null,
            'single_unlam_net_wt_sum' => $dataOne['single_unlam_net_wt_sum'] ?? null,
            'singleLam_net_wt_sum_singleside' => $dataOne['singleLam_net_wt_sum_singleside'] ?? null,
            'singleLam_meter_sum' => $dataOne['singleLam_meter_sum'] ?? null,
             'double_unlam_net_wt_sum' => $dataOne['double_unlam_net_wt_sum'] ?? null,
            'doubleSide_total_waste_sum' => $dataTwo['doubleSide_total_waste_sum'] ?? null,
            'doubleLam_net_wt_sum' => $dataTwo['doubleLam_net_wt_sum'] ?? null,
            'doubleLam_meter_sum' => $dataTwo['doubleLam_meter_sum'] ?? null,
        ];

        $mergedArray[] = $mergedData;
    }

    return $mergedArray;
}
public function mergeArraysByBillDate($arrayOne, $arrayTwo)
{
    $datesArrayOne = array_column($arrayOne, 'bill_date');
    $datesArrayTwo = array_column($arrayTwo, 'bill_date');

    $uniqueDates = array_unique(array_merge($datesArrayOne, $datesArrayTwo));
    $mergedArray = [];

    // Loop through each unique date
    foreach ($uniqueDates as $date) {
        // Find the corresponding data from $arrayOne (or set null if not found)
        $dataOne = array_values(array_filter($arrayOne, function ($item) use ($date) {
            $item = (array) $item;
            return $item['bill_date'] === $date;
        }));

        // Find the corresponding data from $arrayTwo (or set null if not found)
        $dataTwo = array_values(array_filter($arrayTwo, function ($item) use ($date) {
            $item = (array) $item;
            return $item['bill_date'] === $date;
        }));

        $dataOne = (array) ($dataOne[0] ?? ['bill_date' => $date]);
        $dataTwo = (array) ($dataTwo[0] ?? ['bill_date' => $date]);

        // Ensure all keys are present in the result array with null values if not found
        $mergedData = [
            'bill_date' => $date,
            'singleSide_total_waste_sum' => $dataOne['singleSide_total_waste_sum'] ?? null,
            'single_unlam_net_wt_sum' => $dataOne['single_unlam_net_wt_sum'] ?? null,
            'singleLam_net_wt_sum_singleside' => $dataOne['singleLam_net_wt_sum_singleside'] ?? null,
            'singleLam_meter_sum' => $dataOne['singleLam_meter_sum'] ?? null,

            'double_unlam_net_wt_sum' => $dataOne['double_unlam_net_wt_sum'] ?? null,
            'doubleSide_total_waste_sum' => $dataOne['doubleSide_total_waste_sum'] ?? null,
            'doubleLam_net_wt_sum' => $dataOne['doubleLam_net_wt_sum'] ?? null,
            'doubleLam_meter_sum' => $dataOne['doubleLam_meter_sum'] ?? null,
            // Add keys from $tripalProd array
            'triple_total_waste_sum' => $dataTwo['triple_total_waste_sum'] ?? null,
            'tripal_net_wt_sum' => $dataTwo['tripal_net_wt_sum'] ?? null,
            'finalTripal_net_wt_sum' => $dataTwo['finalTripal_net_wt_sum'] ?? null,
            'finalTripal_meter_sum' => $dataTwo['finalTripal_meter_sum'] ?? null,
        ];

        $mergedArray[] = $mergedData;
    }

    return $mergedArray;
}



}

