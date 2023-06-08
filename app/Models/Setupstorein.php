<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Setupstorein extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table='storein_types';
    protected $fillable = [
        'name', 'slug', 'code', 'note', 'status'
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
     * Return true if the size is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    // return short size note
    public function shortNote()
    {
        if (strlen($this->note) > 80) {
            return substr($this->note, 0, 80) . '...';
        }
        return $this->note;
    }
}
