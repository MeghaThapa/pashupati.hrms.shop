<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Items;
use App\Models\Setupstoreout;

class Storeout extends Model
{
    protected $table = 'admin_storeout';
    protected $fillable = [];

    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
   
}
