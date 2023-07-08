<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintingAndCuttingBagItem extends Model
{
    use HasFactory;
    protected $table="printing_and_cutting_bag_items";
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
        return $this->belongsTo('App\Models\fabric', 'fabric_id', "id");
    }
}
