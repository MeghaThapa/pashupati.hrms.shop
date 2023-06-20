<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Fabric;
use App\Models\FabricTransferEntryForBag;
use App\Models\Godam;
use Illuminate\Http\Request;

class FabricTransferEntryForBagController extends Controller
{
  /********** For Receipts ***********/
    public function index(){
        $data = FabricTransferEntryForBag::orderBy('id',"DESC")->paginate(20);
        return view('admin.bag.fabric transfer for bag.index',compact('data'));
    }
    
    public function create(){
        $id = FabricTransferEntryForBag::latest()->value('id');
        $bill_no = "FTB"."-".getNepaliDate(date('Y-m-d'))."-".$id+1;
        $date_np = getNepaliDate(date('Y-m-d'));
        return view("admin.bag.fabric transfer for bag.create",compact("bill_no","date_np"));
    }
    public function store(Request $request){
      $request->validate([
        "receipt_number" =>  "required|unique:bag_fabric_entry",
        "receipt_date" => "required",
        "date_np" => "required"
      ]);
      FabricTransferEntryForBag::create([
        "receipt_number" => $request->receipt_number,
        "receipt_date" => $request->receipt_date,
        "receipt_date_np" => $request->date_np
      ]);
      return back()->with(["message"=>"Creation successful"]);
    }
    /***********  For Receipts end ************/


    /****** For Transfer *********/
    public function fabrictransferindex($id){
      $godam = Godam::where("status","active")->get();
      $data = FabricTransferEntryForBag::where('id',$id)->first();
      return view("admin.bag.transfer to bag.index",compact("data","godam"));
    }
    
    public function getfabricsaccordinggodams(Request $request,$id){
      if($request->ajax()){
          $data = Fabric::where("godam_id",$id)->orderBy('name',"asc")->get();
          return $data;
      }
    }

    public function getspecificfabricdetails(Request $request,$id){
      $name = Fabric::where('id',$id)->first()->name;
      $allfabswithsamenames = Fabric::where("name",$name)->get();
      return response([
          "data" => $allfabswithsamenames
      ]);
    }

}
