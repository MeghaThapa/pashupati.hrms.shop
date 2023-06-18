<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricTemporaryForLam extends Model
{
    use HasFactory;
    protected $table = "fabric_temporary_for_lamination";
    protected $id = "id";
    
    protected $fillable = [
        'name', 'slug', 'fabricgroup_id', 'status','gram','gross_wt','average_wt','net_wt','meter','roll_no','loom_no'
    ];

    public function fabric(){
        return $this->belongsTo(Fabric::class,"fabric_id","id");
    }
}
