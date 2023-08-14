<?php

namespace App\Models;

use App\Models\FabricSendAndReceiveUnlaminatedFabric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Godam;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use App\Models\Shift;

class FabricSendAndReceiveEntry extends Model
{
    use HasFactory;
    protected $table = "fabric_send_and_receive_entry";

    protected $fillable = [
        "godam_id" ,"planttype_id", "plantname_id" , "remarks" ,"shift_id" , "bill_number" , "bill_date" , "bill_date_np"
    ];
    
    public function getgodam()
    {
        return $this->belongsTo(Godam::class, "godam_id");
    }
    
    public function getplanttype()
    {
        return $this->belongsTo(ProcessingStep::class, "planttype_id");
    }
    
    public function getplantname()
    {
        return $this->belongsTo(ProcessingSubcat::class, "plantname_id");
    }
    
    public function getshift()
    {
        return $this->belongsTo(Shift::class, "shift_id");
    }

    public function getunlaminatedfabric(){
        return $this->hasMany(FabricSendAndReceiveUnlaminatedFabric::class,"fsr_entry_id","id");
    }
    
}
