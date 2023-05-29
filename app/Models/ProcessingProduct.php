<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ProcessingProduct extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'processing_code', 'start_date', 'end_date', 'processing_image', 'note', 'status'
    ];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'processing_code'
            ]
        ];
    }


    // return processing code
    public function processingCode($prefix)
    {
        return $prefix . $this->processing_code;
    }

    /**
     * Return relation with Purchase Model
     *
     *
     */
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'purchase_id');
    }


    /**
     * Return relation with FinishedProduct Model
     *
     *
     */
    public function finishedProducts()
    {
        return $this->hasMany('App\Models\FinishedProduct', 'porcessing_pro_id');
    }

    /**
     * Return pivoat realtion
     *
     *
     */
    public function selectedStaff()
    {
        return $this->belongsToMany('App\Models\Staff')
            ->withPivot('processing_step_id')
            ->join('processing_steps', 'processing_steps.id', '=', 'processing_step_id')
            ->select('staff.*', 'processing_product_staff.processing_step_id as selected_step_id', 'processing_steps.name as pivot_step_name', 'processing_steps.id as pivot_step_id');
    }

    /**
     * Return the processing product attached picture
     *
     * @var string
     */
    public function imagepath()
    {
        if (isset($this->processing_image)) {
            return asset('img/processing/' . $this->processing_image);
        }
    }

    /**
     * Return true if the processing product is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
}
