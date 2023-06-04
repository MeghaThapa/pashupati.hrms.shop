<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storeout extends Model
{
    protected $table = 'storeout';
    protected $fillable = [];

    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
     public function storeoutItems()
    {
        return $this->hasMany(StoreOutItem::class, "storeout_id", "id");
    }

}
