<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessingCategoryAndSubsModel extends Model
{
    use HasFactory;
    protected $id = 'id';
    protected $table = 'processing_categories_and_subcategories';
    protected $fillable = [
        'name','status','slug','created_at','updated_at'
    ];
}
