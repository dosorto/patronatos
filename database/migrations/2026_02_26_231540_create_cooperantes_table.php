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
            $table->foreign('id_organizacion')->references('id_organizacion')->on('organizacion');
            $table->string('nombre');
            $table->string('tipo_cooperante');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperantes');
    }
};