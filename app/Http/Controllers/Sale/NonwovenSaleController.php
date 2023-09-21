<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\Supplier;
use App\Models\NonwovenSale;
use App\Models\NonwovenSaleEntry;
use App\Models\NonwovenSaleEntryList;
use App\Models\NonWovenFabric;
use App\Models\FabricNonWovenReceiveEntryStock;
use App\Models\Godam;
use App\Helpers\AppHelper;
use Throwable;
use Yajra\DataTables\DataTables;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class NonwovenSaleController extends Controller
{
    public function index()
    {
        $partyname = Supplier::where('status',1)->get();
        return view('admin.sale.nonwovensale.index',compact('partyname'));
    }

    public function dataTable()
    {
        $data = NonwovenSale::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('supplier', function ($row) {

                return $row->getParty->name;

            })
            ->addColumn('action', function ($row) {

                if($row->status == "sent"){

                    return '<a href="' . route('nonwovenSale.add', ['id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }else{

                    return '<a href="' . route('nonwovenSale.viewBill', ['bill_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }

                return $actionBtn;

            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request)
    {
        // $validator = $request->validate([
        //     'name' => 'required|string|max:60',
        //     'gsm' => 'required|numeric',
        //     'color' => 'required',
        // ]);
        // dd($request);
      
            $nonwovenSale = NonwovenSale::create([
                'bill_no' => $request['bill_number'],
                'bill_date' => $request['bill_date'],
                'partyname_id' => $request['partyname'],
                'bill_for' => $request['bill_for'],
                'lorry_no' => $request['lory_number'],
                'do_no' => $request['dp_number'],
                'gp_no' => $request['gp_number'],
                'remarks' => $request['remarks'],
                'status' => 'sent',
            ]);

        return redirect()->back()->withSuccess('SaleFinalTripal created successfully!');

    }

    public function add($id)
    {
        $find_data = NonwovenSale::find($id);
        $godam_id = Godam::where('name','psi')->value('id');
        $nonwovenfabrics = NonWovenFabric::distinct()->get(['gsm']);
        $total_net = NonwovenSaleEntry::where('bill_id',$id)->sum('net_weight');
        $total_gross = NonwovenSaleEntry::where('bill_id',$id)->sum('gross_weight');
        $total_length = NonwovenSaleEntry::where('bill_id',$id)->sum('length');
        $total_roll = NonwovenSaleEntry::where('bill_id',$id)->count();
       
        return view('admin.sale.nonwovensale.add',compact('find_data','id','nonwovenfabrics','total_net','total_gross','total_length','total_roll'));
    }

    public function getSaleNonwovenList(Request $request){
        // dd($request);

        if($request->ajax()){
            $nonwovensale_id = $request->nonwovensale_id;
            $fabrics = NonwovenSaleEntry::where("bill_id",$request->nonwovensale_id)->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                   
                    ->addColumn("action",function($row){
                        return "
                        <a class='btn btn-danger deleteTripalEntry'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Delete</a>";
                    })
                    ->rawColumns(["action","gram_wt"])
                    ->make(true);

        }
   
    }

    public function viewBill($bill_id)
    {
        $find_data = NonwovenSale::find($bill_id);
        
        $nonwovenlist = NonwovenSaleEntry::where('bill_id',$bill_id)->get();
        return view('admin.sale.nonwovensale.viewbill',compact('find_data','nonwovenlist'));
    }

    public function deleteEntryList(Request $request)
    {

        $unit = NonwovenSaleEntry::find($request->data_id);
        
        $value = FabricNonWovenReceiveEntryStock::find($unit->stock_id);
        $value->status_type = 'active';
        $value->update();

        $unit->delete();

        return response([
            "message" => "Deleted Successfully" 
        ]);

    }

    public function storeEntryList(Request $request)
    {
        try{
            $find_name = FabricNonWovenReceiveEntryStock::find($request->data_id);

                $nonwovensale = NonwovenSaleEntry::create([
                    'fabric_name' => $find_name->fabric_name,
                    'fabric_roll' => $find_name->fabric_roll,
                    'fabric_gsm' => $find_name->fabric_gsm,
                    'fabric_color' => $find_name->fabric_color,
                    'length' => $find_name->length,
                    'gross_weight' => $find_name->gross_weight,
                    'net_weight' => $find_name->net_weight,
                    'bill_id' => $request->nonwovensale_id,
                    'godam_id' => $find_name->godam_id,
                    'stock_id' => $request->data_id,
                    'status' => 'sent',
                ]);
            $find_name->status_type = 'inactive';
            $find_name->update();    

        return response(['message'=>'sale Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
            dd($ex);
        }
    

    }

    public function getSaleTripalList(Request $request){
        // dd($request);

        if($request->ajax()){
            $sale_id = $request->sale_id;
            $fabrics = SaleFinalTripalEntry::where("salefinal_id",$sale_id)->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                   
                    ->addColumn("action",function($row){
                        return "
                        <a class='btn btn-danger deleteTripalEntry'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Delete</a>";
                    })
                    ->rawColumns(["action","gram_wt"])
                    ->make(true);

        }
      
    }

    public function finalNonwovenStoreList(Request $request)
    {
        try{
            // dd($request);
            $getlist = NonwovenSaleEntry::where('bill_id',$request->nonwovensale_id)->where('status','sent')->get();
            // dd($getlist);

            foreach ($getlist as $list) {

                $find_name = NonwovenSaleEntry::find($list->id);
                // dd($find_name);
                $stock = FabricNonWovenReceiveEntryStock::find($find_name->stock_id);
                // dd($stock);
                    $fabricstock = NonwovenSaleEntryList::create([
                        'receive_date' => $stock->receive_date,
                        'receive_no' => $stock->receive_no,
                        'fabric_name' => $find_name->fabric_name,
                        'fabric_roll' => $find_name->fabric_roll,
                        'fabric_gsm' => $find_name->fabric_gsm,
                        'fabric_color' => $find_name->fabric_color,
                        'length' => $find_name->length,
                        'gross_weight' => $find_name->gross_weight,
                        'net_weight' => $find_name->net_weight,
                        'bill_id' => $request->nonwovensale_id,
                        'godam_id' => $find_name->godam_id,
                    ]);

                    if($fabricstock){

                      FabricNonWovenReceiveEntryStock::where('id',$find_name->stock_id)->delete();
                    }

                    $getlist = NonwovenSaleEntry::where('bill_id',$request->nonwovensale_id)->update(['status' => 'completed']);

            }

            $getlist = NonwovenSaleEntryList::where('bill_id',$request->nonwovensale_id)->update(['status' => 'completed']);

            $getdatalist = NonwovenSale::where('id',$request->nonwovensale_id)->update(['status' => 'completed']);

        return response(['message'=>'sale Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
        }
    

    }
}
