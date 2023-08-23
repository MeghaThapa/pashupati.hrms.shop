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
            "date_np" => "required",
            "godam" => "required",
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm"
        ]);

        $file = $request->file("file");
        $godam = $request->godam;
        $type = $request->type;
        $date_np = $request->date_np;

        try{
            $fabricImport = new FabricOpeningImport($godam,$type,$date_np);
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

      public function viewBill(Request $request){

        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();
        $godam_id = $request->godam_id ?? null ;
        $type = $request->type ?? null ;
        $fabric_name = $request->name ?? null ;
        $fabricgroup_id = $request->group ?? null ;

        $godams=Godam::where('status','active')->get(['id','name']);

        $sum = 0;

        $fabrics = FabricStock::where('status',1);

            if ($godam_id  || $godam_id  != null) {
                $fabrics = $fabrics->where('godam_id',$godam_id);
                $sum = $fabrics->sum('net_wt');
                
            }
            $find_godam = Godam::where('id',$godam_id)->value('name');


            if($fabricgroup_id || $fabricgroup_id !=null){
                $fabrics = $fabrics->where('fabricgroup_id',$fabricgroup_id);
                $sum = $fabrics->sum('net_wt');
            }

            $find_group = FabricGroup::where('id',$fabricgroup_id)->value('name');
            // dd($find_group);

            $find_name = FabricStock::where('id',$request->name)->value('name');

            if($fabric_name || $fabric_name !=null){
                $fabrics = $fabrics->where('name',$find_name);
                $sum = $fabrics->sum('net_wt');
            }

            if($type || $type !=null){
                $fabrics = $fabrics->where('is_laminated', $type);
                $sum = $fabrics->sum('net_wt');
            }
            // dd($type);

            if($type == 'true'){
               $find_type = 'Lam';
            }else if($type == 'false'){
               $find_type = 'Unlam';
            }else{
                $find_type = 'All';
            }

           

            // lm is true unlam is false

            $datas= $fabrics->orderBy('name')->get();
            
            $total_gross = $fabrics->sum('gross_wt');
            $total_net = $fabrics->sum('net_wt');
            $total_meter = $fabrics->sum('meter');

            $totaldatas = $fabrics->select('name', DB::raw('SUM(gross_wt) as total_gross'),DB::raw('SUM(net_wt) as total_net'),DB::raw('SUM(meter) as total_meter'),DB::raw('COUNT(name) as total_count'))
                ->groupBy('name')
                ->get();


        return view('admin.fabric.fabric_stock.viewbill',
        compact('settings','sum','godams','request','datas','totaldatas','total_gross','total_net','total_meter','find_godam','find_name','find_type','find_group'));
    }
}
