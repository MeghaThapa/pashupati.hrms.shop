<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\Shift;
use App\Models\Supplier;
use App\Models\FinalTripalStock;
use App\Models\SaleFinalTripal;
use App\Helpers\AppHelper;
use Carbon\Carbon;

class SaleFinalTripalController extends Controller
{
    public function index()
    {
        $bill_no = AppHelper::getFinalTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $partyname = Supplier::where('status',1)->get();
        $salefinaltripals = SaleFinalTripal::paginate(20);
        return view('admin.sale.salefinaltripal.creates',compact('bill_no','bill_date','fabrics','partyname','salefinaltripals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSaleFinalTripalStockList(Request $request)
    {
        $fabric_id = $request->fabric_id;
        $getName = FinalTripalStock::where('id',$fabric_id)->value('name');
        $fabrics = FinalTripalStock::where('name',$getName)->get();

        return response(['response'=>$fabrics]);

    }

    public function store(Request $request)
    {
        // $validator = $request->validate([
        //     'name' => 'required|string|max:60',
        //     'gsm' => 'required|numeric',
        //     'color' => 'required',
        // ]);
        // dd($request);
      
            $fabric = SaleFinalTripal::create([
                'bill_no' => $request['bill_number'],
                'bill_date' => $request['bill_date'],
                'partyname_id' => $request['partyname'],
                'bill_for' => $request['bill_for'],
                'lorry_no' => $request['lory_number'],
                'do_no' => $request['dp_number'],
                'gp_no' => $request['gp_number'],
                'remarks' => $request['remarks'],
            ]);

        return redirect()->back()->withSuccess('SaleFinalTripal created successfully!');

    }

    public function addTripal($id)
    {
        $bill_no = AppHelper::getFinalTripalReceiptNo();
        $bill_date = date('Y-m-d');
        $fabrics = FinalTripalStock::get();
        $partyname = Supplier::where('status',1)->get();
        $salefinaltripals = SaleFinalTripal::paginate(20);
        return view('admin.sale.salefinaltripal.addtripal',compact('bill_no','bill_date','fabrics','partyname','salefinaltripals'));
    }
}
