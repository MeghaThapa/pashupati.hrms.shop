<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricGroup;
use App\Models\NonWovenFabric;
use App\Models\FabricNonWovenReciveEntry;
use App\Models\FabricNonWovenReceiveEntryStock;
use App\Models\Department;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
use App\Models\Wastages;
use App\Models\WasteStock;
use App\Models\AutoLoadItemStock;
use App\Models\DanaName;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class FabricNonWovenReceiveEntryController extends Controller
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
        $departments = Department::get();
        $shifts = Shift::get();
        $nonwovenfabrics = NonWovenFabric::distinct()->get(['gsm']);

        $getnetweight = FabricNonWovenReciveEntry::sum('net_weight');
        // dd($getsumnetweight);
      
        // dd($nonwovenfabrics);
        $receipt_no = "NFE"."-".getNepaliDate(date('Y-m-d'));
        $dana = AutoLoadItemStock::get();
        return view('admin.nonwovenfabrics-receiveentry.create',compact('departments','shifts','nonwovenfabrics','receipt_no','getnetweight','dana'));
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

        $stock = AutoLoadItemStock::where('dana_name_id',$request->dana)->first();
        $presentQuantity = $stock->quantity;
        $deduction = $presentQuantity - $request->danaquantity;

        if($deduction == 0){
            $stock->delete();
        }
        else{
            $stock->update([
                'quantity' => $deduction
            ]);
        }

       $wastage = Wastages::create([
        'name' => 'nonwoven',
      
       ]);

       $wastage_stock = WasteStock::create([
        'waste_id' => $wastage->id,
        'department_id' => $request->godam_id,
        'quantity_in_kg' => $request->wastage,
       
       ]);

        return redirect()->back()->withSuccess('NonWoven created successfully!');
    }

    public function getnonwovenentries(){
        // return response(['response'=> '404']);
        $data = FabricNonWovenReciveEntry::with('nonfabric')->get();
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
      // ->where('is_active','1')
      ->get();
      return Response::json($plantname_list);
    }

    public function getFabricNameColorList(Request $request){
       
      $fabric_gsm = $request->fabric_gsm;
      $fabric_name = $request->fabric_name;
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
