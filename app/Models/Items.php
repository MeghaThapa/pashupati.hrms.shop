<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;

class Items extends Model
{
    protected $table = 'items';
    protected $fillable = ['item', 'pnumber','department_id' ,'category_id', 'status' ,'supplier_id'];


    /**
     * Return relation with Supplier Model
     *
     *
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /**
     * Return relation with Category Model
     *
     *
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
