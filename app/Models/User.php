<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    protected $table = 'users';
    use HasApiTokens, HasFactory, Notifiable;

   
    protected $fillable = [
        'email',
        'password',
    ];

    
    protected $hidden = [
        'password',
    ];

    
    protected $casts = [
    ];


    public function Perfil(){
        return $this->hasOne(Perfil::class);
    }
}