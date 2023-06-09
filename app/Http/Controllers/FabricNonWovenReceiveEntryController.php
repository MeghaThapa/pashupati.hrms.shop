<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricGroup;
use App\Models\NonWovenFabric;
use App\Models\Department;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;
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
        $nonwovenfabrics = NonWovenFabric::get();
        $receipt_no = "NFE"."-".getNepaliDate(date('Y-m-d'));
        // dd($date);
        return view('admin.nonwovenfabrics-receiveentry.create',compact('departments','shifts','nonwovenfabrics','receipt_no'));
    }

    public function store(Request $request)
    {
        // dd('hello');
        //validate form

        dd($request);
        $fabric_gsm = $request->fabric_gsm;

        foreach( $fabric_gsm AS $gsm ){
          $fabricnonwovenreciveentry= FabricNonWovenReciveEntry::create([
            'receive_date' => $request->input('receive_date'),
            'receive_no' => $request->input('receive_no'),
            'godam_data' => $request->input('godam_data'),
            'planttype_data' => $request->input('planttype_data'),
            'plantname_data' => $request->input('plantname_data'),
            'shift' => $request->input('shift'),
            'fabric_roll' => $request->input('fabric_roll'),
            'fabric_gsm' => $gsm,
            'fabric_name' => $request->input('fabric_name'),
            'fabric_color' => $request->input('fabric_color'),
            'length' => $request->input('length'),
            'gross_weight' => $request->input('gross_wt'),
            'net_weight' => $request->input('net_wt'),
          ]);

          $fabricnonwovenreciveentry= FabricNonWovenReceiveEntryStock::create([
            'receive_date' => $request->input('receive_date'),
            'receive_date' => $request->input('receive_date'),
            'receive_no' => $request->input('receive_no'),
            'godam_data' => $request->input('godam_data'),
            'planttype_data' => $request->input('planttype_data'),
            'plantname_data' => $request->input('plantname_data'),
            'shift' => $request->input('shift'),
            'fabric_roll' => $request->input('fabric_roll'),
            'fabric_gsm' => $gsm,
            'fabric_name' => $request->input('fabric_name'),
            'fabric_color' => $request->input('fabric_color'),
            'length' => $request->input('length'),
            'gross_weight' => $request->input('gross_wt'),
            'net_weight' => $request->input('net_wt'),
          ]);

        }


       

        return redirect()->back()->withSuccess('NonWoven created successfully!');
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

    public function getDataList(Request $request)
    {
        return view('admin.nonwovenfabrics-receiveentry.bill_row',compact('request'));
    }

    public function getDanaList(Request $request)
    {
        return view('admin.nonwovenfabrics-receiveentry.bill_dana',compact('request'));
    }
}
