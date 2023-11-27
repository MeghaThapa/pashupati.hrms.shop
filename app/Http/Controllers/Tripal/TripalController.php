<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\FabricStock;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Fabric;
use App\Models\Tripal;
use App\Models\Unit;
use App\Models\UnlaminatedFabric;
use App\Models\Unlaminatedfabrictripal;
use App\Models\Wastages;
use App\Models\WasteStock;
use App\Models\Singletripalname;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Str;
use App\Models\Singlesidelaminatedfabric;
use App\Models\Singlesidelaminatedfabricstock;
use App\Models\SingleTripalDanaConsumption;
use App\Models\SingleTripalBill;
use App\Helpers\AppHelper;
use Yajra\DataTables\DataTables;

class TripalController extends Controller
{
    public function index()
    {

        $bill_no = AppHelper::getSingleTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $shifts = Shift::where('status','active')->get();
        $godam = Godam::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();
        $dana = AutoLoadItemStock::get();

        $sumdana = SingleTripalDanaConsumption::where('bill_no',$bill_no)->sum('quantity');

        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();

        $datas = SingleTripalBill::get();

        return view('admin.tripal.index',compact('godam','planttype','plantname','shifts','bill_no',"dana",'bill_date','godams','sumdana','datas'));
    }

    public function dataTable()
    {
        // dd('lol');
        $tripalGodam = SingleTripalBill::orderBy('created_at','DESC')
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

                    return '<a href="' . route('addsingletripal.create', ['id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }
                else{
                    // return'completed';

                    return '<a href="' . route('singleTripal.viewBill', ['bill_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }

            })
            ->rawColumns(['fromgodam','togodam','action'])
            ->make(true);
    }

    public  function viewBill($bill_id){

        $find_data = SingleTripalBill::find($bill_id);
        $unlam_datas = Unlaminatedfabrictripal::where('bill_id',$bill_id)->get();
        $stocks = SinglesidelaminatedfabricStock::where('bill_id',$bill_id)->get();
        // dd($stocks);

        return view('admin.tripal.viewBill',compact('unlam_datas','stocks','bill_id','find_data'));

    }

    public function createSingletripal($id)
    {

        $find_data = SingleTripalBill::find($id);
        $bill_date = $find_data->bill_date;
        $bill_no = $find_data->bill_no;
        $planttype_id = $find_data->planttype_id;
        $plantname_id = $find_data->plantname_id;
        $shift_id = $find_data->shift_id;
        $godam_id = $find_data->godam_id;
        // dd($planttype_id);

        $danas = AutoLoadItemStock::where('plant_type_id',$planttype_id)
                               ->where('plant_name_id',$plantname_id)
                               ->where('shift_id',$shift_id)
                               ->where('from_godam_id',$godam_id)
                               ->get();

                               // dd($danas);

        // dd($bill_date);
        $shifts = Shift::where('status','active')->get();
        $godam = Godam::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();

        $sumdana = SingleTripalDanaConsumption::where('bill_no',$bill_no)->sum('quantity');

        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();

        // dd($danas);

        $datas = SingleTripalBill::get();
        $fabstocks = FabricStock::get()->unique('name')->values()->all();

        $danalist=SingleTripalDanaConsumption::where('bill_id',$id)->get();


        return view('admin.tripal.create',compact('godam','planttype','plantname','shifts','bill_no','bill_date','godams','sumdana','datas','id','find_data','fabstocks','danalist','danas'));
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
        // dd($request_fabric_id);
        if($request->ajax()){
            if($request->fabric_id != null){
              $fabric_name = FabricStock::where('id',$request->fabric_id)->where('status','1')->value('name');

              $fabrics = FabricStock::where('name',$fabric_name)->with('fabricgroup')->get();
            }
            if($request->roll){
                $fabrics = FabricStock::where('roll_no' , $roll)->with('fabricgroup')->get();
            }

            return response(['response'=>$fabrics]);

        }
    }

    public function checkAutoloadQuantity(Request $request){
        // dd($request);
        if($request->ajax()){
            $dana_id =  $request->danaid;
            $itemquantity = AutoLoadItemStock::where('dana_name_id',$dana_id)->value('quantity');
            return response([
                'itemquantity' => $itemquantity
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


    public function store(Request $request){
        try{

            $data = [];
            parse_str($request->data,$data);


            $fabricstock_id = $data['fabricsid'];
            $bill_id = $data['bill_id'];
            $fabric_data = FabricStock::find($fabricstock_id);
            $fabric_id = $fabric_data->fabric_id;

           DB::beginTransaction();


           $unlam =  Unlaminatedfabrictripal::create([
                'bill_number' => $data['bill_no'],
                'bill_date' => $data['bill_date'],
                'fabric_id' =>$fabric_id ,
                'roll_no' => $fabric_data->roll_no ,
                'gross_wt' => $fabric_data->gross_wt ,
                'net_wt' => $fabric_data->net_wt,
                'meter' => $fabric_data->meter,
                'average' => $fabric_data->average_wt,
                'gram' => $fabric_data->gram_wt,
                'department_id' =>$data['godam_id'],
                'planttype_id' => $data['planttype_id'],
                'plantname_id' =>  $data['plantname_id'],
                'bill_id' =>  $bill_id,
                'status' => "sent",
            ]);



            $fabric_id = $fabric_id;
            $department_id = $data['godam_id'];
            $planttype_id = $data['planttype_id'];
            $plantname_id = $data['plantname_id'];
            $bill_number = $data['bill_no'];
            $bill_date = $data['bill_date'];
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
            // dd($data['laminated_gram_3']);

            $laminated_meter = $data['laminated_meter'];
            $laminated_meter_2 = $data['laminated_meter_2'];
            $laminated_meter_3 = $data['laminated_meter_3'];


            $fabricmodelquery = Fabric::find($fabric_id);


             if($lamimated_roll_no != null && $laminated_gross_weight != null && $laminated_net_weight != null && $laminated_avg_weight != null && $laminated_gram != null && $laminated_meter != null){

                    $single_lamfabric = Singlesidelaminatedfabric::create([
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "fabric_id" => $fabric_id,
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram,
                        "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight,
                        'gross_wt' => $laminated_gross_weight,
                        "roll_no" => $lamimated_roll_no,
                        'net_wt' => $laminated_net_weight,
                        "meter" => $laminated_meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' =>  $bill_id,
                        "status" => "sent"
                    ]);
                    // dd('hello');

                    $singlelamfabric_id = $single_lamfabric->id;

                    $single_stock = Singlesidelaminatedfabricstock::create([
                        "singlelamfabric_id" => $singlelamfabric_id,
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "roll_no" => $lamimated_roll_no,
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram,
                        "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight,
                        'gross_wt' => $laminated_gross_weight,
                        "roll_no" => $lamimated_roll_no,
                        'net_wt' => $laminated_net_weight,
                        "meter" => $laminated_meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        'bill_id' =>  $bill_id,
                        "status" => "sent"
                        // "fabric_id" => $fabric_id,
                    ]);



            }

            if($lamimated_roll_no_2 != null && $laminated_gross_weight_2 != null && $laminated_net_weight_2 != null && $laminated_avg_weight_2 != null && $laminated_gram_2 != null && $laminated_meter_2 != null){

                $single_lamfabric = Singlesidelaminatedfabric::create([
                    "name" => $data['laminated_fabric_name'],
                    // "fabric_id" => $data['fabric_id'],
                    "slug" => Str::slug($data["laminated_fabric_name"]),
                    "fabric_id" => $fabric_id,
                    "gram" =>  $laminated_gram_2,
                    "loom_no" => $fabricmodelquery->loom_no,
                    "average_wt" => $laminated_avg_weight_2,
                    'gross_wt' => $laminated_gross_weight_2,
                    "roll_no" => $lamimated_roll_no_2,
                    'net_wt' => $laminated_net_weight_2,
                    "meter" => $laminated_meter_2,
                    "bill_number" => $bill_number,
                    'bill_date' => $bill_date,
                    "department_id" => $data['godam_id'],
                    "planttype_id" => $planttype_id,
                    "plantname_id" => $plantname_id,
                    'bill_id' =>  $bill_id,
                    "status" => "sent"
                ]);


                $singlelamfabric_id = $single_lamfabric->id;


                $single_stock = Singlesidelaminatedfabricstock::create([
                   "singlelamfabric_id" => $singlelamfabric_id,
                   "name" => $data['laminated_fabric_name'],
                   "slug" => Str::slug($data["laminated_fabric_name"]),
                   "roll_no" => $lamimated_roll_no_2,
                   "department_id" => $data['godam_id'],
                   "gram" =>  $laminated_gram_2,
                   "loom_no" => $fabricmodelquery->loom_no,
                   "average_wt" => $laminated_avg_weight_2,
                   'gross_wt' => $laminated_gross_weight_2,
                   "roll_no" => $lamimated_roll_no_2,
                   'net_wt' => $laminated_net_weight_2,
                   "meter" => $laminated_meter_2,
                   "bill_number" => $bill_number,
                   'bill_date' => $bill_date,
                   "planttype_id" => $planttype_id,
                   "plantname_id" => $plantname_id,
                   'bill_id' =>  $bill_id,
                   "status" => "sent"

                ]);


            }

            if($lamimated_roll_no_3 != null && $laminated_gross_weight_3 != null && $laminated_net_weight_3 != null && $laminated_avg_weight_3 != null && $laminated_gram_3 != null && $laminated_meter_3 != null){

                $single_lamfabric = Singlesidelaminatedfabric::create([
                    "name" => $data['laminated_fabric_name'],
                    // "fabric_id" => $data['fabric_id'],
                    "slug" => Str::slug($data["laminated_fabric_name"]),
                    "fabric_id" => $fabric_id,
                    "gram" =>  $laminated_gram_3,
                    "loom_no" => $fabricmodelquery->loom_no,
                    "average_wt" => $laminated_avg_weight_3,
                    'gross_wt' => $laminated_gross_weight_3,
                    "roll_no" => $lamimated_roll_no_3,
                    'net_wt' => $laminated_net_weight_3,
                    "meter" => $laminated_meter_3,
                    "bill_number" => $bill_number,
                    'bill_date' => $bill_date,
                    "department_id" => $data['godam_id'],
                    "planttype_id" => $planttype_id,
                    "plantname_id" => $plantname_id,
                    'bill_id' =>  $bill_id,
                    "status" => "sent"
                ]);

                $singlelamfabric_id = $single_lamfabric->id;

                $singlelamstock = Singlesidelaminatedfabricstock::create([
                    "singlelamfabric_id" => $singlelamfabric_id,
                    "name" => $data['laminated_fabric_name'],
                    "slug" => Str::slug($data["laminated_fabric_name"]),
                    "roll_no" => $lamimated_roll_no_3,
                    "department_id" => $data['godam_id'],
                    "gram" =>  $laminated_gram_3,
                    "loom_no" => $fabricmodelquery->loom_no,
                    "average_wt" => $laminated_avg_weight_3,
                    'gross_wt' => $laminated_gross_weight_3,
                    "roll_no" => $lamimated_roll_no_3,
                    'net_wt' => $laminated_net_weight_3,
                    "meter" => $laminated_meter_3,
                    "bill_number" => $bill_number,
                    'bill_date' => $bill_date,
                    "planttype_id" => $planttype_id,
                    'bill_id' =>  $bill_id,
                    "plantname_id" => $plantname_id,
                    "status" => "sent"
                ]);

                // $stock = FabricStock::where('fabric_id',$fabric_id)->value('net_wt');
                // $fabrics_id = FabricStock::where('fabric_id',$fabric_id)->value('id');

                // if($laminated_net_weight_3 != null){
                //   $finalstock = $stock - $laminated_net_weight_3 ;
                //   $find_fabric = FabricStock::find($fabrics_id);
                //   $find_fabric->net_wt = $finalstock;
                //   $find_fabric->update();

                // }
            }

            FabricStock::where('id',$fabricstock_id)->delete();


           DB::commit();
           return "Done";
        }
        catch(Exception $e){
            DB::rollback();
            dd($e);
            return "exception".$e->getMessage();
        }
    }

    public function getUnlamSingleLam(Request $request){
        if($request->ajax()){
            $unlam = Unlaminatedfabrictripal::where('bill_id',$request->bill_id)->where('status',"sent")->with('fabric')->get();
            $ul_mtr_total=0;
            $ul_net_wt_total = 0;

            $unlamnet_wt = Unlaminatedfabrictripal::where('bill_id',$request->bill_id)->where('status',"sent")->sum('net_wt');
            $unlamnet_meter = Unlaminatedfabrictripal::where('bill_id',$request->bill_id)->where('status',"sent")->sum('meter');

            $lam = Singlesidelaminatedfabricstock::where('bill_id',$request->bill_id)->where('status',"sent")->get();
            // dd($lam);

            $lam_mtr_total = Singlesidelaminatedfabricstock::where('bill_id',$request->bill_id)->where('status',"sent")->sum('net_wt');
            $lam_net_wt_total = Singlesidelaminatedfabricstock::where('bill_id',$request->bill_id)->where('status',"sent")->sum('meter');


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
            $consumption = $request->consumption;
            $danaNameID = $request->danaNameID;
            $fabric_waste = $request->fabric_waste;
            $polo_waste = $request->polo_waste;
            $selectedDanaID = $request->selectedDanaID;
            $total_waste  = $request->total_waste;
            $lamFabricToDelete = [];
            $lamFabricTempToDelete = [];
            $department = [];


            try{
                DB::beginTransaction();

                $polowaste = $request->polo_waste;
                $fabricwaste = $request->fabric_waste;
                // dd($request);

                $getFabricLastId = Singlesidelaminatedfabric::where('bill_number',$request->bill)->where('status','sent')->latest()->first();


                $department_id = Singlesidelaminatedfabric::value('department_id');



                $getSinglesidelaminatedfabric = Singlesidelaminatedfabric::where('bill_number',$request->bill)->update(['status' => 'completed']);

                $getSinglesidelaminatedfabricstock = Singlesidelaminatedfabricstock::where('bill_number',$request->bill)->update(['status' => 'completed']);

                $unlaminatedfabrictripal = Unlaminatedfabrictripal::where('bill_number',$request->bill)->update(['status' => 'completed']);

                $find_godam = Singlesidelaminatedfabricstock::where('bill_number',$request->bill)->latest()->first();




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

                    $total_waste = $polowaste + $fabric_waste;

                    $singlebill = SingleTripalBill::where('id',$request->bill_id)
                    ->update([
                        'status' => 'completed',
                        'polo_waste' => $polowaste,
                        'fabric_waste' => $fabric_waste,
                        'total_waste' => $total_waste
                    ]);


                DB::commit();

                return response(200);
            }catch(Exception $e){
                DB::rollBack();
                // dd($e);
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

        Singletripalname::create([
            'name' => $request['name'],
            'slug' => $request['name'],
            'date_en' => $date_en,
            'date_np' => $date_np,
        ]);
         return back();


    }

    //singlefilterdata

    public function getSingleFilterData(Request $request){

        $tripal_id = $request->singletripal;
        $find_data = Singletripalname::find($tripal_id);

        $input = $find_data->name;
        $parts = explode(' ', $input);
        $firstString = $parts[0];

        $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        return response([
            'name' => $find_name,
        ]);


    }

    //singlefilterdata for single

    public function getSingleFabricFilterData(Request $request){

        $tripal_id = $request->ids;
        $find_data = FabricStock::find($tripal_id);

        $input = $find_data->name;
        $parts = explode(' ', $input);
        $firstString = $parts[0];

        $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        return response([
            'name' => $find_name,
        ]);


    }

    public function getStockQuantity(Request $request){
         $stockQty=AutoLoadItemStock::
            select('quantity')
            ->where('id',$request->autoloader_id)
            ->first();
        return $stockQty;
    }
}
