<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'return_reason', 'slug', 'refund_amount', 'return_date', 'return_image', 'note', 'status'
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
                'source' => 'return_reason'
            ]
        ];
    }

    /**
     * Return the purchase attached picture
     *
     * @var string
     */
    public function imagepath()
    {
        if (isset($this->return_image)) {
            return asset('img/return-purchases/' . $this->return_image);
        }
    }


    /**
     * Return expense short note
     *
     * @return string
     */
    public function shortNote()
    {
        if (strlen($this->note) > 80) {
            return substr($this->note, 0, 80) . '...';
        }
        return $this->note;
    }

    /**
     * Return true if the expense is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    /**
     * Get the purchase that owns the return.
     */
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase');
    }
}
