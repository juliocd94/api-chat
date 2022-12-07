<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;
    public function Recibidos(){
        return $this->hasMany(Recibido::class);
    }
    public function Leidos(){
        return $this->hasMany(Leido::class);
    }
    public function Chat(){
        return $this->belongsTo(Chat::class);
    }
}
