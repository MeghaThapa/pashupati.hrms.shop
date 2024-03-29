<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Singlesidelaminatedfabric extends Model
{
    use HasFactory;

    protected $fillable = [
        'fabric_id','roll_no','gross_wt','net_wt','meter','gram','created_at','updated_at',
        'plantname_id','department_id','planttype_id','bill_number','bill_date',"status",'name','slug','loom_no','average_wt','type_lam','status','bill_id'
    ];

    public function fabric(){
        return $this->belongsTo(Fabric::class,"fabric_id","id");
    }
}
