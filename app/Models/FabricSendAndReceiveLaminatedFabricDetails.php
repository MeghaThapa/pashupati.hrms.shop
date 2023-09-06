<?php

namespace App\Models;

use App\Models\FabricSendAndReceiveTemporaryForLamination;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSendAndReceiveLaminatedFabricDetails extends Model
{
    use HasFactory;
    protected $table = "fabric_send_and_receive_laminated_temp_details";
    protected $fillable = [
        "temp_lam",'gram_wt','gross_wt','average_wt','net_wt','meter','roll_no','loom_no' , "standard_wt" , "fbgrp_id"
    ];
    public function temporarylamfabric(){
        return $this->belongsTo(FabricSendAndReceiveTemporaryForLamination::class,"temp_lam","id");
    }

    public function getfabricBill(){
        return $this->belongsTo('App\Models\FabricSendAndReceiveEntry','fsr_entry_id');
    }
}
