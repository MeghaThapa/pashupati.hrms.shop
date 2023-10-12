<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleFinalTripal extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no','bill_date','partyname_id','bill_for','lorry_no','do_no','gp_no','remarks','created_at','updated_at','status'
    ];

    public function getParty()
    {
        return $this->belongsTo('App\Models\Supplier','partyname_id');
    }

    public function getSaleList()
    {
        return $this->hasMany('App\Models\SaleFinalTripalList','salefinal_id','id');
    }
}
