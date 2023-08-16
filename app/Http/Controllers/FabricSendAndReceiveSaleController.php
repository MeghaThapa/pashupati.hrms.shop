<?php

namespace App\Http\Controllers;

use App\Models\FabricSale;
use App\Models\FabricSaleEntry;
use App\Models\FabricSaleItems;
use App\Models\FabricStock;
use App\Models\Supplier;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Exceptions\Exception;

class FabricSendAndReceiveSaleController extends Controller
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function index(){
       
        $fabrics = FabricStock::get()->unique('name')->values()->all();
        $partyname = Supplier::where('status',1)->get();
        return view('admin.sale.fabricsale.index',compact('fabrics','partyname'));
    }

    public function indexajax(){
        if($this->request->ajax()){
            return DataTables::of(FabricSaleEntry::with("getParty")->get())
                        ->addIndexColumn()
                        ->addColumn("supplier",function($row){
                            return $row->getParty->name;
                        })
                        ->addColumn("action",function($row){
                            if($row->status == "pending"){
                                return "
                                    <div class='btn-group'>
                                        <a href='javascripy:void(0)' data-id={$row->id} class='btn btn-primary create-sale'><i class='fa fa-plus' aria-hidden='true'></i></a>
                                    </div>
                                ";
                            }else{
                                return "
                                    <div class='btn-group'>
                                        <a href='javascripy:void(0)' data-id={$row->id} class='btn btn-secondary view-sale'><i class='fa fa-eye' aria-hidden='true'></i></a>
                                    </div>
                                ";
                            }
                        })
                        ->rawColumns(["supplier","action"])
                        ->make(true);
        }
    }
    public function store(){
        $this->request->validate([
            'bill_number' => "required",
            'bill_date' => "required",
            'partyname' => "required",
            'bill_for' => "required",
            'lory_number' => "required",
            'dp_number' => "required",
            'gp_number' => "required"
        ]);
        $fabric = FabricSaleEntry::create([
            'bill_no' => $this->request['bill_number'],
            'bill_date' => $this->request['bill_date'],
            'partyname_id' => $this->request['partyname'],
            'bill_for' => $this->request['bill_for'],
            'lorry_no' => $this->request['lory_number'],
            'do_no' => $this->request['dp_number'],
            'gp_no' => $this->request['gp_number'],
            'remarks' => $this->request['remarks'],
        ]);

    return redirect()->back()->withSuccess('SaleFinalTripal created successfully!');
    }

    public function create($entry_id){
        try{
            $fabricsaleentry = FabricSaleEntry::where("id",$entry_id)->firstorFail();
            $fabrics = FabricStock::with("fabric")->get()->unique('name');
            return view("admin.sale.fabricsale.create")->with([
                "fabricsaleentry" => $fabricsaleentry,
                "fabrics" => $fabrics,
                "entry_id" => $entry_id
            ]);
        }catch(Exception $e){
            abort(403);
        }  
    }

    public function getidenticalfabricdetails(){
        if($this->request->ajax()){
            $fabric_id = $this->request->fabric_name_id;
            $name = FabricStock::where("id",$fabric_id)->value('name');
            return DataTables::of(FabricStock::where("name",$name)->get())
                        ->addIndexColumn()
                        ->addColumn("action",function($row){
                            return "<a href='javascript:void(0)' class='btn btn-primary send-to-lower' data-id='{$row->id}'>Send </a>'";
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
    }
    public function storeSale(){
        if($this->request->ajax()){
            // return $this->request->all();
            $fabric = FabricStock::where("id",$this->request->fabric_id)->first();
            FabricSale::create([
                "sale_entry_id" => $this->request->entry_id,
                "fabric_id" => $fabric->fabric_id
            ]);
        }
    }

    public function getSales(){
        if($this->request->ajax()){
            return DataTables::of(FabricSale::where("sale_entry_id",$this->request->entry_id)->with(["getsaleentry","getfabric"])->get())
                    ->addIndexColumn()
                    ->addColumn("fabric_name",function($row){
                        return $row->getfabric->name;
                    })
                    ->addColumn("gross_wt",function($row){
                        return $row->getfabric->gross_wt;
                    })
                    ->addColumn("meter",function($row){
                        return $row->getfabric->meter;
                    })
                    ->addColumn("net_wt",function($row){
                        return $row->getfabric->net_wt;
                    })
                    ->addColumn("gram_wt",function($row){
                        return $row->getfabric->gram_wt;
                    })
                    ->addColumn("roll",function($row){
                        return $row->getfabric->roll_no;
                    })
                    ->addColumn("average_wt",function($row){
                        return $row->getfabric->average_wt;
                    })
                    ->addColumn("action",function($row){
                        return "<button class='btn btn-danger delete-sale' data-id='{$row->id}'> <i class='fa fa-trash' aria-hidden='true'></i> </button>";
                    })
                    ->rawColumns(["action"])
                    ->make(true);
        }
    }

    public function indexsumsajax(){
        if($this->request->ajax()){
            $fabrics = DB::table("fabric_sales")->where("sale_entry_id",$this->request->entry_id)
                                            ->leftJoin("fabrics","fabrics.id", "=","fabric_sales.fabric_id")
                                            ->get();
            $sumnetwt = $fabrics->sum("net_wt");
            $sumgrosswt = $fabrics->sum("gross_wt");
            $summeter = $fabrics->sum("meter");
            return response([
                "net_wt" => $sumnetwt,
                 "gross_wt" => $sumgrosswt,
                 "meter" => $summeter
            ]);
        }
    }

    public function delete(){
        if($this->request->ajax()){
            try{
                $fabric_sale = FabricSale::findorfail($this->request->id);
                $fabric_sale->delete();
                return response([
                    "message" =>  "Deletion Completes",
                    "status" => 200
                ]);
            }catch(Exception $e){
                return response([
                    "message_err" => $e->getMessage()
                ]);
            }
        }
    }

    public function submit(){
        if($this->request->ajax()){
            try{
                DB::beginTransaction();                
                foreach(FabricSale::where("sale_entry_id",$this->request->id)->get() as $data){
                    $sale_fabric_id = $data->fabric_id;
                    
                    FabricSaleItems::create([
                        "fabric_id" => $data->fabric_id,
                        "sale_entry_id" => $data->sale_entry_id
                    ]);
    
                    FabricStock::where("fabric_id",$sale_fabric_id)->delete();
                }
    
                FabricSale::where("sale_entry_id",$this->request->id)->delete();
                FabricSaleEntry::where("id",$this->request->id)->update([
                    "status" => "completed"
                ]);
                DB::commit();
                return response([
                    "status" => 200,
                    "message" => "saved successfully"
                ]);
            }catch(Exception $e){
                DB::rollBack();
            }
        }
    }
}