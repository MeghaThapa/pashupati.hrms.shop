<?php

namespace App\Http\Controllers;

use App\Models\PrintedAndCuttedRolls;
use App\Models\PrintedAndCuttedRollsEntry;
use App\Models\BagFabricReceiveItemSentStock;
use App\Models\Group;
use Illuminate\Http\Request;
use DB;
class PrintedAndCuttedRollsController extends Controller
{
    /*************** For Entry *********/
    public function index(){
        $data = PrintedAndCuttedRollsEntry::all();
        return view("admin.bag.printsandcuts.index",compact("data"));
    }
    public function createEntry(){
         $id = PrintedAndCuttedRollsEntry::latest()->value('id');
        $nepaliDate = getNepaliDate(date('Y-m-d'));
        $date = date('Y-m-d');
        $receipt_no = "PCR" . "-" . getNepaliDate(date('Y-m-d')) . "-" . $id + 1;
        return view("admin.bag.printsandcuts.createEntry",compact('nepaliDate','date','receipt_no'));
    }

    public function storeEntry(Request $request){
       $request->validate([
        "receipt_number" => "required",
        "date" => "required",
        "date_np" => "required"
       ]);

       PrintedAndCuttedRollsEntry::create([
            "receipt_number" => $request->receipt_number,
            "date" => $request->date,
            "date_np" => $request->date_np
       ]);

       return $this->index();
    }
    /*************** For Entry *********/
    public function createPrintedRolls($id){
     $data = PrintedAndCuttedRollsEntry::where('id',$id)->get();
     $fabrics = DB::table('bag_fabric_receive_item_sent_stock as stock')
     ->join('fabrics', 'fabrics.id', '=', 'stock.fabric_id')
     ->select('fabrics.id','fabrics.name')
     ->get();
     $groups=Group::where('status','active')->get();
   //  return $groups;
     return view("admin.bag.printsandcuts.create")->with([
            "data" => $data,
            "fabrics"=>$fabrics,
            "groups"=>$groups
     ]);
    }

    public function getNetWeightGrossWeight($fabric_id){
        $netWeightGrossWeight=BagFabricReceiveItemSentStock::where('fabric_id',$fabric_id)->first(['gross_wt','net_wt']);
        return $netWeightGrossWeight;
    }
}
