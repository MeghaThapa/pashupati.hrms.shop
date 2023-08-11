<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuedStoreinReport extends Model
{
    use HasFactory;
     protected $fillable=[
    'date',
    'name',
    'rate',
    'quantity',
    'total'
    ];
    public function itemsOfStorein()
    {
        return $this->belongsTo('App\Models\ItemsOfStorein', 'name', "id");
    }
}
