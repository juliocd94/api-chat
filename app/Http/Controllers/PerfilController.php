<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\User;
use App\Models\Recibido;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description"
 * )
 */

class PerfilController extends Controller
{
    
    public function index()
    {
        return Perfil::all();
    }

   
    /**
     * Display a listing of the resource.
     * Mostramos el listado de los regitros solicitados.
     * @return \Illuminate\Http\Response
     *
     * @OA\POST(
     *     path="/api/perfil",
     *     tags={"Perfiles de usuario"},
     *     summary="Crear perfil de usuario",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna los datos del perfil registrado."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * ) 
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $perfil = new Perfil();
        $perfil->user_id = $user->id;
        $perfil->nombre = $request->nombre;
        $perfil->save();
        return $perfil;
    }

    /**
     * @OA\GET(
     *     path="/api/perfil/{id}",
     *     tags={"Perfiles de usuario"},
     *     summary="Mostar toda la información realacionada a un perfil de usuario",
     *     @OA\Parameter(
     *         description="Solo requiere el id del perfil de usuario (no es el id del usuario)",
     *         in="path",
     *         name="enterprise",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="Ejemplo con rula local",  value="http://127.0.0.1:8000/api/perfils/1", summary="Perfil ID.")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna: chats, mensajes, mensajes sin leer, mensajes leidos."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * ) 
     */
    public function show(Perfil $perfil)
    {
        $perfil->chat_perfils = $this->chatPerfils($perfil);
        return $perfil;
    }


    public function chatPerfils(Perfil $perfil)
    {
        $chat_perfils = $perfil->ChatPerfils()->with(['Chat'])->get();
        $lista = [];
        foreach($chat_perfils as $c){
            $chat = $c->chat;
            $propietarios = $chat->ChatPerfils()->where('perfil_id','!=',$perfil->id)->get();
            if($propietarios->count()>0){
                $c->destino = $propietarios->first();
                $c->destino->perfil = $c->destino->Perfil;
                $c->destino->letra= strtoupper(substr($c->destino->Perfil->nombre,0,1));
                $c->letra= strtoupper(substr($perfil->nombre,0,1));
            }
            $c->mensajes = $this->mensajes($chat);
            $c->ultimo = $c->mensajes->count()>0?[$c->mensajes->first()]:[];
            $c->sinLeer = $this->mensajesSinLeer($chat,$c->id);
            $c->order_id = $c->created_at;
            if($c->mensajes->count()>0){
                $item = $c->mensajes->first();
                $c->order_id = $item->created_at;
            }
            $this->recibirMensajes($chat,$c->id);
            $lista[] = $c;
        }
        // $perfil->chat_perfils = collect($lista)->sortBy('order','desc');
        $chat_perfils = collect($lista)->sortByDesc('order_id');
        $lista_2 = [];
        foreach($chat_perfils as $chat){
            $lista_2[] = $chat;
        }
        return $lista_2;
    }


    public function mensajes(Chat $chat)
    {
        $mensajes =$chat->Mensajes()->orderBy('id','desc')->take(20)->get();
        foreach($mensajes as $m){
            $m->leido = $m->Leidos()->get()->count();
            $m->recibido = $m->Recibidos()->get()->count();
            $m->hora = $m->created_at->format("h:i a");
        }
        return $mensajes;
    }
    public function recibirMensajes(Chat $chat,$id)
    {
        $mensajes = $this->chat($chat);
        foreach($mensajes as $m){
            if($m->chat_perfil_id!=$id){

                $recibido = new Recibido();
                $recibido->chat_perfil_id = $id;
                $recibido->mensaje_id = $m->id;
                $recibido->save();
            }
        }
    }


    public function mensajesSinLeer(Chat $chat,$id)
    {
        $mensajes = $this->chat($chat);
        return $mensajes->where('leido',0)->where('chat_perfil_id','!=',$id)->count();
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


    public function update(Request $request, Perfil $perfil)
    {
        //
    }

    
    public function destroy(Perfil $perfil)
    {
        //
    }
}
