<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonwovenSaleEntryList extends Model
{
    use HasFactory;

    protected $fillable = [
        'receive_date','receive_no','fabric_roll','fabric_gsm','fabric_name','fabric_color','length','gross_weight','net_weight','bill_id','godam_id','created_at','updated_at','status'
    ];
}
