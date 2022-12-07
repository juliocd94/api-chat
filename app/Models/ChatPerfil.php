<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatPerfil extends Model
{
    use HasFactory;
    public function Chat(){
        return $this->belongsTo(Chat::class);
    }
    public function Perfil(){
        return $this->belongsTo(Perfil::class);
    }
}
