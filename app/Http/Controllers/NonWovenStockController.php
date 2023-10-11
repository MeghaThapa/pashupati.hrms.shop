<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\FabricNonWovenReceiveEntryStock;
use App\Models\NonwovenOpeningStock;
use App\Models\RawMaterialStock;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\Department;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use DB;

class NonWovenStockController extends Controller
{
    public function index()
    {
        $helper = new AppHelper();
        $settings = $helper->getGeneralSettigns();

        // $nonwoven_stocks = FabricNonWovenReceiveEntryStock::paginate(35);

        $godams = Godam::where('status', 'active')->get(['id', 'name']);
        $uniqueGSMs  = $this->getUniqueGSM();
        $uniqueFabricNames = $this->getUniqueFabricName();
        $uniqueFabricColors = $this->getUniqueFabricColor();

        return view(
            'admin.nonwovenstock.getstock',
            compact('settings', 'godams', 'uniqueGSMs', 'uniqueFabricNames', 'uniqueFabricColors')
        );
    }

    public function filterStock(Request $request)
    {

        $nonWovenStocks = FabricNonWovenReceiveEntryStock::query();

        if ($request->fabric_gsm) {
            $nonWovenStocks->where('fabric_gsm', $request->fabric_gsm);
        }
        if ($request->fabric_name) {
            $nonWovenStocks->where('fabric_name', $request->fabric_name);
        }
        if ($request->fabric_color) {
            $nonWovenStocks->where('fabric_color', $request->fabric_color);
        }

        $nonWovenStocks = $nonWovenStocks->with('getGodam')->get();

        $nonWovenStockArray = [];

        foreach ($nonWovenStocks as $nonWovenStock) {
            $fabricName = $nonWovenStock->fabric_name;
            $fabricGSM = $nonWovenStock->fabric_gsm;
            $fabricColor = $nonWovenStock->fabric_color;

            $nonWovenStockArray[$fabricName][$fabricGSM][$fabricColor][] = [
                'godam_id'     => $nonWovenStock->getGodam->name,
                'fabric_roll'  => $nonWovenStock->fabric_roll,
                'fabric_gsm'   => $nonWovenStock->fabric_gsm,
                'fabric_name'  => $nonWovenStock->fabric_name,
                'fabric_color' => $nonWovenStock->fabric_color,
                'length'       => $nonWovenStock->length,
                'gross_weight' => $nonWovenStock->gross_weight,
                'net_weight'   => $nonWovenStock->net_weight,
            ];
        }

        $summary = [];

        foreach ($nonWovenStocks as $nonWovenStock) {
            $fabricName = $nonWovenStock->fabric_name;
            $fabricGSM = $nonWovenStock->fabric_gsm;
            $fabricColor = $nonWovenStock->fabric_color;

            // Create or update summary data for the specific group
            if (!isset($summary[$fabricName])) {
                $summary[$fabricName] = [];
            }
            if (!isset($summary[$fabricName][$fabricGSM])) {
                $summary[$fabricName][$fabricGSM] = [];
            }
            if (!isset($summary[$fabricName][$fabricGSM][$fabricColor])) {
                $summary[$fabricName][$fabricGSM][$fabricColor] = [
                    'godam_id' => $nonWovenStock->getGodam->name,
                    'fabric_roll_count' => 0,
                    'length_total' => 0,
                    'gross_weight_total' => 0,
                    'net_weight_total' => 0,
                ];
            }

            // Update summary data
            $summary[$fabricName][$fabricGSM][$fabricColor]['fabric_roll_count']++;
            $summary[$fabricName][$fabricGSM][$fabricColor]['length_total'] += (float)$nonWovenStock->length;
            $summary[$fabricName][$fabricGSM][$fabricColor]['gross_weight_total'] += (float)$nonWovenStock->gross_weight;
            $summary[$fabricName][$fabricGSM][$fabricColor]['net_weight_total'] += (float)$nonWovenStock->net_weight;
        }

        $view = view('admin.nonwovenstock.ssr.stock_report_view', compact('nonWovenStockArray', 'summary'))->render();
        return response(['status' => true, 'data' => $view]);
    }

    private function getUniqueGSM()
    {
        $uniqueGSM = FabricNonWovenReceiveEntryStock::select('fabric_non_woven_receive_entry_stocks.fabric_gsm')
            ->distinct()
            ->orderByRaw('CAST(fabric_non_woven_receive_entry_stocks.fabric_gsm AS UNSIGNED) ASC')
            ->get();
        return $uniqueGSM;
    }

    private function getUniqueFabricName()
    {
        $uniqueFabricName = FabricNonWovenReceiveEntryStock::select('fabric_non_woven_receive_entry_stocks.fabric_name')
            ->distinct()
            ->orderBy('fabric_non_woven_receive_entry_stocks.fabric_name', 'asc')
            ->get();
        return $uniqueFabricName;
    }

    private function getUniqueFabricColor()
    {
        $uniqueFabricColor = FabricNonWovenReceiveEntryStock::select('fabric_non_woven_receive_entry_stocks.fabric_color')
            ->distinct()
            ->orderBy('fabric_non_woven_receive_entry_stocks.fabric_color', 'asc')
            ->get();
        return $uniqueFabricColor;
    }
}
