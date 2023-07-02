<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagBrand extends Model
{
    use HasFactory;
    protected $table='bag_brands';
    protected $fillable=['name','group_id','status'];
}
