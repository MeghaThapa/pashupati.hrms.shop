<?php

namespace App\Http\Controllers;

use App\Models\DanaName;
use App\Models\Department;
use App\Models\FabricStock;
use App\Models\LaminatedFabric;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Fabric;
use App\Models\UnlaminatedFabric;
use App\Models\UnlaminatedFabricStock;
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
        if($request->ajax()){
            $count = UnlaminatedFabric::where('id',$id)->get();
            $unlaminatedStock = UnlaminatedFabricStock::where('id',$id)->get();
            if(count($count) > 0 && count($unlaminatedStock) > 0 ){
                UnlaminatedFabric::where('id',$id)->delete();
                UnlaminatedFabricStock::where('id',$id)->delete();
                return response([
                    'response'=> "200",
                ]);
            }else{
                return response([
                    'response'=> "400",
                ]);
            }
        }
    }
    public function storelaminated(Request $request){
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
        // ];


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
        return [
            // "data" => $data,
            "net_weigt"=>$net_weight,
            "gross_weight" => $gross_weight,
            "avg_weight" => $avg_weight,
            'total_gram' => $gram
        ];

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
    }
}
