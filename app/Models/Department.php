<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $id = "id";
    protected $table='department';
    protected $fillable = [
        'department', 'slug', 'status', 'id'
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
                'source' => 'department'
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
