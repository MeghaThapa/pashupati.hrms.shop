<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricEntry extends Model
{
    use HasFactory;

    protected $fillable = ['entry_date','godam_id','file_path'];

    public function godam()
    {
        return $this->belongsTo(Godam::class,'godam_id');
    }
}
