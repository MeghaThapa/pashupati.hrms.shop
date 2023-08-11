<?php

namespace App\Http\Controllers;

use App\Models\AutoLoadItemStock;
use App\Models\DanaGroup;
use App\Models\DanaName;
use App\Models\Department;
use App\Models\FabricFSRDanaConsumption;
use App\Models\FabricLaminatedSentFSR;
use App\Models\FabricStock;
use App\Models\FabricTemporaryForLam;
use App\Models\LaminatedFabric;
use App\Models\LaminatedFabricStock;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Fabric;
use App\Models\TemporaryDanaConsumptionFSRTable;
use App\Models\Unit;
use App\Models\UnlaminatedFabric;
use App\Models\UnlaminatedFabricStock;
use App\Models\Wastages;
use App\Models\WasteStock;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Str;
use App\Models\Godam;
use Throwable;
use Yajra\DataTables\DataTables;

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
        $departments = [];
        // $department = Department::where('status','active')->get();
        $getdepartment = AutoLoadItemStock::with('fromGodam')->get();
        foreach($getdepartment as $data){
            $id = $data->from_godam_id;
            if(!in_array($id,$departments)){
                $departments[] = $id;
            }
        }

       // $department = Godam::whereIn('id',$departments)->get();
        $department=Godam::where('status','active')->get();
        $id = FabricLaminatedSentFSR::latest()->value('id');
        $bill_no = "FSR"."-".getNepaliDate(date('Y-m-d'))."-".$id+1;
        $shifts = Shift::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();
        $dana = DanaName::where('status','active')->get();
        $fabrics = Fabric::get();
        // dd($fabrics);
        return view('admin.fabricSendReceive.index',compact('department','planttype','plantname','shifts','bill_no',"dana",'fabrics'));
    }
    public function getplanttype(Request $request){
        if($request->ajax()){
            $department_id =  $request->id;

            $planttype = ProcessingStep::where('godam_id',$department_id)->get();

            $getAllfabrics = Fabric::where('status', '1')->where("godam_id",$department_id)->get();
             $uniqueFabrics = $getAllfabrics->unique('name');


            return response([
                'planttype' => $planttype,
                'godamfabrics' => $uniqueFabrics->values()->all()
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

    // public function getfabrics(Request $request){
    //     if($request->ajax()){

    //         $fabrics = [];

    //         $getAllfabrics = FabricStock::where('status','1')->get();

    //         foreach($getAllfabrics as $data){
    //             $fabric_name = $data->name;
    //             if(!in_array($fabric_name,$fabrics)){
    //                 array_push($fabrics,$data);
    //             }
    //         }
    //         return response([
    //             'fabrics' => $fabrics
    //         ]);
    //     }
    // }

    public function getfabrics(Request $request)
    {
        if ($request->ajax()) {
            $getAllfabrics = FabricStock::where('status', '1')->where("godam_id",$request->godam_id)->get();
            $uniqueFabrics = $getAllfabrics->unique('name');
            return response([
                'fabrics' => $uniqueFabrics->values()->all()
            ]);
        }
    }

    // public function getfabricwithsamename(Request $request){
    //     if($request->ajax()){
    //         $fabric_name_id = $request->fabric_name_id;
    //         $fabric_name = FabricStock::where("id",$fabric_name_id)->value("name");
    //         $fabrics = FabricStock::where("name",$fabric_name)->get();
    //         return response()->json([
    //             "fabrics" => $fabrics
    //         ]);
    //     }
    // }

    public function getfabricwithsamename(Request $request){
        if($request->ajax()){
            $fabric_name_id = $request->fabric_name_id;
            $fabric_name = FabricStock::where("id",$fabric_name_id)->value("name");
            $fabrics = FabricStock::where("name",$fabric_name)->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                    ->addColumn("gram_wt",function($row){
                        return $row->fabricgroup->name;
                    })
                    ->addColumn("action",function($row){
                        return "
                        <a class='btn btn-primary send_to_lower'  
                                 data-name='{$row->name}' 
                                 data-gross_wt='{$row->gross_wt}' 
                                 data-roll_no='{$row->roll_no}'  
                                 data-id='{$row->id}' 
                                 data-net_wt = '{$row->net_wt}'
                                 data-meter = '{$row->meter}'
                                 data-average_wt = '{$row->average_wt}'
                                 data-gram_wt = '{$row->fabricgroup->name}' 
                                 data-bill_no = '{$row->bill_no}'
                                 href='{$row->id}'>Send</a>";
                    })
                    ->rawColumns(["action","gram_wt"])
                    ->make(true);

            // return response()->json([
            //     "fabrics" => $fabrics
            // ]);
        }
    }

    public function sendunlaminated(Request $request){
        // dd('lol');
        //return $request->data;
        // if($request->ajax()){
        //     $data = [];
        //     parse_str($request->data,$data);

        //     $fabricDetails = Fabric::where('id',$data['fabric_name_id'])->first();
        //     $name = $fabricDetails->name;
        //     $roll_number = $fabricDetails->roll_no;
        //     $gross_weight = $fabricDetails->gross_wt;
        //     $net_wt = $fabricDetails->net_wt;
        //     $meter = $fabricDetails->meter;
        //     $gram = $fabricDetails->gram_wt;
        //     $avg = $fabricDetails->average_wt;

        //     try{
        //         DB::beginTransaction();

        //         UnlaminatedFabric::create([
        //             'bill_number' => $data['bill_number'],
        //             'bill_date' => $data['bill_date'],
        //             'fabric_id' =>$data['fabric_name_id'] ,
        //             'roll_no' => $roll_number ,
        //             'gross_wt' => $gross_weight ,
        //             'net_wt' => $net_wt,
        //             'meter' => $meter,
        //             'average' => $avg,
        //             'gram' => $gram,
        //             'department_id' =>$data['to_godam_id'],
        //             'planttype_id' => $data['plant_type_id'],
        //             'plantname_id' =>  $data['plant_name_id']
        //         ]);

        //         DB::commit();
        //     }catch(Exception $e){
        //         DB::rollBack();
        //         return response([
        //             "message" => "Something went wrong!{$e->getMessage()}"
        //         ]);
        //     }
        // }


        if($request->ajax()){
            $data = [];
            parse_str($request->data,$data);

            $bill_number = $data['bill_number'];
            $bill_date = $data['bill_date'];
            $to_godam_id = $data['to_godam_id'];
            $planttype_id = $data['plant_type_id'];
            $plantname_id =  $data['plant_name_id'];
            $roll_number = $data['roll_number'];

            $details = Fabric::where("roll_no",$roll_number)->first();
            try{
                UnlaminatedFabric::create([
                    'bill_number' => $bill_number,
                    'bill_date' => $bill_date,
                    'fabric_id' =>$details->id,
                    'roll_no' => $roll_number ,
                    'gross_wt' => $details->gross_wt ,
                    'net_wt' => $details->net_wt,
                    'meter' => $details->meter,
                    'average' => $details->average_wt,
                    'gram' => $details->gram_wt,
                    'department_id' =>$to_godam_id,
                    'planttype_id' => $planttype_id,
                    'plantname_id' =>  $plantname_id
                ]);
            }catch(Exception $e){
                return response(["message_error" => $e->getMessage()]);
            }
        }
    }

    public function sendunlaminatedrevised(Request $request){
        if($request->ajax()){
            // return $request->all();
            $gram_wt = $request->gram_wt;
            $net_wt = $request->net_wt;
            $gross_wt  =  $request->gross_wt;
            $bill_date =  $request->billDate;
            $bill_no = $request->bill_no;
            $meter = $request->meter;
            $average_wt = $request->average_wt;
            $godam = $request->godam;
            $plantName = $request->plantName;
            $plantType = $request->plantType;
            $fabric_id = $request->fabric_id;
            $roll_number = $request->roll_no;

            try{
                DB::beginTransaction();

                UnlaminatedFabric::create([
                    'bill_number' => $bill_no,
                    'bill_date' => $bill_date,
                    'fabric_id' =>$fabric_id ,
                    'roll_no' => $roll_number ,
                    'gross_wt' => $gross_wt ,
                    'net_wt' => $net_wt,
                    'meter' => $meter,
                    'average' => $average_wt,
                    'gram' => $gram_wt,
                    'department_id' =>$godam,
                    'planttype_id' => $plantType,
                    'plantname_id' =>  $plantName

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
        $data = UnlaminatedFabric::where('status','pending')->with('fabric')->get();
        if(count($data) != 0){
            return response(['response'=>$data]);
        }else{
            return response(['response'=> '404']);
        }
    }

    public function sendunlaminateddelete(Request $request,$id){
        if($request->ajax()){
           try{
                DB::beginTransaction();
                $count = UnlaminatedFabric::where('id',$id)->get();
                $unlaminatedStock = UnlaminatedFabric::where('id',$id)->get();
                if(count($count) > 0 && count($unlaminatedStock) > 0 ){
                    UnlaminatedFabric::where('id',$id)->delete();
                    // UnlaminatedFabricStock::where('id',$id)->delete();
                    DB::commit();
                    return response([
                        'response'=> "200",
                    ]);

                }else{
                    DB::rollBack();
                    return response([
                        'response'=> "400",
                    ]);
                }

           }catch(Exception $e){
                DB::rollBack();
                return response([
                    "message" => "Something went wrong!'{$e->getMessage()}'"
                ]);
           }
        }
    }
    public function storelaminated(Request $request){
        try{
            $data = [];
            parse_str($request->data,$data);

            $idoffabricforsendtolamination = $data['idoffabricforsendtolamination'];

            $fabricstock =  UnlaminatedFabric::where('id',$idoffabricforsendtolamination)->with('fabric')->first(); //where('id',$idoffabricforsendtolamination)->
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
            $meter1 = $data['meter1'];
            // $roll_no = [
            //     "lamimated_roll_no" => $lamimated_roll_no,
            //     "lamimated_roll_no_2" => $lamimated_roll_no_2,
            //     "lamimated_roll_no_3" => $lamimated_roll_no_3,
            // ];

            $laminated_gross_weight = $data['laminated_gross_weight'];
            $laminated_gross_weight_2 = $data['laminated_gross_weight_2'];
            $laminated_gross_weight_3 = $data['laminated_gross_weight_3'];
            $meter2 = $data['meter2'];
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
            $meter3 = $data['meter3'];
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
                        "meter" => $meter1,
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
                        "meter" => $meter1,

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
                        "meter" => $meter2,
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
                        "meter" => $meter2,

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
                        "meter" => $meter3,
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
                        "meter" => $meter3,

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
            $ul_mtr_total=0;
            $ul_net_wt_total = 0;
            foreach($unlam as $data){
                $ul_mtr = $data['meter'];
                $ul_net_wt = $data['net_wt'];

                $ul_mtr_total += $ul_mtr;
                $ul_net_wt_total += $ul_net_wt;
            }
            // $lam = LaminatedFabric::with('fabric')->get();
            $lam = FabricTemporaryForLam::all();
            $lam_mtr_total=0;
            $lam_net_wt_total = 0;
            foreach($lam as $data){
                $lam_mtr = $data['meter'];
                $lam_net_wt = $data['net_wt'];

                $lam_mtr_total += $lam_mtr;
                $lam_net_wt_total += $lam_net_wt;
            }
            return response([
                "unlam" => $unlam,
                "lam" => $lam,
                "ul_mtr_total" => $ul_mtr_total,
                "ul_net_wt_total" => $ul_net_wt_total,
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

    public function finalsubmitfsr(Request $request){
        if($request->ajax()){
            $consumption = $request->consumption;
            $danaNameID = $request->danaNameID;
            $fabric_waste = $request->fabric_waste;
            $polo_waste = $request->polo_waste;
            $selectedDanaID = $request->selectedDanaID;
            $total_waste  = $request->total_waste;
            $godamId = $request->godam_id;
            $autoloader_godam_selected = $request->autoloader_godam_selected;
            $lamFabricToDelete = [];
            $lamFabricTempToDelete = [];
            $department = [];
            $danaData = [];
            $bill_number= $request->bill_number;

            try{
                DB::beginTransaction();
                //deduction
                    // $stock = AutoLoadItemStock::where('dana_name_id',$selectedDanaID)
                    //         ->where("from_godam_id",$autoloader_godam_selected)
                    //         ->first();

                    // $autoloaderDanaData['dana_name_id'] = $stock->dana_name_id;
                    // $autoloaderDanaData["id"] = $stock->id;
                    // $autoloaderDanaData['dana_group_id'] = $stock->dana_group_id;
                    // $autoloaderDanaData['consumption'] = $consumption;

                    // // dd($autoloaderDanaData);

                    // $presentQuantity = $stock->quantity; 

                    // $deduction = $presentQuantity - $consumption;

                    // if($deduction == 0){
                    //     $stock->delete();
                    // }elseif($deduction < 0){
                    //     return response([
                    //         "message" => "amount exceeded for dana consumption",
                    //         "status" => "403"
                    //         ]);
                    // }else{
                    //     $stock->update([
                    //         "quantity" => $deduction
                    //     ]);
                    // }

                $tempconsumptionfsr = TemporaryDanaConsumptionFSRTable::where("bill_number",$bill_number)->get();
                foreach($tempconsumptionfsr as $data){
                    $stock = AutoLoadItemStock::where('dana_name_id',$data->dana_name_id)
                            ->where("from_godam_id",$autoloader_godam_selected)
                            ->first();

                    $autoloaderDanaData['dana_name_id'] = $stock->dana_name_id;
                    $autoloaderDanaData["id"] = $stock->id;
                    $autoloaderDanaData['dana_group_id'] = $stock->dana_group_id;
                    $autoloaderDanaData['consumption'] = $consumption;

                    // dd($autoloaderDanaData);

                    $presentQuantity = $stock->quantity; 

                    $deduction = $presentQuantity - $consumption;

                    if($deduction == 0){
                        $stock->delete();
                    }elseif($deduction < 0){
                        DB::rollBack();
                        return response([
                            "message" => "amount exceeded for dana consumption",
                            "status" => "403"
                            ]);
                    }else{
                        $stock->update([
                            "quantity" => $deduction
                        ]);
                    }
                }

                //fabric stock creation
                    UnlaminatedFabric::where('status','sent')->delete();
                    $lamFabric = LaminatedFabric::with(['lamfabric'])->get();
                    foreach($lamFabric as $data){
                        $fabric = Fabric::create([
                            'name' => $data->lamfabric->name,
                            "godam_id" => $godamId,
                            'slug' => $data->lamfabric->slug,
                            'fabricgroup_id' => $data->lamfabric->fabricgroup_id,
                            'status' => $data->lamfabric->status,
                            "average_wt" => $data->average,
                            'gram_wt' => $data->gram,
                            'gross_wt' => $data->gross_wt,
                            'net_wt' => $data->net_wt,
                            'meter' => $data->meter,
                            'roll_no' => $data->roll_no,
                            'loom_no' => $data->lamfabric->loom_no,
                            "is_laminated" => "true"
                        ]);

                        FabricStock::create([
                            'name' => $data->lamfabric->name,
                            'slug' => $data->lamfabric->slug,
                            "godam_id" => $godamId,
                            'fabricgroup_id' => $data->lamfabric->fabricgroup_id,
                            'status' => $data->lamfabric->status,
                            "average_wt" => $data->average,
                            'gram_wt' => $data->gram,
                            'gross_wt' => $data->gross_wt,
                            'net_wt' => $data->net_wt,
                            'meter' => $data->meter,
                            // "fabric_id" => $fabric->id,
                            'roll_no' => $data->roll_no,
                            'loom_no' => $data->lamfabric->loom_no,
                            // "date_np" => getNepaliDate(date('Y-m-d')),
                            "is_laminated" => "true"
                        ]);

                        $letlaminatedsentfsr = FabricLaminatedSentFSR::create([
                            'name'=> $data->lamfabric->name,
                            'slug' => $data->lamfabric->slug,
                            'fabricgroup_id' => $data->lamfabric->fabricgroup_id,
                            'roll_no' => $data->roll_no,
                            "loom_no" => $data->lamfabric->loom_no,
                            'gross_wt' => $data->gross_wt,
                            'net_wt' => $data->net_wt ,
                            'meter' => $data->meter,
                            'average' => $data->average,
                            'gram' => $data->gram,
                            'plantname_id' => $data->plantname_id,
                            'department_id' => $data->department_id,
                            'planttype_id' => $data->planttype_id,
                            'bill_number' => $data->bill_number,
                            'bill_date' => $data->bill_date
                        ]);

                        // FabricFSRDanaConsumption::create([
                        //     "fabric_laminated_sent_fsr_id" => $letlaminatedsentfsr->id,
                        //     "dana_name_id" =>  $autoloaderDanaData['dana_name_id'],
                        //     "dana_group_id" => $autoloaderDanaData['dana_group_id'],
                        //     "consumption_quantity" => $autoloaderDanaData['consumption']
                        // ]);

                        // $fsrconsumption = FabricFSRDanaConsumption::where("dana_name_id" ,  $autoloaderDanaData['dana_name_id'])
                        //                         ->where("dana_group_id" , $autoloaderDanaData['dana_group_id'])
                        //                         ->where("consumption_quantity" , $autoloaderDanaData['consumption'])
                        //                         ->first();
                        // if(is_null($fsrconsumption)){
                        //     $consumption =  new FabricFSRDanaConsumption;
                        //     $consumption->consumption_quantity = $autoloaderDanaData['consumption'];
                        // }else{
                        //     $consumption = $fsrconsumption;
                        //     $consumption->consumption_quantity = $fsrconsumption->consumption_quantity  + $autoloaderDanaData['consumption'];
                        // }
                        //     $consumption->fabric_laminated_sent_fsr_id =  $letlaminatedsentfsr->id;
                        //     $consumption->dana_name_id =  $autoloaderDanaData['dana_name_id'];
                        //     $consumption->dana_group_id =  $autoloaderDanaData['dana_group_id'];
                        //     $consumption->save();


                        $lamFabricToDelete[] = $data->id;
                        $lamFabricTempToDelete[] = $data->lamfabric->id;

                        if (!in_array($data->department_id, $department)) {
                            $department[] = $data->department_id;
                        }

                    }

                    foreach($tempconsumptionfsr as $data){
                        $fsrconsumption = FabricFSRDanaConsumption::where("dana_name_id" ,  $data->dana_name_id)
                                                ->where("dana_group_id" , $data->dana_group_id)
                                                ->where("consumption_quantity" , $data->consumption_quantity)
                                                ->first();
                        if(is_null($fsrconsumption)){
                            $consumption =  new FabricFSRDanaConsumption;
                            $consumption->consumption_quantity = $data->consumption_quantity;
                        }else{
                            $consumption = $fsrconsumption;
                            $consumption->consumption_quantity = $fsrconsumption->consumption_quantity  + $data->consumption_quantity;
                        }
                            $consumption->fabric_laminated_sent_fsr_id =  $letlaminatedsentfsr->id;
                            $consumption->dana_name_id =  $data->dana_name_id;
                            $consumption->dana_group_id =  $data->dana_group_id;
                            $consumption->save();
                    }
                    

                    if($total_waste>0){
                        WasteStock::create([
                            'godam_id' => '1',
                            'waste_id' => '1',
                            'quantity_in_kg' => $total_waste,
                        ]);
                    }

                    LaminatedFabric::whereIn('id', $lamFabricToDelete)->delete();

                    FabricTemporaryForLam::whereIn('id',$lamFabricTempToDelete)->delete();

                DB::commit();

                return response(200);
            }catch(Throwable $e){
                DB::rollBack();
                dd($e->getMessage());
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

    public function getStuffOfAutoloader(Request $request,$godamId){
        if($request->ajax()){
            $data = AutoLoadItemStock::where("from_godam_id",$godamId)->with("fromGodam","danaName")->get();
            return response([
                "data" => $data
            ]);
        }
    }

    public function addDanaConsumptionTable(Request $request){
        if($request->ajax()){

            $dana_group_id  = DanaName::where("id",$request->dana_name_id)->value("dana_group_id");
            
            TemporaryDanaConsumptionFSRTable::create([
                "bill_number" => $request->bill_number,
                "dana_name_id" => $request->dana_name_id,
                "dana_group_id" => $dana_group_id,
                "consumption_quantity" => $request->consumption
            ]);

            $query = TemporaryDanaConsumptionFSRTable::where("bill_number",$request->bill_number)->with("dananame");
            $consumptions = $query->get();
            $total_consumption = $query->sum("consumption_quantity");
            // $initial = 0;
            // foreach($consumptions as $data){
            //     $initial += $data->consumption_quantity;
            // }

            return response([
                // "dana" => $request->dana,
                "consumptions" => $consumptions,
                "total_consumption" => $total_consumption
            ]);
        }
    }

    public function getDanaConsumptionTable(Request $request){
        $query = TemporaryDanaConsumptionFSRTable::where("bill_number",$request->billnumber)->with("dananame");
        $consumptions = $query->get();
        $total_consumption = $query->sum("consumption_quantity");
        return response([
            "consumptions" =>  $consumptions,
            "total_consumption" => $total_consumption
        ]);
    }
}