<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Godam;
use App\Models\FabricGodam;
use App\Models\FabricGodamTransfer;
use App\Models\FabricStock;
use App\Models\FabricGroup;
use App\Models\FabricGodamList;
use DB;
use Throwable;
use App\Helpers\AppHelper;
use Yajra\DataTables\Facades\DataTables;


class FabricGodamController extends Controller
{
    public function index(Request $request)
    {
       
        return view('admin.fabric.fabricgodam.index');
    }

    public function create()
    {
        $fromgodams = Godam::where('status','active')->get();
        $togodams = Godam::where('status','active')->get();
        return view('admin.fabric.fabricgodam.create',compact('fromgodams','togodams'));
    }

    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'bill_number' => 'required',
        ]);

        try{
        // store category
        $fabricgodam = FabricGodam::create([
            'bill_no' => $request->bill_number,
            'bill_date' => $request->bill_date,
            'fromgodam_id' => $request->fromgodam_id,
            'togodam_id' => $request->togodam_id,
            'remarks' => $request->remarks,
            'status' => 'sent',
        ]);
        return redirect()->route('fabricgodams.index');
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    public function transferFabric($fabricgodam_id)
    {
        $find_data = FabricGodam::find($fabricgodam_id);
        $fabricstocks = FabricStock::where('godam_id',$find_data->fromgodam_id)->get();
        $fromgodams = Godam::where('status','active')->get();
        $togodams = Godam::where('status','active')->get();
        $list = FabricGodamList::where('fabricgodam_id',$fabricgodam_id)->where('status','sent')->count();
        return view('admin.fabric.fabricgodam.transferFabric',compact('fabricstocks','fromgodams','togodams','fabricgodam_id','find_data','list'));
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

    public function viewbill($fabricgodam_id)
    {
        $find_data = FabricGodam::find($fabricgodam_id);
        $fabricdetails = FabricGodamTransfer::where('fabricgodam_id',$fabricgodam_id)->get();

        $total_net = FabricGodamTransfer::where('fabricgodam_id',$fabricgodam_id)->sum('net_wt');
        
        return view('admin.fabric.fabricgodam.viewbill',compact('fabricdetails','find_data','total_net'));
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
                    ->addColumn("action",function($row,Request $request){
                        return "
                        <a class='btn btn-primary sendforlamination'  
                                 data-id='{$row->id}' 
                                 data-fromgodamid='{$request->fromgodam_id}' 
                                 data-togodamid='{$request->togodam_id}' 
                                 bill_no='{$request->bill_number}'  
                                 bill_date = '{$request->bill_date}'
                                 href='{$row->id}'>Send</a>";
                    })
                    ->rawColumns(["action","gram_wt"])
                    ->make(true);

           
        }
    }

    public function getFabricGodamStore(Request $request)
    {
        

        try{
            $find_name = FabricStock::find($request->ids);

                // store category
                $fabricgodam = FabricGodamList::create([
                    'name' => $find_name->name,
                    'slug' => $find_name->slug,
                    'roll' => $find_name->roll_no,
                    'net_wt' => $find_name->net_wt,
                    'fabricgodam_id' => $request->fabricgodam_id,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $request->bill_date,
                    'fromgodam_id' => $request->fromgodam_id,
                    'togodam_id' => $request->togodam_id,
                    'stock_id' => $request->ids,
                ]);
          

        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    public function getFabricGodamList(Request $request){
        if($request->ajax()){
            // dd($request);

            $godamlist = FabricGodamList::where('fabricgodam_id',$request->fabricgodam_id)->where('status','sent')->with('getFromGodam','getToGodam')->get();
            // dd($godamlist);

       
            return response([
                "godamlist" => $godamlist,
            ]);
        }
    }


    public function getFabricGodamFinalStore(Request $request)
    {
        // dd($request);
        

        try{
            DB::beginTransaction();
            $getlist = FabricGodamList::where('fabricgodam_id',$request->fabricgodam_id)->where('status','sent')->get();
            // dd($getlist);

            foreach ($getlist as $list) {

                $find_name = FabricStock::find($list->stock_id);
                // dd($list->stock_id,$find_name);
                // dd($find_name);

                $fabricgodam = FabricGodamTransfer::create([
                    'name' => $find_name->name,
                    'slug' => $find_name->slug,
                    'roll' => $find_name->roll_no,
                    'net_wt' => $find_name->net_wt,
                    'fabricgodam_id' => $request->fabricgodam_id,
                    'bill_no' => $list->bill_no,
                    'bill_date' => $list->bill_date,
                    'fromgodam_id' => $list->fromgodam_id,
                    'togodam_id' => $list->togodam_id,
                ]);

                $fabricstock = FabricStock::create([
                    'name' => $find_name->name,
                    'roll_no' => $find_name->roll_no,
                    'loom_no' => $find_name->loom_no,
                    'fabricgroup_id' => $find_name->fabricgroup_id,
                    'gross_wt' => $find_name->gross_wt,
                    'net_wt' => $find_name->net_wt,
                    'meter' => $find_name->meter,
                    'gram_wt' => $find_name->gram_wt,
                    'average_wt' => $find_name->average_wt,
                    'godam_id' => $list->togodam_id,
                    'date_np' => $find_name->date_np,
                    'bill_no' => $find_name->bill_no,
                    'fabric_id' => $find_name->fabric_id
                ]);

                if($fabricstock){

                  FabricStock::where('id',$list->stock_id)->delete();
                }



            }

            $getupdate = FabricGodamList::where('fabricgodam_id',$request->fabricgodam_id)->update(['status' => 'completed']); 
            $getupdates = FabricGodam::where('id',$request->fabricgodam_id)->update(['status' => 'completed']);
            


            DB::commit();

        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

    public function dataTable()
    {
        $rawMaterial = FabricGodam::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($rawMaterial)
            ->addIndexColumn()
            ->addColumn('fromgodam', function ($row) {
                return $row->getFromGodam->name;
            })
            ->addColumn('togodam', function ($row) {
                return $row->getToGodam->name;
            })

            ->addColumn('action', function ($row) {

                if($row->status == "sent"){

                    return '<a href="' . route('fabricgodams.transferFabric', ['fabricgodam_id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }
                else{
                    // return'completed';

                    return '<a href="' . route('fabricgodams.transferFabricDetail', ['fabricgodam_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }


            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
