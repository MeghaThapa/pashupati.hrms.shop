<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BswFabSendcurtxReceivpatchvalveItems extends Model
{
    use HasFactory;
    protected $table="bsw_fab_sendcurtx_receivpatchvalve_items";
    public function fabric()
    {
        return $this->belongsTo(Fabric::class, 'fabric_id', 'id');
    }
    public function printedfabric()
    {
        return $this->belongsTo(PrintedFabric::class, 'printed_fabric_id', 'id');
    }
}
