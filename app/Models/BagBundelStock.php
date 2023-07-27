<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagBundelStock extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "bundle_no","group_id","bag_brand_id","bundle_no","qty_pcs","qty_in_kg","average_weight","type"
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
    public function bagBrand()
    {
        return $this->belongsTo(BagBrand::class, 'bag_brand_id', 'id');
    }

}
