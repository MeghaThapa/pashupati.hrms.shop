<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\NonwovenBill;
use App\Models\NonwovenDanaConsumption;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;
use Yajra\DataTables\DataTables;

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

        $godams = Godam::get();
        $shifts = Shift::get();
        $nonwovenfabrics = NonWovenFabric::distinct()->get(['gsm']);

        $bill_no = AppHelper::getNonWovenReceiveEntryReceiptNo();


        return view('admin.nonwovenfabrics-receiveentry.index', compact('nonwovenfabrics','bill_no','godams','shifts'));
    }

    public function dataTable()
    {
        $nonwoven = NonwovenBill::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($nonwoven)
            ->addIndexColumn()
            ->addColumn('planttype', function ($row) {
                return $row->getPlantType->name;
            })
            ->addColumn('plantname', function ($row) {
                return $row->getPlantName->name;
            })
            ->addColumn('shift', function ($row) {
                return $row->getShift->name;
            })
            ->addColumn('godam', function ($row) {
                return $row->getGodam->name;
            })
            ->addColumn('action', function ($row) {

                if($row->status == "pending"){

                    return '<a href="' . route('nonwovenentry.create', ['bill_id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }
                else{

                    return '<a href="' . route('nonWovenEntry.viewBill', ['bill_id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }

            })
            ->rawColumns(['fromgodam','togodam','action'])
            ->make(true);
    }

    public  function viewBill($bill_id){

        $find_data = NonwovenBill::find($bill_id);
        $stocks = FabricNonWovenReciveEntry::where('bill_id',$bill_id)->get();
        $total = FabricNonWovenReciveEntry::where('bill_id',$bill_id)->sum('net_weight');

        return view('admin.nonwovenfabrics-receiveentry.viewbill',compact('stocks','bill_id','find_data','total'));

    }

    public function create($bill_id)
    {
        $find_data = NonwovenBill::find($bill_id);
        $bill_date = $find_data->bill_date;
        $bill_no = $find_data->bill_no;
        $planttype_id = $find_data->planttype_id;
        $plantname_id = $find_data->plantname_id;
        $shift_id = $find_data->shift_id;
        $godam_id = $find_data->godam_id;

        $nonwovenfabrics = NonWovenFabric::distinct()->get(['gsm']);

        $getnetweight = FabricNonWovenReciveEntry::where('status','sent')->sum('net_weight');
        $receipt_no = AppHelper::getNonWovenReceiveEntryReceiptNo();
        $danas = AutoLoadItemStock::where('plant_type_id',$planttype_id)
                               ->where('plant_name_id',$plantname_id)
                               ->where('shift_id',$shift_id)
                               ->where('from_godam_id',$godam_id)
                               ->get();


        $danalist = NonwovenDanaConsumption::where('bill_id',$bill_id)->get();

        $sumdana = NonwovenDanaConsumption::where('bill_id',$bill_id)->sum('quantity');

        return view('admin.nonwovenfabrics-receiveentry.create',compact('nonwovenfabrics','receipt_no','getnetweight','danas','find_data','bill_id','danalist','sumdana'));
    }

    public function store(Request $request)
    {
        if($request->ajax()){
            $data = [];
            parse_str($request->data,$data);
            $findbill = NonwovenBill::find($data['bill_id']);
            $nonfabric_id = NonWovenFabric::where('gsm',$data['fabric_gsm'])
                                          ->where('slug',$data['fabric_name'])
                                          ->where('color',$data['fabric_color'])
                                          ->value('id');

               $fabricreceiveenty = FabricNonWovenReciveEntry::create([
                'receive_date' => $data['receive_date'],
                'receive_no' => $data['bill_no'],
                'fabric_roll' => $data['fabric_roll'],
                'fabric_gsm' => $data['fabric_gsm'],
                'bill_id' => $data['bill_id'],
                'fabric_name' => $data['fabric_name'],
                'fabric_color' => $data['fabric_color'],
                'length' => $data['fabric_length'],
                'gross_weight' => $data['gross_weight'],
                'net_weight' => $data['net_weight'],
              ]);


            FabricNonWovenReceiveEntryStock::create([
                'nonfabric_id' => $fabricreceiveenty->id,
                'bill_id' => $data['bill_id'],
                'godam_id' => $findbill->godam_id,
                'receive_date' => $data['receive_date'],
                'receive_no' => $data['bill_no'],
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

            $consumption = $request->consumption;
            $danaNameID = $request->danaNameID;
            $fabric_waste = $request->fabric_waste;
            $total_waste  = $request->total_waste;
            $filter = $request->filter;
            $filament = $request->filament;
            $roal_coast = $request->roal_coast;
            $strip = $request->strip;

            $lamFabricToDelete = [];
            $lamFabricTempToDelete = [];
            $department = [];

            try{
                DB::beginTransaction();
                $find_godam = NonwovenBill::where('id',$request->bill)->latest()->first();


                if($total_waste != null){

                    $wastename = 'nonwoven';

                    $wastage = Wastages::firstOrCreate([
                     'name' => 'nonwoven'
                     ], [
                     'name' => 'nonwoven',
                     'is_active' => '1',

                     ]);

                    $waste_id = Wastages::where('name',$wastename)->value('id');

                    $stock = WasteStock::where('godam_id', $find_godam->department_id)
                    ->where('waste_id', $wastage->id)->count();

                    $getStock = WasteStock::where('godam_id', $find_godam->department_id)
                    ->where('waste_id', $wastage->id)->first();


                    if ($stock == 1) {
                        $getStock->quantity_in_kg += $fabric_waste;
                        $getStock->save();
                    } else {
                        WasteStock::create([
                            'godam_id' => $find_godam->godam_id,
                            'waste_id' => $wastage->id,
                            'quantity_in_kg' => $total_waste,
                        ]);
                    }


                }

                $nonwovenbill = NonwovenBill::where('id',$request->bill)
                ->update([
                    'status' => 'completed',
                    'filter' => $filter,
                    'filament' => $filament,
                    'roal_coast' => $roal_coast,
                    'strip' => $strip,
                    'total_waste' => $total_waste
                ]);


                 $getsinglesidelaminatedfabric = FabricNonWovenReciveEntry::where('bill_id',$request->bill)->update(['status' => 'completed']);

                 $getdoublesidelaminatedfabric = FabricNonWovenReceiveEntryStock::where('bill_id',$request->bill)->update(['status' => 'completed']);


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

    public function getnonwovenentries(Request $request){
        $data = FabricNonWovenReciveEntry::where('bill_id',$request->bill_id)->with('nonfabric')->where('status','sent')->get();
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
