<?php

namespace App\Http\Controllers;

use App\Models\Godam;
use App\Models\BagFabricReceiveItemSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use DB;

class PrintingAndFinishingController extends Controller
{
    public function __invoke(Request $request)
    {
        $godams = Godam::get(['id', 'name']);
        if ($request->ajax()) {

            $datas = $this->data($request);

            $view = view('admin.printingAndFinishing.ssr.report', compact('datas', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.printingAndFinishing.report', compact('godams'));
    }

    private function data($request)
    {

        //query1
        $bagFabricSentDatas = DB::table('bag_fabric_receive_item_sent')
        ->join('bag_fabric_entry', 'bag_fabric_receive_item_sent.fabric_bag_entry_id', '=', 'bag_fabric_entry.id')
        ->whereBetween('bag_fabric_entry.receipt_date', [$request->start_date, $request->end_date])
            ->select('bag_fabric_entry.receipt_date', DB::raw('SUM(bag_fabric_receive_item_sent.net_wt) as total_net_wt'))
            ->groupBy('bag_fabric_entry.receipt_date')
            ->get()
            ->toArray();
        // dd($bagFabricSentDatas);

        //query1
        $printAndCuttedRoleDatas = DB::table('printed_and_cutted_rolls_entry')
        ->join('printing_and_cutting_bag_items', 'printed_and_cutted_rolls_entry.id', '=', 'printing_and_cutting_bag_items.printAndCutEntry_id')
        ->leftJoin('prints_and_cuts_dana_consumptions', 'printed_and_cutted_rolls_entry.id', '=', 'prints_and_cuts_dana_consumptions.printCutEntry_id')
        ->leftJoin('dana_names', 'prints_and_cuts_dana_consumptions.dana_name_id', '=', 'dana_names.id')
        ->whereBetween('printed_and_cutted_rolls_entry.date', [$request->start_date, $request->end_date])
            ->select(
                'printed_and_cutted_rolls_entry.date',
                DB::raw('SUM(printing_and_cutting_bag_items.quantity_piece) as total_quantity_piece'),
                DB::raw('SUM(printing_and_cutting_bag_items.wastage) as total_wastage'),
                'dana_names.name as dana_name',
                DB::raw('SUM(prints_and_cuts_dana_consumptions.quantity) as total_quantity') // Modify this line
            )
            ->groupBy('printed_and_cutted_rolls_entry.date', 'dana_names.name')
            ->get()
            ->toArray();

        dd($printAndCuttedRoleDatas);
            $groupedData = [];
            foreach ($printAndCuttedRoleDatas as $data) {
                $date = $data->date;
                if (!isset($groupedData[$date])) {
                    $groupedData[$date] = (object)[
                        'date' => $data->date,
                        'total_quantity_piece' => $data->total_quantity_piece,
                        'total_wastage' => $data->total_wastage,
                        'dana_consumpt' => [], // Use an array instead of an object
                    ];
                }
                $danaName = $data->dana_name;
                $danaKey = $danaName ?? null;
                $danaConsumptItem = (object)[
                    'dana_name' => $danaName,
                    'total_quantity' => $data->total_quantity,
                ];
                $groupedData[$date]->dana_consumpt[$danaKey] = $data->total_quantity;
            }
            $result = array_values(json_decode(json_encode($groupedData), true));
        $mergedArray = [];

        foreach ($bagFabricSentDatas as $item1) {
            $found = false;

            foreach ($groupedData as $item2) {
                if ($item1->receipt_date == $item2->date) {
                    $item1Array = (array) $item1;
                    $item2Array = (array) $item2;

                    $mergedArray[] = array_merge(
                        $item1Array,
                        array_fill_keys(array_keys($item1Array, null, true), null),
                        $item2Array
                    );

                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $item1Array = (array) $item1;
                $mergedArray[] = [
                    'receipt_date' => $item1Array['receipt_date'],
                    'total_net_wt' => $item1Array['total_net_wt'],
                    'date' => null,
                    'total_quantity_piece' => null,
                    'total_wastage' => null,
                    'dana_consumpt' => null,
                    // 'total_quantity' => null,
                ];
            }
        }
        foreach ($groupedData as $item2) {
            $found = false;

            foreach ($bagFabricSentDatas as $item1) {
                if ($item1->receipt_date == $item2->date) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $item2Array = (array) $item2;
                $mergedArray[] = [
                    'receipt_date' => null,
                    'total_net_wt' => null,
                    'date' => $item2Array['date'],
                    'total_quantity_piece' => $item2Array['total_quantity_piece'],
                    'total_wastage' => $item2Array['total_wastage'],
                    'dana_name' => $item2Array['dana_name'],
                    'total_quantity' => $item2Array['total_quantity'],
                ];
            }
        }
        $totalQtyByDate = DB::table('bag_bundel_entries')
            ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->whereBetween('bag_bundel_entries.receipt_date', [$request->start_date, $request->end_date])
            ->select(DB::raw('SUM(bag_bundel_items.qty_pcs) as total_bag_quantity_piece'), 'bag_bundel_entries.receipt_date')
            ->groupBy('bag_bundel_entries.receipt_date')
            ->get()
            ->toArray();
        // dd($totalQtyByDate);
       $mergedArrayAssociative = [];
        foreach ($mergedArray as $item) {
            $key = $item["receipt_date"] ?? $item["date"];
            $mergedArrayAssociative[$key] = $item;
        }
        // Merge the two arrays based on "receipt_date" or "date"
        foreach ($totalQtyByDate as $item) {
            $key = $item->receipt_date;
            if (isset($mergedArrayAssociative[$key])) {
                // Merge the arrays without overwriting existing values
                $mergedArrayAssociative[$key] = array_merge(
                    $mergedArrayAssociative[$key],
                    (array) $item
                );
            } else {
                $mergedArrayAssociative[$key] = array_merge(
                    array_fill_keys(array_keys($mergedArrayAssociative[$key] ?? []), null),
                    (array) $item
                );
            }
        }

        // Ensure "dana_consumpt" key is present even if there is no match in the arrays
        $mergedArrayAssociative = array_map(function ($item) {
            $item["dana_consumpt"] = $item["dana_consumpt"] ?? null;
            return $item;
        }, $mergedArrayAssociative);

        // Convert the associative array back to an indexed array
        $mergedArrayResult = array_values($mergedArrayAssociative);
                $resultArray = [];

            foreach ($mergedArrayResult as $item) {
            $bill_date = !empty($item['receipt_date']) ? $item['receipt_date'] : $item['date'];
            if (!isset($resultArray[$bill_date])) {
                $resultArray[$bill_date] = [];
            }
            $resultArray[$bill_date][] = [
                        'date' => $item['receipt_date'] ? $item['receipt_date'] : $item['date'],
                        'total_net_wt' => $item['total_net_wt']?? null,
                        'total_quantity_piece' => $item['total_quantity_piece']?? null,
                        'total_wastage' => $item['total_wastage']?? null,
                        'dana_consumpt'=> $item['dana_consumpt']?? null,
                        'total_bag_quantity_piece'=> $item['total_bag_quantity_piece']?? null,
                    ];
        }

        foreach ($resultArray as &$dateGroup) {
            $dateGroup = array_values($dateGroup);
        }
        $rowData = [];
        foreach ($resultArray as $date => $data) {
            $rowData[$date]['date'] = $date;
            $rowData[$date]['rollIssue_total_net_wt'] = 0;
            $rowData[$date]['cuttingBag_total_quantity_piece'] = 0;
            $rowData[$date]['tot_total_wastage'] = 0;
            $rowData[$date]['tot_total_bag_quantity_piece'] = 0;
            $danaQuantities = []; // Associative array to store total_quantity for each dana_name

            foreach ($data as $item) {
                $rowData[$date]['rollIssue_total_net_wt'] += $item['total_net_wt'];
                $rowData[$date]['cuttingBag_total_quantity_piece'] += $item['total_quantity_piece'];
                $rowData[$date]['tot_total_wastage'] += $item['total_wastage'];
                $rowData[$date]['tot_total_bag_quantity_piece'] += $item['total_bag_quantity_piece'];
                $rowData[$date]['dana_consumpt'] = $item['dana_consumpt'];
            }
        }
        //  dd($rowData);
        return $rowData;
    }
}
