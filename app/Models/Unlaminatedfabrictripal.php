<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unlaminatedfabrictripal extends Model
{
    use HasFactory;

    protected $fillable = [
        'fabric_id','roll_no','gross_wt','net_wt','meter','average','gram','created_at','updated_at','bill_id',
        'plantname_id','department_id','planttype_id','bill_number','bill_date',"status"
    ];
    public function fabric(){
        return $this->belongsTo(Fabric::class,"fabric_id","id");
    }
}
