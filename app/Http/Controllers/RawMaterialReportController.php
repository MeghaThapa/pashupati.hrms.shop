<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RawMaterialReportController extends Controller
{
    public function index()
    {
        $results = DB::table('raw_materials')
            ->join('raw_material_items', 'raw_materials.id', '=', 'raw_material_items.raw_material_id')
            ->join('storein_types', 'raw_materials.storein_type_id', '=', 'storein_types.id')
            ->join('dana_names', 'raw_material_items.dana_name_id', '=', 'dana_names.id')
            ->select(
                'dana_names.name as dana_name',
                'raw_materials.date',
                DB::raw('SUM(raw_material_items.quantity) as total_quantity'),
                'storein_types.name as import_from'
            )
            ->groupBy('dana_name', 'raw_materials.date', 'import_from')
            ->orderBy('dana_name', 'asc')
            ->orderBy('raw_materials.date', 'asc')
            ->get();

        $resultOpenings = DB::table('rawmaterial_opening_items as roi')
            ->join('rawmaterial_opening_entries as roe', 'roi.rawmaterial_opening_entry_id', '=', 'roe.id')
            ->join('dana_names', 'roi.dana_name_id', '=', 'dana_names.id') // Join dana_names table
            ->select(
                'dana_names.name as dana_name', // Select dana_names.name
                'roe.opening_date',
                DB::raw('SUM(roi.qty_in_kg) as total_quantity')
            )
            ->groupBy('dana_name', 'roe.opening_date') // Group by dana_name
            ->orderBy('dana_name', 'asc')
            ->orderBy('roe.opening_date', 'asc')
            ->get();

        // Initialize an empty result array
        $openingArray = [];

        // Loop through the query results and restructure the data for $openingArray
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
        // Initialize an empty result array
        $resultArray = [];

        // Loop through the query results and restructure the data
        foreach ($results as $resultRawMaterial) {
            $danaName = $resultRawMaterial->dana_name;
            $date = $resultRawMaterial->date;
            $totalQuantity = $resultRawMaterial->total_quantity;
            $importFrom = $resultRawMaterial->import_from;

            // Check if the dana_name key exists in the resultRawMaterial array, if not, initialize it
            if (!isset($resultArray[$danaName])) {
                $resultArray[$danaName] = [];
            }

            // Add the total_quantity to the corresponding dana_name and date
            $resultArray[$danaName][$date] = [
                'total_quantity' => $totalQuantity,
                'import_from' => $importFrom,
            ];
        }

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

        // Initialize an empty result array
        $autoLoadArray = [];

        // Loop through the query results and restructure the data
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

        $mergedArray = [];

        foreach ($resultArray as $danaName => $dateData) {
            // Initialize an array for the current dana_name
            $danaNameArray = [];

            // Loop through the date data for the current dana_name
            foreach ($dateData as $date => $dateInfo) {
                // Initialize an array for the current date
                $dateArray = [
                    'total_quantity' => $dateInfo['total_quantity'],  // Copy "total_quantity" from the original array
                    'import_from' => $dateInfo['import_from'],         // Copy "import_from" from the original array
                    'opening_quantity' => 0,                          // Default value for opening_quantity
                ];

                // Check if the current date exists in the openingArray and has opening_quantity
                if (isset($openingArray[$danaName][$date]['opening_quantity'])) {
                    // If it exists, set the opening_quantity from the openingArray
                    $dateArray['opening_quantity'] = $openingArray[$danaName][$date]['opening_quantity'];
                }

                // Add the dateArray to the danaNameArray
                $danaNameArray[$date] = $dateArray;
            }

            // Add the danaNameArray to the mergedArray with dana_name as the key
            $mergedArray[$danaName] = $danaNameArray;
        }

        // Loop through $autoLoadArray and merge data into $mergedArray
        foreach ($autoLoadArray as $danaName => $autoLoadData) {
            // Check if the danaName key exists in $mergedArray, if not, initialize it
            if (!isset($mergedArray[$danaName])) {
                $mergedArray[$danaName] = [];
            }

            // Loop through $autoLoadData for the current danaName
            foreach ($autoLoadData as $transferDate => $autoLoadInfo) {
                // Check if the transferDate key exists in $mergedArray for the current danaName
                if (!isset($mergedArray[$danaName][$transferDate])) {
                    $mergedArray[$danaName][$transferDate] = [];
                }

                // Merge the data from $autoLoadInfo into $mergedArray
                $mergedArray[$danaName][$transferDate] = array_merge(
                    $mergedArray[$danaName][$transferDate],
                    $autoLoadInfo
                );

                // Check if 'opening_quantity' exists in $openingArray for the current danaName and transferDate
                if (isset($openingArray[$danaName][$transferDate]['opening_quantity'])) {
                    // Merge 'opening_quantity' into $mergedArray
                    $mergedArray[$danaName][$transferDate]['opening_quantity'] = $openingArray[$danaName][$transferDate]['opening_quantity'];
                } else {
                    // If 'opening_quantity' doesn't exist, set it to 0
                    $mergedArray[$danaName][$transferDate]['opening_quantity'] = 0;
                }
            }
        }

        $finalMergedArray = [];

            // Define a function to merge two arrays recursively
            function mergeArraysRecursive($array1, $array2) {
                foreach ($array2 as $key => $value) {
                    if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                        $array1[$key] = mergeArraysRecursive($array1[$key], $value);
                    } else {
                        $array1[$key] = $value;
                    }
                }
                return $array1;
            }

            // Merge $resultArray into $finalMergedArray
            foreach ($resultArray as $danaName => $dateData) {
                if (!isset($finalMergedArray[$danaName])) {
                    $finalMergedArray[$danaName] = [];
                }
                foreach ($dateData as $date => $dateInfo) {
                    if (!isset($finalMergedArray[$danaName][$date])) {
                        $finalMergedArray[$danaName][$date] = [];
                    }
                    $finalMergedArray[$danaName][$date] = mergeArraysRecursive(
                        $finalMergedArray[$danaName][$date],
                        $dateInfo
                    );
                }
            }

            // Merge $openingArray into $finalMergedArray
            foreach ($openingArray as $danaName => $dateData) {
                if (!isset($finalMergedArray[$danaName])) {
                    $finalMergedArray[$danaName] = [];
                }
                foreach ($dateData as $date => $dateInfo) {
                    if (!isset($finalMergedArray[$danaName][$date])) {
                        $finalMergedArray[$danaName][$date] = [];
                    }
                    $finalMergedArray[$danaName][$date] = mergeArraysRecursive(
                        $finalMergedArray[$danaName][$date],
                        $dateInfo
                    );
                }
            }

            // Merge $autoLoadArray into $finalMergedArray
            foreach ($autoLoadArray as $danaName => $dateData) {
                if (!isset($finalMergedArray[$danaName])) {
                    $finalMergedArray[$danaName] = [];
                }
                foreach ($dateData as $date => $dateInfo) {
                    if (!isset($finalMergedArray[$danaName][$date])) {
                        $finalMergedArray[$danaName][$date] = [];
                    }
                    $finalMergedArray[$danaName][$date] = mergeArraysRecursive(
                        $finalMergedArray[$danaName][$date],
                        $dateInfo
                    );
                }
            }

        return view('admin.rawmaterial.report', compact('mergedArray'));
    }
}
