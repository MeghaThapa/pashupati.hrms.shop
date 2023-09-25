<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapeEntryOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'godam_id','qty','type','date'
    ];
}
