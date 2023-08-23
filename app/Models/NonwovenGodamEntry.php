<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonwovenGodamEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no','bill_date','name','slug','roll','created_at','updated_at','stock_id','length',
        'gram','gross','net','meter','average','gsm','color','godam_id','status','nonwovengodam_id'
    ];
}
