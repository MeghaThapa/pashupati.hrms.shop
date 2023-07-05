<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number', 'bill_date', 'pipe_cutting', 'bd_wastage','other_wastage','total_wastage','total_netweight','total_meter','total_weightinkg','total_wastageinpercent','run_loom','wrapping'
    ];
}
