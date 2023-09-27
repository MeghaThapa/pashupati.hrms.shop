<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\FabricStock;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Fabric;
use App\Models\Unit;
use App\Models\UnlaminatedFabric;
use App\Models\Unlaminatedfabrictripal;
use App\Models\UnlaminatedFabricStock;
use App\Models\Wastages;
use App\Models\WasteStock;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\SingleSideunlaminatedFabric;
use App\Models\Singlesidelaminatedfabric;
use App\Models\Singlesidelaminatedfabricstock;
use App\Models\DoubleSideLaminatedFabric;
use App\Models\DoubleSideLaminatedFabricStock;
use App\Models\DoubleTripalName;
use App\Models\DoubleTripalDanaConsumption;
use App\Models\DoubleTripalBill;
use App\Helpers\AppHelper;
use Yajra\DataTables\DataTables;

class DoubleTripalController extends Controller
{
    public function index()
    {

        $bill_no = AppHelper::getDoubleTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $shifts = Shift::where('status','active')->get();
        $godam = Godam::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();
        $dana = AutoLoadItemStock::get();
        $fabrics = Singlesidelaminatedfabricstock::get()->unique('name')->values()->all();

        $sumdana = DoubleTripalDanaConsumption::where('bill_no',$bill_no)->sum('quantity');

        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();
        $datas = DoubleTripalBill::get();

        // dd($fabrics);
        return view('admin.doubletripal.index',compact('godam','planttype','plantname','shifts','bill_no',"dana",'fabrics','bill_date','godams','sumdana','datas'));
    }

