<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'product_name', 'quantity', 'used_quantity', 'return_quantity', 'damage_quantity', 'unit', 'unit_price', 'discount', 'total'
    ];


    /**
     * Return product discount
     *
     *
     */
    public function discountAmount()
    {
        return ($this->discount / 100) * $this->unit_price * $this->quantity;
    }


    /**
     * Return used available
     *
     *
     */
    public function usedQty()
    {
        return $this->usedProducts()->sum('used_quantity') > 0 ?  $this->usedProducts()->sum('used_quantity') : 0;
    }

    /**
     * Return available quantity
     *
     *
     */
    public function availableQuantity()
    {
        return  $this->quantity - ($this->usedQty() + $this->return_quantity + $this->damage_quantity);
    }

    /**
     * Return relation with Purchase Model
     *
     *
     */
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase');
    }


    /**
     * Return relation with PurchaseProduct Model
     *
     *
     */
    public function usedProducts()
    {
        return $this->hasMany('App\Models\UsedProduct', 'purchase_pro_id');
    }
}
