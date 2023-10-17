<?php

namespace App\Http\Controllers;

use App\Models\BagSellingEntry;
use App\Models\Supplier;
use App\Models\BagBundelStock;
use App\Models\BagSellingItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\NepaliConverter;
use Illuminate\Support\Facades\DB;

class BagSellingEntryController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $neDate;

    public function __construct(NepaliConverter $neDate)
    {
        $this->neDate = $neDate;
    }
    public function index()
    {
        //  $bagSellingEntryData=BagSellingEntry::with('supplier')
        // ->where('id','1')
        // ->get(['challan_no','date','nepali_date','supplier_id','gp_no','lorry_no','do_no','rem'])
        // ->first();
        // return $bagSellingEntryData;
        // return (self::compareData());

        return view('admin.bag.bagSelling.index');
    }
    private function compareData()
    {
        $bagSellingItems = BagSellingItem::all();
        foreach ($bagSellingItems as $item) {
            $stock = BagBundelStock::where('bundle_no', $item->bundel_no)->get()->first();
            if (!$stock) {
                $bagBundelStock = new BagBundelStock();
                $bagBundelStock->group_id = $item->group_id;
                $bagBundelStock->bag_brand_id = $item->brand_bag_id;
                $bagBundelStock->bundle_no = $item->bundel_no;
                $bagBundelStock->qty_pcs = $item->pcs;
                $bagBundelStock->qty_in_kg = $item->weight;
                $bagBundelStock->average_weight = $item->average;
                $bagBundelStock->type = "transaction";
                $bagBundelStock->status = 'sold';
                $bagBundelStock->save();
            }
        }
        return 'done';

        // return view('comparison', ['missingBundelStocks' => $missingBundelStocks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::where('status', '1')->get();
        //return  $suppliers;
        $nepaliDate = getNepaliDate(date('Y-m-d'));
        $date = date('Y-m-d');
        $bagSellingEntryData = null;
        return view('admin.bag.bagSelling.createEntry', compact('nepaliDate', 'date', 'suppliers', 'bagSellingEntryData'));
    }
    public function edit($bagSellingEntry_id)
    {
        $suppliers = Supplier::where('status', '1')->get();
        $bagSellingEntryData = BagSellingEntry::with('supplier')
            ->where('id', $bagSellingEntry_id)
            ->get(['id', 'challan_no', 'date', 'nepali_date', 'supplier_id', 'gp_no', 'lorry_no', 'do_no', 'rem'])
            ->first();
        return view('admin.bag.bagSelling.createEntry', compact('bagSellingEntryData', 'suppliers'));
    }
    public function bagSellingYajraDatatables()
    {
        $bagSellingEntryDatas = BagSellingEntry::with('supplier:id,name')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'challan_no', 'date', 'nepali_date', 'supplier_id', 'gp_no', 'lorry_no', 'do_no', 'rem', 'status']);

        return DataTables::of($bagSellingEntryDatas)
            ->addIndexColumn()
            ->addColumn('statusBtn', function ($bagSellingEntryData) {
                if ($bagSellingEntryData->status == 'completed') {
                    return '<span class="badge badge-pill badge-success">' . $bagSellingEntryData->status . '</span>';
                } else {
                    return '<span class="badge badge-pill badge-primary">' . $bagSellingEntryData->status . '</span>';
                }
            })
            ->addColumn('action', function ($bagSellingEntryData) {
                $actionBtn = '';
                if ($bagSellingEntryData->status == 'running') {
                    $actionBtn .= '
                <a class="btnEdit" href="' . route('bagSellingEntry.edit', ['bagSellingEntry_id' => $bagSellingEntryData->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>
                ';
                    //   <button class="btn btn-danger" id="dltBagSellingEntry" data-id="' . $bagSellingEntryData->id . '">
                    // <i class="fas fa-trash-alt"></i>
                    // </button>
                } else {
                    $actionBtn .= '
                <a class="btnEdit" href="' . route('bagSelling.view', ['bagSellingEntry_id' => $bagSellingEntryData->id]) . '" >
                    <button class="btn btn-info" data-id="' . $bagSellingEntryData->id . '">
                        <i class="fas fa-eye"></i>
                    </button>
                 </a>
                ';
                }


                return $actionBtn;
            })
            ->rawColumns(['action', 'statusBtn'])
            ->make(true);
    }

    public function view($bagSellingEntry_id)
    {
        $bagSellingEntry = BagSellingEntry::with('bagSellingItem', 'supplier:id,name', 'bagSellingItem.group:id,name', 'bagSellingItem.brandBag:id,name')->find($bagSellingEntry_id);
        return view('admin.bag.bagSelling.view', compact('bagSellingEntry'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'challan_no' => 'required',
            'date_np' => 'required',
            'supplier_id' => 'required',
            'gp_no' => 'required',
            'lorry_no' => 'required',
            'do_no' => 'required',
            'rem' => 'required',

        ]);
        // return $request->date_np;
        $bagSellingEntry = new BagSellingEntry();
        $bagSellingEntry->challan_no = $request->challan_no;
        $bagSellingEntry->date = self::getEngDate($request->date_np);
        $bagSellingEntry->nepali_date = $request->date_np;
        $bagSellingEntry->supplier_id  = $request->supplier_id;
        $bagSellingEntry->gp_no = $request->gp_no;
        $bagSellingEntry->lorry_no = $request->lorry_no;
        $bagSellingEntry->do_no = $request->do_no;
        $bagSellingEntry->rem = $request->rem;
        $bagSellingEntry->save();
        $bagSellingEntry->load('supplier:id,name');

        return redirect()->route('bagSellingItem.index', ['id' => $bagSellingEntry->id]);

        // return view('admin.bag.bagSelling.createSalesItem', compact('bagSellingEntry', 'groups'));
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

    // public function convertNepaliDateToEnglishDate($nepaliDate)
    // {
    //     // Split the Nepali date into year, month, and day
    //     list($nepaliYear, $nepaliMonth, $nepaliDay) = explode('-', $nepaliDate);

    //     // Create a NepaliDate instance

    //     // Convert the Nepali date to an English (Gregorian) date
    //     $englishDate = $nepaliDateInstance->convertBsToAd($nepaliYear, $nepaliMonth, $nepaliDay);

    //     // Format the English date as "YYYY-MM-DD"
    //     $englishDateStr = sprintf('%04d-%02d-%02d', $englishDate['year'], $englishDate['month'], $englishDate['date']);

    //     return $englishDateStr;
    // }

    public function getBagBrand(Request $request)
    {
        $bagBundelStock = BagBundelStock::with('bagBrand:id,name')
            ->where('group_id', $request->group_id)
            ->select('bag_brand_id')
            ->distinct('bag_brand_id')
            ->get();
        return $bagBundelStock;
    }

    public function getBundleNo(Request $request)
    {
        $bundleNos = BagBundelStock::where('group_id', $request->group_id)
            ->where('bag_brand_id', $request->brandBrandId)
            ->where('status', 'stock')
            ->get(['id', 'bundle_no']);
        return $bundleNos;
    }

    public function getPcsWeightAvg(Request $request)
    {

        $pcsWeightAvg = BagBundelStock::where('bundle_no', $request->bundel_no)
            ->get(['qty_pcs', 'qty_in_kg', 'average_weight'])
            ->first();
        return  $pcsWeightAvg;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BagSellingEntry  $bagSellingEntry
     * @return \Illuminate\Http\Response
     */
    public function show(BagSellingEntry $bagSellingEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BagSellingEntry  $bagSellingEntry
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BagSellingEntry  $bagSellingEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bagSellingEntry_id)
    {
        $bagSellingEntry = BagSellingEntry::with('supplier:id,name')
            ->find($bagSellingEntry_id);
        //return  $bagSellingEntry->id;
        $groups = BagBundelStock::with('group')
            ->select('group_id')
            ->distinct('group_id')
            ->get();
        return view('admin.bag.bagSelling.createSalesItem', compact('bagSellingEntry', 'groups'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BagSellingEntry  $bagSellingEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(BagSellingEntry $bagSellingEntry)
    {
        //
    }
}
