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
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();

       $tapeststocks = TapeEntryStockModel::get();

        $godams=Godam::where('status','active')->get(['id','name']);
        $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
        $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);

        // dd($tapeststocks);

        return view('admin.tapeentrystock.index',
        compact('settings','tapeststocks','godams','planttypes','plantnames'));
    }

    public function filterStock(Request $request){
        // dd($request);

        $helper= new AppHelper();
        $settings= $helper->getGeneralSettigns();

        $godams=Godam::where('status','active')->get(['id','name']);
        $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
        $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);

        $godam_id = $request->godam_id ?? null ;
        $planttypes_id = $request->planttypes_id ?? null;
        $plantname_id = $request->plantname_id ??null;

          $tapeststocks = TapeEntryStockModel::get();
          // dd($tapeststocks);

            if ($godam_id  != null) {
                $tapeststocks->where('godam_id',$godam_id);
            }
            if($planttypes_id !=null){
                $tapeststocks->where('plantType_id', $planttypes_id);
            }
            if($plantname_id !=null){
                $tapeststocks->where('plantName_id', $plantname_id);
            }

            $tapeststocks= $tapeststocks;
            // dd($tapeststocks);


        return view('admin.tapeentrystock.index',
        compact('settings','tapeststocks','godams','planttypes','plantnames'));
    }
}
