<?php

namespace App\Http\Controllers;
use App\Models\Stock;
use App\Models\OpeningStoreinReport;
use App\Models\ClosingStoreinReport;
use App\Models\StoreinCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Carbon\Carbon;

class ClosingStoreinReportController extends Controller
{
    public function index(){
          $categories=StoreinCategory::where('status', 'active')->get();
          return view('admin.report.storeinOutReport',compact('categories'));
    }

    // public function yajraReport(){
    //         $reportData = [];

    //         $items = DB::table('items_of_storeins')->get();

    //         $today = Carbon::today()->format('Y-n-j');

    //         $allData = DB::table('items_of_storeins as item')
    //             ->leftJoin('opening_storein_reports as opening', function ($join) use ($today) {
    //                 $join->on('opening.name', '=', 'item.id')->where('opening.date', $today);
    //             })
    //             ->leftJoin('purchase_storein_reports as purchase', function ($join) use ($today) {
    //                 $join->on('purchase.name', '=', 'item.id')->where('purchase.date', $today);
    //             })
    //             ->leftJoin('issued_storein_reports as issue', function ($join) use ($today) {
    //                 $join->on('issue.name', '=', 'item.id')->where('issue.date', $today);
    //             })
    //             ->leftJoin('closing_storein_reports as closing', function ($join) use ($today) {
    //                 $join->on('closing.name', '=', 'item.id')->where('closing.date', $today);
    //             })
    //             ->select(
    //                 'item.name as item_name',
    //                 'opening.quantity as opening_qty', 'opening.rate as opening_rate', 'opening.total as opening_total',
    //                 'purchase.quantity as purchase_qty', 'purchase.rate as purchase_rate', 'purchase.total as purchase_total',
    //                 'issue.quantity as issue_qty', 'issue.rate as issue_rate', 'issue.total as issue_total',
    //                 'closing.quantity as closing_qty', 'closing.rate as closing_rate', 'closing.total as closing_total'
    //             )
    //              ->get();

    //             foreach ($items as $item) {
    //                 $data = $allData->where('item_name', $item->name)->first();

    //                 $reportData[] = [
    //                     'item_name' => $item->name,
    //                     'opening_qty' => $data->opening_qty ?? 0,
    //                     'opening_rate' => $data->opening_rate ?? 0,
    //                     'opening_total' => $data->opening_total ?? 0,
    //                     'purchase_qty' => $data->purchase_qty ?? 0,
    //                     'purchase_rate' => $data->purchase_rate ?? 0,
    //                     'purchase_total' => $data->purchase_total ?? 0,
    //                     'issue_qty' => $data->issue_qty ?? 0,
    //                     'issue_rate' => $data->issue_rate ?? 0,
    //                     'issue_total' => $data->issue_total ?? 0,
    //                     'closing_qty' => $data->closing_qty ?? 0,
    //                     'closing_rate' => $data->closing_rate ?? 0,
    //                     'closing_total' => $data->closing_total ?? 0,
    //                 ];
    //             }

    //             return Datatables::of($reportData)
    //                 ->addIndexColumn()
    //                 ->make(true);

    // }
    public function yajraReport() {
        $batchSize = 100;

        $items = DB::table('items_of_storeins')->select('id','name')->get();

        // $today = Carbon::today()->format('Y-n-j');
        $today= Carbon::now()->format('Y-n-j');

        $reportData = [];
        $openingTotal = 0;
        $purchaseTotal = 0;
        $closingTotal = 0;


        foreach (array_chunk($items->toArray(), $batchSize) as $batchItems) {
            $itemId = array_column($batchItems, 'id');

            $allData = DB::table('items_of_storeins as item')
                ->leftJoin('opening_storein_reports as opening', function ($join) use ($today) {
                    $join->on('opening.name', '=', 'item.id')->where('opening.date', $today);
                })
                ->leftJoin('purchase_storein_reports as purchase', function ($join) use ($today) {
                    $join->on('purchase.name', '=', 'item.id')->where('purchase.date', $today);
                })
                ->leftJoin('issued_storein_reports as issue', function ($join) use ($today) {
                    $join->on('issue.name', '=', 'item.id')->where('issue.date', $today);
                })
                ->leftJoin('closing_storein_reports as closing', function ($join) use ($today) {
                    $join->on('closing.name', '=', 'item.id')->where('closing.date', $today);
                })
                ->select(
                    'item.id as item_id',
                    'item.name as item_name',
                    'opening.quantity as opening_qty', 'opening.rate as opening_rate', 'opening.total as opening_total',
                    'purchase.quantity as purchase_qty', 'purchase.rate as purchase_rate', 'purchase.total as purchase_total',
                    'issue.quantity as issue_qty', 'issue.rate as issue_rate', 'issue.total as issue_total',
                    'closing.quantity as closing_qty', 'closing.rate as closing_rate', 'closing.total as closing_total'
                )
                ->whereIn('item.id', $itemId)
                ->get();


            foreach ($batchItems as $item) {
                $data = $allData->where('item_id', $item->id)->first();

                $openingTotal += $data->opening_total ?? 0;
                $purchaseTotal += $data->purchase_total ?? 0;
                $closingTotal += $data->closing_total ?? 0;

                $reportData[] = [
                    'item_name' => $item->name,
                    'opening_qty' => $data->opening_qty ?? 0,
                    'opening_rate' => $data->opening_rate ?? 0,
                    'opening_total' => $data->opening_total ?? 0,
                    'purchase_qty' => $data->purchase_qty ?? 0,
                    'purchase_rate' => $data->purchase_rate ?? 0,
                    'purchase_total' => $data->purchase_total ?? 0,
                    'issue_qty' => $data->issue_qty ?? 0,
                    'issue_rate' => $data->issue_rate ?? 0,
                    'issue_total' => $data->issue_total ?? 0,
                    'closing_qty' => $data->closing_qty ?? 0,
                    'closing_rate' => $data->closing_rate ?? 0,
                    'closing_total' => $data->closing_total ?? 0,
                ];
            }
        }


        return Datatables::of($reportData)->addIndexColumn()
        ->make(true);
    }

    public function closing(){
        try{
            DB::beginTransaction();
        $stocks=DB::table('stocks')->get(['item_id','quantity','avg_price','total_amount']);
        foreach($stocks as $stock){

            $closing = new ClosingStoreinReport();
            $closing->date =Carbon::now()->format('Y-n-j');
            $closing->name =$stock->item_id;
            $closing->quantity =$stock->quantity;
            $closing->rate = $stock->avg_price;
            $closing->total = $stock->total_amount;
            $closing->save();

            $opening = new OpeningStoreinReport();
            $opening->date = Carbon::now()->addDay()->format('Y-n-j');
            $opening->name =$stock->item_id;
            $opening->quantity =$stock->quantity;
            $opening->rate = $stock->avg_price;
            $opening->total = $stock->total_amount;
            $opening->save();

        }
        DB::commit();
        return back();
        }catch(Excption $ex){
            DB::rollback();
        }
    }
}
