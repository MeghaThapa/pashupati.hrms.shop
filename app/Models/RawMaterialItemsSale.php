<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialItemsSale extends Model
{
    use HasFactory;
    protected $table="raw_material_items_sales";
     public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
    public function danaGroup()
    {
        return $this->belongsTo('App\Models\DanaGroup', 'dana_group_id', "id");
    }
}
