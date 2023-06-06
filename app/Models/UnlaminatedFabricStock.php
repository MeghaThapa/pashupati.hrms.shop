<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnlaminatedFabricStock extends Model
{
    use HasFactory;
    protected $table = "unlaminated_fabric_stocks";
    protected $id = 'id';
    protected $fillable = [
        'fabric_id','roll_no','gross_wt','net_wt','meter','average','gram','created_at','updated_at',
        'plantname_id','department_id','planttype_id','bill_number','bill_date'
    ];
    public function fabric(){
        return $this->belongsTo(Fabric::class);
    }
}
