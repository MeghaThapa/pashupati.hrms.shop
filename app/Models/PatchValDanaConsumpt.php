<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatchValDanaConsumpt extends Model
{
    use HasFactory;
       public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
      public function godam()
    {
        return $this->belongsTo('App\Models\Godam', 'godam_id', 'id');
    }
    public function shift()
    {
        return $this->belongsTo('App\Models\Shift', 'shift_id', 'id');
    }
    public function plantType()
    {
        return $this->belongsTo('App\Models\ProcessingStep', 'plantType_id', 'id');
    }
     public function plantName()
    {
        return $this->belongsTo('App\Models\ProcessingSubcat', 'plantName_id', 'id');
    }
}
