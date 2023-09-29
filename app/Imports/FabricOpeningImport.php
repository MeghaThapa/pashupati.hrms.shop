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
    public $date_np;

    public function __construct($godam,$type,$date_np){
        $this->godam = $godam;
        $this->type = $type;
        $this->date_np = $date_np;
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

                 $name_size = trim($row['size']);
                 $slug = $row['gram'];

                 $size = $name_size .'('. $row['gram'].')';
            
                 $fabricgroup = FabricGroup::firstOrCreate([
                     'slug' => $slug
                 ], [
                     'name' => $row['gram'],
                     'slug' => $slug,
                     'is_active' => '1',
                     
                 ]);
                 $fabricgroup_id = FabricGroup::where('slug',$slug)->value('id');

                 $input = $row['size'];
                 $parts = explode(' ', $input);
                 $firstString = $parts[0];   
                         
                 $find_name = filter_var($firstString, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                 $data = $find_name  ;

                 $average = $row['grams'];

                 $gram_wts = ($average) / $data;

                 $average_wt = round($average, 2);
                 $gram_wt = round($gram_wts, 2);
            
            
                 $fabric = Fabric::create( [
                         'name' => $size,
                         'roll_no' => $row['roll_no'],
                         'loom_no' => $row['loom_no'],
                         'fabricgroup_id' => $fabricgroup_id,
                         'gross_wt' => $row['gross_wt'],
                         'net_wt' => $row['net_wt'],
                         'meter' => $row['meter'],
                         'gram_wt' => $gram_wt,
                         'average_wt' => $average_wt,
                         'godam_id' => $this->godam,
                         'date_np' => $this->date_np,
                         'bill_no' => $bill_no,
                         "is_laminated" => $this->type == "lam" ? "true" :  "false"  
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
                     'average_wt' => $average_wt,
                     'godam_id' => $this->godam,
                     'bill_no' => $bill_no,
                     'fabric_id' => $fabric->id,
                      'date_np' => $this->date_np,
                      'status_type' => 'active',
                     "is_laminated" => $this->type == "lam" ? "true" :  "false"  
                 ]);
            }

          
            
        }catch(\Throwable $th){
            // dd($th);
            $this->message = $th;
        }
    }
    public function getExceptionMessage(){
        return $this->message;
    }
}
