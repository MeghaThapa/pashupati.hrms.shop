<?php

namespace App\Models;

use App\Models\Godam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapeEntryItemModel extends Model
{
    use HasFactory;
    protected $table = 'tape_entry_items';
    protected $id = "id";
    protected $fillable = [
        'id','tape_entry_id','toGodam_id','plantType_id','plantName_id',
        'shift_id','tape_type','tape_qty_in_kg','total_in_kg',
        'loading','running','bypass_wast','dana_in_kg',
        'created_at','updated_at'
    ];

    public function tapeentry(){
        return $this->belongsTo(TapeEntry::class,"tape_entry_id","id");
    }
    public function godam(){
        return $this->belongsTo(Godam::class,"toGodam_id","id");
    }

    public function getPlantType()
    {
        return $this->belongsTo('App\Models\ProcessingStep','plantType_id');
    }

    // Plant Name Ex. Koslite, lohia-1, etc
    public function getPlantName()
    {
        return $this->belongsTo('App\Models\ProcessingSubcat','plantName_id');
    }

    public function getShift()
    {
        return $this->belongsTo('App\Models\Shift','shift_id');
    }


}
