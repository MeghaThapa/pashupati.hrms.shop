<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wastages;

class WasteStock extends Model
{
    use HasFactory;
    protected $table = "wastages_stock";
    protected $id = "id";
    // protected $fillable = [
    //     'godam_id','waste_id','quantity_in_kg','created_at','updated_at'
    // ];

    protected $guarded = [];

    public function wastage(){
        return  $this->belongsTo(Wastages::class,'waste_id', "id");
    }

    public function godam()
    {
        return $this->belongsTo('App\Models\Godam', 'godam_id', "id");
    }
}
