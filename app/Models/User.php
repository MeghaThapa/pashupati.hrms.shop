<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_picture', 'role', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return user profile picture
     *
     * @return string
     */
    public function profilePic()
    {
        if (!empty($this->profile_picture)) {
            return asset('img/profile/' . $this->profile_picture);
        }
        return asset('img/boy.png');
    }

    /**
     * Return true if the user is an admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role == 1 ? true : false;
    }

    /**
     * Return true if the user is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status == 1 ? true : false;
    }
}
