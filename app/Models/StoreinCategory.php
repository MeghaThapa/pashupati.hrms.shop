<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreinCategory extends Model
{
    use HasFactory;
    protected $table='storein_categories';
    protected $fillable=[
        'name',
        'note',
        'slug',
        'status'
    ];
}
