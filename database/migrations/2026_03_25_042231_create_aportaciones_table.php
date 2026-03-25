<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('aportaciones', function (Blueprint $table) {
            $table->id('id_aportacion');

        $table->foreignId('id_miembro')
            ->constrained('miembros') // Laravel asume "id"
            ->cascadeOnDelete();

        $table->foreignId('id_proyecto')
            ->constrained('proyectos') // Laravel asume "id"
            ->cascadeOnDelete();

            $table->decimal('monto', 10, 2);
            $table->date('fecha_aportacion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('aportaciones');
    }
};