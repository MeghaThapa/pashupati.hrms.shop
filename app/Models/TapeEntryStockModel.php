<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TapeEntry;

class TapeEntryStockModel extends Model
{
    use HasFactory;
    
    protected $table = 'tape_entry_stock';
    protected $id = "id";
    protected $fillable = [
        'id','tape_entry_id','toGodam_id','plantType_id','plantName_id',
        'shift_id','tape_type','tape_qty_in_kg','total_in_kg',
        'loading','running','bypass_wast','dana_in_kg',
        'created_at','updated_at',"cause"
    ];

    public function tapeentry(){
        return $this->belongsTo(TapeEntry::class);
    }

    public function getGodam()
    {
        return $this->belongsTo('App\Models\Godam','toGodam_id');
    }

    public function getPlantType()
    {
        return $this->belongsTo('App\Models\ProcessingStep','plantType_id');
    }

    public function getPlantName()
    {
        return $this->belongsTo('App\Models\ProcessingSubcat','plantName_id');
    }

    public function getShift()
    {
        return $this->belongsTo('App\Models\Shift','shift_id');
    }
}
