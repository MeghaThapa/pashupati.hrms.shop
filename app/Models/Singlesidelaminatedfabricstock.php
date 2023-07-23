<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Singlesidelaminatedfabricstock extends Model
{
    use HasFactory;

    protected $fillable = [
        'roll_no','gross_wt','net_wt','meter','gram','created_at','updated_at',
        'plantname_id','department_id','planttype_id','bill_number','bill_date',"status",'name','slug','loom_no','average_wt','singlelamfabric_id','type_lam','status','fabric_id'
    ];

    public function getGodam()
    {
        return $this->belongsTo('App\Models\Godam','department_id');
    }

    public function getPlantType()
    {
        return $this->belongsTo('App\Models\ProcessingStep','planttype_id');
    }

    public function getPlantName()
    {
        return $this->belongsTo('App\Models\ProcessingSubcat','plantname_id');
    }

  
}
