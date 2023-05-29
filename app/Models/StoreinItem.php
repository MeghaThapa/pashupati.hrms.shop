<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreinItem extends Model
{
    use HasFactory;
    protected $table = 'admin_storein_item';

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', "id");
    }
    public function size()
    {
        return $this->belongsTo('App\Models\Size', 'size_id', "id");
    }
    public function item()
    {
        return $this->belongsTo('App\Models\Items', 'item_id', "id");
    }
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_id', "id");
    }
   
}
