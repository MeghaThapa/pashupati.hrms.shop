<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSendAndReceiveDanaConsumptionList extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "fabric_send_and_receive_dana_consumption_list";
    protected $fillable = [
        "fsr_entry" , "godam_id" ,"dana_name" ,"dana_group" , "consumption_quantity"
    ];

    public function dananame(){
        return $this->belongsTo(DanaName::class);
    }
    public function danagroup(){
        return $this->belongsTo(DanaGroup::class);
    }
    public function fsrentry(){
        return $this->belongsTo(FabricSendAndReceiveEntry::class);
    }
}
