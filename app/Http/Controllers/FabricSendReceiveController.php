<?php

namespace App\Http\Controllers;

use App\Models\DanaName;
use App\Models\Department;
use App\Models\FabricStock;
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
            $gram = $fabricDetails->fabricgroup->name;
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

                UnlaminatedFabricStock::create([
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
        $data = UnlaminatedFabric::with('fabric')->get();
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
        // return   $request;
        
        try{

            $idoffabricforsendtolamination = $request->idoffabricforsendtolamination;
            $fabric =  UnlaminatedFabricStock::with('fabric')->first(); //where('id',$idoffabricforsendtolamination)->
            $fabric_id = $fabric->fabric_id;
            $department_id = $fabric->department_id;
            $planttype_id = $fabric->planttype_id;
            $plantname_id = $fabric->plantname_id;
            $bill_number = $fabric->bill_number;
            $bill_date = $fabric->bill_date; 
            $meter = $fabric->meter;
    
            $total_goss_weight = floatval($request->laminated_gross_weight + $request->laminated_gross_weight_2 + $request->laminated_gross_weight_3);
            $gross_weight = [
                "laminated_gross_weight" => $request->laminated_gross_weight,
                "laminated_gross_weight_2" => $request->laminated_gross_weight_2,
                "laminated_gross_weight_3" => $request->laminated_gross_weight_3,
                "total" => $total_goss_weight
            ];
    
            $total_net_weight = floatval($request->laminated_net_weight + $request->laminated_net_weight_2 + $request->laminated_net_weight_3);
            $net_weight = [
                "laminated_net_weight" => $request->laminated_net_weight,
                "laminated_net_weight_2" => $request->laminated_net_weight_2,
                "laminated_net_weight_3" => $request->laminated_net_weight_3,
                "total" => $total_net_weight
            ];
    
            $total_avg_weight = floatval($request->laminated_avg_weight + $request->laminated_avg_weight_2 + $request->laminated_avg_weight_3);
            $avg_weight = [
                "laminated_avg_weight" => $request->laminated_avg_weight,
                "laminated_avg_weight_2" => $request->laminated_avg_weight_2,
                "laminated_avg_weight_3" => $request->laminated_avg_weight_3,
                "total" => $total_avg_weight
            ];
    
            $total_gram =  floatval($request->laminated_gram + $request->laminated_gram_2 + $request->laminated_gram_3);
            $gram = [
                "laminated_gram" => $request->laminated_gram,
                "laminated_gram_2" => $request->laminated_gram_2,
                "laminated_gram_3" => $request->laminated_gram_3,
                "total" => $total_gram
            ];

           DB::beginTransaction();
        
                // Unit::create([
                //     "name" => "TestUnit123",
                //     "slug" => "test-unit-123",
                //     "code" => "test-unit-123",
                //     "note" => "TestUnit123",
                //     "status" => "1"
                // ]);
                
                $create = LaminatedFabric::create([
                    "fabric_id" => $fabric_id,
                    "standard_weight_gram" => $request->standard_weight_gram,
                    "roll_no" => $request->laminated_roll_no,
        
                    "gross_wt" => json_encode($gross_weight),
                    "net_wt" => json_encode($net_weight),
                    "average" => json_encode($avg_weight),
                    "gram" => json_encode($gram),
                    "meter" => json_encode($meter), 
        
                    "bill_number" => $bill_number,
                    'bill_date' => $bill_date,
                    "department_id" => $department_id,
                    "planttype_id" => $planttype_id,
                    "plantname_id" => $plantname_id
                ]);

                $create = LaminatedFabricStock::create([
                    "fabric_id" => $fabric_id,
                    "standard_weight_gram" => $request->standard_weight_gram,
                    "roll_no" => $request->laminated_roll_no,
        
                    "gross_wt" => json_encode($gross_weight),
                    "net_wt" => json_encode($net_weight),
                    "average" => json_encode($avg_weight),
                    "gram" => json_encode($gram),
                    "meter" => json_encode($meter), 
        
                    "bill_number" => $bill_number,
                    'bill_date' => $bill_date,
                    "department_id" => $department_id,
                    "planttype_id" => $planttype_id,
                    "plantname_id" => $plantname_id
                ]);

                // UnlaminatedFabricStock::where('id',$idoffabricforsendtolamination)->delete();
                // UnlaminatedFabric::where('id',$idoffabricforsendtolamination)->delete();
                // FabricStock::where('id',$fabric_id)->delete();
                
           DB::commit();
           return "DOne";
        }
        catch(Exception $e){
            DB::rollback();
            return $e->getMessage();
        }



       // $data= [
        //     "laminated_fabric_name" => $request->laminated_fabric_name,
        //     "laminated_fabric_group" => $request->laminated_fabric_group,
        //     "standard_weight_gram" => $request->standard_weight_gram,
        //     "laminated_roll_no" => $request->laminated_roll_no,

        //     "laminated_gross_weight" => $request->laminated_gross_weight,
        //     "laminated_net_weight" => $request->laminated_net_weight,
        //     "laminated_avg_weight" => $request->laminated_avg_weight,
        //     "laminated_gram" => $request->laminated_gram,

        //     "laminated_gross_weight_2" => $request->laminated_gross_weight_2,
        //     "laminated_net_weight_2" => $request->laminated_net_weight_2,
        //     "laminated_avg_weight_2" => $request->laminated_avg_weight_2,
        //     "laminated_gram_2" => $request->laminated_gram_2,

        //     "laminated_gross_weight_3" => $request->laminated_gross_weight_3,
        //     "laminated_net_weight_3" => $request->laminated_net_weight_3,
        //     "laminated_avg_weight_3" => $request->laminated_avg_weight_3,
        //     "laminated_gram_3" => $request->laminated_gram_3,

        //     "idoffabricforsendtolamination" => $request->idoffabricforsendtolamination
        // ];
                // return [
        //     // "data" => $data,
        //     "net_weigt"=>$net_weight,
        //     "gross_weight" => $gross_weight,
        //     "avg_weight" => $avg_weight,
        //     'total_gram' => $gram
        // ];


        /************************* 
        $create = LaminatedFabric::create([
            "laminated_fabric_name" => $request->laminated_fabric_name,
            "laminated_fabric_group" => $request->laminated_fabric_group,
            "standard_weight_gram" => $request->standard_weight_gram,
            "laminated_roll_no" => $request->laminated_roll_no,

            "laminated_gross_weight" => $request->laminated_gross_weight,
            "laminated_net_weight" => $request->laminated_net_weight,
            "laminated_avg_weight" => $request->laminated_avg_weight,
            "laminated_gram" => $request->laminated_gram,

            "laminated_gross_weight_2" => $request->laminated_gross_weight_2,
            "laminated_net_weight_2" => $request->laminated_net_weight_2,
            "laminated_avg_weight_2" => $request->laminated_avg_weight_2,
            "laminated_gram_2" => $request->laminated_gram_2,

            "laminated_gross_weight_3" => $request->laminated_gross_weight_3,
            "laminated_net_weight_3" => $request->laminated_net_weight_3,
            "laminated_avg_weight_3" => $request->laminated_avg_weight_3,
            "laminated_gram_3" => $request->laminated_gram_3,
        ]);
        ****************************/
    }

    
    public function comparelamandunlam(Request $request){
        if($request->ajax()){
            $unlam = UnlaminatedFabric::with('fabric')->get();
            $lam = LaminatedFabric::with('fabric')->get();
            return response([
                "unlam" => $unlam,
                "lam" => $lam
            ]);
        }
    }
}
