<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FabricStock;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\Department;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use DB;
use App\Imports\FabricOpeningImport;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class FabricStockController extends Controller
{
    public function openingCreate(){
        $godam = Godam::where("status","active")->get();
        return view("admin.fabric.fabric_opening.index")->with([
            "godam" => $godam
        ]);
    }
    public function openingStore(Request $request){
        $request->validate([
            "fabric_opening_date" => "required",
            "godam" => "required",
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm"
        ]);

        $file = $request->file("file");
        $godam = $request->godam;
        $type = $request->type;

        try{
            $fabricImport = new FabricOpeningImport($godam,$type);
            $import = Excel::import($fabricImport,$file);
            $importException = $fabricImport->getExceptionMessage();
            if($importException){
                return $importException->getMessage();
            }else{
                return "Import Successful";
            }
            
        }catch(Throwable $th){
            return $th->getMessage();
        }
    }
    public function index()
    {
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();

       $fabric_stock = FabricStock::paginate(35);
       // $fabric_stock = FabricStock::get();

       $godams=Godam::where('status','active')->get(['id','name']);
       $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
       $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);

       // dd($fabric_stock);


       return view('admin.fabric.fabric_stock.index',
       compact('settings','fabric_stock','godams','planttypes','plantnames'));
    }
}
