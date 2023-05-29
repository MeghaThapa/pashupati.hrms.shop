<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class PurchaseDamage extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'damage_reason', 'slug', 'damage_date', 'damage_image', 'note', 'status'
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
                'source' => 'damage_reason'
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
        if (isset($this->damage_image)) {
            return asset('img/damage-purchases/' . $this->damage_image);
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
