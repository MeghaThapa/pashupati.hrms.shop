<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreinDepartment extends Model
{
    use HasFactory;
    protected $table='storein_departments';
    protected $fillable = [
        'name', 'slug', 'status', 'id','category_id'
    ];
}
