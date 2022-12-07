<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Perfil;



class authController extends Controller
{

     public function user(){
        $user = auth()->user();
        return response()->json([
            'user' => $user
        ]);
    }

    public function register(Request $request){

        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);

    }

    public function login(Request $request){

        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ]);
        };

        $user = User::where('email', $request['email'])->with('Perfil')->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request){
        Auth::user()->tokens()->delete();
    }
}