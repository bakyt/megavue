<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    protected $table='authUsers';
    use Notifiable, HasApiTokens, HasRoles;

    public function findForPassport($username) {
        return $this->where('username', $username)->first();
    }
    protected $guard_name='api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'username', 'password', 'new_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'new_password'
    ];
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->new_password;
    }
    public $timestamps = false;
    public function siteRole(){
        return $this->belongsTo('App\SiteRole', 'roleId');
    }
    public function position(){
        return $this->belongsTo('App\Position', 'post');
    }
}
