<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintingAndCuttingBagItem extends Model
{
    use HasFactory;
    protected $table="printing_and_cutting_bag_items";
    protected $fillable=['printAndCutEntry_id','group_id','bag_brand_id','quantity_piece','average','wastage','roll_no','fabric_id','net_weight','cut_length','gross_weight','meter','avg','req_bag','godam_id','wastage_id'];
     public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', "id");
    }
      public function brandBag()
    {
        return $this->belongsTo('App\Models\BagBrand', 'bag_brand_id', "id");
    }
     public function fabric()
    {
        return $this->belongsTo('App\Models\Fabric', 'fabric_id', "id");
    }
}



