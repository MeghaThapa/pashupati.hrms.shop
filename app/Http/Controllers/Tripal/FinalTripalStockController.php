<?php

namespace App\Http\Controllers\Tripal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FabricStock;
use App\Models\FinalTripalStock;
use App\Models\Godam;
use Yajra\DataTables\DataTables;

class FinalTripalStockController extends Controller
{
    public function index()
    {
        $helper = new AppHelper();
        $settings = $helper->getGeneralSettigns();

        $finaltripalname = FinalTripalStock::distinct('name')->pluck('name')->all();
        $godams = Godam::get();
        return view(
            'admin.tripal_stock.finaltripalstock.index',
            compact('settings', 'finaltripalname', 'godams')
        );
    }

    public function getFilterList(Request $request)
    {
        $finalTripalStock = FinalTripalStock::query();

        if ($request->name) {
            $finalTripalStock->where('name', $request->name);
        }
        if ($request->department_id) {
            $finalTripalStock->where('department_id', $request->department_id);
        }

        $finalTripalStock = $finalTripalStock->with('getGodam')->get();

        $finalTripalArray = [];

        foreach ($finalTripalStock as $finalTripalItem) {
            $tripalName = $finalTripalItem->name;

            $finalTripalArray[$tripalName][] = [
                'name'       => $finalTripalItem->name,
                'roll'       => $finalTripalItem->roll_no,
                'net_wt'     => $finalTripalItem->net_wt,
                'gross_wt'   => $finalTripalItem->gross_wt,
                'meter'      => $finalTripalItem->meter,
                'average_wt' => $finalTripalItem->average_wt,
            ];
        }

        $summaryData = [];

        foreach ($finalTripalArray as $fabricName => $fabricEntries) {
            $countFabricRoll = count($fabricEntries);
            $sumNetWeight = 0;
            $sumGrossWeight = 0;
            $sumMeter = 0;
            $sumAverageWt = 0;

            foreach ($fabricEntries as $entry) {
                $sumNetWeight += floatval($entry['net_wt']);
                $sumGrossWeight += floatval($entry['gross_wt']);
                $sumMeter += floatval($entry['meter']);
                $sumAverageWt += floatval($entry['average_wt']);
            }

            $summaryData[] = [
                'name'             => $fabricName,
                'roll_count'       => $countFabricRoll,
                'sum_net_weight'   => $sumNetWeight,
                'sum_gross_weight' => $sumGrossWeight,
                'sum_meter'        => $sumMeter,
                'sum_average_wt'   => $sumAverageWt,
            ];
        }

        $view = view('admin.tripal_stock.finaltripalstock.ssr.final_tripal_stock_view', compact('finalTripalArray','summaryData'))->render();
        return response(['status' => true, 'data' => $view]);
    }

    public function filterStock(Request $request)
    {

        $helper = new AppHelper();
        $settings = $helper->getGeneralSettigns();
        $name = $request->name ?? null;

        $finaltripalname = FinalTripalStock::get()->unique('name')->values()->all();

        $sum = 0;

        if ($name || $name != null) {
            $finaltripal = FinalTripalStock::where('name', 'LIKE', '%' . $request->name . '%')->paginate(35);
            $sum = $finaltripal->sum('net_wt');
        }

        return view(
            'admin.tripal_stock.finaltripalstock.index-ajax',
            compact('settings', 'finaltripal', 'finaltripalname', 'sum')
        );
    }
}
