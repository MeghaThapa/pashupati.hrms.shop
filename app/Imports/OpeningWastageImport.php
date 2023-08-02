<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

use Illuminate\Support\Str;
use App\Models\Godam;
use App\Models\Wastages;
use App\Models\OpeningWastage;
use App\Models\WasteStock;

//for silent creations
class OpeningWastageImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        try{
        foreach ($rows as $row) {
          //  dd($row);
            $trimGodam = strtolower(trim($row['godam']));
            $trimWastage = strtolower(trim($row['wastage']));
            $trimQuantity = strtolower(trim($row['qty']));

            /*******trims spaces in between**********/
            $godam_id = Godam::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimGodam)])->value('id');
            $wastage_id = Wastages::whereRaw('LOWER(REPLACE(name, " ", "")) = LOWER(?)', [str_replace(' ', '', $trimWastage)])->value('id');
            /******* end trims spaces in between**********/
             //dd($godam_id);
            if($godam_id == null) {
                $godam = Godam::Create([
                    "name" => $trimGodam,
                    "status" => "active"
                ]);
                $godam_id =$godam->id;
            }

            if($wastage_id == null){
                $wastage = Wastages::Create([
                    "name" =>  $trimWastage,
                    "status" => "active"
                ]);
                $wastage_id = $wastage->id ;
             }
            if($godam_id && $wastage_id){
                self::openingWastageSave($godam_id,$wastage_id,$trimQuantity);
                self::wastageStockSave($godam_id,$wastage_id,$trimQuantity);

        }else{

        }

        }
        }catch(Exception $ex){

        }

    }
    function openingWastageSave($godam_id, $wastage_id,$trimQuantity){
                $openingWastage=OpeningWastage::where('godam_id',$godam_id)
                        ->where('waste_id',$wastage_id)
                        ->first();
                if($openingWastage){
                      $openingWastage->quantity += $trimQuantity;
                      $openingWastage->save();
                    }else{
                        $openingWastage = new OpeningWastage();
                        $openingWastage->godam_id = $godam_id;
                        $openingWastage->waste_id = $wastage_id;
                        $openingWastage->quantity =$trimQuantity;
                        $openingWastage->save();
                    }
    }

    function wastageStockSave($godam_id, $wastage_id,$trimQuantity){
                $wastageStock=WasteStock::where('godam_id',$godam_id)
                        ->where('waste_id',$wastage_id)
                        ->first();
                if($wastageStock){
                      $wastageStock->quantity_in_kg += $trimQuantity;
                      $wastageStock->save();
                    }else{
                        $wastageStock = new WasteStock();
                        $wastageStock->godam_id = $godam_id;
                        $wastageStock->waste_id = $wastage_id;
                        $wastageStock->quantity_in_kg =$trimQuantity;
                        $wastageStock->save();
                    }
    }
}
