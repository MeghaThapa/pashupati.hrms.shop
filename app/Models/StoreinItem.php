<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreinItem extends Model
{
    use HasFactory;
    protected $table = 'storein_item';

    public function storeinCategory()
    {
        return $this->belongsTo('App\Models\StoreinCategory', 'storein_category_id', "id");
    }
    public function size()
    {
        return $this->belongsTo('App\Models\Size', 'size_id', "id");
    }
    public function storeinDepartment()
    {
        return $this->belongsTo('App\Models\StoreinDepartment', 'department_id', "id");
    }
    public function itemsOfStorein()
    {
        return $this->belongsTo('App\Models\ItemsOfStorein', 'storein_item_id', "id");
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_id', "id");
    }

}
