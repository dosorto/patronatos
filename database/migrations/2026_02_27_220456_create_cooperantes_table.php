<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperantes', function (Blueprint $table) {
            $table->id('id_cooperante');
            $table->unsignedBigInteger('id_organizacion');
            $table->string('nombre');
            $table->string('tipo_cooperante');
            $table->string('telefono');
            $table->string('direccion');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_organizacion')
                  ->references('id_organizacion')
                  ->on('organizacion'); // ← sin "es", así se llama la tabla
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperantes');
    }
};