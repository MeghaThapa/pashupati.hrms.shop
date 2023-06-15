<?php

namespace App\Http\Controllers;

use App\Models\DanaName;
use App\Models\Department;
use App\Models\FabricStock;
use App\Models\FabricTemporaryForLam;
use App\Models\LaminatedFabric;
use App\Models\LaminatedFabricStock;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Fabric;
use App\Models\Unit;
use App\Models\UnlaminatedFabric;
use App\Models\UnlaminatedFabricStock;
use DB;
use Exception;
use Illuminate\Http\Request;
use Str;

class FabricSendReceiveController extends Controller
{
    /************* aile baki xa **************/
    // public function create(){
    //     return $bill_no = getNepaliDate(date('Y-m-d'));
    //     return view('admin.fabricSendReceive.create');
    // }

    // public function store(Request $request){
    //     $request->validate([
    //         "bill_no" => "required",
    //         'billdate' => "required" 
    //     ]);
    // }
    /************* aile baki xa **************/
    public function index()
    {
        $id = UnlaminatedFabric::latest()->value('id');
        $bill_no = "FSR"."-".getNepaliDate(date('Y-m-d'))."-".$id+1;
        $shifts = Shift::where('status','active')->get();
        $department = Department::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();
        $dana = DanaName::where('status','active')->get();
        return view('admin.fabricSendReceive.index',compact('department','planttype','plantname','shifts','bill_no',"dana"));
    }
    public function getplanttype(Request $request){
        if($request->ajax()){
            $department_id =  $request->id;
            $planttype = ProcessingStep::where('department_id',$department_id)->get();
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
        if($request->ajax()){
            $fabrics = FabricStock::where('status','1')->get();
            return response([
                'fabrics' => $fabrics
            ]);
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
    public function storelaminated(Request $request){
        try{

            $data = [];
            parse_str($request->data,$data);

          
            
            $idoffabricforsendtolamination = $data['idoffabricforsendtolamination'];

            $fabricstock =  UnlaminatedFabric::with('fabric')->where('id',$idoffabricforsendtolamination)->first(); //where('id',$idoffabricforsendtolamination)->
            $fabric_id = $fabricstock->fabric_id;
            $department_id = $fabricstock->department_id;
            $planttype_id = $fabricstock->planttype_id;
            $plantname_id = $fabricstock->plantname_id;
            $bill_number = $fabricstock->bill_number;
            $bill_date = $fabricstock->bill_date; 
            $meter = $fabricstock->meter;
            $fabricgroup_id = $fabricstock->fabric->fabricgroup_id;

            $lamimated_roll_no = $data['laminated_roll_no'];
            $lamimated_roll_no_2 = $data['laminated_roll_no_2'];
            $lamimated_roll_no_3 = $data['laminated_roll_no_3'];
            // $roll_no = [
            //     "lamimated_roll_no" => $lamimated_roll_no,
            //     "lamimated_roll_no_2" => $lamimated_roll_no_2,
            //     "lamimated_roll_no_3" => $lamimated_roll_no_3,
            // ];

            $laminated_gross_weight = $data['laminated_gross_weight'];
            $laminated_gross_weight_2 = $data['laminated_gross_weight_2'];
            $laminated_gross_weight_3 = $data['laminated_gross_weight_3'];
            // $total_gross_weight = $laminated_gross_weight + $laminated_gross_weight_2  + $laminated_gross_weight_3;
            // $gross_weight = [
            //     "laminated_gross_weight" => $laminated_gross_weight,
            //     "laminated_gross_weight_2" => $laminated_gross_weight_2,
            //     "laminated_gross_weight_3" => $laminated_gross_weight_3,
            //     "total" => $total_gross_weight
            // ];
    

            $laminated_net_weight = $data['laminated_net_weight'];
            $laminated_net_weight_2 = $data['laminated_net_weight_2'];
            $laminated_net_weight_3 = $data['laminated_net_weight_3'];
            // $total_net_weight = floatval($data['laminated_net_weight']) + floatval($data['laminated_net_weight_2']) + floatval($data['laminated_net_weight_3']);
            // $net_weight = [
            //     "laminated_net_weight" => $data['laminated_net_weight'],
            //     "laminated_net_weight_2" => $data['laminated_net_weight_2'],
            //     "laminated_net_weight_3" => $data['laminated_net_weight_3'],
            //     "total" => $total_net_weight
            // ];


            $laminated_avg_weight = $data['laminated_avg_weight'];
            $laminated_avg_weight_2 = $data['laminated_avg_weight_2'];
            $laminated_avg_weight_3 = $data['laminated_avg_weight_3'];
            // $total_avg_weight = floatval($data['laminated_avg_weight']) + floatval($data['laminated_avg_weight_2']) + floatval($data['laminated_avg_weight_3']);
            // $avg_weight = [
            //     "laminated_avg_weight" => $data['laminated_avg_weight'],
            //     "laminated_avg_weight_2" => $data['laminated_avg_weight_2'],
            //     "laminated_avg_weight_3" => $data['laminated_avg_weight_3'],
            //     "total" => $total_avg_weight
            // ];

            $laminated_gram = $data['laminated_gram'];
            $laminated_gram_2 = $data['laminated_gram_2'];
            $laminated_gram_3 = $data['laminated_gram_3'];
            // $total_gram =  floatval($data['laminated_gram']) + floatval($data['laminated_gram_2']) + floatval($data['laminated_gram_3']);
            // $gram = [
            //     "laminated_gram" => $data['laminated_gram'],
            //     "laminated_gram_2" => $data['laminated_gram_2'],
            //     "laminated_gram_3" => $data['laminated_gram_3'],
            //     "total" => $total_gram
            // ];

            $fabricmodelquery = Fabric::where('id',$fabric_id)->first();

           DB::beginTransaction();
                
                $fabric =  UnlaminatedFabric::with('fabric')->where('id',$idoffabricforsendtolamination)->first();
                $updatetosent = $fabric->update([
                    "status" => "sent"
                ]);

                
                if($lamimated_roll_no != null && $laminated_gross_weight != null && $laminated_net_weight != null && $laminated_avg_weight != null && $laminated_gram != null){

                    $lamfabriccreate = FabricTemporaryForLam::create([
                        "name" => $data['laminated_fabric_name'],
                        // "fabric_id" => $data['fabric_id'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "fabricgroup_id" => $fabricgroup_id,
                        "gram" =>  $laminated_gram,
                        "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight,
                        'gross_wt' => $laminated_gross_weight,
                        "roll_no" => $lamimated_roll_no,
                        'net_wt' => $laminated_net_weight,
                        "meter" => $fabricmodelquery->meter,
                        "status" => "1"
                    ]);

                    $lam_fabric_id = $lamfabriccreate->id;

                    $create = LaminatedFabric::create([
                        "lam_fabric_id" => $lam_fabric_id,
                        "standard_weight_gram" => $data['standard_weight_gram'],
                        "roll_no" => $lamimated_roll_no, 
            
                        "gross_wt" => $laminated_gross_weight,
                        "net_wt" => $laminated_net_weight,
                        "average" => $laminated_avg_weight,
                        "gram" => $laminated_gram,
                        "meter" => $meter,
            
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "department_id" => $department_id,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id
                    ]);

                if($lamimated_roll_no_2 != null && $laminated_gross_weight_2 != null && $laminated_net_weight_2 != null && $laminated_avg_weight_2 != null && $laminated_gram_2 != null){

                    $lamfabriccreate = FabricTemporaryForLam::create([
                        "name" => $data['laminated_fabric_name'],
                        // "fabric_id" => $data['fabric_id'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "fabricgroup_id" => $fabricgroup_id,
                        "gram" =>  $laminated_gram_2,
                        "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight_2,
                        'gross_wt' => $laminated_gross_weight_2,
                        "roll_no" => $lamimated_roll_no_2,
                        'net_wt' => $laminated_net_weight_2,
                        "meter" => $fabricmodelquery->meter,
                        "status" => "1"
                    ]);

                    $lam_fabric_id = $lamfabriccreate->id;

                    $create = LaminatedFabric::create([
                        "lam_fabric_id" => $lam_fabric_id,
                        "standard_weight_gram" => $data['standard_weight_gram'],
                        "roll_no" => $lamimated_roll_no_2, 
            
                        "gross_wt" => $laminated_gross_weight_2,
                        "net_wt" => $laminated_net_weight_2,
                        "average" => $laminated_avg_weight_2,
                        "gram" => $laminated_gram_2,
                        "meter" => $meter,
            
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "department_id" => $department_id,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id
                    ]);
                }

                if($lamimated_roll_no_3 != null && $laminated_gross_weight_3 != null && $laminated_net_weight_3 != null && $laminated_avg_weight_3 != null && $laminated_gram_3 != null){

                    $lamfabriccreate = FabricTemporaryForLam::create([
                        "name" => $data['laminated_fabric_name'],
                        // "fabric_id" => $data['fabric_id'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "fabricgroup_id" => $fabricgroup_id,
                        "gram" =>  $laminated_gram_3,
                        "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight_3,
                        'gross_wt' => $laminated_gross_weight_3,
                        "roll_no" => $lamimated_roll_no_3,
                        'net_wt' => $laminated_net_weight_3,
                        "meter" => $fabricmodelquery->meter,
                        "status" => "1"
                    ]);

                    $lam_fabric_id = $lamfabriccreate->id;

                    $create = LaminatedFabric::create([
                        "lam_fabric_id" => $lam_fabric_id,
                        "standard_weight_gram" => $data['standard_weight_gram'],
                        "roll_no" => $lamimated_roll_no_3, 
            
                        "gross_wt" => $laminated_gross_weight_3,
                        "net_wt" => $laminated_net_weight_3,
                        "average" => $laminated_avg_weight_3,
                        "gram" => $laminated_gram_3,
                        "meter" => $meter,
            
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "department_id" => $department_id,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id
                    ]);
                }

                // $createStock = LaminatedFabricStock::create([
                //     "fabric_id" => $lam_fabric_id,
                //     "standard_weight_gram" => $data['standard_weight_gram'],
                //     "roll_no" => json_encode($roll_no), 
        
                //     "gross_wt" => json_encode($gross_weight),
                //     "net_wt" => json_encode($net_weight),
                //     "average" => json_encode($avg_weight),
                //     "gram" => json_encode($gram),
                //     "meter" => $meter, 
        
                //     "bill_number" => $bill_number,
                //     'bill_date' => $bill_date,
                //     "department_id" => $department_id,
                //     "planttype_id" => $planttype_id,
                //     "plantname_id" => $plantname_id
                // ]);
            }

                // UnlaminatedFabricStock::where('id',$idoffabricforsendtolamination)->delete();
                // UnlaminatedFabric::where('id',$idoffabricforsendtolamination)->delete();
                // FabricStock::where('id',$fabric_id)->delete();
                
           DB::commit();
           return "Done";
        }
        catch(Exception $e){
            DB::rollback();
            return "exception".$e->getMessage();
        }
    }
    
    public function comparelamandunlam(Request $request){
        if($request->ajax()){
            $unlam = UnlaminatedFabric::with('fabric')->where('status',"sent")->get();
            // $lam = LaminatedFabric::with('fabric')->get();
            $lam = FabricTemporaryForLam::all();
            return response([
                "unlam" => $unlam,
                "lam" => $lam
            ]);
        }
    }

    public function discard(Request $request){
        if($request->ajax()){
            try{
                UnlaminatedFabric::truncate();
                UnlaminatedFabricStock::truncate();
                LaminatedFabric::truncate();
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
}
