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

    public function create()
    {

        $id = FabricSendAndReceiveEntry::latest()->value('id');

        $bill_number = "FSR" . "-" . getNepaliDate(date('Y-m-d')) . "-" . $id + 1;

        $bill_date  = date("Y-m-d");

        $bill_date_np = getNepaliDate(date("Y-m-d"));

        $godam = Godam::where("status", "active")->get();

        $shift = Shift::where("status", "active")->get();

        return view('admin.fabricSendReceive.create')->with([

            "bill_number" => $bill_number,

            "bill_date" => $bill_date,

            "bill_date_np" => $bill_date_np,

            "godam" => $godam,

            "shift" => $shift

        ]);
    }

    public function show($id)
    {
        $fabricSendReceiveEntry = FabricSendAndReceiveEntry::with('getgodam','getshift')->whereId($id)->firstOrFail();
        $lamFabrics = FabricSendAndReceiveLaminatedSent::where('fsr_entry_id', $fabricSendReceiveEntry->id)->get();

        $unlaminatedFabricIds = FabricSendAndReceiveLaminatedSent::where('fsr_entry_id', $fabricSendReceiveEntry->id)->pluck('fabid')->toArray();
        $unlaminatedFabrics = Fabric::with('godam')->whereIn('id', $unlaminatedFabricIds)->get();

        $unLams = [];

        foreach ($unlaminatedFabrics as $fabric) {
            $name = $fabric->name;

            if (!isset($unLams[$name])) {
                $unLams[$name] = [];
            }
            $unLams[$name][] = [
                'name' => $fabric->name,
                'roll_no' => $fabric->roll_no,
                'meter' => $fabric->meter,
                'gross_wt' => $fabric->gross_wt,
                'net_wt' => $fabric->net_wt,
                'average_wt' => $fabric->average_wt,
                'from_godam' => $fabric->godam->name,
                'shift' => $fabricSendReceiveEntry->getshift->name,
            ];
        }

        $lamFabricsData = [];

        foreach ($lamFabrics as $lamFabric) {
            $name = $lamFabric->fabric_name;

            if (!isset($lamFabricsData[$name])) {
                $lamFabricsData[$name] = [];
            }

            $lamFabricsData[$name][] = [
                'name' => $lamFabric->fabric_name,
                'roll_no' => $lamFabric->roll_no,
                'meter' => $lamFabric->meter,
                'gross_wt' => $lamFabric->gross_wt,
                'net_wt' => $lamFabric->net_wt,
                'average_wt' => $lamFabric->average_wt,
                'from_godam' => $fabricSendReceiveEntry->getgodam->name,
                'shift' => $fabricSendReceiveEntry->getshift->name,
            ];
        }

        return view('admin.fabricSendReceive.show', compact('fabricSendReceiveEntry', 'unLams','lamFabricsData'));
    }

    public function entrieslist(Request $request)
    {

        if ($request->ajax()) {

            $data = FabricSendAndReceiveEntry::with(["getgodam", "getplanttype", "getplantname", "getshift"])->orderBy("id", "desc")->get();

            return DataTables::of($data)

                ->addIndexColumn()

                ->addColumn("godam", function ($row) {

                    return $row->getgodam->name;
                })

                ->addColumn("planttype", function ($row) {

                    return $row->getplanttype->name;
                })

                ->addColumn("plantname", function ($row) {

                    return $row->getplantname->name;
                })

                ->addColumn("shift", function ($row) {

                    return $row->getshift->name;
                })

                ->addColumn("action", function ($row) {

                    if ($row->status == "pending") {

                        return "

                                        <div class='btn-group'>

                                            <button class='btn btn-primary create' data-id='{$row->id}'><i class='fa fa-plus' aria-hidden='true'></i></button>

                                            <button class='btn btn-danger delete' data-id='{$row->id}'><i class='fa fa-trash' aria-hidden='true'></i></button>

                                        </div>

                                    ";
                    } else {

                        return "

                                    <div class='btn-group'>

                                        <a href='" . route('fabricSendReceive.entry.show', $row->id) . "' class='btn btn-secondary' data-id='{$row->id}'><i class='fa fa-eye' aria-hidden='true'></i></a>

                                    </div>

                                ";
                    }
                })

                ->rawColumns([

                    "godam", "planttype", "plantname", "shift", "action"

                ])

                ->make(true);
        }
    }



    public function store(Request $request)
    {

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

            "godam_id" => $request->godam,

            "planttype_id" => $request->planttype,

            "plantname_id" => $request->plantname,

            "remarks" => $request->remarks,

            "shift_id" => $request->shift,

            "bill_number" => $request->bill_number,

            "bill_date" => $request->bill_date,

            "bill_date_np" => $request->bill_date_np

        ]);

        return back()->with("success", "Creation Successful");
    }



    public function delete(Request $request)
    {

        if ($request->ajax()) {

            FabricSendAndReceiveEntry::where("id", $request->id)->delete();

            return response([

                "status" => "200"

            ]);
        }
    }

    /************* End For Entries **************/



    public function indexrevised($id)
    {

        $dana = DanaName::where('status', 'active')->get();

        $data = FabricSendAndReceiveEntry::where("id", $id)->first();
        $sentEntry = $data;

        $getAllfabrics = FabricStock::where('status', '1')->where("godam_id", $data->godam_id)->get();

        $uniqueFabrics = $getAllfabrics->unique('name')->values()->all();

        $query = FabricSendAndReceiveDanaConsumption::where("fsr_entry_id", $id)->with("dananame");

        $consumptions = $query->get();

        $total_consumption = $query->sum("consumption_quantity");

        return view('admin.fabricSendReceive.indexrevised', compact("data",'sentEntry', "dana", "uniqueFabrics",'id','consumptions','total_consumption'));
    }



    public function getplanttype(Request $request)
    {

        if ($request->ajax()) {

            $department_id =  $request->id;



            $planttype = ProcessingStep::where('godam_id', $department_id)->get();



            $getAllfabrics = FabricStock::where('status', '1')->where("godam_id", $department_id)->get();

            $uniqueFabrics = $getAllfabrics->unique('name');





            return response([

                'planttype' => $planttype,

                'godamfabrics' => $uniqueFabrics->values()->all()

            ]);
        }
    }

    public function getplantname(Request $request)
    {

        if ($request->ajax()) {

            $department_id =  $request->id;

            $plantname = ProcessingSubcat::where('processing_steps_id', $department_id)->get();



            return response([

                'plantname' => $plantname



            ]);
        }
    }



    public function getfabrics(Request $request)
    {

        if ($request->ajax()) {

            $getAllfabrics = FabricStock::where('status', '1')->where("godam_id", $request->godam_id)->get();

            $uniqueFabrics = $getAllfabrics->unique('name');

            return response([

                'fabrics' => $uniqueFabrics->values()->all()

            ]);
        }
    }

    public function getfabricwithsamename(Request $request)
    {

        if ($request->ajax()) {

            $fabric_name_id = $request->fabric_name_id;

            $fabric_name = FabricStock::where("id", $fabric_name_id)->value("name");

            $fabrics = FabricStock::where("name", $fabric_name)->where('godam_id',$request->godam_id)->get();

            return DataTables::of($fabrics)

                ->addIndexColumn()

                ->addColumn("gram_wt", function ($row) {

                    return $row->fabricgroup->name;
                })

                ->addColumn("action", function ($row) {

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

                ->rawColumns(["action", "gram_wt"])

                ->make(true);



            // return response()->json([

            //     "fabrics" => $fabrics

            // ]);

        }
    }

    public function sendunlaminatedrevised2(Request $request)
    { //latest changes //keep

        // return $request;

        if ($request->ajax()) {

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



            try {

                DB::beginTransaction();



                FabricSendAndReceiveUnlaminatedFabric::create([

                    "fsr_entry_id" => $fsr_entry_id,

                    'fabric_id' => $fabric_id,

                    // 'roll_no' => $roll_number ,

                    // 'gross_wt' => $gross_wt ,

                    // 'net_wt' => $net_wt,

                    // 'meter' => $meter,

                    // 'average' => $average_wt,

                    // 'gram_wt' => $gram_wt,

                ]);



                DB::commit();
            } catch (Exception $e) {

                DB::rollBack();

                return response([

                    "message" => "Something went wrong!{$e->getMessage()}"

                ]);
            }
        }
    }

    public function getunlaminatedrevised($fsr_entry_id)
    { //latest

        $data = FabricSendAndReceiveUnlaminatedFabric::where('status', 'pending')->where("fsr_entry_id", $fsr_entry_id)->with('getfabric.fabricgroup')->get();

        if (count($data) != 0) {

            return response(['response' => $data]);
        } else {

            return response(['response' => '404']);
        }
    }





    public function sendunlaminateddeleterevised(Request $request, $id)
    { //latest

        if ($request->ajax()) {

            try {

                DB::beginTransaction();

                $count = FabricSendAndReceiveUnlaminatedFabric::where('id', $id)->get();

                $unlaminatedStock = FabricSendAndReceiveUnlaminatedFabric::where('id', $id)->get();

                if (count($count) > 0 && count($unlaminatedStock) > 0) {

                    FabricSendAndReceiveUnlaminatedFabric::where('id', $id)->delete();

                    DB::commit();

                    return response([

                        'response' => "200",

                    ]);
                } else {

                    DB::rollBack();

                    return response([

                        'response' => "400",

                    ]);
                }
            } catch (Exception $e) {

                DB::rollBack();

                return response([

                    "message" => "Something went wrong!'{$e->getMessage()}'"

                ]);
            }
        }
    }







    public function storelaminatedrevised(Request $request)
    { //latset

        try {

            $data = [];

            parse_str($request->data, $data);

            $data['fsr_entry_id'] = $request->fsr_entry_id;



            $idoffabricforsendtolamination = $data['idoffabricforsendtolamination'];



            $fabricunlaminated =  FabricSendAndReceiveUnlaminatedFabric::where('id', $idoffabricforsendtolamination)->with('getfabric')->first(); //where('id',$idoffabricforsendtolamination)->

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



            $fabric =  FabricSendAndReceiveUnlaminatedFabric::with('getfabric')->where('id', $idoffabricforsendtolamination)->first();

            $updatetosent = $fabric->update([

                "status" => "sent"

            ]);





            if ($lamimated_roll_no != null && $laminated_gross_weight != null && $laminated_net_weight != null && $laminated_avg_weight != null && $laminated_gram != null) {



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

                    "standard_wt" => $data["standard_wt"],

                    "fbgrp_id" => $data["fabricgroup_id"],

                    "meter" => $meter1,

                ]);



                if ($lamimated_roll_no_2 != null && $laminated_gross_weight_2 != null && $laminated_net_weight_2 != null && $laminated_avg_weight_2 != null && $laminated_gram_2 != null) {



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

                        "standard_wt" => $data["standard_wt"],

                        "fbgrp_id" => $data["fabricgroup_id"],

                        "meter" => $meter2,

                    ]);
                }



                if ($lamimated_roll_no_3 != null && $laminated_gross_weight_3 != null && $laminated_net_weight_3 != null && $laminated_avg_weight_3 != null && $laminated_gram_3 != null) {



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

                        "standard_wt" => $data["standard_wt"],

                        "fbgrp_id" => $data["fabricgroup_id"],

                        "meter" => $meter3,

                    ]);
                }
            }



            DB::commit();

            return "Done";
        } catch (Exception $e) {

            DB::rollback();

            return "exception" . $e->getMessage();
        }
    }

    public function comparelamandunlamrevised(Request $request, $entry_id)
    { //latest

        // dd($entry_id);

        if ($request->ajax()) {

            $unlam = FabricSendAndReceiveUnlaminatedFabric::with('getfabric')->where("fsr_entry_id", $entry_id)->where('status', "sent")->get();

            $ul_mtr_total = 0;

            $ul_net_wt_total = 0;

            foreach ($unlam as $data) {

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



            $lam_mtr_total = 0;

            $lam_net_wt_total = 0;

            foreach ($lam as $data) {

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

    public function deleteLam(Request $request)
    {
        $lam = FabricSendAndReceiveLaminatedFabricDetails::findOrFail($request->delete_id);
        $lam->delete();
        return response(['status' => true, 'message' => 'Lam deleted successfully']);
    }

    public function deleteUnLam(Request $request)
    {
        $unLam = FabricSendAndReceiveUnlaminatedFabric::findOrFail($request->delete_id);
        $unLam->delete();
        return response(['status' => true, 'message' => 'Un Lam deleted successfully']);
    }

    public function discard(Request $request)
    {

        if ($request->ajax()) {

            return "Not Allowed";

            try {

                $this->discardDelete();

                return response([

                    "message" => "200"

                ]);
            } catch (Exception $e) {

                return response([

                    "message" => "{$e->getMessage()}"

                ]);
            }
        }
    }



    function discardDelete()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('fabric_unlaminated')->truncate();

        DB::table('fabric_laminated')->truncate();

        DB::table('fabric_temporary_for_lamination')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }



    public function subtractdanafromautoloder(Request $request)
    {

        if ($request->ajax()) {

            $dana = $request->danaId;

            $quantity = $request->quantity;



            // try{

            //     AutoLoadItemStock::where("dana_name_id",$dana)->delete();

            // }catch(Exception $e){

            //     $e->getMessage();

            // }



            return response([

                "data" => [

                    "dana" => $dana,

                    "quantity" => $quantity

                ]

            ], 200);
        }
    }



    public function getStuffOfAutoloader(Request $request)
    {

        if ($request->ajax()) {
            // dd('ll');
            // dd($request);
            $find_data = FabricSendAndReceiveEntry::find($request->bill_id);
            // dd($find_data);

            $godamId = $request->godam_id;

            $plantName = $request->plantname;

            $planttype = $request->planttype;

            $shift = $request->shift;
            // dd($request);
            // $data = AutoLoadItemStock::get();
            // dd($data);

            $data = AutoLoadItemStock::where("from_godam_id", $find_data->godam_id)

                ->where("plant_name_id", $find_data->plantname_id)

                ->where("plant_type_id", $find_data->planttype_id)

                ->where("shift_id", $find_data->shift_id)

                ->with("fromGodam", "danaName")

                ->get();
            // dd($data->count());

            return response([

                "data" => $data

            ]);
        }
    }

    public function getStockQuantity(Request $request){
         $stockQty=AutoLoadItemStock::
            select('quantity')
            ->where('id',$request->autoloader_id)
            ->first();
            // dd($stockQty);
            return response([

                "data" => $stockQty

            ]);
    }



    public function addDanaConsumptionTablerevised(Request $request)
    { //latest

       try{
           DB::beginTransaction();
           if ($request->ajax()) {



               $autoloaderStock = AutoLoadItemStock::find($request->autoloader_id);
               // dd($autoloaderStock);
               // dd($request);


               // dd($request);
               // dd($request->quantity,$autoloaderStock->quantity);
               $fsrDanaConsumption=FabricSendAndReceiveDanaConsumption::where('fsr_entry_id', $request->fsr_entry_id)
               ->where('autoloader_id',$request->autoloader_id)
               ->first();
               // dd($fsrDanaConsumption);

                   if($fsrDanaConsumption != null){
                    // dd('lloo');
                      $fsrDanaConsumption->consumption_quantity= $fsrDanaConsumption->consumption_quantity+$request->consumption;
                      $fsrDanaConsumption->save();
                   }else{
                    // dd('ll');
                       $find_autoloader = AutoLoadItemStock::find($request->autoloader_id);

                       $dana_group_id  = DanaName::where("id", $request->dana_name_id)->value("dana_group_id");
                       // dd($find_autoloader);

                       $daata = FabricSendAndReceiveDanaConsumption::create([

                           "fsr_entry_id" => $request->fsr_entry_id,

                           "dana_name_id" => $request->dana_name_id,

                           "dana_group_id" => $dana_group_id,

                           "consumption_quantity" => $request->consumption,

                           "autoloader_id" => $request->autoloader_id,

                           "from_godam_id" => $find_autoloader->from_godam_id,
                           "plant_type_id" => $find_autoloader->plant_type_id,
                           "plant_name_id" => $find_autoloader->plant_name_id,
                           "shift_id" => $find_autoloader->shift_id,


                       ]);
                       // dd($daata);


               }

               $autoloaderStock->quantity -=$request->consumption;
               if($autoloaderStock->quantity <=0){
                   $autoloaderStock->delete();
               }else{
                   $autoloaderStock->save();
               }




               // $query = FabricSendAndReceiveDanaConsumption::where("fsr_entry_id", $request->fsr_entry_id)->with("dananame");

               // $consumptions = $query->get();
               // // dd($consumptions);

               // $total_consumption = $query->sum("consumption_quantity");


               // return response([

               //     "consumptions" => $consumptions,

               //     "total_consumption" => $total_consumption

               // ]);
           }


           DB::commit();
       }catch(Exception $e){
        dd($e);
           // DB::rollBack();
           return response([
               "message" => "Something went wrong!{$e->getMessage()}"
           ]);
       }






    }





    public function getDanaConsumptionTablerevised(Request $request)
    { //latest

        $query = FabricSendAndReceiveDanaConsumption::where("fsr_entry_id", $request->fsr_entry_id)->with("dananame");

        $consumptions = $query->get();

        $total_consumption = $query->sum("consumption_quantity");

        return response([

            "consumptions" =>  $consumptions,

            "total_consumption" => $total_consumption

        ]);
    }

    public function removedDanaConsumptionTablerevised(Request $request){
        // dd('lol');

        try{

           DB::beginTransaction();
           // dd($request);

           $find_data = FabricSendAndReceiveDanaConsumption::find($request->id);
           // dd($find_data);

           $autoloaderStock = AutoLoadItemStock::where('id',$find_data->autoloader_id)
           ->value('id');

           $find_autoloader = AutoLoadItemStock::find($autoloaderStock);
           // dd($find_autoloader);

           if($find_autoloader != null){
            // dd('lol');

               $find_autoloader->quantity= $find_autoloader->quantity + $find_data->consumption_quantity;
               $find_autoloader->update();
               $find_data->delete();

           }else{
            // dd('hey',$find_data);

               $autoloaderStock = new AutoLoadItemStock();
               $autoloaderStock->from_godam_id = $find_data->from_godam_id;
               $autoloaderStock->plant_type_id = $find_data->plant_type_id;
               $autoloaderStock->plant_name_id = $find_data->plant_name_id;
               $autoloaderStock->shift_id = $find_data->shift_id;
               $autoloaderStock->dana_name_id = $find_data->dana_name_id;
               $autoloaderStock->dana_group_id = $find_data->dana_group_id;
               $autoloaderStock->quantity = $find_data->consumption_quantity;
               $autoloaderStock->save();
               $find_data->delete();
           }

           DB::commit();
            return back();

        }
        catch(Exception $e){
            DB::rollback();
            dd($e);
            return "exception".$e->getMessage();
        }


    }





// bhabhish code

    public function removedDanaConsumptionTablereviseds(Request $request)
    { //latest

        if ($request->ajax()) {

            FabricSendAndReceiveDanaConsumption::where("id", $request->id)->delete();

            return response([

                "message" => "deleted successfully",

                "status" => 200

            ], 200);
        }
    }



    public function finalsubmitfsrrevised(Request $request)
    { //latest
       
        if ($request->ajax()) {

            $consumption = $request->consumption;

            $fabric_waste = $request->fabric_waste;

            $polo_waste = $request->polo_waste;

            $total_waste  = $request->total_waste;

            $lamFabricToDelete = [];

            $lamFabricTempToDelete = [];

            $department = [];

            $danaData = [];

            $fsr_entry_id = $request->fsr_entry_id;



            $fabric_entry = FabricSendAndReceiveEntry::where("id", $fsr_entry_id)->first();

            $godam = $fabric_entry->godam_id;



            // return $lam = FabricSendAndReceiveLaminatedFabricDetails::with('temporarylamfabric.getfabric.getfabric')->get(); //for nested relation

            try {

                DB::beginTransaction();

                $lam = FabricSendAndReceiveLaminatedFabricDetails::with('temporarylamfabric')

                    ->whereHas('temporarylamfabric', function ($query) use ($fsr_entry_id) {

                        $query->where('fsr_entry_id', $fsr_entry_id);
                    })

                    ->get();



                foreach ($lam as $data) {

                    if (isset($data->temporarylamfabric->getfabric->getfabric->id)) {
                        $fabric_id_on_stock = $data->temporarylamfabric->getfabric->getfabric->id;
                    } else {
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
                        'status_type' => "active",

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
                        'fabricgroup_id'  => $data->fbgrp_id,
                        'roll_no' => $data->roll_no,
                        "loom_no" => $data->loom_no,
                        'gross_wt' => $data->gross_wt,
                        'net_wt' => $data->net_wt,
                        'meter' => $data->meter,
                        'average_wt' => $data->average_wt,
                        'gram' => $data->gram_wt,
                        'plantname_id' => $data->plantname_id,
                        'department_id' => $data->department_id,
                        'planttype_id' => $data->planttype_id,
                        'bill_number' => $data->bill_number,
                        'bill_date' => $data->bill_date,
                        "fsr_entry_id" => $fsr_entry_id,
                        'fabric_name' => $data->temporarylamfabric->fabric_name,
                        "slug" => $data->temporarylamfabric->slug,
                        "gram_wt"  => $data->gram_wt,
                        'standard_wt' => $data->standard_wt,
                        "fabid" => $data->temporarylamfabric->getfabric->fabric_id
                    ]);



                    FabricStock::where("fabric_id", $fabric_id_on_stock)->delete();
                }

                // }
                //polo waste is lumps & fabric is rafia
                if ($request->polo_waste && $request->polo_waste != null) {
                   
                    $waste_id = Wastages::where('name', 'lumps')->first()->id;
                    $godam_id = $fabric_entry->godam_id;
                    $polo_waste = $request->polo_waste;
                
                    $existingWasteStock = WasteStock::where('godam_id', $godam_id)
                        ->where('waste_id', $waste_id)
                        ->first();
                    
                    if ($existingWasteStock) {
                        // If a record with the same godam_id and waste_id exists, update the quantity_in_kg
                        $existingWasteStock->quantity_in_kg += $polo_waste;
                        $existingWasteStock->save();
                        
                        // dd($existingWasteStock->quantity_in_kg);    
                    } else {
                         dd('laxmi');
                        // If no matching record exists, create a new row
                        WasteStock::create([
                            'godam_id' => $godam_id,
                            'waste_id' => $waste_id,
                            'quantity_in_kg' => $polo_waste,
                        ]);
                    }
                }
                
                if ($request->fabric_waste && $request->fabric_waste !=null){
                    // dd($request->fabric_waste);
                    $waste_id = Wastages::where('name', 'rafia')->first()->id;
                    $godam_id = $fabric_entry->godam_id;
                    $fabric_waste = $request->fabric_waste;
                
                    $existingWasteStock = WasteStock::where('godam_id', $godam_id)
                        ->where('waste_id', $waste_id)
                        ->first();
                
                    if ($existingWasteStock) {
                        // If a record with the same godam_id and waste_id exists, update the quantity_in_kg
                        $existingWasteStock->quantity_in_kg += $fabric_waste;
                        $existingWasteStock->save();

                    } else {
                        dd();
                        // If no matching record exists, create a new row
                        WasteStock::create([
                            'godam_id' => $godam_id,
                            'waste_id' => $waste_id,
                            'quantity_in_kg' => $fabric_waste,
                        ]);
                    }
                }           
                                      
                FabricSendAndReceiveLaminatedFabricDetails::with('temporarylamfabric')

                    ->whereHas('temporarylamfabric', function ($query) use ($fsr_entry_id) {

                        $query->where('fsr_entry_id', $fsr_entry_id);
                    })->delete();

                FabricSendAndReceiveDanaConsumption::where("fsr_entry_id", $fsr_entry_id)->delete();

                FabricSendAndReceiveUnlaminatedFabric::where('status', 'sent')->where("fsr_entry_id", $fsr_entry_id)->delete();

                FabricSendAndReceiveEntry::where("id", $fsr_entry_id)->update([
                    "polo_waste"=> $request->polo_waste? $request->polo_waste:0,
                    "fabric_waste"=>  $request->fabric_waste? $request->fabric_waste:0,
                    "total_waste"=> $request->total_waste ? $request->total_waste:0,
                    "status" => "completed"

                ]);

                DB::commit();
                return response(200);
            } catch (Throwable $e) {

                DB::rollBack();

                dd($e->getMessage() . ' ' . $e->getLine());

                return response([

                    "exception" => $e->getMessage(),

                ]);
            }
        }
    }

    public function getallSentData()
    {

        return view('admin.fabricSendReceive.edit');
    }



    public function getDatajax(Request $request)
    {

        if ($request->ajax()) {

            return DataTables::of(FabricSendAndReceiveLaminatedSent::get())

                ->addIndexColumn()

                ->addColumn("name", function ($row) {

                    return $row->fabric_name;
                })

                ->addColumn("action", function ($row) {

                    return "

                        <button class='edit-data btn btn-primary' data-roll='{$row->roll_no}' data-gross_wt='{$row->gross_wt}' data-id='{$row->id}'>Edit</button>

                    ";
                })

                ->rawColumns(["action"])

                ->make(true);
        }
    }



    public function updateLamSentFSr(Request $request)
    {

        $request->validate([

            "laminated_id" => "required",

            "initial_fabric_id" => "required"

        ]);

        FabricSendAndReceiveLaminatedSent::where("id", $request->laminated_id)->update([

            "ini_fab_id" => $request->initial_fabric_id

        ]);
    }



    public function getFabricDetailsAccRollNo(Request $request)
    {

        if ($request->ajax()) {

            return Fabric::where("roll_no", $request->roll_no)->first();
        }
    }

    public function getFsrFilterDataName(Request $request){
        // dd($request);


        $input = $request->titles;
        $parts = explode(' ', $input);
        $firstString = $parts[0];

        $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        return response([
            'name' => $find_name,
        ]);


    }
}
