<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\StoreOutItem;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Department;
use App\Models\Storeout;
use App\Models\ItemsOfStorein;
use App\Models\Placement;
use App\Models\Size;
use App\Models\Stock;
use App\Models\Godam;
use App\Models\StoreinDepartment;
use App\Models\StoreoutDepartment;
use App\Models\IssuedStoreinReport;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class StoreoutController extends Controller
{
    /**
     * Display a listing of the purchases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $storeOutDatas = Storeout::all();
        // return $storeOutDatas;
        return view('admin.storeout.index', compact('storeOutDatas'));
    }

    public function storoutYajraDatabales(Request $request)
    {
    $batchSize = 100;

    $storeout = DB::table('storeout')
        ->join('godam', 'godam.id', '=', 'storeout.godam_id')
        ->select(
            'storeout.id',
            'godam.name as storein_department_name',
            'storeout.receipt_date',
            'storeout.receipt_no',
            'storeout.total_amount',
            'storeout.status',
        )
        ->orderBy('storeout.id', 'DESC');
         if ($request->start_date) {
                $start_date = $request->input('start_date');
                $end_date =  now()->format('Y-m-d');
                $storeout->whereBetween('receipt_date', [$start_date, $end_date]);

        }
         if ($request->start_date && $request->end_date ) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $storeout->whereBetween('receipt_date', [$start_date, $end_date]);

        }
       $storeout=$storeout->get();

    $storeoutChunks = array_chunk($storeout->toArray(), $batchSize);

    $reportData = [];

    foreach ($storeoutChunks as $batch) {
        $storeoutBatch = collect($batch);

        foreach ($storeoutBatch as $row) {
            $actionBtn = '';
            if ($row->status == 'running') {
                $actionBtn .=$this->generateRunningAction($row);
            } else {
                $actionBtn .=$this->generateNonRunningAction($row);

            }

            $statusBadge = '<span class="badge badge-pill badge-success">' . $row->status . '</span>';

            $reportData[] = [
                'id' => $row->id,
                'storein_department_name' => $row->storein_department_name,
                'receipt_date' => $row->receipt_date,
                'receipt_no' => $row->receipt_no,
                'total_amount' => $row->total_amount,
                'status' => $statusBadge,
                'action' => $actionBtn,
            ];
        }
    }

    return DataTables::of($reportData)
        ->addIndexColumn()
        ->rawColumns(['action', 'status'])
        ->make(true);
}

public function invoiceView($storeout_id){
    $storeout = Storeout::with(['storeoutItems','godam:id,name', 'storeoutItems.itemsOfStorein'])->find($storeout_id);

    return view('admin.storeout.viewStoreout', compact('storeout'));
}

 public function receiptReport(){
        $godams= Godam::where('status','active')->get(['id','name']);
        return view('admin.storeout.reportPages.receiptReport',compact('godams'));
    }

public function getReceiptNo(Request $request){
    $receiptNos=StoreOut::where('godam_id',$request->godam_id)->get('receipt_no');
    return $receiptNos;
}

public function generateReceiptReportView(Request $request){
    // return $request;
  if (!$request->godam_id || !$request->receipt_no) {
            return response(['status' => false, 'message' => 'Please select godam and receipt no' ]);
        }
     $storeout = Storeout::with('storeoutItems',
           'godam:id,name',
           'storeoutItems.itemsOfStorein:id,name',
           'storeoutItems.size:id,name',
           'storeoutItems.unit:id,name',
           'storeoutItems.placement:id,name',

          )
           ->where('godam_id',$request->godam_id)
           ->where('receipt_no',$request->receipt_no)
           ->first();
        //    $Extra_charges= json_decode($storein->extra_charges);
           if (!$storeout){return response(['status' => false,'message'=>'No Data Found']);}
            $storeoutView = view('admin.storeout.reportview.receiptWiseReport', compact('storeout'))->render();
            return response(['status' => true,'data'=>$storeoutView]);
}

public function dateItemReport(){
        $godams= Godam::where('status','active')->get(['id','name']);
        $departments= StoreoutDepartment::where('status','active')->get(['id','name']);
        return view('admin.storeout.reportPages.dateItemReport',compact(['godams','departments']));
}

  public function generateDateItemReportView(Request $request){
        //  return $request;
         if (!$request->start_date || !$request->end_date || !$request->godam_id) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date and godam and department' ]);
        }
        $reportData = [];
        $dates = [];
        $dates = $this->getEngDateRange($request->start_date,$request->end_date);
        if( $request->department_id){
              $reportData = self::itemAccGodamDateDepartment($dates,$request);
    }
        else{
            return response(['status' => false, 'message' => 'Please select department' ]);

            // $reportData = self::itemAccGodamDate($dates,$request);
        }

        return response(['status' => true, 'data' => $reportData]);
  }

//   private function itemAccGodamDate($dates,$request){

//         $storeouts= Storeout::with('storeoutItems','godam:id,name', 'storeoutItems.department:id,name','storeoutItems.itemsOfStorein','storeoutItems.size','storeoutItems.unit','storeoutItems.placement')
//         ->where('receipt_date', '>=', $request->start_date)
//         ->where('receipt_date', '<=', $request->end_date)
//         ->where('godam_id',$request->godam_id)
//         ->orderBy('receipt_date', 'ASC')
//         ->get();

//         $dataAccDepartments =



//     return view('admin.storeout.reportview.dateGodamWiseItemReport', compact('storeouts', 'dates'))->render();

//   }

  public function itemAccGodamDateDepartment ($dates,$request){
     $reportData = [];
    foreach ($dates as $date) {
        $storeoutItems = DB::table('store_out_items')
        ->join('storeout','store_out_items.storeout_id','=','storeout.id')
        ->join('storeout_departments','store_out_items.storeoutDepartment_id','=','storeout_departments.id')
        ->join('godam', 'storeout.godam_id', '=', 'godam.id')
        ->join('items_of_storeins', 'store_out_items.item_of_storein_id', '=', 'items_of_storeins.id')
        ->join('placements', 'store_out_items.placement_id', '=', 'placements.id')
        ->join('sizes', 'store_out_items.size_id', '=', 'sizes.id')
        ->join('units', 'store_out_items.unit_id', '=', 'units.id')
        ->select('store_out_items.*',
        'placements.name as placement_name',
        'storeout.receipt_no as receipt_no',
        'godam.name as godam_name',
        'items_of_storeins.name as item_name',
        'sizes.name as size_name',
        'storeout_departments.name as store_out_departments_name',
        'units.name as units_name'
        )
        ->where('storeout.receipt_date', $date)
        ->where('storeout.godam_id',$request->godam_id)
        ->where('store_out_items.storeoutDepartment_id',$request->department_id)
        ;
        $storeoutItems = $storeoutItems->get();

        if (!$storeoutItems->isEmpty()) {
            $storeoutView = view('admin.storeout.reportview.dateWiseItemReport', compact('storeoutItems', 'date'))->render();
            array_push($reportData, $storeoutView);
        }

    }
    return $reportData;
  }

    public function placementReport(){
        //  $placement =DB::table('storeout')
        // ->join('store_out_items','store_out_items.storeout_id','=','storeout.id')
        // ->join('placements', 'store_out_items.placement_id', '=', 'placements.id')
        // ->where('storeout.receipt_date', '>=', '2023-07-14')
        // ->where('storeout.receipt_date', '<=', '2023-09-29')
        // ->where('storeout.godam_id','1')
        // ->select(
        //     'placements.id',
        //     'placements.name',
        //     DB::raw('SUM(store_out_items.total) as total')
        // )
        // ->groupBy('placements.id','placements.name')
        // ->get();


        $godams= Godam::where('status','active')->get(['id','name']);
        return view('admin.storeout.reportPages.placementReport',compact(['godams']));
    }
    public function generatePlacementReport(Request $request){
        $placements=DB::table('storeout')
        ->join('store_out_items','store_out_items.storeout_id','=','storeout.id')
        ->join('placements', 'store_out_items.placement_id', '=', 'placements.id')
        ->where('storeout.receipt_date', '>=', $request->start_date)
        ->where('storeout.receipt_date', '<=', $request->end_date)
        ->where('storeout.godam_id',$request->godam_id)
        ->select(
            'placements.id',
            'placements.name',
            DB::raw('SUM(store_out_items.total) as total')
        )
        ->groupBy('placements.id','placements.name')
        ->get();
         return response(['status' => true, 'data' => view('admin.storeout.reportview.placementWiseReport', compact('placements'))->render()]);
  }

    public function dateDepartPlacementReport(){

    //       $storeoutItems = DB::table('store_out_items')
    //         ->join('storeout','store_out_items.storeout_id','=','storeout.id')
    //         ->join('storeout_departments','store_out_items.storeoutDepartment_id','=','storeout_departments.id')
    //         ->join('godam', 'storeout.godam_id', '=', 'godam.id')
    //         ->join('items_of_storeins', 'store_out_items.item_of_storein_id', '=', 'items_of_storeins.id')
    //         ->join('placements','store_out_items.placement_id', '=', 'placements.id')
    //         ->join('sizes', 'store_out_items.size_id', '=', 'sizes.id')
    //         ->join('units', 'store_out_items.unit_id', '=', 'units.id')
    //         ->select('store_out_items.*',
    //         'placements.name as placement_name',
    //         'storeout.receipt_no as receipt_no',
    //         'godam.name as godam_name',
    //         'items_of_storeins.name as item_name',
    //         'sizes.name as size_name',
    //         'storeout_departments.name as store_out_departments_name',
    //         'units.name as units_name'
    // )->get();
    // return $storeoutItems;


            $godams= Godam::where('status','active')->get(['id','name']);
            $departments= StoreoutDepartment::where('status','active')->get(['id','name']);
            return view('admin.storeout.reportPages.dateDepartPlacementReport',compact(['godams','departments']));
    }
    public function getPlacement(Request $request){
        $placements=Placement::where('storeout_dpt_id',$request->department_id)
        ->where('godam_id',$request->godam_id)
        ->get(['id','name']);
        return $placements;
    }
    public function generatedateDepartPlacementReport(Request $request){
         if (!$request->start_date || !$request->end_date || !$request->godam_id || !$request->department_id || !$request->placement_id) {
            return response(['status' => false, 'message' => 'Please select Start date and End Date and department' ]);
        }
        $reportData = [];
        $dates = [];
        $dates = $this->getEngDateRange($request->start_date, $request->end_date);
        foreach ($dates as $date) {
             $storeoutItems = DB::table('store_out_items')
            ->join('storeout','store_out_items.storeout_id','=','storeout.id')
            ->join('storeout_departments','store_out_items.storeoutDepartment_id','=','storeout_departments.id')
            ->join('godam', 'storeout.godam_id', '=', 'godam.id')
            ->join('items_of_storeins', 'store_out_items.item_of_storein_id', '=', 'items_of_storeins.id')
            ->join('placements','store_out_items.placement_id', '=', 'placements.id')
            ->join('sizes', 'store_out_items.size_id', '=', 'sizes.id')
            ->join('units', 'store_out_items.unit_id', '=', 'units.id')
            ->select('store_out_items.*',
            'placements.name as placement_name',
            'storeout.receipt_no as receipt_no',
            'godam.name as godam_name',
            'items_of_storeins.name as item_name',
            'sizes.name as size_name',
            'storeout_departments.name as store_out_departments_name',
            'units.name as units_name'
            )
            ->where('storeout.receipt_date', $date)
            ->where('storeout.godam_id',$request->godam_id)
            ->where('store_out_items.storeoutDepartment_id',$request->department_id)
            ->where('store_out_items.placement_id',$request->placement_id)
            ;

            $storeoutItems = $storeoutItems->get();
            if (!$storeoutItems->isEmpty()) {
                $storeoutView = view('admin.storeout.reportview.dateDepartPlacementWiseReport', compact('storeoutItems', 'date'))->render();
                array_push($reportData, $storeoutView);
            }

        }
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

private function generateRunningAction($row)
{
    return '
    <a class="btnEdit" href="' . route('storeout.edit', ["storeout_id" => $row->id]) . '" >
        <button class="btn btn-primary">
            <i class="fas fa-edit fa-lg"></i>
        </button>
    </a>
    <button class="btn btn-danger" id="dltStoreout" data-id="' . $row->id . '">
        <i class="fas fa-trash-alt"></i>
    </button>
    ';
}

private function generateNonRunningAction($row)
{
    return '
    <a class="" href="' . route('storeout.invoiceView', ["storeout_id" => $row->id]) . '" >
        <button class="btn btn-info">
            <i class="fas fa-file-invoice"></i>
        </button>
    </a>
    ';
}


public function deleteStoreout($storeout_id){
    try{
         DB::beginTransaction();
            $storeout= Storeout::find($storeout_id);
            $storeoutItems = $storeout->storeoutItems;

            if( $storeoutItems && count($storeoutItems) != 0){

                foreach($storeoutItems as $storeOutItem){
                    $stock= Stock::where('item_id', $storeOutItem->item_of_storein_id)
                    ->where('department_id', $storeOutItem->storeoutDepartment_id)
                    ->where('size',$storeOutItem->size_id)
                    ->where('unit',$storeOutItem->unit_id)
                    ->first();
                    $cat_id=ItemsOfStorein::find($storeOutItem->item_of_storein_id)->category_id;
                    if(!$stock){
                        $stock=new Stock();
                        $stock->department_id = $storeOutItem->storeoutDepartment_id;
                        $stock->category_id= $cat_id;
                        $stock->item_id =$storeOutItem->item_of_storein_id;
                        $stock->size= $storeOutItem->size;
                        $stock->quantity= $storeOutItem->quantity;
                        $stock->unit=$storeOutItem->unit;
                        $stock->avg_price = $storeOutItem->rate;
                        $stock->total_amount = $storeOutItem->total;
                        $stock->save();
                    }
                    else{
                        $stock->quantity += $storeOutItem->quantity;
                        $stock->total_amount  =  $stock->quantity * $stock->avg_price;
                        $stock->save();
                    }
                }
            }
           $storeout->delete();
            DB::commit();
        }catch(Exception $e){
                DB::rollBack();
                return $e;
        }
    }
    //megha
    public function storeoutItemDelete($storeout_item_id)
    {
        try{
        DB::beginTransaction();
        $storeOutItem =StoreOutItem::find($storeout_item_id);

        $stock =Stock::where('item_id', $storeOutItem->item_of_storein_id)
        ->where('department_id', $storeOutItem->storeoutDepartment_id)
        ->where('unit',$storeOutItem->unit_id )
        ->where('size',$storeOutItem->size_id )
        ->first();

        if(!$stock){
            $stock= new Stock();
            $stock->department_id =  $storeOutItem->storeoutDepartment_id;
            $stock->category_id = $storeOutItem->itemsOfStorein->category_id;
            $stock->item_id = $storeOutItem->item_of_storein_id;
            $stock->quantity= $storeOutItem->quantity;
            $stock->size = $storeOutItem->size_id;
            $stock->unit = $storeOutItem->unit_id;
            $stock->avg_price = round($storeOutItem->rate,2);
            $stock->total_amount = round($storeOutItem->quantity * $storeOutItem->rate,2);
            $stock->save();
        }else{
            $stock->quantity += $storeOutItem->quantity;
            $stock->total_amount = round($stock->quantity*$stock->avg_price,2);
            $stock->save();
        }

        $storeOutItem->delete();
        DB::commit();
        return true;
        }
        catch(Expection $e){
            DB::rollback();
            return 'something went wrong while deleting item';
        }
    }


    public function create()
    {
        $receipt_no =  AppHelper::getStoreOutReceiptNo();
        $godams =Godam::get(['id','name']);
        $storeOut = null;
        return view('admin.storeout.create', compact('receipt_no', 'storeOut','godams'));
    }

    public function getEditItemData($storeoutItem_id)
    {
        $storeOutItemData = StoreOutItem::with('department', 'placement', 'itemsOfStorein')->find($storeoutItem_id);
        return $storeOutItemData;
    }

    public function edit($storeout_id)
    {
        return redirect()->route('storeout.storeOutItems',['store_out_id'=>$storeout_id]);
    //     $receipt_no = null;
    //    $godams =Godam::get(['id','name']);
    //     $storeOut = StoreOut::find($storeout_id);
    //     return view('admin.storeout.create', compact('receipt_no', 'storeOut','godams'));
    }

    public function saveStoreout(Request $request)
    {
       $request->validate([
            'receipt_date'          => 'required|string|max:50',
            'receipt_no'         => 'required|string|unique:storeout,receipt_no',
            'for_id'         => 'required',
        ]);
        try{
            $storeout = new Storeout;
            $storeout->receipt_date = $request->receipt_date;
            $storeout->receipt_no = $request->receipt_no;
            $storeout->godam_id = $request->for_id;
            $storeout->save();
            return redirect()->route('storeout.storeOutItems', ['store_out_id' => $storeout->id]);
        }catch (Exception $ex){
            return 'something went wrong while storing storeout';
        }
    }
    public function storeOutItems($store_out_id)
    {
        $storeOut = Storeout::find($store_out_id);
        $storeoutDepartment =StoreoutDepartment::get(['id','name']);
        $godams= Godam::get(['id','name']);
       // return  $godam;
       $placementforBlade=
        $stockCategory = DB::table('stocks')
        ->join('storein_categories','storein_categories.id','=','stocks.category_id')
        ->select('storein_categories.id as category_id','storein_categories.name as category_name')
        ->distinct('storein_categories.name')
        ->get();
        //return $stockCategory;
        return view('admin.storeout.createStoreoutItems', compact('storeOut','stockCategory','storeoutDepartment','godams'));
    }
    public function getDepartmentPlacements($dept_id,$storeout_id)
    {
        $godam_id=Storeout::find($storeout_id)->godam_id;
        return Placement::where('storeout_dpt_id', $dept_id)
        ->where('godam_id',$godam_id)
        ->get(['id','name']);
    }
    public function getStoreinItemAccCat(Request $request){

        $DEFAULT_OPTIONS = 50;
        $query = $request->input('query');
        $queryBuilder = DB::table('stocks')
        ->where('stocks.category_id', $request->category_id)
        ->join('items_of_storeins', 'items_of_storeins.id', 'stocks.item_id')
        ->join('storein_departments','storein_departments.id','=','stocks.department_id')
        ->select(
        'items_of_storeins.name as id',
        DB::raw("CONCAT(items_of_storeins.name, ' /', storein_departments.name) as text")
        )
        ->distinct('items_of_storeins.name','items_of_storeins.id','storein_departments.name');

        if ($query !== null) {
            $queryBuilder->where('items_of_storeins.name', 'like', '%' . $query . '%');
        }

        $items = $queryBuilder->paginate($DEFAULT_OPTIONS);

        return response()->json([
            'data' => $items->items(),
            'next_page_url' => $items->nextPageUrl(),
        ]);
    }

    //For Stock QTY RATE AJAX REQUEST
    // public function getStockQtyRate(Request $request){
    //     return DB::table('stocks')
    //     ->where('category_id', $request->cat_id)
    //     ->where('item_id',$request->item_id)
    //     ->where('size', $request->side_id)
    //     ->where('unit', $request->unit_id)
    //     ->first();
    // }
public function getStockQtyRate(Request $request){
     $itemId = DB::table('items_of_storeins')
        ->where('name', $request->item_name)
        ->where('category_id', $request->cat_id)
        ->where('size_id', $request->side_id)
        ->where('unit_id', $request->unit_id)
        ->value('id');

    if ($itemId) {
        // If the item_id is found, fetch the stock quantity
        $stock = DB::table('stocks')
            ->where('category_id', $request->cat_id)
            ->where('item_id', $itemId)
            ->where('size', $request->side_id)
            ->where('unit', $request->unit_id)
            ->first();

        return $stock;
    }
}

    public function getDepartmentSizeUnit($items_of_storein_name, $category_id){
    $similarItems = ItemsOfStorein::where('name', '=', $items_of_storein_name)->pluck('id');
    // Use the found item IDs to filter the Stock model
    $stocks = Stock::with('department:id,name', 'units:id,name', 'sizes:id,name')
        ->whereIn('item_id', $similarItems)
        ->where('category_id', $category_id)
        ->groupBy(['size', 'unit', 'department_id'])
        ->get(['size', 'unit', 'department_id']);

        $ArrayStock =$stocks->toArray();

        $unitArray = [];
        $sizeArray = [];
        $departmentArray = [];

        foreach ($stocks as $stock) {
            $unit = $stock->units;
            $size = $stock->sizes;
            $department = $stock->department;

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
    public function updateStoreOut(Request $request, $storeout_id)
    {
       $request->validate([
            'receipt_date'          => 'required|string|max:50',
            'receipt_no'         => 'required|string',
            'for_id'         => 'required',
        ]);
        try{
        $storeOut = Storeout::find($storeout_id);
        $storeOut->receipt_date = $request->receipt_date;
        $storeOut->receipt_no = $request->receipt_no;
        $storeOut->godam_id  = $request->for_id;
        $storeOut->save();
        return redirect()->route('storeout.storeOutItems', ['store_out_id' => $storeOut->id]);
        }catch (Exception $ex){
            return 'something went wrong while storing storeout';
        }
    }

    public function saveStoreoutItems(Request $request)
    {
        // return $request;
        $request->validate([
            'category_id'=> 'required',
            'storeout_id' => 'required',
            'item_name' =>'required',
            'size' => 'required',
            'unit' => 'required',
            'quantity' => 'required',
            'department_id' => 'required',
            'placement_id' => 'required',
            'through' => 'required',
        ]);

        try {
            DB::beginTransaction();
             $itemId = DB::table('items_of_storeins')
            ->where('name', $request->item_name)
            ->where('category_id', $request->category_id)
            ->where('size_id', $request->size)
            ->where('unit_id', $request->unit)
            ->value('id');

            $stock = Stock::where('item_id',$itemId)
            ->where('category_id',$request->category_id)
            ->where('size',$request->size)
            ->where('unit',$request->unit)
            ->first();

            $storeOutItem = new StoreOutItem();
            $storeOutItem->item_of_storein_id = $stock->item_id;
            $storeOutItem->storeout_id = $request->storeout_id;
            $storeOutItem->storeoutDepartment_id = $request->department_id;
            $storeOutItem->placement_id = $request->placement_id;
            $storeOutItem->unit_id = $request->unit;
            $storeOutItem->size_id = $request->size;
            $storeOutItem->quantity = $request->quantity;
            $storeOutItem->rate = round($stock->avg_price,2);
            $storeOutItem->through = $request->through;
            $storeOutItem->total = round($storeOutItem->quantity * $stock->avg_price,2);
            $storeOutItem->save();

            if ($stock) {
                if ($stock->quantity < $request->quantity) {
                    return response()->json([
                        'message' => "you don't have enough stock available.",
                    ], 500);
                } else {
                    $stock->quantity = $stock->quantity - $request->quantity;
                    $stock->total_amount = round($stock->quantity * $stock->avg_price,2);
                    $stock->save();
                    if ($stock->quantity <= 0) {
                        $stock->delete();
                    }
                }
            } else {

                return response()->json([
                    'message' => 'Something went wrong.',
                ], 500);
                // throw new Exception("Stock not found");
            }

            DB::commit();
            return response()->json([
                'message' => 'Storeout Item Created Successfully.',
                'storeOutItem' =>  Self::getSingleStoreOutItemData($storeOutItem->id),
                'stock' => $stock
            ]);
            // Both operations succeeded
        } catch (Exception $e) {
            DB::rollback();
            return response()->json($e);
        }
    }

    public function getSingleStoreOutItemData($storeOutItem_id)
    {
        return  StoreOutItem::with(['itemsOfStorein','size:id,name','unit:id,name', 'placement:id,name', 'department:id,name'])
        ->find($storeOutItem_id);
    }
    public function getStoreOutItemData($storeout_id)
    {
        $storeout_items = StoreOutItem::with(['placement:id,name', 'size:id,name','unit:id,name','itemsOfStorein'])
        ->where('storeout_id', $storeout_id)
        ->orderBy('created_at', 'DESC')
        ->get();
        return response()->json([
            'storeOutItem' =>  $storeout_items
        ]);
    }
    public function saveEntireStoreOut(Request $request, $storeout_id)
    {
        try {
            $today= Carbon::now()->format('Y-n-j');
            DB::beginTransaction();
            $storeout = Storeout::with('storeoutItems')->find($storeout_id);
            $storeout->total_amount = round($request->storeOutTotal,2);
            $storeout->remark = $request->store_out_remark;
            $storeout->status = 'completed';
            $storeout->save();

            foreach($storeout->storeoutItems as $item){
                $issuedStoreinReport=IssuedStoreinReport::where('name',$item->item_of_storein_id)
                ->where('date',$today)
                ->first();
                if($issuedStoreinReport){
                    $issuedStoreinReport->quantity += $item->quantity;
                    $issuedStoreinReport->total =  round($issuedStoreinReport->quantity * $item->rate,2);
                    $issuedStoreinReport->save();
                }else{
                    $issuedStoreinReport= new IssuedStoreinReport();
                    $issuedStoreinReport->date =$today;
                    $issuedStoreinReport->name =$item->item_of_storein_id;
                    $issuedStoreinReport->quantity =$item->quantity;
                    $issuedStoreinReport->rate = $item->rate;
                    $issuedStoreinReport->total =round($issuedStoreinReport->quantity * $issuedStoreinReport->rate,2);
                    $issuedStoreinReport->save();
                }
            }
            DB::commit();
            return redirect()->route('storeout.index')->withSuccess('Store Out Created Successfully!');
        } catch (Exception $e) {
            DB::rollback();
            return back()->withErrors('Something went Wrong!');
        }
    }
    public function updateStoreOutItems(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = $request->validate([
                'quantity'          => 'required|min:1',
                'item_id'         => 'required',
                'department_id'   => 'required',
                'placement_id'    => 'required',
                'through'   => 'required',

            ]);
            $storeOutItem =  Stock::where('item_id', $storeOutItem->item_of_storein_id)
            ->where('department_id', $storeOutItem->storeoutDepartment_id)
            ->where('size',$storeOutItem->size)
            ->first();
            // old storeout quantity
            $oldStoreOutQuantity = $storeOutItem->quantity;
            $storeOutItem->item_of_storein_id = $request->item_id;
            $storeOutItem->quantity = $request->quantity;
            $storeOutItem->storeoutDepartment_id = $request->department_id;
            $storeOutItem->placement_id = $request->placement_id;
            $storeOutItem->through = $request->through;
            $storeOutItem->total = $storeOutItem->quantity * $storeOutItem->rate;
            $storeOutItem->save();

            $stock = Stock::where('item_id', $request->item_id)->first();
            if ($stock) {
                if ($stock->quantity < $request->quantity) {
                    return response()->json([
                        'message' => "you don't have enough stock available.",
                    ], 400);
                } else {
                    $stock->quantity = ($stock->quantity +  $oldStoreOutQuantity) - $request->quantity;
                    $stock->save();
                    if ($stock->quantity <= 0) {
                        $stock->delete();
                    }
                }
            }
            DB::commit();

            return response()->json([
                'message' => 'Store out updated successfully',
                'storeOutItem' =>  self::getStoreinItemData($storeOutItem->id)
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json('Something is wrong', 400);
        }
    }
}
