<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintingAndCuttingBagStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "printing_and_cutting_bag_stocks";
    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', "id");
    }
    public function bagBrand()
    {
        return $this->belongsTo('App\Models\BagBrand', 'bag_brand_id', "id");
    }
}
