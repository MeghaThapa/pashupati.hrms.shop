<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class FabricGroup extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'slug', 'status'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
}
