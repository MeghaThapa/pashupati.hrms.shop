<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialSalesEntry extends Model
{
    use HasFactory;
    public function godam()
    {
        return $this->belongsTo('App\Models\Godam', 'godam_id', "id");
    }
     public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id', "id");
    }

    public function getSalesData()
    {
        return $this->hasMany('App\Models\RawMaterialItemsSale', 'raw_material_sales_entry_id', "id");
    }
}
