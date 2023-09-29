<?php

namespace App\Models;

use App\Models\DanaGroup;
use App\Models\DanaName;
use App\Models\FabricSendAndReceiveEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSendAndReceiveDanaConsumption extends Model
{
    use HasFactory;
    protected $table = "fabric_send_and_receive_dana_consumption";
    protected $fillable = [
        "fsr_entry_id" , "dana_name_id" ,"dana_group_id" , "consumption_quantity","autoloader_id",'from_godam_id','plant_name_id','plant_type_id','shift_id'
    ];
    public function dananame(){
        return $this->belongsTo(DanaName::class,"dana_name_id","id");
    }
    public function danagroup(){
        return $this->belongsTo(DanaGroup::class);
    }
    public function fsrentry(){
        return $this->belongsTo(FabricSendAndReceiveEntry::class,"fsr_entry_id","id");
    }
}
