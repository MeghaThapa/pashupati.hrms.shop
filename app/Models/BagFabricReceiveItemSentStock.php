<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BagFabricReceiveItemSentStock extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "bag_fabric_receive_item_sent_stock";
    protected $fillable = [
        "fabric_bag_entry_id","fabric_id","gram","gross_wt","net_wt","meter","roll_no","loom_no","average",'status'
    ];
    public function fabric(){
        return $this->belongsTo(Fabric::class,"fabric_id");
    }
    public function fabricTransferForBag(){
        return $this->belongsTo(FabricTransferEntryForBag::class,"fabric_bag_entry_id","id");
    }
}
