<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricGroup;
use App\Models\NonWovenFabric;
use App\Models\FabricNonWovenReciveEntry;
use App\Models\FabricNonWovenReceiveEntryStock;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Wastages;
use App\Models\WasteStock;
use App\Models\AutoLoadItemStock;
use App\Models\DanaName;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;

class NonwovenReceiveEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = NonWovenFabric::query();
        if (request('term')) {
            $term = request('term');
            $query->where('name', 'Like', '%' . $term . '%');
        }
        $nonwovenfabrics = $query->orderBy('id', 'DESC')->paginate(15);

        return view('admin.nonwovenfabrics-receiveentry.index', compact('nonwovenfabrics'));
    }

    public function create()
    {
   
        $godams = Godam::get();
        $shifts = Shift::get();
        $nonwovenfabrics = NonWovenFabric::distinct()->get(['gsm']);

        $getnetweight = FabricNonWovenReciveEntry::where('status','sent')->sum('net_weight');
        $receipt_no = AppHelper::getNonWovenReceiveEntryReceiptNo();
        $dana = AutoLoadItemStock::get();
        return view('admin.nonwovenfabrics-receiveentry.create',compact('godams','shifts','nonwovenfabrics','receipt_no','getnetweight','dana'));
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            // return $request->data;
            $data = [];
            parse_str($request->data,$data);
            // dd($data);
            $nonfabric_id = NonWovenFabric::where('gsm',$data['fabric_gsm'])
                                          ->where('slug',$data['fabric_name'])
                                          ->where('color',$data['fabric_color'])
                                          ->value('id');
                                          // dd($nonfabric_id);

               $fabricreceiveenty = FabricNonWovenReciveEntry::create([
                'receive_date' => $data['receive_date'],
                'receive_no' => $data['receive_no'],
                'godam_id' => $data['to_godam_id'],
                'planttype_id' => $data['planttype_id'],
                'plantname_id' => $data['plantname_id'],
                'shift_id' => $data['shift_id'],
                'fabric_roll' => $data['fabric_roll'],
                'fabric_gsm' => $data['fabric_gsm'],
                'nonwovenfabric_id' => $nonfabric_id,
                'fabric_name' => $data['fabric_name'],
                'fabric_color' => $data['fabric_color'],
                'length' => $data['fabric_length'],
                'gross_weight' => $data['gross_weight'],
                'net_weight' => $data['net_weight'],
              ]);


            FabricNonWovenReceiveEntryStock::create([
                'nonfabric_id' => $fabricreceiveenty->id,
                'receive_date' => $data['receive_date'],
                'receive_no' => $data['receive_no'],
                'godam_id' => $data['to_godam_id'],
                'planttype_id' => $data['planttype_id'],
                'plantname_id' => $data['plantname_id'],
                'shift_id' => $data['shift_id'],
                'fabric_roll' => $data['fabric_roll'],
                'fabric_gsm' => $data['fabric_gsm'],
                'fabric_name' => $data['fabric_name'],
                'fabric_color' => $data['fabric_color'],
                'length' => $data['fabric_length'],
                'gross_weight' => $data['gross_weight'],
                'net_weight' => $data['net_weight'],
            ]);
    
        }


        return redirect()->back()->withSuccess('NonWoven created successfully!');
    }

    public function storeWaste(Request $request)
    {

        if($request->ajax()){
            // dd($request);
            // dd('kk');
            $consumption = $request->consumption;
            $danaNameID = $request->danaNameID;
            $fabric_waste = $request->fabric_waste;
            $polo_waste = $request->polo_waste;
            $selectedDanaID = $request->selectedDanaID;
            $total_waste  = $request->total_waste;
            $lamFabricToDelete = [];
            $lamFabricTempToDelete = [];
            $department = [];

            // dd($department);

            try{
                DB::beginTransaction();

                 $stocks = AutoLoadItemStock::where('id',$request->selectedDanaID)->value('dana_name_id');

                 $stock = AutoLoadItemStock::where('dana_name_id',$stocks)->first();
                 // dd($stock);


                 $presentQuantity = $stock->quantity;
                 $deduction = $presentQuantity - $request->consumption;
                 // dd($deduction);

                 // if($deduction == 0){
                 //     $stock->delete();
                 // }
                 // else{
                 //     $stock->update([
                 //         'quantity' => $deduction
                 //     ]);
                 // }

                 $getsinglesidelaminatedfabric = FabricNonWovenReciveEntry::where('receive_no',$request->bill)->update(['status' => 'completed']); 

                 $getdoublesidelaminatedfabric = FabricNonWovenReceiveEntryStock::where('receive_no',$request->bill)->update(['status' => 'completed']); 


                DB::commit();

                return response(200);
            }catch(Exception $e){
                DB::rollBack();
                return response([
                    "exception" => $e->getMessage(),
                ]);
            }
        }
 

        return redirect()->back()->withSuccess('NonWoven created successfully!');
    }

    public function getnonwovenentries(){
        // return response(['response'=> '404']);
        $data = FabricNonWovenReciveEntry::with('nonfabric')->where('status','sent')->get();
        // dd($data);
        if(count($data) != 0){
            return response(['response'=>$data]);
        }else{
            return response(['response'=> '404']);
        }   
    }

    public function edit($slug)
    {
        $nonwovenfabrics = NonWovenFabric::where('slug', $slug)->first();
        return view('admin.nonwovenfabric.edit', compact('nonwovenfabrics'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $fabric = NonWovenFabric::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:non_woven_fabrics,name,' . $fabric->id,
            'gsm' => 'required|numeric|unique:non_woven_fabrics',
            'color' => 'required',
        ]);

        // update fabric
        $fabric->update([
            'name' => $request->name,
            'gsm' => $request['gsm'],
            'color' => $request['color'],
        ]);


        return redirect()->route('nonwovenfabrics.index')->withSuccess('Fabric updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = NonWovenFabric::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('nonwovenfabrics.index')->withSuccess('Fabric deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = NonWovenFabric::where('slug', $slug)->first();

        // change category status
        if ($category->status == 1) {
            $category->update([
                'status' => 0
            ]);
        } else {
            $category->update([
                'status' => 1
            ]);
        }
        return redirect()->route('nonwovenfabrics.index')->withSuccess('Fabric status changed successfully!');
    }

    public function getPlantTypeList(Request $request){
      $godam_id = $request->godam_id;
      $department_list = ProcessingStep::where('department_id',$godam_id)
      // ->where('is_active','1')
      ->with('department')
      ->get();
      // dd($department_list);
      return Response::json($department_list);
    }

    public function getPlantNameList(Request $request){
        // dd('hh');
        // dd($request);
      $planttype_id = $request->planttype_id;
      $plantname_list = ProcessingSubcat::where('processing_steps_id',$planttype_id)
      // ->where('is_active','1')
      ->get();
      return Response::json($plantname_list);
    }


    public function getFabricNameList(Request $request){
        // dd('hh');
        // dd($request);
      $fabric_gsm = $request->fabric_gsm;
      $plantname_list = NonWovenFabric::where('gsm',$fabric_gsm)
      ->distinct()->get(['name','slug']);
      // ->get();
      // dd($plantname_list);
      return Response::json($plantname_list);
    }

    public function getFabricNameColorList(Request $request){
       
      $fabric_gsm = $request->fabric_gsm;
      $fabric_name = $request->fabric_name;
      // dd($request);
      $fabric_color_list = NonWovenFabric::where('gsm',$fabric_gsm)
                                          ->where('slug',$fabric_name)
                                         ->get();
                                         // dd($fabric_color_list);
      return Response::json($fabric_color_list);
    }

    public function getDataList(Request $request)
    {
        return view('admin.nonwovenfabrics-receiveentry.bill_row',compact('request'));
    }

    public function getDanaList(Request $request)
    {
        return view('admin.nonwovenfabrics-receiveentry.bill_dana',compact('request'));
    }
}
