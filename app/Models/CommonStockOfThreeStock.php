<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonStockOfThreeStock extends Model
{
    use HasFactory;
    protected $table ='common_stock_of_three_stock';

    public function curtexToPatchValFabric()
    {
        return $this->belongsTo(CurtexToPatchValFabric::class, 'curtexToPatchValFabric_id', 'id');
    }
}
