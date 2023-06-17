<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FabricGroup;

class FabricLaminatedSentFSR extends Model
{
    use HasFactory;
    protected $table = "fabric_laminated_sent_fsr";
    protected $fillable = [
        'name', 'slug', 'fabricgroup_id','roll_no','gross_wt','net_wt','meter','average','gram','created_at','updated_at',
        'plantname_id','department_id','planttype_id','bill_number','bill_date',"loom_no"
    ];
    public function fabricgroup(){
        $this->belongsTo(FabricGroup::class,"fabricgroup_id","id");
    }
}
