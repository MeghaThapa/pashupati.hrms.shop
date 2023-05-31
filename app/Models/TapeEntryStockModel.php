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
        'id','tape_entry_id','togodam_id','planttype_id','plantname_id',
        'shift_id','tape_type','tape_qty_in_kg','total_in_kg',
        'loading','running','bypass_wast','dana_in_kg',
        'created_at','updated_at'
    ];

    public function tapeentry(){
        return $this->belongsTo(TapeEntry::class);
    }
}
