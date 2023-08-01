<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalTripalStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'fabric_id','doublefabric_id','roll_no','gross_wt','net_wt','meter','gram','created_at','updated_at',
        'plantname_id','department_id','planttype_id','bill_number','bill_date','name','slug','loom_no','average_wt','status','date_en','date_np','finaltripal_id','finaltripalname_id','date_en','date_np','gsm'
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
