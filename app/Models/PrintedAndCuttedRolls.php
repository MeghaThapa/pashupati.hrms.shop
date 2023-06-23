<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintedAndCuttedRolls extends Model
{
    use HasFactory;
    protected $table = "printed_and_cutted_rolls";
    protected $fillable = [
        "receipt_number" ,"date", "date_np" , "status"
    ];
}
