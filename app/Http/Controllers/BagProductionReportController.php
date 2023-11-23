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
            //  $bagBundelStock=BagBundelStock::with('bagBrand:id,name')
            //  ->where
            //  ->get();
            //  $groupedDatas = $bagBundelStock->groupBy('bag_brand_id');
            //     $formattedDatas = [];
            //     foreach ($groupedDatas as $groupId => $group) {
            //         $totalPcs = 0;
            //         $totalKgs = 0;
            //         $totalGramPerBag = 0;
            //         foreach ($group as $groupedData) {
            //             $totalPcs += $groupedData->qty_pcs;
            //             $totalKgs += $groupedData->qty_in_kg;
            //             $totalGramPerBag += $groupedData->qty_pcs/$groupedData->qty_in_kg;

            //             $formattedDatas[$groupedData->bagBrand->name][] = [
            //                 'bag_brand' => $groupedData->bagBrand->name,
            //                 'qty_pcs' => $groupedData->qty_pcs,
            //                 'qty_in_kg'=> $groupedData->qty_in_kg,
            //                 'gram_per_bag' => $groupedData->qty_in_kg/$groupedData->qty_pcs,
            //             ];
            //         }
            //     }
            //     return $formattedDatas;
            $results = DB::table('bag_bundel_entries')
            ->select(
                'bag_brands.name as bag_brand_name',
                'bag_bundel_items.bag_brand_id',
                'bag_bundel_entries.receipt_date',
                DB::raw('SUM(bag_bundel_items.qty_in_kg) as total_qty_in_kg'),
                DB::raw('SUM(bag_bundel_items.qty_pcs) as total_qty_pcs')
            )
            ->join('bag_bundel_items', 'bag_bundel_entries.id', '=', 'bag_bundel_items.bag_bundel_entry_id')
            ->join('bag_brands', 'bag_bundel_items.bag_brand_id', '=', 'bag_brands.id')
             ->whereBetween('bag_bundel_entries.receipt_date', ['2023-07-22','2023-09-28',])
            ->groupBy('bag_brands.name', 'bag_bundel_items.bag_brand_id', 'bag_bundel_entries.receipt_date')
            ->get();
            return $results;


            return view('admin.bag.bagBundelling.report');
        }

    private function data($request)
        {


        }
}
