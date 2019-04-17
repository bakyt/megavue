<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    public function findForPassport($username) {
        return $this->where('phone', $username)->first();
    }

    protected $guard_name = 'api';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function stores(){
        return $this->belongsToMany('App\Store', 'user_store');
    }
}
