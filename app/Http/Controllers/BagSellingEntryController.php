<?php

namespace App\Http\Controllers;

use App\Models\BagSellingEntry;
use App\Models\Supplier;
use App\Models\BagBundelStock;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class BagSellingEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  $bagSellingEntryData=BagSellingEntry::with('supplier')
        // ->where('id','1')
        // ->get(['challan_no','date','nepali_date','supplier_id','gp_no','lorry_no','do_no','rem'])
        // ->first();
        // return $bagSellingEntryData;
        return view('admin.bag.bagSelling.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers=Supplier::where('status','1')->get();
        //return  $suppliers;
        $nepaliDate = getNepaliDate(date('Y-m-d'));
        $date = date('Y-m-d');
         $bagSellingEntryData=null;
        return view('admin.bag.bagSelling.createEntry',compact('nepaliDate','date','suppliers','bagSellingEntryData'));
    }
     public function edit($bagSellingEntry_id)
    {
          $suppliers=Supplier::where('status','1')->get();
        $bagSellingEntryData=BagSellingEntry::with('supplier')
        ->where('id',$bagSellingEntry_id)
        ->get(['id','challan_no','date','nepali_date','supplier_id','gp_no','lorry_no','do_no','rem'])
        ->first();
         return view('admin.bag.bagSelling.createEntry',compact('bagSellingEntryData','suppliers'));

    }
    public function bagSellingYajraDatatables(){
        $bagSellingEntryDatas=BagSellingEntry::with('supplier:id,name')
        ->get(['id','challan_no','date','nepali_date','supplier_id','gp_no','lorry_no','do_no','rem','status']);

        return DataTables::of($bagSellingEntryDatas)
            ->addIndexColumn()
            ->addColumn('statusBtn', function ($bagSellingEntryData) {
                return '<span class="badge badge-pill badge-success">'.$bagSellingEntryData->status.'</span>';
            })
            ->addColumn('action', function ($bagSellingEntryData) {
                $actionBtn='';
                if($bagSellingEntryData->status == 'running'){
                    $actionBtn .= '
                <a class="btnEdit" href="' . route('bagSellingEntry.edit', ['bagSellingEntry_id' => $bagSellingEntryData->id]) . '" >
                    <button class="btn btn-primary">
                        <i class="fas fa-edit fa-lg"></i>
                    </button>
                </a>

                <button class="btn btn-danger" id="dltBagSellingEntry" data-id="'.$bagSellingEntryData->id.'">
                <i class="fas fa-trash-alt"></i>
            </button>

                ';
                }
                else{
                    $actionBtn .= '
                <button class="btn btn-info" id="dltBagSellingEntry" data-id="'.$bagSellingEntryData->id.'">
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

    public function store(Request $request){
        $request->validate([
                'challan_no'=>'required',
                'date' =>'required',
                'date_np'=>'required',
                'supplier_id'=>'required',
                'gp_no'=>'required',
                'lorry_no'=>'required',
                'do_no'=>'required',
                'rem'=>'required',
        ]);
        $bagSellingEntry=new BagSellingEntry();
        $bagSellingEntry->challan_no = $request->challan_no;
        $bagSellingEntry->date = $request->date;
        $bagSellingEntry->nepali_date = $request->date_np;
        $bagSellingEntry->supplier_id  = $request->supplier_id;
        $bagSellingEntry->gp_no = $request->gp_no;
        $bagSellingEntry->lorry_no = $request->lorry_no;
        $bagSellingEntry->do_no = $request->do_no;
        $bagSellingEntry->rem = $request->rem;
        $bagSellingEntry->save();
        $bagSellingEntry->load('supplier:id,name');
        //return $bagSellingEntryData;
        $groups=BagBundelStock::with('group')
        ->select('group_id')
        ->distinct('group_id')
        ->get()
        ;

        //return $groups;
        return view('admin.bag.bagSelling.createSalesItem',compact('bagSellingEntry','groups'));
    }

    public function getBagBrand(Request $request){
        $bagBundelStock= BagBundelStock::with('bagBrand:id,name')
        ->where('group_id',$request->group_id)
        ->select('bag_brand_id')
        ->distinct('bag_brand_id')
        ->get();
        return $bagBundelStock;
    }

    public function getBundleNo(Request $request){
         $bundleNos= BagBundelStock::where('group_id',$request->group_id)
        ->where('bag_brand_id',$request->brandBrandId)
        ->get(['id','bundle_no']);
        return $bundleNos;
    }

    public function getPcsWeightAvg(Request $request){

        $pcsWeightAvg =BagBundelStock::where('bundle_no',$request->bundel_no)
        ->get(['qty_pcs','qty_in_kg','average_weight'])
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
         $bagSellingEntry=BagSellingEntry::with('supplier:id,name')
       ->find($bagSellingEntry_id);
       //return  $bagSellingEntry->id;
       $groups=BagBundelStock::with('group')
        ->select('group_id')
        ->distinct('group_id')
        ->get()
        ;
     return view('admin.bag.bagSelling.createSalesItem',compact('bagSellingEntry','groups'));
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
