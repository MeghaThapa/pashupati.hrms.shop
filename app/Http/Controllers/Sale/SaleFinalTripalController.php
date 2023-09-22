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
use App\Models\SaleFinalTripalEntry;
use App\Models\FinalTripalName;
use App\Models\Godam;
use App\Helpers\AppHelper;
use Throwable;
use Yajra\DataTables\DataTables;
use App\Exports\TripalSale;
use PDF;
use Maatwebsite\Excel\Facades\Excel;


class SaleFinalTripalController extends Controller
{
    public function index()
    {
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $partyname = Supplier::where('status',1)->get();
        $salefinaltripals = SaleFinalTripal::with('getSaleList')->paginate(20);
        return view('admin.sale.salefinaltripal.index',compact('fabrics','partyname','salefinaltripals'));
    }

    public function dataTable()
    {
        $data = SaleFinalTripal::orderBy('created_at','DESC')
                       ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('supplier', function ($row) {

                return $row->getParty->name;

            })
            ->addColumn('action', function ($row) {

                if($row->status == "sent"){

                    return '<a href="' . route('salefinaltripals.addTripal', ['id' => $row->id]) . '" class="btn btn-info"><i class="fas fa-plus"></i></a>';

                }else{

                    return '<a href="' . route('salefinaltripals.viewTripalBill', ['id' => $row->id]) . '" class="btn btn-primary" ><i class="fas fa-print"></i></a>';

                }


                return $actionBtn;

            })
            ->rawColumns(['action'])
            ->make(true);
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

    public function getTripalSaleTotal(Request $request){
        if($request->ajax()){
            $tripal = SaleFinalTripalList::where('salefinal_id',$request->bill_id)
                                            ->get();
            $sumnetwt = $tripal->sum("net");
            $sumgrosswt = $tripal->sum("gross");
            $summeter = $tripal->sum("meter");
            // dd($tripal);
            return response([
                "net_wt" => $sumnetwt,
                 "gross_wt" => $sumgrosswt,
                 "meter" => $summeter
            ]);
        }
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
                'status' => 'sent',
            ]);

