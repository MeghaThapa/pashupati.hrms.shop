<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapeEntry extends Model
{
    use HasFactory;
    protected $table = "tape_entry";
    protected $id = 'id';
    protected $fillable = [
        'receipt_number','tape_entry_date','created_at','updated_at'
    ];
}
