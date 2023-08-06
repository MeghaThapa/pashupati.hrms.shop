<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FabricLaminatedSentFSR;
use App\Models\DanaName;
use App\Models\DanaGroup;

class FabricFSRDanaConsumption extends Model
{
    use HasFactory;
    protected $table = "fabric_fsr_dana_consumption";
    protected $fillable = [
        "fabric_laminated_sent_fsr_id" , "dana_name_id", "dana_group_id", "consumption_quantity"
    ];
    public function fsrlam(){
        return  $this->belongsTo(FabricLaminatedSentFSR::class,"fabric_laminated_sent_fsr_id","fabric_laminated_sent_fsr_id");
    }
    public function dananame(){
        return $this->belongsTo(DanaName::class,"dana_name_id","id");
    }
    public function danagroup(){
        return $this->belongsTo(DanaGroup::class,"dana_group_id","id");
    }
}
