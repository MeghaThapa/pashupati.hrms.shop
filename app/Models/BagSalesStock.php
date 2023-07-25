<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagSalesStock extends Model
{
    use HasFactory;
      public function group()
        {
            return $this->belongsTo(Group::class, 'group_id', 'id');
        }
      public function brandBag()
        {
            return $this->belongsTo(BagBrand::class, 'brand_bag_id', 'id');
        }
}
