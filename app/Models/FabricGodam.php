<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricGodam extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no', 'bill_date', 'remarks','fromgodam_id','togodam_id','status'
    ];

    public function getFromGodam()
    {
        return $this->belongsTo('App\Models\Godam','fromgodam_id');
    }

    public function getToGodam()
    {
        return $this->belongsTo('App\Models\Godam','togodam_id');
    }
}
