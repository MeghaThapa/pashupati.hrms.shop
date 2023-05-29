<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminStoreOutItem extends Model
{
    use HasFactory;

    protected $table = "admin_store_out_items";
    protected $id = "id";
    public function placement()
    {
        return $this->belongsTo('App\Models\Placement', 'placement_id', "id");
    }
    public function item()
    {
        return $this->belongsTo('App\Models\Items', 'item_id', "id");
    }
    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id', "id");
    }
    public function storeOut()
    {
        return $this->belongsTo('App\Models\Storeout', 'storeout_id', "id");
    }
}
