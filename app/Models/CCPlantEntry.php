<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCPlantEntry extends Model
{
    use HasFactory;
    protected $table = "ccplantentry";
    protected $fillable = [
        "godam_id",
        "date",
        "date_np",
        "receipt_number",
        "dana_name_id",
        "dana_quantity",
        "remarks",
        "status"
    ];

    public function godam()
    {
        return $this->belongsTo(Godam::class);
    }

    public function danaName()
    {
        return $this->belongsTo(DanaName::class,'dana_name_id');
    }
}
