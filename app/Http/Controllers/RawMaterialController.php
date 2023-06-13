<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\DanaGroup;
use App\Models\DanaName;
use App\Models\Godam;
use App\Models\RawMaterialStock;
use App\Models\RawMaterial;
use App\Models\Setupstorein;
use App\Models\Department;
use App\Models\Storein;
use App\Models\Supplier;
use App\Models\StoreinType;
use Illuminate\Http\Request;
use DateTime;
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
        $rawMaterial = RawMaterial::get();
        return DataTables::of($rawMaterial)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '
                <a class="btnEdit" href="' . route('rawMaterial.edit', ["rawMaterial_id" => $row->id]) . '" >
                <i class="fas fa-edit fa-lg"></i>
                </a>';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
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
