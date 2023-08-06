<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Godam;
use App\Models\FabricGodam;
use App\Models\FabricGodamTransfer;
use App\Models\FabricStock;
use App\Models\FabricGroup;
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
        return view('admin.fabric.fabricgodam.create');
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
            'remarks' => $request->remarks,
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
        $fabricstocks = FabricStock::get();
        $fromgodams = Godam::where('status','active')->get();
        $togodams = Godam::where('status','active')->get();
        $groups = FabricGroup::get();
        return view('admin.fabric.fabricgodam.transferFabric',compact('fabricstocks','fromgodams','togodams','fabricgodam_id','find_data','groups'));
    }

    public function transferFabricDetail($fabricgodam_id)
    {
        $find_data = FabricGodam::find($fabricgodam_id);
        $fabricdetails = FabricGodamTransfer::get();
        return view('admin.fabric.fabricgodam.transferFabricDetail',compact('fabricdetails','find_data'));
    }

    public function getFabricStockList(Request $request)
    {
        // dd($request);
        $fabricstock_id = $request->fabricstock_id;
        $group_id = $request->group_id;
        $fromgodam_id = $request->fromgodam_id;
        $togodam_id = $request->togodam_id;
        $bill_no = $request->bill_no;
        $bill_date = $request->bill_date;

        $find_name = FabricStock::find($fabricstock_id);
        // dd($find_name);
        $fabricstocks = FabricStock::where('status',1);

        // dd($find_name->name,$request->group_id);

        if($fabricstock_id != null){
            // dd($find_name);
           $fabricstocks = $fabricstocks->where('name',$find_name->name);
        }
        // dd($request);

        // if($group_id != null){
        //     // dd('kk',$group_id);
        //     $fabricstocks = $fabricstocks->where('fabricgroup_id',$group_id);
        //     // dd($group_id,$fabricstocks->get());
        // }

        $fabricstocks = $fabricstocks->with('fabricgroup')->get();

        // dd($fabricstocks);

        return response(['fabricstocks'=>$fabricstocks,
                         'fromgodam_id'=>$fromgodam_id,
                         'togodam_id'=>$togodam_id,
                         'bill_no'=>$bill_no,
                         'bill_date'=>$bill_date,
                           ]);

    }

    public function getFabricGodamStore(Request $request)
    {
        //validate form
        // $validator = $request->validate([
        //     'bill_number' => 'required',
        // ]);

        // dd($request);
        $find_name = FabricStock::find($request->ids);
        // dd($find_name);

        try{
            $count = FabricGodamTransfer::where('fabricstock_id',$request->ids)
                                        ->where('fabricgodam_id',$request->fabricgodam_id)
                                        ->where('bill_no',$request->bill_no)
                                        ->where('bill_date',$request->bill_date)
                                        ->where('fromgodam_id',$request->fromgodam_id)
                                        ->where('togodam_id',$request->togodam_id)
                                        ->count();

            // dd($count);
            if($count != 1){
                // store category
                $fabricgodam = FabricGodamTransfer::create([
                    'fabricstock_id' => $request->ids,
                    'name' => $find_name->name,
                    'slug' => $find_name->slug,
                    'fabricgodam_id' => $request->fabricgodam_id,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $request->bill_date,
                    'fromgodam_id' => $request->fromgodam_id,
                    'togodam_id' => $request->togodam_id,
                ]);

                // $fabricstock = FabricStock::create([
                //     'name' => $find_name->name,
                //     'roll_no' => $find_name->roll_no,
                //     'loom_no' => $find_name->loom_no,
                //     'fabricgroup_id' => $find_name->fabricgroup_id,
                //     'gross_wt' => $find_name->gross_wt,
                //     'net_wt' => $find_name->net_wt,
                //     'meter' => $find_name->meter,
                //     'gram_wt' => $find_name->gram_wt,
                //     'average_wt' => $find_name->average_wt,
                //     'godam_id' => $request->togodam_id,
                //     'date_np' => $find_name->date_np,
                //     'bill_no' => $find_name->bill_no,
                //     'fabric_id' => $find_name->fabric_id
                // ]);

                // $find_name->delete();

            }                            
      


        // return back();
        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
        }
    }

    public function dataTable()
    {
        // dd('lol');
        $rawMaterial = FabricGodam::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($rawMaterial)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                // if($row->status=="complete"){
                //     return '<span class="badge badge-success">COMPLETED</span>';
                // }
                $actionBtn = '
                <a class="btn btn-sm btn-primary btnPlus" href="' . route('fabricgodams.transferFabric', ["fabricgodam_id" => $row->id]) . '" >
                <i class="fas fa-plus fa-lg"></i>
                </a>

                <a class="btn btn-sm btn-primary btnView" href="' . route('fabricgodams.transferFabricDetail', ["fabricgodam_id" => $row->id]) . '" >
                <i class="fas fa-eye fa-lg"></i>
                </a>

               
                ';


                return $actionBtn;

            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
