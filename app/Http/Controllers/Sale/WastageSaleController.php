<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\Supplier;
use App\Models\WastageSale;
use App\Models\WasteStock;
use App\Models\WastageSaleEntry;
use App\Models\WastageSaleEntryList;
use App\Models\NonWovenFabric;
use App\Models\FabricNonWovenReceiveEntryStock;
use App\Models\Godam;
use App\Helpers\AppHelper;
use Throwable;
use Yajra\DataTables\DataTables;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class WastageSaleController extends Controller
{
    public function index()
    {
        $partyname = Supplier::where('status',1)->get();
        return view('admin.sale.wastagesale.index',compact('partyname'));
    }

    public function dataTable()
    {
        $data = WastageSale::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('supplier', function ($row) {

                return $row->getParty->name;

            })
            ->addColumn('action', function ($row) {

                if($row->status == "sent"){

                    return '<a href="' . route('wastageSale.add', ['id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }else{

                    return '<a href="' . route('wastageSale.viewBill', ['bill_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

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
      
            $WastageSale = WastageSale::create([
                'bill_no' => $request['bill_number'],
                'bill_date' => $request['bill_date'],
                'partyname_id' => $request['partyname'],
                'lorry_no' => $request['lory_number'],
                'do_no' => $request['dp_number'],
                'gp_no' => $request['gp_number'],
                'status' => 'sent',
            ]);

        return redirect()->back()->withSuccess('WsatageSale created successfully!');

    }

    public function add($id)
    {
        $find_data = WastageSale::find($id);
        $godam_id = Godam::where('name','psi')->value('id');
        $wastagestocks = WasteStock::where('godam_id',$godam_id)->get();
       
        return view('admin.sale.wastagesale.add',compact('find_data','id','wastagestocks'));
    }

    public function storeEntry(Request $request)
    {
        // $validator = $request->validate([
        //     'name' => 'required|string|max:60',
        //     'gsm' => 'required|numeric',
        //     'color' => 'required',
        // ]);
        // dd($request);
        if($request->quantity > $request->available_quantity){

            return response(['message'=>'Quantity is greater']);

        }else{

            $WastageSale = WastageSaleEntry::create([
                'bill_id' => $request['bill_id'],
                'waste_id' => $request['wastage'],
                'quantity' => $request['quantity'],
                'status' => 'sent',
            ]);

            return response(['message'=>'Entry done successfully']);

        }
    

    }

    public function getWastageList(Request $request){
        if($request->ajax()){
            
            $datas = WastageSaleEntry::where('bill_id',$request->bill_id)->get();


            return DataTables::of($datas)
                    ->addIndexColumn()
                    ->addColumn('waste', function ($row) {
                        return $row->getWaste->wastage->name;
                    })
                   
                    ->addColumn("action",function($row,Request $request){
                        return "
                        <a class='btn btn-danger deleteEntry'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Delete</a>";
                    })
                    ->rawColumns(["wastage","action"])
                    ->make(true);

           
        }
    }

    public function storeFinalEntry(Request $request)
    {

        try{
            DB::beginTransaction();
            // dd($request);
            $getlist = WastageSaleEntry::where('bill_id',$request->bill_id)->where('status','sent')->get();

            foreach ($getlist as $list) {

                $data = WasteStock::where('id',$list->waste_id)->value('quantity_in_kg');
                $find_data = WasteStock::find($list->waste_id);
                $quantity = $find_data->quantity_in_kg;
                $final = $quantity - $list->quantity;
                $find_data->quantity_in_kg = $final; 
                $find_data->update();

                $finalEntry = WastageSaleEntryList::create([
                    'bill_id' => $list->bill_id,
                    'waste_id' => $list->waste_id,
                    'quantity' => $list->quantity,
                    'status' => 'completed',
                ]);

            }

            $getupdate = WastageSaleEntry::where('bill_id',$request->bill_id)->update(['status' => 'completed']); 
            $getupdates = WastageSale::where('id',$request->bill_id)->update(['status' => 'completed']); 

            DB::commit();

        return response(['message'=>'Godam Transferred Successfully']);
        }
        catch (Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

 

    public function viewBill($bill_id)
    {
        $find_data = WastageSale::find($bill_id);
        
        $nonwovenlist = WastageSaleEntry::where('bill_id',$bill_id)->get();
        return view('admin.sale.wastagesale.viewbill',compact('find_data','nonwovenlist'));
    }

    public function getWastageQuantity(Request $request)
    {
        $find_data = WasteStock::find($request->wastage);
        // dd($find_data);
        return response([
            "quantity" => $find_data->quantity_in_kg 
        ]);
        
        // $nonwovenlist = WastageSaleEntry::where('bill_id',$bill_id)->get();
        return view('admin.sale.wastagesale.viewbill',compact('find_data','nonwovenlist'));
    }


    public function deleteEntryList(Request $request)
    {

        $unit = WastageSaleEntry::find($request->data_id);

        $unit->delete();
        return response([
            "message" => "Deleted Successfully" 
        ]);

    }

   
}
