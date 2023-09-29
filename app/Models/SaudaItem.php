<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaudaItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'qty' => 'decimal:2',
        'rate' => 'decimal:2'
    ];

    public function deliveryOrderForItem()
    {
        return $this->belongsTo(DeliveryOrderForItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function dispatchSaudaItemToParty()
    {
        return $this->hasMany(DispatchSaudaItemToParty::class,'sauda_item_id');
    }
}
