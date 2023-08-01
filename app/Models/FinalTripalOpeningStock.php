<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalTripalOpeningStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number','bill_date','godam_id','name','slug','finaltripalname_id'
        ,'roll_no','gram','net_wt','meter','average','gsm','created_at','updated_at','date_en','date_np','type'
    ];

    public function getGodam()
    {
        return $this->belongsTo('App\Models\Godam','godam_id');
    }
}
