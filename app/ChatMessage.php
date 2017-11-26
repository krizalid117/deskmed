<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = "chat_messages";

    protected $fillable = [
        "message_text",
        "id_usuario",
        "id_tipo_usuario",
    ];

    public function chatRoom() {
        return $this->belongsTo('App\ChatRoom', 'uuid_chat_room');
    }

    public function usuario() {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }
}
