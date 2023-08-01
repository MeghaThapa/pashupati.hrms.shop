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
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class FabricImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{

    public $godam_id;

    public function __construct($godam_id)
    {
        $this->godam_id = $godam_id;
    }
  
   public function collection(Collection $rows)
   {
      // dd($rows);
      
      $bill_no = "FI"."-".getNepaliDate(date('Y-m-d'))."-".strtotime(date(("H:i:s")));

      $gettapeQuantity = TapeEntryStockModel::where('toGodam_id',$this->godam_id)
                                            ->value('id');

      $findTape = TapeEntryStockModel::find($gettapeQuantity);
      // dd($findTape->tape_qty_in_kg);    
      dd($rows[0]['roll_no']);

      $totalwastage = $request['total_wastage'];
      $totalnetWeight = $request['total_netweight'];
      $finalWastage = $totalwastage + $totalnetWeight;


      // dd($finalWastage,$findTape->tape_qty_in_kg);
      // if($totalnetWeight < $findTape->tape_qty_in_kg){

      //     $final = $findTape->tape_qty_in_kg - $finalWastage;
      //     $findTape->tape_qty_in_kg = $final;
      //     $findTape->update();

      //     $countData = FabricDetail::where('bill_number',$bill_no)->count();
      //     if($countData != 1){
      //         // store subcategory
      //         $fabric = FabricDetail::create([
      //             'bill_number' => $bill_no,
      //             'bill_date' => '0',
      //             'pipe_cutting' => $request['pipe_cutting'],
      //             'bd_wastage' => $request['bd_wastage'],
      //             'other_wastage' => $request['other_wastage'],
      //             'total_wastage' => $request['total_wastage'],
      //             'total_netweight' => $request['total_netweight'],
      //             'total_meter' => $request['total_meter'],
      //             'total_weightinkg' => $request['total_weightinkg'],
      //             'total_wastageinpercent' => $request['total_wastageinpercent'],
      //             'run_loom' => $request['run_loom'],
      //             'wrapping' => $request['wrapping'],
      //         ]);

      //     }
      //  }


       foreach ($rows as $row) {
           // dd($row);
           $size = trim($row['size']);
           $slug = $row['gram'];

           $fabricgroup = FabricGroup::firstOrCreate([
               'slug' => $slug
           ], [
               'name' => $row['gram'],
               'slug' => $slug,
               'is_active' => '1',
               
           ]);
           $fabricgroup_id = FabricGroup::where('slug',$slug)->value('id');

           $gram_wt = (round(round($row['grams'], 2) / (int) filter_var($row['size'], FILTER_SANITIZE_NUMBER_INT) ));


           $fabric = Fabric::firstOrCreate([
               'roll_no' => $row['roll_no']
           ], [
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
               'bill_no' => $bill_no,
               
           ]);

           $fabricstock = FabricStock::firstOrCreate([
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
            'bill_no' => $bill_no,
            'fabric_id' => $fabric->id
        ]);
           
       }
   }
}
