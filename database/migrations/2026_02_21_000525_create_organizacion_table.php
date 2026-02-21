<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizacion', function (Blueprint $table) {
            $table->id('id_organizacion');
            $table->unsignedBigInteger('id_tipo_organizacion');
            $table->unsignedBigInteger('id_municipio');
            $table->unsignedBigInteger('id_departamento');
            $table->string('direccion');
            $table->string('nombre');
            $table->string('rtn');
            $table->string('telefono');
            $table->date('fecha_creacion');
            $table->string('estado')->default('Activo');
            $table->timestamps();

            $table->foreign('id_tipo_organizacion')->references('id_tipo_organizacion')->on('tipo_organizacion');
            $table->foreign('id_municipio')->references('id')->on('municipios');
            $table->foreign('id_departamento')->references('id')->on('departamentos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizacion');
    }
};