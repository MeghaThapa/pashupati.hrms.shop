<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FabricDetail;
use App\Models\TapeEntryItemModel;
use App\Models\ProcessingSubcat;
use App\Models\SingleTripalBill;

class WastageProductionReportController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {

            $plantArray = $this->getPlantArray($request);
            // dd($plantArray);

            $view = view('admin.wastageProductionReport.ssr.reportview', compact('plantArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.wastageProductionReport.report');
    }

    private function getPlantArray($request)
    {
     $sumByPlantType = TapeEntryItemModel::join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
    ->select(DB::raw('SUM(tape_qty_in_kg) as total_qty'), 'plantType_id','tape_entry.tape_entry_date')
    ->groupBy('plantType_id', 'tape_entry.tape_entry_date')
    ->whereBetween('tape_entry.tape_entry_date', [$request->start_date, $request->end_date])
    ->get()
    ->toArray();


    dd($sumByPlantType);


        $fabricDetailProduction = FabricDetail::
            select('fabric_details.bill_date',
            DB::raw('(SELECT SUM(total_wastage) FROM fabric_details WHERE godam_id = 1 AND bill_date = fabric_details.bill_date) as psigodam'),
            DB::raw('(SELECT SUM(total_wastage) FROM fabric_details WHERE godam_id = 2 AND bill_date = fabric_details.bill_date) as newpsigodam'),
            DB::raw('(SELECT SUM(total_wastage) FROM fabric_details WHERE godam_id = 3 AND bill_date = fabric_details.bill_date) as bswgodam')
        )
        ->groupBy('fabric_details.bill_date')
        // ->take(5)
        ->get()
        ->toArray();
        // dd($tapePlantProduction,$fabricDetailProduction);

        $LaminationPlantProduction = DB::table('fabric_send_and_receive_entry')
        // ->leftJoin('fabric_send_and_receive_entry', 'tape_entry.id', '=', 'fabric_send_and_receive_entry.tape_entry_id')
        // ->whereBetween('tape_entry.tape_entry_date', [$request->start_date, $request->end_date])
        ->select('fabric_send_and_receive_entry.bill_date_np',

            DB::raw('(SELECT SUM(total_waste) FROM fabric_send_and_receive_entry WHERE planttype_id = 2) as total_fsr1'),
            DB::raw('(SELECT SUM(total_waste) FROM fabric_send_and_receive_entry WHERE planttype_id = 5) as total_fsr2'),

        )
        ->groupBy('fabric_send_and_receive_entry.bill_date_np')
        ->get()
        ->toArray();

        // $nonWovenProduction = DB::table('nonwoven_bills')
        // // ->whereBetween('tape_entry.tape_entry_date', [$request->start_date, $request->end_date])
        // ->select('nonwoven_bills.bill_date',

        //     DB::raw('(SELECT SUM(total_waste) FROM nonwoven_bills WHERE planttype_id = 2) as total_fsr1'),
        //     DB::raw('(SELECT SUM(total_waste) FROM nonwoven_bills WHERE planttype_id = 5) as total_fsr2'),

        // )
        // ->groupBy('nonwoven_bills.bill_date')
        // ->get()
        // ->toArray();

        // dd($nonWovenProduction);
        // $mergedArray = self::mergeArraysByBillDate1($singleSideProd, $doubleSideProd);

        // $resultArray = [];

        // foreach ($tapEntryItems as $item) {
        //     $resultArray[$item->tape_entry_date][] = [
        //         'date' => $item->tape_entry_date,
        //         'plantName_id' => $item->plantName_id,
        //         'toGodam_id' => $item->toGodam_id,
        //         'tape_qty_in_kg' => $item->total_tape_qty_in_kg,
        //         'total_loading' => $item->total_loading,
        //         'total_running' => $item->total_running,
        //         'total_bypass_wastage' => $item->total_bypass_wastage
        //     ];
        // }
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
        return $tapePlantProduction;
    }
}
