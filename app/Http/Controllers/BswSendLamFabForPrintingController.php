<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BswLamFabForPrintingEntry;
use App\Models\ProcessingStep;
use App\Models\Shift;
use App\Models\Godam;
use App\Models\Group;
use App\Models\BagBrand;
use App\Models\ProcessingSubcat;
use App\Models\FabricStock;
use App\Models\PrintedFabric;
use App\Models\AutoLoadItemStock;
use Yajra\DataTables\Facades\DataTables;
use DB;

class BswSendLamFabForPrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //   $bswLamFabForPrintingEntryDatas=BswLamFabForPrintingEntry::with(['plantType:id,name','plantName:id,name','shift:id,name','godam:id,name','group:id,name','bagBrand:id,name'])
        // ->get(['id','receipt_no','date','plant_type_id','plant_name_id','shift_id','godam_id','group_id','bag_brands_id','status']);
        // return $bswLamFabForPrintingEntryDatas;
       return view('admin.bsw.index');
    }

    public function getBrandBag(Request $request){
        $bagBrands=BagBrand::where('group_id',$request->group_id)->get(['id','name']);
        return $bagBrands;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = BswLamFabForPrintingEntry::latest()->value('id');
        if($id){
            $receipt_no = "BLFP"."-".getNepaliDate(date('Y-m-d'))."-".$id+1;
        }else{
            $receipt_no = "BLFP"."-".getNepaliDate(date('Y-m-d'))."-".'1';
        }

        $nepaliDate = getNepaliDate(date('Y-m-d'));
        $plantTypes = ProcessingStep::where('status','1')->get(['id','name']);
        $shifts=Shift::where('status','active')->get(['id','name']);
        $godams=Godam::where('status','active')->get(['id','name']);
        $groups=Group::where('status','active')->get(['id','name']);
        $bswLamFabForPrintingEntryData =null;
        //return $laminatedFabrics;
        //$bagBrands=BagBrand::where('status','active')->get(['id','name']);
        return view('admin.bsw.create',compact(['receipt_no','nepaliDate','plantTypes','shifts','godams','groups','bswLamFabForPrintingEntryData']));
    }

    public function saveEntry(Request $request){
         $request->validate([
        'receipt_no'=>'required',
        'date_np'=>'required',
        'plant_type_id'=>'required',
        'plant_name_id'=>'required',
        'shift_id'=>'required',
        'godam_id'=>'required',
        'group_name_id'=>'required',
        'brand_bag_id'=>'required',
    ]);
        $bswLamFabForPrintingEntry=new BswLamFabForPrintingEntry();
        $bswLamFabForPrintingEntry->receipt_no =$request->receipt_no;
        $bswLamFabForPrintingEntry->date=$request->date_np;
        $bswLamFabForPrintingEntry->plant_type_id =$request->plant_type_id;
        $bswLamFabForPrintingEntry->plant_name_id =$request->plant_name_id;
        $bswLamFabForPrintingEntry->shift_id =$request->shift_id;
        $bswLamFabForPrintingEntry->godam_id =$request->godam_id;
        $bswLamFabForPrintingEntry->group_id =$request->group_name_id;
        $bswLamFabForPrintingEntry->bag_brands_id =$request->brand_bag_id;
        $bswLamFabForPrintingEntry->save();
        $id=$bswLamFabForPrintingEntry->id;
        return redirect()->route('BswLamFabSendForPrinting.createItems',['id'=>$id]);
        //self::createItems($bswLamFabForPrintingEntry);

    }

    public function createItems( $id){
        $bswLamFabForPrintingEntry= BswLamFabForPrintingEntry::with(['plantType:id,name','plantName:id,name','shift:id,name','godam:id,name','group:id,name','bagBrand:id,name'])->find($id);
        $laminatedFabrics=FabricStock::with('fabricgroup')->where('is_laminated','true')
        ->where('godam_id',$bswLamFabForPrintingEntry->godam_id)
        ->get();
        $uniqueFabrics = $laminatedFabrics->unique('name')->values()->all();
        $printedFabrics=PrintedFabric::where('status','active')->get();

        //godam
        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
        $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();
        return view('admin.bsw.lamFabricSendForPrinting',compact(['bswLamFabForPrintingEntry','uniqueFabrics','printedFabrics','godams']));
    }

  public function lamFabData(Request $request) {
    if($request->lamFabName){
        $tableDatas = FabricStock::where('name', $request->lamFabName)->get();
        return DataTables::of($tableDatas)
        ->addIndexColumn()
        ->addColumn('action', function ($tableData) {
            $actionBtn = '<button class="btn btn-danger" id="lamsendEntry"
                data-id="' . $tableData->id . '"
                data-name="' . $tableData->name . '"
                data-gross_wt="' . $tableData->gross_wt . '"
                data-roll_no="' . $tableData->roll_no . '"
                data-fabric_id="' . $tableData->fabric_id . '"
                data-net_wt="' . $tableData->net_wt . '"
                data-meter="' . $tableData->meter . '"
                data-gram_wt="' . $tableData->gram_wt . '"
                data-average="' . $tableData->average_wt . '"
            >Send</button>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }}

     public function edit($id)
    {
        $bswLamFabForPrintingEntry= BswLamFabForPrintingEntry::with(['plantType:id,name','plantName:id,name','shift:id,name','godam:id,name','group:id,name','bagBrand:id,name'])->find($id);
        $laminatedFabrics=FabricStock::with('fabricgroup')->where('is_laminated','true')
        ->where('godam_id',$bswLamFabForPrintingEntry->godam_id )
        ->get();
        $uniqueFabrics = $laminatedFabrics->unique('name')->values()->all();
        $printedFabrics=PrintedFabric::where('status','active')->get();
        $godams=AutoLoadItemStock::with(['fromGodam'=>function($query){
            $query->select('id','name');
        }])
        ->select('from_godam_id')
        ->distinct()
        ->get();
        // return  $printedFabrics;
        return view('admin.bsw.lamFabricSendForPrinting',compact(['bswLamFabForPrintingEntry','uniqueFabrics','printedFabrics','godams']));
    }

    public function yajraDatatables(){
        $bswLamFabForPrintingEntryDatas=BswLamFabForPrintingEntry::with(['plantType:id,name','plantName:id,name','shift:id,name','godam:id,name','group:id,name','bagBrand:id,name'])
        ->get(['id','receipt_no','date','plant_type_id','plant_name_id','shift_id','godam_id','group_id','bag_brands_id','status']);

        return DataTables::of($bswLamFabForPrintingEntryDatas)
            ->addIndexColumn()
            ->addColumn('statusBtn', function ($bswLamFabForPrintingEntryData) {
                return '<span class="badge badge-pill badge-success">'.$bswLamFabForPrintingEntryData->status.'</span>';
            })
            ->addColumn('action', function ($bswLamFabForPrintingEntryData) {
                $actionBtn='';
                if($bswLamFabForPrintingEntryData->status == 'running'){
                    $actionBtn .= '
                <a class="btnEdit" href="' . route('BswLamFabSendForPrinting.edit', ['id' => $bswLamFabForPrintingEntryData->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>

                <button class="btn btn-danger" id="dltBagSellingEntry" data-id="'.$bswLamFabForPrintingEntryData->id.'">
                <i class="fas fa-trash-alt"></i>
            </button>

                ';
                }
                else{
                    $actionBtn .= '
                <button class="btn btn-info" id="dltBagSellingEntry" data-id="'.$bswLamFabForPrintingEntryData->id.'">
                    <i class="fas fa-eye"></i>
                </button>
                '
                ;
                }


                return $actionBtn;
            })
            ->rawColumns(['action','statusBtn'])
            ->make(true);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
