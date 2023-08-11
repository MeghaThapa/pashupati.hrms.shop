<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Str;
use App\Models\Supplier;
use App\Models\FinalTripalStock;
use App\Models\SaleFinalTripal;
use App\Models\SaleFinalTripalList;
use App\Helpers\AppHelper;
use Throwable;
use Yajra\DataTables\DataTables;

class SaleFinalTripalController extends Controller
{
    public function index()
    {
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $partyname = Supplier::where('status',1)->get();
        $salefinaltripals = SaleFinalTripal::with('getSaleList')->paginate(20);
        return view('admin.sale.salefinaltripal.index',compact('fabrics','partyname','salefinaltripals'));
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
        $findtripal = SaleFinalTripal::find($id);
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $salefinaltripals = SaleFinalTripal::paginate(20);
        return view('admin.sale.salefinaltripal.addtripal',compact('findtripal','fabrics','salefinaltripals','id'));
    }

    public function viewTripal($id)
    {
        $findtripal = SaleFinalTripal::find($id);
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $salefinaltripals = SaleFinalTripal::paginate(20);
        return view('admin.sale.salefinaltripal.viewtripal',compact('findtripal','fabrics','salefinaltripals','id'));
    }

    public function getfinaltripalFilter(Request $request){
        if($request->ajax()){
            $fabric_name_id = $request->fabric_name_id;
            $fabric_name = FinalTripalStock::where("id",$fabric_name_id)->value("name");
            $fabrics = FinalTripalStock::where("name",$fabric_name)->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                    ->addColumn("gram_wt",function($row){
                        return '1';
                    })
                    ->addColumn("action",function($row){
                        return "
                        <a class='btn btn-primary send_to_lower'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Send</a>";
                    })
                    ->rawColumns(["action","gram_wt"])
                    ->make(true);

        }
    }

    public function getSaleTripalList(Request $request){
        if($request->ajax()){
            $saletripal_id = $request->saletripal_id;

            $fabrics = SaleFinalTripalList::where('salefinal_id',$saletripal_id)->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                    ->addColumn("gram",function($row){
                        return '1';
                    })
                    
                    ->rawColumns(["gram"])
                    ->make(true);

        }
    }

    public function finalTripalStoreList(Request $request)
    {
        try{
            $find_name = FinalTripalStock::find($request->data_id);
            
                $fabricstock = SaleFinalTripalList::create([
                    'name' => $find_name->name,
                    'slug' => $find_name->slug,
                    'roll' => $find_name->roll_no,
                    'gross' => $find_name->gross_wt,
                    'net' => $find_name->net_wt,
                    'meter' => $find_name->meter,
                    'gram' => $find_name->gram,
                    'average' => $find_name->average_wt,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $request->bill_date,
                    'salefinal_id' => $request->salefinal_id,
                ]);

                if($fabricstock){

                  FinalTripalStock::where('id',$request->data_id)->delete();
                }


        return response(['message'=>'sale Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
        }
    

    }
}
