<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = "stocks";
    protected $id = "id";
    protected $fillable = ['department_id','category_id','item_id','size','quantity','unit','avg_price','total_amount'];

    public static function createStock($storeinItem)
    {
        $stock = Stock::where('item_id', $storeinItem->storein_item_id )
        ->where('department_id', $storeinItem->department_id)
        ->where('unit',$storeinItem->unit_id)
        ->where('size',$storeinItem->size_id)
        ->first();

        if (!$stock) {
           dd('here');
            $stock = new Stock();
            $stock->quantity = $storeinItem->quantity;
            $stock->avg_price = round($storeinItem->price,2);
            $stock->total_amount = round($storeinItem->total_amount,2);
        } else {
             dd('to add stock');
            $stock->quantity += $storeinItem->quantity;
            $total = $stock->total_amount + $storeinItem->total_amount;
            $stock->avg_price = round($total / $stock->quantity,2);
            $stock->total_amount  =  round($stock->quantity * $stock->avg_price,2);
        }
        $stock->item_id = $storeinItem->storein_item_id;
        $stock->size = $storeinItem->size_id;
        $stock->unit = $storeinItem->unit_id;
        $stock->department_id = $storeinItem->department_id;
        $stock->category_id = $storeinItem->storein_category_id;
        $stock->save();
    }

    public static function updateStock($old_item_id, $old_item_total_amount, $old_item_quantity, $newRequest, $new_total_amount)
    {
        if ($old_item_id == $newRequest->product_id) {
            $stock = Stock::where('item_id', $old_item_id)->first();
            $stock->quantity = $stock->quantity - $old_item_quantity;
            $stock->total_amount = $stock->total_amount - $old_item_total_amount;
            $stock->quantity = $stock->quantity + $newRequest->quantity;
            $stock->total_amount =  $stock->total_amount + $new_total_amount;
            $stock->avg_price = $stock->total_amount / $stock->quantity;
            $stock->save();
        }
    }
    public static function deleteStock($item_id, $old_item_total_amount, $old_item_quantity)
    {
        $stock = Stock::where('item_id', $item_id)->first();

        $stock->quantity -= $old_item_quantity;
        if ($stock->quantity <= 0) {
            $stock->delete();
            return true;
        }
        $stock->total_amount -= $old_item_total_amount;
        $stock->avg_price = round($stock->total_amount / $stock->quantity,2);
        $stock->save();
    }
    // relate stock with category,item,department
    public function category()
    {
        return $this->belongsTo(StoreinCategory::class, 'category_id', "id");
    }
    public function item()
    {
        return $this->belongsTo(ItemsOfStorein::class, 'item_id', "id");
    }
    public function sizes()
    {
        return $this->belongsTo(Size::class, 'size', "id");
    }
      public function units()
    {
        return $this->belongsTo(Unit::class, 'unit', "id");
    }

    public function department()
    {
        return $this->belongsTo(StoreinDepartment::class, 'department_id', 'id');
    }
}
