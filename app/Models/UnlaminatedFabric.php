<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fabric;

class UnlaminatedFabric extends Model
{
    use HasFactory;
    protected $table = "unlaminated_fabric";
    protected $id = 'id';
    protected $fillable = [
        'fabric_id','roll_no','gross_wt','net_wt','meter','average','gram','created_at','updated_at',
        'plantname_id','department_id','planttype_id','bill_number','bill_date'
    ];
    public function fabric(){
        return $this->belongsTo(Fabric::class);
    }
}
