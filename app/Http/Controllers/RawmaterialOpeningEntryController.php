<?php

namespace App\Http\Controllers;

use App\Models\RawmaterialOpeningEntry;
use App\Models\Godam;
use App\Models\DanaGroup;
use App\Models\RawmaterialOpeningItem;
use App\Models\RawMaterialStock;
use App\Models\DanaName;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class RawmaterialOpeningEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.rawmaterialOpening.index');
    }

    public function tableData()
    {
        $rawmaterialOpeningEntryDatas = RawmaterialOpeningEntry::with('godam:id,name')
            ->get(['id', 'opening_date', 'receipt_no', 'to_godam', 'status']);
        return DataTables::of($rawmaterialOpeningEntryDatas)
            ->addIndexColumn()
            ->addColumn('statusBtnn', function ($rawmaterialOpeningEntryData) {
                return '<span class="badge badge-pill badge-success">' . $rawmaterialOpeningEntryData->status . '</span>';
            })
            ->addColumn('action', function ($rawmaterialOpeningEntryData) {
                $actionBtn = '';
                if ($rawmaterialOpeningEntryData->status == 'running') {
                    $actionBtn .= '
                <a class="btnEdit" href="' . route('rawMaterialOpening.edit', ['rawMaterialOpening_id' => $rawmaterialOpeningEntryData->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>

                <button class="btn btn-danger" id="dltRawmaterialEntry" data-id="' . $rawmaterialOpeningEntryData->id . '">
                <i class="fas fa-trash-alt"></i>
            </button>

                ';
                } else {
                    $actionBtn .= '
                <button class="btn btn-info" id="see" data-id="' . $rawmaterialOpeningEntryData->id . '">
                    <i class="fas fa-eye"></i>
                </button>
                ';
                }


                return $actionBtn;
            })
            ->rawColumns(['action', 'statusBtnn'])
            ->make(true);
    }

    public function delete($openingRawmaterialEntry_id)
    {
        $openingRawmaterialEntry = RawmaterialOpeningEntry::with('items')->find($openingRawmaterialEntry_id);
        $openingRawmaterialEntry->delete();
    }

    public function edit($rawMaterialOpening_id)
    {
        $rawmaterialOpeningEntryData = RawmaterialOpeningEntry::with('godam')->find($rawMaterialOpening_id);
        // return $rawmaterialOpeningEntryData;
        $godams = Godam::where('status', 'active')->get(['id', 'name']);
        return view('admin.rawmaterialOpening.createOpeningEntry', compact('godams', 'rawmaterialOpeningEntryData'));
    }
    public function update(Request $request, $openingRawmaterialEntry_id)
    {

        $rawmaterialOpeningEntryData = RawmaterialOpeningEntry::find($openingRawmaterialEntry_id);
        $rawmaterialOpeningEntryData->opening_date = $request->date_np;
        $rawmaterialOpeningEntryData->receipt_no = $request->receipt_no;
        $rawmaterialOpeningEntryData->to_godam = $request->to_godam;
        $rawmaterialOpeningEntryData->remark = $request->remark;
        $rawmaterialOpeningEntryData->save();
        // return $rawmaterialOpeningEntryData;

        $rawmaterialOpeningEntry = $rawmaterialOpeningEntryData->load('godam:id,name');
        // return $rawmaterialOpeningEntry;
        $danaGroups = DanaGroup::where('status', 'active')->get(['id', 'name']);
        return view('admin.rawmaterialOpening.createOpeningItems', compact('rawmaterialOpeningEntry', 'danaGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $godams = Godam::where('status', 'active')->get(['id', 'name']);
        $rawmaterialOpeningEntryData = null;
        return view('admin.rawmaterialOpening.createOpeningEntry', compact('godams', 'rawmaterialOpeningEntryData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rawmaterialOpeningEntry = new RawmaterialOpeningEntry();
        $rawmaterialOpeningEntry->opening_date = $request->date_np;
        $rawmaterialOpeningEntry->receipt_no = $request->receipt_no;
        $rawmaterialOpeningEntry->to_godam = $request->to_godam;
        $rawmaterialOpeningEntry->remark = $request->remark ? $request->remark : 'null';
        $rawmaterialOpeningEntry->save();
        $rawmaterialOpeningEntry = $rawmaterialOpeningEntry->load('godam:id,name');
        //  return $rawmaterialOpeningEntry;

        $danaGroups = DanaGroup::where('status', 'active')->get(['id', 'name']);
        return view('admin.rawmaterialOpening.createOpeningItems', compact('rawmaterialOpeningEntry', 'danaGroups'));
    }

    public function saveEntire(Request $request)
    {

        try {
            DB::beginTransaction();
            $rawmaterialOpeningEntry = RawmaterialOpeningEntry::find($request->rawmaterial_opening_entry_id);
            $rawmaterialOpeningEntry->status = "completed";
            $rawmaterialOpeningEntry->save();
            $rawmaterialOpeningItems = RawmaterialOpeningItem::where('rawmaterial_opening_entry_id', $request->rawmaterial_opening_entry_id)
                ->get();
            //check if the stock aleady exists
            if ($rawmaterialOpeningItems) {
                foreach ($rawmaterialOpeningItems as $rawmaterialOpeningItem) {
                    $rawmaterialStock = RawMaterialStock::where('godam_id', $rawmaterialOpeningEntry->to_godam)
                        ->where('dana_group_id', $rawmaterialOpeningItem->dana_group_id)
                        ->where('dana_name_id', $rawmaterialOpeningItem->dana_name_id)->first();

                    if ($rawmaterialStock) {
                        $rawmaterialStock->quantity += $rawmaterialOpeningItem->qty_in_kg;
                        $rawmaterialStock->save();
                    } else {
                        $rawmaterialStock = new RawMaterialStock();
                        $rawmaterialStock->godam_id = $rawmaterialOpeningEntry->to_godam;
                        $rawmaterialStock->dana_group_id = $rawmaterialOpeningItem->dana_group_id;
                        $rawmaterialStock->dana_name_id = $rawmaterialOpeningItem->dana_name_id;
                        $rawmaterialStock->quantity = $rawmaterialOpeningItem->qty_in_kg;
                        $rawmaterialStock->save();
                    }
                }
            } else {
                return false;
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }
    public function getDanaGroupDanaName($danaGroup_id)
    {
        $danaName = DanaName::where('dana_group_id', $danaGroup_id)->get(['id', 'name']);
        return $danaName;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\rawmaterialOpeningEntry  $rawmaterialOpeningEntry
     * @return \Illuminate\Http\Response
     */
    public function show(rawmaterialOpeningEntry $rawmaterialOpeningEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\rawmaterialOpeningEntry  $rawmaterialOpeningEntry
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\rawmaterialOpeningEntry  $rawmaterialOpeningEntry
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\rawmaterialOpeningEntry  $rawmaterialOpeningEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(rawmaterialOpeningEntry $rawmaterialOpeningEntry)
    {
        //
    }
}
