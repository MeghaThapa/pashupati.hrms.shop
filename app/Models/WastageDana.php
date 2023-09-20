<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WastageDana extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function danaName()
    {
        return $this->belongsTo(DanaName::class,'dana_id');
    }

    public function danaGroup()
    {
        return $this->belongsTo(DanaGroup::class,'dana_group_id');
    }
}
