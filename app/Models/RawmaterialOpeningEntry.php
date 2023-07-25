<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawmaterialOpeningEntry extends Model
{
    use HasFactory;
    protected $table='rawmaterial_opening_entries';
     public function godam()
    {
        return $this->belongsTo('App\Models\Godam', 'to_godam', "id");
    }

    public function items()
        {
            return $this->hasMany(RawmaterialOpeningItem::class, 'rawmaterial_opening_entry_id', 'id');
        }

}
