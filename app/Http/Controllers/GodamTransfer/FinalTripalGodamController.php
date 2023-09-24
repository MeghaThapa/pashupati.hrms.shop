<?php

namespace App\Http\Controllers\GodamTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Godam;
use App\Models\TripalGodam;
use App\Models\TripalGodamList;
use App\Models\TripalGodamEntry;
use App\Models\FinalTripalStock;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class FinalTripalGodamController extends Controller
{
    public function index(Request $request)
    {
       
        return view('admin.godamtransfer.finaltripal.index');
    }

    public function create()
    {
        $fromgodams = Godam::where('status','active')->get();
        $togodams = Godam::where('status','active')->get();
        return view('admin.godamtransfer.finaltripal.create',compact('fromgodams','togodams'));
    }

    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'bill_number' => 'required',
        ]);

        try{
        // store category
        $fabricgodam = TripalGodam::create([
            'bill_no' => $request->bill_number,
            'bill_date' => $request->bill_date,
            'fromgodam_id' => $request->fromgodam_id,
            'togodam_id' => $request->togodam_id,
            'remarks' => $request->remarks,
        ]);
        return redirect()->route('tripalGodamTransfer.index');
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    public function dataTable()
    {
        $tripalGodam = TripalGodam::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($tripalGodam)
            ->addIndexColumn()
            ->addColumn('fromgodam', function ($row) {
                return $row->getFromGodam->name;
            })
            ->addColumn('togodam', function ($row) {
                return $row->getToGodam->name;
            })
            ->addColumn('action', function ($row) {

                if($row->status == "sent"){

                    return '<a href="' . route('tripalGodam.transferFabric', ['tripalgodam_id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }
                else{
                    // return'completed';

                    return '<a href="' . route('tripalGodam.viewBill', ['tripalgodam_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }

            })
            ->rawColumns(['fromgodam','togodam','action'])
            ->make(true);
    }

    public function getTransferFilter(Request $request){
        if($request->ajax()){
            $stock_id = $request->stock_id;
            $find_bill = TripalGodam::find($request->bill_id);
            // dd($find_bill);
            $fabric_name = FinalTripalStock::where('department_id',$find_bill->fromgodam_id)->where("id",$stock_id)->value("name");
            $fabrics = FinalTripalStock::where('department_id',$find_bill->fromgodam_id)->where("name",$fabric_name)->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                   
                    ->addColumn("action",function($row,Request $request){
                        return "
                        <a class='btn btn-primary sendforentry'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Send</a>";
                    })
                    ->rawColumns(["action"])
                    ->make(true);

           
        }
    }

    public function transferFabric($tripalgodam_id)
    {
        $find_data = TripalGodam::find($tripalgodam_id);
        $stocks = FinalTripalStock::where('department_id',$find_data->fromgodam_id)->get()->unique('name')->values()->all();
        // dd('lol');

        $total_net = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->sum('net');
        $total_gross = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->sum('gross');
        $total_meter = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->sum('meter');
        $total_roll = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->count();
       
        return view('admin.godamtransfer.finaltripal.transferFabric',compact('stocks','tripalgodam_id','find_data','total_net','total_gross','total_meter','total_roll'));
    }

    public function transferFabricDetail($fabricgodam_id)
    {
        $find_data = FabricGodam::find($fabricgodam_id);
        $fabricdetails = FabricGodamTransfer::where('fabricgodam_id',$fabricgodam_id)->paginate(10);
        $net_wt = 0;
        if($find_data->bill_no != null){

          $net_wt = FabricGodamTransfer::where('fabricgodam_id',$fabricgodam_id)->sum('net_wt');
        }
        return view('admin.fabric.fabricgodam.transferFabricDetail',compact('fabricdetails','find_data','net_wt'));
    }

    public function deleteFabricGodamList(Request $request)
    {

        $unit = FabricGodamList::find($request->data_id);

        // delete unit
        $unit->delete();
        return response([
            "message" => "Deleted Successfully" 
        ]);

    }

   

    public function getTripalGodamStore(Request $request)
    {

        try{
            // dd($request);
            $find_name = FinalTripalStock::find($request->data_id);

                // store category
                $fabricgodam = TripalGodamEntry::create([
                    'name' => $find_name->name,
                    'slug' => $find_name->slug,
                    'roll' => $find_name->roll_no,
                    'gross' => $find_name->gross_wt,
                    'net' => $find_name->net_wt,
                    'meter' => $find_name->meter,
                    'average' => $find_name->average_wt,
                    'gsm' => $find_name->gsm,
                    'finaltripal_id' => $find_name->finaltripalname_id,
                    'godam_id' => $find_name->department_id,
                    'bill_no' => $find_name->bill_number,
                    'bill_date' => $find_name->bill_date,
                    'tripalgodam_id' => $request->tripalgodam_id,
                    'stock_id' => $request->data_id,
                ]);
          

        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    public function getTripalGodamList(Request $request){
        // dd($request);

        if($request->ajax()){
            $tripal_id = $request->tripal_id;
            $fabrics = TripalGodamEntry::where("tripalgodam_id",$tripal_id)->where('status','sent')->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                   
                    ->addColumn("action",function($row){
                        return "
                        <a class='btn btn-danger deleteTripalEntry'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Delete</a>";
                    })
                    ->rawColumns(["action"])
                    ->make(true);

        }
      
    }

    public function deleteList(Request $request)
    {

        $unit = TripalGodamEntry::find($request->data_id);

        // delete unit
        $unit->delete();
        return response([
            "message" => "Deleted Successfully" 
        ]);

    }


    public function getFabricGodamList(Request $request){
        if($request->ajax()){

            $godamlist = TripalGodamList::where('status','sent')->with('getFromGodam','getToGodam')->get();
            // dd($godamlist);

       
            return response([
                "godamlist" => $godamlist,
            ]);
        }
    }

    public function getTripalGodamFinalStore(Request $request)
    {

        try{
            DB::beginTransaction();
            $getlist = TripalGodamEntry::where('tripalgodam_id',$request->tripalgodam_id)->where('status','sent')->get();

            foreach ($getlist as $list) {

                $data = TripalGodam::where('id',$request->tripalgodam_id)->value('togodam_id');

                $finaltripalstock = FinalTripalStock::create([
                          "name" => $list->name,
                          "slug" => $list->slug,
                          "bill_number" => $list->bill_no,
                          'bill_date' => $list->bill_date,
                          "department_id" => $data,
                          "gram" =>  $list->gram,
                          "loom_no" => '0',
                          "roll_no" => $list->roll,
                          'gross_wt' => $list->gross,
                          "meter" => $list->meter,
                          "average_wt" => $list->average,
                          "gsm" => $list->gsm,
                          'net_wt' => $list->net,
                          "finaltripalname_id" => $list->finaltripal_id,
                          "date_en" => $list->bill_date,
                          "date_np" => $list->bill_date,

                          "status" => "sent"
                      ]);

                

                if($finaltripalstock){

                  FinalTripalStock::where('id',$list->stock_id)->delete();
                }


            }

            $getupdate = TripalGodamEntry::where('tripalgodam_id',$request->tripalgodam_id)->update(['status' => 'completed']); 
            $getupdates = TripalGodam::where('id',$request->tripalgodam_id)->update(['status' => 'completed']); 

            DB::commit();

        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

    public  function viewBill($tripalgodam_id){

        $find_data = TripalGodam::find($tripalgodam_id);
        $stocks = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->get();
        $id = $tripalgodam_id;

        $total_net = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->sum('net');
        $total_gross = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->sum('gross');
        $total_meter = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->sum('meter');
        $total_roll = TripalGodamEntry::where('tripalgodam_id',$tripalgodam_id)->count();
          
        return view('admin.godamtransfer.finaltripal.viewBill',compact('stocks','tripalgodam_id','find_data','id','total_net','total_gross','total_meter','total_roll'));

    }

  

}
