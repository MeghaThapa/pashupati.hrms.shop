<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagBundelEntry;
use DB;

class BagProductionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __invoke(Request $request)
        {
            if ($request->ajax()) {

                $datas = $this->data($request);

                $view = view('admin.bag.bagBundelling.ssr.report', compact('datas', 'request'))->render();
                return response(['status' => true, 'data' => $view]);
            }




            return view('admin.bag.bagBundelling.report');
        }

    private function data($request)
        {
            $results1 =  DB::table('bag_bundel_entries')
            ->select(
            'bag_bundel_entries.receipt_date',
            'bag_bundel_items.bag_brand_id',
            'bag_brands.name as brand_name',
            DB::raw('SUM(bag_bundel_items.qty_in_kg) as total_qty_in_kg'),
            DB::raw('SUM(bag_bundel_items.qty_pcs) as total_qty_pcs')
            )
            ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->join('bag_brands', 'bag_bundel_items.bag_brand_id', '=', 'bag_brands.id')
            ->whereBetween('bag_bundel_entries.receipt_date', [$request->start_date, $request->end_date])
            ->groupBy('bag_bundel_entries.receipt_date', 'bag_bundel_items.bag_brand_id', 'bag_brands.name')
            ->get();
            // dd($results1);

            //for summary
            $result = DB::table('bag_bundel_entries')
            ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->join('bag_brands', 'bag_bundel_items.bag_brand_id', '=', 'bag_brands.id')
            ->select('bag_bundel_items.bag_brand_id', 'bag_brands.name', DB::raw('SUM(bag_bundel_items.qty_in_kg) as total_qty_in_kg'), DB::raw('SUM(bag_bundel_items.qty_pcs) as total_qty_pcs'))
            ->whereBetween('bag_bundel_entries.receipt_date', [$request->start_date, $request->end_date])
            ->groupBy('bag_bundel_items.bag_brand_id', 'bag_brands.name')
            ->get();
            // dd($result);
            //megha testt
            $groupedDatas = $results1->groupBy('bag_brand_id');
             $formattedDatas = [];
             foreach ($groupedDatas as $groupId => $group) {
                    foreach ($group as $groupedData) {
                        $formattedDatas[$groupedData->brand_name][] = [
                            'receipt_date' =>$groupedData->receipt_date,
                            'bag_brand' => $groupedData->brand_name,
                            'qty_pcs' => $groupedData->total_qty_pcs,
                            'qty_in_kg'=> $groupedData->total_qty_in_kg,
                            'gram_per_bag' => number_format($groupedData->total_qty_in_kg/$groupedData->total_qty_pcs,3),
                        ];
                    }
                }


                // dd( $formattedDatas);
            return [
        'result' => $result,
        'formattedDatas' => $formattedDatas,
    ];

        }
}
