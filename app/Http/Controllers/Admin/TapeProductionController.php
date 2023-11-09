<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProcessingSubcat;
use App\Models\TapeEntryItemModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TapeProductionController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {

            $plantArray = $this->getPlantArray($request);

            $view = view('admin.TapeEntry.ssr.reportview', compact('plantArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.TapeEntry.report');
    }

    private function getPlantArray($request)
    {
        $tapEntryItems = TapeEntryItemModel::select(
            'tape_entry.tape_entry_date',
            'tape_entry_items.plantName_id',
            'tape_entry_items.toGodam_id',
            DB::raw('SUM(tape_qty_in_kg) as total_tape_qty_in_kg'),
            DB::raw('SUM(loading) as total_loading'),
            DB::raw('SUM(running) as total_running'),
            DB::raw('SUM(bypass_wast) as total_bypass_wastage'),
        )
            ->join('tape_entry', 'tape_entry_items.tape_entry_id', 'tape_entry.id')
            ->where('tape_entry.tape_entry_date', '>=', $request->start_date)
            ->where('tape_entry.tape_entry_date', '<=', $request->end_date)
            ->where('tape_entry.status', 'created')
            ->groupBy('tape_entry.tape_entry_date', 'tape_entry_items.plantName_id', 'tape_entry_items.plantName_id','tape_entry_items.toGodam_id')
            ->orderBy('tape_entry.tape_entry_date', 'asc')
            ->get();    

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
        $rowData = [];
        foreach ($resultArray as $date => $data) {
            $rowData[$date]['date'] = $date;

            $rowData[$date]['godam_one_total_loading'] = 0;
            $rowData[$date]['godam_one_total_running'] = 0;
            $rowData[$date]['godam_one_total_bypass_wastage'] = 0;

            $rowData[$date]['godam_two_total_loading'] = 0;
            $rowData[$date]['godam_two_total_running'] = 0;
            $rowData[$date]['godam_two_total_bypass_wastage'] = 0;

            $rowData[$date]['godam_three_total_loading'] = 0;
            $rowData[$date]['godam_three_total_running'] = 0;
            $rowData[$date]['godam_three_total_bypass_wastage'] = 0;
         
            foreach ($data as $item) {
                $plantName = ProcessingSubcat::whereId($item['plantName_id'])->first()->name;
                $rowData[$date][$plantName] = $item['tape_qty_in_kg'];
                //for me 
               
                if($item['toGodam_id'] == 1){
                    $rowData[$date]['godam_one_total_loading'] = $rowData[$date]['godam_one_total_loading']+$item['total_loading'];
                    $rowData[$date]['godam_one_total_running'] = $rowData[$date]['godam_one_total_running']+$item['total_running'];
                    $rowData[$date]['godam_one_total_bypass_wastage'] = $rowData[$date]['godam_one_total_bypass_wastage'] + $item['total_bypass_wastage'];

                    $rowData[$date]['psi'] = $item['toGodam_id'];
                }elseif($item['toGodam_id'] == 2){
                    $rowData[$date]['godam_two_total_loading'] = $rowData[$date]['godam_two_total_loading'] + $item['total_loading'];
                    $rowData[$date]['godam_two_total_running'] = $rowData[$date]['godam_two_total_running'] + $item['total_running'];
                    $rowData[$date]['godam_two_total_bypass_wastage'] = $rowData[$date]['godam_two_total_bypass_wastage'] + $item['total_bypass_wastage'];
                    $rowData[$date]['newpsi'] = $item['toGodam_id'];
                }elseif($item['toGodam_id'] ==3 ){
                    $rowData[$date]['godam_three_total_loading'] = $rowData[$date]['godam_three_total_loading'] +  $item['total_loading'];
                    $rowData[$date]['godam_three_total_running'] = $rowData[$date]['godam_three_total_running'] + $item['total_running'];
                    $rowData[$date]['godam_three_total_bypass_wastage'] = $rowData[$date]['godam_three_total_bypass_wastage'] + $item['total_bypass_wastage'];
                    $rowData[$date]['bsw'] = $item['toGodam_id'];
                }

            }
        }
        dd($rowData);
        return $rowData;
    }
}
