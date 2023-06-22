<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\DanaGroup;
use App\Models\DanaName;
use App\Models\Godam;
use App\Models\RawMaterialStock;
use App\Models\RawMaterial;
use App\Models\RawMaterialItem;
use App\Models\Setupstorein;
use App\Models\Department;
use App\Models\Storein;
use App\Models\Supplier;
use App\Models\StoreinType;
use Illuminate\Http\Request;
use DateTime;
use DB;
use Yajra\DataTables\Facades\DataTables;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.rawMaterial.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function dataTable()
    {
        $rawMaterial = DB::table('raw_materials')
        ->leftJoin('suppliers', 'raw_materials.supplier_id', '=', 'suppliers.id')
        ->leftJoin('storein_types', 'raw_materials.storein_type_id', '=', 'storein_types.id')
        ->leftJoin('godam as toGodam', 'raw_materials.to_godam_id', '=', 'toGodam.id')
        ->leftJoin('godam as fromGodam', 'raw_materials.from_godam_id', '=', 'fromGodam.id')
        ->leftJoin('raw_material_items', 'raw_material_items.raw_material_id', '=', 'raw_materials.id')
        ->select(
            'raw_materials.id',
            'raw_materials.date',
            'raw_materials.receipt_no',
            'suppliers.name as supplier_name',
            'raw_materials.pp_no',
            'storein_types.name as storein_type_name',
            'toGodam.name as to_godam_name',
            'fromGodam.name as from_godam_name',
        )
        ->selectSub(function ($query) {
            $query->from('raw_material_items')
                ->selectRaw('SUM(raw_material_items.quantity)')
                ->whereColumn('raw_material_items.raw_material_id', 'raw_materials.id');
        }, 'raw_material_item_quantity')
        ->groupBy(
            'raw_materials.id',
            'raw_materials.date',
            'raw_materials.receipt_no',
            'suppliers.name',
            'raw_materials.pp_no',
            'storein_types.name',
            'toGodam.name',
            'fromGodam.name'
        )
        ->orderBy('raw_materials.created_at','DESC')
        ->get();

        return DataTables::of($rawMaterial)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '
                <a class="btn btn-sm btn-primary btnEdit" href="' . route('rawMaterial.edit', ["rawMaterial_id" => $row->id]) . '" >
                <i class="fas fa-edit fa-lg"></i>
                </a>
                 <button type="button" id="rawMaterialDeleteBtn" class="btnEdit btn btn-sm btn-danger"  data-id="'.$row->id.'">
                <i class="fas fa-trash fa-lg"></i>
                </button>
                ';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function delete($rawMaterial_id){
         try{
         DB::beginTransaction();
        $rawMaterial = RawMaterial::find($rawMaterial_id);
        $rawMaterialItems = RawMaterialItem::where('raw_material_id',$rawMaterial_id)->get();
        $fromGodamCheckBool = $rawMaterial->from_godam_id?true:false;

        if($rawMaterialItems && count($rawMaterialItems) !=0){
            foreach($rawMaterialItems as $item){
                $toGodamStock=RawMaterialStock::where('godam_id',$rawMaterial->to_godam_id)
                ->where('dana_group_id',$item->dana_group_id)
                ->where('dana_name_id',$item->dana_name_id)
                ->first();
                if(!$toGodamStock){
                    return response()->json([
                        'message'=>'some items of this raw material have already been transfered '
                    ],500);
                }

                if($fromGodamCheckBool && $fromGodamCheckBool===true){
                    $fromGodamStock=RawMaterialStock::where('godam_id',$rawMaterial->from_godam_id)
                    ->where('dana_group_id',$item->dana_group_id)
                    ->where('dana_name_id',$item->dana_name_id)
                    ->first();
                    if($fromGodamStock){
                        $fromGodamStock->quantity += $item->quantity;
                        $fromGodamStock->save();
                    }
                    else{
                        $newGodamStock= new RawMaterialStock();
                        $newGodamStock->godam_id =$rawMaterial->from_godam_id;
                        $newGodamStock->dana_group_id =$item->dana_group_id;
                        $newGodamStock->dana_name_id =$item->dana_name_id;
                        $newGodamStock->quantity =$item->quantity;
                        $newGodamStock->save();
                    }
                }
                    $toGodamStock->quantity -= $item->quantity;
                    if($toGodamStock->quantity <=0){
                        $toGodamStock->delete();
                    }else{
                        $toGodamStock->save();
                    }

            }
        }
        $rawMaterial->delete();
       DB::commit();
         }catch(Exception $e){
                DB::rollBack();
                return $e;
        }

    }

    public function create()
    {
        $receipt_no = AppHelper::getRawMaterialReceiptNo();
        $suppliers = Supplier::get(['id','name']);
        $storeinTypes = StoreinType::get(['id','name']);

        $fromGodams = DB::table('raw_material_stocks')
        ->join('godam','godam.id','=','raw_material_stocks.godam_id')
        ->select('godam.id','godam.name')
        ->distinct('godam.name','godam.id')
        ->get();

        $godams = Godam::get(['id','name']);
        $rawMaterial = null;
        return view('admin.rawMaterial.create', compact('suppliers','fromGodams','storeinTypes', 'godams', 'receipt_no', 'rawMaterial'));
    }
    public function edit($rawMaterial_id)
    {
        // $suppliers = Supplier::get(['id','name']);
        // $storeinTypes = StoreinType::get(['id','name']);
        // $godams = Godam::get(['id','name']);

        // $fromGodams = DB::table('raw_material_stocks')
        // ->join('godam','godam.id','=','raw_material_stocks.godam_id')
        // ->select('godam.id','godam.name')
        // ->distinct('godam.name','godam.id')
        // ->get();

        // return view('admin.rawMaterial.create', compact('suppliers','fromGodams', 'storeinTypes', 'godams', 'rawMaterial'));
        $rawMaterial = RawMaterial::find($rawMaterial_id);
        return redirect()->route('rawMaterial.createRawMaterialItems', ['rawMaterial_id' => $rawMaterial->id]);
    }
    public function update(Request $request, $rawMaterial_id)
    {
        $requestStoreinTypeName = self::getTypeNameFromId($request->Type_id);
       // return $requestStoreinTypeName;
       $validator = $request->validate([
        'supplier_id' => $requestStoreinTypeName === 'local' || $requestStoreinTypeName === 'import'  ? 'required' : '',
        'date' => 'required|date',
        'Type_id' => 'required',
        'to_godam_id' => 'required',
        'Receipt_no' => $requestStoreinTypeName === 'local' ?'required':'',
        'from_godam_id' => $requestStoreinTypeName === 'godam' ? 'required' : '',
        'challan_no' => $requestStoreinTypeName === 'godam' ? 'required' : '',
        'gp_no' => $requestStoreinTypeName === 'godam' ? 'required' : '',
        'bill_no' => $requestStoreinTypeName === 'local' ? 'required' : '',
        'pp_no' => $requestStoreinTypeName === 'import' ? 'required' : '',
        ]);
       //return $requestStoreinTypeName;
        if (strtolower($requestStoreinTypeName) === 'godam' && $request->to_godam_id === $request->from_godam_id) {
            return back()->withErrors('From Godam and To Godam cannot be similar');
        }
        $rawMaterial = RawMaterial::find($rawMaterial_id);
        $rawMaterial->supplier_id = $request->supplier_id;
        $rawMaterial->date = $request->date;
        $rawMaterial->pp_no = $request->pp_no;
        $rawMaterial->storein_type_id = $request->Type_id;
        $rawMaterial->from_godam_id = $request->from_godam_id ? $request->from_godam_id : null;
        $rawMaterial->challan_no = $request->challan_no ? $request->challan_no : null;
        $rawMaterial->gp_no = $request->gp_no ? $request->gp_no : null;
        $rawMaterial->to_godam_id = $request->to_godam_id;
        $rawMaterial->remark = $request->remarks;
        $rawMaterial->receipt_no = $request->Receipt_no;
        $rawMaterial->status = 'pending';
        $rawMaterial->save();
        return redirect()->route('rawMaterial.createRawMaterialItems', ['rawMaterial_id' => $rawMaterial->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTypeNameFromId($storein_type_id)
    {
        return Setupstorein::where('id', $storein_type_id)->first()->name;
    }



    public function store(Request $request)
    {

        $requestStoreinTypeName = self::getTypeNameFromId($request->Type_id);
        $validator = $request->validate([
            'supplier_id' => $requestStoreinTypeName === 'local' || $requestStoreinTypeName === 'import'  ? 'required' : '',
            'date' => 'required|date',
            'Type_id' => 'required',
            'to_godam_id' => 'required',
            'Receipt_no' => $requestStoreinTypeName === 'local' ?'required':'',
            'from_godam_id' => $requestStoreinTypeName === 'godam' ? 'required' : '',
            'challan_no' => $requestStoreinTypeName === 'godam' ? 'required' : '',
            'gp_no' => $requestStoreinTypeName === 'godam' ? 'required' : '',
            'bill_no' => $requestStoreinTypeName === 'local' ? 'required' : '',
            'pp_no' => $requestStoreinTypeName === 'import' ? 'required' : '',
        ]);

       //return $requestStoreinTypeName;
        if (strtolower($requestStoreinTypeName) === 'godam' && $request->to_godam_id === $request->from_godam_id) {
            return back()->withErrors('From Godam and To Godam cannot be similar');
        }

        $rawMaterial = new RawMaterial();
        $rawMaterial->supplier_id = $request->supplier_id;
        $rawMaterial->date = $request->date;
        $rawMaterial->pp_no = $request->pp_no?? null;
        $rawMaterial->bill_no = $request->bill_no?? null;
        $rawMaterial->storein_type_id = $request->Type_id;
        $rawMaterial->from_godam_id = $request->from_godam_id?? null;
        $rawMaterial->challan_no = $request->challan_no ?? null;
        $rawMaterial->gp_no = $request->gp_no ?? null;
        $rawMaterial->to_godam_id = $request->to_godam_id;
        $rawMaterial->remark = $request->remarks;
        $rawMaterial->receipt_no = $request->Receipt_no;
        $rawMaterial->status = 'pending';
        $rawMaterial->save();

        return redirect()->route('rawMaterial.createRawMaterialItems', ['rawMaterial_id' => $rawMaterial->id]);
    }


    public function createRawMaterialItems($rawMaterial_id)
    {
        $rawMaterial = RawMaterial::with('storein_type', 'toGodam', 'fromGodam','supplier')->find($rawMaterial_id);


        if($rawMaterial && $rawMaterial->from_godam_id ){
            // From  Raw Material Stock
            $danaGroups = DB::table('raw_material_stocks')
                ->join('dana_groups','dana_groups.id','=','raw_material_stocks.dana_group_id')
                ->where('raw_material_stocks.godam_id',$rawMaterial->from_godam_id)
                ->select('dana_groups.name','dana_groups.id')
                ->distinct('dana_groups.name','dana_groups.id')
                ->get();
            $fromRawMaterialStock=true;
         }else{
             $fromRawMaterialStock=false;
            $danaGroups = DanaGroup::get(['id','name']);
         }

        return view('admin.rawMaterial.createRawMaterialItem', compact('rawMaterial', 'danaGroups','fromRawMaterialStock'));
    }

        public function getStock(Request $request){
            return DB::table('raw_material_stocks')
            ->where('godam_id',$request->godam_id)
            ->where('dana_group_id',$request->dana_group_id)
            ->where('dana_name_id',$request->danaName_id)
            ->first()->quantity;
        }
    public function getDanaGroupDanaNameFromRawMStock($danaGroup_id,$godam_id){
            return DB::table('raw_material_stocks')
            ->join('dana_names','dana_names.id','=','raw_material_stocks.dana_name_id')
            ->where('raw_material_stocks.godam_id',$godam_id)
            ->where('raw_material_stocks.dana_group_id',$danaGroup_id)
            ->select('dana_names.id','dana_names.name')
            ->get();
    }
    public function getDanaGroupDanaName($danaGroup_id)
    {
        return DanaName::where('dana_group_id', $danaGroup_id)->get(['id','name']);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */
    public function show(RawMaterial $rawMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(RawMaterial $rawMaterial)
    {
        //
    }
}
