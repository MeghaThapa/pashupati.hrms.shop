<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripalGodamEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no','bill_date','name','slug','roll','created_at','updated_at','stock_id',
        'gram','gross','net','meter','average','gsm','finaltripal_id','godam_id','status','tripalgodam_id'
    ];
}
