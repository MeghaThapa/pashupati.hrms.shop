<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'category_id', 'slug', 'sizes', 'note', 'status'
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
                'source' => 'name'
            ]
        ];
    }

    /**
     * Return relation with Category Model
     *
     *
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    /**
     * Return relation with FinishedProduct Model
     *
     *
     */
    public function finishedProducts()
    {
        return $this->hasMany('App\Models\FinishedProduct');
    }

    /**
     * Return true if the category is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    /**
     * Return category short note
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
}
