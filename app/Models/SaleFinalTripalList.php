<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleFinalTripalList extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number','bill_date','salefinal_id','finaltripal_id','created_at','updated_at'
    ];
}
