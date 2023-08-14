<?php

namespace App\Models;

use App\Models\FabricSendAndReceiveEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fabric;

class FabricSendAndReceiveUnlaminatedFabric extends Model
{
    use HasFactory;
    protected $table = "fabric_send_and_receive_unlaminated_revised";
    protected $id = 'id';
    protected $fillable = [
       "fsr_entry_id",'fabric_id',"status"
    //    'roll_no','gross_wt','net_wt','meter','average','gram_wt','created_at','updated_at', 
    ];
    public function getfabric(){
        return $this->belongsTo(Fabric::class,"fabric_id","id");
    }
    public function getfsrentry(){
        return $this->belongsTo(FabricSendAndReceiveEntry::class,"fsr_entry_id","id");
    }
}
