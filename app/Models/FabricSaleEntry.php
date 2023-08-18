<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricSaleEntry extends Model
{
    use HasFactory;
    protected $table = "fabric_sale_entry";
    protected $fillable = [
        'bill_no','bill_date','partyname_id','bill_for','lorry_no','do_no','gp_no','remarks',"status",'created_at','updated_at'
    ];
    public function getParty()
    {
        return $this->belongsTo('App\Models\Supplier','partyname_id');
    }
}
