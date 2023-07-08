<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintsAndCutsDanaConsumption extends Model
{
    use HasFactory;
    protected $table="prints_and_cuts_dana_consumptions";
       public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
    public function danaGroup()
    {
        return $this->belongsTo('App\Models\DanaGroup', 'dana_group_id', "id");
    }
      public function godam()
    {
        return $this->belongsTo('App\Models\Godam', 'godam_id', 'id');
    }
}
