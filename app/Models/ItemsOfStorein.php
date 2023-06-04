<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsOfStorein extends Model
{
    use HasFactory;
    protected $table='items_of_storeins';
    public function storeinCategory()
    {
        return $this->belongsTo('App\Models\StoreinCategory', 'category_id', "id");
    }
     public function storeinDepartment()
    {
        return $this->belongsTo('App\Models\StoreinDepartment', 'department_id', "id");
    }

}
