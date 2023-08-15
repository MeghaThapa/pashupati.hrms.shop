<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoubleTripalDanaConsumption extends Model
{
    use HasFactory;

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

    public function getAutoloader()
    {
        return $this->belongsTo('App\Models\AutoLoadItemStock', 'autoloader_id', 'id');
    }
}
