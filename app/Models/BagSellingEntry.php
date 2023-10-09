<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagSellingEntry extends Model
{
    use HasFactory;
    protected $table = 'bag_selling_entries';
    public function bagSellingItem()
    {
        return $this->hasMany(BagSellingItem::class, "bag_selling_entry_id", "id");
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
