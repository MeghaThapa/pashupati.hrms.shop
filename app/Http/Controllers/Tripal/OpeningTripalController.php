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
use App\Models\Singletripalname;
use App\Models\DoubleTripalName;
use App\Helpers\AppHelper;
use Carbon\Carbon;

class OpeningTripalController extends Controller
{
    public function index()
    {
        
        $bill = AppHelper::getFinalTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $singlestocks  = Singletripalname::get();
        $openingstock  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->sum('net_wt');

        $openingstocks  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->paginate(15);

        $total_net  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->sum('net_wt');
        $total_meter  = Singlesidelaminatedfabricstock::where('bill_number' , 'Opening')->sum('meter');

        return view('admin.tripal.openingtripal.index',compact('bill','bill_date','godam','singlestocks','openingstock','openingstocks','total_meter','total_net'));
    }


    public function storeData(Request $request)
    {

       // dd($request);
       $find_data = Singletripalname::find($request->fabric_id);

       $single_stock = Singlesidelaminatedfabricstock::create([
           "singletripalname_id" => $request->fabric_id,
           "name" => $find_data->name,
           "slug" => $find_data->slug,
           "roll_no" => $request['roll'], 
           "department_id" => $request['godam_id'],
           "bill_number" => $request['bill_number'],
           'bill_date' => $request['bill_date'],
           'gross_wt' => $request['gross_wt'],
           "roll_no" => $request['roll'],
           'net_wt' => $request['net_wt'],
           "meter" => $request['meter'],
           "average_wt" => $request['average'],
           // "gram" =>  $request['gram'],
           "loom_no" => '0',
           "status" => 'completed',
         
           // "planttype_id" => $find_data->planttype_id,
           // "plantname_id" => $find_data->plantname_id,
           // "fabric_id" => $find_data->fabric_id,
       ]);
       return redirect()->back();
     
      
        
       
    }

    //douletripaloopening

    public function getOpeningDoubleTripalIndex()
    {
        // dd('kk');
        
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $singlestocks  = DoubleTripalName::get();
        $openingstock  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->sum('net_wt');

        $openingstocks  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->paginate(15);

        $total_net  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->sum('net_wt');
        $total_meter  = DoubleSideLaminatedFabricStock::where('bill_number' , 'Opening')->sum('meter');

        return view('admin.tripal.openingdoubletripal.index',compact('bill_date','godam','singlestocks','openingstock','openingstocks','total_meter','total_net'));
    }

    public function storeDoubleData(Request $request)
    {
        $find_data = DoubleTripalName::find($request->fabric_id);

        $single_stock = DoubleSideLaminatedFabricStock::create([
            "doubletripalname_id" => $request->fabric_id,
            "name" => $find_data->name,
            "slug" => $find_data->slug,
            "roll_no" => $request['roll'], 
            "department_id" => $request['godam_id'],
            "bill_number" => $request['bill_number'],
            'bill_date' => $request['bill_date'],
            "gram" =>  $request['gram'],
            "average_wt" => $request['average'],
            "roll_no" => $request['roll'],
            'net_wt' => $request['net_wt'],
            "meter" => $request['meter'],
            "loom_no" => '0',
            'gross_wt' => $request['gross_wt'],
            "status" => 'completed',
          
        ]);

        return back();
       
        
       
    }


    //douletripaloopening

    public function getOpeningFinalTripalIndex()
    {
        // dd('kk');
        
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $singlestocks  = FinalTripalName::get();
        $openingstock  = FinalTripalStock::where('bill_number' , 'Opening')->sum('net_wt');

        $openingstocks  = FinalTripalStock::where('bill_number' , 'Opening')->paginate(10);
        // dd($openingstocks);

        $total_net  = FinalTripalStock::where('bill_number' , 'Opening')->sum('net_wt');
        $total_meter  = FinalTripalStock::where('bill_number' , 'Opening')->sum('meter');

        return view('admin.tripal.openingfinaltripal.index',compact('bill_date','godam','singlestocks','openingstock','openingstocks','total_meter','total_net'));
    }

    public function storeFinalData(Request $request)
    {
        // dd($request);
        $find_data = FinalTripalName::find($request->fabric_id);
      

        $finaltripalstock = FinalTripalStock::create([
            "name" => $find_data->name,
            "slug" => $find_data->slug,
            "bill_number" => $request['bill_number'],
            'bill_date' => $request['bill_date'],
            "department_id" => $request['godam_id'],
            "finaltripalname_id" => $request->fabric_id,
            "loom_no" => '0',
            'gross_wt' => $request['gross_wt'],
            "roll_no" => $request['roll'],
            "gram" =>  $request['gsm'],
            'net_wt' => $request['net_wt'],
            "meter" => $request['meter'],
            "average_wt" => $request['average'],
            "gsm" => $request['gsm'],
            // "finaltripal_id" => $finaltripal->id,
            "date_en" => date('Y-m-d'),
            "date_np" => date('Y-m-d'),

            "status" => 'completed',
        ]);

        return back();
        
       
    }

    //edit section for tripal


    public function getOpeningFinalTripalEdit($id)
    {
        $finaltripalstocks = FinalTripalStock::find($id);
        // dd($finaltripalstocks);
        $finaltripalname = FinalTripalName::get();
        $godam= Godam::where('status','active')->get();
        return view('admin.tripal.openingfinaltripal.edit', compact('finaltripalstocks','godam','finaltripalname'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function getOpeningFinalTripalUpdate(Request $request, $id)
    {
        // dd($request);

        //validate form
        // $validator = $request->validate([
        //     'godam_id' => 'required',
        //     'tripal' => 'required',
        //     'roll' => 'required',
        //     'gram' => 'required',
        //     'net_wt' => 'required',
        //     'meter' => 'required',
        //     'average' => 'required',
        //     'gsm' => 'required',
        // ]);

        $finaltripal = FinalTripalStock::find($id);


        $tripalname = FinalTripalName::find($request->fabric_id);


        $finaltripal->update([
            "name" => $tripalname->name,
            "slug" => $tripalname->slug,
            "bill_number" => $request['bill_number'],
            'bill_date' => $request['bill_date'],
            "department_id" => $request['godam_id'],
            "finaltripalname_id" => $request->fabric_id,
            "loom_no" => '0',
            'gross_wt' => '0',
            "roll_no" => $request['roll'],
            "gram" =>  $request['gram_wt'],
            'net_wt' => $request['net_wt'],
            "meter" => $request['meter'],
            "average_wt" => $request['average'],
            "gsm" => $request['gsm'],
            // "finaltripal_id" => $finaltripal->id,
            "date_en" => $request['bill_date'],
            "date_np" => date('Y-m-d'),

            "status" => "sent"
        ]);


        return redirect()->route('openingfinaltripal.index')->withSuccess('Tripal updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroyTripal($id)
    {
        // dd('kk');
        $category = FinalTripalStock::find($id);
        // destroy category
        $category->delete();
        return redirect()->route('openingfinaltripal.index')->withSuccess('Fabric deleted successfully!');
    }

  
}
