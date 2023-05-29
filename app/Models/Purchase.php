<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_date', 'supplier_id', 'purchase_code', 'sub_total', 'discount', 'trasnport', 'total', 'total_paid', 'total_due', 'payment_type', 'purchase_image', 'status', 'note'
    ];


    /**
     * Return the purchase attached picture
     *
     * @var string
     */
    public function imagepath()
    {
        if (isset($this->purchase_image)) {
            return asset('img/purchases/' . $this->purchase_image);
        }
    }

    /**
     * Return true if the purchase is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    /**
     * Return relation with Supplier Model
     *
     *
     */
    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier', 'supplier_id');
    }

    /**
     * Return relation with PurchaseProduct Model
     *
     *
     */
    public function purchaseProducts()
    {
        return $this->hasMany('App\Models\PurchaseProduct', 'purchase_id');
    }

    /**
     * Return relation with ProcessingProduct Model
     *
     *
     */
    public function processingProducts()
    {
        return $this->hasMany('App\Models\ProcessingProduct', 'purchase_id');
    }



    /**
     *
     * Get the purchase return record associated with the purchase.
     */
    public function purchaseReturn()
    {
        return $this->hasOne('App\Models\PurchaseReturn');
    }

    /**
     *
     * Get the purchase damage record associated with the purchase.
     */
    public function purchaseDamage()
    {
        return $this->hasOne('App\Models\PurchaseDamage');
    }
}
