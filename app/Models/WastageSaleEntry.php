<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WastageSaleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id','waste_id','quantity','created_at','updated_at','status'
    ];

    public function getWaste()
    {
        return $this->belongsTo('App\Models\WasteStock', 'waste_id', "id");
    }
}
