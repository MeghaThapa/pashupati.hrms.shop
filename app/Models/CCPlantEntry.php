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
        "remarks",
        "status"
    ];
}
