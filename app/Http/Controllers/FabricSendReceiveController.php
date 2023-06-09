<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FabricStock;
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
        return view('admin.fabricSendReceive.index',compact('department','planttype','plantname','shifts','bill_no'));
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

            // return [
            //     $name,
            //     $roll_number ,
            //     $gross_weight,
            //     $net_wt ,
            //     $meter ,
            //     $gram,
            //     $avg
            // ];

            // to_godam_id=1&plant_type_id=1&plant_name_id=1

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

            // return $this->returnUnlaminatedStockData();
            // bill_date: "2023-06-04"
            // bill_number: "FSR-2080-2-21-5507"
            // fabric_name_id: "3"
            // plant_name_id: "3"
            // plant_type_id: "4"
            // shift_name_id: "1"
            // to_godam_id: "2"
        }

        // function returnUnlaminatedStockData(){
        //     UnlaminatedFabric::all();
        // }   
    }
    public function getunlaminated(){
        // return response(['response'=> '404']);
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
            if(count($count) > 0){
                UnlaminatedFabric::where('id',$id)->delete();
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
        return $request->id;
    }
}
