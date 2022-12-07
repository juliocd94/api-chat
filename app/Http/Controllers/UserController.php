<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{



    /**
     * @OA\GET(
     *     path="/users",
     *     tags={"Usuarios"},
     *     summary="Todos los usuarios",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna: Retorna todos los usuarios registrados)"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * ) 
     */
    public function index()
    {
        return User::all();
    }

   
    public function store(Request $request)
    {
        //
    }

    
    public function show(User $user)
    {
        //
    }

    
    public function update(Request $request, User $user)
    {
        //
    }

    
    public function destroy(User $user)
    {
        //
    }


    /**
     * @OA\POST(
     *     path="/api/login-user",
     *     tags={"Usuarios"},
     *     summary="Inicio de sesión para usuarios registrados en el sistema",
     *     @OA\Parameter(
     *         description="Correo electronico del usuario",
     *         in="path",
     *         name="email",
     *         required=true,
     *         @OA\Schema(type="email"),
     *         @OA\Examples(example="Correo",  value="ejemplo@ejemplo.com", summary="Correo electronico.")
     *     ),
     *     @OA\Parameter(
     *         description="Contraseña",
     *         in="path",
     *         name="password",
     *         required=true,
     *         @OA\Schema(type="password"),
     *         @OA\Examples(example="Contraseña",  value="********", summary="Contraseña.")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna: Inicio de sesión, y Token de acceso Sanctum (requerido para el consumo de la API)"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * ) 
     */
    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ]);
        };
        $user = User::where('email', $request['email'])->with('Perfil')->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->perfil = $user->Perfil;
        $user->letra= strtoupper(substr($user->perfil->nombre,0,1));


        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}