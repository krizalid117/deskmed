<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\CanResetPassword;

class Usuario extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    protected $table = "usuarios";
}
