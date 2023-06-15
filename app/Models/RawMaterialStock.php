<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialStock extends Model
{
    use HasFactory;
    protected $table = 'raw_material_stocks';
    protected $fillable=[
        'dana_group_id',
        'dana_name_id',
        'quantity'
    ];
    public static function createRawMaterialStock($rawMaterialItemId , $godam_id)
    {
        $rawMaterialItem = RawMaterialItem::find($rawMaterialItemId);
        $rawMaterialStock = RawMaterialStock::where('godam_id', $godam_id)
        ->where('dana_name_id', $rawMaterialItem->dana_name_id)->first();
        // return $rawMaterialStock;
        if (!$rawMaterialStock) {
            $rawMaterialStockItem = new RawMaterialStock();
            $rawMaterialStockItem->dana_group_id  = $rawMaterialItem->dana_group_id;
            $rawMaterialStockItem->dana_name_id  = $rawMaterialItem->dana_name_id;
            $rawMaterialStockItem->quantity = $rawMaterialItem->quantity;
            $rawMaterialStockItem->godam_id = $godam_id;
            $rawMaterialStockItem->save();
        } else {
            $rawMaterialStock->dana_group_id  = $rawMaterialItem->dana_group_id;
            $rawMaterialStock->dana_name_id  = $rawMaterialItem->dana_name_id;
            $rawMaterialStock->quantity +=   $rawMaterialItem->quantity;
            $rawMaterialStock->save();
        }
    }
    public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
    public function danaGroup()
    {
        return $this->belongsTo('App\Models\DanaGroup', 'dana_group_id', "id");
    }
    // public function department()
    // {
    //     return $this->belongsTo('App\Models\Department', 'department_id', "id");
    // }
     public function godam()
    {
        return $this->belongsTo('App\Models\Godam', 'godam_id', "id");
    }


}
