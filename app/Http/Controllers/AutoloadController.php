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
    public function getReceiptNo(){
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
        $autoload= new AutoLoad();
        $autoload->transfer_date=$request->trasfer_date;
        $autoload->receipt_no  = $request->receipt_no;
        $autoload->save();
        return $autoload;
       // return redirect()->route('autoLoad.createAutoloadItem',['autoload_id'=>$autoload->id]);
    }
     public function createAutoloadItem($autoload_id){
        $fromGodams = RawMaterialStock::with('department')->select('department_id')->distinct()->get();
//return $fromGodams;
       //return $fromGodams;
        //$fromGodams = Department::all();
        $plantTypes = ProcessingStep::all();
        $plantNames = ProcessingSubcat::all();
        $shifts= Shift::where('status','active')->get();
       // $danaGroups= DanaGroup::where('status','active')->get();
        $autoload= AutoLoad::find($autoload_id);
        //return $autoload;
        return view('admin.autoload.createaAutoloadItems',compact('fromGodams','plantTypes','plantNames','shifts','autoload'));
    }
    public function getDanaGroupAccToGodam($department_id){
        $danaGroups= RawMaterialStock::with('danaGroup')->where('department_id',$department_id)->select('dana_group_id')->distinct()->get();
        return $danaGroups;
    }
    //  public function getDanaGroupDanaName($danaGroup_id,$fromGodam_id)
     public function getDanaGroupDanaName($danaGroup_id,$fromGodam_id)
    {
        //->where('department_id',$fromGodam_id)
        
        $rawMaterialStockDanaName=RawMaterialStock::with('danaName')->where('department_id',$fromGodam_id)->where('dana_group_id',$danaGroup_id)->get();
    
        return $rawMaterialStockDanaName;

    }

    public function getPlantTypePlantName($plantType_id){
        $plantNames=ProcessingSubcat::where('processing_steps_id',$plantType_id)->get();
        return $plantNames;
    }
    public function getPlantTypeAccGodam($department_id){
        $processingSteps=ProcessingStep::where('department_id',$department_id)->get(['id','name']);
        return $processingSteps;
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
    public function edit($id)
    {
        //
    }

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
