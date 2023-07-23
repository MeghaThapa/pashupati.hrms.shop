<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoubleSideLaminatedFabricStock;
use App\Models\DoubleSideLaminatedFabric;
use App\Models\Wastages;
use App\Models\WasteStock;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\Singlesidelaminatedfabric;
use App\Models\SinglesidelaminatedfabricStock;
use App\Models\AutoLoadItemStock;
use App\Models\Godam;
use App\Models\Shift;
use App\Models\TripalEntry;
use App\Models\FinalTripalName;
use App\Models\FinalTripal;
use App\Models\FinalTripalStock;
use App\Helpers\AppHelper;
use Carbon\Carbon;

class OpeningTripalController extends Controller
{
    public function index()
    {
        
        $bill_no = AppHelper::getFinalTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $godam= Godam::where('status','active')->get();
        $shifts = Shift::where('status','active')->get();
        $dana = AutoLoadItemStock::get();
        $fabrics  = DoubleSideLaminatedFabricStock::get();
        $finaltripalname  = FinalTripalName::get();
        // dd($fabrics);
        return view('admin.tripal.openingtripal.index',compact('bill_no','bill_date','godam','shifts','fabrics','dana','finaltripalname'));
    }

    public function getFabricTypeList(Request $request)
    {
        // dd($request);
        $tripal_type = $request->tripaltype;
        // dd($request);
        if($tripal_type == 1){
            // dd('lol');
            $fabrics  = SinglesidelaminatedfabricStock::get();

        }elseif($tripal_type == 2){
            // dd('hello');

            $fabrics  = DoubleSideLaminatedFabricStock::get();

        }else{
            // dd('shit');

            $fabrics = FinalTripalStock::get();

        }

        return response([
            'response'=>$fabrics,
        ]);
        
       
    }
}
