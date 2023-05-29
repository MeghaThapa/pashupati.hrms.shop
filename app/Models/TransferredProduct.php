<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class TransferredProduct extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finished_id', 'showroom_id', 'transferred_code', 'slug', 'transferred_date', 'cartoon_number', 'transferred_quantities', 'transferred_image', 'note', 'status'
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
                'source' => 'transferred_code'
            ]
        ];
    }

    // return transferred code
    public function transferredCode($prefix)
    {
        return $prefix . $this->transferred_code;
    }


    /**
     * Return relation with FinishedProduct Model
     *
     *
     */
    public function finishedProduct()
    {
        return $this->belongsTo('App\Models\FinishedProduct', 'finished_id');
    }

    /**
     * Return relation with Showroom Model
     *
     *
     */
    public function showroom()
    {
        return $this->belongsTo('App\Models\Showroom', 'showroom_id');
    }

    /**
     * Return the transferred product attached picture
     *
     * @var string
     */
    public function imagepath()
    {
        if (isset($this->transferred_image)) {
            return asset('img/transferred/' . $this->transferred_image);
        }
    }

    /**
     * Return true if the transferred product is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
}