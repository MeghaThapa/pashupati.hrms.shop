<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReprocessWaste extends Model
{
    use HasFactory;

    protected $guarded = []; 

    public function godam()
    {
        return $this->belongsTo(Godam::class,'godam_id');
    }
}
