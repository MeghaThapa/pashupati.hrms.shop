<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fabric;
use App\Models\FabricTemporaryForLam;

class LaminatedFabric extends Model
{
    use HasFactory;
    protected $table  = "fabric_laminated";
    protected $id ="id";
    protected $fillable = [
        'lam_fabric_id','roll_no','gross_wt','net_wt','meter','average','gram','created_at','updated_at',
        'plantname_id','department_id','planttype_id','bill_number','bill_date'
    ];
    public function fabric(){
        return $this->belongsTo(Fabric::class,"fabric_id","id");
    }

    public function lamfabric(){
        return $this->belongsTo(FabricTemporaryForLam::class,"lam_fabric_id","id");
    }
}
