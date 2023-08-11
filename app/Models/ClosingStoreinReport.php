<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingStoreinReport extends Model
{
    use HasFactory;
     public function itemsOfStorein()
    {
        return $this->belongsTo('App\Models\ItemsOfStorein', 'name', "id");
    }
}
