<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;
    protected $table = "raw_materials";

    public function storein_type()
    {
        return $this->belongsTo('App\Models\StoreinType', 'storein_type_id', 'id');
    }

    public function toGodam()
    {
        return $this->belongsTo('App\Models\Godam', 'to_godam_id', 'id');
    }

    public function fromGodam()
    {
        return $this->belongsTo('App\Models\Godam', 'from_godam_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id', 'id');
    }

    public function rawMaterialsItem(){
        return $this->hasMany('App\Models\RawMaterialItem', 'raw_material_id', 'id');
    }

}
