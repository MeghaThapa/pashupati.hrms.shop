<?php

namespace App\Http\Controllers;

use App\Models\BagFabricReceiveItemSent;
use App\Models\BagFabricReceiveItemSentStock;
use App\Models\BagTemporaryFabricReceive;
use App\Models\Category;
use App\Models\Fabric;
use App\Models\FabricStock;
use App\Models\FabricTransferEntryForBag;
use App\Models\Godam;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Symfony\Component\Mime\Encoder\Rfc2231Encoder;

class FabricTransferEntryForBagController extends Controller
{
  /********** For Receipts ***********/
  public function index()
  {
    $data = FabricTransferEntryForBag::orderBy('id', "DESC")->paginate(20);
    return view('admin.bag.fabricTransferForBag.index', compact('data'));
  }

  public function create()
  {
    $id = FabricTransferEntryForBag::latest()->value('id');
    $bill_no = "FTB" . "-" . getNepaliDate(date('Y-m-d')) . "-" . $id + 1;
    $date_np = getNepaliDate(date('Y-m-d'));
    return view("admin.bag.fabricTransferForBag.create", compact("bill_no", "date_np"));
  }
  public function store(Request $request)
  {
    $request->validate([
      "receipt_number" => "required|unique:bag_fabric_entry",
      "receipt_date" => "required",
      "date_np" => "required"
    ]);
    FabricTransferEntryForBag::create([
      "receipt_number" => $request->receipt_number,
      "receipt_date" => $request->receipt_date,
      "receipt_date_np" => $request->date_np
    ]);
    Session::flash('success', 'Creation successful');
    return back();
    // ->with(["message" => "Creation successful"]);
  }
  /***********  For Receipts end ************/

  /********* For Revewing what was sent --report *********/
  public function viewSentItem($id){
    return BagFabricReceiveItemSent::where('fabric_bag_entry_id',$id)->get();
  }
    /********* For Revewing what was sent --report end*********/


  /****** For Transfer *********/
  public function fabrictransferindex($id)
  {
    $godam = Godam::where("status", "active")->get();
    $data = FabricTransferEntryForBag::where('id', $id)->first();
    return view("admin.bag.transfer to bag.index", compact("data", "godam","id"));
  }

  public function getfabricsaccordinggodams(Request $request, $id)
  {
    if ($request->ajax()) {
      $data = FabricStock::where("godam_id", $id)->orderBy('name', "asc")->get();
      return $data;
    }
  }

  public function getspecificfabricdetails(Request $request, $id)
  {
    $name = FabricStock::where('id', $id)->first()->name;
    $allfabswithsamenames = FabricStock::where("name", $name)->get();
    return response([
      "data" => $allfabswithsamenames
    ]);
  }

  public function sendfabrictolower(Request $request, $id)
  {
    if ($request->ajax()) {

      $fabric_bag_entry_id = $request->fabric_bag_entry_id;

      $fabricDetails = FabricStock::where("id", $id)->get();
      try {
        DB::beginTransaction();

        foreach ($fabricDetails as $data) {
          BagTemporaryFabricReceive::create([
            "fabric_bag_entry_id" => $fabric_bag_entry_id,
            "fabric_id" => $id,
            "gram" => $data->gram,
            "gross_wt" => $data->gross_wt,
            "net_wt" => $data->net_wt,
            "meter" => $data->meter,
            "roll_no" => $data->roll_no,
            "loom_no" => $data->loom_no
          ]);
        }

        DB::commit();

        return response(["status"=> "200"]);

      } catch (Exception $e) {
        DB::rollBack();
        return $e->getMessage();
      }
    }
  }

  public function gettemporaryfabricforbag(Request $request){
      if($request->ajax()){
          return response([
            "data" => BagTemporaryFabricReceive::with(['fabric'])->get(),
          ]);
      }
  }

  public function discard(Request $request){
    if($request->ajax()){
      BagTemporaryFabricReceive::truncate();
    }
  }

  public function deletefromlowertable(Request $request){
    if($request->ajax()){
        $request->validate([
          "id" => "required"
        ]);

        try{
          BagTemporaryFabricReceive::where('id',$request->id)->delete();
          return response([
            "message" => "ok"
          ],200);
        }
        catch(Exception $e){
          DB::rollBack();
          return response([
            "message" => $e->getMessage()
          ],400);
        }
    }
  }

  public function finalsave(Request $request){
    if($request->ajax()){
      $id = [];
      $fabric_entry_id = $request->fabric_id;
      $data = BagTemporaryFabricReceive::all();
      try{
        DB::beginTransaction();

        foreach($data as $d){
          BagFabricReceiveItemSent::create([
            // BagTemporaryFabricReceive::create
            "fabric_bag_entry_id" => $fabric_entry_id,
            "fabric_id" => $d->fabric_id,
            "gram" => $d->gram,
            "gross_wt" => $d->gross_wt,
            "net_wt" => $d->net_wt,
            "meter" => $d->meter,
            "roll_no" => $d->roll_no,
            "loom_no" => $d->loom_no
          ]);

          BagFabricReceiveItemSentStock::create([
            "fabric_bag_entry_id" => $fabric_entry_id,
            "fabric_id" => $d->fabric_id,
            "gram" => $d->gram,
            "gross_wt" => $d->gross_wt,
            "net_wt" => $d->net_wt,
            "meter" => $d->meter,
            "roll_no" => $d->roll_no,
            "loom_no" => $d->loom_no
          ]);

          $this->updateFabricTransferEntryForBag($fabric_entry_id);

          $id[] = $d->id;

        }

        BagTemporaryFabricReceive::whereIn("id",$id)->delete();

        DB::commit();
        return response([
          "message" => "ok"
        ],200);
      }
      catch(Exception $e){
        DB::rollback();
        return $e->getMessage();
      }
    }
  }

  public function updateFabricTransferEntryForBag($id){
    FabricTransferEntryForBag::where('id',$id)->update([
      "status" => "completed"
    ]);
  }

}
