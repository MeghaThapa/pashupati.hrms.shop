<?php

namespace App\Http\Controllers;

use App\Models\DanaName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RawMaterialDanaDateWiseReport;

class RawMaterialReportController extends Controller
{

    public function index()
    {
        DB::table('raw_material_dana_date_wise_reports')->truncate();

        $reports = DB::table('raw_material_dana_date_wise_reports')
            ->join('dana_names', 'raw_material_dana_date_wise_reports.dana_name_id', '=', 'dana_names.id')
            ->select('dana_names.name as dana_name', 'raw_material_dana_date_wise_reports.date', 'opening_amount', 'import', 'local', 'from_godam', 'tape', 'lam', 'nw_plant', 'sales', 'to_godam', 'closing')
            ->orderBy('dana_name', 'asc')
            ->orderBy('date', 'asc')
            ->get();

        $formattedReports = [];

        foreach ($reports as $report) {
            $danaName = $report->dana_name;
            $date = $report->date;

            if (!isset($formattedReports[$danaName])) {
                $formattedReports[$danaName] = [];
            }

            $formattedReports[$danaName][$date] = [
                'opening_amount' => $report->opening_amount,
                'import' => $report->import,
                'local' => $report->local,
                'from_godam' => $report->from_godam,
                'tape' => $report->tape,
                'lam' => $report->lam,
                'nw_plant' => $report->nw_plant,
                'sales' => $report->sales,
                'to_godam' => $report->to_godam,
                'closing' => $report->closing,
            ];
        }

        return view('admin.rawMaterial.psireport', compact('formattedReports'));
    }

    public function oldIndex()
    {
        $godam_id = 1;

        $rawMaterialArray = $this->getRawMaterialArray();

        $rawMaterialOpeningsArray = $this->getRawMaterialOpeningArray();

        $autoLoadItemsArray = $this->getAutoLoadItemsArray();

        $salesArray = $this->getSalesArray();

        $mergedArray = $this->getMergedData($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray, $salesArray);


        $status = $this->calculateAndSave($mergedArray,$godam_id);

        dd($status);

        return view('admin.rawMaterial.report', compact('mergedArray'));
    }

