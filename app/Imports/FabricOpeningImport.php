<?php

namespace App\Imports;

use App\Models\Fabric;
use App\Models\FabricGroup;
use App\Models\FabricStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class FabricOpeningImport implements ToCollection,WithHeadingRow,WithCalculatedFormulas
{
    public $message;
    public $godam;
    public $type;

    public function __construct($godam,$type){
        $this->godam = $godam;
        $this->type = $type;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $bill_no = "opening";
        // dd($collection);

        try{
           foreach($collection as $row){
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
                        'godam_id' => $this->godam,
                        'bill_no' => $bill_no,
                        "is_laminated" => $this->type == "lam" ? "true" :  "false"  
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
                    'godam_id' => $this->godam,
                    'bill_no' => $bill_no,
                    // 'fabric_id' => $fabric->id, live ma xa hola yo sayad
                    "is_laminated" => $this->type == "lam" ? "true" :  "false"  
                ]);
           }
            
        }catch(\Throwable $th){
            $this->message = $th;
        }
    }
    public function getExceptionMessage(){
        return $this->message;
    }
}
