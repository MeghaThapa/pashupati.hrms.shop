<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawmaterialOpeningItem extends Model
{
    use HasFactory;
    protected $table='rawmaterial_opening_items';

    public function danaGroup()
    {
        return $this->belongsTo('App\Models\DanaGroup', 'dana_group_id', "id");
    }
    public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
}
