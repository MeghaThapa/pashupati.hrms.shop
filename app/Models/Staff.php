<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone_number', 'profile_picture', 'department', 'designation', 'address', 'status'
    ];

    /**
     * Return relation with ProcessingStepStaff Model
     *
     *
     */
    public function processingStaff()
    {
        return $this->hasMany('App\ProcessingStepStaff');
    }


    /**
     * Return the staff profile picture
     *
     * @var string
     */
    public function profilePic()
    {
        if (isset($this->profile_picture)) {
            return asset('img/staff/' . $this->profile_picture);
        }
    }

    /**
     * Return true if the staff is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
}
