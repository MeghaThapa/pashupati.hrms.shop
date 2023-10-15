<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\AutoLoad;
use App\Models\ProcessingStep;
use App\Models\Department;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\DanaGroup;
use App\Models\RawMaterial;
use App\Models\RawMaterialStock;
use App\Models\AutoLoadItemStock;
use Yajra\DataTables\Facades\DataTables;
use DB;

class AutoloadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.autoload.index');
    }
    public function getReceiptNo()
    {
        return  AppHelper::getAutoLoadReceiptNo();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.autoload.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $validator = $request->validate([
            'trasfer_date'          => 'required|Date',
            'receipt_no'         => 'required|unique:auto_loads,receipt_no',
        ]);
        $autoload = new AutoLoad();
        $autoload->transfer_date = $request->trasfer_date;
        $autoload->receipt_no  = $request->receipt_no;
        $autoload->save();
        return $autoload;
        // return redirect()->route('autoLoad.createAutoloadItem',['autoload_id'=>$autoload->id]);
    }
    public function update(Request $request)
    {
        //return $request;
        $autoload = AutoLoad::find($request->autoload_id_model);
        $autoload->transfer_date = $request->transfer_date_model;
        $autoload->save();
        $fromGodams = RawMaterialStock::with('godam')->select('godam_id')->distinct()->get();
        // return $fromGodams;
        $plantTypes = ProcessingStep::all();
        $plantNames = ProcessingSubcat::all();
        $shifts = Shift::where('status', 'active')->get();
        return view('admin.autoload.createaAutoloadItems', compact('fromGodams', 'plantTypes', 'plantNames', 'shifts', 'autoload'));
    }
    public function createAutoloadItem($autoload_id)
    {
        $fromGodams = RawMaterialStock::with('godam')->select('godam_id')->distinct()->get();
        //return $fromGodams;
        $plantTypes = ProcessingStep::all();
        $plantNames = ProcessingSubcat::all();
        $shifts = Shift::where('status', 'active')->get();
        $autoload = AutoLoad::find($autoload_id);
        return view('admin.autoload.createaAutoloadItems', compact('fromGodams', 'plantTypes', 'plantNames', 'shifts', 'autoload'));
    }
    // create
    public function getDanaGroupAccToGodam($godam_id)
    {
        return RawMaterialStock::with('danaGroup')->where('godam_id', $godam_id)->select('dana_group_id')->distinct()->get();
    }
    //  public function getDanaGroupDanaName($danaGroup_id,$fromGodam_id)
    public function getDanaGroupDanaName($danaGroup_id, $fromGodam_id)
    {
        return RawMaterialStock::with('danaName')->where('godam_id', $fromGodam_id)->where('dana_group_id', $danaGroup_id)->get();
    }

    //for Edit
    public function getEditDanaGroupAccToGodam($department_id)
    {
        return AutoLoadItemStock::with('danaGroup')->where('from_godam_id', $department_id)->select('dana_group_id')->distinct()->get();
    }
    public function getEditDanaGroupDanaName($danaGroup_id, $fromGodam_id)
    {
        return AutoLoadItemStock::with('danaName')->where('from_godam_id', $fromGodam_id)->where('dana_group_id', $danaGroup_id)->get();
    }



    public function getPlantTypePlantName($plantType_id)
    {
        $plantNames = ProcessingSubcat::where('processing_steps_id', $plantType_id)->get();
        return $plantNames;
    }
    public function getPlantTypeAccGodam($godam_id)
    {

        $processingSteps = ProcessingStep::where('godam_id', $godam_id)->get(['id', 'name']);
        return $processingSteps;
    }

    public function dataTable()
    {

        $autoLoads = AutoLoad::select('id', 'transfer_date', 'receipt_no', 'created_at', 'updated_at', 'status')
            ->orderBy('created_at', 'desc')
            ->withCount('autoloadItems')
            ->get();
        return DataTables::of($autoLoads)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $actionBtn = '';

                if(auth()->user()->hasRole('Admin')){
                    $actionBtn.='<a class="btn btn-sm btn-primary" href="'.route('autoLoad.createAutoloadItem',$row->id).'">Edit</a>';
                }else{
                    $actionBtn.='<a class="btn btn-sm btn-primary" href="'.route('autoLoad.createAutoloadItem',$row->id).'">Edit</a>';
                }

                if ($row->status == "complete") {
                    $actionBtn.='<span class="badge badge-success">COMPLETED</span>';
                    return $actionBtn;
                }

                $actionBtn .= '
                <button class=" btn btn-primary btnEdit" data-id="' . $row->id . '">
                <i class="fas fa-edit fa-lg"></i>
                </button>';
                if ($row->autoload_items_count == 0) {
                    $actionBtn .= '
                <button class="btn btn-danger btnAutoloadDlt" data-id="' . $row->id . '">
                <i class="fas fa-trash fa-lg"></i>
                </button>';
                }
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function delete($autoload_id)
    {
        $autoload = AutoLoad::find($autoload_id);
        $autoload->delete();
    }

    public function saveEntireAutoload($autoload_id)
    {
        $autoload = AutoLoad::find($autoload_id);
        $autoload->status = 'complete';
        $autoload->save();
        return redirect()->route('autoload.index');
    }
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

    public function getEditItemData($autoLoad_id)
    {
        $autoload = AutoLoad::find($autoLoad_id);
        return $autoload;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


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
