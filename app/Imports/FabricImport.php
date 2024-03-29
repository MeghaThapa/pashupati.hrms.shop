<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Fabric;
use App\Models\FabricGroup;
use Illuminate\Support\Str;
use App\Models\FabricStock;
use App\Models\TapeEntryStockModel;
use App\Models\FabricDetail;
use App\Models\Wastages;
use App\Models\WasteStock;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Helpers\AppHelper;

use Illuminate\Support\Facades\Log;

class FabricImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{

    public $godam_id;
    public $date_np;

    public function __construct($godam_id, $date_np)
    {
        $this->godam_id = $godam_id;
        $this->date_np = $date_np;
    }

    public function collection(Collection $rows)
    {
        // dd($rows);

        $bill_no = "FI" . "-" . getNepaliDate(date('Y-m-d')) . "-" . strtotime(date(("H:i:s")));

        try {

            $gettapeQuantity = TapeEntryStockModel::where('toGodam_id', $this->godam_id)
                ->value('id');

            $findTape = TapeEntryStockModel::find($gettapeQuantity);

            $totalwastage = $rows[0]['pipecutting'] + $rows[0]['bdwastage'] + $rows[0]['otherwastage'];
            $totalnetWeight = $rows[0]['totalnet'];
            $finalWastage = $totalwastage + $totalnetWeight;


            if ($totalnetWeight < $findTape->tape_qty_in_kg) {

                $final = $findTape->tape_qty_in_kg - $finalWastage;
                $findTape->tape_qty_in_kg = $final;
                $findTape->update();
            }

            $bill_date_en = AppHelper::convertNepaliToEnglishDate($this->date_np);


            // store subcategory
            $wasteagepercent = (($totalwastage) / $rows[0]['totalweightkg']) * 100;
            $detail = FabricDetail::create([
                'bill_number' => $bill_no,
                'bill_date' => $this->date_np,
                'bill_date_en' => $bill_date_en,
                'godam_id' => $this->godam_id,
                'pipe_cutting' => $rows[0]['pipecutting'],
                'bd_wastage' => $rows[0]['bdwastage'],
                'other_wastage' => $rows[0]['otherwastage'],
                'total_wastage' => $totalwastage,
                'total_netweight' => $totalnetWeight,
                'total_meter' => $rows[0]['totalmeter'],
                'total_weightinkg' => $rows[0]['totalweightkg'],
                'total_wastageinpercent' => $wasteagepercent,
                'run_loom' => $rows[0]['runloom'],
                'wrapping' => '0',
            ]);

            $wastename = 'rafia';

            $wastage = Wastages::firstOrCreate([
                'name' => 'rafia'
            ], [
                'name' => 'rafia',
                'is_active' => '1',

            ]);

            $waste_id = Wastages::where('name', $wastename)->value('id');

            $stock = WasteStock::where('godam_id', $this->godam_id)
                ->where('waste_id', $wastage->id)->count();

            $getStock = WasteStock::where('godam_id', $this->godam_id)
                ->where('waste_id', $wastage->id)->first();


            if ($stock == 1) {
                $getStock->quantity_in_kg += $totalwastage;
                $getStock->save();
            } else {
                WasteStock::create([
                    'godam_id' => $this->godam_id,
                    'waste_id' => $wastage->id,
                    'quantity_in_kg' => $totalwastage,
                ]);
            }



            foreach ($rows as $row) {

                if (isset($row['size']) && isset($row['gram']) && isset($row['roll_no'])) {

                    $name_size = trim($row['size']);
                    $slug = $row['gram'];

                    $size = $name_size . '(' . $row['gram'] . ')';

                    $fabricgroup = FabricGroup::firstOrCreate([
                        'slug' => $slug
                    ], [
                        'name' => $row['gram'],
                        'slug' => $slug,
                        'status' => '1',
                    ]);

                    $fabricgroup_id = FabricGroup::where('slug', $slug)->value('id');

                    $input = $row['size'];
                    $parts = explode(' ', $input);
                    $firstString = $parts[0];

                    $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                    $data = $find_name;

                    $average = $row['grams'];

                    $gram_wt = ($average) / $data;

                    // $gram_wt = (round(round($row['grams'], 2) / (int) filter_var($row['size'], FILTER_SANITIZE_NUMBER_INT) ));


                    $fabric = Fabric::create([
                        'name' => $size,
                        'roll_no' => $row['roll_no'],
                        'loom_no' => $row['loom_no'],
                        'fabricgroup_id' => $fabricgroup_id,
                        'gross_wt' => $row['gross_wt'],
                        'net_wt' => $row['net_wt'],
                        'meter' => $row['meter'],
                        'gram_wt' => $gram_wt,
                        'average_wt' => $row['grams'],
                        'godam_id' => $this->godam_id,
                        'date_np' => $this->date_np,
                        'bill_no' => $bill_no,
                    ]);

                    $fabricstock = FabricStock::create([
                        'name' => $size,
                        'roll_no' => $row['roll_no'],
                        'loom_no' => $row['loom_no'],
                        'fabricgroup_id' => $fabricgroup_id,
                        'gross_wt' => $row['gross_wt'],
                        'net_wt' => $row['net_wt'],
                        'meter' => $row['meter'],
                        'gram_wt' => $gram_wt,
                        'average_wt' => $row['grams'],
                        'godam_id' => $this->godam_id,
                        'date_np' => $this->date_np,
                        'bill_no' => $bill_no,
                        'fabric_id' => $fabric->id,
                        'status_type' => 'active',
                    ]);
                }
            }

            // return response(200);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response([
                "exception" => $e->getMessage(),
            ]);
        }
    }
}
