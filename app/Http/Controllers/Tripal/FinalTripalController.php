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
use App\Models\SinglesidelaminatedfabricStock;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\Shift;
use App\Models\TripalEntry;
use App\Models\FinalTripalName;
use App\Models\FinalTripal;
use App\Models\FinalTripalStock;
use App\Helpers\AppHelper;
use App\Models\FinalTripalDanaConsumption;
use Carbon\Carbon;
use App\Models\FinalTripalBill;


class FinalTripalController extends Controller
{
    public function index()
    {

        $bill_no = AppHelper::getFinalTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $shifts = Shift::where('status','active')->get();
        $dana = AutoLoadItemStock::get();
        $fabrics  = DoubleSideLaminatedFabricStock::get()->unique('name')->values()->all();;
        $finaltripalname  = FinalTripalName::get();
        $sumdana = FinalTripalDanaConsumption::where('bill_no',$bill_no)->sum('quantity');

        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();

        $datas = FinalTripalBill::get();
        // dd($fabrics);
        return view('admin.finaltripal.index',compact('bill_no','bill_date','godam','shifts','fabrics','dana','finaltripalname','godams','sumdana','datas'));
    }


    public function createFinaltripal($id)
    {
        $find_data = FinalTripalBill::find($id);
        $bill_date = $find_data->bill_date;
        $bill_no = $find_data->bill_no;
        
        $godam= Godam::where('status','active')->get();
        $shifts = Shift::where('status','active')->get();
        $dana = AutoLoadItemStock::get();
        $fabrics  = DoubleSideLaminatedFabricStock::get()->unique('name')->values()->all();;
        $finaltripalname  = FinalTripalName::get();
        $sumdana = FinalTripalDanaConsumption::where('bill_no',$bill_no)->sum('quantity');

        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();

        return view('admin.finaltripal.create',compact('bill_no','bill_date','godam','shifts','fabrics','dana','finaltripalname','godams','sumdana','id','find_data'));


    }

    public function getfilter(Request $request){
        
        $tripal_id = $request->tripal;
        $find_data = FinalTripalName::find($tripal_id);
        
        $input = $find_data->name;
        $parts = explode(' ', $input);
        $firstString = $parts[0];   
                
        $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        return response([
            'name' => $find_name,
        ]);


    }

    public function getDoubleFabricStockList(Request $request){
        
        if($request->ajax()){
            if($request->fabric_id != null){
              $fabric_name = DoubleSideLaminatedFabricStock::where('id',$request->fabric_id)->value('name');

              $fabrics = DoubleSideLaminatedFabricStock::where('name',$fabric_name)->get();
            }
            if($request->roll){
                $fabrics = DoubleSideLaminatedFabricStock::where('roll_no' , $roll)->get();
            }
            
            return response([
                'response'=>$fabrics,
                'bill_number' => $request->bill_number,
                'bill_date' => $request->bill_date,
                'plantname_id' => $request->plantname_id,
                'godam_id' => $request->godam_id,
                'plantype_id' => $request->plantype_id,
                'shift_id' => $request->shift_id
            ]);
          
        }
    }

    public function storeTripalEntry(Request $request){
        // dd('ll');
        // dd($request);
        $getfabric = DoubleSideLaminatedFabricStock::find($request['data_id']);
        $fabric_id = DoubleSideLaminatedFabric::where('id',$getfabric->doublelamfabric_id)->value('fabric_id');

       

        $double_lamfabric = TripalEntry::create([
            "name" => $getfabric->name,
            "slug" => $getfabric->name,
            "fabric_id" => $fabric_id,
            "department_id" => $request['godam_id'],
            "gram" =>  $getfabric->gram,
            "loom_no" => $getfabric->loom_no,
            "average_wt" => $getfabric->average_wt,
            'gross_wt' => $getfabric->gross_wt,
            "roll_no" => $getfabric->roll_no,
            'net_wt' => $getfabric->net_wt,
            "meter" => $getfabric->meter,
            "bill_number" => $request['bill_number'],
            'bill_date' => $request['bill_date'],
            "planttype_id" => $request['plantype_id'],
            "plantname_id" => $request['plantname_id'],
            "doublefabric_id" => $request['data_id'],
            "date_en" => $request['bill_date'],
            "date_np" => $request['bill_date'],
            "bill_id" => $request['bill_id'],
            "status" => "sent"
        ]);
    }

    public function getTripalFabricEntry(Request $request){
        // dd($request);
        if($request->ajax()){
          
            $tripalentry = TripalEntry::where("status",'sent')->get();
            $finaltripal = FinalTripal::where("status",'sent')->get();
            // dd($tripalentry);

            $unlam = DoubleSidelaminatedfabricstock::with('fabric')->where('status',"sent")->get();
            $ul_mtr_total=0;
            $ul_net_wt_total = 0;
            // dd($unlam);

            $unlamnet_wt = DoubleSidelaminatedfabricstock::with('fabric')->where('status',"sent")->sum('net_wt');
            $unlamnet_meter = DoubleSidelaminatedfabricstock::with('fabric')->where('status',"sent")->sum('meter');
            
            $lam = FinalTripalStock::where('status','sent')->get();
            // dd($lam);

            $lam_mtr_total = FinalTripalStock::with('fabric')->where('status',"sent")->sum('net_wt');
            // dd($lam_mtr_total);
            $lam_net_wt_total = FinalTripalStock::with('fabric')->where('status',"sent")->sum('meter');
            // dd($lam_mtr_total,$lam_net_wt_total);
            
            return response([
                'tripalentry'=>$tripalentry,
                'finaltripal'=>$finaltripal,
                "ul_mtr_total" => $unlamnet_meter,
                "ul_net_wt_total" => $unlamnet_wt,
                "lam_mtr_total" => $lam_mtr_total,
                "lam_net_wt_total" => $lam_net_wt_total
              
            ]);
          
        }
    }

