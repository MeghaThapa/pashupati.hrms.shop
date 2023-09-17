<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WastageSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no','bill_date','partyname_id','lorry_no','do_no','gp_no','created_at','updated_at','status'
    ];

    public function getParty()
    {
        return $this->belongsTo('App\Models\Supplier','partyname_id');
    }
}
