<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsedProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finished_id', 'purchase_pro_id', 'used_quantity'
    ];


    /**
     * Return relation with Purchase Model
     *
     *
     */
    public function purchaseProduct()
    {
        return $this->belongsTo('App\Models\PurchaseProduct', 'purchase_pro_id');
    }
}
