<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricGodamList extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no', 'bill_date', 'fabricgodam_id','fromgodam_id','togodam_id','name','slug','roll','net_wt','stock_id'
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