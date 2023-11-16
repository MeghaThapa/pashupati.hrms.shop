<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Size;
use App\Models\Stock;
use App\Models\Storein;
use App\Models\StoreinItem;
use App\Models\StoreinDepartment;
use App\Models\StoreinCategory;
use App\Models\FabricSendAndReceiveEntry;
use App\Models\WasteStock;
use App\Models\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments=StoreinDepartment::where('status','active')->get();
        $categories =StoreinCategory::where('status','active')->get();
        return view('admin.Stock.itemStock', compact('departments','categories'));
    }
    private function insertData()
    {
                DB::statement('
            CREATE TEMPORARY TABLE temp_wastages AS
            SELECT godam_id, waste_id, SUM(quantity_in_kg) AS total_quantity_in_kg
            FROM wastages_stock
            GROUP BY godam_id, waste_id
        ');

        // Delete all rows from the original table
        WasteStock::query()->delete();

        // Insert the consolidated rows back into the original table
        DB::statement('
            INSERT INTO wastages_stock (godam_id, waste_id, quantity_in_kg)
            SELECT godam_id, waste_id, total_quantity_in_kg
            FROM temp_wastages
        ');

        // Drop the temporary table
        DB::statement('DROP TEMPORARY TABLE temp_wastages');
        // // Retrieve the data from FabricSendAndReceiveEntry where either polo_waste or fabric_waste is not null
        // $entries = FabricSendAndReceiveEntry::where(function ($query) {
        //     $query->whereNotNull('polo_waste')->orWhereNotNull('fabric_waste');
        // })->get();

        // // Iterate over the data and insert or update the WastagesStock table
        // foreach ($entries as $entry) {
        //     if (!is_null($entry->polo_waste)) {
        //         $this->updateOrCreateWastagesStock($entry->godam_id, 33, $entry->polo_waste);
        //     }

        //     if (!is_null($entry->fabric_waste)) {
        //         $this->updateOrCreateWastagesStock($entry->godam_id, 24, $entry->fabric_waste);
        //     }
        // }

        // return "Data inserted or updated successfully.";
    }

    private function updateOrCreateWastagesStock($godamId, $wasteId, $quantity)
    {
        WasteStock::insert([
            'godam_id' => $godamId,
            'waste_id' => $wasteId,
            'quantity_in_kg' => $quantity,
        ]);
    }

    private function updateWasteEntries()
    {
        // Retrieve the data from both tables
        $lamWasteData = DB::table('lam_waste_recover')->get();
        $fabricData = DB::table('fabric_send_and_receive_entry')->get();

        // Iterate over the records and update when dates match
        foreach ($lamWasteData as $lamRow) {
            foreach ($fabricData as $fabricRow) {
                if ($lamRow->date == $fabricRow->bill_date_np) {
                    DB::table('fabric_send_and_receive_entry')
                        ->where('id', $fabricRow->id) // Replace with the actual primary key of your table
                        ->update([
                            'polo_waste' => $lamRow->polo_waste,
                            'fabric_waste' => $lamRow->fabric_waste,
                            'total_waste' => $lamRow->total_waste,
                        ]);
                }
            }
        }

        return "Waste entries updated successfully.";
    }
    public function filterStockAccCategory($category_id)
    {
        $stock = Stock::with('item', 'department', 'category')
        ->where('category_id', $category_id)->get();
        if ($stock) {
            return $stock;
        } else {
            // return "hello";
            return false;
        }
    }

    public function filter(Request $request){
        $TOTAL_ROW=35;
        $TOTAL_AMOUNT = 0;

        $stocks = DB::table('stocks')
        ->join('storein_categories', 'stocks.category_id', '=', 'storein_categories.id')
        ->join('items_of_storeins', 'stocks.item_id', '=', 'items_of_storeins.id')
        ->join('storein_departments', 'stocks.department_id', '=', 'storein_departments.id')
         ->join('units','stocks.unit','=','units.id')
        ->join('sizes','stocks.size','=','sizes.id')
        ->select(
            'stocks.*',
            'storein_categories.name as category_name',
            'items_of_storeins.name as item_name',
            'items_of_storeins.pnumber as item_num',
            'storein_departments.name as department_name',
            'units.name as unit_name',
            'sizes.name as size_name',
        );
        if ($request->storein_department || $request->storein_department != null) {
            $stocks->where('stocks.department_id', $request->storein_department);
        }
        if($request->storein_category || $request->storein_category !=null){
              $stocks->where('stocks.category_id', $request->storein_category);
        }
        $stocks = $stocks->paginate($TOTAL_ROW);
        $stocks->appends($request->only('storein_department', 'storein_category'));

        foreach ($stocks->items() as $stock){
            $TOTAL_AMOUNT += $stock->total_amount;
        }

        $departments=StoreinDepartment::where('status','active')->get();
        $categories =StoreinCategory::where('status','active')->get();
        return view('admin.Stock.itemStock', compact('stocks','departments','categories','TOTAL_AMOUNT'));
    }
    public function yajra(Request $request){

        $query = DB::table('stocks')
        ->join('storein_categories', 'stocks.category_id', '=', 'storein_categories.id')
        ->join('items_of_storeins', 'stocks.item_id', '=', 'items_of_storeins.id')
        ->join('storein_departments', 'stocks.department_id', '=', 'storein_departments.id')
        ->join('units', 'stocks.unit', '=', 'units.id')
        ->join('sizes', 'stocks.size', '=', 'sizes.id')
        ->select(
            'stocks.*',
            'storein_categories.name as category_name',
            'items_of_storeins.name as item_name',
            'items_of_storeins.pnumber as item_num',
            'storein_departments.name as department_name',
            'units.name as unit_name',
            'sizes.name as size_name'
        )
        ->orderBy('stocks.id', 'DESC')
        ->get();

    $batchSize = 100;
    $data = [];

    foreach (array_chunk($query->toArray(), $batchSize) as $rows) {
        foreach ($rows as $row) {
            $data[] = [
                'id' => $row->id,
                'quantity' => $row->quantity,
                'avg_price' => $row->avg_price,
                'total_amount' => $row->total_amount,
                'category_name' => $row->category_name,
                'item_name' => $row->item_name,
                'item_num' => $row->item_num,
                'department_name' => $row->department_name,
                'unit_name' => $row->unit_name,
                'size_name' => $row->size_name,
            ];
        }
    }
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function departmentFilter()
    {
        $stocks = Stock::with('category', 'item', 'department')->get();
        $departments = Department::where('status', 'active')->get();
        return view('admin.Stock.departmentFilter', compact('stocks', 'departments'));
    }

    //filter department acc categories
    public function getCategoryDepartment($category_id){
        $departments= DB::table('stocks')
        ->join('storein_departments','storein_departments.id','=','stocks.department_id')
        ->where('stocks.category_id',$category_id)
        ->select('storein_departments.id','storein_departments.name')
        ->groupBy('storein_departments.id','storein_departments.name')
        ->get();
        return $departments;
    }

    public function filterStock()
    {
        $categories = Category::where('status', '1')->get();
        // return $categories;
        $departments = Department::where('status', 'active')->get();
        // return $departments;
        $stocks = Stock::with('category', 'item', 'department')->get();
        return view('admin.Stock.filterStock', compact('stocks', 'categories', 'departments'));
    }

    public function filterStockAccDepartment($department_id)
    {
        $stock = Stock::with('item', 'department', 'category')->where('department_id', $department_id)->get();
        if ($stock) {
            return $stock;
        } else {
            // return "hello";
            return false;
        }
    }

    //update or create stock


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
