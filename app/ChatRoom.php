<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $table = "chat_rooms";

    public function horaMedica() {
        return $this->hasMany('App\HoraMedica');
    }
}
