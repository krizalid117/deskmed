<?php

namespace App\Providers;

use App\ChatRoom;
use App\HoraMedica;
use App\Http\Controllers\GlobalController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        /*
         * Authenticate the user's personal channel...
         */
//        Broadcast::channel('App.User.*', function ($user, $userId) {
//            return (int) $user->id === (int) $userId;
//        });

        Broadcast::channel('chatroom.*', function ($user, $idHora) {
            $hora = HoraMedica::find($idHora);

            GlobalController::log("--------------------");

            GlobalController::log("uuid chatroom: " . ChatRoom::where('hora_id', $hora->id)->first()->uuid);

            GlobalController::log("id_hora: " . $hora->id);

            GlobalController::log("id_medico: " . $hora->id_medico);
            GlobalController::log("id_paciente: " . $hora->id_paciente);

            GlobalController::log("RETURN: " . ((int) $user->id === (int) $hora->id_medico) || ((int) $user->id === (int) $hora->id_paciente) ? "true" : "false");

            GlobalController::log("--------------------");

            return (((int) $user->id === (int) $hora->id_medico) || ((int) $user->id === (int) $hora->id_paciente));
        });
    }
}
