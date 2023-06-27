<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use App\Models\AutoLoadItemStock;
use App\Models\DanaName;
use App\Models\Department;
use App\Models\FabricLaminatedSentFSR;
use App\Models\FabricStock;
use App\Models\FabricTemporaryForLam;
use App\Models\LaminatedFabric;
use App\Models\LaminatedFabricStock;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Fabric;
use App\Models\Tripal;
use App\Models\Unit;
use App\Models\UnlaminatedFabric;
use App\Models\Unlaminatedfabrictripal;
use App\Models\UnlaminatedFabricStock;
use App\Models\Wastages;
use App\Models\WasteStock;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Str;
use App\Models\Singlesidelaminatedfabric;
use App\Models\SinglesidelaminatedfabricStock;

class TripalController extends Controller
{
    public function index()
    {
        $id = UnlaminatedFabric::latest()->value('id');
        $bill_no = "TRP"."-".getNepaliDate(date('Y-m-d'))."-".$id+1;
        $bill_date = date('Y-m-d');
        $shifts = Shift::where('status','active')->get();
        $department = Department::where('status','active')->get();
        $planttype = ProcessingStep::where('status','1')->get();
        $plantname = ProcessingSubcat::where('status','active')->get();
        $dana = AutoLoadItemStock::get();
        $fabrics = Fabric::get();
        // dd($bill_date,$bill_no);
        return view('admin.tripal.index',compact('department','planttype','plantname','shifts','bill_no',"dana",'fabrics','bill_date'));
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
            // dd($data);
            
            $fabric_id = $data['fabricsid'];
            // dd($fabric_id);
            $fabric_data = Fabric::find($fabric_id);
            // dd($fabric_data);

            Unlaminatedfabrictripal::create([
                'bill_number' => $data['bill_no'],
                'bill_date' => $data['bill_date'],
                'fabric_id' =>$fabric_id ,
                'roll_no' => $fabric_data->roll_no ,
                'gross_wt' => $fabric_data->gross_wt ,
                'net_wt' => $fabric_data->net_wt,
                'meter' => $fabric_data->meter,
                'average' => '0',
                'gram' => '0',
                'department_id' =>$data['godam_id'],
                'planttype_id' => $data['planttype_id'],
                'plantname_id' =>  $data['plantname_id'],
                'sent' => 'status',
            ]);

            // dd('hello');

            $fabricstock =  Unlaminatedfabrictripal::with('fabric')->where('id',$fabric_id)->first(); //where('id',$idoffabricforsendtolamination)->
            $fabric_id = $fabricstock->fabric_id;
            $department_id = $fabricstock->department_id;
            $planttype_id = $fabricstock->planttype_id;
            $plantname_id = $fabricstock->plantname_id;
            $bill_number = $fabricstock->bill_number;
            $bill_date = $fabricstock->bill_date; 
            $meter = $fabricstock->meter;
            $fabricgroup_id = $fabricstock->fabric->id;

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
        

            $fabricmodelquery = Fabric::where('id',$fabric_id)->first();
            // dd($fabricmodelquery);

           DB::beginTransaction();
           // dd('lol');
                
                $fabric =  Unlaminatedfabrictripal::with('fabric')->where('id',$fabric_id)->first();
                $updatetosent = $fabric->update([
                    "status" => "sent"
                ]);

                
                if($lamimated_roll_no != null && $laminated_gross_weight != null && $laminated_net_weight != null && $laminated_avg_weight != null && $laminated_gram != null){

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
                        "meter" => $fabricmodelquery->meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        "status" => "1"
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
                        "meter" => $fabricmodelquery->meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id
                    ]);

