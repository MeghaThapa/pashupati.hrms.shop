<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LamWaste extends Model
{
    use HasFactory;
    protected $table="lam_waste_recover";
    protected $fillable=[
        'date','polo_waste','fabric_waste','total_waste'
    ];
}
