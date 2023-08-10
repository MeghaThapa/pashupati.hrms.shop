<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BswFabSendcurtxReceivpatchvalveEntry extends Model
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
}
