<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\StoreinCategory;
use App\Models\Charges;
use App\Models\StoreinDepartment;
use App\Models\SubCategory;
 use App\Models\StoreinType;
 use App\Models\Godam;

// use DBApp\Models\Setupstoreout;
use App\Models\Storein;
use App\Models\ItemsOfStorein;
use App\Models\Size;
use App\Models\Stock;
use App\Models\StoreinItem;
use App\Models\Tax;
use App\Models\PurchaseStoreinReport;
use Carbon\Carbon;

use Carbon\CarbonPeriod;
use DB;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;



class StoreinController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $storeinDatas = Storein::with('supplier')->orderBy('created_at', 'DESC')->get();
        $godams=Godam::where('status','active')->get(['id','name']);
        return view('admin.storein.index', compact('storeinDatas','godams'));
    }


    public function categoryReport(){
         $categories=StoreinCategory::where('status','active')->get(['id','name']);
          return view('admin.storein.reportPages.categoryReport',compact('categories'));
    }

    public function generateCategoryReportView(Request $request){
           if (!$request->start_date || !$request->end_date || !$request->storein_cat_id) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date and department' ]);
        }
        $reportData = [];
        $dates = [];
        $dates = $this->getEngDateRange($request->start_date, $request->end_date);
        $firstDate = reset($dates);
        $lastDate = end($dates);
        // dd($lastDate);
        foreach ($dates as $date) {
           $storeiItems = DB::table('storein_item')
            ->where('storein_item.storein_category_id',$request->storein_cat_id)
            ->join('storein', 'storein_item.storein_id', '=', 'storein.id')
            ->join('suppliers','storein.supplier_id','=', 'suppliers.id')
            // ->join('storein_categories', 'storein_item.storein_category_id', '=', 'storein_categories.id')
            ->join('items_of_storeins', 'storein_item.storein_item_id', '=', 'items_of_storeins.id')
            ->join('sizes', 'storein_item.size_id', '=', 'sizes.id')
            ->join('units', 'storein_item.unit_id', '=', 'units.id')
            ->join('storein_departments', 'storein_item.department_id', '=', 'storein_departments.id')
            ->select(
                'storein_item.*',
                'suppliers.name as supplier_name',
                'storein.sr_no as sr_no',
                'storein_departments.name as storein_departments_name',
                'items_of_storeins.name as item_name',
                'sizes.name as sizes_name',
                'units.name as units_name',
                'storein.purchase_date'
            )
            ->where('storein.purchase_date', $date);

            $storeiItems = $storeiItems->get();
            if (!$storeiItems->isEmpty()) {
                $storeinView = view('admin.storein.reportview.catWiseReport', compact('storeiItems', 'date','firstDate','lastDate'))->render();
                array_push($reportData, $storeinView);
            }

        }
        return response(['status' => true, 'data' => $reportData,'firstDate'=>$firstDate, 'lastDate'=>$lastDate]);
    }
    public function supplierReport(){
         $suppliers=Supplier::where('status','1')->get(['id','name']);
          return view('admin.storein.reportPages.supplierReport',compact('suppliers'));
    }

    public function generateSupplierReportView(Request $request){
        if (!$request->start_date || !$request->end_date || !$request->supplier_id) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date and department' ]);
        }
        $reportData = [];
        $dates = [];
        $dates = $this->getEngDateRange($request->start_date, $request->end_date);
        // $firstDate = reset($dates);
        // $lastDate = end($dates);
        // dd($lastDate);
        foreach ($dates as $date) {
           $storeiItems = DB::table('storein_item')
            ->join('storein', 'storein_item.storein_id', '=', 'storein.id')
            ->join('suppliers','storein.supplier_id','=', 'suppliers.id')
            // ->join('storein_categories', 'storein_item.storein_category_id', '=', 'storein_categories.id')
            ->join('items_of_storeins', 'storein_item.storein_item_id', '=', 'items_of_storeins.id')
            ->join('sizes', 'storein_item.size_id', '=', 'sizes.id')
            ->join('units', 'storein_item.unit_id', '=', 'units.id')
            ->join('storein_departments', 'storein_item.department_id', '=', 'storein_departments.id')

            ->select(
                'storein_item.*',
                'storein.sr_no as sr_no',
                'items_of_storeins.name as item_name',
                'sizes.name as sizes_name',
                'units.name as units_name'
            )
            ->where('storein.purchase_date', $date)
            ->where('storein.supplier_id',$request->supplier_id)
            ;

            $storeiItems = $storeiItems->get();
            if (!$storeiItems->isEmpty()) {
                $storeinView = view('admin.storein.reportview.supplierWiseReort', compact('storeiItems', 'date'))->render();
                array_push($reportData, $storeinView);
            }

        }
        return response(['status' => true, 'data' => $reportData]);
    }

    public function storeinTypeReport(){
         $storeinTypes=StoreinType::where('status','active')->get(['id','name']);
          return view('admin.storein.reportPages.storeinTypeReport',compact('storeinTypes'));
    }

    public function generateStoreinTypeReportView(Request $request){
         if (!$request->start_date || !$request->end_date || !$request->storein_type_id) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date and storein type' ]);
        }
        $reportData = [];
        $dates = [];
        $dates = $this->getEngDateRange($request->start_date, $request->end_date);
        // $firstDate = reset($dates);
        // $lastDate = end($dates);
        // dd($lastDate);
        foreach ($dates as $date) {
           $storeiItems = DB::table('storein_item')
            ->join('storein', 'storein_item.storein_id', '=', 'storein.id')
            ->join('suppliers','storein.supplier_id','=', 'suppliers.id')
            // ->join('storein_categories', 'storein_item.storein_category_id', '=', 'storein_categories.id')
            ->join('items_of_storeins', 'storein_item.storein_item_id', '=', 'items_of_storeins.id')
            ->join('sizes', 'storein_item.size_id', '=', 'sizes.id')
            ->join('units', 'storein_item.unit_id', '=', 'units.id')
            ->join('storein_departments', 'storein_item.department_id', '=', 'storein_departments.id')

            ->select(
                'storein_item.*',
                'storein.sr_no as sr_no',
                'items_of_storeins.name as item_name',
                'sizes.name as sizes_name',
                'units.name as units_name'
            )
            ->where('storein.purchase_date', $date)
            ->where('storein.storein_type_id',$request->storein_type_id)
            ;

            $storeiItems = $storeiItems->get();
            if (!$storeiItems->isEmpty()) {
                $storeinView = view('admin.storein.reportview.supplierWiseReort', compact('storeiItems', 'date'))->render();
                array_push($reportData, $storeinView);
            }

        }
        return response(['status' => true, 'data' => $reportData]);
    }

    public function srNoReport(){
        $srNos=Storein::get(['sr_no']);
        return view('admin.storein.reportPages.srNoReport',compact('srNos'));
    }

    public function generateSrNoReportView(Request $request){
          if ( !$request->sr_no) {
            return response(['status' => false, 'message' => 'Please select Sr no' ]);
            }
           $storein = Storein::with('storeinItems',
           'supplier:id,name',
           'storeinType:id,name',
           'storeinItems.storeinDepartment:id,name',
           'storeinItems.itemsOfStorein:id,name',
           'storeinItems.size:id,name',
           'storeinItems.unit:id,name')
           ->where('sr_no',$request->sr_no)
           ->first();
           $Extra_charges= json_decode($storein->extra_charges);
           if (!$storein){return response(['status' => false,'message'=>'No Data Found']);}
            $storeinView = view('admin.storein.reportview.srNoWiseReport', compact('storein','Extra_charges'))->render();
            return response(['status' => true,'data'=>$storeinView]);
    }

    public function itemReport(){
        $items= ItemsOfStorein::select('name')->distinct('name')->get();
         return view('admin.storein.reportPages.itemReport',compact('items'));

    }

    public function getItemSize(Request $request){
        if(!$request->item_name){return null;}
        $sizes = ItemsOfStorein::with('size:id,name')->where('name',$request->item_name)->get(['size_id']);
        return response()->json($sizes,200);

    }

    public function generateItemReportView(Request $request){
           if (!$request->start_date || !$request->end_date || !$request->item_name || !$request->size_id) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date and storein type' ]);
        }
        $reportData = [];
        $dates = [];
        $dates = $this->getEngDateRange($request->start_date, $request->end_date);

       foreach ($dates as $date) {
           $storeiItems = DB::table('storein_item')
            ->join('storein', 'storein_item.storein_id', '=', 'storein.id')
            ->join('suppliers','storein.supplier_id','=', 'suppliers.id')
            ->join('items_of_storeins', 'storein_item.storein_item_id', '=', 'items_of_storeins.id')
            ->join('sizes', 'storein_item.size_id', '=', 'sizes.id')
            ->join('units', 'storein_item.unit_id', '=', 'units.id')
            ->join('storein_departments', 'storein_item.department_id', '=', 'storein_departments.id')
            ->select(
                'storein_item.*',
                'storein.sr_no as sr_no',
                'suppliers.name as supplier_name',
                'sizes.name as size',
                'units.name as unit',
                'storein_departments.name as department_name'
            )
            ->where('storein.purchase_date', $date)
            // ->where('items_of_storeins.name',$request->item_name)
            // ->where('items_of_storeins.size_id',$request->size_id)
            ;

            $storeiItems = $storeiItems->get();
            if (!$storeiItems->isEmpty()) {
                $storeinView = view('admin.storein.reportview.itemWiseReport', compact('storeiItems', 'date'))->render();
                array_push($reportData, $storeinView);
            }

        }
        return response(['status' => true, 'data' => $reportData]);

    }

    public function entryReport(){

        $departments= StoreinDepartment::where('status','active')->get(['id','name']);
        // $itemNames= ItemsOfStorein::where('status','active')->get(['id','name']);
        return view('admin.storein.reportPages.entryReport',compact('departments'));
    }
     public function generateEntryReportView(Request $request)
    {
        if (!$request->start_date || !$request->end_date || !$request->storein_dept_id) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date and department' ]);
        }
        $reportData = [];
        $dates = [];
        $dates = $this->getEngDateRange($request->start_date, $request->end_date);

        foreach ($dates as $date) {
           $storeiItems = DB::table('storein_item')
            ->where('storein_item.department_id',$request->storein_dept_id)
            ->join('storein', 'storein_item.storein_id', '=', 'storein.id')
            ->join('storein_categories', 'storein_item.storein_category_id', '=', 'storein_categories.id')
            ->join('items_of_storeins', 'storein_item.storein_item_id', '=', 'items_of_storeins.id')
            ->join('sizes', 'storein_item.size_id', '=', 'sizes.id')
            ->join('units', 'storein_item.unit_id', '=', 'units.id')
            ->join('storein_departments', 'storein_item.department_id', '=', 'storein_departments.id')
            ->select(
                'storein_item.*',
                'storein_categories.name as storein_categories_name',
                'storein_departments.name as storein_departments_name',
                'items_of_storeins.name as item_name',
                'sizes.name as sizes_name',
                'units.name as units_name',
                'storein.purchase_date'
            )
            ->where('storein.purchase_date', $date);

            $storeiItems = $storeiItems->get();
            if (!$storeiItems->isEmpty()) {
                $storeinView = view('admin.storein.reportview.WiseReport', compact('storeiItems', 'date'))->render();
                array_push($reportData, $storeinView);
            }

        }
        // return response(['status' => true, 'data' => $reportData]);
        // Summary Part Code

        $startDate = $dates[0];
        $endDate = end($dates);

        $summaryStoreIn = DB::table('storein_item')
        ->where('storein_item.department_id',$request->storein_dept_id)
        ->join('storein', 'storein_item.storein_id', '=', 'storein.id')
        ->join('storein_categories', 'storein_item.storein_category_id', '=', 'storein_categories.id')
        ->join('items_of_storeins', 'storein_item.storein_item_id', '=', 'items_of_storeins.id')
        ->join('sizes', 'storein_item.size_id', '=', 'sizes.id')
        ->join('units', 'storein_item.unit_id', '=', 'units.id')
        ->select(
            'items_of_storeins.name as item_name',
            'sizes.name as size_name',
            'units.name as unit_name',
            'storein_categories.name as storein_category_name',
            DB::raw('SUM(storein_item.quantity) as total_quantity'),
            DB::raw('SUM(storein_item.total_amount) as grand_total'),
        )
        ->groupBy('items_of_storeins.name','sizes.name','units.name','storein_categories.name');

        if ($request->start_date && $request->end_date) {
            $summaryStoreIn = $summaryStoreIn->where('storein.purchase_date', '>=', $request->start_date)
            ->where('storein.purchase_date', '<=', $request->end_date);
        }

        $summaryStoreIn = $summaryStoreIn->get();
        $summaryView = view('admin.storein.reportview.summary', compact('summaryStoreIn', 'startDate', 'endDate'))->render();
        array_push($reportData, $summaryView);
         return response(['status' => true, 'data' => $reportData]);
    }
      private function getEngDateRange($startDate, $endDate)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate   = Carbon::createFromFormat('Y-m-d', $endDate);

        $dateRange = CarbonPeriod::create($startDate, $endDate);

        $dates = array_map(fn ($date) => $date->format('Y-m-d'), iterator_to_array($dateRange));

        return $dates;
    }
    /**
     * Show the form for creating a new purchase.
     *
     * @return \Illuminate\Http\Response
     */
    //  m

    public function categoryNameForTbl($id)
    {
        $categoryName = StoreinCategory::find($id)->get('name');
        return $categoryName;
    }

    public function createStorein()
    {
        $storeintype = StoreinType::all();
        $suppliers = Supplier::where('status', 1)->latest()->get();

        $storeinData = null;
        return view('admin.storein.storeinCreate', compact('storeintype', 'suppliers','storeinData'));
    }
    //  public function editStorein($storein_id)
    // {
    //     // return $storein_id;
    //     $storeintype = StoreinType::all();
    //     $suppliers = Supplier::where('status', 1)->latest()->get();
    //     $storeinData = Storein::with('storeinType', 'supplier')->find($storein_id);
    //     // return $storeinData;
    //     return view('admin.storein.storeinCreate', compact('storeintype', 'suppliers', 'storeinData'));
    // }
    public function editStorein($storein_id)
    {
        return redirect()->route('storein.storeinItemCreate',['id'=>$storein_id]);
    //     $storeintype = StoreinType::all();
    //     $suppliers = Supplier::where('status', 1)->latest()->get();
    //     $storeinData = DB::table('storein')
    //     ->join('suppliers', 'suppliers.id', '=', 'storein.supplier_id')
    //     ->join('storein_types', 'storein_types.id', '=', 'storein.storein_type_id')
    //     ->select(
    //         'storein.id',
    //         'suppliers.name as supplier_name',
    //         'suppliers.id as supplier_id',
    //         'storein_types.name as storein_type_name',
    //         'storein_types.id as storein_type_id',
    //         'storein.sr_no',
    //         'storein.bill_no',
    //         'storein.pp_no',
    //         'storein.purchase_date',
    //         'storein.total_discount',
    //         'storein.grand_total'
    //     )
    //     ->where('storein.id', '=', $storein_id)
    //     ->first();
    //   //  return $storeinData;

    //     return view('admin.storein.storeinCreate', compact('storeintype', 'suppliers', 'storeinData'));
    }

    // public function storinYajraDatabales()
    // {
    //     $storein = DB::table('storein')
    //     ->join('suppliers','suppliers.id','=','storein.supplier_id')
    //     ->join('storein_types','storein_types.id','=','storein.storein_type_id')
    //     ->select(
    //         'storein.id',
    //         'suppliers.name as supplier_name',
    //         'storein_types.name as storein_type_name',
    //         'storein.sr_no',
    //         'storein.bill_no',
    //         'storein.pp_no',
    //         'storein.purchase_date',
    //         'storein.total_discount',
    //         'storein.status',
    //         'storein.grand_total',
    //         )
    //     ->get();

    //     return DataTables::of($storein)
    //         ->addIndexColumn()
    //         ->addColumn('action', function ($row) {
    //             $actionBtn ='';
    //             if($row->status == 'running'){
    //                 $actionBtn .= '
    //                     <a class="btnEdit" href="' . route('storein.editStorein', ["storein_id" => $row->id]) . '" >
    //                         <button class="btn btn-primary">
    //                             <i class="fas fa-edit fa-lg"></i>
    //                         </button>
    //                     </a>
    //                     <button class="btn btn-danger" id="dltstorein" data-id="'.$row->id.'">
    //                         <i class="fas fa-trash-alt"></i>
    //                     </button>
    //                     ';
    //             }else{
    //                 $actionBtn .=  '
    //                 <a class="btnEdit" href="' . route('storein.invoiceView', ["storein_id" => $row->id]) . '" >
    //                     <button class="btn btn-info">
    //                         <i class="fas fa-file-invoice"></i>
    //                     </button>
    //                 </a>
    //                 ';
    //             }
    //             return $actionBtn;
    //         })
    //        ->addColumn('status', function ($row) {
    //             return '<span class="badge badge-pill badge-success">'.$row->status.'</span>';
    //         })
    //         ->rawColumns(['action','status'])
    //         ->make(true);
    // }

    public function storinYajraDatabales(Request $request)
{

    $batchSize = 100;

    $storein = DB::table('storein')
        ->join('suppliers', 'suppliers.id', '=', 'storein.supplier_id')
        ->join('storein_types', 'storein_types.id', '=', 'storein.storein_type_id')
        ->select(
            'storein.id',
            'suppliers.name as supplier_name',
            'storein_types.name as storein_type_name',
            'storein.sr_no',
            'storein.bill_no',
            'storein.pp_no',
            'storein.purchase_date',
            'storein.total_discount',
            'storein.status',
            'storein.grand_total',
        )
        ->orderBy('storein.id', 'DESC');
        if ($request->start_date) {
                $start_date = $request->input('start_date');
                $end_date =  now()->format('Y-m-d');
                $storein->whereBetween('purchase_date', [$start_date, $end_date]);

        }
        if ($request->start_date && $request->end_date ) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $storein->whereBetween('purchase_date', [$start_date, $end_date]);

        }
       $storein=$storein->get();
        //   if ($request->godam_id) {
        //         $query->where('godam_id', (int)$request->godam_id);
        //     }

    $storeinChunks = array_chunk($storein->toArray(), $batchSize);

    $reportData = [];

    foreach ($storeinChunks as $batch) {
        $storeinBatch = collect($batch);

        foreach ($storeinBatch as $row) {
            $actionBtn = '';
            if ($row->status == 'running') {
                $actionBtn .= $this->generateRunningAction($row);
            } else {
                $actionBtn .= $this->generateNonRunningAction($row);
            }
            $statusBadge = '<span class="badge badge-pill badge-success">' . $row->status . '</span>';

            $reportData[] = [
                'id' => $row->id,
                'supplier_name' => $row->supplier_name,
                'storein_type_name' => $row->storein_type_name,
                'sr_no' => $row->sr_no,
                'bill_no' => $row->bill_no,
                'pp_no' => $row->pp_no,
                'purchase_date' => $row->purchase_date,
                'total_discount' => $row->total_discount,
                'status' => $statusBadge,
                'grand_total' => $row->grand_total,
                'action' => $actionBtn,
            ];
        }
    }

    return DataTables::of($reportData)
        ->addIndexColumn()
        ->rawColumns(['action', 'status'])
        ->make(true);
}



    private function generateRunningAction($item)
    {
        return '
            <a class="btnEdit" href="' . route('storein.editStorein', ["storein_id" => $item->id]) . '" >
                <button class="btn btn-primary">
                    <i class="fas fa-edit fa-lg"></i>
                </button>
            </a>
            <button class="btn btn-danger" id="dltstorein" data-id="' . $item->id . '">
                <i class="fas fa-trash-alt"></i>
            </button>
        ';
    }

    private function generateNonRunningAction($item)
    {
        return '
            <a class="btnEdit" href="' . route('storein.invoiceView', ["storein_id" => $item->id]) . '" >
                <button class="btn btn-info">
                    <i class="fas fa-file-invoice"></i>
                </button>
            </a>
        ';
    }


    public function updateStorein(Request $request, $storein_id)
    {

            $validator = $request->validate([
                'type' => 'required',
                'ppno' => [
                    Rule::requiredIf(function () use ($request) {
                         return in_array(StoreinType::find($request->type)->name, ['import']);
                    }),
                ],
                'srno' => [
                    Rule::requiredIf(function () use ($request) {
                        return in_array(StoreinType::find($request->type)->name, ['import','local']);
                   }),
                ],
                'billno' => [
                    Rule::requiredIf(function () use ($request) {
                        return in_array(StoreinType::find($request->type)->name, ['sapat','local']);
                   }),
                ],
                'purchaseDate' => 'required|date',
                'supplier' => 'required',
            ]);

        $storein = Storein::find($storein_id);
        $storein->sr_no = $request->srno;
        $storein->bill_no = $request->billno;
        $storein->pp_no = $request->ppno;
        $storein->purchase_date = $request->purchaseDate;
        $storein->storein_type_id = $request->type;
        $storein->supplier_id = $request->supplier;
        $storein->save();
        // return $storein;
        return redirect()->route('storein.storeinItemCreate', ['id' => $storein->id]);
    }

    public function saveStorein(Request $request)
    {
        $validator = $request->validate([
            'type' => 'required',
            'ppno' => [
                Rule::requiredIf(function () use ($request) {
                     return in_array(StoreinType::find($request->type)->name, ['import']);
                }),
            ],
            'srno' => [
                Rule::requiredIf(function () use ($request) {
                    return in_array(StoreinType::find($request->type)->name, ['import','local']);
               }),
            ],
            'billno' => [
                Rule::requiredIf(function () use ($request) {
                    return in_array(StoreinType::find($request->type)->name, ['sapat','local']);
               }),
            ],
            'purchaseDate' => 'required|date',
            'supplier' => 'required',
        ]);

        $storein = new Storein();
        $storein->sr_no = $request->srno ? $request->srno : null;
        $storein->bill_no = $request->billno ? $request->billno : null;
        $storein->pp_no = $request->ppno ? $request->ppno : null;
        $storein->purchase_date = $request->purchaseDate;
        $storein->storein_type_id = $request->type;
        $storein->supplier_id = $request->supplier;
        $storein->save();
        return redirect()->route('storein.storeinItemCreate', ['id' => $storein->id]);
    }
    public function saveImg($request)
    {
        $image = $request->file('purchaseImage');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/storeinImages', $imageName);
        $imagePath = 'storage/storeinImages/' . $imageName;
        return $imagePath;
    }
    public function saveEntireStorein(Request $request, $storein_id)
    {
        try{
        $today= Carbon::now()->format('Y-n-j');
        DB::beginTransaction();
        $storein = Storein::with('storeinItems')
            ->withSum('storeinItems', 'total_amount')
            ->find($storein_id);

        $storein->sub_total = $storein->storein_items_sum_total_amount;
        $grandTotal = $storein->sub_total;
        //checking weather discount percentage has came or amount
        if ($request->discount_percent && $request->discount_percent != null && $request->discount_percent != 0) {
            $storein->discount_percent = $request->discount_percent;
            $storein->total_discount =  ($request->discount_percent * $storein->sub_total) / 100;
            $grandTotal -= $storein->total_discount;
        } else {
            $storein->total_discount =  $request->discount_amount ? $request->discount_amount : 0;
            $grandTotal -= $storein->total_discount;
        }
        $chargeArray = [];
        $chargeAmount = array_values(array_filter($request->chargeAmount, function($value) {
            return $value !== null;
        }));
        $chargeTotal = array_values(array_filter($request->chargeTotal, function($value) {
            return $value !== null;
        }));
        $chargeOperator = array_values(array_filter($request->chargeOperator, function($value) {
            return $value !== null;
        }));

        if ($request->chargeName && count($request->chargeName) > 0) {
            for ($i = 0; $i < count($chargeAmount); $i++) {
                    $chargeArr = explode('-', $request->chargeName[$i]);
                    $charge_id = $chargeArr[0];
                    $charge_name = $chargeArr[1];

                    $charge_amount = $chargeAmount[$i] ? $chargeAmount[$i] : '0';
                    $charge_operator = $chargeOperator[$i];
                    $charge_total = $chargeTotal[$i] ? $chargeTotal[$i] : '0';

                    $chargeArray[] = [
                        'charge_id' => $charge_id,
                        'charge_name' => $charge_name,
                        'charge_amount' => $charge_amount,
                        'charge_operator' => $charge_operator,
                        'charge_total' => $charge_total
                    ];
                    $grandTotal += $charge_total;
            }
        }
        $storein->grand_total = $grandTotal;
        $storein->extra_charges = json_encode($chargeArray);
        // save image
        if ($request->hasFile('purchaseImage')) {
            $imgPath = self::saveImg($request);
            $storein->image_path = $imgPath;
        }
        $storein->note = $request->note;
        $storein->status = 'completed';
        // storein status change
        $storein->save();


        foreach($storein->storeinItems as $item){

        $purchaseStoreinReport=PurchaseStoreinReport::where('name',$item->storein_item_id)
        ->where('date',$today)
        ->first();
        if($purchaseStoreinReport){
            $purchaseStoreinReport->quantity += $item->quantity;
            $purchaseStoreinReport->save();
        }else{
            $purchaseStoreinReport= new PurchaseStoreinReport();
                    $purchaseStoreinReport->date =$today;
                    $purchaseStoreinReport->name =$item->storein_item_id;
                    $purchaseStoreinReport->quantity =$item->quantity;
                    $purchaseStoreinReport->rate = $item->price;
                    $purchaseStoreinReport->total =$purchaseStoreinReport->quantity * $purchaseStoreinReport->rate;
                    $purchaseStoreinReport->save();
                }
            }
        DB::commit();
        return redirect()->route('storein.index')->withSuccess('Storein entirely created successfully!');
        }catch(Exception $e){
            DB::rollback();
            return 'something went wrong on saving storein entirely';

        }
    }


    public function getTaxPercentage($tax_slug)
    {
        return Tax::where('slug', $tax_slug)->first();
    }
    public function invoiceView($storein_id)
    {
        $storein = Storein::with(['supplier', 'storeinType', 'storeinItems', 'storeinItems.itemsOfStorein'])->find($storein_id);
        $charges = $storein->extra_charges ? json_decode($storein->extra_charges) : [];
        return view('admin.storein.viewStorein', compact('storein', 'charges'));
    }

    public function getcategoryItems(Request $request)
    {
        $PAGINATE_NO=50;
        $query = $request->input('query');
        $category_id =$request->input('category_id');

        $queryBuilder =DB::table('items_of_storeins')
        ->join('storein_categories','storein_categories.id','=','items_of_storeins.category_id')
        ->where('items_of_storeins.category_id',$request->category_id)
        ->select('items_of_storeins.name as id', 'items_of_storeins.name as text')
        ->distinct();

        if ($query !== null) {
            $queryBuilder->where('items_of_storeins.name', 'like', '%' . $query . '%');
        }
        $items = $queryBuilder->paginate($PAGINATE_NO);

         return response()->json([
            'data' => $items->items(),
            'next_page_url' => $items->nextPageUrl(),
        ]);
    }

    public function getDepartmentSizeUnit(Request $request){

        // $encodedName = urldecode($items_of_storein_name);
        $items_of_storein =ItemsOfStorein::with('storeinDepartment:id,name','unit:id,name','size:id,name')
        ->where('name', $request->items_of_storein_name)
        ->where('category_id',$request->category_id)
        ->groupBy(['size_id','unit_id','department_id'])
        ->get(['size_id','unit_id','department_id']);

        $ArrayItemStorein =$items_of_storein->toArray();

        $unitArray = [];
        $sizeArray = [];
        $departmentArray = [];

        foreach ($items_of_storein as $item) {
            $unit = $item->unit;
            $size = $item->size;
            $department = $item->storeinDepartment;

            if (!in_array($unit, $unitArray, true)) {
                $unitArray[] = $unit;
            }

            if (!in_array($size, $sizeArray, true)) {
                $sizeArray[] = $size;
            }

            if (!in_array($department, $departmentArray, true)) {
                $departmentArray[] = $department;
            }
        }

        return response()->json(
            [
                'units' => $unitArray,
                'size' => $sizeArray,
                'department' => $departmentArray
            ]
            );
    }
    public function EditItemStoreData(Request $request)
    {
        try{
            return $request;
            DB::beginTransaction();
            $id = $request->storein_item_id;
            $storeinItem = StoreinItem::find($id);
            $old_item_quantity = $storeinItem->quantity;
            $old_item_total_amount = $storeinItem->total_amount;
            $old_item_id = $storeinItem->storein_item_id;

            $new_total_amount = $request->quantity * $request->unit_price;

            $storeinItem->storein_category_id = $request->category_id;
            $storeinItem->storein_item_id = $request->product_id;
            $storeinItem->quantity = $request->quantity;
            $storeinItem->price = $request->unit_price;
            $storeinItem->unit_id = $request->unit_id;

            // if ($request->discount_percentage == 0) {
            //     $storeinItem->discount_percentage = 0;

            //     $storeinItem->discount_amount = 0;
            //     $storeinItem->total_amount = $storeinItem->price * $storeinItem->quantity;
            // } else {
                // $storeinItem->discount_percentage = $request->discount_percentage;
                // $netTotal = $storeinItem->quantity * $storeinItem->price;
                // $storeinItem->discount_amount = ($netTotal *  $storeinItem->discount_percentage) / 100;
                $storeinItem->total_amount = $storeinItem->quantity * $storeinItem->price;
            // }
            $storeinItem->save();
            // return $storeinItem;
            Stock::updateStock($old_item_id, $old_item_total_amount, $old_item_quantity, $request, $new_total_amount);
            DB::commit();
            return self::getStoreInById($storeinItem->id);
        }catch(Exception $ex){
            DB::rollBack();
            return 'something went wrong';
        }

    }

    public function getEditItemData($storeinItem_id)
    {
        // return $storeinItem_id;
        $storeinItem = StoreinItem::with(['storeinCategory', 'itemsOfStorein', 'unit'])->where('id', $storeinItem_id)->first();
        return $storeinItem;
    }

    public function storeinItemDelete($id)
    {
        try{

        DB::beginTransaction();
        $storeinItem = StoreinItem::find($id);
        Stock::deleteStock($storeinItem->storein_item_id, $storeinItem->total_amount, $storeinItem->quantity);
        $storeinItem->delete();

        // checking if items is empty then set grand total to zero
        $storeIn = Storein::with('storeinItems')->find($storeinItem->storein_id);
        if (!$storeIn->storeinItems || count($storeIn->storeinItems) <= 0) {
            $storeIn->grand_total = 0;
            $storeIn->sub_total = 0;
            $storeIn->total_discount = 0;
            $storeIn->save();
        }
        DB::commit();
        return true;
    }catch(Exception $ex){
        DB::rollback();
        return'Something went wrong in deleting item';
    }
    }

    public function storeinDelete($id)
    {
        try{
         DB::beginTransaction();
            $storein= Storein::find($id);
            $storeinItems = $storein->storeinItems;
            if( $storeinItems && count($storeinItems) != 0){

                foreach($storeinItems as $storein_item){
                    $stock=Stock::where('item_id',$storein_item->storein_item_id)->first();
                    $stock->quantity -=$storein_item->quantity;
                    if($stock->quantity <= 0){
                        $stock->delete();
                    }
                    else{
                        $stock->save();
                    }
                }
            }
           $storein->delete();
            DB::commit();
        }catch(Exception $e){
                DB::rollBack();
                return $e;
        }

    }
    // m
    public function saveStoreinItems(Request $request, $id)
    {
        try{
        DB::beginTransaction();
        $validator = $request->validate([
            'category_id'  => 'required',
            'item_name'   => 'required',
            'quantity'      => 'required|numeric',
            'unit_price'   => 'required|numeric',
            'size_id'   => 'required',
            'unit_id'   => 'required',
            'department_id'   => 'required',
        ]);
       //  name comes here instead of item id so converting name to id
        $itemofstorein=ItemsOfStorein::where('name',$request->item_name)
        ->where('unit_id',$request->unit_id)
        ->where('category_id',$request->category_id)
        ->where('department_id',$request->department_id)
        ->where('size_id',$request->size_id)
        ->first();

        $totalAmt = ($request->quantity * $request->unit_price);

        $storeinItem = new StoreinItem();
        $storeinItem->storein_category_id = $request->category_id;
        $storeinItem->storein_item_id = $itemofstorein->id;
        $storeinItem->quantity = $request->quantity;
        $storeinItem->size_id=$request->size_id;
        $storeinItem->unit_id = $request->unit_id;
        $storeinItem->department_id =$request->department_id;
        $storeinItem->storein_id = $id;
        $storeinItem->price = $request->unit_price;
        $storeinItem->total_amount = $totalAmt;
        $storeinItem->save();

        //Stock::createStock($storeinItem);
        $stock = Stock::where('item_id', $storeinItem->storein_item_id )
        ->where('category_id', $storeinItem->storein_category_id)
        ->where('department_id', $storeinItem->department_id)
        ->where('unit',$storeinItem->unit_id)
        ->where('size',$storeinItem->size_id)
        ->first();
        //dd($stock);
        if (!$stock) {
        //   return('here');
            $stock = new Stock();
            $stock->quantity = $storeinItem->quantity;
            $stock->avg_price = round($storeinItem->price,2);
            $stock->total_amount = round($storeinItem->total_amount,2);
        } else {
            //  return('to add stock');
            $stock->quantity;
            $stock->quantity += $storeinItem->quantity;
           // dd($stock->quantity);
            $total = $stock->total_amount + $storeinItem->total_amount;
            $stock->avg_price = round($total / $stock->quantity,2);
            $stock->total_amount  =  round($stock->quantity * $stock->avg_price,2);
        }
        $stock->item_id = $storeinItem->storein_item_id;
        $stock->size = $storeinItem->size_id;
        $stock->unit = $storeinItem->unit_id;
        $stock->department_id = $storeinItem->department_id;
        $stock->category_id = $storeinItem->storein_category_id;
        $stock->save();
        DB::commit();
        return self::getStoreInById($storeinItem->id);

        }catch(Exception $ex)
        {
            DB::rollback();
            return 'some thing went wrong in store in item';
        }
    }

    //get size of storeinitem
    public function getSizeOfItems($items_of_storein_id){
      $sizeOfItems = ItemsOfStorein::with('size')->find($items_of_storein_id);
   // ->get(['size.size_id', 'size.name']);
        // foreach($sizeOfItems as $sizeOfItem){
        //     $size=Size::find($sizeOfItem)->get(['id','name']);
        // }
       // $size=Size::find('$sizeOfItem')->get(['id','name']);
        return $sizeOfItems;
        //  return response()->json([
        //         'size' => $sizeOfItem,
        //     ]);
    }

    // getItems department name
    public function getItemsDepartment($items_of_storein_name)
    {
        $items_of_storein =ItemsOfStorein::with('storeinDepartment')->where('name', $items_of_storein_name)->select('department_id')
        ->distinct()
        ->get();
        return $items_of_storein;
    }

    public function getUnitOfItems($items_of_storein_name){
        $items_of_storein =ItemsOfStorein::with('unit')->where('name', $items_of_storein_name)->select('department_id')
        ->distinct()
        ->get();
        return $items_of_storein;
    }
    // layout
    public function storeinItemCreate($id)
    {
        $storein = Storein::with('tax')->find($id);
        $categories = StoreinCategory::where('status', 1)->get();
        $items = ItemsOfStorein::with('storeinCategory')->get();
        $sizes = Size::where('status', 1)->get();
        $taxes = Tax::all();
        $units = Unit::get();
        $suppliers = Supplier::where('status', 1)->latest()->get();
        $charges = Charges::all();
        $departments = StoreinDepartment::where('status', 'active')->get();
        $addedCharges = $storein->extra_charges ? json_decode($storein->extra_charges) : [];
        $storeinItems = StoreinItem::where('storein_id', $storein->id)->with(['storeinCategory', 'itemsOfStorein', 'unit'])->get();
        return view('admin.storein.createItems', compact('storein', 'addedCharges', 'departments', 'categories', 'suppliers', 'items', 'units', 'charges', 'sizes', 'storeinItems', 'taxes'));
    }

    //to get department according to category
    public function getDepartentAccCat($category_id){
        return StoreinDepartment::where('category_id',$category_id)->get(['id','name']);
    }
    public function storeInItemsRetrive($storein_id)
    {
        return Storein::with(['storeinItems.storeinCategory:id,name', 'storeinItems.itemsOfStorein', 'storeinItems.unit:id,name', 'storeinItems.size:id,name','storeinItems.storeinDepartment:id,name'])->find($storein_id);
    }
    public function getStoreInById($id)
    {
        return StoreinItem::with(['storeinCategory:id,name', 'itemsOfStorein', 'unit:id,name', 'size:id,name','storeinDepartment:id,name'])->find($id);
    }



    // create pdf
    public function createPDF()
    {
        // retreive all records from db
        $data = Storein::latest()->get();
        // share data to view
        view()->share('purchases', $data);
        $pdf = PDF::loadView('admin.pdf.purchases', $data->all());
        // download PDF file with download method
        return $pdf->download('purchases-list.pdf');
    }
}
