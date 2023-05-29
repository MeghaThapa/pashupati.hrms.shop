<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone_number', 'profile_picture', 'company_name', 'designation', 'address', 'status'
    ];

    /**
     * Return relation with Purchase Model
     *
     *
     */
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase');
    }

    /**
     * Return the supplier profile picture
     *
     * @var string
     */
    public function profilePic()
    {
        if (isset($this->profile_picture)) {
            return asset('img/suppliers/' . $this->profile_picture);
        }
    }

    /**
     * Return true if the supplier is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
}
