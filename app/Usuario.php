<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    protected $table = "usuarios";
}
