<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function preparedBy(){
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function purchaseOrderItems(){
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
