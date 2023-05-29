<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $table='tax';
    protected $fillable = [
        'tax_type', 'slug', 'percentage', 'id', 'status'
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
                'source' => 'tax_type'
            ]
        ];
    }

    /**
     * Return true if the unit is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    /**
     * Return unit short note
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
