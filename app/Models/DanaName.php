<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DanaGroup;

class DanaName extends Model
{
    use HasFactory;
    protected $table = "dana_names";

      protected $fillable = [
        'name','status','dana_group_id'
    ];

    public function danagroup(){
        return $this->belongsTo(DanaGroup::class,'dana_group_id');
    }

    public function rawMaterialStock()
    {
        return $this->hasOne(RawMaterialStock::class,'dana_name_id');
    }
}
