<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintedAndCuttedRollsEntry extends Model
{
    use HasFactory;
    protected $table = "printed_and_cutted_rolls_entry";
    protected $fillable = [
        "receipt_number" ,"date", "date_np" , "status"
    ];

    public function printingAndCuttingBagItems()
    {
        return $this->hasMany(PrintingAndCuttingBagItem::class, "printAndCutEntry_id", "id");
    }

}
