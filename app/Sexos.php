<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sexos extends Model
{
    protected $table = "sexos";

    public function usuarios() {
        return $this->hasMany('App\Usuario');
    }
}