        return redirect()->back()->withSuccess('SaleFinalTripal created successfully!');

    }

    public function addTripal($id)
    {
        $findtripal = SaleFinalTripal::find($id);
        $godam_id = Godam::where('name','psi')->value('id');
        // dd($item);
        $fabrics = FinalTripalStock::where('department_id',$godam_id)->get()->unique('name')->values()->all();
        // dd($fabrics);
        $salefinaltripals = SaleFinalTripal::paginate(20);
        return view('admin.sale.salefinaltripal.addtripal',compact('findtripal','fabrics','salefinaltripals','id'));
    }

    public function viewTripal($id)
    {
        $findtripal = SaleFinalTripal::find($id);
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $salefinaltripals = SaleFinalTripalList::where('salefinal_id',$id)->get();
        // dd($salefinaltripals);
        return view('admin.sale.salefinaltripal.billtripal',compact('findtripal','fabrics','salefinaltripals','id'));
    }

    public function viewTripalBill($id)
    {
        $findtripal = SaleFinalTripal::find($id);
        $fabrics = FinalTripalStock::get()->unique('name')->values()->all();
        $salefinaltripals = SaleFinalTripalList::where('salefinal_id',$id)->orderBy('name')->get();

        $total_gross = SaleFinalTripalList::where('salefinal_id',$id)->sum('gross');
        $total_net = SaleFinalTripalList::where('salefinal_id',$id)->sum('net');
        $total_meter = SaleFinalTripalList::where('salefinal_id',$id)->sum('meter');

        $totaltripals = SaleFinalTripalList::where('salefinal_id',$id)->select('name', DB::raw('SUM(gross) as total_gross'),DB::raw('SUM(net) as total_net'),DB::raw('SUM(meter) as total_meter'),DB::raw('COUNT(name) as total_count'))
            ->groupBy('name')
            ->get();
        return view('admin.sale.salefinaltripal.billtripallist',compact('findtripal','fabrics','salefinaltripals','id','totaltripals','total_gross','total_net','total_meter'));
    }

    public function getfinaltripalFilter(Request $request){
        if($request->ajax()){
            $godam_id = Godam::where('name','psi')->value('id');
            $fabric_name_id = $request->fabric_name_id;
            $fabric_name = FinalTripalStock::where("id",$fabric_name_id)->value("name");
            $fabrics = FinalTripalStock::where('department_id',$godam_id)->where("name",$fabric_name);
            // dd($fabrics->count());

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
        // dd($request);

        if($request->ajax()){
            $sale_id = $request->sale_id;
            $fabrics = SaleFinalTripalEntry::where("salefinal_id",$sale_id)->get();

            return DataTables::of($fabrics)
                    ->addIndexColumn()
                   
                    ->addColumn("action",function($row){
                        return "
                        <a class='btn btn-danger deleteTripalEntry'  
                                 data-id='{$row->id}' 
                                 href='{$row->id}'>Delete</a>";
                    })
                    ->rawColumns(["action","gram_wt"])
                    ->make(true);

        }
        // if($request->ajax()){
        //     dd($request);
        //     $salefinal_id = $request->salefinal_id;

        //     $fabrics = SaleFinalTripalEntry::where('salefinal_id',$salefinal_id)->get();

        //     return response([
        //         "datalist" => $fabrics,
        //     ]);


        // }
    }

    public function deleteEntryList(Request $request)
    {

        $unit = SaleFinalTripalEntry::find($request->data_id);

        // delete unit
        $unit->delete();
        return response([
            "message" => "Deleted Successfully" 
        ]);

    }

    public function finalTripalStoreEntryList(Request $request)
    {
        try{
            $find_name = FinalTripalStock::find($request->data_id);
            
                $fabricstock = SaleFinalTripalEntry::create([
                    'name' => $find_name->name,
                    'slug' => $find_name->slug,
                    'roll' => $find_name->roll_no,
                    'gross' => $find_name->gross_wt,
                    'net' => $find_name->net_wt,
                    'meter' => $find_name->meter,
                    'gram' => $find_name->gram,
                    'average' => $find_name->average_wt,
                    'gsm' => $find_name->gsm,
                    'godam_id' => $find_name->department_id,
                    'bill_type' => $find_name->bill_number,
                    'bill_no' => $request->bill_no,
                    'bill_date' => $find_name->bill_date,
                    'finaltripal_id' => $find_name->finaltripalname_id,
                    'salefinal_id' => $request->salefinal_id,
                    'stock_id' => $request->data_id,
                    'status' => 'sent',
                ]);

        return response(['message'=>'sale Transferred Successfully']);
        }
        catch (Exception $ex){
            dd($ex);
            return $ex;
        }
    

    }

    public function finalTripalStoreList(Request $request)
    {
        try{
            $getlist = SaleFinalTripalEntry::where('salefinal_id',$request->salefinal_id)->where('status','sent')->get();

            foreach ($getlist as $list) {

                $find_name = SaleFinalTripalEntry::find($list->id);
                // dd($find_name);
                    $fabricstock = SaleFinalTripalList::create([
                        'name' => $find_name->name,
                        'slug' => $find_name->slug,
                        'roll' => $find_name->roll,
                        'gross' => $find_name->gross,
                        'net' => $find_name->net,
                        'meter' => $find_name->meter,
                        'gram' => $find_name->gram,
                        'average' => $find_name->average,
                        'gsm' => $find_name->gsm,
                        'godam_id' => $find_name->godam_id,
                        'bill_type' => $find_name->bill_type,
                        'bill_no' => $request->bill_no,
                        'bill_date' => $find_name->bill_date,
                        'finaltripal_id' => $find_name->finaltripal_id,
                        'bill_no' => $find_name->bill_no,
                        'salefinal_id' => $request->salefinal_id,
                    ]);

                    if($fabricstock){

                      FinalTripalStock::where('id',$find_name->stock_id)->delete();
                    }

                    $getlist = SaleFinalTripalEntry::where('salefinal_id',$request->salefinal_id)->update(['status' => 'completed']);


            }

            $getlist = SaleFinalTripal::where('id',$request->salefinal_id)->update(['status' => 'completed']);



        return response(['message'=>'sale Transferred Successfully']);
        }
        catch (Exception $ex){
            return $ex;
        }
    

    }

    public function downloadPdf(Request $request,$id)
       {


           $findtripal = SaleFinalTripal::find($id);
           $data = SaleFinalTripalList::where('salefinal_id',$id)->orderBy('name')->get();

           $total_gross = SaleFinalTripalList::where('salefinal_id',$id)->sum('gross');
           $total_net = SaleFinalTripalList::where('salefinal_id',$id)->sum('net');
           $total_meter = SaleFinalTripalList::where('salefinal_id',$id)->sum('meter');

           $totaltripals = SaleFinalTripalList::where('salefinal_id',$id)->select('name', DB::raw('SUM(gross) as total_gross'),DB::raw('SUM(net) as total_net'),DB::raw('SUM(meter) as total_meter'),DB::raw('COUNT(name) as total_count'))
               ->groupBy('name')
               ->get();


           $pdf = PDF::loadView('admin.sale.salefinaltripal.pdf', [
               'data' => $data,
               'findtripal' => $findtripal,
               'total_gross' => $total_gross,
               'total_net' => $total_net,
               'total_meter' => $total_meter,
               'totaltripals' => $totaltripals,
           ]);

           return $pdf->download('tripal_sale.pdf');
       }

    public function downloadExcel(Request $request,$id)
       {
        
         return Excel::download(new TripalSale($id), 'tripalsale.xlsx');
       }  

    public function restock(Request $request)
       {
        try{
            DB::beginTransaction();

            $getdata = SaleFinalTripalList::get();

            foreach ($getdata as $list) {

               $finaltripal_id = FinalTripalName::where('name',$list->name)->value('id');

               $net_wt = $list->net;
               $meter = $list->meter;
               $average = ($net_wt / $meter) * 1000;


               $input = $list->name;
               $parts = explode(' ', $input);
               $firstString = $parts[0];   
                       
               $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

               $data = $find_name / 39.37;

               $gsm = ($average) / $data;
                  

              $finaltripalstock = FinalTripalStock::create([
                        "name" => $list->name,
                        "slug" => $list->slug,
                        "bill_number" => $list->bill_type,
                        'bill_date' => $list->bill_date,
                        "department_id" => '2',
                        "gram" =>  $list->gram,
                        "loom_no" => '0',
                        "roll_no" => $list->roll,
                        'gross_wt' => $list->gross,
                        "meter" => $list->meter,
                        "average_wt" => $list->average,
                        "gsm" => $gsm,
                        'net_wt' => $list->net,
                        "finaltripalname_id" => $list->finaltripal_id,
                        "date_en" => $list->bill_date,
                        "date_np" => $list->bill_date,

                        "status" => "sent"
                    ]);


            }
        


            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            return response([
                "message" => "Something went wrong!{$e->getMessage()}" 
            ]);
        }
         

         return back();
        
       }       


}
