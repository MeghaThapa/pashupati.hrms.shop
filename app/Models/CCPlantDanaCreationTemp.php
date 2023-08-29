<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCPlantDanaCreationTemp extends Model
{
    use HasFactory;

    protected $table = "cc_plant_dana_creation";

    protected $guarded = [];

    public function danaGroup(){
        return $this->belongsTo(DanaGroup::class,'dana_group_id');
    }

    public function danaName(){
        return $this->belongsTo(DanaName::class,'dana_name_id');
    }
}
