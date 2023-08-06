<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricGodam extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no', 'bill_date', 'remarks'
    ];
}
