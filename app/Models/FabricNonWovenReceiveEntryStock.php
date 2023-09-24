<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricNonWovenReceiveEntryStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'receive_date', 'receive_no', 'fabric_roll', 'fabric_gsm','fabric_name','fabric_color','length','gross_weight','net_weight','nonfabric_id','status','bill_id','godam_id','status_type'
    ];

    
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    public function getGodam()
    {
        return $this->belongsTo('App\Models\Godam','godam_id');
    }

    public function getPlantType()
    {
        return $this->belongsTo('App\Models\ProcessingStep','planttype_id');
    }

    public function getPlantName()
    {
        return $this->belongsTo('App\Models\ProcessingSubcat','plantname_id');
    }

    public function getShift()
    {
        return $this->belongsTo('App\Models\Shift','shift_id');
    }
}
