<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalTripalBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'plantname_id','godam_id','planttype_id','shift_id','bill_no','bill_date',
        'created_at','updated_at',
    ];
}