    public function dataTable()
    {
        // dd('lol');
        $tripalGodam = DoubleTripalBill::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($tripalGodam)
            ->addIndexColumn()
            ->addColumn('planttype', function ($row) {
                return $row->getPlantType->name;
            })
            ->addColumn('plantname', function ($row) {
                return $row->getPlantName->name;
            })
            ->addColumn('shift', function ($row) {
                return $row->getShift->name;
            })
            ->addColumn('godam', function ($row) {
                return $row->getGodam->name;
            })
            ->addColumn('action', function ($row) {

                if($row->status == "sent"){

                    return '<a href="' . route('adddoubletripal.create', ['id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }
                else{
                    // return'completed';

                    return '<a href="' . route('doubleTripal.viewBill', ['bill_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }

            })
            ->rawColumns(['fromgodam','togodam','action'])
            ->make(true);
    }

    public  function viewBill($bill_id){

        $find_data = DoubleTripalBill::find($bill_id);
        // dd($find_data);
        $unlam_datas = SingleSideunlaminatedFabric::where('bill_id',$bill_id)->get();
        $stocks = DoubleSideLaminatedFabric::where('bill_id',$bill_id)->get();
        // dd($stocks);
          
        return view('admin.doubletripal.viewbill',compact('unlam_datas','stocks','bill_id','find_data'));

    }

    public function createDoubletripal($id)
    {
        $find_data = DoubleTripalBill::find($id);
        $bill_date = $find_data->bill_date;
        $bill_no = $find_data->bill_no;
        $planttype_id = $find_data->planttype_id;
        $plantname_id = $find_data->plantname_id;
        $shift_id = $find_data->shift_id;
        $godam_id = $find_data->godam_id;
        
        $shifts = Shift::where('status','active')->get();
        $godam = Godam::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();

        $danas = AutoLoadItemStock::where('plant_type_id',$planttype_id)
                               ->where('plant_name_id',$plantname_id)
                               ->where('shift_id',$shift_id)
                               ->where('from_godam_id',$godam_id)
                               ->get();
        $fabrics = Singlesidelaminatedfabricstock::get()->unique('name')->values()->all();

        $sumdana = DoubleTripalDanaConsumption::where('bill_no',$bill_no)->sum('quantity');

        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();
        $datas = DoubleTripalBill::get();
        $danalist = DoubleTripalDanaConsumption::where('bill_id',$id)->get();

        // dd($fabrics);
        return view('admin.doubletripal.create',compact('godam','planttype','plantname','shifts','bill_no','fabrics','bill_date','godams','sumdana','datas','find_data','id','danalist','danas'));

    }


    public function getSingleLamFabric(Request $request){
        
        if($request->ajax()){
            $getAllfabrics = SinglesidelaminatedfabricStock::where('id',$request->fabric_id)->value('name');
            $fabrics = SinglesidelaminatedfabricStock::where('name',$getAllfabrics)->get();
            
            return response(['response'=>$fabrics]);
   
        }
    }
    public function getplanttype(Request $request){
        if($request->ajax()){
            $department_id =  $request->id;
            $planttype = ProcessingStep::where('godam_id',$department_id)->get();
            return response([
                'planttype' => $planttype
            ]);
        }
    }
    public function getplantname(Request $request){
        if($request->ajax()){
            $department_id =  $request->id;
            $plantname = ProcessingSubcat::where('processing_steps_id',$department_id)->get();
            return response([
                'plantname' => $plantname
            ]);
        }
    }

    public function getfabrics(Request $request){
        // dd($request);
        if($request->ajax()){
            $fabrics = Fabric::where('status','1')->get();
            // dd($fabrics);
            return response(['response'=>$fabrics]);
            // return response([
            //     'fabrics' => $fabrics
            // ]);
        }
    }

    public function sendunlaminated(Request $request){
        if($request->ajax()){
            // return $request->data;
            $data = [];
            parse_str($request->data,$data);
            $fabricDetails = Fabric::where('id',$data['fabric_name_id'])->first();
            $name = $fabricDetails->name;
            $roll_number = $fabricDetails->roll_no;
            $gross_weight = $fabricDetails->gross_wt;
            $net_wt = $fabricDetails->net_wt;
            $meter = $fabricDetails->meter; 
            // $gram = $fabricDetails->fabricgroup->name;
            $gram = $fabricDetails->gram;
            $avg = ($net_wt + $gross_weight)/2;

            try{
                DB::beginTransaction();

                UnlaminatedFabric::create([
                    'bill_number' => $data['bill_number'],
                    'bill_date' => $data['bill_date'],
                    'fabric_id' =>$data['fabric_name_id'] ,
                    'roll_no' => $roll_number ,
                    'gross_wt' => $gross_weight ,
                    'net_wt' => $net_wt,
                    'meter' => $meter,
                    'average' => $avg,
                    'gram' => $gram,
                    'department_id' =>$data['to_godam_id'],
                    'planttype_id' => $data['plant_type_id'],
                    'plantname_id' =>  $data['plant_name_id']
                ]);

                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                return response([
                    "message" => "Something went wrong!{$e->getMessage()}" 
                ]);
            }
        }

    }
    public function getunlaminated(){
        $data = UnlaminatedFabric::with('fabric')->where('status','pending')->get();
        if(count($data) != 0){
            return response(['response'=>$data]);
        }else{
            return response(['response'=> '404']);
        }   
    }

    public function sendunlaminateddelete(Request $request,$id){
        // if($request->ajax()){
        //    try{
        //         DB::beginTransaction();
        //         $count = UnlaminatedFabric::where('id',$id)->get();
        //         $unlaminatedStock = UnlaminatedFabricStock::where('id',$id)->get();
        //         if(count($count) > 0 && count($unlaminatedStock) > 0 ){
        //             UnlaminatedFabric::where('id',$id)->delete();
        //             UnlaminatedFabricStock::where('id',$id)->delete();
        //             DB::commit();
        //             return response([
        //                 'response'=> "200",
        //             ]);
                
        //         }else{
        //             DB::rollBack();
        //             return response([
        //                 'response'=> "400",
        //             ]);
        //         }
                
        //    }catch(Exception $e){
        //         DB::rollBack();
        //         return response([
        //             "message" => "Something went wrong!'{$e->getMessage()}'"
        //         ]);
        //    }
        // }
    }
    public function store(Request $request){
        try{
            // dd('lol');

            $data = [];
            parse_str($request->data,$data);
            
            $fabric_ids = $data['fabricsid'];
            $bill_id = $data['bill_id'];
            // dd($request);
            //thiis fabric id is singlesidestockid

            $fabric_data = Singlesidelaminatedfabricstock::find($fabric_ids);
            $getdata = Singlesidelaminatedfabric::where('id',$fabric_data->singlelamfabric_id)->value('fabric_id');
            $fabric_id = $getdata;
            // dd($fabric_id,$getdata,$fabric_data);

           DB::beginTransaction();

      

            SingleSideunlaminatedFabric::create([
                'bill_id' => $bill_id,
                'bill_number' => $data['bill_no'],
                'bill_date' => $data['bill_date'],
                'fabric_id' =>$fabric_id ,
                'name' => $fabric_data->name ,
                'slug' => $fabric_data->slug ,
                'roll_no' => $fabric_data->roll_no ,
                'gross_wt' => $fabric_data->gross_wt ,
                'net_wt' => $fabric_data->net_wt,
                'meter' => $fabric_data->meter,
                'average' => $fabric_data->average_wt,
                'gram' => $fabric_data->gram,
                'department_id' =>$data['godam_id'],
                'planttype_id' => $data['planttype_id'],
                'plantname_id' =>  $data['plantname_id'],
                'status' => 'sent',
            ]);

            // dd('hello');

            $fabricstock =  SingleSideunlaminatedFabric::with('fabric')->where('id',$fabric_id)->where('bill_number',$data['bill_no'])->latest()->first(); //where('id',$idoffabricforsendtolamination)->
            // dd($fabricstock);
            $department_id = $data['godam_id'];
            $planttype_id = $data['planttype_id'];
            $plantname_id = $data['plantname_id'];
            $bill_number = $data['bill_no'];
            $bill_date = $data['bill_date']; 
            // $meter = $fabricstock->meter;
            // $fabricgroup_id = $fabricstock->fabric->id;

            $lamimated_roll_no = $data['laminated_roll_no'];
            $lamimated_roll_no_2 = $data['laminated_roll_no_2'];
            $lamimated_roll_no_3 = $data['laminated_roll_no_3'];
    

            $laminated_gross_weight = $data['laminated_gross_weight'];
            $laminated_gross_weight_2 = $data['laminated_gross_weight_2'];
            $laminated_gross_weight_3 = $data['laminated_gross_weight_3'];
           
    

            $laminated_net_weight = $data['laminated_net_weight'];
            $laminated_net_weight_2 = $data['laminated_net_weight_2'];
            $laminated_net_weight_3 = $data['laminated_net_weight_3'];
       


            $laminated_avg_weight = $data['laminated_avg_weight'];
            $laminated_avg_weight_2 = $data['laminated_avg_weight_2'];
            $laminated_avg_weight_3 = $data['laminated_avg_weight_3'];
       

            $laminated_gram = $data['laminated_gram'];
            $laminated_gram_2 = $data['laminated_gram_2'];
            $laminated_gram_3 = $data['laminated_gram_3'];

            $laminated_meter = $data['laminated_meter'];
            $laminated_meter_2 = $data['laminated_meter_2'];
            $laminated_meter_3 = $data['laminated_meter_3'];
        

            $fabricmodelquery = Fabric::where('id',$fabric_id)->first();

           // dd($laminated_gross_weight,$laminated_gross_weight_2,$laminated_gross_weight_3);
                
                $fabric =  SingleSideunlaminatedFabric::with('fabric')->where('id',$fabric_id)->first();

         

                
                if($lamimated_roll_no != null && $laminated_gross_weight != null && $laminated_net_weight != null && $laminated_avg_weight != null && $laminated_gram != null){

                    $double_lamfabric = DoubleSideLaminatedFabric::create([
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "fabric_id" => $fabric_id,
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram,
                        // "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight,
                        'gross_wt' => $laminated_gross_weight,
                        "roll_no" => $lamimated_roll_no,
                        'net_wt' => $laminated_net_weight,
                        "meter" => $laminated_meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' => $bill_id,
                        "status" => "sent"
                    ]);
                    // dd('hello');

                    $doublelamfabric_id = $double_lamfabric->id;

                    $double_stock = DoubleSideLaminatedFabricStock::create([
                        "doublelamfabric_id" => $doublelamfabric_id,
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "roll_no" => $lamimated_roll_no, 
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram,
                        // "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight,
                        'gross_wt' => $laminated_gross_weight,
                        "roll_no" => $lamimated_roll_no,
                        'net_wt' => $laminated_net_weight,
                        "meter" => $laminated_meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' => $bill_id,
                        "status" => "sent"
                    ]);

                    $stock = Singlesidelaminatedfabricstock::where('id',$fabric_ids)->value('net_wt');
                    // dd($stock);
                    $fabrics_id = Singlesidelaminatedfabricstock::where('id',$fabric_ids)->value('id');
                    // dd($stock);

                    // if($laminated_net_weight != null){
                    //   $finalstock = $stock - $laminated_net_weight ;
                    //   // dd($stock,$laminated_net_weight);
                    //   $find_fabric = Singlesidelaminatedfabricstock::find($fabrics_id);
                    //   // dd($find_fabric);
                    //   $find_fabric->net_wt = $finalstock;
                    //   $find_fabric->update();

                    // }
                }
                if($lamimated_roll_no_2 != null && $laminated_gross_weight_2 != null && $laminated_net_weight_2 != null && $laminated_avg_weight_2 != null && $laminated_gram_2 != null){

                    $double_lamfabric = DoubleSideLaminatedFabric::create([
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "fabric_id" => $fabric_id,
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram_2,
                        // "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight_2,
                        'gross_wt' => $laminated_gross_weight_2,
                        "roll_no" => $lamimated_roll_no_2,
                        'net_wt' => $laminated_net_weight_2,
                        "meter" => $laminated_meter_2,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' => $bill_id,
                        "status" => "sent"
                    ]);
                    // dd('hello');

                    $doublelamfabric_id = $double_lamfabric->id;

                    $double_stock = DoubleSideLaminatedFabricStock::create([
                        "doublelamfabric_id" => $doublelamfabric_id,
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "roll_no" => $lamimated_roll_no, 
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram_2,
                        // "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight_2,
                        'gross_wt' => $laminated_gross_weight_2,
                        "roll_no" => $lamimated_roll_no_2,
                        'net_wt' => $laminated_net_weight_2,
                        "meter" => $laminated_meter_2,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' => $bill_id,
                        "status" => "sent"
                    ]);

                    $stock = Singlesidelaminatedfabricstock::where('id',$fabric_ids)->value('net_wt');
                    // dd($stock);
                    $fabrics_id = Singlesidelaminatedfabricstock::where('id',$fabric_ids)->value('id');
                    // dd($stock);

                    // if($laminated_net_weight != null){
                    //   $finalstock = $stock - $laminated_net_weight ;
                    //   // dd($stock,$laminated_net_weight);
                    //   $find_fabric = Singlesidelaminatedfabricstock::find($fabrics_id);
                    //   // dd($find_fabric);
                    //   $find_fabric->net_wt = $finalstock;
                    //   $find_fabric->update();

                    // }
                }

                if($lamimated_roll_no_3 != null && $laminated_gross_weight_3 != null && $laminated_net_weight_3 != null && $laminated_avg_weight_3 != null && $laminated_gram_3 != null){

                    $double_lamfabric = DoubleSideLaminatedFabric::create([
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "fabric_id" => $fabric_id,
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram_3,
                        // "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight_3,
                        'gross_wt' => $laminated_gross_weight_3,
                        "roll_no" => $lamimated_roll_no_3,
                        'net_wt' => $laminated_net_weight_3,
                        "meter" => $laminated_meter_2,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' => $bill_id,
                        "status" => "sent"
                    ]);
                    // dd('hello');

                    $doublelamfabric_id = $double_lamfabric->id;

                    $double_stock = DoubleSideLaminatedFabricStock::create([
                        "doublelamfabric_id" => $doublelamfabric_id,
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "roll_no" => $lamimated_roll_no, 
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram_3,
                        // "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight_3,
                        'gross_wt' => $laminated_gross_weight_3,
                        "roll_no" => $lamimated_roll_no_3,
                        'net_wt' => $laminated_net_weight_3,
                        "meter" => $laminated_meter_3,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' => $bill_id,
                        "status" => "sent"
                    ]);

              
                }

                Singlesidelaminatedfabricstock::where('id',$fabric_ids)->delete();


                // if($lamimated_roll_no_2 != null && $laminated_gross_weight_2 != null && $laminated_net_weight_2 != null && $laminated_avg_weight_2 != null && $laminated_gram_2 != null){

                //     $single_lamfabric = DoubleSideLaminatedFabric::create([
                //         "name" => $data['laminated_fabric_name'],
                //         // "fabric_id" => $data['fabric_id'],
                //         "slug" => Str::slug($data["laminated_fabric_name"]),
                //         "fabric_id" => $fabric_id,
                //         "gram" =>  $laminated_gram_2,
                //         "loom_no" => $fabricmodelquery->loom_no,
                //         "average_wt" => $laminated_avg_weight_2,
                //         'gross_wt' => $laminated_gross_weight_2,
                //         "roll_no" => $lamimated_roll_no_2,
                //         'net_wt' => $laminated_net_weight_2,
                //         "meter" => $fabricmodelquery->meter,
                //         "bill_number" => $bill_number,
                //         'bill_date' => $bill_date,
                //         "department_id" => $data['godam_id'],
                //         "planttype_id" => $planttype_id,
                //         "plantname_id" => $plantname_id,
                //         "status" => "1"
                //     ]);


                //     $singlelamfabric_id = $single_lamfabric->id;


                //     $single_stock = DoubleSideLaminatedFabricStock::create([
                //        "singlelamfabric_id" => $singlelamfabric_id,
                //        "name" => $data['laminated_fabric_name'],
                //        "slug" => Str::slug($data["laminated_fabric_name"]),
                //        "roll_no" => $lamimated_roll_no_2, 
                //        "department_id" => $data['godam_id'],
                //        "gram" =>  $laminated_gram,
                //        "loom_no" => $fabricmodelquery->loom_no,
                //        "average_wt" => $laminated_avg_weight_2,
                //        'gross_wt' => $laminated_gross_weight_2,
                //        "roll_no" => $lamimated_roll_no_2,
                //        'net_wt' => $laminated_net_weight_2,
                //        "meter" => $fabricmodelquery->meter,
                //        "bill_number" => $bill_number,
                //        'bill_date' => $bill_date,
                //        "planttype_id" => $planttype_id,
                //        "plantname_id" => $plantname_id

                //     ]);
                // }

                // if($lamimated_roll_no_3 != null && $laminated_gross_weight_3 != null && $laminated_net_weight_3 != null && $laminated_avg_weight_3 != null && $laminated_gram_3 != null){

                //     $single_lamfabric = Singlesidelaminatedfabric::create([
                //         "name" => $data['laminated_fabric_name'],
                //         // "fabric_id" => $data['fabric_id'],
                //         "slug" => Str::slug($data["laminated_fabric_name"]),
                //         "fabric_id" => $fabric_id,
                //         "gram" =>  $laminated_gram_3,
                //         "loom_no" => $fabricmodelquery->loom_no,
                //         "average_wt" => $laminated_avg_weight_3,
                //         'gross_wt' => $laminated_gross_weight_3,
                //         "roll_no" => $lamimated_roll_no_3,
                //         'net_wt' => $laminated_net_weight_3,
                //         "meter" => $fabricmodelquery->meter,
                //         "bill_number" => $bill_number,
                //         'bill_date' => $bill_date,
                //         "department_id" => $data['godam_id'],                     
                //         "planttype_id" => $planttype_id,
                //         "plantname_id" => $plantname_id,
                //         "status" => "1"
                //     ]);

                //     $singlelamfabric_id = $single_lamfabric->id;

                //     $singlelamstock = Singlesidelaminatedfabricstock::create([
                //         "singlelamfabric_id" => $singlelamfabric_id,
                //         "name" => $data['laminated_fabric_name'],
                //         "slug" => Str::slug($data["laminated_fabric_name"]),
                //         "roll_no" => $lamimated_roll_no_3, 
                //         "department_id" => $data['godam_id'],
                //         "gram" =>  $laminated_gram,
                //         "loom_no" => $fabricmodelquery->loom_no,
                //         "average_wt" => $laminated_avg_weight_3,
                //         'gross_wt' => $laminated_gross_weight_3,
                //         "roll_no" => $lamimated_roll_no_3,
                //         'net_wt' => $laminated_net_weight_3,
                //         "meter" => $fabricmodelquery->meter,
                //         "bill_number" => $bill_number,
                //         'bill_date' => $bill_date,
                //         "planttype_id" => $planttype_id,
                //         "plantname_id" => $plantname_id
                //     ]);
                // }

                
           DB::commit();
           return "Done";
        }
        catch(Exception $e){
            DB::rollback();
            dd($e);
            return "exception".$e->getMessage();
        }
    }
    
    public function getUnlamSingleDoubleLam(Request $request){
        if($request->ajax()){
            $unlam = SingleSideunlaminatedFabric::where('bill_id',$request->bill_id)->where('status',"sent")->get();
            $ul_mtr_total=0;
            $ul_net_wt_total = 0;

            $unlamnet_wt = SingleSideunlaminatedFabric::where('bill_id',$request->bill_id)->where('status',"sent")->sum('net_wt');
            $unlamnet_meter = SingleSideunlaminatedFabric::where('bill_id',$request->bill_id)->where('status',"sent")->sum('meter');
         
            $lam = DoubleSidelaminatedfabricstock::where('status','sent')->where('bill_id',$request->bill_id)->get();
            // dd($lam);

            $lam_mtr_total = DoubleSidelaminatedfabricstock::where('bill_id',$request->bill_id)->where('status',"sent")->sum('meter');
            $lam_net_wt_total = DoubleSidelaminatedfabricstock::where('bill_id',$request->bill_id)->where('status',"sent")->sum('net_wt');

       
            return response([
                "unlam" => $unlam,
                "lam" => $lam,
                "ul_mtr_total" => $unlamnet_meter,
                "ul_net_wt_total" => $unlamnet_wt,
                "lam_mtr_total" => $lam_mtr_total,
                "lam_net_wt_total" => $lam_net_wt_total
            ]);
        }
    }

    public function discard(Request $request){
        if($request->ajax()){
            try{
                $this->discardDelete();
                return response([
                    "message" => "200"
                ]);
            }
            catch(Exception $e){
                return response([
                    "message" => "{$e->getMessage()}"
                ]);
            }
        }
    }

    function discardDelete(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fabric_unlaminated')->truncate();
        DB::table('fabric_laminated')->truncate();
        DB::table('fabric_temporary_for_lamination')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
                // dd($request);

                $polowaste = $request->polo_waste;
                $fabricwaste = $request->fabric_waste;

                  $getFabricLastId = DoubleSideLaminatedFabricStock::where('bill_number',$request->bill)->where('status','sent')->latest()->first();

                  // dd($getFabricLastId);

                    // $stocks = AutoLoadItemStock::where('id',$request->selectedDanaID)->value('dana_name_id');

                    // $stock = AutoLoadItemStock::where('dana_name_id',$stocks)->first();

                    // $presentQuantity = $stock->quantity;
                    // $deduction = $presentQuantity - $consumption;

                    // if($deduction == 0){
                    //     $stock->delete();
                    // }
                    // else{
                    //     $stock->update([
                    //         "quantity" => $deduction
                    //     ]);
                    // }

                    $getsinglesidelaminatedfabric = SingleSideunlaminatedFabric::where('bill_number',$request->bill)->update(['status' => 'completed']); 

                    $getdoublesidelaminatedfabric = DoubleSideLaminatedFabric::where('bill_number',$request->bill)->update(['status' => 'completed']); 

                    $getdoublesidelaminatedfabricstock = DoubleSideLaminatedFabricStock::where('bill_number',$request->bill)->update(['status' => 'completed']); 

                    $find_godam = DoubleSideLaminatedFabricStock::where('bill_number',$request->bill)->latest()->first();
                    // dd($find_godam);


                    if($polowaste != null){

                        $wastename = 'lumps';

                        $wastage = Wastages::firstOrCreate([
                         'name' => 'lumps'
                         ], [
                         'name' => 'lumps',
                         'is_active' => '1',

                         ]);

                        $waste_id = Wastages::where('name',$wastename)->value('id');

                        $stock = WasteStock::where('godam_id', $find_godam->department_id)
                        ->where('waste_id', $wastage->id)->count();

                        $getStock = WasteStock::where('godam_id', $find_godam->department_id)
                        ->where('waste_id', $wastage->id)->first();
                        

                        if ($stock == 1) {
                            $getStock->quantity_in_kg += $polowaste;
                            $getStock->save();
                        } else {
                            WasteStock::create([
                                'godam_id' => $find_godam->department_id,
                                'waste_id' => $wastage->id,
                                'quantity_in_kg' => $polowaste,
                            ]);
                        }

                    }


                    if($fabricwaste != null){

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
                            $getStock->quantity_in_kg += $fabricwaste;
                            $getStock->save();
                        } else {
                            WasteStock::create([
                                'godam_id' => $find_godam->department_id,
                                'waste_id' => $wastage->id,
                                'quantity_in_kg' => $fabricwaste,
                            ]);
                        }


                    }

                $finaltripal = DoubleTripalBill::where('id',$request->bill_id)->update(['status' => 'completed']); 
               

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

    public function subtractdanafromautoloder(Request $request){
        if($request->ajax()){
            $dana = $request->danaId;
            $quantity = $request->quantity;

            // try{
            //     AutoLoadItemStock::where("dana_name_id",$dana)->delete();
            // }catch(Exception $e){
            //     $e->getMessage();
            // }

            return response([
                "data" => [
                    "dana"=> $dana,
                    "quantity" => $quantity
                ]
            ],200);
        }
    }

    public function storeTripalName(Request $request){
        // dd('hey',$request);
        // $validator = $request->validate([
        //     'name'    => 'required|unique:final_tripal_names,name',
        // ]);

        $date_np = AppHelper::getTodayNepaliDate();
        $date_en = date('Y-m-d');
        // dd($date_np,$date_en);

        DoubleTripalName::create([
            'name' => $request['name'],
            'slug' => $request['name'],
            'date_en' => $date_en,
            'date_np' => $date_np,
        ]);
         return back();

    
    }

    public function getDoubleFilterData(Request $request){
        
        $tripal_id = $request->doubletripal;
        $find_data = DoubleTripalName::find($tripal_id);
        
        $input = $find_data->name;
        $parts = explode(' ', $input);
        $firstString = $parts[0];   
                
        $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        return response([
            'name' => $find_name,
        ]);


    }

    //singlefilterdata for single

    public function getFilterDoubleFabricTripalList(Request $request){
        // dd($request);
        
        $tripal_id = $request->ids;
        $find_data = Singlesidelaminatedfabricstock::find($tripal_id);
        // dd($find_data);
        
        $input = $find_data->name;
        $parts = explode(' ', $input);
        $firstString = $parts[0];   
                
        $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        return response([
            'name' => $find_name,
        ]);


    }
}
