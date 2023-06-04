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
    protected $table = 'storein';
    protected $fillable = [
            'purchase_date',
            'supplier_id',
            'sr_no',
            'bill_no',
            'pp_no',
            'storein_department_id',
            'sub_total',
            'total_discount',
            'dicount_percent',
            'grand_total',
            'image_path',
            'status',
            'note',
            'extra_charges'
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
        return $this->belongsTo('App\Models\Supplier','supplier_id');
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

    // public function storeinproduct()
    // {
    //     return $this->belongsTo(ItemsOfStorein::class, "items_id", "id");
    // }

    public function storeInDepartment()
    {
        return $this->belongsTo(StoreinDepartment::class, "storein_department_id", "id");
    }

    public function storeinType()
    {
        return $this->belongsTo(StoreinType::class, "storein_type_id", "id");
    }
}
