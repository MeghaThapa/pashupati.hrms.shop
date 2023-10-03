<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricTransferEntryForBag extends Model
{
    use HasFactory;
    protected $table = "bag_fabric_entry";
    protected $fillable = [
        "receipt_number" , "receipt_date","receipt_date_np","status"
    ];
}
