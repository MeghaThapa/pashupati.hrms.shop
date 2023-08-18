<?php

namespace App\Models;

use App\Models\FabricSendAndReceiveEntry;
use App\Models\FabricSendAndReceiveUnlaminatedFabric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSendAndReceiveTemporaryForLamination extends Model
{
    use HasFactory;
    protected $table = "fabric_send_and_receive_temp_lam";
    protected $fillable = [
        "fsr_entry_id","unlam_fabric_id", "fabric_name" ,"slug"
     ];

     public function getfsrentry(){
        return $this->belongsTo(FabricSendAndReceiveEntry::class,"fsr_entry_id","id");
     }
     public function getfabric(){
        return $this->belongsTo(FabricSendAndReceiveUnlaminatedFabric::class,"unlam_fabric_id","id");
     }
     public function getLamFabricDetails(){
      return $this->hasMany(FabricSendAndReceiveLaminatedFabricDetails::class,"fab_temp_lam_id","id");
     }
}
