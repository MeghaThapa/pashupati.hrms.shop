<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapeDanaItem extends Model
{
    use HasFactory;

    public function godam(){
        return $this->belongsTo(Godam::class,'from_godam_id');
    }

    public function danaName(){
        return $this->belongsTo(DanaName::class,'dana_name_id');
    }

    public function danaGroup(){
        return $this->belongsTo(DanaGroup::class,'dana_group_id');
    }
}
