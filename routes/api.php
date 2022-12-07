<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login-user','UserController@login');
Route::post('/leerMensajes/{chatPerfil}','ChatPerfilController@leer');

Route::apiResource('/users','UserController');
Route::apiResource('/chats','ChatController');
Route::apiResource('/mensajes','MensajeController');
Route::apiResource('/chatPerfils','ChatPerfilController');
Route::apiResource('/perfils','PerfilController');

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('user', [AuthController::class, 'user']);
});