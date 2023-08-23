<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReprocessWasteTemp extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function waste()
    {
        return $this->belongsTo(Wastages::class,'waste_id');
    }

    public function dana()
    {
        return $this->belongsTo(DanaName::class,'dana_id');
    }
}
