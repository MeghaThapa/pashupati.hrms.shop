<?php

namespace App\Http\Controllers\Opening;

use App\Http\Controllers\Controller;
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

class NonWovenController extends Controller
{
    public function index()
    {
    
        $godams = Godam::get();
        $shifts = Shift::get();
        $nonwovenfabrics = NonWovenFabric::distinct()->get(['gsm']);

        $getnetweight = FabricNonWovenReciveEntry::where('status','sent')->sum('net_weight');
        $receipt_no = AppHelper::getNonWovenReceiveEntryReceiptNo();
        $dana = AutoLoadItemStock::get();
        return view('admin.nonwovenfabrics-receiveentry.opening.index',compact('godams','shifts','nonwovenfabrics','receipt_no','getnetweight','dana'));
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();
            dd($request);

            $fabricnon = FabricNonWovenReceiveEntryStock::create([
                'nonfabric_id' => '1',
                'receive_date' => $request['receive_date'],
                'receive_no' => $request['receive_no'],
                'godam_id' => $request['to_godam_id'],
                'planttype_id' => '1',
                'plantname_id' => '1',
                'shift_id' => '1',
                'fabric_roll' => $request['fabric_roll'],
                'fabric_gsm' => $request['fabric_gsm'],
                'fabric_name' => $request['fabric_name'],
                'fabric_color' => $request['fabric_color'],
                'length' => $request['fabric_length'],
                'gross_weight' => $request['gross_weight'],
                'net_weight' => $request['net_weight'],
            ]);

            return back();
           

       
       DB::commit();
         }catch(Exception $e){
                DB::rollBack();
                return $e;
        }

    }

   
}
