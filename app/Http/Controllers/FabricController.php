<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricGroup;
use App\Models\Godam;
use App\Models\Shift;
use App\Models\FabricDetail;
use App\Models\TapeEntryStockModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FabricImport;
use Illuminate\Support\Facades\DB;


class FabricController extends Controller
{
    public function index(Request $request)
    {
        // $query = Fabric::query();
        // if (request('term')) {
        //     $term = request('term');
        //     $query->where('name', 'Like', '%' . $term . '%');
        // }
        // $fabrics = $query->orderBy('id', 'DESC')->paginate(15);

        $fabrics = FabricDetail::paginate(15);

        $departments = Godam::get();
        $shifts = Shift::get();
$fabric_netweight = 0;
        // $getFabricLastId = Fabric::latest()->first();


        // // dd($getFabricLastId);

        // if($getFabricLastId != null)
        // {
        //  $fabric_netweight = Fabric::where('bill_no',$getFabricLastId->bill_no)->sum('net_wt');

        // }

        // else{
        //
        // }


        return view('admin.fabric.index', compact('fabrics','departments','shifts','fabric_netweight'));
    }


     public function create()
    {
        $fabricgroups = FabricGroup::get();
         $godams = Godam::get();

        return view('admin.fabric.create',compact('fabricgroups','godams'));
    }

    public function store(Request $request)
    {
        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:60|unique:fabrics',
            'fabricgroup_id' => 'required|integer',
            // 'roll_no' => 'required|integer',
            // 'loom_no' => 'required|integer',
            // 'gross_wt' => 'required|integer',
            // 'net_wt' => 'required|integer',
        ]);


        // store subcategory
        $fabric = Fabric::create([
            'name' => $request['name'],
            'roll_no' => '0',
            'loom_no' => '0',
            'fabricgroup_id' => $request['fabricgroup_id'],
            'godam_id' => $request['godam_id'],
            'average_wt' => '0',
            'gross_wt' => '0',
            'net_wt' => '0',
            'meter' => $request['meter'],
            'gram_wt' => '00',
        ]);

        return redirect()->back()->withSuccess('Sub category created successfully!');
    }

    public function import(Request $request){
        // dd($request);
        $request->validate([
            "file" => "required|mimes:csv,xlsx,xls,xltx,xltm",
        ]);
        $file = $request->file('file');
        $import = Excel::import(new FabricImport($request->godam_id,$request->date_np), $file);
        if($import){
            return back()->with(["message"=>"Data imported successfully!"]);
        }else{
            return "Unsuccessful";
        }
    }

    public function edit($slug)
    {
        $fabrics = Fabric::where('slug', $slug)->first();
        $fabricgroups = FabricGroup::get();
        $godams = Godam::get();
        return view('admin.fabric.edit', compact('fabrics','fabricgroups','godams'));
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
        $fabric = Fabric::where('slug', $slug)->first();

        //validate form
        $validator = $request->validate([
            'name' => 'required|string|max:50|unique:fabrics,name,' . $fabric->id,
            'fabricgroup_id' => 'required|integer',
            // 'roll_no' => 'required|integer',
            // 'loom_no' => 'required|integer',
            // 'gross_wt' => 'required|integer',
            // 'net_wt' => 'required|integer',
        ]);

        // update fabric
        $fabric->update([
            'name' => $request->name,
            // 'roll_no' => $request['roll_no'],
            // 'loom_no' => $request['loom_no'],
            'fabricgroup_id' => $request['fabricgroup_id'],
            'godam_id' => $request['godam_id'],
            // 'gross_wt' => $request['gross_wt'],
            // 'net_wt' => $request['net_wt'],
            'meter' => $request['meter'],
        ]);


        return redirect()->route('fabrics.index')->withSuccess('Fabric updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $category = Fabric::where('slug', $slug)->first();
        // destroy category
        $category->delete();
        return redirect()->route('fabrics.index')->withSuccess('Fabric deleted successfully!');
    }


    /**
     * Change the status of specified category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($slug)
    {
        $category = Fabric::where('slug', $slug)->first();

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
        return redirect()->route('fabrics.index')->withSuccess('Fabric status changed successfully!');
    }

    public function fabricDetail(Request $request)
    {
        // dd($request);
        $godam_id = $request->to_godam_id;
        $planttype_id = $request->planttype_id;
        $plantname_id = $request->plantname_id;
        $shift_id = $request->shift_id;
        //validate form
        $validator = $request->validate([
            'pipe_cutting' => 'required|integer',
            'bd_wastage' => 'required|integer',
            'other_wastage' => 'required|integer',
            'total_wastage' => 'required|integer',
            // 'total_netweight' => 'required|integer',
            'total_meter' => 'required|integer',
            'total_weightinkg' => 'required|integer',
            'total_wastageinpercent' => 'required|integer',
            'run_loom' => 'required|integer',
            'wrapping' => 'required|integer',
        ]);


        $getLastId = Fabric::latest()->first();
        $bill_no = $getLastId->bill_no;

        $gettapeQuantity = TapeEntryStockModel::where('toGodam_id',$godam_id)
                                              // ->where('plantType_id',$planttype_id)
                                              // ->where('plantName_id',$plantname_id)
                                              // ->where('shift_id',$shift_id)
                                              ->value('id');
                                              // dd($gettapeQuantity);

        $findTape = TapeEntryStockModel::find($gettapeQuantity);
        dd($findTape->tape_qty_in_kg);
        $totalwastage = $request['total_wastage'];
        $totalnetWeight = $request['total_netweight'];
        $finalWastage = $totalwastage + $totalnetWeight;

        // dd($finalWastage,$findTape->tape_qty_in_kg);
        if($totalnetWeight < $findTape->tape_qty_in_kg){

            $final = $findTape->tape_qty_in_kg - $finalWastage;
            $findTape->tape_qty_in_kg = $final;
            $findTape->update();

            $countData = FabricDetail::where('bill_number',$bill_no)->count();
            if($countData != 1){
                // store subcategory
                $fabric = FabricDetail::create([
                    'bill_number' => $bill_no,
                    'bill_date' => '0',
                    'pipe_cutting' => $request['pipe_cutting'],
                    'bd_wastage' => $request['bd_wastage'],
                    'other_wastage' => $request['other_wastage'],
                    'total_wastage' => $request['total_wastage'],
                    'total_netweight' => $request['total_netweight'],
                    'total_meter' => $request['total_meter'],
                    'total_weightinkg' => $request['total_weightinkg'],
                    'total_wastageinpercent' => $request['total_wastageinpercent'],
                    'run_loom' => $request['run_loom'],
                    'wrapping' => $request['wrapping'],
                ]);

            }

        }

        return redirect()->back()->withSuccess('Sub category created successfully!');
    }

    public function discard()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fabrics')->truncate();
        DB::table('fabric_stock')->truncate();
        DB::table('fabric_details')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return back();
    }


}
