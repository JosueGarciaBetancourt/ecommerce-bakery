<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opinions', function (Blueprint $table) {
            $table->id(); // Crea una columna 'id' como llave primaria
            $table->unsignedBigInteger('usuario_id'); // Asegúrate de que sea 'unsignedBigInteger'
            $table->text('opiniontext');
            $table->integer('estrellas');
            $table->timestamps();
    
            // Define la clave foránea
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opiniones');
    }
};
