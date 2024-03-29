<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wastages extends Model
{
    use HasFactory;
    protected $table = "wastages";
    protected $id = 'id';
    // protected $fillable = [
    //     'name','status','created_at','updated_at'
    // ];

    protected $guarded = [];

    public function wastageStock()
    {
        return $this->hasOne(WasteStock::class,'waste_id');
    }
}
