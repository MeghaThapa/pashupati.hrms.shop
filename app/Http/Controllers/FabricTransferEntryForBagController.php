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
use App\Services\NepaliConverter;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Symfony\Component\Mime\Encoder\Rfc2231Encoder;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class FabricTransferEntryForBagController extends Controller
{

    protected $neDate;

    public function __construct(NepaliConverter $neDate)
    {
        $this->neDate = $neDate;
    }

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

        return redirect()->route('fabric.transfer.entry.for.bag');
        // ->with(["message" => "Creation successful"]);
    }
    /***********  For Receipts end ************/

    /********* For Revewing what was sent --report *********/
    public function viewSentItem($id)
    {
        // return BagFabricReceiveItemSent::where('fabric_bag_entry_id',$id)->get();

        $data = BagFabricReceiveItemSent::where('fabric_bag_entry_id', $id)->get();
        return view("admin.bag.fabricTransferForBag.viewBill", compact("data"));
    }
    /********* For Revewing what was sent --report end*********/


    /****** For Transfer *********/
    public function fabrictransferindex($id)
    {
        $godam = Godam::where("status", "active")->get();
        $data = FabricTransferEntryForBag::where('id', $id)->first();
        return view("admin.bag.transfer to bag.index", compact("data", "godam", "id"));
    }

    public function getfabricsaccordinggodams(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = FabricStock::where("godam_id", $id)->orderBy('name', "asc")->with(['fabricgroup', 'fabric'])->get()->unique("name")->values()->all();
            return response([
                "data" => $data
            ]);
        }
    }

    public function getspecificfabricdetails(Request $request, $id)
    {
        $name = FabricStock::where('fabric_id', $id)->first()->name;
        $allfabswithsamenames = FabricStock::where("name", $name)->with(['fabricgroup', 'fabric'])->get();
        return DataTables::of($allfabswithsamenames)
            ->addIndexColumn()
            ->addColumn("name", function ($row) {
                return $row->fabric->name;
            })
            ->addColumn("gross_wt", function ($row) {
                return $row->fabric->gross_wt;
            })
            ->addColumn("gross_wt", function ($row) {
                return $row->fabric->gross_wt;
            })
            ->addColumn("net_wt", function ($row) {
                return $row->fabric->net_wt;
            })
            ->addColumn("average_wt", function ($row) {
                return number_format($row->fabric->average_wt, 2);
            })
            ->addColumn("gram_wt", function ($row) {

                $response = $this->gramWt($row);
                return $response;
            })
            ->addColumn("action", function ($row) {
                $gram_wt = $this->gramWt($row);
                return "<a class='btn btn-primary sendFabLower' data-gram_wt='{$gram_wt}' href='$row->fabric_id' data-id='$row->fabric_id'>send</a>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    protected function gramWt($row)
    {
        $size = number_format(floatVal(filter_var($row->fabric->name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)), 2);
        $average = number_format(floatval($row->average_wt), 2);
        return number_format($average / $size, 2);
    }

    public function sendfabrictolower(Request $request, $id)
    {
        if ($request->ajax()) {

            $fabric_bag_entry_id = $request->fabric_bag_entry_id;

            $fabricDetails = FabricStock::where("fabric_id", $id)->get();
            try {
                DB::beginTransaction();

                foreach ($fabricDetails as $data) {
                    BagTemporaryFabricReceive::create([
                        "fabric_bag_entry_id" => $fabric_bag_entry_id,
                        "fabric_id" => $id,
                        "gram" => $data->gram_wt,
                        "gross_wt" => $data->gross_wt,
                        "net_wt" => $data->net_wt,
                        "average" => $data->average_wt,
                        "meter" => $data->meter,
                        "roll_no" => $data->roll_no,
                        "loom_no" => $data->loom_no
                    ]);
                }

                DB::commit();

                return response(["status" => "200"]);
            } catch (Exception $e) {
                DB::rollBack();
                return $e->getMessage();
            }
        }
    }

    public function gettemporaryfabricforbag(Request $request)
    {
        if ($request->ajax()) {
            return response([
                "data" => BagTemporaryFabricReceive::with(['fabric'])->get(),
            ]);
        }
    }

    public function discard(Request $request)
    {
        if ($request->ajax()) {
            // BagTemporaryFabricReceive::truncate();
        }
    }

    public function deletefromlowertable(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                "id" => "required"
            ]);

            try {
                BagTemporaryFabricReceive::where('id', $request->id)->delete();
                return response([
                    "message" => "ok"
                ], 200);
            } catch (Exception $e) {
                DB::rollBack();
                return response([
                    "message" => $e->getMessage()
                ], 400);
            }
        }
    }

    public function finalsave(Request $request)
    {
        // return $request;
        if ($request->ajax()) {
            $id = [];
            $fabric_entry_id = $request->fabric_id;
            $data = BagTemporaryFabricReceive::all();
            $id_of_fabric_stock = [];
            try {
                DB::beginTransaction();

                foreach ($data as $d) {
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
                        "average" => $d->average,
                        "net_wt" => $d->net_wt,
                        "meter" => $d->meter,
                        "roll_no" => $d->roll_no,
                        "loom_no" => $d->loom_no
                    ]);

                    $id[] = $d->id;

                    $id_of_fabric_stock[] = $d->fabric_id;
                }

                $this->updateFabricTransferEntryForBag($fabric_entry_id);

                // return [
                //   "details" => FabricStock::whereIn("fabric_id",$id_of_fabric_stock)->get(),
                //   "ids" => $id_of_fabric_stock];

                BagTemporaryFabricReceive::whereIn("id", $id)->delete();
                FabricStock::whereIn("fabric_id", $id_of_fabric_stock)->delete();

                DB::commit();
                return response([
                    "message" => "ok"
                ], 200);
            } catch (Exception $e) {
                DB::rollback();
                return $e->getMessage();
            }
        }
    }

    public function updateFabricTransferEntryForBag($id)
    {
        FabricTransferEntryForBag::where('id', $id)->update([
            "status" => "completed"
        ]);
    }

    public function fabricEntryReport()
    {
        $godams = Godam::all();
        return view('admin.bag.fabricTransferForBag.entryreport', compact('godams'));
    }

    public function generateEntryReportView(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date']);
        }
        $reportData = [];
        $nepaliDates = [];
        $nepaliDates = $this->getDateRangeNepali($request->start_date, $request->end_date);

        foreach ($nepaliDates as $nepaliDate) {

            $fabrics = DB::table('bag_fabric_receive_item_sent')
                            ->join('bag_fabric_entry', 'bag_fabric_receive_item_sent.fabric_bag_entry_id', '=', 'bag_fabric_entry.id')
                            ->join('fabrics', 'bag_fabric_receive_item_sent.fabric_id', '=', 'fabrics.id')
                            ->join('godam', 'fabrics.godam_id', '=', 'godam.id')
                            ->select(
                                'bag_fabric_receive_item_sent.*',
                                'fabrics.name as fabric_name',
                                'bag_fabric_entry.receipt_date_np',
                                'godam.name as godam_name',
                                'fabrics.godam_id',
                            )
                            ->where('bag_fabric_entry.receipt_date_np', $nepaliDate);

                    if($request->godam_id){
                        $fabrics = $fabrics->where('fabrics.godam_id',$request->godam_id);
                    }

            $fabrics = $fabrics->get();
            if (!$fabrics->isEmpty()) {
                $fabricView = view('admin.bag.fabricTransferForBag.reportview.fabricdatewise', compact('fabrics', 'nepaliDate'))->render();
                array_push($reportData, $fabricView);
            }
        }

        // Summary Part Code

        $nepaliStartDate = $nepaliDates[0];
        $nepaliEndDate = end($nepaliDates);

        $summaryFabrics = DB::table('bag_fabric_receive_item_sent')
            ->join('fabrics', 'bag_fabric_receive_item_sent.fabric_id', '=', 'fabrics.id')
            ->join('bag_fabric_entry', 'bag_fabric_receive_item_sent.fabric_bag_entry_id', '=', 'bag_fabric_entry.id')
            ->select(
                'name',
                DB::raw('COUNT(fabrics.name) as roll_count'),
                DB::raw('SUM(fabrics.gross_wt) as gross_wt'),
                DB::raw('SUM(fabrics.net_wt) as net_wt'),
                DB::raw('SUM(fabrics.meter) as meter')
            )
            ->groupBy('fabrics.name');

        if ($request->start_date && $request->end_date) {
            $summaryFabrics = $summaryFabrics->where('bag_fabric_entry.receipt_date_np', '>=', $request->start_date)->where('bag_fabric_entry.receipt_date_np', '<=', $request->end_date);
        }

        if($request->godam_id){
            $summaryFabrics = $summaryFabrics->where('fabrics.godam_id',$request->godam_id);
        }

        $summaryFabrics = $summaryFabrics->get();
        $summaryView = view('admin.bag.fabricTransferForBag.reportview.fabricsummary', compact('summaryFabrics', 'nepaliStartDate', 'nepaliEndDate'))->render();
        array_push($reportData, $summaryView);
        return response(['status' => true, 'data' => $reportData]);
    }


    private function getDateRangeNepali($npStartDate, $npEndDate)
    {
        $startEngDate = $this->getEngDate($npStartDate);
        $endEngDate   = $this->getEngDate($npEndDate);

        $npDates = [];

        $engDates = $this->getEngDateRange($startEngDate, $endEngDate);
        foreach ($engDates as $engDate) {
            array_push($npDates, $this->getNpDate($engDate));
        }

        return $npDates;
    }

    private function getEngDateRange($startDate, $endDate)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate   = Carbon::createFromFormat('Y-m-d', $endDate);

        $dateRange = CarbonPeriod::create($startDate, $endDate);

        $dates = array_map(fn ($date) => $date->format('Y-m-d'), iterator_to_array($dateRange));

        return $dates;
    }

    private function getEngDate($npDate)
    {
        $explodedStartDate = explode('-', $npDate);
        $date = $this->neDate->nep_to_eng($explodedStartDate[0], $explodedStartDate[1], $explodedStartDate[2]);

        if ($date['month'] < 10) {
            $month = '0' . $date['month'];
        } else {
            $month = $date['month'];
        }

        return $date['year'] . '-' . $month . '-' . $date['date'];
    }

    private function getNpDate($engDate)
    {
        $explodedStartDate = explode('-', $engDate);
        return $this->neDate->eng_to_nep($explodedStartDate[0], $explodedStartDate[1], $explodedStartDate[2]);
    }
}
