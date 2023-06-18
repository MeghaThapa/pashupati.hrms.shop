<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class FabricStock extends Model
{
    use HasFactory;
    use Sluggable;
    protected $table = 'fabric_stock';
    protected $id = 'id';
    protected $fillable = [
        'name','slug', 'fabricgroup_id', 'status','gram','gross_wt','net_wt','meter','roll_no','loom_no',"is_laminated"
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
}
