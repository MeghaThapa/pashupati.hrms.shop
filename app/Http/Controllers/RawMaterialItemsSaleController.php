<?php

namespace App\Http\Controllers;
use App\Models\RawMaterialSalesEntry;
use App\Models\RawMaterialStock;
use App\Models\DanaGroup;
use App\Models\RawMaterialItemsSale;
use Illuminate\Http\Request;
use DB;

class RawMaterialItemsSaleController extends Controller
{
    public function create( $id){
        $danaGroups = DB::table('raw_material_stocks')
        ->join('dana_groups','dana_groups.id','=','raw_material_stocks.dana_group_id')
        ->select('dana_groups.id','dana_groups.name')
        ->distinct('dana_groups.name','dana_groups.id')
        ->get();
        // $danaGroups=  DanaGroup::where('status', 'active')->get();
        $rawMaterialSalesEntry=RawMaterialSalesEntry::with(['godam:id,name','supplier:id,name'])->find($id);
        return view('admin.rawMaterialSales.createItems',compact('rawMaterialSalesEntry','danaGroups'));
    }
    public function getDanaGroupDanaName(Request $request){
       $danaNames = DB::table('raw_material_stocks')
        ->join('dana_names','dana_names.id','=','raw_material_stocks.dana_name_id')
        ->where('raw_material_stocks.godam_id',$request->godam_id)
        ->where('raw_material_stocks.dana_group_id',$request->dana_group_id)
        ->select('dana_names.id','dana_names.name')
        ->distinct('dana_names.name','dana_names.id')
        ->get();
       return $danaNames;
    }
    public function getDanaStockQty(Request $request){
        $stockQty = RawMaterialStock::where('godam_id',$request->godam_id)
        ->where('dana_group_id',$request->dana_group_id)
        ->where('dana_name_id',$request->danaName_id)
        ->first();
        return $stockQty;
    }
    public function store(Request $request){
        try{
            DB::beginTransaction();
        $request->validate([
            'rawMaterialSalesEntry_id'=>'required',
            'lorry_no'=>'required',
            'dana_group_id'=>'required',
            'dana_name_id'=>'required',
            'quantity_in_kg'=>'required',
        ]);
         $rawMaterialItemsSale=RawMaterialItemsSale::where('raw_material_sales_entry_id',$request->rawMaterialSalesEntry_id)
         ->where('dana_group_id',$request->dana_group_id)
         ->where('dana_name_id',$request->dana_name_id)
         ->first();
         if($rawMaterialItemsSale){
            $rawMaterialItemsSale->qty_in_kg += $request->quantity_in_kg;
             $rawMaterialItemsSale->save();
         }else
         {
        $rawMaterialItemsSale=new RawMaterialItemsSale();
        $rawMaterialItemsSale->lorry_no = $request->lorry_no;
        $rawMaterialItemsSale->raw_material_sales_entry_id =$request->rawMaterialSalesEntry_id;
        $rawMaterialItemsSale->dana_group_id =$request->dana_group_id;
        $rawMaterialItemsSale->dana_name_id =$request->dana_name_id;
        $rawMaterialItemsSale->qty_in_kg =$request->quantity_in_kg;
        $rawMaterialItemsSale->save();
        }
        $RawMaterialSalesEntry= RawMaterialSalesEntry::find($request->rawMaterialSalesEntry_id);
        $rawMaterialStock=RawMaterialStock::where('godam_id',$RawMaterialSalesEntry->godam_id)
        ->where('dana_group_id',$request->dana_group_id)
        ->where('dana_name_id',$request->dana_name_id)
        ->first();
        if($rawMaterialStock->quantity -$request->qty_in_kg < 0){
             return('hy');
            return redirect()->back()->with('error', 'Insufficient stock available');
        }else if($rawMaterialStock->quantity ==0){
            return('hello');
            $rawMaterialStock->delete();
        }
        else{
            // return('i am here');
            $rawMaterialStock->quantity -= $request->quantity_in_kg;
            $rawMaterialStock->save();
            // return $rawMaterialStock->quantity;
        }
        DB::commit();
            return back()->with('success', 'Stock sold successfully');
        }catch(Exception $ex){
        DB::rollback();
        }
    }
    public function delete($salesItem_id){
        try{
            DB::beginTransaction();
        $rawMaterialItemsSale =RawMaterialItemsSale::find($salesItem_id);
        $rawMaterialItemsSale->delete();
        $rawMaterialSalesEntry= RawMaterialSalesEntry::find($rawMaterialItemsSale->raw_material_sales_entry_id);
        // to restore to rawmaterial stock
        $rawMaterialStock =RawMaterialStock::where('godam_id',$rawMaterialSalesEntry->godam_id)
        ->where('dana_group_id',$rawMaterialItemsSale->dana_group_id)
        ->where('dana_name_id',$rawMaterialItemsSale->dana_name_id)
        ->first();
        if($rawMaterialStock ){
           $rawMaterialStock->quantity += $rawMaterialItemsSale->qty_in_kg;
           $rawMaterialStock->save();
        }else{
            $rawMaterialStock= new RawMaterialStock();
            $rawMaterialStock->godam_id =$rawMaterialSalesEntry->godam_id;
            $rawMaterialStock->dana_group_id = $rawMaterialItemsSale->dana_group_id;
            $rawMaterialStock->dana_name_id  = $rawMaterialItemsSale->dana_name_id;
            $rawMaterialStock->quantity = $rawMaterialItemsSale->qty_in_kg;
            $rawMaterialStock->save();
        }
        DB::commit();
        }catch(Exception $ex){
            DB::rollback();
        }

    }
    public function getSalesData(Request $request){
        $rawMaterialSaleItems= RawMaterialItemsSale::with(['danaGroup:id,name','danaName:id,name'])
        ->where('raw_material_sales_entry_id',$request->rawmaterial_sales_entry_id)
        ->get();
        return $rawMaterialSaleItems;
    }
}

