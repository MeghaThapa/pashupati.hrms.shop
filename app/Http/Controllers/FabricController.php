<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Godam;
use App\Models\Shift;
use App\Models\Fabric;
use Carbon\CarbonPeriod;
use App\Models\FabricSale;
use App\Models\FabricEntry;
use App\Models\FabricGodam;
use App\Models\FabricGroup;
use App\Models\FabricStock;
use App\Models\FabricDetail;
use Illuminate\Http\Request;
use App\Imports\FabricImport;
use App\Models\FabricGodamList;
use App\Models\FabricSaleItems;
use Yajra\DataTables\DataTables;
use App\Services\NepaliConverter;
use Illuminate\Support\Facades\DB;
use App\Models\TapeEntryStockModel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BagFabricReceiveItemSent;
use App\Models\FabricGodamTransfer;
use App\Models\FabricSendAndReceiveLaminatedSent;
use App\Models\Singlesidelaminatedfabric;
use App\Models\Unlaminatedfabrictripal;

class FabricController extends Controller
{
    protected $neDate;

    public function __construct(NepaliConverter $neDate)
    {
        $this->neDate = $neDate;
    }

    public function fixData()
    {
        $unlaminatedFabrics = FabricStock::where('godam_id', 1)->where('is_laminated', 'false')->pluck('roll_no')->toArray();

        $sentFabrics = FabricSendAndReceiveLaminatedSent::with('fsrentry')->whereNotNull('fabid')->pluck('roll_no')->toArray();

        $fabrics = Fabric::whereIn('roll_no', $sentFabrics)->pluck('roll_no')->toArray();

        // $fabricStock = FabricStock::where('godam_id',1)->where('is_laminated','true')->pluck('roll_no')->toArray();
        $result = array_intersect($fabrics, $unlaminatedFabrics);
        dd($result);
    }

    public function fixDataFabricFromSale()
    {

        $fabricStock = FabricStock::where('is_laminated', "true")->where('godam_id', 1)->pluck('roll_no')->toArray();

        $laminatedFabricsFrom = FabricSendAndReceiveLaminatedSent::whereNotNull('fabid')->pluck('fabid');

        $salesFabricIds = FabricSale::where('is_laminated', 'false')->pluck('fabric_id')->toArray();

        $fabricsFromSale = Fabric::whereIn('id', $salesFabricIds)->where('is_laminated', 'true')->pluck('roll_no')->toArray();

        $result = array_intersect($fabricStock, $fabricsFromSale);
        dd($result);
        // $fabricGodamTransfers = FabricGodamTransfer::where('fromgodam_id',3)->where('togodam_id',1)->pluck('roll')->toArray();



        // fixed fabric stock to fabric bag
        $fabricStock = FabricStock::where('is_laminated', "false")->pluck('roll_no')->toArray();
        $fabricToBags = BagFabricReceiveItemSent::pluck('roll_no')->toArray();
        $result = array_intersect($fabricStock, $fabricToBags);
        dd($result);
    }

    public function fixDataold2()
    {
        $soldFabricIds = FabricSaleItems::pluck('fabric_id')->toArray();

        $soldFabricRolls = Fabric::whereIn('id', $soldFabricIds)->pluck('roll_no')->toArray();

        $updatedItems = array_map(function ($soldFabricRolls) {
            // Check if the last character of the item is 'A', 'B', 'C', or 'D'
            if (in_array(substr($soldFabricRolls, -2), ['-A', '-B', '-C', '-D'])) {
                // Remove the last character and return the updated item
                return substr($soldFabricRolls, 0, -2);
            }

            if (in_array(substr($soldFabricRolls, -1), ['A', 'B', 'C', 'D'])) {
                // Remove the last character and return the updated item
                return substr($soldFabricRolls, 0, -1);
            }

            // If neither condition is met, return the original item
            return $soldFabricRolls;
        }, $soldFabricRolls);

        $unlaminatedFabrics = FabricStock::where('godam_id', 1)->where('is_laminated', 'false')->pluck('roll_no')->toArray();

        $updatedUnLaminatedFabrics = array_map(function ($unlaminatedFabrics) {
            // Check if the item starts with '01-'
            if (strpos($unlaminatedFabrics, '01-') === 0) {
                // Remove '01-' from the beginning and store the result
                $unlaminatedFabrics = substr($unlaminatedFabrics, 3);
            }

            // If neither condition is met, return the original item
            return $unlaminatedFabrics;
        }, $unlaminatedFabrics);

        $result = array_intersect($updatedUnLaminatedFabrics, $updatedItems);

        dd($result);

        $result = array_map(function ($item) {
            return "01-$item";
        }, $result);

        // $laminatedFabricsFrom = FabricSendAndReceiveLaminatedSent::with('fsrentry')->whereNull('fabid')->pluck('roll_no')->toArray();

        // $soldFabrics = FabricSale::with('getfabric','getsaleentry')->get();
        // return view('admin.fabric.salepage',compact('soldFabrics'));
    }



