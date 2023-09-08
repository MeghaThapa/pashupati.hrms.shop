<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RawMaterialReportController extends Controller
{

    public function index()
    {
        $rawMaterialArray = $this->getRawMaterialArray();

        $rawMaterialOpeningsArray = $this->getRawMaterialOpeningArray();

        $autoLoadItemsArray = $this->getAutoLoadItemsArray();

        $mergedArray = $this->getMergedData($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray);

        return view('admin.rawMaterial.report', compact('mergedArray'));
    }

    private function mergeArrays($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray)
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

    private function getMergedData($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray)
    {
        $mergedData = $this->mergeArrays($rawMaterialArray, $rawMaterialOpeningsArray, $autoLoadItemsArray);
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
}
