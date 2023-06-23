<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagFabricReceiveItemSentStock extends Model
{
    use HasFactory;
    protected $table = "bag_fabric_receive_item_sent_stock";
    protected $fillable = [
        "fabric_bag_entry_id","fabric_id","gram","gross_wt","net_wt","meter","roll_no","loom_no"
    ];
    public function fabric(){
        return $this->belongsTo(Fabric::class);
    }
}
