<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BswLamPrintedFabricStock extends Model
{
    use HasFactory;
      public function printedFabric(){
        return $this->belongsTo(PrintedFabric::class,'printed_fabric_id','id');
    }
}
