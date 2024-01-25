<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Imports\StockImport;
use App\Imports\OpeningRawmaterialImport;
use App\Imports\OpeningWastageImport;

//for silent creations
class StockImportController extends Controller
{
    public function import(Request $request){
        $request->validate([
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm",
        ]);
        $file = $request->file('file');
        $import = Excel::import(new StockImport, $file );
        if($import){
            return back()->with(["message"=>"Data imported successfully!"]);
        }else{
            return "Unsuccessful";
        }
    }

    public function openingRawmaterialImport(Request $request){
        $request->validate([
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm",
        ]);
        $file = $request->file('file');

        $import = Excel::import(new OpeningRawmaterialImport, $file );
        // return $import;
        if($import){
            return back()->with(["message"=>"Data imported successfully!"]);
        }else{
            return "Unsuccessful";
        }
    }



    public function openingWastageImport(Request $request){
         $request->validate([
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm",
        ]);
        $file = $request->file('file');

        $import = Excel::import(new OpeningWastageImport, $file );
        // return $import;
        if($import){
            return back()->with(["message"=>"Data imported successfully!"]);
        }else{
            return "Unsuccessful";
        }
    //
    }
}

//for popups
// class StockImportController extends Controller
// {
//     public function import(Request $request)
//     {
//         $request->validate([
//             "file" => "required|mimes:csv,xlsx,xls,xltx,xltm",
//         ]);

//         $file = $request->file('file');
//         $import = new StockImport;

//         try {
//             Excel::import($import, $file);
//         }
//         // catch (\Throwable $e) {
//         //     return back()->with(["message_err" => [$e->getMessage()]]);
//         //     //  return back()->with(["message_err" => $e->getMessage()]);
//         // }
//         catch (\Throwable $e) {
//             return "hello";
//             $errorDetails = $import->getErrorMessages();
//             $errorMessages = array_column($errorDetails, 'data');
//             return back()->with(["message_err" => $errorMessages]);
//         }


//         $errorMessages = $import->getErrorMessages();

//         if (!empty($errorMessages)) {
//             return back()->with(["message_err" => $errorMessages]);
//         } else {
//             return back()->with(["message" => "Data imported successfully!"]);
//         }
//     }
// }
