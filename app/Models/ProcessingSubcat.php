<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ProcessingSubcat extends Model
{
    use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'processing_subcats';
    protected $fillable = [
        'name', 'slug','status','processing_steps_id','department_id'
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
                'source' => 'name'
            ]
        ];
    }
    public function processingSteps()
    {
        return $this->belongsTo('App\Models\ProcessingStep','processing_steps_id','id');//, 'processing_steps_id ', 'id'
    }
    /**
     * Return true if the step is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }

    public function processStep(){
        return $this->belongsTo(processingStep::class,'processing_steps_id','id');
    }

    /**
     * Return step short note
     *
     * @return string
     */
    public function shortNote()
    {
        if (strlen($this->note) > 80) {
            return substr($this->note, 0, 80) . '...';
        }
        return $this->note;
    }
    public function department() {
        return $this->belongsTo(Department::class,'department_id','id');
    }
}
