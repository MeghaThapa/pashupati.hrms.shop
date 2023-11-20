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
        if ($request->ajax()) {

            $plantArray = $this->getPlantArray($request);
            // dd($plantArray);

            $view = view('admin.tripalProductionReport.ssr.reportview', compact('plantArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.tripalProductionReport.report');
    }

    private function getPlantArray($request)
    {
        // dd($request);
        // $tapEntryItems = SingleTripalBill::where('status','completed')->get();
        $tapEntryItems = SingleTripalBill::select(
            'single_tripal_bills.bill_date',

        )

            ->join('unlaminatedfabrictripals', function ($join) {
                $join->on('unlaminatedfabrictripals.bill_date', '=', 'single_tripal_bills.bill_date');
                //   DB::raw('SUM(unlaminatedfabrictripals.net_wt) as total_net_wt')
                // DB::raw('SUM(unlaminatedfabrictripals.net_wt) as total_another_column');
                    // ->whereBetween('tape_entry_items.date_column', [$request->start_date, $request->end_date]);
            })
            ->groupBy('single_tripal_bills.bill_date')
            // ->orderBy('tape_entry.tape_entry_date', 'asc')
            ->get();
            // dd($tapEntryItems);

        $resultArray = [];

        foreach ($tapEntryItems as $item) {
            $resultArray[$item->tape_entry_date][] = [
                'date' => $item->tape_entry_date,
                'plantName_id' => $item->plantName_id,
                'toGodam_id' => $item->toGodam_id,
                'tape_qty_in_kg' => $item->total_tape_qty_in_kg,
                'total_loading' => $item->total_loading,
                'total_running' => $item->total_running,
                'total_bypass_wastage' => $item->total_bypass_wastage
            ];
        }
        // $rowData = [];
        // foreach ($resultArray as $date => $data) {
        //     $rowData[$date]['date'] = $date;

        //     $rowData[$date]['godam_one_total_loading'] = 0;
        //     $rowData[$date]['godam_one_total_running'] = 0;
        //     $rowData[$date]['godam_one_total_bypass_wastage'] = 0;

        //     $rowData[$date]['godam_two_total_loading'] = 0;
        //     $rowData[$date]['godam_two_total_running'] = 0;
        //     $rowData[$date]['godam_two_total_bypass_wastage'] = 0;

        //     $rowData[$date]['godam_three_total_loading'] = 0;
        //     $rowData[$date]['godam_three_total_running'] = 0;
        //     $rowData[$date]['godam_three_total_bypass_wastage'] = 0;

        //     foreach ($data as $item) {
        //         $plantName = ProcessingSubcat::whereId($item['plantName_id'])->first()->name;
        //         $rowData[$date][$plantName] = $item['tape_qty_in_kg'];
        //         //for me

        //         if($item['toGodam_id'] == 1){
        //             $rowData[$date]['godam_one_total_loading'] = $rowData[$date]['godam_one_total_loading']+$item['total_loading'];
        //             $rowData[$date]['godam_one_total_running'] = $rowData[$date]['godam_one_total_running']+$item['total_running'];
        //             $rowData[$date]['godam_one_total_bypass_wastage'] = $rowData[$date]['godam_one_total_bypass_wastage'] + $item['total_bypass_wastage'];

        //             $rowData[$date]['psi'] = $item['toGodam_id'];
        //         }elseif($item['toGodam_id'] == 2){
        //             $rowData[$date]['godam_two_total_loading'] = $rowData[$date]['godam_two_total_loading'] + $item['total_loading'];
        //             $rowData[$date]['godam_two_total_running'] = $rowData[$date]['godam_two_total_running'] + $item['total_running'];
        //             $rowData[$date]['godam_two_total_bypass_wastage'] = $rowData[$date]['godam_two_total_bypass_wastage'] + $item['total_bypass_wastage'];
        //             $rowData[$date]['newpsi'] = $item['toGodam_id'];
        //         }elseif($item['toGodam_id'] ==3 ){
        //             $rowData[$date]['godam_three_total_loading'] = $rowData[$date]['godam_three_total_loading'] +  $item['total_loading'];
        //             $rowData[$date]['godam_three_total_running'] = $rowData[$date]['godam_three_total_running'] + $item['total_running'];
        //             $rowData[$date]['godam_three_total_bypass_wastage'] = $rowData[$date]['godam_three_total_bypass_wastage'] + $item['total_bypass_wastage'];
        //             $rowData[$date]['bsw'] = $item['toGodam_id'];
        //         }

        //     }
        // }
        // dd($rowData);
        return $tapEntryItems;
    }
}
