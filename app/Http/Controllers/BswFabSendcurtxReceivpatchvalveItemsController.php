<?php

namespace App\Http\Controllers;

use App\Models\BswFabSendcurtxReceivpatchvalveItems;
use App\Models\BswFabSendcurtxReceivpatchvalveEntry;
use App\Models\FabricStock;
use App\Models\BswLamPrintedFabricStock;
use Illuminate\Http\Request;
 use Yajra\DataTables\Facades\DataTables;
use DB;

class BswFabSendcurtxReceivpatchvalveItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createItems(Request $request)
    {

        $bswFabSendcurtxReceivpatchvalveEntryData=BswFabSendcurtxReceivpatchvalveEntry::with(['plantType:id,name','plantName:id,name','shift:id,name','godam:id,name'])
        ->find($request->bswFabSendcurtxReceivpatchvalveEntry_id);
        //return $bswFabSendcurtxReceivpatchvalveEntryData;
        return view('admin.bsw.patch_valve.createItems',compact('bswFabSendcurtxReceivpatchvalveEntryData'));
    }

    public function edit($id){
  $bswFabSendcurtxReceivpatchvalveEntryData=BswFabSendcurtxReceivpatchvalveEntry::with(['plantType:id,name','plantName:id,name','shift:id,name','godam:id,name'])
        ->find($id);
        //return $bswFabSendcurtxReceivpatchvalveEntryData;
        return view('admin.bsw.patch_valve.createItems',compact('bswFabSendcurtxReceivpatchvalveEntryData'));
    }

    public function getFabricName1(Request $request){
        if($request->fabricType == "unlam"){
            $unlamFabrics=FabricStock::where('is_laminated','false')
            ->select('name')
            ->distinct('name')
            ->get();
            return $unlamFabrics;
        }elseif($request->fabricType == "lam"){
            $laminatedFabrics=FabricStock::where('is_laminated','true')
            ->select('name')
            ->distinct('name')
            ->get();
            return $laminatedFabrics;
        }else{
            $uniqueFabrics = BswLamPrintedFabricStock::join('printed_fabrics', 'bsw_lam_printed_fabric_stocks.printed_fabric_id', '=', 'printed_fabrics.id')
            ->select('printed_fabrics.name')
            ->distinct('printed_fabrics.name')
            ->get();
            return $uniqueFabrics;
        }
    }
    public function fabData(Request $request){

        $fabricType=strtolower($request->fabType);
       if ($fabricType == 'unlam') {
            $tableDatas = FabricStock::where('name',$request->fabName)
            ->where('is_laminated','false')
            ->get();
        } elseif ($fabricType == 'lam') {
            $tableDatas = FabricStock::where('name',$request->fabName)
            ->where('is_laminated','true')
            ->get();
        } elseif($fabricType == 'printed'){
            $tableDatas = DB::table('bsw_lam_printed_fabric_stocks as stock')
            ->join('printed_fabrics', 'stock.printed_fabric_id', '=', 'printed_fabrics.id')
            ->where('printed_fabrics.name',$request->fabName)
            ->select('stock.id as id',
            'printed_fabrics.name as name',
            'stock.gross_weight as gross_wt',
            'stock.roll_no as roll_no',
            'stock.net_weight as net_wt',
            'stock.meter as meter',
            'stock.average as average_wt',
            'stock.gram_weight as gram_wt')
            ->get();
        }
        return DataTables::of($tableDatas)
            ->addIndexColumn()
            ->addColumn('action', function ($tableData) {
                $actionBtn = '<button class="btn btn-danger" id="lamsendEntry"
                    data-id="' . $tableData->id . '"
                    data-name="' . $tableData->name . '"
                    data-gross_wt="' . $tableData->gross_wt . '"
                    data-roll_no="' . $tableData->roll_no . '"
                    data-net_wt="' . $tableData->net_wt . '"
                    data-meter="' . $tableData->meter . '"
                    data-gram_wt="' . $tableData->gram_wt . '"
                    data-average="' . $tableData->average_wt . '"
                >Send</button>';
                return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getFabricName(Request $request)
    {
        $PAGINATE_NO=50;
        // search text
        $searchText = $request->input('query');
        $fabricType =$request->input('fabricType');

        if($fabricType == "unlam"){
            $unlamFabrics=DB::table('fabric_stock')
            ->where('is_laminated','false')
            ->select('fabric_stock.name as id', 'fabric_stock.name as text')
            ->distinct();
            if ($searchText !== null) {
                $unlamFabrics->where('name', 'like', '%' . $searchText . '%');
            }
            $items = $unlamFabrics->paginate($PAGINATE_NO);
            return response()->json([
                'data' => $items->items(),
                'next_page_url' => $items->nextPageUrl(),
            ]);

        }elseif($fabricType == "lam"){
            $lamFabrics=DB::table('fabric_stock')
            ->where('is_laminated','true')
            ->select('fabric_stock.name as id', 'fabric_stock.name as text')
            ->distinct();
            if ($searchText !== null) {
                $lamFabrics->where('name', 'like', '%' . $searchText . '%');
            }
            $items = $lamFabrics->paginate($PAGINATE_NO);
            return response()->json([
                'data' => $items->items(),
                'next_page_url' => $items->nextPageUrl(),
            ]);
        }elseif($fabricType == "printed"){
            $uniqueFabrics = DB::table('bsw_lam_printed_fabric_stocks')
            ->join('printed_fabrics', 'bsw_lam_printed_fabric_stocks.printed_fabric_id', '=', 'printed_fabrics.id')
            ->select('printed_fabrics.name as id', 'printed_fabrics.name as text')
            ->distinct();
            if ($searchText !== null) {
                $uniqueFabrics->where('printed_fabrics.name', 'like', '%' . $searchText . '%');
            }
            $items = $uniqueFabrics->paginate($PAGINATE_NO);
            return response()->json([
                'data' => $items->items(),
                'next_page_url' => $items->nextPageUrl(),
            ]);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BswFabSendcurtxReceivpatchvalveItems  $bswFabSendcurtxReceivpatchvalveItems
     * @return \Illuminate\Http\Response
     */
    public function show(BswFabSendcurtxReceivpatchvalveItems $bswFabSendcurtxReceivpatchvalveItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BswFabSendcurtxReceivpatchvalveItems  $bswFabSendcurtxReceivpatchvalveItems
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BswFabSendcurtxReceivpatchvalveItems  $bswFabSendcurtxReceivpatchvalveItems
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BswFabSendcurtxReceivpatchvalveItems $bswFabSendcurtxReceivpatchvalveItems)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BswFabSendcurtxReceivpatchvalveItems  $bswFabSendcurtxReceivpatchvalveItems
     * @return \Illuminate\Http\Response
     */
    public function destroy(BswFabSendcurtxReceivpatchvalveItems $bswFabSendcurtxReceivpatchvalveItems)
    {
        //
    }
}
