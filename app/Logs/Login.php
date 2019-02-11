<?php

namespace App\Logs;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'logs_logins';
    public $timestamps = false;
}
