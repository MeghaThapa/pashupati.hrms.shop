<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOutItem extends Model
{
    use HasFactory;

    protected $table = "store_out_items";
    protected $id = "id";
    public function placement()
    {
        return $this->belongsTo('App\Models\Placement', 'placement_id', "id");
    }
    public function itemsOfStorein()
    {
        return $this->belongsTo('App\Models\ItemsOfStorein', 'item_of_storein_id', "id");
    }

    public function department()
    {
        return $this->belongsTo('App\Models\StoreoutDepartment', 'storeoutDepartment_id', "id");
    }
    public function size()
    {
        return $this->belongsTo('App\Models\Size', 'size_id', "id");
    }
     public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_id', "id");
    }
    public function storeOut()
    {
        return $this->belongsTo('App\Models\Storeout', 'storeout_id', "id");
    }

}