    public function fixDataOld()
    {

        $laminatedFabricsFrom = FabricSendAndReceiveLaminatedSent::with('fsrentry')->whereNull('fabid')->get();

        return view('admin.fabric.fixpage', compact('laminatedFabricsFrom'));

        dd(count($laminatedFabricsFrom));

        $salesFabricIds = FabricSale::pluck('fabric_id')->toArray();

        $fabricsFromSale = Fabric::whereIn('id', $salesFabricIds)->pluck('roll_no')->toArray();

        $fabricStocks = Fabric::where('godam_id', 1)->pluck('roll_no')->toArray();

        $result = array_intersect($fabricStocks, $fabricsFromSale);



        $fabricstocks = DB::table('fabric_stock')->whereIn('roll_no', $result)->get();

        dd($fabricstocks);

        dd('task done');

        $laminatedFabricsFrom = FabricSendAndReceiveLaminatedSent::whereNotNull('fabid')->pluck('fabid');

        dd(count($laminatedFabricsFrom));

        $unlaminatedfabrics = Fabric::whereIn('id', $laminatedFabricsFrom)->where('godam_id', 1)->pluck('roll_no')->toArray();
        // dd($unlaminatedfabrics);


        // $laminatedFabrics = FabricSendAndReceiveLaminatedSent::pluck('roll_no')->toArray();
        $result = array_intersect($fabricStocks, $unlaminatedfabrics);
        dd($result);
        $fabricStocks = FabricStock::where('godam_id', 1)->where('is_laminated', 'false')->whereIn('roll_no', $result)->delete();
        dd('done');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FabricDetail::with('getGodam');

            if ($request->start_date && $request->end_date) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $query->whereBetween('bill_date', [$start_date, $end_date]);
            }

            if ($request->godam_id) {
                $query->where('godam_id', (int)$request->godam_id);
            }

            $totalNetweightSum = $query->sum('total_netweight');

            $data = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('godam', function ($row) {
                    return $row->getGodam->name;
                })

                ->toArray();

            $data['total_netweight_sum'] = $totalNetweightSum;