    public function storeTripalName(Request $request){
        // dd('hey',$request);
        // $validator = $request->validate([
        //     'name'    => 'required|unique:final_tripal_names,name',
        // ]);

        $todayEnglishDate = Carbon::now()->format('Y-n-j');

        FinalTripalName::create([
            'name' => $request['name'],
            'slug' => $request['name'],
            'date_en' => $todayEnglishDate,
            'date_np' => $todayEnglishDate,
        ]);
         return back();

    

   
    }



    public function store(Request $request){
        // dd('hey');
        // $validator = $request->validate([
        //     'name'    => 'required|unique:final_tripal_names,name',
        // ]);
        // dd($request);

        $find_tripal_bill = TripalEntry::where('bill_number',$request->bill_no)->value('id');
        $bill_id = $request->bill_id;
        // dd($)
        $find_bill = TripalEntry::find($find_tripal_bill);
        $findtripalname = FinalTripalName::where('id',$request->tripal)->value('name');
        // dd($request,$find_bill);

        $todayEnglishDate = Carbon::now()->format('Y-n-j');

        $finaltripal = FinalTripal::create([
            "name" => $findtripalname,
            "slug" => $findtripalname,
            "bill_number" => $request['bill_no'],
            'bill_date' => $request['bill_date'],
            "planttype_id" => $find_bill->planttype_id,
            "plantname_id" => $find_bill->plantname_id,
            "doublefabric_id" => $find_bill->doublefabric_id,
            "fabric_id" => $find_bill->fabric_id,
            "department_id" => $find_bill->department_id,
            "finaltripalname_id" => $request->tripal,
            "gram" =>  $find_bill->gram,
            "loom_no" => $find_bill->loom_no,
            "roll_no" => $request['roll'],
            'gross_wt' => $request['gross_weight'],
            "meter" => $request['meter'],
            "average_wt" => $request['average'],
            "gsm" => $request['gsm'],
            'net_wt' => $request['net_wt'],
            "date_en" => $request['net_wt'],
            "date_np" => $request['net_wt'],
            "bill_id" => $bill_id,
            "status" => "sent"
        ]);

        $finaltripalstock = FinalTripalStock::create([
            "name" => $findtripalname,
            "slug" => $findtripalname,
            "bill_number" => $request['bill_no'],
            'bill_date' => $request['bill_date'],
            "planttype_id" => $find_bill->planttype_id,
            "plantname_id" => $find_bill->plantname_id,
            "doublefabric_id" => $find_bill->doublefabric_id,
            "fabric_id" => $find_bill->fabric_id,
            "department_id" => $find_bill->department_id,
            "finaltripalname_id" => $request->tripal,
            "gram" =>  $find_bill->gram,
            "loom_no" => $find_bill->loom_no,
            "roll_no" => $request['roll'],
            'gross_wt' => $request['gross_weight'],
            "meter" => $request['meter'],
            "average_wt" => $request['average'],
            "gsm" => $request['gsm'],
            'net_wt' => $request['net_wt'],
            "finaltripal_id" => $finaltripal->id,
            "date_en" => $request['net_wt'],
            "date_np" => $request['net_wt'],
            "bill_id" => $bill_id,

            "status" => "sent"
        ]);

        return back();
    }

    public function getWastageStore(Request $request){

        if($request->ajax()){
            // dd($request);
            // dd('kk');
            $consumption = $request->consumption;
            $danaNameID = $request->danaNameID;
            $fabric_waste = $request->fabric_waste;
            $polo_waste = $request->polo_waste;
            $selectedDanaID = $request->selectedDanaID;
            $total_waste  = $request->total_waste;
            $lamFabricToDelete = [];
            $lamFabricTempToDelete = [];
            $department = [];

            // dd($department);

            try{
                DB::beginTransaction();

                  $getFabricLastId = FinalTripalStock::where('status','sent')->where('bill_number',$request->bill)->latest()->first();

                 
                    $getsinglesidelaminatedfabric = TripalEntry::where('bill_number',$getFabricLastId->bill_number)->update(['status' => 'completed']); 

                    $getdoublesidelaminatedfabric = FinalTripal::where('bill_number',$getFabricLastId->bill_number)->update(['status' => 'completed']); 

                    $getdoublesidelaminatedfabricstock = FinalTripalStock::where('bill_number',$getFabricLastId->bill_number)->update(['status' => 'completed']); 

                    $find_godam = FinalTripalStock::where('bill_number',$request->bill)->latest()->first();

                    if($fabric_waste != null){

                        $wastename = 'tripal';

                        $wastage = Wastages::firstOrCreate([
                         'name' => 'tripal'
                         ], [
                         'name' => 'tripal',
                         'is_active' => '1',

                         ]);

                        $waste_id = Wastages::where('name',$wastename)->value('id');

                        $stock = WasteStock::where('godam_id', $find_godam->department_id)
                        ->where('waste_id', $wastage->id)->count();
                        // dd($stock);

                        $getStock = WasteStock::where('godam_id', $find_godam->department_id)
                        ->where('waste_id', $wastage->id)->first();
                        

                        if ($stock == 1) {
                            $getStock->quantity_in_kg += $fabric_waste;
                            $getStock->save();
                        } else {
                            WasteStock::create([
                                'godam_id' => $find_godam->department_id,
                                'waste_id' => $wastage->id,
                                'quantity_in_kg' => $fabric_waste,
                            ]);
                        }


                    }



                DB::commit();

                return response(200);
            }catch(Exception $e){
                DB::rollBack();
                return response([
                    "exception" => $e->getMessage(),
                ]);
            }
        }
    }
}
