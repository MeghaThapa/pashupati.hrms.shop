<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoLoadItemStock extends Model
{
    use HasFactory;

    protected $table = 'autoload_items_stock';


    protected $fillable = [
        'from_godam_id','plant_type_id','plant_name_id','shift_id','dana_group_id','dana_name_id','quantity'
    ];


    public function danaName()
    {
        return $this->belongsTo('App\Models\DanaName', 'dana_name_id', "id");
    }
    public function danaGroup()
    {
        return $this->belongsTo('App\Models\DanaGroup', 'dana_group_id', "id");
    }
    public function fromGodam()
    {
        return $this->belongsTo('App\Models\Godam', 'from_godam_id', 'id');
    }
    public function shift()
    {
        return $this->belongsTo('App\Models\Shift', 'shift_id', 'id');
    }
    public function plantType()
    {
        return $this->belongsTo('App\Models\ProcessingStep', 'plant_type_id', 'id');
    }
    public function plantName()
    {
        return $this->belongsTo('App\Models\ProcessingSubcat', 'plant_name_id', 'id');
    }
    public function autoload()
    {
        return $this->belongsTo('App\Models\AutoLoad', 'autoload_id', 'id');
    }
}
