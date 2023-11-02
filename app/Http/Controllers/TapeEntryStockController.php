<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\TapeEntryStockModel;
use App\Models\RawMaterialStock;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\Department;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use DB;

class TapeEntryStockController extends Controller
{
    public function index()
    {
    $tableDatas = DB::table('tape_entry_openings')
    ->select(
        'tape_entry_openings.qty as opening',
        'godam.id',
        'godam.name',
        DB::raw('(SELECT SUM(total_weightinkg) FROM fabric_details WHERE fabric_details.godam_id = tape_entry_openings.godam_id) AS rolldown_total'),
        DB::raw('(SELECT SUM(total_wastage) FROM fabric_details WHERE fabric_details.godam_id = tape_entry_openings.godam_id) AS total_wastage_sum'),
        DB::raw('(SELECT SUM(total_in_kg) FROM tape_entry_items WHERE tape_entry_items.toGodam_id = tape_entry_openings.godam_id) AS production_total')
    )
    ->join('godam', 'godam.id', '=', 'tape_entry_openings.godam_id')
    ->groupBy('tape_entry_openings.qty', 'godam.id', 'godam.name','tape_entry_openings.godam_id')
    ->get();

    // return $tableDatas;
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();

    //    $tapeststocks = TapeEntryStockModel::paginate(35);

        $godams=Godam::where('status','active')->get(['id','name']);
        $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
        $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);

        // dd($tapeststocks);

        return view('admin.tapeentrystock.index',
        compact('settings','tableDatas','godams','planttypes','plantnames'));
    }

    public function filterStock(Request $request){
        // dd($request);

        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();

        $godams=Godam::where('status','active')->get(['id','name']);
        $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
        $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);

        $godam_id = $request->godam_id ?? null ;

        $tapeststocks = TapeEntryStockModel::paginate(35);

        if ($godam_id  != null) {
            $tapeststocks = TapeEntryStockModel::where('toGodam_id',$godam_id)->paginate(35);
        }
        
        $tapeststocks= $tapeststocks;

        return view('admin.tapeentrystock.index',
        compact('settings','tapeststocks','godams','planttypes','plantnames'));
    }
}
