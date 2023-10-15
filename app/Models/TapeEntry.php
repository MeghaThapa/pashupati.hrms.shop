<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapeEntry extends Model
{
    use HasFactory;
    protected $table = "tape_entry";
    protected $id = 'id';
    protected $fillable = [
        'receipt_number','tape_entry_date','status','created_at','updated_at'
    ];

    public function tapeEntryItems(){
        return $this->hasMany(TapeEntryItemModel::class,'tape_entry_id');
    }
}
