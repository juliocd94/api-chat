<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mensaje_id')->nullable()->constrained('mensajes');
            $table->foreignId('archivo_id')->nullable()->constrained('archivos');

            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivo_mensajes');
    }
};
