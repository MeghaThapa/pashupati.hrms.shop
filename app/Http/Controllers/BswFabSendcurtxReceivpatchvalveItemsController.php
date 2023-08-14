<?php

namespace App\Http\Controllers;

use App\Models\BswFabSendcurtxReceivpatchvalveItems;
use App\Models\BswFabSendcurtxReceivpatchvalveEntry;
use App\Models\FabricStock;
use App\Models\Fabric;
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

    // public function getFabricName1(Request $request){
    //     if($request->fabricType == "unlam"){
    //         $unlamFabrics=FabricStock::where('is_laminated','false')
    //         ->select('name')
    //         ->distinct('name')
    //         ->get();
    //         return $unlamFabrics;
    //     }elseif($request->fabricType == "lam"){
    //         $laminatedFabrics=FabricStock::where('is_laminated','true')
    //         ->select('name')
    //         ->distinct('name')
    //         ->get();
    //         return $laminatedFabrics;
    //     }else{
    //         $uniqueFabrics = BswLamPrintedFabricStock::join('printed_fabrics', 'bsw_lam_printed_fabric_stocks.printed_fabric_id', '=', 'printed_fabrics.id')
    //         ->select('printed_fabrics.name')
    //         ->distinct('printed_fabrics.name')
    //         ->get();
    //         return $uniqueFabrics;
    //     }
    // }
    public function fabData(Request $request){
        $tableDatas = [];
        $fabricType=strtolower($request->fabType);
       if ($fabricType == 'unlam') {
                $tableDatas =DB::table('fabric_stock as stock')
                ->join('fabrics','stock.fabric_id','=','fabrics.id')
                ->where('stock.is_laminated','false')
                ->where('fabrics.name',$request->fabName)
                ->select(
                    'stock.id as id',
                    'fabrics.name as name',
                    'fabrics.id as fabric_id',
                    'fabrics.is_laminated as is_laminated',
                    'stock.gross_wt as gross_wt',
                    'stock.roll_no as roll_no',
                    'stock.net_wt as net_wt',
                    'stock.meter as meter',
                    'stock.average_wt as average_wt',
                    'stock.gram_wt as gram_wt'
                )->get();

        } elseif ($fabricType == 'lam') {
             $tableDatas =DB::table('fabric_stock as stock')
                ->join('fabrics','stock.fabric_id','=','fabrics.id')
                ->where('stock.is_laminated','true')
                ->where('fabrics.name',$request->fabName)
                ->select(
                    'stock.id as id',
                    'fabrics.name as name',
                    'fabrics.id as fabric_id',
                    'fabrics.is_laminated as is_laminated',
                    'stock.gross_wt as gross_wt',
                    'stock.roll_no as roll_no',
                    'stock.net_wt as net_wt',
                    'stock.meter as meter',
                    'stock.average_wt as average_wt',
                    'stock.gram_wt as gram_wt'
                )->get();
        } elseif($fabricType == 'printed'){
            $tableDatas = DB::table('bsw_lam_printed_fabric_stocks as stock')
            ->join('printed_fabrics', 'stock.printed_fabric_id', '=', 'printed_fabrics.id')
            ->where('printed_fabrics.name',$request->fabName)
            ->select('stock.id as id',
            'printed_fabrics.id as fabric_id',
            'printed_fabrics.name as name',
            'fabrics.is_laminated as is_laminated',
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
                    data-name="' . $tableData->name . '"
                    data-fabric_id="' . $tableData->fabric_id. '"
                    data-is_laminated="' . $tableData->is_laminated . '"
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
    //megha
    public function lamFabData(Request $request){
        $bswFabSendcurtxReceivpatchvalveItems =BswFabSendcurtxReceivpatchvalveItems::where('entry_id',$request->bsw_lam_fabcurtexToPatchVal_entry_id)
        ->get();
        return $bswFabSendcurtxReceivpatchvalveItems;
    }
    public function store(Request $request){
        $request->validate([
            'is_laminated'=>'required',
            'fabric_id'=>'required',
            'bswcurtexto_patchVal_Entry_id'=>'required',
            // 'name' =>'required',
            'roll_no' =>'required',
            'gross_wt'=>'required',
            'net_wt'=>'required',
            'meter'=>'required',
            'average'=>'required',
            'gram_wt'=>'required',
        ]);
        if($request->is_laminated == 'true' || $request->is_laminated == 'false')
        {
            $fabricName=Fabric::find($request->fabric_id)->name;
            $bswFabSendcurtxReceivpatchvalveItem = new BswFabSendcurtxReceivpatchvalveItems();
            $bswFabSendcurtxReceivpatchvalveItem->entry_id =$request->bswcurtexto_patchVal_Entry_id;
            $bswFabSendcurtxReceivpatchvalveItem->name =  $fabricName;
            $bswFabSendcurtxReceivpatchvalveItem->roll_no = $request->roll_no;
            $bswFabSendcurtxReceivpatchvalveItem->gross_wt = $request->gross_wt;
            $bswFabSendcurtxReceivpatchvalveItem->net_wt = $request->net_wt;
            $bswFabSendcurtxReceivpatchvalveItem->meter = $request->meter;
            $bswFabSendcurtxReceivpatchvalveItem->average = $request->average;
            $bswFabSendcurtxReceivpatchvalveItem->gram_wt =$request->gram_wt;
            $bswFabSendcurtxReceivpatchvalveItem->save();
        }else{
            $fabricName=PrintedFabric::find($request->fabric_id)->name;
            $bswFabSendcurtxReceivpatchvalveItem = new BswFabSendcurtxReceivpatchvalveItems();
            $bswFabSendcurtxReceivpatchvalveItem->entry_id =$request->bswcurtexto_patchVal_Entry_id;
            $bswFabSendcurtxReceivpatchvalveItem->name =  $fabricName;
            $bswFabSendcurtxReceivpatchvalveItem->roll_no = $request->roll_no;
            $bswFabSendcurtxReceivpatchvalveItem->gross_wt = $request->gross_wt;
            $bswFabSendcurtxReceivpatchvalveItem->net_wt = $request->net_wt;
            $bswFabSendcurtxReceivpatchvalveItem->meter = $request->meter;
            $bswFabSendcurtxReceivpatchvalveItem->average = $request->average;
            $bswFabSendcurtxReceivpatchvalveItem->gram_wt =$request->gram_wt;
            $bswFabSendcurtxReceivpatchvalveItem->save();
        }
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
