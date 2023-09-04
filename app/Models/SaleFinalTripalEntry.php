<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleFinalTripalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no','bill_date','salefinal_id','created_at','updated_at','name','slug','roll','gross','net','meter','average','gram','stock_id','bill_type','godam_id','finaltripal_id','gsm'
    ];
}
