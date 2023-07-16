<?php

namespace App\Http\Controllers;

use App\Models\PrintedAndCuttedRolls;
use App\Models\PrintedAndCuttedRollsEntry;
use App\Models\BagFabricReceiveItemSentStock;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\Wastages;
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
        $data = PrintedAndCuttedRollsEntry::where('id',$id)->first();
        // return $data;
        $fabrics = DB::table('bag_fabric_receive_item_sent_stock as stock')
        ->join('fabrics', 'fabrics.id', '=', 'stock.fabric_id')
        ->select('fabrics.id','fabrics.name')
        ->get();
        $groups=Group::where('status','active')->get();
         $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();

        $wasteGodams=Godam::where('status','active')->get();
        $wastageTypes=Wastages::where('status','active')->get();
        return view("admin.bag.printsandcuts.create")->with([
                "data" => $data,
                "fabrics"=>$fabrics,
                "groups"=>$groups,
                "godams"=>$godams,
                "wasteGodams"=>$wasteGodams,
                "wastageTypes"=>$wastageTypes,
        ]);
    }
    public function getFabric(Request $request){
      return  BagFabricReceiveItemSentStock::where('roll_no',$request->roll_no)
       ->with(['fabric'=>function ($query){
            $query->select('id','name');
       }])
       ->select('fabric_id as fabric_id','net_wt','gross_wt','average')
       ->first();
    }
    public function getDanaGroup(Request $request){
        $danaGroups=AutoLoadItemStock::with(['danaGroup'=>function ($query){
                $query->select('id','name');
            }])
            ->select('dana_group_id')
            ->where('from_godam_id',$request->godam_id)
            ->distinct()
            ->get();

            return response()->json([
                'danaGroups'=>$danaGroups
            ]);
        }
        public function getDanaName(Request $request){
        $danaNames=AutoLoadItemStock::with(['danaName'=>function ($query){
                $query->select('id','name');
            }])
            ->select('dana_name_id')
            //->where('dana_group_id',$request->dana_group_id)
            ->where('from_godam_id',$request->godam_id)
            ->distinct()
            ->get();
        return $danaNames;
        }
    public function getStockQuantity(Request $request){
         $stockQty=AutoLoadItemStock::
            select('quantity')
            ->where('from_godam_id',$request->godam_id)
            ->where('dana_name_id',$request->dana_name_id)
            ->first();
        return $stockQty;
    }

}
