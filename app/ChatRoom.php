<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $table = "chat_rooms";

    protected $primaryKey = 'uuid';

    public function horaMedica() {
        return $this->hasMany('App\HoraMedica');
    }
}
