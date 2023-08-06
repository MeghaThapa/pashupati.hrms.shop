<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintedFabDanaConsumpt extends Model
{
    use HasFactory;
      public function godam()
    {
        return $this->belongsTo('App\Models\Godam', 'godam_id', "id");
    }
      public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
}
