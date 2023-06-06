<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class NonWovenFabric extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'slug', 'gsm','color', 'status'
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