    private function calculateAndSave($mergedArray, $godam_id)
    {

        try {

            DB::beginTransaction();

            $previousDanaName = null;
            $openingQuantity = 0;
            $previousClosingAmount = 0;

            foreach ($mergedArray as $danaName => $dates) {
                if ($danaName !== $previousDanaName) {
                    // If a new dana_name is encountered, reset the opening quantity to 0
                    $openingQuantity = 0;
                }

                foreach ($dates as $date => $dateData) {

                    // Create a new instance of RawMaterialDanaDateWiseReport
                    $rawMaterialDanaDateWiseReport = new RawMaterialDanaDateWiseReport;


                    // Set the dana_name_id
                    $dana = DanaName::where('name', $danaName)->firstOrFail();
                    if ($dana) {
                        $rawMaterialDanaDateWiseReport->dana_name_id = $dana->id;
                    }

                    // Set godam_id (assuming $godam_id is available)
                    $rawMaterialDanaDateWiseReport->godam_id = $godam_id;

                    $rawMaterialDanaDateWiseReport->date = $date;

                    // Calculate and set the values for other fields
                    $openingAmount = $dateData['opening_quantity'] ?? $openingQuantity + $previousClosingAmount;
                    $rawMaterialDanaDateWiseReport->opening_amount = $openingAmount;

                    $import = isset($dateData['import_from']) && $dateData['import_from'] == 'import' ? $dateData['total_quantity'] : 0;
                    $rawMaterialDanaDateWiseReport->import = $import;

                    // logic for local
                    $local = isset($dateData['import_from']) && $dateData['import_from'] == 'local' ? $dateData['total_quantity'] : 0;
                    $rawMaterialDanaDateWiseReport->local = $local;

                    // Calculate from_godam based on the conditions you provided
                    $fromGodam = isset($dateData['import_from']) && $dateData['import_from'] == 'godam' && $dateData['from_godam_id'] == 2 && $dateData['to_godam_id'] == 1 ? $dateData['total_quantity'] : 0;
                    $rawMaterialDanaDateWiseReport->from_godam = $fromGodam;

                    $rawMaterialDanaDateWiseReport->tape = $dateData['tape plant'] ?? 0;
                    $rawMaterialDanaDateWiseReport->lam = $dateData['lamination plant'] ?? 0;
                    $rawMaterialDanaDateWiseReport->nw_plant = $dateData['nonwoven plant'] ?? 0;
                    $rawMaterialDanaDateWiseReport->sales = $dateData['sales_quantity'] ?? 0;

                    $toGodam = isset($dateData['import_from']) && $dateData['from_godam_id'] == 1 && $dateData['to_godam_id'] == 2 && $dateData['import_from'] == 'godam' ? $dateData['total_quantity'] : 0;
                    $rawMaterialDanaDateWiseReport->to_godam = $toGodam;

                    $closing = $rawMaterialDanaDateWiseReport->opening_amount + $rawMaterialDanaDateWiseReport->import + $rawMaterialDanaDateWiseReport->local + $rawMaterialDanaDateWiseReport->from_godam - $rawMaterialDanaDateWiseReport->tape - $rawMaterialDanaDateWiseReport->lam - $rawMaterialDanaDateWiseReport->nw_plant - $rawMaterialDanaDateWiseReport->sales - $rawMaterialDanaDateWiseReport->to_godam;
                    $rawMaterialDanaDateWiseReport->closing = $closing;

                    // Save the record
                    $rawMaterialDanaDateWiseReport->save();

                    $previousClosingAmount = $closing;
                }
                $previousDanaName = $danaName;
            }
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    private function mergeArrays($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray, $salesArray)
    {
        $mergedArray = [];

        // Merge $rawMaterialArray into $mergedArray
        foreach ($rawMaterialArray as $danaName => $rawMaterialData) {
            if (!isset($mergedArray[$danaName])) {
                $mergedArray[$danaName] = [];
            }
            foreach ($rawMaterialData as $date => $dateInfo) {
                if (!isset($mergedArray[$danaName][$date])) {
                    $mergedArray[$danaName][$date] = [];
                }
                // Preserve "total_quantity" and "import_from" from $rawMaterialArray
                $mergedArray[$danaName][$date]['total_quantity'] = $dateInfo['total_quantity'];
                $mergedArray[$danaName][$date]['import_from'] = $dateInfo['import_from'];
                $mergedArray[$danaName][$date]['from_godam_id'] = $dateInfo['from_godam_id'];
                $mergedArray[$danaName][$date]['to_godam_id'] = $dateInfo['to_godam_id'];
            }
        }

        // Merge $rawMaterialOpeningsArray into $mergedArray
        foreach ($rawMaterialOpeningsArray as $danaName => $openingData) {
            if (!isset($mergedArray[$danaName])) {
                $mergedArray[$danaName] = [];
            }
            foreach ($openingData as $openingDate => $openingInfo) {
                if (!isset($mergedArray[$danaName][$openingDate])) {
                    $mergedArray[$danaName][$openingDate] = [];
                }
                // Preserve "opening_quantity" from $rawMaterialOpeningsArray
                $mergedArray[$danaName][$openingDate]['opening_quantity'] = $openingInfo['opening_quantity'];
            }
        }

        // Merge $autoLoadItemsArray into $mergedArray
        $mergedArray = $this->recursiveMerge($mergedArray, $autoLoadItemsArray);

        // Merge $salesArray into $mergedArray
        foreach ($salesArray as $danaName => $salesData) {
            foreach ($salesData as $billDate => $salesInfo) {
                if (!isset($mergedArray[$danaName])) {
                    $mergedArray[$danaName] = [];
                }
                if (!isset($mergedArray[$danaName][$billDate])) {
                    $mergedArray[$danaName][$billDate] = [];
                }
                // Add the sales_quantity from $salesArray into $mergedArray
                $mergedArray[$danaName][$billDate]['sales_quantity'] = $salesInfo['sales_quantity'];
            }
        }

        foreach ($mergedArray as &$danaData) {
            ksort($danaData);
        }

        return $mergedArray;
    }

    private function recursiveMerge($array1, $array2)
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = $this->recursiveMerge($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }
        return $array1;
    }

    private function getMergedData($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray, $salesArray)
    {
        $mergedData = $this->mergeArrays($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray, $salesArray);
        return $mergedData;
    }

    private function getRawMaterialArray()
    {

        $rawMaterialsResults = DB::table('raw_materials')
            ->join('raw_material_items', 'raw_materials.id', '=', 'raw_material_items.raw_material_id')
            ->join('storein_types', 'raw_materials.storein_type_id', '=', 'storein_types.id')
            ->join('dana_names', 'raw_material_items.dana_name_id', '=', 'dana_names.id')
            ->select(
                'dana_names.name as dana_name',
                'raw_materials.date',
                'raw_materials.to_godam_id',
                'raw_materials.from_godam_id',
                DB::raw('SUM(raw_material_items.quantity) as total_quantity'),
                'storein_types.name as import_from'
            )
            ->where('raw_materials.to_godam_id', 1)
            ->orWhere('raw_materials.from_godam_id', 1)
            ->groupBy('dana_name', 'raw_materials.date', 'import_from', 'from_godam_id', 'to_godam_id')
            ->orderBy('dana_name', 'asc')
            ->orderBy('raw_materials.date', 'asc')
            ->get();

        $resultArray = [];

        foreach ($rawMaterialsResults as $resultRawMaterial) {
            $danaName = $resultRawMaterial->dana_name;
            $date = $resultRawMaterial->date;
            $totalQuantity = $resultRawMaterial->total_quantity;
            $importFrom = $resultRawMaterial->import_from;
            $fromGodamId = $resultRawMaterial->from_godam_id;
            $toGodamId = $resultRawMaterial->to_godam_id;

            // Check if the dana_name key exists in the resultRawMaterial array, if not, initialize it
            if (!isset($resultArray[$danaName])) {
                $resultArray[$danaName] = [];
            }

            // Add the total_quantity to the corresponding dana_name and date
            $resultArray[$danaName][$date] = [
                'total_quantity' => $totalQuantity,
                'import_from' => $importFrom,
                'from_godam_id' => $fromGodamId,
                'to_godam_id' => $toGodamId,
            ];
        }

        return $resultArray;
    }

    private function getRawMaterialOpeningArray()
    {
        $openingArray = [];
        $resultOpenings = DB::table('rawmaterial_opening_items')
            ->join('rawmaterial_opening_entries', 'rawmaterial_opening_entries.id', '=', 'rawmaterial_opening_items.rawmaterial_opening_entry_id')
            ->join('dana_names', 'rawmaterial_opening_items.dana_name_id', '=', 'dana_names.id')
            ->select(
                'dana_names.name as dana_name', // Select dana_names.name
                'rawmaterial_opening_entries.opening_date',
                DB::raw('SUM(rawmaterial_opening_items.qty_in_kg) as total_quantity')
            )
            ->groupBy('dana_name', 'rawmaterial_opening_entries.opening_date') // Group by dana_name
            ->orderBy('dana_name', 'asc')
            ->orderBy('rawmaterial_opening_entries.opening_date', 'asc')
            ->get();

        foreach ($resultOpenings as $resultOpening) {
            $danaName = $resultOpening->dana_name;
            $openingDate = $resultOpening->opening_date;
            $totalQuantity = $resultOpening->total_quantity;

            // Check if the danaName key exists in the $openingArray, if not, initialize it
            if (!isset($openingArray[$danaName])) {
                $openingArray[$danaName] = [];
            }

            // Add the openingDate and totalQuantity to the corresponding danaName
            $openingArray[$danaName][$openingDate] = [
                'opening_quantity' => $totalQuantity,
                // You can add more keys here if needed
            ];
        }

        return $openingArray;
    }

    private function getAutoLoadItemsArray()
    {
        $autoLoadArray = [];

        $autoLoadData = DB::table('autoload_items as ai')
            ->leftJoin('auto_loads as al', 'ai.autoload_id', '=', 'al.id')
            ->leftJoin('godam', 'ai.from_godam_id', '=', 'godam.id')
            ->leftJoin('dana_names', 'ai.dana_name_id', '=', 'dana_names.id')
            ->leftJoin('processing_steps', 'ai.plant_type_id', '=', 'processing_steps.id')
            ->select(
                'dana_names.name as dana_name',
                'al.transfer_date',
                DB::raw('SUM(ai.quantity) as autoload_quantity'),
                DB::raw('COUNT(*) as count'),
                'processing_steps.name as processing_step_name'
            )
            ->groupBy('dana_name', 'al.transfer_date', 'processing_step_name')
            ->orderBy('dana_name', 'asc')
            ->orderBy('al.transfer_date', 'asc')
            ->get();

        foreach ($autoLoadData as $result) {
            $danaName = $result->dana_name;
            $transferDate = $result->transfer_date;
            $autoloadQuantity = $result->autoload_quantity;
            $processingStepName = $result->processing_step_name;

            // Check if the dana_name key exists in the result array, if not, initialize it
            if (!isset($autoLoadArray[$danaName])) {
                $autoLoadArray[$danaName] = [];
            }

            // Check if the transfer_date key exists in the dana_name array, if not, initialize it
            if (!isset($autoLoadArray[$danaName][$transferDate])) {
                $autoLoadArray[$danaName][$transferDate] = [];
            }

            // Add the processing_step_name and autoload_quantity to the corresponding dana_name, transfer_date, and processing_step_name
            $autoLoadArray[$danaName][$transferDate][$processingStepName] = $autoloadQuantity;
        }

        return $autoLoadArray;
    }

    public function getSalesArray()
    {
        $result = DB::table('raw_material_items_sales as rmi')
            ->join('raw_material_sales_entries as rms', 'rmi.raw_material_sales_entry_id', '=', 'rms.id')
            ->join('dana_names as dn', 'rmi.dana_name_id', '=', 'dn.id')
            ->select(
                'dn.name as dana_name',
                'rms.bill_date',
                DB::raw('SUM(rmi.qty_in_kg) as sales_quantity')
            )
            ->groupBy('dana_name', 'bill_date')
            ->orderBy('dana_name', 'asc')
            ->orderBy('bill_date', 'asc')
            ->get();

        // Transform the result into the desired format
        $salesArray = [];

        foreach ($result as $row) {
            $danaName = $row->dana_name;
            $billDate = $row->bill_date;
            $salesQuantity = $row->sales_quantity;

            if (!isset($salesArray[$danaName])) {
                $salesArray[$danaName] = [];
            }

            $salesArray[$danaName][$billDate] = [
                'sales_quantity' => $salesQuantity,
            ];
        }
        return $salesArray;
    }
}