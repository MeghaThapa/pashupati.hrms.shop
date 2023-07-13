<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\DoubleSideLaminatedFabricStock;
use App\Models\RawMaterialStock;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\Department;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use DB;

class DoubleTripalStockController extends Controller
{

    public function index()
    {
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();


       $doubletripal = DoubleSideLaminatedFabricStock::get();

       $godams=Godam::where('status','active')->get(['id','name']);
       $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
       $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);


       return view('admin.tripal_stock.doubletripalstock.index',
       compact('settings','doubletripal','godams','planttypes','plantnames'));
    }
    
}
