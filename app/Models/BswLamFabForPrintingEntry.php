<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BswLamFabForPrintingEntry extends Model
{
    use HasFactory;
     public function plantType(){
        return $this->belongsTo(ProcessingStep::class);
    }
     public function plantName(){
        return $this->belongsTo(ProcessingSubcat::class);
    }
     public function shift(){
        return $this->belongsTo(Shift::class);
    }
     public function godam(){
        return $this->belongsTo(Godam::class);
    }
     public function group(){
        return $this->belongsTo(Group::class);
    }
     public function bagBrand(){
        return $this->belongsTo(BagBrand::class,'bag_brands_id','id');
    }
}
