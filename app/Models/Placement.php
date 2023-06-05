<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    use HasFactory;
    protected $table = 'placements';
    protected $fillable = [
        'name', 'department_id', 'status'
    ];

    public function storeinDepartment()
    {
        return $this->belongsTo(StoreinDepartment::class, 'department_id', 'id');
    }
}
