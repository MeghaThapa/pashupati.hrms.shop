<?php

namespace App\Models;

use App\Models\Fabric;
use App\Models\FabricSaleEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSale extends Model
{
    use HasFactory;
   protected $table = "fabric_sales";
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
