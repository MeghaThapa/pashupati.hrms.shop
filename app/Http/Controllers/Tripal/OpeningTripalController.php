<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoubleSideLaminatedFabricStock;
use App\Models\DoubleSideLaminatedFabric;
use App\Models\Wastages;
use App\Models\WasteStock;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\Singlesidelaminatedfabric;
use App\Models\Singlesidelaminatedfabricstock;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\Shift;
use App\Models\TripalEntry;
use App\Models\FinalTripalName;
use App\Models\FinalTripal;
use App\Models\FinalTripalStock;
use App\Models\FinalTripalOpeningStock;
use App\Helpers\AppHelper;
use Carbon\Carbon;

class OpeningTripalController extends Controller
{
    public function index()
    {
        
        $bill = AppHelper::getFinalTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $singlestocks  = Singlesidelaminatedfabricstock::where('bill_number' ,'!=', 'Opening')->get();
        $openingstock  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->sum('net_wt');

        $openingstocks  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->get();

        $total_net  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->sum('net_wt');
        $total_meter  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->sum('meter');

        return view('admin.tripal.openingtripal.index',compact('bill','bill_date','godam','singlestocks','openingstock','openingstocks','total_meter','total_net'));
    }


    public function storeData(Request $request)
    {

       // dd($request);
       $find_data = Singlesidelaminatedfabricstock::find($request->fabric_id);
       // dd($find_data);
       if($request['net_wt'] > $find_data->net_wt){
           return response()->json([
               'message' => 'Not Enough Stock',
           ], 400);

       }
       else{

           $final_net_wt = $find_data->net_wt + $request['net_wt'];
           $find_data->net_wt = $final_net_wt;
           $find_data->update();
           // dd($final_net_wt);

           $single_stock = Singlesidelaminatedfabricstock::create([
               "singlelamfabric_id" => $find_data->singlelamfabric_id,
               "name" => $find_data->name,
               "slug" => $find_data->slug,
               "roll_no" => $request['roll'], 
               "department_id" => $request['godam_id'],
               "bill_number" => $request['bill_number'],
               'bill_date' => $request['bill_date'],
               "gram" =>  $request['gram_wt'],
               "average_wt" => $request['average'],
               "roll_no" => $request['roll'],
               'net_wt' => $request['net_wt'],
               "meter" => $request['meter'],
               "loom_no" => $find_data->loom_no,
               'gross_wt' => $find_data->gross_wt,
             
               "planttype_id" => $find_data->planttype_id,
               "plantname_id" => $find_data->plantname_id,
               "fabric_id" => $find_data->fabric_id,
           ]);
           return redirect()->back();



       }
      
        
       
    }

    //douletripaloopening

    public function getOpeningDoubleTripalIndex()
    {
        // dd('kk');
        
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $singlestocks  = DoubleSideLaminatedFabricStock::where('bill_number' ,'!=', 'Opening')->get();
        $openingstock  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->sum('net_wt');

        $openingstocks  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->get();

        $total_net  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->sum('net_wt');
        $total_meter  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->sum('meter');

        return view('admin.tripal.openingdoubletripal.index',compact('bill_date','godam','singlestocks','openingstock','openingstocks','total_meter','total_net'));
    }

    public function storeDoubleData(Request $request)
    {
        // dd($request);
        $find_data = DoubleSideLaminatedFabricStock::find($request->fabric_id);
        // dd($find_data);
        if($request['net_wt'] > $find_data->net_wt){
            return response()->json([
                'message' => 'Not Enough Stock',
            ], 400);

        }
        else{


            $final_net_wt = $find_data->net_wt + $request['net_wt'];
            $find_data->net_wt = $final_net_wt;
            $find_data->update();
            // dd($final_net_wt);

            $single_stock = DoubleSideLaminatedFabricStock::create([
                "doublelamfabric_id" => $find_data->doublelamfabric_id,
                "name" => $find_data->name,
                "slug" => $find_data->slug,
                "roll_no" => $request['roll'], 
                "department_id" => $request['godam_id'],
                "bill_number" => $request['bill_number'],
                'bill_date' => $request['bill_date'],
                "gram" =>  $request['gram_wt'],
                "average_wt" => $request['average'],
                "roll_no" => $request['roll'],
                'net_wt' => $request['net_wt'],
                "meter" => $request['meter'],
                "loom_no" => $find_data->loom_no,
                'gross_wt' => $find_data->gross_wt,
              
                "planttype_id" => $find_data->planttype_id,
                "plantname_id" => $find_data->plantname_id,
                "fabric_id" => $find_data->fabric_id,
            ]);

            return back();

        }
        
       
    }


    //douletripaloopening

    public function getOpeningFinalTripalIndex()
    {
        // dd('kk');
        
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $singlestocks  = FinalTripalName::get();
        $openingstock  = FinalTripalOpeningStock::where('type' , 'opening')->sum('net_wt');

        $openingstocks  = FinalTripalOpeningStock::where('type' , 'opening')->get();
        // dd($openingstocks);

        $total_net  = FinalTripalOpeningStock::where('type' , 'opening')->sum('net_wt');
        $total_meter  = FinalTripalOpeningStock::where('type' , 'opening')->sum('meter');

        return view('admin.tripal.openingfinaltripal.index',compact('bill_date','godam','singlestocks','openingstock','openingstocks','total_meter','total_net'));
    }

    public function storeFinalData(Request $request)
    {
        // dd($request);
        $find_data = FinalTripalName::find($request->fabric_id);
        // dd($getdata);
        $single_stock = FinalTripalOpeningStock::create([
            "finaltripalname_id" => $request->fabric_id,
            "name" => $find_data->name,
            "slug" => $find_data->slug,
            "roll_no" => $request['roll'], 
            "godam_id" => $request['godam_id'],
            "bill_number" => $request['bill_number'],
            'bill_date' => $request['bill_date'],
            "gram" =>  $request['gram'],
            "average" => $request['average'],
            "roll" => $request['roll'],
            'net_wt' => $request['net_wt'],
            "meter" => $request['meter'],
          
            "date_en" => date('Y-m-d'),
            "date_np" => date('Y-m-d'),
        ]);
        return back();
        
       
    }

  
}
