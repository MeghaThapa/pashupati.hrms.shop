<?php

namespace App\Models;

use App\Models\CCPlantEntry;
use App\Models\DanaName;
use App\Models\ProcessingStep;
use App\Models\ProcessingSubcat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCPlantItems extends Model
{
    use HasFactory;
    protected $table = 'cc_plant_items';
    protected $fillable = [
        "cc_plant_entry_id" , 'planttype_id' , "plantname_id" , "dana_id" , "quantity"
    ];
    public function entry(){
        return $this->belongsTo(CCPlantEntry::class,"cc_plant_entry_id","id");
    }
    public function dananame(){
        return $this->belongsTo(DanaName::class,"dana_id","id");
    }
    public function planttype(){
        return $this->belongsTo(ProcessingStep::class,"planttype_id","id");
    }
    public function plantname(){
        return $this->belongsTo(ProcessingSubcat::class,"plantname_id","id");
    }
}
