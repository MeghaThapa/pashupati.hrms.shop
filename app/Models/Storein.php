<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Items;
use App\Models\Setupstorein;
use App\Models\StoreinItem;

class Storein extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'admin_storein';
    protected $fillable = [
        'purchase_date',
        'supplier_id',
        'sr_no',
        'bill_no',
        'pp_no',
        'size',
        'tax',
        'category_id', 'subcategory_id', 'type', 'item', 'purchase_code', 'sub_total', 'discount', 'trasnport', 'total', 'total_paid', 'total_due', 'payment_type', 'purchase_image', 'status', 'storein_status', 'note'
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

    public function storeinItems()
    {
        return $this->hasMany(StoreinItem::class, "storein_id", "id");
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, "tax_id", "id");
    }
    // public
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, "payment_method_id", "id");
    }
    public function purchaseDamage()
    {
        return $this->hasOne('App\Models\PurchaseDamage');
    }

    public function storeincategory()
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }

    public function storeinsubcategory()
    {
        return $this->belongsTo(SubCategory::class, "sub_categories_id", "id");
    }

    public function storeinproduct()
    {
        return $this->belongsTo(Items::class, "items_id", "id");
    }

    public function storeintype()
    {
        return $this->belongsTo(Setupstorein::class, "storein_id", "id");
    }
}
