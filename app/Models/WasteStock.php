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
    protected $fillable = [
        'department_id','waste_id','quantity_in_kg','created_at','updated_at'
    ];
    public function wastage(){
        return  $this->belongsTo(Wastages::class);
    }  
}
