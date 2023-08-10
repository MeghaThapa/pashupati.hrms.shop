<?php

namespace App\Models;

use App\Models\DanaGroup;
use App\Models\DanaName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryDanaConsumptionFSRTable extends Model
{
    use HasFactory;
    protected $table = "temp_dana_consumption_table_fsr";
    protected $fillable = [
        "bill_number", "dana_name_id" , "dana_group_id", "consumption_quantity"
    ];
    public function dananame(){
        return $this->belongsTo(DanaName::class,"dana_name_id","id");
    }
    public function danagroup(){
        return $this->belongsTo(DanaGroup::class,"dana_group_id","id");
    }
}
