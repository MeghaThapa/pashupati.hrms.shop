<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreoutDepartment extends Model
{
    use HasFactory;
    protected $table= 'storeout_departments';
    public function createdBy()
        {
            return $this->belongsTo(User::class, 'created_by');
        }
}
