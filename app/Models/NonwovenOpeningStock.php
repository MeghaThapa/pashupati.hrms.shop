<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonwovenOpeningStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'receive_date', 'receive_no', 'fabric_roll', 'fabric_gsm','fabric_name','fabric_color','length','gross_weight','net_weight','godam_id'
    ];

    public function getGodam()
    {
        return $this->belongsTo('App\Models\Godam','godam_id');
    }
}
