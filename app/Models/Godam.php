<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godam extends Model
{
    use HasFactory;
    protected  $id = "id";
    protected $table = "godam";
    protected $fillable = [
        'name','status'
    ];

     public function isActive()
    {
        return $this->status == 'active' ? true : false;
    }
}
