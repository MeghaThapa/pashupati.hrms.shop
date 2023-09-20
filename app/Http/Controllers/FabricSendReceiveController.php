<?php



namespace App\Http\Controllers;



use App\Models\AutoLoadItemStock;

use App\Models\DanaGroup;

use App\Models\DanaName;



use App\Models\FabricSendAndReceiveDanaConsumption;

use App\Models\FabricSendAndReceiveDanaConsumptionList;

use App\Models\FabricSendAndReceiveEntry;

use App\Models\FabricSendAndReceiveLaminatedFabricDetails;

use App\Models\FabricSendAndReceiveLaminatedSent;

use App\Models\FabricSendAndReceiveTemporaryForLamination;

use App\Models\FabricStock;



use App\Models\ProcessingStep;

use App\Models\ProcessingSubcat;

use App\Models\Shift;

use App\Models\Fabric;



use App\Models\Unit;

use App\Models\Wastages;

use App\Models\WasteStock;

use Illuminate\Support\Facades\DB;

use Exception;

use Illuminate\Http\Request;

use Str;

use App\Models\Godam;

use Throwable;

use Yajra\DataTables\DataTables;

use App\Models\FabricSendAndReceiveUnlaminatedFabric;



class FabricSendReceiveController extends Controller

{

    /************* For Entries **************/

    public function create(){

        $id = FabricSendAndReceiveEntry::latest()->value('id');

        $bill_number = "FSR"."-".getNepaliDate(date('Y-m-d'))."-".$id+1;

        $bill_date  = date("Y-m-d");

        $bill_date_np = getNepaliDate(date("Y-m-d"));

        $godam = Godam::where("status","active")->get();

        $shift = Shift::where("status","active")->get();

        return view('admin.fabricSendReceive.create')->with([

            "bill_number" => $bill_number,

            "bill_date" => $bill_date,

            "bill_date_np" => $bill_date_np,

            "godam" => $godam,

            "shift" => $shift

        ]);

    }



    public function entrieslist(Request $request){

        if($request->ajax()){

            $data = FabricSendAndReceiveEntry::with(["getgodam","getplanttype","getplantname","getshift"])->orderBy("id","desc")->get();

            return DataTables::of($data)

                            ->addIndexColumn()

                            ->addColumn("godam",function($row){

                                return $row->getgodam->name;

                            })

                            ->addColumn("planttype",function($row){

                                return $row->getplanttype->name;

                            })

                            ->addColumn("plantname",function($row){

                                return $row->getplantname->name;

                            })

                            ->addColumn("shift",function($row){

                                return $row->getshift->name;

                            })

                            ->addColumn("action",function($row){

                                if($row->status == "pending"){

                                    return "

                                        <div class='btn-group'>

                                            <button class='btn btn-primary create' data-id='{$row->id}'><i class='fa fa-plus' aria-hidden='true'></i></button>

                                            <button class='btn btn-danger delete' data-id='{$row->id}'><i class='fa fa-trash' aria-hidden='true'></i></button>

                                        </div>

                                    ";

                                }else{

                                    return "

                                    <div class='btn-group'>

                                        <button class='btn btn-secondary view' data-id='{$row->id}'><i class='fa fa-eye' aria-hidden='true'></i></button>

                                    </div>

                                ";

                                }



                            })

                            ->rawColumns([

                                "godam","planttype" ,"plantname" ,"shift","action"

                            ])

                            ->make(true);

        }

    }



    public function store(Request $request){

        $request->validate([

            "bill_number" => "required",

            'bill_date' => "required",

            "bill_date_np" => "required",

            "godam" => "required",

            "planttype" => "required",

            "plantname" => "required",

            "shift" => "required",

        ]);



        FabricSendAndReceiveEntry::create([

            "godam_id" => $request->godam ,

            "planttype_id" => $request->planttype,

            "plantname_id" => $request->plantname ,

            "remarks" => $request->remarks,

            "shift_id" => $request->shift,

            "bill_number" => $request->bill_number,

            "bill_date" => $request->bill_date ,

            "bill_date_np" => $request->bill_date_np

        ]);

        return back()->with("success","Creation Successful");

    }



