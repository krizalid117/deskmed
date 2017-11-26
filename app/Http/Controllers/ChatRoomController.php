<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\ChatRoom;
use App\Events\ChatRoomNewMessage;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    public function getMessages($uuid) {
        $chatRoom = ChatRoom::find($uuid);

        $messages = $chatRoom->messages()->get();

        $mensajes = [];

        foreach ($messages as $msg) {
            $time = explode(" ", $msg->created_at)[1];

            $usuario = $msg->usuario()->first();

            $mensajes[] = [
                "isSender" => $usuario->id === Auth::user()->id,
                "time" => $time,
                "message" => $msg->message_text,
                "image" => $usuario->getProfileImage(),
                "user" => $usuario->nombres . " " . $usuario->apellidos
            ];
        }

        return response()->json([
            "id_hora" => $chatRoom->horaMedica()->first()->id,
            "messages" => $mensajes,
        ]);
    }

    public function getChatRooms() {
        $user = Auth::user();
        $chatrooms = [];

        $datos = [
            "chatrooms" => []
        ];

        $horas = ($user->id_tipo_usuario === 2 ? $user->horasAsDoctor() : $user->horasAsPaciente())->get();

        foreach ($horas as $hora) {
            $chatroom = ChatRoom::where('hora_id', $hora->id)->first();

            if (!is_null($chatroom)) {

                $receiver = (($user->id_tipo_usuario === 2) ? $hora->paciente() : $hora->medico())->first();

                $chatrooms[] = [
//                    "chatroom" =>  ChatRoom::where('hora_id', $hora->id)->first()->toArray(),
                    "uuid" => $chatroom->uuid,
                    "activa" => $chatroom->activa,
                    "receiver" => [
                        "id" => $receiver->id,
                        "image" => $receiver->getProfileImage(),
                        "nombres" => $receiver->nombres,
                        "apellidos" => $receiver->apellidos,
                        "isDoctor" => ($receiver->id_tipo_usuario === 2),
                    ],
                    "hora" => [
                        "id" => $hora->id,
                        "nombre" => $hora->nombre,
                        "fecha" => implode("-", array_reverse(explode("-", $hora->fecha))),
                        "hora_inicio" => $hora->hora_inicio,
                        "hora_termino" => $hora->hora_termino,
                        "color" => $hora->hex_color,
                    ]
                ];
            }
        }

        $datos["chatrooms"] = $chatrooms;

        return response()->json($datos);
    }

    public function sendMessage(Request $request) {
        $datos = [
            "error" => false,
        ];

        $chatroom = ChatRoom::find($request["uuid_chatroom"]);

        $create = $chatroom->messages()->create([
            "message_text" => $request["message"],
            "id_usuario" => Auth::user()->id,
            "id_tipo_usuario" => Auth::user()->id_tipo_usuario,
        ]);

        if (!$create) {
            $datos["error"] = true;
        }
        else {
            try {
                broadcast(new ChatRoomNewMessage($chatroom->horaMedica()->first()->id))->toOthers();
            }
            catch (BroadcastException $e) {
                $datos["error"] = true;
                $datos["mensaje"] = "Broadcast exception (pusher): " . $e->getMessage();
            }
        }

        return response()->json($datos);
    }
}
