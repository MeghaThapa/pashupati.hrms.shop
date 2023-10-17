<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function itemsOfStoreins(){
        return $this->belongsTo(ItemsOfStorein::class,'items_of_storeins_id','id');
    }

    public function storeinDepartment(){
        return $this->belongsTo(StoreinDepartment::class,'storein_department_id','id');
    }
}
