<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\FabricDetail;

class FabricProductionReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {

            $plantArray = $this->generateReportData($request);

            $view = view('admin.fabric.ssr.production_reportview', compact('plantArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.fabric.production_report');
    }

    private function generateReportData($request)
    {
        $fabricItems = FabricDetail::select(
            'bill_date_en',
            'godam_id',
            DB::raw('SUM(pipe_cutting) as total_pipe_cutting'),
            DB::raw('SUM(bd_wastage) as total_bd_wastage'),
            DB::raw('SUM(other_wastage) as total_other_wastage'),
            DB::raw('SUM(total_netweight) as total_netwt'),
            DB::raw('SUM(run_loom) as total_run_loom'),
            DB::raw('SUM(total_meter) as grand_total_meter'),
        )
            ->where('bill_date_en', '>=', $request->start_date)
            ->where('bill_date_en', '<=', $request->end_date)
            ->groupBy('bill_date_en', 'godam_id')->get();
        $resultArray = [];

        foreach ($fabricItems as $item) {
            $resultArray[$item->bill_date_en][] = [
                'date' => $item->bill_date_en,
                'godam_id' => $item->godam_id,
                'total_pipe_cutting' => $item->total_pipe_cutting,
                'total_bd_wastage' => $item->total_bd_wastage,
                'total_other_wastage' => $item->total_other_wastage,
                'total_netwt' => $item->total_netwt,
                'total_run_loom' => $item->total_run_loom,
                'grand_total_meter' => $item->grand_total_meter,
            ];
        }

        $rowData = [];

        foreach ($resultArray as $date => $data) {
            $rowData[$date]['date'] = $date;

            $rowData[$date]['godam_one_total_pipe_cutting'] = 0;
            $rowData[$date]['godam_one_total_bd_wastage'] = 0;
            $rowData[$date]['godam_one_total_other_wastage'] = 0;
            $rowData[$date]['godam_one_total_netwt'] = 0;
            $rowData[$date]['godam_one_run_loom'] = 0;
            $rowData[$date]['godam_one_grand_total_meter'] = 0;

            $rowData[$date]['godam_two_total_pipe_cutting'] = 0;
            $rowData[$date]['godam_two_total_bd_wastage'] = 0;
            $rowData[$date]['godam_two_total_other_wastage'] = 0;
            $rowData[$date]['godam_two_total_netwt'] = 0;
            $rowData[$date]['godam_two_run_loom'] = 0;
            $rowData[$date]['godam_two_grand_total_meter'] = 0;

            $rowData[$date]['godam_three_total_pipe_cutting'] = 0;
            $rowData[$date]['godam_three_total_bd_wastage'] = 0;
            $rowData[$date]['godam_three_total_other_wastage'] = 0;
            $rowData[$date]['godam_three_total_netwt'] = 0;
            $rowData[$date]['godam_three_run_loom'] = 0;
            $rowData[$date]['godam_three_grand_total_meter'] = 0;

            foreach ($data as $item) {

                if($item['godam_id'] == 1){
                    $rowData[$date]['godam_one_total_pipe_cutting'] = $rowData[$date]['godam_one_total_pipe_cutting']+$item['total_pipe_cutting'];
                    $rowData[$date]['godam_one_total_bd_wastage'] = $rowData[$date]['godam_one_total_bd_wastage']+$item['total_bd_wastage'];
                    $rowData[$date]['godam_one_total_other_wastage'] = $rowData[$date]['godam_one_total_other_wastage'] + $item['total_other_wastage'];
                    $rowData[$date]['godam_one_total_netwt'] = $rowData[$date]['godam_one_total_netwt'] + $item['total_netwt'];
                    $rowData[$date]['godam_one_run_loom'] = $rowData[$date]['godam_one_run_loom'] + $item['total_run_loom'];
                    $rowData[$date]['godam_one_grand_total_meter'] = $rowData[$date]['godam_one_grand_total_meter'] + $item['grand_total_meter'];

                    $rowData[$date]['psi'] = $item['godam_id'];
                }elseif($item['godam_id'] == 2){
                    $rowData[$date]['godam_two_total_pipe_cutting'] = $rowData[$date]['godam_two_total_pipe_cutting']+$item['total_pipe_cutting'];
                    $rowData[$date]['godam_two_total_bd_wastage'] = $rowData[$date]['godam_two_total_bd_wastage']+$item['total_bd_wastage'];
                    $rowData[$date]['godam_two_total_other_wastage'] = $rowData[$date]['godam_two_total_other_wastage'] + $item['total_other_wastage'];
                    $rowData[$date]['godam_two_total_netwt'] = $rowData[$date]['godam_two_total_netwt'] + $item['total_netwt'];
                    $rowData[$date]['godam_two_run_loom'] = $rowData[$date]['godam_two_run_loom'] + $item['total_run_loom'];
                    $rowData[$date]['godam_two_grand_total_meter'] = $rowData[$date]['godam_two_grand_total_meter'] + $item['grand_total_meter'];

                    $rowData[$date]['newpsi'] = $item['godam_id'];
                }elseif($item['godam_id'] ==3 ){
                    $rowData[$date]['godam_three_total_pipe_cutting'] = $rowData[$date]['godam_three_total_pipe_cutting']+$item['total_pipe_cutting'];
                    $rowData[$date]['godam_three_total_bd_wastage'] = $rowData[$date]['godam_three_total_bd_wastage']+$item['total_bd_wastage'];
                    $rowData[$date]['godam_three_total_other_wastage'] = $rowData[$date]['godam_three_total_other_wastage'] + $item['total_other_wastage'];
                    $rowData[$date]['godam_three_total_netwt'] = $rowData[$date]['godam_three_total_netwt'] + $item['total_netwt'];
                    $rowData[$date]['godam_three_run_loom'] = $rowData[$date]['godam_three_run_loom'] + $item['total_run_loom'];
                    $rowData[$date]['godam_three_grand_total_meter'] = $rowData[$date]['godam_three_grand_total_meter'] + $item['grand_total_meter'];

                    $rowData[$date]['bsw'] = $item['godam_id'];
                }

            }
        }
        return $rowData;
    }
}
