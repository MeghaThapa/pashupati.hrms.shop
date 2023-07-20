<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagBundelStock extends Model
{
    use HasFactory;
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
    public function bagBrand()
    {
        return $this->belongsTo(BagBrand::class, 'bag_brand_id', 'id');
    }

}
