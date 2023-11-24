<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagBundelEntry;
use DB;

class BagProductionReportController extends Controller
{
    public function prodAccDate(Request $request){
        if ($request->ajax()) {

            $datas = $this->dateWiseData($request);

            $view = view('admin.bag.bagBundelling.ssr.dateReport', compact('datas', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.bag.bagBundelling.dateReport'); 
    }

    private function dateWiseData($request){

        $wastageResults = DB::table('printed_and_cutted_rolls_entry')
        ->select(
            'printed_and_cutted_rolls_entry.date',
            DB::raw('SUM(printing_and_cutting_bag_items.wastage) as total_wastage')
        )
        ->join('printing_and_cutting_bag_items', 'printed_and_cutted_rolls_entry.id', '=', 'printing_and_cutting_bag_items.printAndCutEntry_id')
        ->groupBy('printed_and_cutted_rolls_entry.date')
        ->get()
        ;
        // dd($wastageResults);

        $results = DB::table('bag_bundel_entries')
        ->select(
            'bag_bundel_entries.receipt_date',
            DB::raw('SUM(bag_bundel_items.qty_in_kg) as total_qty_in_kg'),
            DB::raw('SUM(bag_bundel_items.qty_pcs) as total_qty_pcs')
        )
        ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
        ->whereBetween('bag_bundel_entries.receipt_date', [$request->start_date, $request->end_date])
        ->groupBy('bag_bundel_entries.receipt_date')
        ->get()
        ;
        // dd($results);
        $mergedArray = self::mergeCollectionsByDate($wastageResults, $results);
       
        // $resultArray = [];
        // foreach ($mergedArray as $item) {
        //     $bill_date = $item['date'];
        //     $resultArray[$bill_date][] = [
        //         'date' => $item['date'],
        //         'total_wastage' => $item['total_wastage'],
        //         'receipt_date' => $item['receipt_date'],
        //         'total_qty_in_kg' => $item['total_qty_in_kg'],
        //         'total_qty_pcs' => $item['total_qty_pcs'],
        //     ];
        // }
        //  dd($mergedArray);
        return $mergedArray; 
       
    }

    // private function mergeCollectionsByDate($collectionOne, $collectionTwo)
    // {
    //     $dateKey = 'date'; // Replace 'date' with the actual key you want to use
    //     $receiptDateKey = 'receipt_date'; // Replace 'receipt_date' with the actual key you want to use
    
    //     $datesArrayOne = $collectionOne->pluck($dateKey)->toArray();
    //     $datesArrayTwo = $collectionTwo->pluck($receiptDateKey)->toArray();
    
    //     $commonDates = array_intersect($datesArrayOne, $datesArrayTwo);
    //     $uniqueDatesOne = array_diff($datesArrayOne, $commonDates);
    //     $uniqueDatesTwo = array_diff($datesArrayTwo, $commonDates);
    
    //     $mergedArray = [];
    
    //     // Process common dates
    //     foreach ($commonDates as $date) {
    //         $dataOne = $collectionOne->where($dateKey, $date)->first();
    //         $dataTwo = $collectionTwo->where($receiptDateKey, $date)->first();
    
    //         // Convert objects to arrays
    //         $dataOneArray = $dataOne ? (array) $dataOne : [];
    //         $dataTwoArray = $dataTwo ? (array) $dataTwo : [];
    
    //         // Merge data from $dataOneArray and $dataTwoArray, keeping specific keys
    //         $mergedData = [
    //             'date' => $date,
    //             'total_wastage' => $dataOneArray['total_wastage'] ?? null,
    //             'receipt_date' => $dataTwoArray['receipt_date'] ?? null,
    //             'total_qty_in_kg' => $dataTwoArray['total_qty_in_kg'] ?? null,
    //             'total_qty_pcs' => $dataTwoArray['total_qty_pcs'] ?? null,
    //         ];
    
    //         $mergedArray[] = $mergedData;
    //     }
    
    //     // Process unique dates from $collectionOne
    //     foreach ($uniqueDatesOne as $date) {
    //         $dataOne = $collectionOne->where($dateKey, $date)->first();
    
    //         // Convert object to array
    //         $dataOneArray = $dataOne ? (array) $dataOne : [];
    
    //         // Add data to the result array
    //         $mergedArray[] = [
    //             'date' => $date,
    //             'total_wastage' => $dataOneArray['total_wastage'] ?? null,
    //             'receipt_date' => null,
    //             'total_qty_in_kg' => null,
    //             'total_qty_pcs' => null,
    //         ];
    //     }
    
    //     // Process unique dates from $collectionTwo
    //     foreach ($uniqueDatesTwo as $date) {
    //         $dataTwo = $collectionTwo->where($receiptDateKey, $date)->first();
    
    //         // Convert object to array
    //         $dataTwoArray = $dataTwo ? (array) $dataTwo : [];
    
    //         // Add data to the result array
    //         $mergedArray[] = [
    //             'date' => null,
    //             'total_wastage' => null,
    //             'receipt_date' => $dataTwoArray['receipt_date'] ?? null,
    //             'total_qty_in_kg' => $dataTwoArray['total_qty_in_kg'] ?? null,
    //             'total_qty_pcs' => $dataTwoArray['total_qty_pcs'] ?? null,
    //         ];
    //     }
    
    //     return $mergedArray;
    // }
    
    private function mergeCollectionsByDate($collectionOne, $collectionTwo)
{
    $dateKey = 'date'; // Replace 'date' with the actual key you want to use
    $receiptDateKey = 'receipt_date'; // Replace 'receipt_date' with the actual key you want to use

    $datesArrayOne = $collectionOne->pluck($dateKey)->toArray();
    $datesArrayTwo = $collectionTwo->pluck($receiptDateKey)->toArray();

    $commonDates = array_intersect($datesArrayOne, $datesArrayTwo);
    $uniqueDatesOne = array_diff($datesArrayOne, $commonDates);
    $uniqueDatesTwo = array_diff($datesArrayTwo, $commonDates);

    $mergedArray = [];

    // Process common dates
    foreach ($commonDates as $date) {
        $dataOne = $collectionOne->where($dateKey, $date)->first();
        $dataTwo = $collectionTwo->where($receiptDateKey, $date)->first();

        // Convert objects to arrays
        $dataOneArray = $dataOne ? (array) $dataOne : [];
        $dataTwoArray = $dataTwo ? (array) $dataTwo : [];

        // Merge data from $dataOneArray and $dataTwoArray, keeping specific keys
        $mergedData = [
            'date' => $date,
            'total_wastage' => $dataOneArray['total_wastage'] ?? null,
            'receipt_date' => $dataTwoArray['receipt_date'] ?? null,
            'total_qty_in_kg' => $dataTwoArray['total_qty_in_kg'] ?? null,
            'total_qty_pcs' => $dataTwoArray['total_qty_pcs'] ?? null,
        ];

        $mergedArray[] = $mergedData;
    }

    // Process unique dates from $collectionOne
    foreach ($uniqueDatesOne as $date) {
        $dataOne = $collectionOne->where($dateKey, $date)->first();

        // Convert object to array
        $dataOneArray = $dataOne ? (array) $dataOne : [];

        // Add data to the result array
        $mergedArray[] = [
            'date' => $date,
            'total_wastage' => $dataOneArray['total_wastage'] ?? null,
            'receipt_date' => null,
            'total_qty_in_kg' => null,
            'total_qty_pcs' => null,
        ];
    }

    // Process unique dates from $collectionTwo
    foreach ($uniqueDatesTwo as $date) {
        $dataTwo = $collectionTwo->where($receiptDateKey, $date)->first();

        // Convert object to array
        $dataTwoArray = $dataTwo ? (array) $dataTwo : [];

        // Add data to the result array
        $mergedArray[] = [
            'date' => null,
            'total_wastage' => null,
            'receipt_date' => $dataTwoArray['receipt_date'] ?? null,
            'total_qty_in_kg' => $dataTwoArray['total_qty_in_kg'] ?? null,
            'total_qty_pcs' => $dataTwoArray['total_qty_pcs'] ?? null,
        ];
    }

    // Sort merged array by 'date' in descending order
    usort($mergedArray, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return $mergedArray;
}

    
    public function __invoke(Request $request)
        {
            if ($request->ajax()) {

                $datas = $this->data($request);

                $view = view('admin.bag.bagBundelling.ssr.report', compact('datas', 'request'))->render();
                return response(['status' => true, 'data' => $view]);
            }
            return view('admin.bag.bagBundelling.report');
        }

    private function data($request)
        {
           $results1 = DB::table('bag_bundel_entries')
            ->select(
                'bag_bundel_entries.receipt_date',
                'bag_brands.name as brand_name',
                DB::raw('SUM(bag_bundel_items.qty_in_kg) as total_qty_in_kg'),
                DB::raw('SUM(bag_bundel_items.qty_pcs) as total_qty_pcs')
            )
            ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->join('bag_brands', 'bag_bundel_items.bag_brand_id', '=', 'bag_brands.id')
            ->whereBetween('bag_bundel_entries.receipt_date', [$request->start_date, $request->end_date])
            ->groupBy('bag_bundel_entries.receipt_date', 'bag_brands.name')
            ->get();

            // dd($results1);

            //for summary
            $result = DB::table('bag_bundel_entries')
            ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->join('bag_brands', 'bag_bundel_items.bag_brand_id', '=', 'bag_brands.id')
            ->select('bag_bundel_items.bag_brand_id', 'bag_brands.name', DB::raw('SUM(bag_bundel_items.qty_in_kg) as total_qty_in_kg'), DB::raw('SUM(bag_bundel_items.qty_pcs) as total_qty_pcs'))
            ->whereBetween('bag_bundel_entries.receipt_date', [$request->start_date, $request->end_date])
            ->groupBy('bag_bundel_items.bag_brand_id', 'bag_brands.name')
            ->get();
            // dd($result);
            //megha testt
            $groupedDatas = $results1->groupBy('bag_brand_id');
             $formattedDatas = [];
             foreach ($groupedDatas as $groupId => $group) {
                    foreach ($group as $groupedData) {
                        $formattedDatas[$groupedData->receipt_date][] = [
                            'receipt_date' =>$groupedData->receipt_date,
                            'bag_brand' => $groupedData->brand_name,
                            'qty_pcs' => $groupedData->total_qty_pcs,
                            'qty_in_kg'=> $groupedData->total_qty_in_kg,
                            'gram_per_bag' => number_format($groupedData->total_qty_in_kg/$groupedData->total_qty_pcs,3),
                        ];
                    }
                }


                // dd( $formattedDatas);
            return [
        'result' => $result,
        'formattedDatas' => $formattedDatas,
    ];

        }
}

