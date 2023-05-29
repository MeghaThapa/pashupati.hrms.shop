<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class FinishedProduct extends Model
{

    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'porcessing_pro_id', 'finished_code', 'slug', 'sub_cat_id', 'sizes', 'quantities', 'rejected_quantities', 'finished_date', 'finished_image', 'status', 'note'
    ];



    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'finished_code'
            ]
        ];
    }

    // return finished code
    public function finishedCode($prefix)
    {
        return $prefix . $this->finished_code;
    }

    /**
     * Return relation with ProcessingProduct Model
     *
     *
     */
    public function processingProduct()
    {
        return $this->belongsTo('App\Models\ProcessingProduct', 'porcessing_pro_id');
    }

    /**
     * Return relation with TransferredProduct Model
     *
     *
     */
    public function transferredProducts()
    {
        return $this->hasMany('App\Models\TransferredProduct', 'finished_id');
    }

    /**
     * Return relation with Subcategory Model
     *
     *
     */
    public function productType()
    {
        return $this->belongsTo('App\Models\SubCategory', 'sub_cat_id');
    }

    /**
     * Return relation with UsedProduct Model
     *
     *
     */
    public function usedProducts()
    {
        return $this->hasMany('App\Models\UsedProduct', 'finished_id');
    }

    /**
     * Return the finished product attached picture
     *
     * @var string
     */
    public function imagepath()
    {
        if (isset($this->finished_image)) {
            return asset('img/finished/' . $this->finished_image);
        }
    }

    /**
     * Return true if the finished product is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
}
