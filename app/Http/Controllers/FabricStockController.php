<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FabricStock;
use App\Models\Fabric;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\Department;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\FabricGroup;
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

            if($import){
                return back()->with(["message"=>"Data imported successfully!"]);
            }else{
                return "Unsuccessful";
            }
            
            // $importException = $fabricImport->getExceptionMessage();
            // if($importException){
            //     return $importException->getMessage();
            // }else{
            //     return "Import Successful";
            // }
            
        }catch(Throwable $th){
            return $th->getMessage();
        }
    }
    public function index()
    {
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();

       $fabric_stock = FabricStock::orderBy('roll_no')->paginate(35);
       // $fabric_stock = FabricStock::get();

       $godams=Godam::where('status','active')->get(['id','name']);
       $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
       $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);
       $sum = 0;

       $fabricgroups = FabricGroup::where('status','1')->get(['id','name']);


       // dd($fabricgroups);


       return view('admin.fabric.fabric_stock.index',
       compact('settings','fabric_stock','godams','planttypes','plantnames','sum','fabricgroups'));
    }

      public function filterStock(Request $request){

        // dd('hey');
        
        $getname = Fabric::where('id',$request->fabric_id)->value('name');

        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();
        $godam_id = $request->godam_id ?? null ;
        $type = $request->type ?? null ;
        $fabric_id = $request->fabric_id ?? null ;
        $fabricgroup_id = $request->fabricgroup_id ?? null ;

        $godams=Godam::where('status','active')->get(['id','name']);

        $sum = 0;

        $fabrics = FabricStock::where('status',1);

            if ($godam_id  || $godam_id  != null) {
                $fabrics = $fabrics->where('godam_id',$godam_id);
                $sum = $fabrics->sum('net_wt');
            }

            if($fabric_id || $fabric_id !=null){
                $fabrics = $fabrics->where('name', 'LIKE', '%'.$getname.'%');
                $sum = $fabrics->sum('net_wt');
            }

            // dd($type);

            if($type || $type !=null){
                $fabrics = $fabrics->where('is_laminated', $type);
                $sum = $fabrics->sum('net_wt');
            }

            if($fabricgroup_id || $fabricgroup_id !=null){
                $fabrics = $fabrics->where('fabricgroup_id',$fabricgroup_id);
                $sum = $fabrics->sum('net_wt');
            }

            $fabric_stock= $fabrics->orderBy('roll_no')->paginate(35);


        return view('admin.fabric.fabric_stock.index-ajax',
        compact('settings','fabric_stock','sum','godams','request'));
    }
}
