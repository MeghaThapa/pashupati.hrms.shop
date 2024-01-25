<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Godam;
use App\Models\FabricSendAndReceiveLaminatedSent;
use App\Models\ProcessingSubcat;
use App\Models\FabricSendAndReceiveEntry;
use DB;

class LaminationProductionController extends Controller
{
    public function __invoke(Request $request)
    {
        $godams = Godam::get(['id', 'name']);
        if ($request->ajax()) {

            $plantArray = $this->generateReport($request);

            $view = view('admin.laminationProduction.ssr.report', compact('plantArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.laminationProduction.report', compact('godams'));
    }

    private function generateReport($request)
    {
        $fabricLamSentEntries = FabricSendAndReceiveEntry::select(
            'fabric_send_and_receive_entry.godam_id',
            'fabric_send_and_receive_entry.bill_date',
            'fabric_send_and_receive_entry.plantname_id',
            'fabric_send_and_receive_laminated_sent.net_wt',
            'fabric_send_and_receive_laminated_sent.meter',
            'fabric_send_and_receive_laminated_sent.fabid',
            'processing_subcats.name as plantName',
            'fabrics.net_wt as fabNetWt'
        )
            ->leftJoin('fabric_send_and_receive_laminated_sent', 'fabric_send_and_receive_entry.id', '=', 'fabric_send_and_receive_laminated_sent.fsr_entry_id')
            ->leftJoin('processing_subcats', 'fabric_send_and_receive_entry.plantname_id', '=', 'processing_subcats.id')
            ->leftJoin('fabrics', 'fabric_send_and_receive_laminated_sent.fabid', '=', 'fabrics.id')
            ->where('fabric_send_and_receive_entry.bill_date', '>=', $request->start_date)
            ->where('fabric_send_and_receive_entry.bill_date', '<=', $request->end_date)
            ->where('fabric_send_and_receive_entry.godam_id', $request->godam_id)
            ->where('fabric_send_and_receive_entry.status', 'completed')
            ->orderBy('fabric_send_and_receive_entry.bill_date', 'asc')
            ->get()->toArray();

        $fabricEntries =  FabricSendAndReceiveEntry::select(
            'bill_date',
            'godam_id',
            'plantname_id',
            DB::raw('SUM(total_waste) as totalWaste'),
        )
            ->where('bill_date', '>=', $request->start_date)
            ->where('bill_date', '<=', $request->end_date)
            ->where('godam_id', $request->godam_id)
            ->where('status', 'completed')
            ->groupBy(
                'bill_date',
                'godam_id',
                'plantname_id',
            )->get()->toArray();

        $fabricLamSummedData = $this->getSumData($fabricLamSentEntries);
        $mergedArray = $this->mergeSummedDataWithWastageData($fabricLamSummedData, $fabricEntries);
        $dateFormatted = $this->makeDateKey($mergedArray);
        $resultArray = $this->reFormatReport($dateFormatted);
        return $resultArray;
    }

    private function makeDateKey($mergedArray)
    {
        $formattedArray = [];
        foreach ($mergedArray as $item) {
            $billDate = $item['bill_date'];

            if (!isset($formattedArray[$billDate])) {
                $formattedArray[$billDate] = [];
            }

            $formattedArray[$billDate][] = $item;
        }
        return $formattedArray;
    }

    private function mergeSummedDataWithWastageData($fabricLamSummedData, $fabricEntries)
    {
        $tempArray = [];
        foreach ($fabricEntries as $entry) {
            $key = $entry['bill_date'] . '_' . $entry['godam_id'] . '_' . $entry['plantname_id'];
            $tempArray[$key] = $entry;
        }

        // Merge the data into $fabricLamSummedData
        foreach ($fabricLamSummedData as &$summedItem) {
            $key = $summedItem['bill_date'] . '_' . $summedItem['godam_id'] . '_' . $summedItem['plantname_id'];

            if (isset($tempArray[$key])) {
                $mergedData = $tempArray[$key];
                unset($tempArray[$key]); // Remove the used entry to avoid duplication
                $summedItem = array_merge($summedItem, $mergedData);
            }
        }

        // Add remaining entries from $fabricEntries
        $fabricLamSummedData = array_merge($fabricLamSummedData, array_values($tempArray));
        return $fabricLamSummedData;
    }

    private function getSumData($data)
    {
        $summedData = [];

        foreach ($data as $item) {
            $key = $item['godam_id'] . '_' . $item['bill_date'] . '_' . $item['plantname_id'];

            if (!isset($summedData[$key])) {
                $summedData[$key] = [
                    'godam_id' => $item['godam_id'],
                    'bill_date' => $item['bill_date'],
                    'plantname_id' => $item['plantname_id'],
                    'plantName' => $item['plantName'],
                    'fabNetWt' => 0,
                    'net_wt' => 0,
                    'meter' => 0,
                ];
            }

            // Check if the 'fabid' has not been processed for this key
            if (!isset($summedData[$key]['fabid'][$item['fabid']])) {
                $summedData[$key]['fabNetWt'] += floatval($item['fabNetWt']);
                $summedData[$key]['fabid'][$item['fabid']] = true; // Mark 'fabid' as processed

            }
            $summedData[$key]['net_wt'] += (float)$item['net_wt'];
            $summedData[$key]['meter'] += (float)$item['meter'];
        }

        $finalSummedData = array_values($summedData);
        return $finalSummedData;
    }

    private function reFormatReport($resultArray)
    {
        foreach ($resultArray as $date => $data) {
            $rowData[$date]['date'] = $date;

            $rowData[$date]['sundar_total_unlam'] = 0;
            $rowData[$date]['sundar_total_lam'] = 0;
            $rowData[$date]['sundar_total_meter'] = 0;
            $rowData[$date]['sundar_total_waste'] = 0;
            $rowData[$date]['sundar_waste_perc'] = 0;

            $rowData[$date]['jp_total_unlam'] = 0;
            $rowData[$date]['jp_total_lam'] = 0;
            $rowData[$date]['jp_total_meter'] = 0;
            $rowData[$date]['jp_total_waste'] = 0;
            $rowData[$date]['jp_waste_perc'] = 0;

            $rowData[$date]['bsw_total_unlam'] = 0;
            $rowData[$date]['bsw_total_lam'] = 0;
            $rowData[$date]['bsw_total_meter'] = 0;
            $rowData[$date]['bsw_total_waste'] = 0;
            $rowData[$date]['bsw_waste_perc'] = 0;

            foreach ($data as $item) {
                if(!isset($item['plantName'])){
                    dd($item);
                }
                if ($item['plantName'] == 'sunder lam') {
                    $rowData[$date]['sundar_total_unlam'] = $rowData[$date]['sundar_total_unlam'] + $item['fabNetWt'];
                    $rowData[$date]['sundar_total_lam'] = $rowData[$date]['sundar_total_lam'] + $item['net_wt'];
                    $rowData[$date]['sundar_total_meter'] = $rowData[$date]['sundar_total_meter'] + $item['meter'];
                    $rowData[$date]['sundar_total_waste'] = $rowData[$date]['sundar_total_waste'] + $item['totalWaste'];
                    // $rowData[$date]['sundar_waste_perc'] = $rowData[$date]['sundar_waste_perc'] + $item['perc'];
                } elseif ($item['plantName'] == 'jp lam') {
                    $rowData[$date]['jp_total_unlam'] = $rowData[$date]['jp_total_unlam'] + $item['fabNetWt'];
                    $rowData[$date]['jp_total_lam'] = $rowData[$date]['jp_total_lam'] + $item['net_wt'];
                    $rowData[$date]['jp_total_meter'] = $rowData[$date]['jp_total_meter'] + $item['meter'];
                    $rowData[$date]['jp_total_waste'] = $rowData[$date]['jp_total_waste'] + $item['totalWaste'];
                    // $rowData[$date]['jp_waste_perc'] = $rowData[$date]['jp_waste_perc'] + $item['perc'];
                } elseif ($item['plantName'] == 'bsw ecotex') {
                    $rowData[$date]['bsw_total_unlam'] = $rowData[$date]['bsw_total_unlam'] + $item['fabNetWt'];
                    $rowData[$date]['bsw_total_lam'] = $rowData[$date]['bsw_total_lam'] + $item['net_wt'];
                    $rowData[$date]['bsw_total_meter'] = $rowData[$date]['bsw_total_meter'] + $item['meter'];
                    $rowData[$date]['bsw_total_waste'] = $rowData[$date]['bsw_total_waste'] + $item['totalWaste'];
                    // $rowData[$date]['bsw_waste_perc'] = $rowData[$date]['bsw_waste_perc'] + $item['perc'];
                }
            }
        }
        return $rowData;

    }


    private function megha($request)
    {
        $fsritems = FabricSendAndReceiveLaminatedSent::select(
            'fabric_send_and_receive_entry.bill_date',
            'fabric_send_and_receive_entry.godam_id',
            'fabric_send_and_receive_entry.plantname_id',
            'processing_subcats.name as plantName',
            DB::raw('SUM(fabric_send_and_receive_entry.total_waste) as total_ofWaste'),
            DB::raw('SUM(fabric_send_and_receive_laminated_sent.net_wt) as total_lam_net_wt'),
            DB::raw('SUM(fabrics.net_wt) as total_unlam_net_wt'),
            DB::raw('SUM(fabric_send_and_receive_laminated_sent.meter) as total_meter')
        )
            ->join('fabric_send_and_receive_entry', 'fabric_send_and_receive_laminated_sent.fsr_entry_id', '=', 'fabric_send_and_receive_entry.id')
            ->leftjoin('fabrics', 'fabric_send_and_receive_laminated_sent.fabid', '=', 'fabrics.id')
            ->join('processing_subcats', 'fabric_send_and_receive_entry.plantname_id', '=', 'processing_subcats.id')
            ->where('fabric_send_and_receive_entry.bill_date', '>=', $request->start_date)
            ->where('fabric_send_and_receive_entry.bill_date', '<=', $request->end_date)
            ->where('fabric_send_and_receive_entry.godam_id', $request->godam_id)
            ->groupBy(
                'fabric_send_and_receive_entry.bill_date',
                'fabric_send_and_receive_entry.godam_id',
                'fabric_send_and_receive_entry.plantname_id',
                'processing_subcats.name'
            )
            ->orderBy('fabric_send_and_receive_entry.bill_date', 'asc')
            ->get()->toArray();
        // dd($fsritems);

        $fabricentries = FabricSendAndReceiveEntry::select(
            'bill_date',
            DB::raw('SUM(fabric_send_and_receive_entry.total_waste) as total_ofWaste'),
            'plantname_id'
        )->groupBy(
            'bill_date',
            'plantname_id'
        )
            ->where('fabric_send_and_receive_entry.bill_date', '>=', $request->start_date)
            ->where('fabric_send_and_receive_entry.bill_date', '<=', $request->end_date)
            ->where('fabric_send_and_receive_entry.godam_id', $request->godam_id)
            ->get()->toArray();
        // dd($fabricentries);
        //merge array

        $mergedArray = [];
        foreach ($fsritems as $item1) {
            foreach ($fabricentries as $item2) {
                if ($item1["bill_date"] === $item2["bill_date"] && $item1["plantname_id"] === $item2["plantname_id"]) {
                    // Match found, merge the data
                    $mergedArray[] = array_merge($item1, $item2);
                }
            }
        }
        $resultArray = [];
        foreach ($mergedArray as $item) {
            $bill_date = $item['bill_date'];
            $resultArray[$bill_date][] = [
                'date' => $item['bill_date'],
                'godam_id' => $item['godam_id'],
                'plant_name' => $item['plantName'],
                'total_ofWaste' => $item['total_ofWaste'],
                'total_lam_netWt' => $item['total_lam_net_wt'],
                'total_unlam_netWt' => $item['total_unlam_net_wt'],
                'perc' =>  $item['total_lam_net_wt'] > 0 ? $item['total_ofWaste'] / $item['total_lam_net_wt'] * 100 : 0,
                'total_meter' => $item['total_meter'],
            ];
        }
        $rowData = [];
        foreach ($resultArray as $date => $data) {
            $rowData[$date]['date'] = $date;

            $rowData[$date]['sundar_total_unlam'] = 0;
            $rowData[$date]['sundar_total_lam'] = 0;
            $rowData[$date]['sundar_total_meter'] = 0;
            $rowData[$date]['sundar_total_waste'] = 0;
            $rowData[$date]['sundar_waste_perc'] = 0;

            $rowData[$date]['jp_total_unlam'] = 0;
            $rowData[$date]['jp_total_lam'] = 0;
            $rowData[$date]['jp_total_meter'] = 0;
            $rowData[$date]['jp_total_waste'] = 0;
            $rowData[$date]['jp_waste_perc'] = 0;

            $rowData[$date]['bsw_total_unlam'] = 0;
            $rowData[$date]['bsw_total_lam'] = 0;
            $rowData[$date]['bsw_total_meter'] = 0;
            $rowData[$date]['bsw_total_waste'] = 0;
            $rowData[$date]['bsw_waste_perc'] = 0;

            foreach ($data as $item) {
                if ($item['plant_name'] == 'sunder lam') {
                    $rowData[$date]['sundar_total_unlam'] = $rowData[$date]['sundar_total_unlam'] + $item['total_unlam_netWt'];
                    $rowData[$date]['sundar_total_lam'] = $rowData[$date]['sundar_total_lam'] + $item['total_lam_netWt'];
                    $rowData[$date]['sundar_total_meter'] = $rowData[$date]['sundar_total_meter'] + $item['total_meter'];
                    $rowData[$date]['sundar_total_waste'] = $rowData[$date]['sundar_total_waste'] + $item['total_ofWaste'];
                    $rowData[$date]['sundar_waste_perc'] = $rowData[$date]['sundar_waste_perc'] + $item['perc'];
                } elseif ($item['plant_name'] == 'jp lam') {
                    $rowData[$date]['jp_total_unlam'] = $rowData[$date]['jp_total_unlam'] + $item['total_unlam_netWt'];
                    $rowData[$date]['jp_total_lam'] = $rowData[$date]['jp_total_lam'] + $item['total_lam_netWt'];
                    $rowData[$date]['jp_total_meter'] = $rowData[$date]['jp_total_meter'] + $item['total_meter'];
                    $rowData[$date]['jp_total_waste'] = $rowData[$date]['jp_total_waste'] + $item['total_ofWaste'];
                    $rowData[$date]['jp_waste_perc'] = $rowData[$date]['jp_waste_perc'] + $item['perc'];
                } elseif ($item['plant_name'] == 'bsw ecotex') {
                    $rowData[$date]['bsw_total_unlam'] = $rowData[$date]['bsw_total_unlam'] + $item['total_unlam_netWt'];
                    $rowData[$date]['bsw_total_lam'] = $rowData[$date]['bsw_total_lam'] + $item['total_lam_netWt'];
                    $rowData[$date]['bsw_total_meter'] = $rowData[$date]['bsw_total_meter'] + $item['total_meter'];
                    $rowData[$date]['bsw_total_waste'] = $rowData[$date]['bsw_total_waste'] + $item['total_ofWaste'];
                    $rowData[$date]['bsw_waste_perc'] = $rowData[$date]['bsw_waste_perc'] + $item['perc'];
                }
            }
        }
        // dd($rowData);
        return $rowData;
    }
}
