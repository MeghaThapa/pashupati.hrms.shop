<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSaleItems extends Model
{
    use HasFactory;
    protected $table = "fabric_sale_items";
    protected $fillable = [
        "sale_entry_id" , 'fabric_id' 
       ];
       public function getfabric(){
        return $this->belongsTo(Fabric::class,"fabric_id","id");
       }
       public function getsaleentry(){
        return $this->belongsTo(FabricSaleEntry::class,"entry_id","id");
       }
}