    public function delete(Request $request){

        if($request->ajax()){

            FabricSendAndReceiveEntry::where("id",$request->id)->delete();

            return response([

                "status" => "200"

            ]);

        }

    }

    /************* End For Entries **************/



    public function indexrevised($id){

        $dana = DanaName::where('status','active')->get();

        $data = FabricSendAndReceiveEntry::where("id",$id)->first();

        $getAllfabrics = FabricStock::where('status', '1')->where("godam_id",$data->godam_id)->get();

        $uniqueFabrics = $getAllfabrics->unique('name')->values()->all();

        return view('admin.fabricSendReceive.indexrevised',compact("data","dana","uniqueFabrics"));

    }



    public function getplanttype(Request $request){

        if($request->ajax()){

            $department_id =  $request->id;



            $planttype = ProcessingStep::where('godam_id',$department_id)->get();



            $getAllfabrics = FabricStock::where('status', '1')->where("godam_id",$department_id)->get();

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

                                 data-id='{$row->fabric_id}'

                                 data-net_wt = '{$row->net_wt}'

                                 data-meter = '{$row->meter}'

                                 data-average_wt = '{$row->average_wt}'

                                 data-gram_wt = '{$row->fabricgroup->name}'

                                 data-bill_no = '{$row->bill_no}'

                                 href='{$row->fabric_id}'>Send</a>";

                    })

                    ->rawColumns(["action","gram_wt"])

                    ->make(true);



            // return response()->json([

            //     "fabrics" => $fabrics

