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
                // foreach ($groupedDatas as $groupId => $group) {
                //     $totalPcs = 0;
                //     $totalKgs = 0;
                //     $totalGramPerBag = 0;
                //     foreach ($group as $groupedData) {
                //         $totalPcs += $groupedData->qty_pcs;
                //         $totalKgs += $groupedData->qty_in_kg;
                //         $totalGramPerBag += $groupedData->qty_pcs/$groupedData->qty_in_kg;

                //         $formattedDatas[$groupedData->bagBrand->name][] = [
                //             'bag_brand' => $groupedData->bagBrand->name,
                //             'qty_pcs' => $groupedData->qty_pcs,
                //             'qty_in_kg'=> $groupedData->qty_in_kg,
                //             'gram_per_bag' => $groupedData->qty_in_kg/$groupedData->qty_pcs,
                //         ];
                //     }
                // }
            //     return $formattedDatas;
           $results = DB::table('bag_bundel_entries as bb')
            ->select(
                'bb.receipt_date',
                'bi.bag_bundel_entry_id',
                'bi.bag_brand_id',
                'bi.qty_in_kg',
                'bi.qty_pcs',
                'br.name as brand_name'
            )
            ->join('bag_bundel_items as bi', 'bb.id', '=', 'bi.bag_bundel_entry_id')
            ->join('bag_brands as br', 'bi.bag_brand_id', '=', 'br.id')
            ->orderBy('bb.receipt_date')
            ->get();
            $groupedDatas = $results->groupBy('bag_brand_id');
            // return ($groupedDatas);
             $formattedDatas = [];
              foreach ($groupedDatas as $groupId => $group) {
                    $totalPcs = 0;
                    $totalKgs = 0;
                    $totalGramPerBag = 0;
                    foreach ($group as $groupedData) {
                        $totalPcs += $groupedData->qty_pcs;
                        $totalKgs += $groupedData->qty_in_kg;
                        $totalGramPerBag += $groupedData->qty_pcs/$groupedData->qty_in_kg;

                        $formattedDatas[$groupedData->brand_name][] = [
                            'receipt_date' =>$groupedData->receipt_date,
                            'bag_brand' => $groupedData->brand_name,
                            'qty_pcs' => $groupedData->qty_pcs,
                            'qty_in_kg'=> $groupedData->qty_in_kg,
                            'gram_per_bag' => $groupedData->qty_in_kg/$groupedData->qty_pcs,
                        ];
                    }
                }

            return $formattedDatas;


            return view('admin.bag.bagBundelling.report');
        }

    private function data($request)
        {


        }
}
