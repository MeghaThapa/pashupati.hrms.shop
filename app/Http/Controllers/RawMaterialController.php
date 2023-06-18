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
        if($rawMaterialItems && count($rawMaterialItems) !=0){
            foreach($rawMaterialItems as $item){
                $stock=RawMaterialStock::where('godam_id',$rawMaterial->to_godam_id)
                ->where('dana_group_id',$item->dana_group_id)
                ->where('dana_name_id',$item->dana_name_id)
                ->first();
                $stock->quantity -= $item->quantity;
                if($stock->quantity <=0){
                    $stock->delete();
                }
                else{
                    $stock->save();
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
        $suppliers = Supplier::all();
        $storeinTypes = StoreinType::all();
        $godams = Godam::all();
        $rawMaterial = null;
        return view('admin.rawMaterial.create', compact('suppliers', 'storeinTypes', 'godams', 'receipt_no', 'rawMaterial'));
    }
    public function edit($rawMaterial_id)
    {
        $suppliers = Supplier::all();
        $storeinTypes = Setupstorein::all();
         $godams = Godam::all();
        $rawMaterial = RawMaterial::with('storein_type', 'toGodam', 'fromGodam')->find($rawMaterial_id);

        return view('admin.rawMaterial.create', compact('suppliers', 'storeinTypes', 'godams', 'rawMaterial'));
    }
    public function update(Request $request, $rawMaterial_id)
    {
        // return $request;
        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required|date',
            'pp_no' => 'required',
            'Type_id' => 'required',
            'to_godam_id' => 'required',
            'Receipt_no' => 'required',

        ]);
        $requestStoreinTypeName = self::getTypeNameFromId($request->Type_id);
        if ($requestStoreinTypeName == "Godam") {
            $request->validate([
                'from_godam_id' => 'required',
                'challan_no' => 'required',
                'gp_no' => 'required',
            ]);
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
        // return $request;
        $validator = $request->validate([
            'supplier_id' => 'required',
            'date' => 'required|date',
            'pp_no' => 'required',
            'Type_id' => 'required',
            'to_godam_id' => 'required',
            'Receipt_no' => 'required',

        ]);
        $requestStoreinTypeName = self::getTypeNameFromId($request->Type_id);
        if ($requestStoreinTypeName == "Godam") {
            $request->validate([
                'from_godam_id' => 'required',
                'challan_no' => 'required',
                'gp_no' => 'required',
            ]);
        }
        $rawMaterial = new RawMaterial();
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
        //return $rawMaterial;
        return redirect()->route('rawMaterial.createRawMaterialItems', ['rawMaterial_id' => $rawMaterial->id]);
    }
    public function createRawMaterialItems($rawMaterial_id)
    {
        $storeinTypes = Setupstorein::all();
        $godams = Department::all();
        $danaGroups = DanaGroup::all();
        $danaNames = DanaName::all();
        $rawMaterial = RawMaterial::find($rawMaterial_id);
        return view('admin.rawMaterial.createRawMaterialItem', compact('rawMaterial', 'storeinTypes', 'godams', 'danaGroups', 'danaNames'));
    }

    public function getDanaGroupDanaName($danaGroup_id)
    {
       // $rawMaterialStockDanaName=RawMaterialStock::with('danaName')->where('department_id',$fromGodam_id)->where('dana_group_id',$danaGroup_id)->get();
        return DanaName::where('dana_group_id', $danaGroup_id)->get();
       // return $rawMaterialStockDanaName;

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
