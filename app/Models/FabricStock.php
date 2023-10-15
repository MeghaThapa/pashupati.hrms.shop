<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricStock extends Model
{
    use HasFactory,Sluggable, SoftDeletes;
    protected $table = 'fabric_stock';
    protected $id = 'id';
    // protected $fillable = [
    //     'name','slug', 'fabricgroup_id', 'status','average_wt','gram_wt','gross_wt','net_wt','meter','roll_no','loom_no','is_laminated','godam_id','bill_no','fabric_id','date_np'
    // ];

    protected $guarded = [];

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

    public function godam()
    {
        return $this->belongsTo('App\Models\Godam','godam_id');
    }

    public function fabricgroup()
    {
        return $this->belongsTo(FabricGroup::class, 'fabricgroup_id', 'id');
    }

    public function fabric()
    {
        return $this->belongsTo(Fabric::class, 'fabric_id', 'id');
    }

    public function getGodam()
    {
        return $this->belongsTo('App\Models\Godam','godam_id');
    }

    public function getShift()
    {
        return $this->belongsTo('App\Models\Shift','shift_id');
    }
}
