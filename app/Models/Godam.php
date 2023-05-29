<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Godam extends Model
{
    use HasFactory;
    protected  $id = "id";
    protected $table = "godams";
    protected $fillable = [
        'name','status','created_at','updated_at'
    ];
}
