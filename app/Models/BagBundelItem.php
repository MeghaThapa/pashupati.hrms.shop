<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagBundelItem extends Model
{
    use HasFactory;
    protected $table="bag_bundel_items";

    public function group(){
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function bagBrand(){
        return $this->belongsTo(BagBrand::class, 'bag_brand_id');
    }


}
