<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\FabricSale;
use App\Models\FabricStock;
use App\Rules\ValidDpNumber;
use Illuminate\Http\Request;
use App\Models\FabricSaleEntry;
use App\Models\FabricSaleItems;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Exceptions\Exception;

class FabricSendAndReceiveSaleController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {

        $fabrics = FabricStock::get()->unique('name')->values()->all();
        $partyname = Supplier::where('status', 1)->get();
        return view('admin.sale.fabricsale.index', compact('fabrics', 'partyname'));
    }

    public function indexajax()
    {
        $fabricSalesEntries = FabricSaleEntry::select(
            'fabric_sale_entry.id as fabric_sale_id',
            'fabric_sale_entry.bill_no',
            'fabric_sale_entry.bill_date',
            'fabric_sale_entry.status',
            DB::raw('SUM(fabrics.net_wt) as total_net_wt'),
            'suppliers.name as supplier_name' // Add this line to select the supplier name
        )
            ->leftJoin('fabric_sale_items', 'fabric_sale_entry.id', '=', 'fabric_sale_items.sale_entry_id')
            ->leftJoin('fabrics', 'fabric_sale_items.fabric_id', '=', 'fabrics.id')
            ->leftJoin('suppliers', 'fabric_sale_entry.partyname_id', '=', 'suppliers.id')
            ->groupBy('fabric_sale_entry.bill_no', 'fabric_sale_entry.bill_date', 'suppliers.name', 'fabric_sale_entry.id', 'fabric_sale_entry.status');
        if ($this->request->ajax()) {
            return DataTables::of($fabricSalesEntries)
                ->addIndexColumn()
                ->addColumn("supplier", function ($row) {
                    return $row->supplier_name;
                })
                ->addColumn('net_wt', function ($row) {
                    return $row->total_net_wt;
                })
                ->addColumn("action", function ($row) {
                    if ($row->status == "pending") {
                        return "
                                    <div class='btn-group'>
                                        <a href='javascripy:void(0)' data-id={$row->fabric_sale_id} class='btn btn-primary create-sale'><i class='fa fa-plus' aria-hidden='true'></i></a>
                                    </div>
                                ";
                    } else {
                        return '<a href="' . route('fabric.sale.viewBill', ['bill_id' => $row->fabric_sale_id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';
                        // return "
                        //     <div class='btn-group'>
                        //         <a href='javascripy:void(0)' data-id={$row->id} class='btn btn-secondary view-sale'><i class='fa fa-eye' aria-hidden='true'></i></a>
                        //     </div>
                        // ";
                    }
                })
                ->rawColumns(["action"])
                ->make(true);
        }
    }

    public function viewBill($id)
    {
        $findsale = FabricSaleEntry::find($id);
        $fabrics = FabricSaleItems::with('getfabric')->where('sale_entry_id', $id)
            ->get();

        $formattedData = [];

        $fabrics->each(function ($fabric) use (&$formattedData) {
            $formattedData[$fabric->getfabric->name][] = [
                'name' => $fabric->getfabric->name,
                'gross_wt' => $fabric->getfabric->gross_wt,
                'net_wt' => $fabric->getfabric->net_wt,
                'roll_no' => $fabric->getfabric->roll_no,
                'meter' => $fabric->getfabric->meter,
                'average_wt' => $fabric->getfabric->average_wt,
                'gram_wt' => $fabric->getfabric->gram_wt
            ];
        });

        $totalstocks = DB::table('fabric_sale_items')
            ->join('fabrics', 'fabric_sale_items.fabric_id', '=', 'fabrics.id')
            ->select(
                'fabrics.name',
                DB::raw('SUM(fabrics.gross_wt) as total_gross'),
                DB::raw('SUM(fabrics.net_wt) as total_net'),
                DB::raw('SUM(fabrics.meter) as total_meter'),
                DB::raw('COUNT(fabrics.name) as total_count')
            )
            ->where('fabric_sale_items.sale_entry_id', $id)
            ->groupBy('fabrics.name')
            ->orderBy('fabrics.name')
            ->get();

        $total_gross = $totalstocks->sum('total_gross');
        $total_net = $totalstocks->sum('total_net');
        $total_meter = $totalstocks->sum('total_meter');

        return view('admin.sale.fabricsale.viewbill', compact('findsale', 'fabrics', 'totalstocks', 'total_gross', 'total_net', 'total_meter', 'formattedData'));
    }

    public function printViewBill($id)
    {
        $findsale = FabricSaleEntry::find($id);
        $fabrics = FabricSaleItems::with('getfabric')->where('sale_entry_id', $id)
            ->get();

        $formattedData = [];

        $fabrics->each(function ($fabric) use (&$formattedData) {
            $formattedData[$fabric->getfabric->name][] = [
                'name' => $fabric->getfabric->name,
                'gross_wt' => $fabric->getfabric->gross_wt,
                'net_wt' => $fabric->getfabric->net_wt,
                'roll_no' => $fabric->getfabric->roll_no,
                'meter' => $fabric->getfabric->meter,
                'average_wt' => $fabric->getfabric->average_wt,
                'gram_wt' => $fabric->getfabric->gram_wt
            ];
        });

        $totalstocks = DB::table('fabric_sale_items')
            ->join('fabrics', 'fabric_sale_items.fabric_id', '=', 'fabrics.id')
            ->select(
                'fabrics.name',
                DB::raw('SUM(fabrics.gross_wt) as total_gross'),
                DB::raw('SUM(fabrics.net_wt) as total_net'),
                DB::raw('SUM(fabrics.meter) as total_meter'),
                DB::raw('COUNT(fabrics.name) as total_count')
            )
            ->where('fabric_sale_items.sale_entry_id', $id)
            ->groupBy('fabrics.name')
            ->orderBy('fabrics.name')
            ->get();

        $total_gross = $totalstocks->sum('total_gross');
        $total_net = $totalstocks->sum('total_net');
        $total_meter = $totalstocks->sum('total_meter');

        return view('admin.sale.fabricsale.printviewbill', compact('findsale', 'fabrics', 'totalstocks', 'total_gross', 'total_net', 'total_meter', 'formattedData'));
    }

    public function store()
    {
        $this->request->validate([
            'bill_number' => "required",
            'bill_date' => "required",
            'partyname' => "required",
            'bill_for' => "required",
            'lory_number' => "required",
            'dp_number' => [
                'required',
                new ValidDpNumber,
            ],
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

    public function create($entry_id)
    {
        try {
            $fabricsaleentry = FabricSaleEntry::where("id", $entry_id)->firstorFail();
            $fabrics = FabricStock::with("fabric")->get()->unique('name');
            return view("admin.sale.fabricsale.create")->with([
                "fabricsaleentry" => $fabricsaleentry,
                "fabrics" => $fabrics,
                "entry_id" => $entry_id
            ]);
        } catch (Exception $e) {
            abort(403);
        }
    }

    public function getidenticalfabricdetails()
    {
        if ($this->request->ajax()) {
            $fabric_id = $this->request->fabric_name_id;
            $name = FabricStock::where("id", $fabric_id)->value('name');
            return DataTables::of(FabricStock::where("name", $name)->get())
                ->addIndexColumn()
                ->addColumn("action", function ($row) {
                    return "<a href='javascript:void(0)' class='btn btn-primary send-to-lower' data-id='{$row->id}'>Send </a>'";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function storeSale()
    {
        if ($this->request->ajax()) {
            // return $this->request->all();
            $fabric = FabricStock::where("id", $this->request->fabric_id)->first();
            FabricSale::create([
                "sale_entry_id" => $this->request->entry_id,
                "fabric_id" => $fabric->fabric_id
            ]);
        }
    }

    public function getSales()
    {
        if ($this->request->ajax()) {
            return DataTables::of(FabricSale::where("sale_entry_id", $this->request->entry_id)->with(["getsaleentry", "getfabric"])->get())
                ->addIndexColumn()
                ->addColumn("fabric_name", function ($row) {
                    return $row->getfabric->name;
                })
                ->addColumn("gross_wt", function ($row) {
                    return $row->getfabric->gross_wt;
                })
                ->addColumn("meter", function ($row) {
                    return $row->getfabric->meter;
                })
                ->addColumn("net_wt", function ($row) {
                    return $row->getfabric->net_wt;
                })
                ->addColumn("gram_wt", function ($row) {
                    return $row->getfabric->gram_wt;
                })
                ->addColumn("roll", function ($row) {
                    return $row->getfabric->roll_no;
                })
                ->addColumn("average_wt", function ($row) {
                    return $row->getfabric->average_wt;
                })
                ->addColumn("action", function ($row) {
                    return "<button class='btn btn-danger delete-sale' data-id='{$row->id}'> <i class='fa fa-trash' aria-hidden='true'></i> </button>";
                })
                ->rawColumns(["action"])
                ->make(true);
        }
    }

    public function indexsumsajax()
    {
        if ($this->request->ajax()) {
            $fabrics = DB::table("fabric_sales")->where("sale_entry_id", $this->request->entry_id)
                ->leftJoin("fabrics", "fabrics.id", "=", "fabric_sales.fabric_id")
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

    public function delete()
    {
        if ($this->request->ajax()) {
            try {
                $fabric_sale = FabricSale::findorfail($this->request->id);
                $fabric_sale->delete();
                return response([
                    "message" =>  "Deletion Completes",
                    "status" => 200
                ]);
            } catch (Exception $e) {
                return response([
                    "message_err" => $e->getMessage()
                ]);
            }
        }
    }

    public function submit()
    {
        if ($this->request->ajax()) {
            try {
                DB::beginTransaction();
                foreach (FabricSale::where("sale_entry_id", $this->request->id)->get() as $data) {
                    $sale_fabric_id = $data->fabric_id;

                    FabricSaleItems::create([
                        "fabric_id" => $data->fabric_id,
                        "sale_entry_id" => $data->sale_entry_id
                    ]);

                    FabricStock::where("fabric_id", $sale_fabric_id)->delete();
                }

                FabricSale::where("sale_entry_id", $this->request->id)->delete();
                FabricSaleEntry::where("id", $this->request->id)->update([
                    "status" => "completed"
                ]);
                DB::commit();
                return response([
                    "status" => 200,
                    "message" => "saved successfully"
                ]);
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
    }

    public function report()
    {
        return view('admin.sale.fabricsale.report');
    }

    public function generateReportView(Request $request)
    {
        $fabricSalesEntries = FabricSaleEntry::with('getParty', 'fabricSaleItems.fabric')
            ->where('bill_date', '>=', $request->start_date)
            ->where('bill_date', '<=', $request->end_date)->where('bill_for', $request->bill_for)
            ->orderBy('bill_date', 'ASC')
            ->get();

        $generatedView = view('admin.sale.fabricsale.reportview', compact('fabricSalesEntries'))->render();

        $summaryReport = DB::table('fabric_sale_items')
            ->select(
                'fabrics.name as fabric_name',
                DB::raw('SUM(fabrics.gross_wt) as total_gross_wt'),
                DB::raw('SUM(fabrics.net_wt) as total_net_wt'),
                DB::raw('SUM(fabrics.meter) as total_meter'),
                DB::raw('COUNT(fabrics.name) as fabric_count')
            )
            ->join('fabrics', 'fabric_sale_items.fabric_id', '=', 'fabrics.id')
            ->join('fabric_sale_entry', 'fabric_sale_items.sale_entry_id', '=', 'fabric_sale_entry.id')
            ->where('fabric_sale_entry.bill_date', '>=', $request->start_date)
            ->where('fabric_sale_entry.bill_date', '<=', $request->end_date)
            ->where('fabric_sale_entry.bill_for', '=', $request->bill_for)
            ->groupBy('fabrics.name')
            ->get();

        $summaryView = view('admin.sale.fabricsale.summaryview', compact('summaryReport'))->render();

        return response(['status' => true, 'data' => $generatedView, 'summary' => $summaryView]);
    }
}
