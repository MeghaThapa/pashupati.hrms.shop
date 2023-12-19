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
        $bagFabricSentDatas = DB::table('bag_fabric_receive_item_sent')
        ->join('bag_fabric_entry', 'bag_fabric_receive_item_sent.fabric_bag_entry_id', '=', 'bag_fabric_entry.id')
        ->whereBetween('bag_fabric_entry.receipt_date', [$request->start_date, $request->end_date])
            ->select('bag_fabric_entry.receipt_date', DB::raw('SUM(bag_fabric_receive_item_sent.net_wt) as total_net_wt'))
            ->groupBy('bag_fabric_entry.receipt_date')
            ->get()
            ->toArray();

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

        $mergedArray = [];

        foreach ($bagFabricSentDatas as $item1) {
            $found = false;

            foreach ($printAndCuttedRoleDatas as $item2) {
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
                    'dana_name' => null,
                    'total_quantity' => null,
                ];
            }
        }
        foreach ($printAndCuttedRoleDatas as $item2) {
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

        // dd($mergedArray);
        $totalQtyByDate = DB::table('bag_bundel_entries')
        ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
        ->whereBetween('bag_bundel_entries.receipt_date', [$request->start_date, $request->end_date])
            ->select(DB::raw('SUM(bag_bundel_items.qty_pcs) as total_qty_pcs'), 'bag_bundel_entries.receipt_date')
            ->groupBy('bag_bundel_entries.receipt_date')
            ->get()
            ->toArray();


        // Merge data based on receipt_date or date
        foreach ($mergedArray as &$mergedItem) {
            foreach ($totalQtyByDate as $totalQtyItem) {
                // Check both receipt_date and date for a match
                if (
                    ($mergedItem['receipt_date'] !== null && $mergedItem['receipt_date'] === $totalQtyItem->receipt_date) ||
                    ($mergedItem['receipt_date'] === null && $mergedItem['date'] === $totalQtyItem->receipt_date)
                ) {
                    // Update total_qty_pcs in $mergedArray
                    $mergedItem['total_quantity_piece'] = $totalQtyItem->total_qty_pcs;
                    // You can break if you expect only one matching receipt_date
                    break;
                }
            }
        }


        $resultArray = [];
        foreach ($mergedArray as $item) {
            $bill_date = $item['receipt_date'] ? $item['receipt_date'] : $item['date'];
            $resultArray[$bill_date][] = [
                'date' => $item['receipt_date'] ? $item['receipt_date'] : $item['date'],
                'total_net_wt' => $item['total_net_wt'],
                'total_quantity_piece' => $item['total_quantity_piece'],
                'total_wastage' => $item['total_wastage'],
                'dana_name' => $item['dana_name'],
                'total_quantity' => $item['total_quantity'],
            ];
        }

        $rowData = [];
        dd($resultArray);
        foreach ($resultArray as $date => $data) {
            $rowData[$date]['date'] = $date;
            $rowData[$date]['rollIssue_total_net_wt'] = 0;
            $rowData[$date]['cuttingBag_total_quantity_piece'] = 0;
            $rowData[$date]['tot_total_wastage'] = 0;
            $rowData[$date]['tot_total_quantity'] = 0;
            $danaQuantities = []; // Associative array to store total_quantity for each dana_name

            foreach ($data as $item) {
                $rowData[$date]['rollIssue_total_net_wt'] += $item['total_net_wt'];
                $rowData[$date]['cuttingBag_total_quantity_piece'] += $item['total_quantity_piece'];
                $rowData[$date]['tot_total_wastage'] += $item['total_wastage'];
                $rowData[$date]['tot_total_quantity'] += $item['total_quantity'];

                // Check if the dana_name exists in the associative array, if not, initialize it
                if (!isset($danaQuantities[$item['dana_name']])) {
                    $danaQuantities[$item['dana_name']] = 0;
                }

                // // Add the total_quantity for the current dana_name
                $danaQuantities[$item['dana_name']] += $item['total_quantity'];
            }

            // Add the dana_name and corresponding total_quantity to the result
            $rowData[$date]['dana_quantities'] = $danaQuantities;
        }
        //  dd($rowData);
        return $rowData;
    }
}
