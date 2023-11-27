<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FabricDetail;
use App\Models\TapeEntryItemModel;
use App\Models\ProcessingSubcat;
use App\Models\SingleTripalBill;
use App\Models\PrintedAndCuttedRollsEntry;
use App\Models\ReprocessWaste;
use App\Models\CCPlantEntry;
use App\Helpers\AppHelper;

class WastageProductionReportController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {

            $plantArray = $this->getPlantArray($request);

            $view = view('admin.wastageProductionReport.ssr.reportview', compact('plantArray', 'request'))->render();
            return response(['status' => true, 'data' => $view]);
        }
        return view('admin.wastageProductionReport.report');
    }

    private function getPlantArray($request)
    {
        // $fabricdata = FabricDetail::get();


        // foreach ($fabricdata as $fabricdata) {
        //     $find = FabricDetail::find($fabricdata->id);
        //     // dd($find->bill_date);
        //     $value = AppHelper::convertNepaliToEnglishDate($find->bill_date);
        //     // dd($value);
        //     $find->update(['bill_date_en' => $value]);
        // }
        // dd('date fixed');

    $tapePLantProduction = TapeEntryItemModel::join('tape_entry', 'tape_entry_items.tape_entry_id', '=', 'tape_entry.id')
                ->select(
                    DB::raw('SUM(tape_qty_in_kg)  as total_qty '),
                   'plantName_id','tape_entry.tape_entry_date')
                ->groupBy('plantName_id', 'tape_entry.tape_entry_date')
                ->whereIn('tape_entry_items.plantName_id',['1','2','3','4','9','13'])
                ->whereBetween('tape_entry.tape_entry_date', [$request->start_date, $request->end_date])
                ->get()
                ->toArray();

        $resultTapeArray = [];

        foreach ($tapePLantProduction as $item) {

            $key = $item['tape_entry_date'];

            if (!isset($resultTapeArray[$key])) {
                $resultTapeArray[$key] = [];
            }

            $resultTapeArray[$key]['date'] = $key;

            $resultTapeArray[$key]['plant_name_' . $item['plantName_id'] . '_total_quantity'] = $item['total_qty'];
        }


    $fabricDetailProduction = DB::table('fabric_details')
    ->select(
        'fabric_details.godam_id','fabric_details.bill_date_en',
        DB::raw('SUM(total_wastage)  as total_qty '),

        )
    ->whereBetween('fabric_details.bill_date_en', [$request->start_date, $request->end_date])
    ->groupBy('fabric_details.godam_id','fabric_details.bill_date_en')
    ->get()
    ->toArray();

    $resultFabricArray = [];

    foreach ($fabricDetailProduction as $item) {

        $key = $item->bill_date_en;

        if (!isset($resultFabricArray[$key])) {
            $resultFabricArray[$key] = [];
        }

        $resultFabricArray[$key]['date'] = $key;

        $resultFabricArray[$key]['godam_' . $item->godam_id . '_total_quantity'] = $item->total_qty;
    }


    $firstArray = self::mergeArraysByBillDateTapleFabric($resultTapeArray, $resultFabricArray);

    $LaminationPlantProduction = DB::table('fabric_send_and_receive_entry')
        ->select('fabric_send_and_receive_entry.plantname_id','fabric_send_and_receive_entry.bill_date',

            DB::raw('(SUM(total_waste)) as total_qty'),

        )
        ->whereIn('fabric_send_and_receive_entry.plantname_id',['5','6','10','14'])
        ->whereBetween('fabric_send_and_receive_entry.bill_date', [$request->start_date, $request->end_date])
        ->groupBy('fabric_send_and_receive_entry.plantName_id','fabric_send_and_receive_entry.bill_date')
        ->get()
        ->toArray();

        $resultLaminationArray = [];

        foreach ($LaminationPlantProduction as $item) {

            $key = $item->bill_date;

            if (!isset($resultLaminationArray[$key])) {
                $resultLaminationArray[$key] = [];
            }

            $resultLaminationArray[$key]['date'] = $key;
            $resultLaminationArray[$key]['plant_name_' . $item->plantname_id . '_total_quantity'] = $item->total_qty;

        }

        $nonWovenProduction = DB::table('nonwoven_bills')
        ->select('nonwoven_bills.bill_date_en',

            DB::raw('(SELECT SUM(total_waste) FROM nonwoven_bills) as total_value'),

        )
        ->whereBetween('nonwoven_bills.bill_date_en', [$request->start_date, $request->end_date])
        ->groupBy('nonwoven_bills.bill_date_en')

        ->get()
        ->toArray();

        $secondArray = self::mergeArraysByBillDate2($resultLaminationArray, $nonWovenProduction);

        $thirdArray = self::mergeArraysByBillDateDoubleArray($firstArray, $secondArray);

        $PrintCutProduction = PrintedAndCuttedRollsEntry::join('printing_and_cutting_bag_items', 'printing_and_cutting_bag_items.printAndCutEntry_id', '=', 'printed_and_cutted_rolls_entry.id')
        ->select(DB::raw('SUM(wastage) as total_wastage'), 'godam_id','printed_and_cutted_rolls_entry.date')
        ->groupBy('printing_and_cutting_bag_items.godam_id','printed_and_cutted_rolls_entry.date')
        ->whereIn('printing_and_cutting_bag_items.godam_id',['1','3'])
        ->whereBetween('printed_and_cutted_rolls_entry.date', [$request->start_date, $request->end_date])
        ->get()
        ->toArray();

        $resultPrintCutArray = [];

        foreach ($PrintCutProduction as $item) {

            $key = $item['date'];

            if (!isset($resultPrintCutArray[$key])) {
                $resultPrintCutArray[$key] = [];
            }

            $resultPrintCutArray[$key]['date'] = $key;

            $resultPrintCutArray[$key]['godam_' . $item['godam_id'] . '_total_quantity'] = $item['total_wastage'];
        }


        $reprocessWastageProduction = ReprocessWaste::join('reprocess_wastage_details', 'reprocess_wastage_details.reprocess_waste_id', '=', 'reprocess_wastes.id')
        ->select(DB::raw('SUM(cutter_quantity) as total_reprocess_wastage'),'reprocess_wastes.date')
        ->groupBy('reprocess_wastes.date')
        ->whereBetween('reprocess_wastes.date', [$request->start_date, $request->end_date])
        ->get()
        ->toArray();

        $fourthArray = self::mergeArraysByBillDatePrintReprocess($resultPrintCutArray, $reprocessWastageProduction);

        $fifthArray = self::mergeArraysByBillDateThirdFourth($thirdArray, $fourthArray);

        $ccPlantProduction = CCPlantEntry::join('cc_plant_wastages', 'cc_plant_wastages.ccplantentry_id', '=', 'ccplantentry.id')
        ->select(DB::raw('SUM(quantity) as total_ccplant_wastage'),'ccplantentry.date')
        ->whereBetween('ccplantentry.date', [$request->start_date, $request->end_date])
        ->groupBy('ccplantentry.date')
        ->get()
        ->toArray();

        $sixthArray = self::mergeArraysByBillDateFifthCcplant($fifthArray, $ccPlantProduction);

        $resultArray = [];
        foreach ($sixthArray as $item) {
            $bill_date = $item['bill_date'];
            $resultArray[$bill_date][] = [
                'bill_date' => $item['bill_date'],
                'tapeplant_wastage1' => $item['tapeplant_wastage1'],
                'tapeplant_wastage2' => $item['tapeplant_wastage2'],
                'tapeplant_wastage3' => $item['tapeplant_wastage3'],
                'tapeplant_wastage4' => $item['tapeplant_wastage4'],
                'tapeplant_wastage9' => $item['tapeplant_wastage9'],
                'tapeplant_wastage13' => $item['tapeplant_wastage13'],
                'godam1' => $item['godam1'],
                'godam2' => $item['godam2'],
                'godam3' => $item['godam3'],
                'laminationplant_wastage1' => $item['laminationplant_wastage1'],
                'laminationplant_wastage2' => $item['laminationplant_wastage2'],
                'laminationplant_wastage3' => $item['laminationplant_wastage3'],
                'laminationplant_wastage4' => $item['laminationplant_wastage4'],
                'nonwoven_wastage' => $item['nonwoven_wastage'],
                'printfinish_wastage1' => $item['printfinish_wastage1'],
                'printfinish_wastage3' => $item['printfinish_wastage3'],
                'erema_wastage' => $item['erema_wastage'],
                'total_ccplant_wastage' => $item['total_ccplant_wastage'],

            ];
        }

    $rowData = [];
        foreach ($resultArray as $date => $data) {
            $rowData[$date]['bill_date'] = $date;
            $rowData[$date]['tapeplant_wastage1'] = 0;
            $rowData[$date]['tapeplant_wastage2'] = 0;
            $rowData[$date]['tapeplant_wastage3'] = 0;
            $rowData[$date]['tapeplant_wastage4'] = 0;
            $rowData[$date]['tapeplant_wastage9'] = 0;
            $rowData[$date]['tapeplant_wastage13'] = 0;
            $rowData[$date]['godam1'] = 0;
            $rowData[$date]['godam2'] = 0;
            $rowData[$date]['godam3'] = 0;
            $rowData[$date]['laminationplant_wastage1'] = 0;
            $rowData[$date]['laminationplant_wastage2'] = 0;
            $rowData[$date]['laminationplant_wastage3'] = 0;
            $rowData[$date]['laminationplant_wastage4'] = 0;
            $rowData[$date]['nonwoven_wastage'] = 0;
            $rowData[$date]['printfinish_wastage1'] = 0;
            $rowData[$date]['printfinish_wastage3'] = 0;
            $rowData[$date]['erema_wastage'] = 0;
            $rowData[$date]['total_ccplant_wastage'] = 0;

            foreach ($data as $item) {

                $rowData[$date]['tapeplant_wastage1'] += $item['tapeplant_wastage1'];
                $rowData[$date]['tapeplant_wastage2'] += $item['tapeplant_wastage2'];
                $rowData[$date]['tapeplant_wastage3'] += $item['tapeplant_wastage3'];
                $rowData[$date]['tapeplant_wastage4'] += $item['tapeplant_wastage4'];
                $rowData[$date]['tapeplant_wastage9'] += $item['tapeplant_wastage9'];
                $rowData[$date]['tapeplant_wastage13'] += $item['tapeplant_wastage13'];
                $rowData[$date]['godam1'] += $item['godam1'];
                $rowData[$date]['godam2'] += $item['godam2'];
                $rowData[$date]['godam3'] += $item['godam3'];

                $rowData[$date]['laminationplant_wastage1'] += $item['laminationplant_wastage1'];
                $rowData[$date]['laminationplant_wastage2'] += $item['laminationplant_wastage2'];
                $rowData[$date]['laminationplant_wastage3'] += $item['laminationplant_wastage3'];
                $rowData[$date]['laminationplant_wastage4'] += $item['laminationplant_wastage4'];

                $rowData[$date]['nonwoven_wastage'] += $item['nonwoven_wastage'];
                $rowData[$date]['printfinish_wastage1'] += $item['printfinish_wastage1'];
                $rowData[$date]['printfinish_wastage3'] += $item['printfinish_wastage3'];
                $rowData[$date]['erema_wastage'] += $item['erema_wastage'];

                $rowData[$date]['total_ccplant_wastage'] += $item['total_ccplant_wastage'];

            }


        }
        return $rowData;


    }

    public function mergeArraysByBillDateTapleFabric($arrayOne, $arrayTwo)
    {

      $datesArrayOne = array_column($arrayOne, 'date');
      $datesArrayTwo = array_column($arrayTwo, 'date');

      $uniqueDates = array_unique(array_merge($datesArrayOne, $datesArrayTwo));
      $mergedArray = [];

      // Loop through each unique date
      foreach ($uniqueDates as $date) {
    // Find the corresponding data from $arrayOne (or set null if not found)
      $dataOne = array_values(array_filter($arrayOne, function ($item) use ($date) {
      $item = (array) $item;
       return $item['date'] === $date;
      }));


     // Find the corresponding data from $arrayTwo (or set null if not found)
     $dataTwo = array_values(array_filter($arrayTwo, function ($item) use ($date) {
     $item = (array) $item;
      return $item['date'] === $date;
     }));

     $dataOne = (array) ($dataOne[0] ?? ['date' => $date]);
     $dataTwo = (array) ($dataTwo[0] ?? ['date' => $date]);
    //  dd($arrayOne,$dataOne);


    // Ensure all keys are present in the result array with null values if not found
     $mergedData = [
     'bill_date' => $date,
     'tapeplant_wastage1' => $dataOne['plant_name_1_total_quantity'] ?? null,
     'tapeplant_wastage2' => $dataOne['plant_name_2_total_quantity'] ?? null,
     'tapeplant_wastage3' => $dataOne['plant_name_3_total_quantity'] ?? null,
     'tapeplant_wastage4' => $dataOne['plant_name_4_total_quantity'] ?? null,
     'tapeplant_wastage9' => $dataOne['plant_name_9_total_quantity'] ?? null,
     'tapeplant_wastage13' => $dataOne['plant_name_13_total_quantity'] ?? null,
     'godam1' => $dataTwo['godam_1_total_quantity'] ?? null,
     'godam2' => $dataTwo['godam_2_total_quantity'] ?? null,
     'godam3' => $dataTwo['godam_3_total_quantity'] ?? null,
     ];

     $mergedArray[] = $mergedData;
    }

    return $mergedArray;
    }

    public function mergeArraysByBillDate2($arrayOne, $arrayTwo)
    {

      $datesArrayOne = array_column($arrayOne, 'date');
      $datesArrayTwo = array_column($arrayTwo, 'bill_date_en');

      $uniqueDates = array_unique(array_merge($datesArrayOne, $datesArrayTwo));
      $mergedArray = [];

      // Loop through each unique date
      foreach ($uniqueDates as $date) {
    // Find the corresponding data from $arrayOne (or set null if not found)
      $dataOne = array_values(array_filter($arrayOne, function ($item) use ($date) {
      $item = (array) $item;
       return $item['date'] === $date;
      }));


     // Find the corresponding data from $arrayTwo (or set null if not found)
     $dataTwo = array_values(array_filter($arrayTwo, function ($item) use ($date) {
     $item = (array) $item;
      return $item['bill_date_en'] === $date;
     }));

     $dataOne = (array) ($dataOne[0] ?? ['date' => $date]);
     $dataTwo = (array) ($dataTwo[0] ?? ['date' => $date]);

    // Ensure all keys are present in the result array with null values if not found
     $mergedData = [
     'bill_date' => $date,
     'laminationplant_wastage1' => $dataOne['plant_name_5_total_quantity'] ?? null,
     'laminationplant_wastage2' => $dataOne['plant_name_6_total_quantity'] ?? null,
     'laminationplant_wastage3' => $dataOne['plant_name_5_total_quantity'] ?? null,
     'laminationplant_wastage4' => $dataOne['plant_name_5_total_quantity'] ?? null,
     'nonwoven_wastage' => $dataTwo['total_value'] ?? null,
     ];

     $mergedArray[] = $mergedData;
    }


    return $mergedArray;
    }

    public function mergeArraysByBillDatePrintReprocess($arrayOne, $arrayTwo)
    {

      $datesArrayOne = array_column($arrayOne, 'date');
      $datesArrayTwo = array_column($arrayTwo, 'date');

      $uniqueDates = array_unique(array_merge($datesArrayOne, $datesArrayTwo));
      $mergedArray = [];


      // Loop through each unique date
      foreach ($uniqueDates as $date) {
    // Find the corresponding data from $arrayOne (or set null if not found)
      $dataOne = array_values(array_filter($arrayOne, function ($item) use ($date) {
      $item = (array) $item;
       return $item['date'] === $date;
      }));


     // Find the corresponding data from $arrayTwo (or set null if not found)
     $dataTwo = array_values(array_filter($arrayTwo, function ($item) use ($date) {
     $item = (array) $item;
      return $item['date'] === $date;
     }));

     $dataOne = (array) ($dataOne[0] ?? ['date' => $date]);
     $dataTwo = (array) ($dataTwo[0] ?? ['date' => $date]);

    // Ensure all keys are present in the result array with null values if not found
     $mergedData = [
     'bill_date' => $date,
     'printfinish_wastage1' => $dataOne['godam_1_total_quantity'] ?? null,
     'printfinish_wastage3' => $dataOne['godam_3_total_quantity'] ?? null,
     'erema_wastage' => $dataTwo['godam_1_total_quantity'] ?? null,
     ];

     $mergedArray[] = $mergedData;
    }

    return $mergedArray;
    }


    public function mergeArraysByBillDateDoubleArray($arrayOne, $arrayTwo)
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
                'tapeplant_wastage1' => $dataOne['tapeplant_wastage1'] ?? null,
                'tapeplant_wastage2' => $dataOne['tapeplant_wastage2'] ?? null,
                'tapeplant_wastage3' => $dataOne['tapeplant_wastage3'] ?? null,
                'tapeplant_wastage4' => $dataOne['tapeplant_wastage4'] ?? null,
                'tapeplant_wastage9' => $dataOne['tapeplant_wastage9'] ?? null,
                'tapeplant_wastage13' => $dataOne['tapeplant_wastage13'] ?? null,
                'godam1' => $dataOne['godam1'] ?? null,
                'godam2' => $dataOne['godam2'] ?? null,
                'godam3' => $dataOne['godam3'] ?? null,

                'laminationplant_wastage1' => $dataTwo['laminationplant_wastage1'] ?? null,
                'laminationplant_wastage2' => $dataTwo['laminationplant_wastage2'] ?? null,
                'laminationplant_wastage3' => $dataTwo['laminationplant_wastage3'] ?? null,
                'laminationplant_wastage4' => $dataTwo['laminationplant_wastage4'] ?? null,
                'nonwoven_wastage' => $dataTwo['nonwoven_wastage'] ?? null,
            ];

            $mergedArray[] = $mergedData;
        }

        return $mergedArray;
    }

    public function mergeArraysByBillDateThirdFourth($arrayOne, $arrayTwo)
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

     $dataOne = (array) ($dataOne[0] ?? ['date' => $date]);
     $dataTwo = (array) ($dataTwo[0] ?? ['date' => $date]);

    // Ensure all keys are present in the result array with null values if not found
     $mergedData = [
     'bill_date' => $date,
     'tapeplant_wastage1' => $dataOne['tapeplant_wastage1'] ?? null,
     'tapeplant_wastage2' => $dataOne['tapeplant_wastage2'] ?? null,
     'tapeplant_wastage3' => $dataOne['tapeplant_wastage3'] ?? null,
     'tapeplant_wastage4' => $dataOne['tapeplant_wastage4'] ?? null,
     'tapeplant_wastage9' => $dataOne['tapeplant_wastage9'] ?? null,
     'tapeplant_wastage13' => $dataOne['tapeplant_wastage13'] ?? null,
     'godam1' => $dataOne['godam1'] ?? null,
     'godam2' => $dataOne['godam2'] ?? null,
     'godam3' => $dataOne['godam3'] ?? null,
     'laminationplant_wastage1' => $dataOne['laminationplant_wastage1'] ?? null,
     'laminationplant_wastage2' => $dataOne['laminationplant_wastage2'] ?? null,
     'laminationplant_wastage3' => $dataOne['laminationplant_wastage3'] ?? null,
     'laminationplant_wastage4' => $dataOne['laminationplant_wastage4'] ?? null,
     'nonwoven_wastage' => $dataOne['nonwoven_wastage'] ?? null,

     'printfinish_wastage1' => $dataTwo['printfinish_wastage1'] ?? null,
     'printfinish_wastage3' => $dataTwo['printfinish_wastage3'] ?? null,
     'erema_wastage' => $dataTwo['erema_wastage'] ?? null,
     ];

     $mergedArray[] = $mergedData;
    }

    return $mergedArray;
    }

    public function mergeArraysByBillDateFifthCcplant($arrayOne, $arrayTwo)
    {

      $datesArrayOne = array_column($arrayOne, 'bill_date');
      $datesArrayTwo = array_column($arrayTwo, 'date');

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
      return $item['date'] === $date;
     }));

     $dataOne = (array) ($dataOne[0] ?? ['date' => $date]);
     $dataTwo = (array) ($dataTwo[0] ?? ['date' => $date]);

    // Ensure all keys are present in the result array with null values if not found
     $mergedData = [
     'bill_date' => $date,
     'tapeplant_wastage1' => $dataOne['tapeplant_wastage1'] ?? null,
     'tapeplant_wastage2' => $dataOne['tapeplant_wastage2'] ?? null,
     'tapeplant_wastage3' => $dataOne['tapeplant_wastage3'] ?? null,
     'tapeplant_wastage4' => $dataOne['tapeplant_wastage4'] ?? null,
     'tapeplant_wastage9' => $dataOne['tapeplant_wastage9'] ?? null,
     'tapeplant_wastage13' => $dataOne['tapeplant_wastage13'] ?? null,
     'godam1' => $dataOne['godam1'] ?? null,
     'godam2' => $dataOne['godam2'] ?? null,
     'godam3' => $dataOne['godam3'] ?? null,
     'laminationplant_wastage1' => $dataOne['laminationplant_wastage1'] ?? null,
     'laminationplant_wastage2' => $dataOne['laminationplant_wastage2'] ?? null,
     'laminationplant_wastage3' => $dataOne['laminationplant_wastage3'] ?? null,
     'laminationplant_wastage4' => $dataOne['laminationplant_wastage4'] ?? null,
     'nonwoven_wastage' => $dataOne['nonwoven_wastage'] ?? null,
     'printfinish_wastage1' => $dataOne['printfinish_wastage1'] ?? null,
     'printfinish_wastage3' => $dataOne['printfinish_wastage3'] ?? null,
     'erema_wastage' => $dataOne['erema_wastage'] ?? null,
     'total_ccplant_wastage' => $dataTwo['total_ccplant_wastage'] ?? null,

    ];

     $mergedArray[] = $mergedData;
    }

    return $mergedArray;
    }


}