            // ]);

        }

    }







    public function sendunlaminatedrevised2(Request $request){ //latest changes //keep

        // return $request;

        if($request->ajax()){

            $gram_wt = $request->gram_wt;

            $net_wt = $request->net_wt;

            $gross_wt  =  $request->gross_wt;

            $meter = $request->meter;

            $average_wt = $request->average_wt;

            $fabric_id = $request->fabric_id;

            $roll_number = $request->roll_no;

            $fsr_entry_id = $request->fsr_entry_id;



            // return FabricStock::where("fabric_id","{$request->fabric_id}")->get();

            // return Fabric::where("id","914")->get();



            try{

                DB::beginTransaction();



                FabricSendAndReceiveUnlaminatedFabric::create([

                    "fsr_entry_id" => $fsr_entry_id,

                    'fabric_id' =>$fabric_id ,

                    // 'roll_no' => $roll_number ,

                    // 'gross_wt' => $gross_wt ,

                    // 'net_wt' => $net_wt,

                    // 'meter' => $meter,

                    // 'average' => $average_wt,

                    // 'gram_wt' => $gram_wt,

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





    public function getunlaminatedrevised($fsr_entry_id){//latest

        $data = FabricSendAndReceiveUnlaminatedFabric::where('status','pending')->where("fsr_entry_id",$fsr_entry_id)->with('getfabric.fabricgroup')->get();

        if(count($data) != 0){

            return response(['response'=>$data]);

        }else{

            return response(['response'=> '404']);

        }

    }





    public function sendunlaminateddeleterevised(Request $request,$id){ //latest

        if($request->ajax()){

           try{

                DB::beginTransaction();

                $count = FabricSendAndReceiveUnlaminatedFabric::where('id',$id)->get();

                $unlaminatedStock = FabricSendAndReceiveUnlaminatedFabric::where('id',$id)->get();

                if(count($count) > 0 && count($unlaminatedStock) > 0 ){

                    FabricSendAndReceiveUnlaminatedFabric::where('id',$id)->delete();

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







    public function storelaminatedrevised(Request $request){ //latset

        try{

            $data = [];

            parse_str($request->data,$data);

            $data['fsr_entry_id'] = $request->fsr_entry_id;



            $idoffabricforsendtolamination = $data['idoffabricforsendtolamination'];



            $fabricunlaminated =  FabricSendAndReceiveUnlaminatedFabric::where('id',$idoffabricforsendtolamination)->with('getfabric')->first(); //where('id',$idoffabricforsendtolamination)->

            $loom_no = $fabricunlaminated->getfabric->loom_no;



            $lamimated_roll_no = $data['laminated_roll_no'];

            $lamimated_roll_no_2 = $data['laminated_roll_no_2'];

            $lamimated_roll_no_3 = $data['laminated_roll_no_3'];

            $meter1 = $data['meter1'];



            $laminated_gross_weight = $data['laminated_gross_weight'];

            $laminated_gross_weight_2 = $data['laminated_gross_weight_2'];

            $laminated_gross_weight_3 = $data['laminated_gross_weight_3'];

            $meter2 = $data['meter2'];





            $laminated_net_weight = $data['laminated_net_weight'];

            $laminated_net_weight_2 = $data['laminated_net_weight_2'];

            $laminated_net_weight_3 = $data['laminated_net_weight_3'];

            $meter3 = $data['meter3'];





            $laminated_avg_weight = $data['laminated_avg_weight'];

            $laminated_avg_weight_2 = $data['laminated_avg_weight_2'];

            $laminated_avg_weight_3 = $data['laminated_avg_weight_3'];





            $laminated_gram = $data['laminated_gram'];

            $laminated_gram_2 = $data['laminated_gram_2'];

            $laminated_gram_3 = $data['laminated_gram_3'];





           DB::beginTransaction();



                $fabric =  FabricSendAndReceiveUnlaminatedFabric::with('getfabric')->where('id',$idoffabricforsendtolamination)->first();

                $updatetosent = $fabric->update([

                    "status" => "sent"

                ]);





                if($lamimated_roll_no != null && $laminated_gross_weight != null && $laminated_net_weight != null && $laminated_avg_weight != null && $laminated_gram != null){



                    $lamfabriccreate = FabricSendAndReceiveTemporaryForLamination::create([

                        "fsr_entry_id" => $data['fsr_entry_id'],

                        "unlam_fabric_id" => $fabricunlaminated->id,

                        "fabric_name" =>  $data['laminated_fabric_name'],

                        "slug" =>  Str::slug($data["laminated_fabric_name"]),

                    ]);



                    FabricSendAndReceiveLaminatedFabricDetails::create([

                        "temp_lam" => $lamfabriccreate->id,

                        "gram_wt" =>  $laminated_gram,

                        "loom_no" => $loom_no,

                        "average_wt" => $laminated_avg_weight,

                        'gross_wt' => $laminated_gross_weight,

                        "roll_no" => $lamimated_roll_no,

                        'net_wt' => $laminated_net_weight,

                        "standard_wt" => $data["standard_wt"] ,

                        "fbgrp_id" => $data["fabricgroup_id"],

                        "meter" => $meter1,

                    ]);



                if($lamimated_roll_no_2 != null && $laminated_gross_weight_2 != null && $laminated_net_weight_2 != null && $laminated_avg_weight_2 != null && $laminated_gram_2 != null){



                    // $lamfabriccreate = FabricSendAndReceiveTemporaryForLamination::create([

                    //     "fsr_entry_id" => $data['fsr_entry_id'],

                    //     "unlam_fabric_id" => $fabricunlaminated->id,

                    // ]);



                    FabricSendAndReceiveLaminatedFabricDetails::create([

                        "temp_lam" => $lamfabriccreate->id,

                        "gram_wt" =>  $laminated_gram_2,

                        "loom_no" => $loom_no,

                        "average_wt" => $laminated_avg_weight_2,

                        'gross_wt' => $laminated_gross_weight_2,

                        "roll_no" => $lamimated_roll_no_2,

                        'net_wt' => $laminated_net_weight_2,

                        "standard_wt" => $data["standard_wt"] ,

                        "fbgrp_id" => $data["fabricgroup_id"],

                        "meter" => $meter2,

                    ]);



                }



                if($lamimated_roll_no_3 != null && $laminated_gross_weight_3 != null && $laminated_net_weight_3 != null && $laminated_avg_weight_3 != null && $laminated_gram_3 != null){



                    // $lamfabriccreate = FabricSendAndReceiveTemporaryForLamination::create([

                    //     "fsr_entry_id" => $data['fsr_entry_id'],

                    //     "unlam_fabric_id" => $fabricunlaminated->id ,

                    //     ""

                    // ]);



                    FabricSendAndReceiveLaminatedFabricDetails::create([

                        "temp_lam" => $lamfabriccreate->id,

                        "gram_wt" =>  $laminated_gram_3,

                        "loom_no" => $loom_no,

                        "average_wt" => $laminated_avg_weight_3,

                        'gross_wt' => $laminated_gross_weight_3,

                        "roll_no" => $lamimated_roll_no_3,

                        'net_wt' => $laminated_net_weight_3,

                        "standard_wt" => $data["standard_wt"] ,

                        "fbgrp_id" => $data["fabricgroup_id"],

                        "meter" => $meter3,

                    ]);

                }

            }



           DB::commit();

           return "Done";

        }

        catch(Exception $e){

            DB::rollback();

            return "exception".$e->getMessage();

        }

    }

    public function comparelamandunlamrevised(Request $request,$entry_id){ //latest

        // dd($entry_id);

        if($request->ajax()){

            $unlam = FabricSendAndReceiveUnlaminatedFabric::with('getfabric')->where("fsr_entry_id",$entry_id)->where('status',"sent")->get();

            $ul_mtr_total=0;

            $ul_net_wt_total = 0;

            foreach($unlam as $data){

                $ul_mtr = $data->getfabric->meter;

                $ul_net_wt = $data->getfabric->net_wt;



                $ul_mtr_total += $ul_mtr;

                $ul_net_wt_total += $ul_net_wt;

            }

            $lam = FabricSendAndReceiveLaminatedFabricDetails::with('temporarylamfabric')

                            ->whereHas('temporarylamfabric', function ($query) use ($entry_id) {

                                $query->where('fsr_entry_id', $entry_id);

                            })

                            ->get();



            $lam_mtr_total=0;

            $lam_net_wt_total = 0;

            foreach($lam as $data){

                $lam_mtr = floatval($data['meter']);

                $lam_net_wt = floatval($data['net_wt']);



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

    public function deleteLam(Request $request){
        $lam = FabricSendAndReceiveLaminatedFabricDetails::findOrFail($request->delete_id);
        $lam->delete();
        return response(['status'=>true,'message'=>'Lam deleted successfully']);
    }

    public function deleteUnLam(Request $request){
        $unLam = FabricSendAndReceiveUnlaminatedFabric::findOrFail($request->delete_id);
        $unLam->delete();
        return response(['status'=>true,'message'=>'Un Lam deleted successfully']);
    }

    public function discard(Request $request){

        if($request->ajax()){

            return "Not Allowed";

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



    public function getStuffOfAutoloader(Request $request){

        if($request->ajax()){

            $godamId = $request->godam_id;

            $plantName = $request->plantname;

            $planttype = $request->planttype;

            $shift = $request->shift;

            $data = AutoLoadItemStock::where("from_godam_id",$godamId)

                                    ->where("plant_name_id",$plantName)

                                    ->where("plant_type_id",$planttype)

                                    ->where("shift_id",$shift)

                                    ->with("fromGodam","danaName")

                                    ->get();

            return response([

                "data" => $data

            ]);

        }

    }



    public function addDanaConsumptionTablerevised(Request $request){ //latest

        if($request->ajax()){

            $dana_group_id  = DanaName::where("id",$request->dana_name_id)->value("dana_group_id");



            FabricSendAndReceiveDanaConsumption::create([

                "fsr_entry_id" => $request->fsr_entry_id,

                "dana_name_id" => $request->dana_name_id,

                "dana_group_id" => $dana_group_id,

                "consumption_quantity" => $request->consumption,

                "autoloader_id" => $request->autoloader_id

            ]);



            $query = FabricSendAndReceiveDanaConsumption::where("fsr_entry_id",$request->fsr_entry_id)->with("dananame");

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





    public function getDanaConsumptionTablerevised(Request $request){//latest

        $query = FabricSendAndReceiveDanaConsumption::where("fsr_entry_id",$request->fsr_entry_id)->with("dananame");

            $consumptions = $query->get();

            $total_consumption = $query->sum("consumption_quantity");

        return response([

            "consumptions" =>  $consumptions,

            "total_consumption" => $total_consumption

        ]);

    }







    public function removedDanaConsumptionTablerevised(Request $request){//latest

        if($request->ajax()){

            FabricSendAndReceiveDanaConsumption::where("id",$request->id)->delete();

            return response([

                "message" => "deleted successfully",

                "status" => 200

            ],200);

        }

    }



    public function finalsubmitfsrrevised(Request $request){//latest

        if($request->ajax()){

            $consumption = $request->consumption;

            $fabric_waste = $request->fabric_waste;

            $polo_waste = $request->polo_waste;

            $total_waste  = $request->total_waste;

            $lamFabricToDelete = [];

            $lamFabricTempToDelete = [];

            $department = [];

            $danaData = [];

            $fsr_entry_id= $request->fsr_entry_id;



            $fabric_entry = FabricSendAndReceiveEntry::where("id",$fsr_entry_id)->first();

            $godam = $fabric_entry->godam_id;



            // return $lam = FabricSendAndReceiveLaminatedFabricDetails::with('temporarylamfabric.getfabric.getfabric')->get(); //for nested relation



            try{

                DB::beginTransaction();

                $tempconsumptionfsr = FabricSendAndReceiveDanaConsumption::where("fsr_entry_id",$fsr_entry_id)->get();

                foreach($tempconsumptionfsr as $data){

                    // $stock = AutoLoadItemStock::where('dana_name_id',$data->dana_name_id)

                    //         ->where("from_godam_id",$godam)

                    //         ->first();



                    $stock = AutoLoadItemStock::where('id',$data->autoloader_id)

                            ->first();



                    $autoloaderDanaData['dana_name_id'] = $stock->dana_name_id;

                    $autoloaderDanaData["id"] = $stock->id;

                    $autoloaderDanaData['dana_group_id'] = $stock->dana_group_id;

                    $autoloaderDanaData['consumption_quantity'] = $data->consumption_quantity;

                    $autoloaderDanaData['total_consumption'] = $consumption;



                    $presentQuantity = $stock->quantity;



                    $deduction = $presentQuantity - $autoloaderDanaData['consumption_quantity'];



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

                    $lam = FabricSendAndReceiveLaminatedFabricDetails::with('temporarylamfabric')

                            ->whereHas('temporarylamfabric', function ($query) use ($fsr_entry_id) {

                                $query->where('fsr_entry_id', $fsr_entry_id);

                            })

                            ->get();



                    foreach($lam as $data){

                        if(isset($data->temporarylamfabric->getfabric->getfabric->id)){
                            $fabric_id_on_stock = $data->temporarylamfabric->getfabric->getfabric->id;
                        }else{
                            dd($data->temporarylamfabric);
                        }



                        $fabric = Fabric::create([

                            'name' => $data->temporarylamfabric->fabric_name,

                            "godam_id" => $godam,

                            'slug' => $data->temporarylamfabric->slug,

                            'fabricgroup_id' => $data->fbgrp_id,

                            'status' => "1",

                            "average_wt" => $data->average_wt,

                            'gram_wt' => $data->gram_wt,

                            'gross_wt' => $data->gross_wt,

                            'net_wt' => $data->net_wt,

                            'meter' => $data->meter,

                            'roll_no' => $data->roll_no,

                            'loom_no' => $data->loom_no,

                            "is_laminated" => "true"

                        ]);



                        FabricStock::create([

                            'name' => $data->temporarylamfabric->fabric_name,

                            "godam_id" => $godam,

                            'slug' => $data->temporarylamfabric->slug,

                            'fabricgroup_id' => $data->fbgrp_id,

                            'status' => "1",

                            "average_wt" => $data->average_wt,

                            'gram_wt' => $data->gram_wt,

                            'gross_wt' => $data->gross_wt,

                            'net_wt' => $data->net_wt,

                            'meter' => $data->meter,

                            'roll_no' => $data->roll_no,

                            'loom_no' => $data->loom_no,

                            "is_laminated" => "true",

                            "fabric_id" => $fabric->id,

                            "date_np" => getNepaliDate(date('Y-m-d')),

                        ]);


                        $letlaminatedsentfsr = FabricSendAndReceiveLaminatedSent::create([



                            // "fsr_entry_id", "fabric_name" , "slug" , "net_wt" , "average_wt" ,"gross_wt", "gram_wt" , "meter" ,"fabricgroup_id" , 'standard_wt' ,"loom_no" , "roll_no"



                            'fabricgroup_id'  => $data->fbgrp_id,

                            'roll_no' => $data->roll_no,

                            "loom_no" => $data->loom_no,

                            'gross_wt' => $data->gross_wt,

                            'net_wt' => $data->net_wt ,

                            'meter' => $data->meter,

                            'average_wt' => $data->average_wt,

                            'gram' => $data->gram_wt,

                            'plantname_id' => $data->plantname_id,

                            'department_id' => $data->department_id,

                            'planttype_id' => $data->planttype_id,

                            'bill_number' => $data->bill_number,

                            'bill_date' => $data->bill_date,

                            "fsr_entry_id" => $fsr_entry_id,

                            'fabric_name'=> $data->temporarylamfabric->fabric_name,

                            "slug" => $data->temporarylamfabric->slug,

                            "gram_wt"  => $data->gram_wt,

                            'standard_wt' => $data->standard_wt,

                            "fabid" => $data->temporarylamfabric->getfabric->fabric_id

                        ]);



                        FabricStock::where("fabric_id",$fabric_id_on_stock)->delete();



                    }



                    foreach($tempconsumptionfsr as $data){

                        $fsrconsumption = FabricSendAndReceiveDanaConsumptionList::where("dana_name" , $data->dana_name_id)

                                                ->where("dana_group" , $data->dana_group_id)

                                                ->where("consumption_quantity" , $data->consumption_quantity)

                                                ->first();

                        if(is_null($fsrconsumption)){

                            $consumption =  new FabricSendAndReceiveDanaConsumptionList;

                            $consumption->consumption_quantity = $data->consumption_quantity;

                        }else{

                            $consumption = $fsrconsumption;

                            $consumption->consumption_quantity = $fsrconsumption->consumption_quantity  + $data->consumption_quantity;

                        }

                            $consumption->fsr_entry =  $fsr_entry_id;

                            $consumption->dana_name =  $data->dana_name_id;

                            $consumption->dana_group =  $data->dana_group_id;

                            $consumption->godam_id =  FabricSendAndReceiveEntry::where("id",$fsr_entry_id)->value("godam_id");

                            $consumption->save();

                    }





                    if($total_waste>0){

                        WasteStock::create([

                            'godam_id' => '1',

                            'waste_id' => '1',

                            'quantity_in_kg' => $total_waste,

                        ]);

                    }

                    FabricSendAndReceiveLaminatedFabricDetails::with('temporarylamfabric')

                        ->whereHas('temporarylamfabric', function ($query) use ($fsr_entry_id) {

                            $query->where('fsr_entry_id', $fsr_entry_id);

                        })->delete();

                    FabricSendAndReceiveDanaConsumption::where("fsr_entry_id",$fsr_entry_id)->delete();

                    FabricSendAndReceiveUnlaminatedFabric::where('status','sent')->where("fsr_entry_id",$fsr_entry_id)->delete();



                    FabricSendAndReceiveEntry::where("id",$fsr_entry_id)->update([

                        "status" => "completed"

                    ]);

                DB::commit();



                return response(200);

            }catch(Throwable $e){

                DB::rollBack();

                dd($e->getMessage().' '. $e->getLine());

                return response([

                    "exception" => $e->getMessage(),

                ]);

            }

        }

    }

    public function getallSentData(){

        return view('admin.fabricSendReceive.edit');

    }



    public function getDatajax(Request $request){

        if($request->ajax()){

            return DataTables::of(FabricSendAndReceiveLaminatedSent::get())

                ->addIndexColumn()

                ->addColumn("name",function($row){

                    return $row->fabric_name;

                })

                ->addColumn("action",function($row){

                    return "

                        <button class='edit-data btn btn-primary' data-roll='{$row->roll_no}' data-gross_wt='{$row->gross_wt}' data-id='{$row->id}'>Edit</button>

                    ";

                })

                ->rawColumns(["action"])

                ->make(true);

        }

    }



    public function updateLamSentFSr(Request $request){

        $request->validate([

            "laminated_id" => "required",

            "initial_fabric_id" => "required"

        ]);

        FabricSendAndReceiveLaminatedSent::where("id",$request->laminated_id)->update([

            "ini_fab_id" => $request->initial_fabric_id

        ]);

    }



    public function getFabricDetailsAccRollNo(Request $request){

        if($request->ajax()){

            return Fabric::where("roll_no",$request->roll_no)->first();

        }

    }

}
