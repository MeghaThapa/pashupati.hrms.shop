<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonwovenBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'plantname_id','godam_id','planttype_id','shift_id','bill_no','bill_date','bill_date_en',
        'created_at','updated_at','status'
    ];

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
