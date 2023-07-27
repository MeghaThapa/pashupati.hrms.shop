<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\Items;
use App\Models\Stock;
use Illuminate\Support\Str;
use App\Models\Godam;
use App\Models\DanaName;
use App\Models\DanaGroup;
use App\Models\RawMaterialStock;


//for silent creations
class OpeningRawmaterialImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $trimGodam = strtolower(trim($row['godam']));
            $trimDanaGroup = strtolower(trim($row['group']));
            $trimDanaName = strtolower(trim($row['name']));
            $trimQuantity = strtolower(trim($row['quantity']));

            /*******trims spaces in between**********/
            $godam_id = Godam::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimGodam)])->value('id');
            $danaGroup_id = DanaGroup::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimDanaGroup)])->value('id');
            $danaName_id = DanaName::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimDanaName)])->value('id');
            /******* end trims spaces in between**********/

            if($godam_id == null) {
                $godam = Godam::Create([
                    "name" => isset($trimGodam),
                    "status" => "active"
                ]);
                $godam_id =$godam->id;
            }

            if($danaGroup_id == null){
                $danaGroup = DanaGroup::Create([
                    "name" =>  isset($trimDanaGroup),
                    "statu" => "active"
                ]);
                $danaGroup_id = $danaGroup->id ;
             }

             if ($danaName_id === null) {
                $danaName = DanaName::create([
                    'name' =>  trim(strtolower($trimDanaName)),
                     'dana_group_id' => $danaGroup_id,
                     'status' => "active"

                 ]);
                 $danaName_id = $danaName->id;
             }


            if($godam_id && $danaGroup_id && $danaName_id ){
                $rawMaterialStock=RawMaterialStock::where('godam_id',$godam_id)
                        ->where('dana_group_id',$danaGroup_id)
                        ->where('dana_name_id',$danaName_id)
                        ->first();
                if($rawMaterialStock){
                    //   $rawMaterialStock->quantity += $row['trimQuantity'];
                      $rawMaterialStock->quantity += $trimQuantity;
                       $rawMaterialStock->save();
                    }else{
                        $rawMaterialStockItem = new RawMaterialStock();
                        $rawMaterialStockItem->godam_id = $godam_id;
                        $rawMaterialStockItem->dana_group_id  = $danaGroup_id;
                        $rawMaterialStockItem->dana_name_id  = $danaName_id;
                        $rawMaterialStockItem->quantity =  $trimQuantity;
                        $rawMaterialStockItem->save();
                    }
                }else{

                }

        }
    }
}
