<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Fabric;
use App\Models\FabricGroup;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class FabricImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{
  
   public function collection(Collection $rows)
   {
    // dd($rows);
       foreach ($rows as $row) {
           $slug = $row['gram'];
           // dd($row['gram'],$row['grams']);

           $fabricgroup = FabricGroup::firstOrCreate([
               'slug' => $slug
           ], [
               'name' => $row['gram'],
               'slug' => $slug,
               'is_active' => '1',
               
           ]);
           $fabricgroup_id = FabricGroup::where('slug',$slug)->value('id');

           $fabric = Fabric::firstOrCreate([
               'roll_no' => $roll_no
           ], [
               'name' => $row['size'],
               'roll_no' => $row['roll_no'],
               'loom_no' => $row['loom_no'],
               'fabricgroup_id' => $fabricgroup_id,
               'gross_wt' => $row['gross_wt'],
               'net_wt' => $row['net_wt'],
               'meter' => $row['meter'],
               'gram' => $row['grams'],
               
           ]);

         //   Fabric::create([
         //     'name' => $row['size'],
         //     'roll_no' => $row['roll_no'],
         //     'loom_no' => $row['loom_no'],
         //     'fabricgroup_id' => $fabricgroup_id,
         //     'gross_wt' => $row['gross_wt'],
         //     'net_wt' => $row['net_wt'],
         //     'meter' => $row['meter'],
         //     'gram' => $row['grams'],
         // ]);
       }
   }
}
