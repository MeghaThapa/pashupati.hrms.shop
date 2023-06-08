<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsOfStorein extends Model
{
    use HasFactory;
    protected $table='items_of_storeins';
    protected $fillable = ['name', 'pnumber','department_id' ,'category_id', 'size_id','unit_id','status' ];//,'supplier_id'
    public function storeinCategory()
    {
        return $this->belongsTo('App\Models\StoreinCategory', 'category_id', "id");
    }
     public function storeinDepartment()
    {
        return $this->belongsTo('App\Models\StoreinDepartment', 'department_id', "id");
    }
    public function size()
    {
        return $this->belongsTo('App\Models\Size', 'size_id', "id");
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_id', "id");
    }

}
