<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoLoad extends Model
{
    use HasFactory;
    protected $table='auto_loads';
      public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
    public function danaGroup()
    {
        return $this->belongsTo('App\Models\DanaGroup', 'dana_group_id', "id");
    }

    public function autoLoadItems()
    {
        return $this->hasMany(AutoloadItems::class, "autoload_id", "id");
    }

}


