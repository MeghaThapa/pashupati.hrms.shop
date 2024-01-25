<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use App\Models\RawMaterialSalesEntry;
use App\Models\RawMaterialStock;
use App\Models\DanaGroup;
use App\Models\Godam;

use App\Models\RawMaterialItemsSale;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class RawMaterialSalesEntryController extends Controller
{
    public function index(){

        return view('admin.rawMaterialSales.index');
    }

    public function create(){
        $suppliers=Supplier::where('status', '1')->get();
        $godams = Godam::where('status','active')->get();
        $nepaliDate = getNepaliDate(date('Y-m-d'));
        return view('admin.rawMaterialSales.create',compact('suppliers','godams','nepaliDate'));
    }

    public function store(Request $request){
        $rawMaterialSalesEntry = new RawMaterialSalesEntry();
        $rawMaterialSalesEntry->bill_date = $request->bill_date;
        $rawMaterialSalesEntry->bill_no =$request->bill_no;
        $rawMaterialSalesEntry->supplier_id = $request->supplier_id;
        $rawMaterialSalesEntry->godam_id = $request->godam_id;
        $rawMaterialSalesEntry->challan_no = $request->challan_no;
        $rawMaterialSalesEntry->do_no = $request->do_no;
        $rawMaterialSalesEntry->gp_no = $request->gp_no;
        $rawMaterialSalesEntry->through = $request->through;
        $rawMaterialSalesEntry->sale_for = $request->sale_for;
        $rawMaterialSalesEntry->save();
        return redirect()->route('rawMaterialItemsSalesEntry.create',['id'=>$rawMaterialSalesEntry->id]) ;
    }

    public function edit($id){
        $rawMaterialSalesEntry=RawMaterialSalesEntry::with(['godam:id,name','supplier:id,name'])->find($id);
         $danaGroups = DB::table('raw_material_stocks')
        ->join('dana_groups','dana_groups.id','=','raw_material_stocks.dana_group_id')
        ->where('godam_id',$rawMaterialSalesEntry->godam_id)
        ->select('dana_groups.id','dana_groups.name')
        ->distinct('dana_groups.name','dana_groups.id')
        ->get();
        return view('admin.rawMaterialSales.createItems',compact('rawMaterialSalesEntry','danaGroups'));
    }

    public function saveEntire(Request $request){
         $rawMaterialSalesEntry=RawMaterialSalesEntry::find($request->rawmaterial_sales_entry_id);
        $rawMaterialSalesEntry->status="completed";
        $rawMaterialSalesEntry->save();
    }
    public function yajraDatatables(){
        $rawMaterialSalesEntryData =RawMaterialSalesEntry::with(['godam:id,name','supplier:id,name'])->get();
         return DataTables::of($rawMaterialSalesEntryData)
            ->addIndexColumn()
            ->addColumn('statusBtn', function ($rawMaterialSalesEntryData) {
                return '<span class="badge badge-pill badge-success">'.$rawMaterialSalesEntryData->status.'</span>';
            })
            ->addColumn('action', function ($rawMaterialSalesEntryData) {
                $actionBtn='';
                if($rawMaterialSalesEntryData->status == 'running'){
                    $actionBtn .= '
                <a class="btnEdit" href="' . route('rawMaterialSalesEntry.edit', ['id' => $rawMaterialSalesEntryData->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>

                <button class="btn btn-danger" id="dltBagSellingEntry" data-id="'.$rawMaterialSalesEntryData->id.'">
                <i class="fas fa-trash-alt"></i>
            </button>

                ';
                }
                else{
                    $actionBtn .= '
                <button class="btn btn-info" id="dltBagSellingEntry" data-id="'.$rawMaterialSalesEntryData->id.'">
                    <i class="fas fa-eye"></i>
                </button>
                '
                ;
                }


                return $actionBtn;
            })
            ->rawColumns(['action','statusBtn'])
            ->make(true);
    }

}
