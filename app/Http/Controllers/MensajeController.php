<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\Chat;
use Illuminate\Http\Request;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mensaje = new Mensaje();
        $mensaje->mensaje = $request->mensaje;
        $mensaje->chat_id = $request->chat_id;
        $mensaje->chat_perfil_id = $request->chat_perfil_id;
        $mensaje->save();
        return $this->mensajes($mensaje);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mensaje  $mensaje
     * @return \Illuminate\Http\Response
     */
    public function show(Mensaje $mensaje)
    {
        //
    }
    public function mensajes(Mensaje $mensaje)
    {
        $chat = $mensaje->Chat;
        $mensajes =$chat->Mensajes()->orderBy('id','desc')->take(20)->get();
        foreach($mensajes as $m){
            $m->leido = $m->Leidos()->get()->count();
            $m->recibido = $m->Recibidos()->get()->count();
            $m->hora = $m->created_at->format("h:i a");
        }
        return $mensajes;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mensaje  $mensaje
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mensaje $mensaje)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mensaje  $mensaje
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mensaje $mensaje)
    {
        //
    }
}
