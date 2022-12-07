<?php

namespace App\Http\Controllers;

use App\Models\ChatPerfil;
use App\Models\Leido;
use App\Models\Chat;
use App\Models\Recibido;
use Illuminate\Http\Request;

class ChatPerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return ChatPerfil::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChatPerfil  $chatPerfil
     * @return \Illuminate\Http\Response
     */
    public function show(ChatPerfil $chatPerfil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChatPerfil  $chatPerfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChatPerfil $chatPerfil)
    {
        //
    }
    public function leer(Request $request, ChatPerfil $chatPerfil)
    {
        $chat = $chatPerfil->chat;
        $this->leerMensajes($chat,$chatPerfil->id);
        return $chatPerfil;
    }
    public function leerMensajes(Chat $chat,$id)
    {
        $mensajes = $this->chat($chat);

        foreach($mensajes as $m){
            if($m->chat_perfil_id!=$id && $m->leido==0){
                $leido = new Leido();
                $leido->chat_perfil_id = $id;
                $leido->mensaje_id = $m->id;
                $leido->save();
            }
        }
    }
    public function chat(Chat $chat)
    {
        $mensajes =$chat->Mensajes()->orderBy('id','desc')->take(50)->get();
        foreach($mensajes as $m){
            $m->leido = $m->Leidos()->get()->count();
            $m->recibido = $m->Recibidos()->get()->count();
        }
        return $mensajes;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChatPerfil  $chatPerfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChatPerfil $chatPerfil)
    {
        //
    }
}
