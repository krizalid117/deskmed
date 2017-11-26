<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    public $incrementing = false;

    protected $table = "chat_rooms";

    protected $primaryKey = 'uuid';

    public function horaMedica() {
        return $this->belongsTo('App\HoraMedica', 'hora_id');
    }

    public function messages() {
        return $this->hasMany('App\ChatMessage', 'uuid_chat_room');
    }
}
