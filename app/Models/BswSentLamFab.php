<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BswSentLamFab extends Model
{
    use HasFactory;
    protected $table = "bsw_sent_lam_fabs";

    public function fabric(){
        return $this->belongsTo(Fabric::class,'fabric_id','id');
    }
}
