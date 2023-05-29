<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DanaGroup;

class DanaName extends Model
{
    use HasFactory;
    protected $table = "dana_names";
    
    public function danagroup(){
        return $this->belongsTo(DanaGroup::class);
    }
}