                if($lamimated_roll_no_2 != null && $laminated_gross_weight_2 != null && $laminated_net_weight_2 != null && $laminated_avg_weight_2 != null && $laminated_gram_2 != null){

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
                        "meter" => $fabricmodelquery->meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "department_id" => $data['godam_id'],
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        "status" => "1"
                    ]);


                    $singlelamfabric_id = $single_lamfabric->id;


                    $single_stock = Singlesidelaminatedfabricstock::create([
                       "singlelamfabric_id" => $singlelamfabric_id,
                       "name" => $data['laminated_fabric_name'],
                       "slug" => Str::slug($data["laminated_fabric_name"]),
                       "roll_no" => $lamimated_roll_no_2, 
                       "department_id" => $data['godam_id'],
                       "gram" =>  $laminated_gram,
                       "loom_no" => $fabricmodelquery->loom_no,
                       "average_wt" => $laminated_avg_weight_2,
                       'gross_wt' => $laminated_gross_weight_2,
                       "roll_no" => $lamimated_roll_no_2,
                       'net_wt' => $laminated_net_weight_2,
                       "meter" => $fabricmodelquery->meter,
                       "bill_number" => $bill_number,
                       'bill_date' => $bill_date,
                       "planttype_id" => $planttype_id,
                       "plantname_id" => $plantname_id

                    ]);
                }

                if($lamimated_roll_no_3 != null && $laminated_gross_weight_3 != null && $laminated_net_weight_3 != null && $laminated_avg_weight_3 != null && $laminated_gram_3 != null){

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
                        "meter" => $fabricmodelquery->meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "department_id" => $data['godam_id'],                     
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id,
                        "status" => "1"
                    ]);

                    $singlelamfabric_id = $single_lamfabric->id;

                    $singlelamstock = Singlesidelaminatedfabricstock::create([
                        "singlelamfabric_id" => $singlelamfabric_id,
                        "name" => $data['laminated_fabric_name'],
                        "slug" => Str::slug($data["laminated_fabric_name"]),
                        "roll_no" => $lamimated_roll_no_3, 
                        "department_id" => $data['godam_id'],
                        "gram" =>  $laminated_gram,
                        "loom_no" => $fabricmodelquery->loom_no,
                        "average_wt" => $laminated_avg_weight_3,
                        'gross_wt' => $laminated_gross_weight_3,
                        "roll_no" => $lamimated_roll_no_3,
                        'net_wt' => $laminated_net_weight_3,
                        "meter" => $fabricmodelquery->meter,
                        "bill_number" => $bill_number,
                        'bill_date' => $bill_date,
                        "planttype_id" => $planttype_id,
                        "plantname_id" => $plantname_id
                    ]);
                }

             
            }

                
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
            $unlam = Unlaminatedfabrictripal::with('fabric')->where('status',"sent")->get();
            $ul_mtr_total=0;
            $ul_net_wt_total = 0;
            // dd($unlam);

            $unlamnet_wt = Unlaminatedfabrictripal::with('fabric')->where('status',"sent")->sum('net_wt');
            $unlamnet_meter = Unlaminatedfabrictripal::with('fabric')->where('status',"sent")->sum('meter');
         
            $lam = Singlesidelaminatedfabricstock::all();

            $lam_mtr_total = Singlesidelaminatedfabricstock::with('fabric')->where('status',"sent")->sum('net_wt');
            // dd($net_wt);
            $lam_net_wt_total = Unlaminatedfabrictripal::with('fabric')->where('status',"sent")->sum('meter');

       
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
                $department_id = Singlesidelaminatedfabric::value('department_id');
                // dd($lamFabric);

                //deduction
                    $stock = AutoLoadItemStock::where('dana_name_id',$selectedDanaID)->first();
                    $presentQuantity = $stock->quantity;
                    $deduction = $presentQuantity - $consumption;

                    if($deduction == 0){
                        $stock->delete();
                    }
                    else{
                        $stock->update([
                            "quantity" => $deduction
                        ]);
                    }

                    $unlamfabtripal = Unlaminatedfabrictripal::where('status','sent')->get();
                    $getalldata = Unlaminatedfabrictripal::where('bill_number',$unlamfabtripal[0]->bill_number)->get();
                    // dd($getalldata);
                    // dd($unlamfabtripal,$unlamfabtripal[0]->bill_number);

                    Tripal::create([
                        'bill_number' => $unlamfabtripal[0]->bill_number, 
                        'bill_date' => $unlamfabtripal[0]->bill_date, 
                        'fabric_id' => $unlamfabtripal[0]->fabric_id, 
                        'roll_no' => $unlamfabtripal[0]->roll_no, 
                        'gross_wt' => $unlamfabtripal[0]->gross_wt, 
                        'net_wt' => $unlamfabtripal[0]->net_wt, 
                        'meter' => $unlamfabtripal[0]->meter, 
                        'average' => $unlamfabtripal[0]->average, 
                        'gram' => $unlamfabtripal[0]->gram, 
                        'department_id' => $unlamfabtripal[0]->department_id, 
                        'planttype_id' => $unlamfabtripal[0]->planttype_id, 
                        'plantname_id' => $unlamfabtripal[0]->plantname_id, 
                        // 'status' => $data->lamfabric->status,
                        "type_lam" => "single"
                    ]);
                
                //fabric stock creation
                    // Unlaminatedfabrictripal::where('status','sent')->delete();
                    // $lamFabric = Singlesidelaminatedfabric::get();
                    // dd($lamFabric);
                    // foreach($lamFabric as $data){
                    //     Tripal::create([
                    //         'bill_number' => $data->lamfabric->bill_number, 
                    //         'bill_date' => $data->lamfabric->bill_date, 
                    //         'fabric_id' => $data->lamfabric->fabric_id, 
                    //         'roll_no' => $data->lamfabric->roll_no, 
                    //         'gross_wt' => $data->lamfabric->gross_wt, 
                    //         'net_wt' => $data->lamfabric->net_wt, 
                    //         'meter' => $data->lamfabric->meter, 
                    //         'average' => $data->lamfabric->average, 
                    //         'gram' => $data->lamfabric->gram, 
                    //         'department_id' => $data->lamfabric->department_id, 
                    //         'planttype_id' => $data->lamfabric->planttype_id, 
                    //         'plantname_id' => $data->lamfabric->plantname_id, 
                    //         'status' => $data->lamfabric->status,
                    //         "type_lam" => "single"
                    //     ]);

                    //     // FabricStock::create([
                    //     //     'name' => $data->lamfabric->name, 
                    //     //     'slug' => $data->lamfabric->slug, 
                    //     //     'fabricgroup_id' => $data->lamfabric->fabricgroup_id, 
                    //     //     'status' => $data->lamfabric->status,
                    //     //     'gram' => $data->gram,
                    //     //     'gross_wt' => $data->gross_wt,
                    //     //     'net_wt' => $data->net_wt,
                    //     //     'meter' => $data->meter,
                    //     //     'roll_no' => $data->roll_no,
                    //     //     'loom_no' => $data->lamfabric->loom_no,
                    //     //     "is_laminated" => "true"
                    //     // ]);

                    //     // FabricLaminatedSentFSR::create([
                    //     //     'name'=> $data->lamfabric->name, 
                    //     //     'slug' => $data->lamfabric->slug, 
                    //     //     'fabricgroup_id' => $data->lamfabric->fabricgroup_id,
                    //     //     'roll_no' => $data->roll_no,
                    //     //     "loom_no" => $data->lamfabric->loom_no,
                    //     //     'gross_wt' => $data->gross_wt,
                    //     //     'net_wt' => $data->net_wt ,
                    //     //     'meter' => $data->meter,
                    //     //     'average' => $data->average,
                    //     //     'gram' => $data->gram,
                    //     //     'plantname_id' => $data->plantname_id,
                    //     //     'department_id' => $data->department_id,
                    //     //     'planttype_id' => $data->planttype_id,
                    //     //     'bill_number' => $data->bill_number,
                    //     //     'bill_date' => $data->bill_date
                    //     // ]);

                    //     // $lamFabricToDelete[] = $data->id;
                    //     // $lamFabricTempToDelete[] = $data->lamfabric->id;

                    //     // if (!in_array($data->department_id, $department)) {
                    //     //     $department[] = $data->department_id;
                    //     // }
                        
                    // }

                    // Wastages::create([
                    //     'name' => 'tripal',
                    //     'waste_id' => '1',
                    //     'quantity_in_kg' => $total_waste,
                    // ]);

                    WasteStock::create([
                        'department_id' => $department_id,
                        'waste_id' => '1',
                        'quantity_in_kg' => $total_waste,
                    ]);

                    $wastage = Wastages::create([
                     'name' => 'tripal',
                    
                    ]);

                    // $wastage_stock = WasteStock::create([
                    //  'waste_id' => $wastage->id,
                    //  'department_id' => $data->lamfabric->department_id,
                    //  'quantity_in_kg' => $request->wastage,
                    
                    // ]);

                    // LaminatedFabric::whereIn('id', $lamFabricToDelete)->delete();

                    // FabricTemporaryForLam::whereIn('id',$lamFabricTempToDelete)->delete();

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
}