            return response()->json($data);
        }

        $fabrics = FabricDetail::paginate(15);
        $departments = Godam::get();
        $shifts = Shift::get();
        $fabric_netweight = 0;
        return view('admin.fabric.index', compact('fabrics', 'departments', 'shifts', 'fabric_netweight'));
    }

    public function test()
    {
        $data = Fabric::get();
        foreach ($data as $value) {
            $group = FabricGroup::find($value->fabricgroup_id);
            $final = $value->name . '(' . $group->name . ')';
            $sa = Fabric::where('id', $value->id)->update(['name' => $final]);
        }
    }

    public function create()
    {
        $fabricgroups = FabricGroup::get();
        $godams = Godam::get();
        return view('admin.fabric.create', compact('fabricgroups', 'godams'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:60|unique:fabrics',
            'fabricgroup_id' => 'required|integer',
            // 'roll_no' => 'required|integer',
            // 'loom_no' => 'required|integer',
            // 'gross_wt' => 'required|integer',
            // 'net_wt' => 'required|integer',
        ]);

        // store subcategory
        $fabric = Fabric::create([
            'name' => $request['name'],
            'roll_no' => '0',
            'loom_no' => '0',
            'fabricgroup_id' => $request['fabricgroup_id'],
            'godam_id' => $request['godam_id'],
            'average_wt' => '0',
            'gross_wt' => '0',
            'net_wt' => '0',
            'meter' => $request['meter'],
            'gram_wt' => '00',
        ]);

        return redirect()->back()->withSuccess('Sub category created successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm",
        ]);
        try {

            DB::beginTransaction();

            if ($request->file('file')) {
                $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
                $request->file('file')->storeAs('uploads/fabric/import', $fileName, 'public');
                $filePath =  '/storage/uploads/fabric/import/' . $fileName;
            } else {
                $filePath = null;
            }

            FabricEntry::create([
                'entry_date' => $request->date_np,
                'godam_id' => $request->godam_id,
                'file_path' => $filePath,
            ]);

            $file = $request->file('file');
            $import = Excel::import(new FabricImport($request->godam_id, $request->date_np), $file);


            DB::commit();
            return back()->with(["message" => "Data imported successfully!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function entryReport()
    {
        return view('admin.fabric.report');
    }

    public function entryReportTable(Request $request)
    {
        if ($request->ajax()) {
            $query = FabricEntry::with('godam');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('file_path', function ($row) {
                    $fileName = asset($row->file_path);
                    return '<a href="' . asset($fileName) . '"> Download/View File</a>';
                })
                ->addColumn('godam', function ($row) {
                    return $row->godam->name;
                })
                ->rawColumns(['file_path'])
                ->make();
        }
    }

    public function edit($slug)
    {
        $fabrics = Fabric::where('slug', $slug)->first();
        $fabricgroups = FabricGroup::get();
        $godams = Godam::get();
        return view('admin.fabric.edit', compact('fabrics', 'fabricgroups', 'godams'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $fabric = Fabric::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:fabrics,name,' . $fabric->id,
            'fabricgroup_id' => 'required|integer',
            // 'roll_no' => 'required|integer',
            // 'loom_no' => 'required|integer',
            // 'gross_wt' => 'required|integer',
            // 'net_wt' => 'required|integer',
        ]);

        // update fabric
        $fabric->update([
            'name' => $request->name,
            // 'roll_no' => $request['roll_no'],
            // 'loom_no' => $request['loom_no'],
            'fabricgroup_id' => $request['fabricgroup_id'],
            'godam_id' => $request['godam_id'],
            // 'gross_wt' => $request['gross_wt'],
            // 'net_wt' => $request['net_wt'],
            'meter' => $request['meter'],
        ]);


        return redirect()->route('fabrics.index')->withSuccess('Fabric updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = Fabric::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('fabrics.index')->withSuccess('Fabric deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = Fabric::where('slug', $slug)->first();

        // change category status
        if ($category->status == 1) {
            $category->update([
                'status' => 0
            ]);
        } else {
            $category->update([
                'status' => 1
            ]);
        }
        return redirect()->route('fabrics.index')->withSuccess('Fabric status changed successfully!');
    }

    public function fabricDetail(Request $request)
    {
        $godam_id = $request->to_godam_id;
        $planttype_id = $request->planttype_id;
        $plantname_id = $request->plantname_id;
        $shift_id = $request->shift_id;
        //validate form
        $validator = $request->validate([
            'pipe_cutting' => 'required|integer',
            'bd_wastage' => 'required|integer',
            'other_wastage' => 'required|integer',
            'total_wastage' => 'required|integer',
            // 'total_netweight' => 'required|integer',
            'total_meter' => 'required|integer',
            'total_weightinkg' => 'required|integer',
            'total_wastageinpercent' => 'required|integer',
            'run_loom' => 'required|integer',
            'wrapping' => 'required|integer',
        ]);


        $getLastId = Fabric::latest()->first();
        $bill_no = $getLastId->bill_no;

        $gettapeQuantity = TapeEntryStockModel::where('toGodam_id', $godam_id)
            // ->where('plantType_id',$planttype_id)
            // ->where('plantName_id',$plantname_id)
            // ->where('shift_id',$shift_id)
            ->value('id');
        // dd($gettapeQuantity);

        $findTape = TapeEntryStockModel::find($gettapeQuantity);
        dd($findTape->tape_qty_in_kg);
        $totalwastage = $request['total_wastage'];
        $totalnetWeight = $request['total_netweight'];
        $finalWastage = $totalwastage + $totalnetWeight;

        // dd($finalWastage,$findTape->tape_qty_in_kg);
        if ($totalnetWeight < $findTape->tape_qty_in_kg) {

            $final = $findTape->tape_qty_in_kg - $finalWastage;
            $findTape->tape_qty_in_kg = $final;
            $findTape->update();

            $countData = FabricDetail::where('bill_number', $bill_no)->count();
            if ($countData != 1) {
                // store subcategory
                $fabric = FabricDetail::create([
                    'bill_number' => $bill_no,
                    'bill_date' => '0',
                    'pipe_cutting' => $request['pipe_cutting'],
                    'bd_wastage' => $request['bd_wastage'],
                    'other_wastage' => $request['other_wastage'],
                    'total_wastage' => $request['total_wastage'],
                    'total_netweight' => $request['total_netweight'],
                    'total_meter' => $request['total_meter'],
                    'total_weightinkg' => $request['total_weightinkg'],
                    'total_wastageinpercent' => $request['total_wastageinpercent'],
                    'run_loom' => $request['run_loom'],
                    'wrapping' => $request['wrapping'],
                ]);
            }
        }

        return redirect()->back()->withSuccess('Sub category created successfully!');
    }

    public function fabricDetailDestroy($fabricDetail_id)
    {

        try {
            DB::beginTransaction();
            $id = $fabricDetail_id;
            $find_data = FabricDetail::find($id);


            // dd($find_data->total_netweight,$find_data);
            $getfabricstock = FabricStock::where('bill_no', $find_data->bill_number)->get();

            $getfabric = Fabric::where('bill_no', $find_data->bill_number)->get();

            foreach ($getfabric as $stock) {
                $stock->delete();
            }

            foreach ($getfabricstock as $stock) {
                $stock->delete();
            }

            $gettapeQuantity = TapeEntryStockModel::where('toGodam_id', $find_data->godam_id)
                ->value('id');

            $findTape = TapeEntryStockModel::find($gettapeQuantity);
            $totalwaste = $find_data->pipe_cutting + $find_data->bd_wastage + $find_data->other_wastage;

            $value = $find_data->total_netweight + $totalwaste;

            $final = $findTape->tape_qty_in_kg + $value;
            $findTape->tape_qty_in_kg = $final;
            $findTape->update();

            FabricDetail::where('id', $id)->delete();


            DB::commit();

            return back();
        } catch (Exception $e) {
            DB::rollBack();
            return response([
                "exception" => $e->getMessage(),
            ]);
        }

        return back();
    }

    public function discard()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fabrics')->truncate();
        DB::table('fabric_stock')->truncate();
        DB::table('fabric_details')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return back();
    }

    public function fabricEntryReport()
    {
        $godams = Godam::all();
        return view('admin.fabric.entryreport', compact('godams'));
    }

    public function godamTransferReport()
    {
        $godams = Godam::all();
        return view('admin.fabric.godam_transfer_report', compact('godams'));
    }

    public function laminatedReport()
    {
        $godams = Godam::all();
        return view('admin.fabric.laminated_report', compact('godams'));
    }

    public function unLaminatedReport()
    {
        $godams = Godam::all();
        return view('admin.fabric.unlaminated_report', compact('godams'));
    }

    public function generateLaminatedFabricView(Request $request)
    {

        ini_set('max_execution_time', 1200);

        if (!$request->start_date || !$request->end_date) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date']);
        }
        $reportData = [];
        $nepaliDates = [];
        $nepaliDates = $this->getDateRangeNepali($request->start_date, $request->end_date);
        $godam_id = $request->godam_id;
        foreach ($nepaliDates as $nepaliDate) {

            $laminatedFabrics = FabricSendAndReceiveLaminatedSent::join('fabrics', 'fabric_send_and_receive_laminated_sent.fabid', '=', 'fabrics.id')
                ->whereNotNull('fabric_send_and_receive_laminated_sent.fabid')->with('fabric.godam')->where('fabrics.date_np', $nepaliDate);

            if ($request->godam_id) {
                $laminatedFabrics = $laminatedFabrics->where('godam_id', $godam_id);
            }

            $laminatedFabrics = $laminatedFabrics->get();

            if (!$laminatedFabrics->isEmpty()) {
                $fabricView = view('admin.fabric.reportview.laminated_datewise', compact('laminatedFabrics', 'nepaliDate'))->render();
                array_push($reportData, $fabricView);
            }
        }

        // Summary Part Code

        $nepaliStartDate = $nepaliDates[0];
        $nepaliEndDate = end($nepaliDates);

        $summaryFabrics = FabricSendAndReceiveLaminatedSent::select(
            'fabric_send_and_receive_laminated_sent.fabric_name',
            DB::raw('COUNT(fabric_send_and_receive_laminated_sent.fabric_name) as roll_count'),
            DB::raw('SUM(fabric_send_and_receive_laminated_sent.gross_wt) as gross_wt'),
            DB::raw('SUM(fabric_send_and_receive_laminated_sent.net_wt) as net_wt'),
            DB::raw('SUM(fabric_send_and_receive_laminated_sent.meter) as meter')
        )
            ->join('fabrics', 'fabric_send_and_receive_laminated_sent.fabid', '=', 'fabrics.id')
            ->whereNotNull('fabric_send_and_receive_laminated_sent.fabid')
            ->groupBy('fabric_send_and_receive_laminated_sent.fabric_name');

        if ($request->start_date && $request->end_date) {
            $summaryFabrics = $summaryFabrics->where('fabrics.date_np', '>=', $request->start_date)->where('fabrics.date_np', '<=', $request->end_date);
        }

        if ($request->godam_id) {
            $summaryFabrics = $summaryFabrics->where('fabrics.godam_id', $request->godam_id);
        }

        $summaryFabrics = $summaryFabrics->get();
        $summaryView = view('admin.fabric.reportview.lamfabricsummary', compact('summaryFabrics', 'nepaliStartDate', 'nepaliEndDate'))->render();
        array_push($reportData, $summaryView);
        return response(['status' => true, 'data' => $reportData]);
    }

    public function generateUnLaminatedFabricView(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date']);
        }
        $reportData = [];
        $nepaliDates = [];
        $nepaliDates = $this->getDateRangeNepali($request->start_date, $request->end_date);
        $godam_id = $request->godam_id;
        foreach ($nepaliDates as $nepaliDate) {

            $unLaminatedFabrics = FabricStock::query()->with('godam');

            if ($request->godam_id) {
                $unLaminatedFabrics->where('godam_id', $godam_id);
            }

            $unLaminatedFabrics = $unLaminatedFabrics->where('fabric_stock.date_np', $nepaliDate)->where('fabric_stock.is_laminated', 'false')->get();

            if (!$unLaminatedFabrics->isEmpty()) {
                $fabricView = view('admin.fabric.reportview.unlaminated_datewise', compact('unLaminatedFabrics', 'nepaliDate'))->render();
                array_push($reportData, $fabricView);
            }
        }

        // Summary Part Code

        $nepaliStartDate = $nepaliDates[0];
        $nepaliEndDate = end($nepaliDates);

        $summaryFabrics = FabricStock::select(
            'name',
            DB::raw('COUNT(name) as roll_count'),
            DB::raw('SUM(gross_wt) as gross_wt'),
            DB::raw('SUM(net_wt) as net_wt'),
            DB::raw('SUM(meter) as meter')
        )
            ->where('is_laminated', 'false')
            ->groupBy('name');

        if ($request->start_date && $request->end_date) {
            $summaryFabrics = $summaryFabrics->where('fabric_stock.date_np', '>=', $request->start_date)->where('fabric_stock.date_np', '<=', $request->end_date);
        }

        if ($request->godam_id) {
            $summaryFabrics = $summaryFabrics->where('fabric_stock.godam_id', $request->godam_id);
        }

        $summaryFabrics = $summaryFabrics->get();
        $summaryView = view('admin.fabric.reportview.unlamfabricsummary', compact('summaryFabrics', 'nepaliStartDate', 'nepaliEndDate'))->render();
        array_push($reportData, $summaryView);
        return response(['status' => true, 'data' => $reportData]);
    }

    public function generateGodamTransferView(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date']);
        }
        $reportData = [];
        $nepaliDates = [];
        $nepaliDates = $this->getDateRangeNepali($request->start_date, $request->end_date);
        foreach ($nepaliDates as $nepaliDate) {

            $fabricGodams = FabricGodamList::with('getToGodam', 'fabric')->where('bill_date', $nepaliDate);

            if ($request->godam_id) {
                $fabricGodams = $fabricGodams->where('fromgodam_id', $request->godam_id);
            }
            $fabricGodams = $fabricGodams->get();

            if (!$fabricGodams->isEmpty()) {
                $fabricView = view('admin.fabric.reportview.godam_transfer_datewise', compact('fabricGodams', 'nepaliDate'))->render();
                array_push($reportData, $fabricView);
            }
        }

        // Summary Part Code

        $nepaliStartDate = $nepaliDates[0];
        $nepaliEndDate = end($nepaliDates);

        $summaryFabrics = FabricGodamList::select(
            'fabric_godam_lists.name',
            DB::raw('COUNT(fabric_godam_lists.name) as roll_count'),
            DB::raw('SUM(fabrics.gross_wt) as gross_wt'),
            DB::raw('SUM(fabrics.net_wt) as net_wt'),
            DB::raw('SUM(fabrics.meter) as meter')
        )
            ->join('fabrics', 'fabric_godam_lists.fabric_id', '=', 'fabrics.id')
            ->groupBy('fabric_godam_lists.name');

        if ($request->start_date && $request->end_date) {
            $summaryFabrics = $summaryFabrics->where('fabric_godam_lists.bill_date', '>=', $request->start_date)->where('fabric_godam_lists.bill_date', '<=', $request->end_date);
        }

        if ($request->godam_id) {
            $summaryFabrics = $summaryFabrics->where('fabric_godam_lists.fromgodam_id', $request->godam_id);
        }

        $summaryFabrics = $summaryFabrics->get();
        $summaryView = view('admin.fabric.reportview.fabricsummary', compact('summaryFabrics', 'nepaliStartDate', 'nepaliEndDate'))->render();
        array_push($reportData, $summaryView);
        return response(['status' => true, 'data' => $reportData]);
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

            $fabrics = Fabric::where('date_np', $nepaliDate);

            if ($request->godam_id) {
                $fabrics = $fabrics->where('godam_id', $request->godam_id);
            }
            $fabrics = $fabrics->get();
            if (!$fabrics->isEmpty()) {
                $fabricView = view('admin.fabric.reportview.fabricdatewise', compact('fabrics', 'nepaliDate'))->render();
                array_push($reportData, $fabricView);
            }
        }

        // Summary Part Code

        $nepaliStartDate = $nepaliDates[0];
        $nepaliEndDate = end($nepaliDates);

        $summaryFabrics = Fabric::select(
            'name',
            DB::raw('COUNT(name) as roll_count'),
            DB::raw('SUM(gross_wt) as gross_wt'),
            DB::raw('SUM(net_wt) as net_wt'),
            DB::raw('SUM(meter) as meter')
        )
            ->groupBy('name');

        if ($request->start_date && $request->end_date) {
            $summaryFabrics = $summaryFabrics->where('date_np', '>=', $request->start_date)->where('date_np', '<=', $request->end_date);
        }

        if ($request->godam_id) {
            $summaryFabrics = $summaryFabrics->where('godam_id', $request->godam_id);
        }

        $summaryFabrics = $summaryFabrics->get();
        $summaryView = view('admin.fabric.reportview.fabricsummary', compact('summaryFabrics', 'nepaliStartDate', 'nepaliEndDate'))->render();
        array_push($reportData, $summaryView);
        return response(['status' => true, 'data' => $reportData]);
    }

    private function getDateRangeNepali($npStartDate, $npEndDate)
    {
        $startEngDate = $this->getEngDate($npStartDate);
        $endEngDate = $this->getEngDate($npEndDate);

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
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

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
