<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\BagBundelStock;
use App\Models\BagBundleOpening;
use App\Models\Group;
use App\Models\BagBrand;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BundleStockImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            $groups = trim($row['group']);
           
            $group = Group::firstOrCreate([
                'name' => $groups
            ], [
                'name' => $row['group'],
                'status' => 'active',
                
            ]);
            $group_id = Group::where('name',$groups)->value('id');
            

            $brands = trim($row['brand']);


            $brand = BagBrand::firstOrCreate([
                'name' => $brands
            ], [
                'name' => $row['brand'],
                'group_id' => $group_id,
                'status' => 'active',
                
            ]);
            $brand_id = BagBrand::where('name',$row['brand'])->value('id');

            $bagbundleopening = BagBundleOpening::create([
                'group_id' => $group_id,
                'bag_brand_id' => $brand_id,
                'bundle_no' => $row['bundle_no'],
                'qty_pcs' => $row['pcs'],
                'qty_in_kg' => $row['weight'],
                'average_weight' => $row['avgwt'],
                'type' => $row['type'],
                
            ]);

            $bagbundlestock = BagBundelStock::create([
                'group_id' => $group_id,
                'bag_brand_id' => $brand_id,
                'bundle_no' => $row['bundle_no'],
                'qty_pcs' => $row['pcs'],
                'qty_in_kg' => $row['weight'],
                'average_weight' => $row['avgwt'],
                'type' => $row['type'],
                
            ]);
            
        }
      
    }
}
