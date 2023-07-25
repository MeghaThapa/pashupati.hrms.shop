<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Fabric;
use App\Models\FabricGroup;
use Illuminate\Support\Str;
use App\Models\FabricStock;
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
      $bill_no = "FI"."-".getNepaliDate(date('Y-m-d'))."-".strtotime(date(("H:i:s")));

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
