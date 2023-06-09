<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FabricStock;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\Department;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use DB;

class FabricStockController extends Controller
{
    public function index()
    {
       $helper= new AppHelper();
       $settings= $helper->getGeneralSettigns();

       $fabric_stock = FabricStock::paginate(35);
       // $fabric_stock = FabricStock::get();

       $godams=Godam::where('status','active')->get(['id','name']);
       $planttypes=ProcessingStep::where('status','1')->get(['id','name']);
       $plantnames= ProcessingSubcat::where('status','active')->get(['id','name']);

       // dd($fabric_stock);


       return view('admin.fabric.fabric_stock.index',
       compact('settings','fabric_stock','godams','planttypes','plantnames'));
    }
}
