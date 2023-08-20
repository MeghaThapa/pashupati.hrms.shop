<?php

namespace App\Models;

use App\Models\FabricGroup;
use App\Models\FabricSendAndReceiveEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSendAndReceiveLaminatedSent extends Model
{
    use HasFactory;
    protected $table = "fabric_send_and_receive_laminated_sent";
    protected $fillable = [
        "fsr_entry_id", "fabric_name" , "slug" , "net_wt" , "average_wt" ,"gross_wt", "gram_wt" , "meter" ,"fabricgroup_id" , 'standard_wt' ,"loom_no" , "roll_no","fabid"
    ];
    public function fsrentry(){
        return $this->belongsTo(FabricSendAndReceiveEntry::class);
    }
    public function fabrigroup(){
        return $this->belongsTo(FabricGroup::class);
    }
}
