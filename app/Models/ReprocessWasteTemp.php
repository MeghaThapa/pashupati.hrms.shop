<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReprocessWasteTemp extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function wastage()
    {
        return $this->belongsTo(Wastages::class,'wastage_id');
    }

}
