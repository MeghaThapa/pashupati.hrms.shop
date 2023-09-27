<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DispatchSaudaItemToParty extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function deliveryOrderForItem()
    {
        return $this->belongsTo(DeliveryOrderForItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saudaItem()
    {
        return $this->belongsTo(SaudaItem::class);
    }
}
