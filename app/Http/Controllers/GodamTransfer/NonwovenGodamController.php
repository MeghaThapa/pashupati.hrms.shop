<?php

namespace App\Http\Controllers\GodamTransfer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Godam;
use App\Models\NonwovenGodam;
use App\Models\NonwovenGodamList;
use App\Models\NonwovenGodamEntry;
use App\Models\FinalTripalStock;
use App\Models\NonWovenFabric;
use App\Models\FabricNonWovenReceiveEntryStock;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class NonwovenGodamController extends Controller
{
    public function index(Request $request)
    {
       
        return view('admin.godamtransfer.nonwoven.index');
    }

    public function create()
    {
        $fromgodams = Godam::where('status','active')->get();
        $togodams = Godam::where('status','active')->get();
        return view('admin.godamtransfer.nonwoven.create',compact('fromgodams','togodams'));
    }

    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'bill_number' => 'required',
        ]);

        try{
        // store category
        $fabricgodam = NonwovenGodam::create([
            'bill_no' => $request->bill_number,
            'bill_date' => $request->bill_date,
            'fromgodam_id' => $request->fromgodam_id,
            'togodam_id' => $request->togodam_id,
            'remarks' => $request->remarks,
        ]);
        return redirect()->route('nonwovenGodamTransfer.index');
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    public function dataTable()
    {
        $nonwovenGodam = NonwovenGodam::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($nonwovenGodam)
            ->addIndexColumn()
            ->addColumn('fromgodam', function ($row) {
                return $row->getFromGodam->name;
            })
            ->addColumn('togodam', function ($row) {
                return $row->getToGodam->name;
            })
            ->addColumn('action', function ($row) {

                if($row->status == "sent"){

                    return '<a href="' . route('nonwovenGodam.transferFabric', ['nonwovengodam_id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }
                else{
                    // return'completed';

                    return '<a href="' . route('nonwovenGodam.viewBill', ['nonwovengodam_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }

            })
            ->rawColumns(['fromgodam','togodam','action'])
            ->make(true);
    }

    public function getTransferFilter(Request $request){
        if($request->ajax()){
            // dd($request);
            $fabric_gsm = $request->fabric_gsm;

            $datas = FabricNonWovenReceiveEntryStock::get();

            // if(!empty($fabric_gsm)){
            //   $datas = FabricNonWovenReceiveEntryStock::where('fabric_gsm', $fabric_gsm);
            // }

            // $datas = $datas->get();
            // dd($datas);

           

            return DataTables::of($datas)
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

    public function transferFabric($nonwovengodam_id)
    {
        $find_data = NonwovenGodam::find($nonwovengodam_id);
        $stocks = FinalTripalStock::get();
        $nonwovenfabrics = NonWovenFabric::distinct()->get(['gsm']);
       
        return view('admin.godamtransfer.nonwoven.transferFabric',compact('stocks','nonwovengodam_id','find_data','nonwovenfabrics'));
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

    

    public function getNonwovenGodamStore(Request $request)
    {

        try{
            // dd($request);
            $find_name = FabricNonWovenReceiveEntryStock::find($request->data_id);
            // dd($find_name);

                // store category
                $fabricgodam = NonwovenGodamEntry::create([
                    'slug' => $find_name->fabric_name,
                    'roll' => $find_name->fabric_roll,
                    'gsm' => $find_name->fabric_gsm,
                    'name' => $find_name->fabric_name,
                    'color' => $find_name->fabric_color,
                    'length' => $find_name->length,
                    'gross' => $find_name->gross_weight,
                    'net' => $find_name->net_weight,
                    'godam_id' => $find_name->godam_id,
                    'nonwoven_id' => $find_name->nonwovenname_id,
                    'bill_no' => $find_name->receive_no,
                    'bill_date' => $find_name->receive_date,
                    'nonwovengodam_id' => $request->nonwovengodam_id,
                    'stock_id' => $request->data_id,
                ]);
          

        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    public function getNonwovenGodamList(Request $request){
        // dd($request);

        if($request->ajax()){
            $tripal_id = $request->tripal_id;
            $fabrics = NonwovenGodamEntry::where("nonwovengodam_id",$tripal_id)->where('status','sent')->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                   
                    ->addColumn("action",function($row){
                        return "
                        <a class='btn btn-danger deleteNonwovenEntry'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Delete</a>";
                    })
                    ->rawColumns(["action"])
                    ->make(true);

        }
      
    }

    public function deleteList(Request $request)
    {

        $unit = NonwovenGodamEntry::find($request->data_id);

        // delete unit
        $unit->delete();
        return response([
            "message" => "Deleted Successfully" 
        ]);

    }


    public function getFabricGodamList(Request $request){
        if($request->ajax()){

            $godamlist = NonwovenGodamList::where('status','sent')->with('getFromGodam','getToGodam')->get();
            // dd($godamlist);

       
            return response([
                "godamlist" => $godamlist,
            ]);
        }
    }

    public function getNonwovenGodamFinalStore(Request $request)
    {

        try{
            DB::beginTransaction();
            // dd($request);
            $getlist = NonwovenGodamEntry::where('nonwovengodam_id',$request->nonwovengodam_id)->where('status','sent')->get();

            foreach ($getlist as $list) {

                $data = NonwovenGodam::where('id',$request->nonwovengodam_id)->value('togodam_id');

                $nonwovenstock = FabricNonWovenReceiveEntryStock::create([
                    // 'nonfabric_id' => $fabricreceiveenty->id,
                    // 'bill_id' => $list->bill_no,
                    'receive_date' => $list->bill_date,
                    'receive_no' => $list->bill_no,
                    'fabric_roll' => $list->roll,
                    'fabric_gsm' => $list->gsm,
                    'fabric_name' => $list->name,
                    'fabric_color' => $list->color,
                    'length' => $list->length,
                    'gross_weight' => $list->gross,
                    'net_weight' => $list->net,
                    'godam_id' => $data,
                ]);

              

                

                if($nonwovenstock){

                  FabricNonWovenReceiveEntryStock::where('id',$list->stock_id)->delete();
                }


            }

            $getupdate = NonwovenGodamEntry::where('nonwovengodam_id',$request->nonwovengodam_id)->update(['status' => 'completed']); 
            $getupdates = NonwovenGodam::where('id',$request->nonwovengodam_id)->update(['status' => 'completed']); 

            DB::commit();

        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

    public  function viewBill($tripalgodam_id){

        $find_data = NonwovenGodam::find($tripalgodam_id);
        $stocks = NonwovenGodamEntry::where('tripalgodam_id',$tripalgodam_id)->get();
        $id = $tripalgodam_id;
          
        return view('admin.godamtransfer.nonwoven.viewBill',compact('stocks','tripalgodam_id','find_data','id'));

    }

}
