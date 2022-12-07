<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatPerfil;
use App\Models\Mensaje;
use App\Models\User;
use Illuminate\Http\Request;


class ChatController extends Controller
{
   
    public function index()
    {
        return Chat::all();
    }

    /**
     * @OA\POST(
     *     path="/chats",
     *     tags={"Chats"},
     *     summary="Iniciar un chat con un usuario registrado",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna los datos de un nuevo chat)"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * ) 
     */
    public function store(Request $request)
    {
        $user = User::where('email',$request->email)->get();
        if($user->count()>0){
            $user = $user->first();
            $chat = new Chat();
            $chat->tipo =1;
            $chat->save();
            $user->perfil = $user->Perfil;
            $chatPerfil1 = new ChatPerfil();
            $chatPerfil1->perfil_id = $user->perfil->id;
            $chatPerfil1->chat_id = $chat->id;
            $chatPerfil1->save();
            $chatPerfil2 = new ChatPerfil();
            $chatPerfil2->perfil_id = $request->perfil_id;
            $chatPerfil2->chat_id = $chat->id;
            $chatPerfil2->save();
            $mensaje = new Mensaje();
            $mensaje->chat_perfil_id = $chatPerfil2->id;
            $mensaje->mensaje = $request->mensaje;
            $mensaje->chat_id = $chat->id;
            $mensaje->save();
            return $chat;
        }
    }

    
    public function show(Chat $chat)
    {

        $mensajes =$chat->Mensajes()->orderBy('id','desc')->take(20)->get();
        foreach($mensajes as $m){
            $m->leido = $m->Leidos()->get()->count();
            $m->recibido = $m->Recibidos()->get()->count();
            $m->hora = $m->created_at->format("h:i a");
        }
        return $mensajes;
    }

   
    public function update(Request $request, Chat $chat)
    {
        //
    }

   
    public function destroy(Chat $chat)
    {
        //
    }
}
