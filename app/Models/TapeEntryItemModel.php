<?php

namespace App\Models;

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
        return $this->belongsTo(TapeEntry::class);
    }

 
}
